<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;

class HomeController extends Controller
{
    public function index()
    {
        // Breaking Strip - Featured articles (prioritize with images)
        $featuredStrip = Article::published()
            ->whereNotLike('featured_image', '%placeholder%')
            ->latest('published_at')
            ->take(6)
            ->get();

        // Hero Slides (prioritize with images)
        $heroSlides = Article::published()
            ->whereNotLike('featured_image', '%placeholder%')
            ->latest('published_at')
            ->take(10)
            ->get();

        // Side Cards (right column - 2 articles: 1 top, 1 bottom)
        $sideCards = Article::published()
            ->whereNotLike('featured_image', '%placeholder%')
            ->latest('published_at')
            ->skip(5)
            ->take(2)
            ->get();

        // Category Strip - latest articles from each category (prioritize with images)
        $categoryStrip = Article::published()
            ->whereNotLike('featured_image', '%placeholder%')
            ->latest('published_at')
            ->take(15)
            ->get();

        // Recent & Popular (prioritize with images)
        $recentFeatured = Article::published()
            ->whereNotLike('featured_image', '%placeholder%')
            ->latest('published_at')
            ->take(6)
            ->get();

        $popularPosts = Article::published()
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        // Sports Section
        $sportsPosts = Article::published()
            ->whereHas('category', fn($q) => $q->where('slug', 'sports'))
            ->latest('published_at')
            ->take(8)
            ->get();

        // Lifestyle Section
        $lifestylePosts = Article::published()
            ->whereHas('category', fn($q) => $q->where('slug', 'entertainment'))
            ->latest('published_at')
            ->take(6)
            ->get();

        // Technology Section
        $technologyPosts = Article::published()
            ->whereHas('category', fn($q) => $q->where('slug', 'technology'))
            ->latest('published_at')
            ->take(4)
            ->get();

        // Sidebar data (prioritize with images)
        $sidebarLatestArticles = Article::published()
            ->whereNotLike('featured_image', '%placeholder%')
            ->latest('published_at')
            ->take(5)
            ->get();

        $latestFeatured = $sidebarLatestArticles->first();
        $latestSidebarPosts = $sidebarLatestArticles->slice(1);

        $topCategories = Category::active()
            ->withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->take(10)
            ->get();

        $popularTags = Tag::whereHas('articles', fn($q) => $q->published())
            ->withCount('articles')
            ->orderByDesc('articles_count')
            ->take(10)
            ->get();

        // Pagination for older articles
        $paginator = Article::published()
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
