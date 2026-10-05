<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Announcement;
use App\Models\Event;
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

        // Get data for both modern and welcome views with caching
        // Cache for 1 hour to reduce database queries
        $latestArticles = Cache::remember('homepage_latest_articles', now()->addHours(1), function () {
            return Article::published()
                ->whereNotNull('featured_image')
                ->with(['category:id,name,slug', 'user:id,name'])
                ->latest('published_at')
                ->take(20)
                ->get();
        });

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

        // Get sidebar articles (recent, limited to 5)
        $sidebarArticles = Cache::remember('homepage_sidebar_articles', now()->addHours(1), function () {
            return Article::published()
                ->with(['category:id,name,slug', 'user:id,name'])
                ->latest('published_at')
                ->take(5)
                ->get(['id', 'title', 'slug', 'published_at', 'category_id', 'user_id', 'featured_image']);
        });

        // Get active announcements (shorter cache for more frequent updates)
        $announcements = Cache::remember('homepage_announcements', now()->addMinutes(30), function () {
            return Announcement::active()
                ->ordered()
                ->take(3)
                ->get(['id', 'title', 'description', 'type', 'background_color']);
        });

        // Get upcoming events
        $upcomingEvents = Cache::remember('homepage_events', now()->addHours(1), function () {
            return Event::active()
                ->upcoming()
                ->take(6)
                ->get(['id', 'title', 'description', 'date', 'location']);
        });

        // Modern structured view (recommended)
        if ($viewType === 'modern') {
            return view('frontend.home-modern', compact('latestArticles', 'categories', 'sidebarCategories', 'sidebarArticles', 'announcements', 'upcomingEvents'));
        }

        // Legacy welcome blade view
        return view('welcome', compact('latestArticles', 'categories', 'sidebarCategories', 'sidebarArticles', 'announcements', 'upcomingEvents'));
    }
}
