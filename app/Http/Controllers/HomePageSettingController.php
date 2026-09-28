<?php

namespace App\Http\Controllers;

use App\Models\HomePageSetting;
use Illuminate\Http\Request;

class HomePageSettingController extends Controller
{
    public function index()
    {
        $settings = HomePageSetting::orderBy('order')->get();
        $availableSections = [
            'hero' => 'Hero Slider',
            'category_strip' => 'Category Strip',
            'recent_articles' => 'Recent Articles',
            'popular_articles' => 'Popular Articles',
            'featured_category' => 'Featured Category',
            'sidebar' => 'Sidebar',
        ];

        return view('admin.home-page-settings.index', compact('settings', 'availableSections'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'sections' => 'required|array',
            'sections.*.section_name' => 'required|string',
            'sections.*.is_enabled' => 'boolean',
            'sections.*.order' => 'integer',
            'sections.*.items_count' => 'integer|min:1|max:20',
        ]);

        foreach ($validated['sections'] as $section) {
            HomePageSetting::updateOrCreate(
                ['section_name' => $section['section_name']],
                [
                    'is_enabled' => $section['is_enabled'] ?? false,
                    'order' => $section['order'] ?? 0,
                    'items_count' => $section['items_count'] ?? 6,
                ]
            );
        }

        return back()->with('success', 'Homepage settings berhasil diperbarui');
    }
}
