<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $generalSettings = Setting::where('category', 'general')->get()->pluck('value', 'key');
        $seoSettings = Setting::where('category', 'seo')->get()->pluck('value', 'key');
        $socialSettings = Setting::where('category', 'social')->get()->pluck('value', 'key');
        $aiSettings = Setting::where('category', 'ai')->get()->pluck('value', 'key');

        return view('admin.settings.index', compact(
            'generalSettings',
            'seoSettings',
            'socialSettings',
            'aiSettings'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'site_logo' => 'nullable|image|max:2048',
            'site_favicon' => 'nullable|image|max:1024',
            'contact_email' => 'nullable|email|max:255',
            'footer_text' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_youtube' => 'nullable|url',
            'anthropic_api_key' => 'nullable|string',
        ]);

        $generalSettings = ['site_name', 'site_description', 'contact_email', 'footer_text'];
        $seoSettings = ['seo_title', 'seo_description'];
        $socialSettings = ['social_facebook', 'social_twitter', 'social_instagram', 'social_youtube'];
        $aiSettings = ['anthropic_api_key'];

        foreach ($validated as $key => $value) {
            if ($key === 'site_logo' || $key === 'site_favicon') {
                continue;
            }

            $category = 'general';
            if (in_array($key, $seoSettings)) $category = 'seo';
            if (in_array($key, $socialSettings)) $category = 'social';
            if (in_array($key, $aiSettings)) $category = 'ai';

            Setting::set($key, $value, 'string', $category);
        }

        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $path, 'image', 'general');
        }

        if ($request->hasFile('site_favicon')) {
            $path = $request->file('site_favicon')->store('settings', 'public');
            Setting::set('site_favicon', $path, 'image', 'general');
        }

        if ($validated['anthropic_api_key'] ?? false) {
            $this->updateEnvVar('ANTHROPIC_API_KEY', $validated['anthropic_api_key']);
        }

        return redirect()->back()->with('success', 'Settings berhasil diperbarui');
    }

    private function updateEnvVar($key, $value)
    {
        $envFile = base_path('.env');
        if (!file_exists($envFile)) {
            return;
        }

        $content = file_get_contents($envFile);
        $pattern = '/^' . preg_quote($key) . '=.*/m';

        // Sanitize value - remove newlines and control characters
        $sanitized = preg_replace('/[\r\n\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $value);

        // Escape quotes
        $escaped = str_replace('"', '\\"', $sanitized);

        // Write with quotes
        $safeValue = '"' . $escaped . '"';

        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, $key . '=' . $safeValue, $content);
        } else {
            // Value didn't exist, append
            $content .= "\n" . $key . '=' . $safeValue;
        }

        file_put_contents($envFile, $content);
    }
}
