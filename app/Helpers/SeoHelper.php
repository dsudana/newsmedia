<?php

namespace App\Helpers;

use App\Models\SeoSetting;
use App\Models\Article;

class SeoHelper
{
    /**
     * Render meta tags for a specific page
     */
    public static function renderMetaTags(string $pageName = null, ?Article $article = null): string
    {
        $meta = '';

        if ($article) {
            // Article-specific meta tags
            $meta .= self::renderArticleMeta($article);
        } elseif ($pageName) {
            // Page-specific meta tags from SEO settings
            $meta .= self::renderPageMeta($pageName);
        }

        return $meta;
    }

    /**
     * Render article meta tags
     */
    private static function renderArticleMeta(Article $article): string
    {
        $meta = '';
        $description = $article->meta?->meta_description ?? $article->excerpt ?? '';
        $imageUrl = $article->featured_image ? asset('storage/' . $article->featured_image) : asset('images/placeholder.jpg');

        // Standard meta tags
        $meta .= self::metaTag('title', $article->meta?->meta_title ?? $article->title);
        $meta .= self::metaTag('description', $description, 160);

        if ($article->meta?->meta_keywords) {
            $meta .= self::metaTag('keywords', $article->meta->meta_keywords);
        }

        if ($article->meta?->canonical_url) {
            $meta .= "<link rel=\"canonical\" href=\"{$article->meta->canonical_url}\">\n";
        } else {
            $meta .= "<link rel=\"canonical\" href=\"" . route('blog.show', $article->slug) . "\">\n";
        }

        // Open Graph
        $meta .= self::metaTag('og:title', $article->meta?->og_title ?? $article->title);
        $meta .= self::metaTag('og:description', $article->meta?->og_description ?? $description);
        $meta .= self::metaTag('og:image', $article->meta?->og_image ?? $imageUrl);
        $meta .= self::metaTag('og:type', $article->meta?->og_type ?? 'article');
        $meta .= self::metaTag('og:url', route('blog.show', $article->slug));

        // Twitter Card
        $meta .= self::metaTag('twitter:card', $article->meta?->twitter_card ?? 'summary_large_image');
        $meta .= self::metaTag('twitter:title', $article->meta?->twitter_title ?? $article->title);
        $meta .= self::metaTag('twitter:description', $article->meta?->twitter_description ?? $description);
        $meta .= self::metaTag('twitter:image', $article->meta?->twitter_image ?? $imageUrl);

        // Article-specific structured data
        $meta .= self::renderArticleStructuredData($article);

        return $meta;
    }

    /**
     * Render page-specific meta tags
     */
    private static function renderPageMeta(string $pageName): string
    {
        $seoSetting = SeoSetting::getByPageName($pageName);

        if (!$seoSetting) {
            return '';
        }

        $meta = '';

        // Standard meta tags
        if ($seoSetting->meta_title) {
            $meta .= self::metaTag('title', $seoSetting->meta_title);
        }

        if ($seoSetting->meta_description) {
            $meta .= self::metaTag('description', $seoSetting->meta_description);
        }

        if ($seoSetting->meta_keywords) {
            $meta .= self::metaTag('keywords', $seoSetting->meta_keywords);
        }

        if ($seoSetting->canonical_url) {
            $meta .= "<link rel=\"canonical\" href=\"{$seoSetting->canonical_url}\">\n";
        }

        // Robots
        $meta .= self::metaTag('robots', $seoSetting->robots_tag);

        // Open Graph
        if ($seoSetting->og_title || $seoSetting->og_description || $seoSetting->og_image) {
            $meta .= self::metaTag('og:title', $seoSetting->og_title);
            $meta .= self::metaTag('og:description', $seoSetting->og_description);
            $meta .= self::metaTag('og:image', $seoSetting->og_image);
            $meta .= self::metaTag('og:type', $seoSetting->og_type ?? 'website');
        }

        // Twitter Card
        if ($seoSetting->twitter_title || $seoSetting->twitter_description || $seoSetting->twitter_image) {
            $meta .= self::metaTag('twitter:card', $seoSetting->twitter_card ?? 'summary_large_image');
            $meta .= self::metaTag('twitter:title', $seoSetting->twitter_title);
            $meta .= self::metaTag('twitter:description', $seoSetting->twitter_description);
            $meta .= self::metaTag('twitter:image', $seoSetting->twitter_image);
        }

        // Structured Data
        if ($seoSetting->structured_data) {
            $meta .= "<script type=\"application/ld+json\">\n";
            $meta .= json_encode($seoSetting->structured_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $meta .= "\n</script>\n";
        }

        return $meta;
    }

    /**
     * Render Article structured data (JSON-LD)
     */
    private static function renderArticleStructuredData(Article $article): string
    {
        $imageUrl = $article->featured_image ? asset('storage/' . $article->featured_image) : asset('images/placeholder.jpg');

        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $article->title,
            'description' => $article->excerpt ?? substr(strip_tags($article->content), 0, 160),
            'image' => [
                '@type' => 'ImageObject',
                'url' => $imageUrl,
            ],
            'datePublished' => $article->published_at?->toIso8601String(),
            'dateModified' => $article->updated_at?->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $article->user?->name ?? 'Admin',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.png'),
                ],
            ],
        ];

        return "<script type=\"application/ld+json\">\n"
            . json_encode($structuredData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            . "\n</script>\n";
    }

    /**
     * Helper to create meta tags
     */
    private static function metaTag(string $name, ?string $content = null, ?int $limit = null): string
    {
        if (!$content) {
            return '';
        }

        if ($limit) {
            $content = substr($content, 0, $limit);
        }

        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

        // Check if it's an og: or twitter: tag
        if (str_contains($name, ':')) {
            return "<meta property=\"{$name}\" content=\"{$content}\">\n";
        }

        return "<meta name=\"{$name}\" content=\"{$content}\">\n";
    }
}
