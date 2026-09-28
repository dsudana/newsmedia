<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(15);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        $categories = ['info' => 'Info', 'urgent' => 'Urgent', 'warning' => 'Warning', 'maintenance' => 'Maintenance'];
        return view('admin.announcements.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'category' => 'nullable|string|max:50',
            'priority' => 'required|integer|in:1,2,3',
            'starts_at' => 'required|date_format:Y-m-d H:i',
            'ends_at' => 'nullable|date_format:Y-m-d H:i|after:starts_at',
            'is_pinned' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['starts_at'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $validated['starts_at']);
        if ($validated['ends_at']) {
            $validated['ends_at'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $validated['ends_at']);
        }

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully');
    }

    public function edit(Announcement $announcement)
    {
        $categories = ['info' => 'Info', 'urgent' => 'Urgent', 'warning' => 'Warning', 'maintenance' => 'Maintenance'];
        return view('admin.announcements.edit', compact('announcement', 'categories'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'category' => 'nullable|string|max:50',
            'priority' => 'required|integer|in:1,2,3',
            'starts_at' => 'required|date_format:Y-m-d H:i',
            'ends_at' => 'nullable|date_format:Y-m-d H:i|after:starts_at',
            'is_pinned' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['starts_at'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $validated['starts_at']);
        if ($validated['ends_at']) {
            $validated['ends_at'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $validated['ends_at']);
        }

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully');
    }
}
