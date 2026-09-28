<?php

namespace App\Http\Controllers\Admin;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

class SiteSettingController extends Controller
{
    public function index()
    {
        Cache::forget('site_settings');

        $settings = [
            'site_name' => SiteSetting::get('site_name', 'Newsmedia'),
            'site_tagline' => SiteSetting::get('site_tagline', 'Portal Berita Terkini'),
            'site_description' => SiteSetting::get('site_description', ''),
            'logo_url' => SiteSetting::get('logo_url'),
            'favicon_url' => SiteSetting::get('favicon_url'),
            'primary_color' => SiteSetting::get('primary_color', '#3b82f6'),
            'secondary_color' => SiteSetting::get('secondary_color', '#1f2937'),
            'contact_email' => SiteSetting::get('contact_email', ''),
            'contact_phone' => SiteSetting::get('contact_phone', ''),
            'contact_address' => SiteSetting::get('contact_address', ''),
            'facebook_url' => SiteSetting::get('facebook_url', ''),
            'twitter_url' => SiteSetting::get('twitter_url', ''),
            'instagram_url' => SiteSetting::get('instagram_url', ''),
            'youtube_url' => SiteSetting::get('youtube_url', ''),
            'timezone' => SiteSetting::get('timezone', 'Asia/Jakarta'),
            'posts_per_page' => SiteSetting::get('posts_per_page', 10),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
            'primary_color' => 'required|regex:/^#[0-9a-fA-F]{6}$/',
            'secondary_color' => 'required|regex:/^#[0-9a-fA-F]{6}$/',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'contact_address' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'timezone' => 'required|timezone',
            'posts_per_page' => 'required|integer|min:5|max:50',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            SiteSetting::put('logo_url', $path);
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            SiteSetting::put('favicon_url', $path);
        }

        SiteSetting::put('site_name', $validated['site_name']);
        SiteSetting::put('site_tagline', $validated['site_tagline']);
        SiteSetting::put('site_description', $validated['site_description']);
        SiteSetting::put('primary_color', $validated['primary_color']);
        SiteSetting::put('secondary_color', $validated['secondary_color']);
        SiteSetting::put('contact_email', $validated['contact_email']);
        SiteSetting::put('contact_phone', $validated['contact_phone']);
        SiteSetting::put('contact_address', $validated['contact_address']);
        SiteSetting::put('facebook_url', $validated['facebook_url']);
        SiteSetting::put('twitter_url', $validated['twitter_url']);
        SiteSetting::put('instagram_url', $validated['instagram_url']);
        SiteSetting::put('youtube_url', $validated['youtube_url']);
        SiteSetting::put('timezone', $validated['timezone']);
        SiteSetting::put('posts_per_page', $validated['posts_per_page']);

        Cache::forget('site_settings');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan situs berhasil diperbarui');
    }
}
