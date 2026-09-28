<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use App\Models\HomepageSection;
use Illuminate\Database\Eloquent\Collection;

class HomepageBuilderService
{
    /**
     * Get all sections for a page type
     */
    public function getAllSections(string $pageType): Collection
    {
        return HomepageSection::byPage($pageType)->ordered()->get();
    }

    /**
     * Get only active sections for a page type
     */
    public function getActiveSections(string $pageType): Collection
    {
        return HomepageSection::byPage($pageType)->active()->ordered()->get();
    }

    /**
     * Get available section types
     */
    public function getSectionTypes(): array
    {
        return HomepageSection::SECTION_TYPES;
    }

    /**
     * Create a new section
     */
    public function createSection(array $data): HomepageSection
    {
        // Set order to max order + 1 for the given page type
        $maxOrder = HomepageSection::byPage($data['page_type'])
            ->max('order') ?? 0;

        $data['order'] = $maxOrder + 1;

        return HomepageSection::create($data);
    }

    /**
     * Update a section
     */
    public function updateSection(HomepageSection $section, array $data): HomepageSection
    {
        $section->update($data);
        return $section;
    }

    /**
     * Delete a section
     */
    public function deleteSection(HomepageSection $section): bool
    {
        return $section->delete();
    }

    /**
     * Toggle section status
     */
    public function toggleStatus(HomepageSection $section): HomepageSection
    {
        $section->update(['status' => !$section->status]);
        return $section;
    }

    /**
     * Reorder sections by provided orders
     */
    public function reorderSections(array $orders): void
    {
        foreach ($orders as $id => $order) {
            HomepageSection::find($id)?->update(['order' => $order]);
        }
    }

    /**
     * Get resolved data for a section based on its type
     */
    public function getSectionData(HomepageSection $section): array
    {
        return match ($section->section_type) {
            'article_carousel' => $this->getArticleCarouselData($section),
            'category_highlight' => $this->getCategoryHighlightData($section),
            'blog_preview' => $this->getBlogPreviewData($section),
            'testimonial' => $this->getTestimonialData($section),
            'trending_news_carousel' => $this->getTrendingNewsCarouselData($section),
            'hero_carousel' => $this->getHeroCarouselData($section),
            'newsletter' => [],
            'image_banner' => [],
            'text_image_split' => [],
            'lookbook' => [],
            default => [],
        };
    }

    /**
     * Get article carousel data
     */
    private function getArticleCarouselData(HomepageSection $section): array
    {
        $limit = $section->config['limit'] ?? 6;
        $sortBy = $section->config['sort_by'] ?? 'latest';

        $query = Article::published();

        switch ($sortBy) {
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'oldest':
                $query->oldest('published_at');
                break;
            case 'latest':
            default:
                $query->latest('published_at');
        }

        $articles = $query->limit($limit)->get();

        return [
            'type' => 'article_carousel',
            'articles' => $articles,
            'count' => $articles->count(),
        ];
    }

    /**
     * Get category highlight data
     */
    private function getCategoryHighlightData(HomepageSection $section): array
    {
        $limit = $section->config['limit'] ?? 6;

        $categories = Category::active()
            ->withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->limit($limit)
            ->get();

        return [
            'type' => 'category_highlight',
            'categories' => $categories,
            'count' => $categories->count(),
        ];
    }

    /**
     * Get blog preview data
     */
    private function getBlogPreviewData(HomepageSection $section): array
    {
        $limit = $section->config['limit'] ?? 6;
        $sortBy = $section->config['sort_by'] ?? 'latest';

        $query = Article::published();

        switch ($sortBy) {
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'oldest':
                $query->oldest('published_at');
                break;
            case 'latest':
            default:
                $query->latest('published_at');
        }

        $articles = $query->limit($limit)->get();

        return [
            'type' => 'blog_preview',
            'articles' => $articles,
            'count' => $articles->count(),
        ];
    }

    /**
     * Get testimonial data
     */
    private function getTestimonialData(HomepageSection $section): array
    {
        // Placeholder for testimonial data - currently returns empty
        return [
            'type' => 'testimonial',
            'testimonials' => [],
            'count' => 0,
        ];
    }

    /**
     * Get trending news carousel data
     */
    private function getTrendingNewsCarouselData(HomepageSection $section): array
    {
        $limit = $section->config['limit'] ?? 12;
        $sortBy = $section->config['sort_by'] ?? 'views';

        $query = Article::published()->with(['category', 'user']);

        // Sort by trending (views), latest, or popular
        switch ($sortBy) {
            case 'latest':
                $query->latest('published_at');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'views':
            default:
                $query->orderBy('views_count', 'desc');
        }

        $articles = $query->limit($limit)->get();

        return [
            'type' => 'trending_news_carousel',
            'articles' => $articles,
            'count' => $articles->count(),
        ];
    }

    /**
     * Get hero carousel data - auto-popular articles
     */
    private function getHeroCarouselData(HomepageSection $section): array
    {
        $mainLimit = $section->config['main_limit'] ?? 5;
        $sideLimit = $section->config['side_limit'] ?? 2;

        // Get most popular articles for main carousel
        $mainArticles = Article::published()
            ->with(['category', 'user'])
            ->orderBy('views_count', 'desc')
            ->limit($mainLimit)
            ->get();

        // Get additional popular articles for side cards
        $sideArticles = Article::published()
            ->with(['category', 'user'])
            ->orderBy('views_count', 'desc')
            ->offset($mainLimit)
            ->limit($sideLimit)
            ->get();

        return [
            'type' => 'hero_carousel',
            'main_articles' => $mainArticles,
            'side_articles' => $sideArticles,
            'count' => $mainArticles->count() + $sideArticles->count(),
        ];
    }

    /**
     * Get breaking news strip section data
     */
    public function getBreakingNewsStripData($config = []): array
    {
        $limit = $config['limit'] ?? 12;
        $sliderSpeed = $config['slider_speed'] ?? 3000;

        $articles = Article::where('status', 'published')
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get(['id', 'title', 'slug', 'featured_image', 'published_at']);

        return [
            'articles' => $articles,
            'slider_speed' => $sliderSpeed,
        ];
    }

    /**
     * Get recent and popular articles data
     */
    public function getRecentAndPopularData($config = []): array
    {
        $recentLimit = $config['recent_limit'] ?? 6;
        $popularLimit = $config['popular_limit'] ?? 4;

        $recent = Article::where('status', 'published')
            ->orderByDesc('published_at')
            ->limit($recentLimit)
            ->get();

        $popular = Article::where('status', 'published')
            ->orderByDesc('views_count')
            ->limit($popularLimit)
            ->get();

        return [
            'recent_articles' => $recent,
            'popular_articles' => $popular,
        ];
    }

    /**
     * Get category strip carousel data
     */
    public function getCategoryStripCarouselData($config = []): array
    {
        $limit = $config['limit'] ?? 12;
        $category = $config['category'] ?? null;
        $sliderSpeed = $config['slider_speed'] ?? 3000;

        $query = Article::where('status', 'published')
            ->orderByDesc('published_at');

        if ($category) {
            $query->whereHas('category', function($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        $articles = $query->limit($limit)->get();

        return [
            'articles' => $articles,
            'slider_speed' => $sliderSpeed,
        ];
    }

    /**
     * Get category grid section data
     */
    public function getCategoryGridSectionData($config = []): array
    {
        $limit = $config['limit'] ?? 8;
        $category = $config['category'] ?? null;

        $query = Article::where('status', 'published')
            ->orderByDesc('published_at');

        if ($category) {
            $query->whereHas('category', function($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        $articles = $query->limit($limit)->get();

        return ['articles' => $articles];
    }

    /**
     * Get category list section data
     */
    public function getCategoryListSectionData($config = []): array
    {
        $limit = $config['limit'] ?? 6;
        $category = $config['category'] ?? null;

        $query = Article::where('status', 'published')
            ->orderByDesc('published_at');

        if ($category) {
            $query->whereHas('category', function($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        $articles = $query->limit($limit)->get();

        return ['articles' => $articles];
    }

    /**
     * Get sports carousel data
     */
    public function getSportsCarouselData($config = []): array
    {
        $limit = $config['limit'] ?? 10;
        $sliderSpeed = $config['slider_speed'] ?? 3000;

        $articles = Article::where('status', 'published')
            ->whereHas('category', function($q) {
                $q->where('slug', 'sports');
            })
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();

        return [
            'articles' => $articles,
            'slider_speed' => $sliderSpeed,
        ];
    }
}
