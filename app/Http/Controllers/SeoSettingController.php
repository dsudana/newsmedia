<?php

namespace App\Http\Controllers;

use App\Models\SeoSetting;
use Illuminate\Http\Request;

class SeoSettingController extends Controller
{
    public function index()
    {
        $seoSettings = SeoSetting::paginate(15);

        return view('admin.seo.index', compact('seoSettings'));
    }

    public function create()
    {
        $pages = [
            'home' => 'Home Page',
            'blog' => 'Blog Listing',
            'contact' => 'Contact Page',
            'about' => 'About Page',
        ];

        return view('admin.seo.create', compact('pages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_name' => 'required|string|unique:seo_settings|max:100',
            'page_title' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:255',
            'og_image' => 'nullable|url',
            'og_type' => 'nullable|string|max:100',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string|max:255',
            'twitter_image' => 'nullable|url',
            'twitter_card' => 'nullable|string|in:summary,summary_large_image',
            'index' => 'boolean',
            'follow' => 'boolean',
            'sitemap_priority' => 'nullable|numeric|between:0,1',
            'sitemap_changefreq' => 'nullable|string|in:always,hourly,daily,weekly,monthly,yearly,never',
            'is_active' => 'boolean',
        ]);

        SeoSetting::create($validated);

        return redirect()->route('admin.seo-settings.index')
            ->with('success', 'SEO setting created successfully');
    }

    public function edit(SeoSetting $seoSetting)
    {
        return view('admin.seo.edit', compact('seoSetting'));
    }

    public function update(Request $request, SeoSetting $seoSetting)
    {
        $validated = $request->validate([
            'page_title' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:255',
            'og_image' => 'nullable|url',
            'og_type' => 'nullable|string|max:100',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string|max:255',
            'twitter_image' => 'nullable|url',
            'twitter_card' => 'nullable|string|in:summary,summary_large_image',
            'index' => 'boolean',
            'follow' => 'boolean',
            'sitemap_priority' => 'nullable|numeric|between:0,1',
            'sitemap_changefreq' => 'nullable|string|in:always,hourly,daily,weekly,monthly,yearly,never',
            'is_active' => 'boolean',
        ]);

        $seoSetting->update($validated);

        return redirect()->route('admin.seo-settings.index')
            ->with('success', 'SEO setting updated successfully');
    }

    public function destroy(SeoSetting $seoSetting)
    {
        $seoSetting->delete();

        return redirect()->route('admin.seo-settings.index')
            ->with('success', 'SEO setting deleted successfully');
    }
}
