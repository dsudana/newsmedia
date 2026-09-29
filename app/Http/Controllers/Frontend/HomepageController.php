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
                ->with(['category', 'user', 'comments' => fn($q) => $q->approved()])
                ->latest('published_at')
                ->take(20)
                ->get();
        });

        $categories = Cache::remember('homepage_categories', now()->addHours(1), function () {
            return Category::active()
                ->with(['articles' => fn($q) => $q->published()->latest('published_at')->take(4)])
                ->withCount('articles')
                ->orderBy('articles_count', 'desc')
                ->take(10)
                ->get();
        });

        // Get active announcements (shorter cache for more frequent updates)
        $announcements = Cache::remember('homepage_announcements', now()->addMinutes(30), function () {
            return Announcement::active()
                ->ordered()
                ->take(3)
                ->get();
        });

        // Get upcoming events
        $upcomingEvents = Cache::remember('homepage_events', now()->addHours(1), function () {
            return Event::active()
                ->upcoming()
                ->take(6)
                ->get();
        });

        // Modern structured view (recommended)
        if ($viewType === 'modern') {
            return view('frontend.home-modern', compact('latestArticles', 'categories', 'announcements', 'upcomingEvents'));
        }

        // Legacy welcome blade view
        return view('welcome', compact('latestArticles', 'categories', 'announcements', 'upcomingEvents'));
    }
}
