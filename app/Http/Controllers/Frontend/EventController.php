<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    const PER_PAGE = 12;

    public function index(Request $request)
    {
        $query = Event::active()
            ->with(['user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Default: show upcoming events first, then past
        $query->orderByRaw("CASE WHEN event_date >= NOW() THEN 0 ELSE 1 END, event_date ASC");

        $events = $query->paginate(self::PER_PAGE);

        return view('frontend.events.index', compact('events'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)->active()->firstOrFail();
        $event->recordView();

        // Get related upcoming events
        $relatedEvents = Event::active()
            ->upcoming()
            ->where('id', '!=', $event->id)
            ->limit(6)
            ->get();

        return view('frontend.events.show', compact('event', 'relatedEvents'));
    }
}
