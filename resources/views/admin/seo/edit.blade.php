<x-admin.layout-modern>
    <x-slot name="header">
        Edit SEO Setting - Update Page Settings
    </x-slot>

    <div class="max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('admin.seo-settings.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                <i class="fas fa-arrow-left"></i> Back to SEO Settings
            </a>
        </div>

        <form action="{{ route('admin.seo-settings.update', $seoSetting) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Page Information</h3>
                </div>

                <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm text-blue-800"><strong>Page Name:</strong> {{ $seoSetting->page_name }}</p>
                </div>

                <div>
                    <label for="page_title" class="block text-sm font-semibold text-gray-900 mb-2">Page Title *</label>
                    <input type="text" id="page_title" name="page_title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('page_title') border-red-500 @enderror" value="{{ old('page_title', $seoSetting->page_title) }}">
                    @error('page_title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Meta Tags -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Meta Tags</h3>
                </div>

                <div>
                    <label for="meta_title" class="block text-sm font-semibold text-gray-900 mb-2">Meta Title (max 60 chars)</label>
                    <input type="text" id="meta_title" name="meta_title" maxlength="60" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('meta_title', $seoSetting->meta_title) }}" placeholder="Used in search engine results">
                    <p class="text-xs text-gray-500 mt-1">Used in search engine results</p>
                </div>

                <div>
                    <label for="meta_description" class="block text-sm font-semibold text-gray-900 mb-2">Meta Description (max 160 chars)</label>
                    <textarea id="meta_description" name="meta_description" rows="3" maxlength="160" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Shown in search engine results">{{ old('meta_description', $seoSetting->meta_description) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Shown in search engine results</p>
                </div>

                <div>
                    <label for="meta_keywords" class="block text-sm font-semibold text-gray-900 mb-2">Meta Keywords</label>
                    <input type="text" id="meta_keywords" name="meta_keywords" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('meta_keywords', $seoSetting->meta_keywords) }}" placeholder="Comma separated keywords">
                </div>

                <div>
                    <label for="canonical_url" class="block text-sm font-semibold text-gray-900 mb-2">Canonical URL</label>
                    <input type="url" id="canonical_url" name="canonical_url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('canonical_url', $seoSetting->canonical_url) }}" placeholder="https://example.com/page">
                </div>
            </div>

            <!-- Open Graph Tags -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Open Graph (OG) Tags</h3>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="og_title" class="block text-sm font-semibold text-gray-900 mb-2">OG Title</label>
                        <input type="text" id="og_title" name="og_title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('og_title', $seoSetting->og_title) }}" placeholder="For social media sharing">
                    </div>
                    <div>
                        <label for="og_type" class="block text-sm font-semibold text-gray-900 mb-2">OG Type</label>
                        <input type="text" id="og_type" name="og_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('og_type', $seoSetting->og_type) }}" placeholder="website, article">
                    </div>
                </div>

                <div>
                    <label for="og_description" class="block text-sm font-semibold text-gray-900 mb-2">OG Description</label>
                    <textarea id="og_description" name="og_description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Description for social media">{{ old('og_description', $seoSetting->og_description) }}</textarea>
                </div>

                <div>
                    <label for="og_image" class="block text-sm font-semibold text-gray-900 mb-2">OG Image URL</label>
                    <input type="url" id="og_image" name="og_image" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('og_image', $seoSetting->og_image) }}" placeholder="https://example.com/image.jpg">
                </div>
            </div>

            <!-- Twitter Card Tags -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Twitter Card Tags</h3>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="twitter_title" class="block text-sm font-semibold text-gray-900 mb-2">Twitter Title</label>
                        <input type="text" id="twitter_title" name="twitter_title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('twitter_title', $seoSetting->twitter_title) }}" placeholder="For Twitter">
                    </div>
                    <div>
                        <label for="twitter_card" class="block text-sm font-semibold text-gray-900 mb-2">Twitter Card Type</label>
                        <select id="twitter_card" name="twitter_card" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Type</option>
                            <option value="summary" {{ old('twitter_card', $seoSetting->twitter_card) === 'summary' ? 'selected' : '' }}>Summary</option>
                            <option value="summary_large_image" {{ old('twitter_card', $seoSetting->twitter_card) === 'summary_large_image' ? 'selected' : '' }}>Summary Large Image</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="twitter_description" class="block text-sm font-semibold text-gray-900 mb-2">Twitter Description</label>
                    <textarea id="twitter_description" name="twitter_description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Description for Twitter">{{ old('twitter_description', $seoSetting->twitter_description) }}</textarea>
                </div>

                <div>
                    <label for="twitter_image" class="block text-sm font-semibold text-gray-900 mb-2">Twitter Image URL</label>
                    <input type="url" id="twitter_image" name="twitter_image" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('twitter_image', $seoSetting->twitter_image) }}" placeholder="https://example.com/image.jpg">
                </div>
            </div>

            <!-- Robots & Sitemap -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Robots & Sitemap</h3>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="index" value="1" {{ old('index', $seoSetting->index) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-900">Allow Index</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="follow" value="1" {{ old('follow', $seoSetting->follow) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-900">Allow Follow</span>
                    </label>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="sitemap_priority" class="block text-sm font-semibold text-gray-900 mb-2">Sitemap Priority (0-1)</label>
                        <input type="number" id="sitemap_priority" name="sitemap_priority" step="0.1" min="0" max="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('sitemap_priority', $seoSetting->sitemap_priority) }}">
                    </div>
                    <div>
                        <label for="sitemap_changefreq" class="block text-sm font-semibold text-gray-900 mb-2">Change Frequency</label>
                        <select id="sitemap_changefreq" name="sitemap_changefreq" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Frequency</option>
                            <option value="always" {{ old('sitemap_changefreq', $seoSetting->sitemap_changefreq) === 'always' ? 'selected' : '' }}>Always</option>
                            <option value="hourly" {{ old('sitemap_changefreq', $seoSetting->sitemap_changefreq) === 'hourly' ? 'selected' : '' }}>Hourly</option>
                            <option value="daily" {{ old('sitemap_changefreq', $seoSetting->sitemap_changefreq) === 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('sitemap_changefreq', $seoSetting->sitemap_changefreq) === 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ old('sitemap_changefreq', $seoSetting->sitemap_changefreq) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ old('sitemap_changefreq', $seoSetting->sitemap_changefreq) === 'yearly' ? 'selected' : '' }}>Yearly</option>
                            <option value="never" {{ old('sitemap_changefreq', $seoSetting->sitemap_changefreq) === 'never' ? 'selected' : '' }}>Never</option>
                        </select>
                    </div>
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $seoSetting->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-900">Active</span>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium transition-colors">
                    <i class="fas fa-save"></i> Update SEO Setting
                </button>
                <a href="{{ route('admin.seo-settings.index') }}" class="inline-flex items-center gap-2 bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300 font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin.layout-modern>
