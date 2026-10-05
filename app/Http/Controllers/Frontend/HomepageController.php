<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Event;
use App\Models\Video;
use App\Services\HomepageBuilderService;
use Illuminate\Support\Facades\Cache;

class HomepageController extends Controller
{
    public function __construct(private HomepageBuilderService $service)
    {
    }

    public function index()
    {
        // Check query parameter to determine view type
        // Options: 'modern' (default) - structured layout, 'builder' - homebuilder sections, 'welcome' - standalone
        $viewType = request('view', 'modern');

        // If explicitly requesting homebuilder view
        if ($viewType === 'builder') {
            $activeSections = $this->service->getActiveSections('homepage');
            if ($activeSections->isNotEmpty()) {
                $sections = $activeSections->map(fn($s) => [
                    'section' => $s,
                    'data' => $this->service->getSectionData($s),
                ]);
                return view('frontend.home', ['sections' => $sections, 'useBuilder' => true]);
            }
        }

        // ============================================
        // TIERED CONTENT STRATEGY - No Duplicate Articles
        // ============================================
        $shownIds = []; // Track all shown article IDs

        // TIER 0: Breaking News (Urgent - highest priority, 15 min cache)
        $breakingNews = Cache::remember('homepage_breaking_news', now()->addMinutes(15), function () {
            return Article::published()
                ->whereNotNull('featured_image')
                ->where(function ($q) {
                    $q->where('is_breaking', true)
                      ->orWhere('priority', '>=', 8);
                })
                ->where('created_at', '>=', now()->subHours(24))
                ->orderByDesc('created_at')
                ->take(2)
                ->with(['category:id,name,slug', 'user:id,name'])
                ->get();
        });
        $shownIds = array_merge($shownIds, $breakingNews->pluck('id')->toArray());

        // TIER 1: Featured Articles (Top 2 - highest viewed/commented)
        $featuredArticles = Cache::remember('homepage_featured_articles', now()->addHours(1), function () {
            return Article::published()
                ->whereNotNull('featured_image')
                ->with(['category:id,name,slug', 'user:id,name'])
                ->orderByDesc('views_count')
                ->take(2)
                ->get();
        });
        $shownIds = array_merge($shownIds, $featuredArticles->pluck('id')->toArray());

        // TIER 2: Latest Articles (Recent posts - next 10, excluding featured)
        $latestArticles = Cache::remember('homepage_latest_articles', now()->addHours(1), function () use ($shownIds) {
            return Article::published()
                ->whereNotNull('featured_image')
                ->with(['category:id,name,slug', 'user:id,name'])
                ->whereNotIn('id', $shownIds)
                ->latest('published_at')
                ->take(10)
                ->get();
        });
        $shownIds = array_merge($shownIds, $latestArticles->pluck('id')->toArray());

        // TIER 3: Trending Articles (Recent with engagement - last 7 days, excluding featured & latest)
        $trendingArticles = Cache::remember('homepage_trending_articles', now()->addHours(1), function () use ($shownIds) {
            return Article::published()
                ->whereNotNull('featured_image')
                ->with(['category:id,name,slug', 'user:id,name'])
                ->whereNotIn('id', $shownIds)
                ->where('created_at', '>=', now()->subDays(7))
                ->orderByDesc('views_count')
                ->take(5)
                ->get();
        });
        $shownIds = array_merge($shownIds, $trendingArticles->pluck('id')->toArray());

        $categories = Cache::remember('homepage_categories', now()->addHours(1), function () {
            return Category::active()
                ->withCount(['articles' => fn($q) => $q->published()])
                ->having('articles_count', '>', 0)
                ->orderBy('articles_count', 'desc')
                ->take(10)
                ->get(['id', 'name', 'slug', 'icon', 'description']);
        });

        // Get sidebar categories (limited to 8, with article counts)
        $sidebarCategories = Cache::remember('homepage_sidebar_categories', now()->addHours(1), function () {
            return Category::active()
                ->withCount(['articles' => fn($q) => $q->published()])
                ->having('articles_count', '>', 0)
                ->orderBy('articles_count', 'desc')
                ->limit(8)
                ->get(['id', 'name', 'slug']);
        });

        // Sidebar articles (exclude all above tiers)
        $sidebarArticles = Cache::remember('homepage_sidebar_articles', now()->addHours(1), function () use ($shownIds) {
            return Article::published()
                ->with(['category:id,name,slug', 'user:id,name'])
                ->whereNotIn('id', $shownIds)
                ->latest('published_at')
                ->take(5)
                ->get(['id', 'title', 'slug', 'published_at', 'category_id', 'user_id', 'featured_image']);
        });


        // Get upcoming events
        $upcomingEvents = Cache::remember('homepage_events', now()->addHours(1), function () {
            try {
                return Event::active()
                    ->upcoming()
                    ->take(6)
                    ->get(['id', 'title', 'description', 'event_date', 'location', 'featured_image']);
            } catch (\Exception $e) {
                return collect();
            }
        });

        // Get featured videos for gallery section
        $videoGallery = Cache::remember('homepage_video_gallery', now()->addHours(1), function () {
            return Video::published()
                ->with(['category:id,name', 'user:id,name'])
                ->latest('published_at')
                ->take(8)
                ->get(['id', 'title', 'youtube_id', 'thumbnail_url', 'category_id', 'youtube_url', 'views_count', 'published_at']);
        });

        // Modern structured view (recommended)
        if ($viewType === 'modern') {
            return view('frontend.home-modern', compact('breakingNews', 'featuredArticles', 'latestArticles', 'trendingArticles', 'categories', 'sidebarCategories', 'sidebarArticles', 'upcomingEvents', 'videoGallery'));
        }

        // Legacy welcome blade view
        return view('welcome', compact('breakingNews', 'featuredArticles', 'latestArticles', 'trendingArticles', 'categories', 'sidebarCategories', 'sidebarArticles', 'upcomingEvents', 'videoGallery'));
    }
}
