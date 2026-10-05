<x-admin.layout-modern>
    <div class="space-y-6 pr-4">
        <!-- Header Section -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
            <p class="text-sm text-gray-600 mt-1">Configure your site settings and preferences</p>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-4 rounded-lg flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- General Settings -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-cogs text-indigo-600"></i>
                        General Settings
                    </h2>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="site_name" class="block text-sm font-semibold text-gray-900 mb-2">
                                <i class="fas fa-globe mr-2 text-indigo-600"></i>Site Name
                            </label>
                            <input type="text" name="site_name" id="site_name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                value="{{ old('site_name', $settings['site_name'] ?? '') }}" placeholder="NEWSMEDIA">
                        </div>

                        <div>
                            <label for="contact_email" class="block text-sm font-semibold text-gray-900 mb-2">
                                <i class="fas fa-envelope mr-2 text-indigo-600"></i>Contact Email
                            </label>
                            <input type="email" name="contact_email" id="contact_email"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                                placeholder="admin@retnews.com">
                        </div>
                    </div>

                    <div>
                        <label for="site_description" class="block text-sm font-semibold text-gray-900 mb-2">
                            <i class="fas fa-align-left mr-2 text-indigo-600"></i>Site Description
                        </label>
                        <textarea name="site_description" id="site_description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Describe your site...">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="site_logo" class="block text-sm font-semibold text-gray-900 mb-2">
                                <i class="fas fa-image mr-2 text-indigo-600"></i>Site Logo
                            </label>
                            @if (!empty($settings['site_logo']))
                                <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Site Logo"
                                        class="h-12 w-auto object-contain">
                                </div>
                            @endif
                            <input type="file" name="site_logo" id="site_logo" accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="site_favicon" class="block text-sm font-semibold text-gray-900 mb-2">
                                <i class="fas fa-star mr-2 text-indigo-600"></i>Favicon
                            </label>
                            @if (!empty($settings['site_favicon']))
                                <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                                    <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon"
                                        class="h-8 w-8 object-contain">
                                </div>
                            @endif
                            <input type="file" name="site_favicon" id="site_favicon" accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label for="footer_text" class="block text-sm font-semibold text-gray-900 mb-2">
                            <i class="fas fa-minus mr-2 text-indigo-600"></i>Footer Text
                        </label>
                        <input type="text" name="footer_text" id="footer_text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            value="{{ old('footer_text', $settings['footer_text'] ?? '') }}"
                            placeholder="© 2026 NEWSMEDIA. All rights reserved.">
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-search text-indigo-600"></i>
                        SEO Settings
                    </h2>
                </div>

                <div class="p-6 space-y-6">
                    <div>
                        <label for="seo_title" class="block text-sm font-semibold text-gray-900 mb-2">
                            Default SEO Title
                        </label>
                        <input type="text" name="seo_title" id="seo_title"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            value="{{ old('seo_title', $settings['seo_title'] ?? '') }}"
                            placeholder="NEWSMEDIA - Latest News & Stories">
                    </div>

                    <div>
                        <label for="seo_description" class="block text-sm font-semibold text-gray-900 mb-2">
                            Default SEO Description
                        </label>
                        <textarea name="seo_description" id="seo_description" rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Enter default SEO description...">{{ old('seo_description', $settings['seo_description'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-share-alt text-indigo-600"></i>
                        Social Media
                    </h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="social_facebook" class="block text-sm font-semibold text-gray-900 mb-2">
                                <i class="fab fa-facebook mr-2 text-blue-600"></i>Facebook URL
                            </label>
                            <input type="url" name="social_facebook" id="social_facebook"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}"
                                placeholder="https://facebook.com/...">
                        </div>

                        <div>
                            <label for="social_twitter" class="block text-sm font-semibold text-gray-900 mb-2">
                                <i class="fab fa-twitter mr-2 text-blue-400"></i>Twitter URL
                            </label>
                            <input type="url" name="social_twitter" id="social_twitter"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}"
                                placeholder="https://twitter.com/...">
                        </div>

                        <div>
                            <label for="social_instagram" class="block text-sm font-semibold text-gray-900 mb-2">
                                <i class="fab fa-instagram mr-2 text-pink-600"></i>Instagram URL
                            </label>
                            <input type="url" name="social_instagram" id="social_instagram"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}"
                                placeholder="https://instagram.com/...">
                        </div>

                        <div>
                            <label for="social_youtube" class="block text-sm font-semibold text-gray-900 mb-2">
                                <i class="fab fa-youtube mr-2 text-red-600"></i>YouTube URL
                            </label>
                            <input type="url" name="social_youtube" id="social_youtube"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}"
                                placeholder="https://youtube.com/...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end gap-3">
                <button type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg font-semibold hover:shadow-lg transition-shadow flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>Save Settings</span>
                </button>
            </div>
        </form>
    </div>
    </x-x-admin-layout-modern>
