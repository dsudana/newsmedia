<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $statuses = ['scheduled' => 'Scheduled', 'ongoing' => 'Ongoing', 'cancelled' => 'Cancelled', 'completed' => 'Completed'];
        return view('admin.events.create', compact('statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'event_date' => 'required|date_format:Y-m-d H:i',
            'event_end_date' => 'nullable|date_format:Y-m-d H:i|after:event_date',
            'location' => 'nullable|string|max:255',
            'location_details' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'status' => 'required|string|in:scheduled,ongoing,cancelled,completed',
            'capacity' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['event_date'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $validated['event_date']);
        if ($validated['event_end_date']) {
            $validated['event_end_date'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $validated['event_end_date']);
        }

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully');
    }

    public function edit(Event $event)
    {
        $statuses = ['scheduled' => 'Scheduled', 'ongoing' => 'Ongoing', 'cancelled' => 'Cancelled', 'completed' => 'Completed'];
        return view('admin.events.edit', compact('event', 'statuses'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'event_date' => 'required|date_format:Y-m-d H:i',
            'event_end_date' => 'nullable|date_format:Y-m-d H:i|after:event_date',
            'location' => 'nullable|string|max:255',
            'location_details' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'status' => 'required|string|in:scheduled,ongoing,cancelled,completed',
            'capacity' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['event_date'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $validated['event_date']);
        if ($validated['event_end_date']) {
            $validated['event_end_date'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $validated['event_end_date']);
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully');
    }
}
