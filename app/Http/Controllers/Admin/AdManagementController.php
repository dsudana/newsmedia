<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdSlot;
use App\Models\AdSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdManagementController extends Controller
{
    public function index()
    {
        $slots = AdSlot::latest()->paginate(20);
        $googleAdsenseCode = AdSetting::get('google_adsense_code');
        $adsEnabled = AdSetting::get('ads_enabled', true);

        return view('admin.ads.index', compact('slots', 'googleAdsenseCode', 'adsEnabled'));
    }

    public function create()
    {
        return view('admin.ads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|in:header,sidebar,content,footer',
            'ad_type' => 'required|in:adsense,manual,affiliate',
            'ad_code' => 'nullable|string',
            'manual_html' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        AdSlot::create($validated);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Ad slot berhasil dibuat');
    }

    public function edit(AdSlot $adSlot)
    {
        return view('admin.ads.edit', compact('adSlot'));
    }

    public function update(Request $request, AdSlot $adSlot)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|in:header,sidebar,content,footer',
            'ad_type' => 'required|in:adsense,manual,affiliate',
            'ad_code' => 'nullable|string',
            'manual_html' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $adSlot->update($validated);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Ad slot berhasil diperbarui');
    }

    public function delete(AdSlot $adSlot)
    {
        $adSlot->delete();

        return redirect()->route('admin.ads.index')
            ->with('success', 'Ad slot berhasil dihapus');
    }

    public function settings()
    {
        $settings = [
            'google_adsense_code' => AdSetting::get('google_adsense_code'),
            'ads_enabled' => AdSetting::get('ads_enabled', true),
        ];

        return view('admin.ads.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'google_adsense_code' => 'nullable|string',
            'ads_enabled' => 'boolean',
        ]);

        AdSetting::put('google_adsense_code', $validated['google_adsense_code']);
        AdSetting::put('ads_enabled', $validated['ads_enabled'] ?? false, 'boolean');

        return redirect()->route('admin.ads.settings')
            ->with('success', 'Pengaturan iklan berhasil disimpan');
    }
}
