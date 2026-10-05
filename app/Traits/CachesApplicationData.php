<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\Article;

trait CachesApplicationData
{
    /**
     * Get cached categories with article counts
     * Cache for 1 week (604800 seconds)
     */
    public static function getCachedCategories($ttl = 604800)
    {
        return Cache::remember('app:categories:active', $ttl, function () {
            return Category::active()
                ->withCount(['articles' => function ($q) {
                    $q->published()->whereNull('deleted_at');
                }])
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Get cached popular articles
     * Cache for 24 hours (86400 seconds)
     */
    public static function getCachedPopularArticles($categoryId = null, $limit = 5, $ttl = 86400)
    {
        $cacheKey = $categoryId
            ? "app:popular:articles:category:{$categoryId}"
            : "app:popular:articles";

        return Cache::remember($cacheKey, $ttl, function () use ($categoryId, $limit) {
            $query = Article::published()
                ->with(['category', 'user'])
                ->orderByDesc('views_count');

            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            return $query->take($limit)->get();
        });
    }

    /**
     * Get cached recent articles
     * Cache for 1 hour (3600 seconds)
     */
    public static function getCachedRecentArticles($categoryId = null, $limit = 5, $ttl = 3600)
    {
        $cacheKey = $categoryId
            ? "app:recent:articles:category:{$categoryId}"
            : "app:recent:articles";

        return Cache::remember($cacheKey, $ttl, function () use ($categoryId, $limit) {
            $query = Article::published()
                ->with(['category', 'user'])
                ->latest('published_at');

            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            return $query->take($limit)->get();
        });
    }

    /**
     * Flush all application caches
     */
    public static function flushApplicationCaches()
    {
        Cache::forget('app:categories:active');
        Cache::flush('app:popular:articles*');
        Cache::flush('app:recent:articles*');
    }
}
