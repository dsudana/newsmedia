<?php

namespace App\Services;

use App\Models\Article;

class SEOService
{
    public function generateArticleSchema(Article $article): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $article->title,
            'description' => $article->excerpt,
            'image' => $article->featured_image ? asset('storage/' . $article->featured_image) : null,
            'datePublished' => $article->published_at?->toIso8601String(),
            'dateModified' => $article->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $article->user->name,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('logo.png'),
                ],
            ],
        ];

        if ($article->meta) {
            $schema['keywords'] = implode(', ', $article->meta->keywords_used ?? []);
        }

        return array_filter($schema);
    }

    public function generateFAQSchema(Article $article): array
    {
        if ($article->faqs->isEmpty()) {
            return [];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $article->faqs->map(function ($faq) {
                return [
                    '@type' => 'Question',
                    'name' => $faq->question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => strip_tags($faq->answer),
                    ],
                ];
            })->toArray(),
        ];
    }

    public function checkSEOReadiness(Article $article): array
    {
        $checks = [
            'title_length' => (strlen($article->title ?? '') >= 30 && strlen($article->title) <= 70),
            'meta_description' => ($article->meta && strlen($article->meta->meta_description ?? '') >= 120 && strlen($article->meta->meta_description) <= 160),
            'content_length' => (str_word_count(strip_tags($article->content ?? '')) >= 1000),
            'heading_structure' => (substr_count(strtolower($article->content ?? ''), '<h2') >= 2),
            'featured_image' => !empty($article->featured_image),
            'focus_keyword' => ($article->meta && !empty($article->meta->focus_keyword)),
            'slug_friendly' => (strlen($article->slug ?? '') <= 75 && preg_match('/^[a-z0-9-]+$/', $article->slug)),
            'internal_links' => ($article->meta && !empty($article->meta->internal_links) && count($article->meta->internal_links) >= 2),
        ];

        return $checks;
    }

    public function getSEOScoreBreakdown(Article $article): array
    {
        $checks = $this->checkSEOReadiness($article);
        $score = 0;

        $weights = [
            'title_length' => 15,
            'meta_description' => 15,
            'content_length' => 20,
            'heading_structure' => 10,
            'featured_image' => 10,
            'focus_keyword' => 10,
            'slug_friendly' => 5,
            'internal_links' => 15,
        ];

        foreach ($checks as $check => $passed) {
            if ($passed) {
                $score += $weights[$check] ?? 0;
            }
        }

        return [
            'total_score' => min(100, $score),
            'checks' => $checks,
            'weights' => $weights,
        ];
    }

    public function generateRecommendations(Article $article): array
    {
        $checks = $this->checkSEOReadiness($article);
        $recommendations = [];

        if (!$checks['title_length']) {
            $recommendations[] = [
                'severity' => 'high',
                'message' => 'Title should be between 30-70 characters. Current: ' . strlen($article->title ?? ''),
            ];
        }

        if (!$checks['meta_description']) {
            $recommendations[] = [
                'severity' => 'high',
                'message' => 'Meta description should be between 120-160 characters. Current: ' . strlen($article->meta?->meta_description ?? ''),
            ];
        }

        if (!$checks['content_length']) {
            $recommendations[] = [
                'severity' => 'high',
                'message' => 'Content should have at least 1000 words. Current: ' . str_word_count(strip_tags($article->content ?? '')),
            ];
        }

        if (!$checks['heading_structure']) {
            $recommendations[] = [
                'severity' => 'medium',
                'message' => 'Article should have at least 2 H2 headings for better structure',
            ];
        }

        if (!$checks['featured_image']) {
            $recommendations[] = [
                'severity' => 'medium',
                'message' => 'Add a featured image to improve CTR and user engagement',
            ];
        }

        if (!$checks['focus_keyword']) {
            $recommendations[] = [
                'severity' => 'medium',
                'message' => 'Define a focus keyword for better SEO targeting',
            ];
        }

        if (!$checks['slug_friendly']) {
            $recommendations[] = [
                'severity' => 'low',
                'message' => 'URL slug should be lowercase, use hyphens, and be under 75 characters',
            ];
        }

        if (!$checks['internal_links']) {
            $recommendations[] = [
                'severity' => 'low',
                'message' => 'Add at least 2 internal links to other relevant articles',
            ];
        }

        return $recommendations;
    }

    public function generateInternalLinks(Article $article): array
    {
        if (!$article->meta || empty($article->meta->keywords_used)) {
            return [];
        }

        $keywords = $article->meta->keywords_used;
        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->limit(5)
            ->get();

        $internalLinks = [];
        foreach ($keywords as $keyword) {
            foreach ($relatedArticles as $related) {
                if (stripos($related->title, $keyword) !== false) {
                    $internalLinks[] = [
                        'keyword' => $keyword,
                        'article_id' => $related->id,
                        'article_title' => $related->title,
                        'url' => route('articles.show', $related->slug),
                    ];
                }
            }
        }

        return array_slice($internalLinks, 0, 5);
    }

    public function autoInsertInternalLinks(Article $article): void
    {
        if (!$article->meta || empty($article->meta->focus_keyword)) {
            return;
        }

        $internalLinks = $this->generateInternalLinks($article);
        if (empty($internalLinks)) {
            return;
        }

        $content = $article->content;
        foreach ($internalLinks as $link) {
            $keyword = $link['keyword'];
            $url = $link['url'];

            $pattern = '/(?<![<])' . preg_quote($keyword, '/') . '(?![^<]*>)/i';
            $replacement = '<a href="' . $url . '" title="' . $link['article_title'] . '">' . $keyword . '</a>';
            $content = preg_replace($pattern, $replacement, $content, 1);
        }

        $article->update(['content' => $content]);
        $article->meta->update(['internal_links' => $internalLinks]);
    }
}
