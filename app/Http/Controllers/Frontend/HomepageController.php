<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Announcement;
use App\Models\Event;
use App\Services\HomepageBuilderService;

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

        // Get data for both modern and welcome views
        $latestArticles = Article::published()
            ->with(['category', 'user'])
            ->latest('published_at')
            ->take(20)
            ->get();

        $categories = Category::active()
            ->with(['articles' => fn($q) => $q->published()->latest('published_at')->take(4)])
            ->withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->take(10)
            ->get();

        // Get active announcements
        $announcements = Announcement::active()
            ->ordered()
            ->take(3)
            ->get();

        // Get upcoming events
        $upcomingEvents = Event::active()
            ->upcoming()
            ->take(6)
            ->get();

        // Modern structured view (recommended)
        if ($viewType === 'modern') {
            return view('frontend.home-modern', compact('latestArticles', 'categories', 'announcements', 'upcomingEvents'));
        }

        // Legacy welcome blade view
        return view('welcome', compact('latestArticles', 'categories', 'announcements', 'upcomingEvents'));
    }
}
