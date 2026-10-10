<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    private const CACHE_TTL = 3600; // 1 hour

    private function getArticleSelect()
    {
        return ['id', 'title', 'slug', 'featured_image', 'category_id', 'user_id', 'published_at', 'views_count', 'excerpt'];
    }

    public function index()
    {
        // Breaking Strip - Featured articles (prioritize with images)
        $featuredStrip = Cache::remember('home_featured_strip', self::CACHE_TTL, function () {
            return Article::published()
                ->whereNotLike('featured_image', '%placeholder%')
                ->select($this->getArticleSelect())
                ->with('category:id,name,slug', 'user:id,name')
                ->latest('published_at')
                ->take(6)
                ->get();
        });

        // Hero Slides (prioritize with images)
        $heroSlides = Cache::remember('home_hero_slides', self::CACHE_TTL, function () {
            return Article::published()
                ->whereNotLike('featured_image', '%placeholder%')
                ->select($this->getArticleSelect())
                ->with('category:id,name,slug', 'user:id,name')
                ->latest('published_at')
                ->take(10)
                ->get();
        });

        // Side Cards (right column - 2 articles: 1 top, 1 bottom)
        $sideCards = $featuredStrip->slice(5, 2);

        // Category Strip - latest articles from each category (prioritize with images)
        $categoryStrip = Cache::remember('home_category_strip', self::CACHE_TTL, function () {
            return Article::published()
                ->whereNotLike('featured_image', '%placeholder%')
                ->select($this->getArticleSelect())
                ->with('category:id,name,slug', 'user:id,name')
                ->latest('published_at')
                ->take(15)
                ->get();
        });

        // Recent & Popular (prioritize with images)
        $recentFeatured = Cache::remember('home_recent_featured', self::CACHE_TTL, function () {
            return Article::published()
                ->whereNotLike('featured_image', '%placeholder%')
                ->select($this->getArticleSelect())
                ->with('category:id,name,slug', 'user:id,name')
                ->latest('published_at')
                ->take(6)
                ->get();
        });

        $popularPosts = Cache::remember('home_popular_posts', self::CACHE_TTL, function () {
            return Article::published()
                ->select($this->getArticleSelect())
                ->with('category:id,name,slug', 'user:id,name')
                ->orderBy('views_count', 'desc')
                ->take(5)
                ->get();
        });

        // Sports Section
        $sportsPosts = Cache::remember('home_sports_posts', self::CACHE_TTL, function () {
            return Article::published()
                ->select($this->getArticleSelect())
                ->with('category:id,name,slug', 'user:id,name')
                ->whereHas('category', fn($q) => $q->where('slug', 'sports'))
                ->latest('published_at')
                ->take(8)
                ->get();
        });

        // Lifestyle Section
        $lifestylePosts = Cache::remember('home_lifestyle_posts', self::CACHE_TTL, function () {
            return Article::published()
                ->select($this->getArticleSelect())
                ->with('category:id,name,slug', 'user:id,name')
                ->whereHas('category', fn($q) => $q->where('slug', 'entertainment'))
                ->latest('published_at')
                ->take(6)
                ->get();
        });

        // Technology Section
        $technologyPosts = Cache::remember('home_technology_posts', self::CACHE_TTL, function () {
            return Article::published()
                ->select($this->getArticleSelect())
                ->with('category:id,name,slug', 'user:id,name')
                ->whereHas('category', fn($q) => $q->where('slug', 'technology'))
                ->latest('published_at')
                ->take(4)
                ->get();
        });

        // Sidebar data (prioritize with images)
        $sidebarLatestArticles = Cache::remember('home_sidebar_latest', self::CACHE_TTL, function () {
            return Article::published()
                ->whereNotLike('featured_image', '%placeholder%')
                ->select($this->getArticleSelect())
                ->with('category:id,name,slug', 'user:id,name')
                ->latest('published_at')
                ->take(5)
                ->get();
        });

        $latestFeatured = $sidebarLatestArticles->first();
        $latestSidebarPosts = $sidebarLatestArticles->slice(1);

        // Top Categories with article count (cached)
        $topCategories = Cache::remember('home_top_categories', self::CACHE_TTL, function () {
            return Category::active()
                ->withCount(['articles' => fn($q) => $q->published()])
                ->orderBy('articles_count', 'desc')
                ->take(10)
                ->get(['id', 'name', 'slug']);
        });

        // Popular Tags (cached)
        $popularTags = Cache::remember('home_popular_tags', self::CACHE_TTL, function () {
            return Tag::whereHas('articles', fn($q) => $q->published())
                ->withCount('articles')
                ->orderByDesc('articles_count')
                ->take(10)
                ->get(['id', 'name', 'slug']);
        });

        // Pagination for older articles (not cached - user-specific)
        $paginator = Article::published()
            ->select($this->getArticleSelect())
            ->with('category:id,name,slug', 'user:id,name')
            ->latest('published_at')
            ->paginate(12);

        return view('home', compact(
            'featuredStrip',
            'heroSlides',
            'sideCards',
            'categoryStrip',
            'recentFeatured',
            'popularPosts',
            'sportsPosts',
            'lifestylePosts',
            'technologyPosts',
            'sidebarLatestArticles',
            'latestFeatured',
            'latestSidebarPosts',
            'topCategories',
            'popularTags',
            'paginator'
        ) + ['tags' => $popularTags]);
    }
}
