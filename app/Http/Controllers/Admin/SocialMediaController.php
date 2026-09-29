<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function index()
    {
        $socialMedias = SocialMedia::ordered()->paginate(10);
        return view('admin.social-media.index', compact('socialMedias'));
    }

    public function create()
    {
        return view('admin.social-media.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|string|unique:social_media',
            'icon' => 'required|string',
            'url' => 'required|url',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        SocialMedia::create($validated);

        return redirect()->route('admin.social-media.index')
            ->with('success', 'Social media link created successfully');
    }

    public function edit(SocialMedia $socialMedia)
    {
        return view('admin.social-media.edit', compact('socialMedia'));
    }

    public function update(Request $request, SocialMedia $socialMedia)
    {
        $validated = $request->validate([
            'platform' => 'required|string|unique:social_media,platform,' . $socialMedia->id,
            'icon' => 'required|string',
            'url' => 'required|url',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $socialMedia->update($validated);

        return redirect()->route('admin.social-media.index')
            ->with('success', 'Social media link updated successfully');
    }

    public function destroy(SocialMedia $socialMedia)
    {
        $socialMedia->delete();

        return redirect()->route('admin.social-media.index')
            ->with('success', 'Social media link deleted successfully');
    }
}
