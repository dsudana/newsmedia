<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::withTrashed()->latest()->paginate(15);

        $total_ads = Advertisement::withTrashed()->count();
        $active_ads = Advertisement::where('is_active', true)->count();
        $inactive_ads = Advertisement::where('is_active', false)->count();
        $placements_count = Advertisement::distinct('placement')->count('placement');

        return view('admin.advertisements.index', compact('advertisements', 'total_ads', 'active_ads', 'inactive_ads', 'placements_count'));
    }

    public function create()
    {
        $types = [
            'banner' => 'Banner Image (Gambar)',
            'adsense' => 'Google AdSense (Script)',
            'script' => 'Custom Script (JavaScript)',
        ];

        $placements = [
            'header_banner' => 'Header Banner (1200x128px)',
            'sidebar_top' => 'Sidebar Atas (300x250px)',
            'sidebar_bottom' => 'Sidebar Bawah (300x600px)',
            'content_middle' => 'Konten Tengah (300x400px)',
        ];

        $sizes = [
            '1200x128' => 'Header Banner - 1200x128px',
            '300x250' => 'Medium Rectangle - 300x250px',
            '300x600' => 'Half Page - 300x600px',
            '300x400' => 'Content Middle - 300x400px',
            '728x90' => 'Leaderboard - 728x90px',
            '970x90' => 'Large Leaderboard - 970x90px',
        ];

        return view('admin.advertisements.create', compact('types', 'placements', 'sizes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:banner,adsense,script',
            'placement' => 'required|string',
            'image' => 'nullable|image|max:5120|required_if:type,banner',
            'url' => 'nullable|url|required_if:type,banner',
            'description' => 'nullable|string',
            'script' => 'nullable|string|required_if:type,adsense|required_if:type,script',
            'size' => 'required|string',
            'width' => 'required|integer|min:100',
            'height' => 'required|integer|min:50',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('advertisements', 'public');
            $validated['image'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        Advertisement::create($validated);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Iklan berhasil ditambahkan');
    }

    public function edit(Advertisement $advertisement)
    {
        $types = [
            'banner' => 'Banner Image (Gambar)',
            'adsense' => 'Google AdSense (Script)',
            'script' => 'Custom Script (JavaScript)',
        ];

        $placements = [
            'header_banner' => 'Header Banner (1200x128px)',
            'sidebar_top' => 'Sidebar Atas (300x250px)',
            'sidebar_bottom' => 'Sidebar Bawah (300x600px)',
            'content_middle' => 'Konten Tengah (300x400px)',
        ];

        $sizes = [
            '1200x128' => 'Header Banner - 1200x128px',
            '300x250' => 'Medium Rectangle - 300x250px',
            '300x600' => 'Half Page - 300x600px',
            '300x400' => 'Content Middle - 300x400px',
            '728x90' => 'Leaderboard - 728x90px',
            '970x90' => 'Large Leaderboard - 970x90px',
        ];

        return view('admin.advertisements.edit', compact('advertisement', 'types', 'placements', 'sizes'));
    }

    public function update(Request $request, Advertisement $advertisement)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:banner,adsense,script',
            'placement' => 'required|string',
            'image' => 'nullable|image|max:5120|required_if:type,banner',
            'url' => 'nullable|url|required_if:type,banner',
            'description' => 'nullable|string',
            'script' => 'nullable|string|required_if:type,adsense|required_if:type,script',
            'size' => 'required|string',
            'width' => 'required|integer|min:100',
            'height' => 'required|integer|min:50',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('advertisements', 'public');
            $validated['image'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        $advertisement->update($validated);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Iklan berhasil diperbarui');
    }

    public function destroy(Advertisement $advertisement)
    {
        $advertisement->delete();

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Iklan berhasil dihapus');
    }

    public function restore($id)
    {
        $advertisement = Advertisement::withTrashed()->findOrFail($id);
        $advertisement->restore();

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Iklan berhasil dipulihkan');
    }

    public function recordView($id)
    {
        try {
            $advertisement = Advertisement::findOrFail($id);
            $advertisement->increment('view_count');
            return response()->json(['success' => true, 'view_count' => $advertisement->view_count]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 404);
        }
    }

    public function recordClick($id)
    {
        try {
            $advertisement = Advertisement::findOrFail($id);
            $advertisement->increment('click_count');
            return response()->json(['success' => true, 'click_count' => $advertisement->click_count]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 404);
        }
    }
}
