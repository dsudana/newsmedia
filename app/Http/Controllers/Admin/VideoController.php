<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with(['category', 'user'])
            ->latest('published_at')
            ->paginate(15);

        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.videos.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:published,draft',
            'published_at' => 'nullable|date',
        ]);

        $youtubeId = Video::extractYoutubeId($validated['youtube_url']);
        if (!$youtubeId) {
            return back()->withErrors(['youtube_url' => 'URL YouTube tidak valid']);
        }

        $validated['youtube_id'] = $youtubeId;
        $validated['thumbnail_url'] = Video::getYoutubeThumbnail($youtubeId);
        $validated['user_id'] = Auth::id();

        if (!$validated['published_at']) {
            $validated['published_at'] = now();
        }

        Video::create($validated);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video berhasil ditambahkan');
    }

    public function edit(Video $video)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.videos.edit', compact('video', 'categories'));
    }

    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:published,draft',
            'published_at' => 'nullable|date',
        ]);

        $youtubeId = Video::extractYoutubeId($validated['youtube_url']);
        if (!$youtubeId) {
            return back()->withErrors(['youtube_url' => 'URL YouTube tidak valid']);
        }

        $validated['youtube_id'] = $youtubeId;
        $validated['thumbnail_url'] = Video::getYoutubeThumbnail($youtubeId);

        if (!$validated['published_at']) {
            $validated['published_at'] = now();
        }

        $video->update($validated);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video berhasil diperbarui');
    }

    public function destroy(Video $video)
    {
        $video->delete();
        return redirect()->route('admin.videos.index')
            ->with('success', 'Video berhasil dihapus');
    }
}
