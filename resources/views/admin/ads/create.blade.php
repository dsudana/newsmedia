<x-admin-layout-modern>
    <div class="space-y-6 pr-4 max-w-4xl">
        <!-- Header Section -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create New Ad</h1>
            <p class="text-sm text-gray-600 mt-1">Create a new advertisement campaign</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data"
                class="p-8 space-y-8">
                @csrf

                <!-- Basic Info Section -->
                <div class="space-y-6">
                    <h2
                        class="text-lg font-semibold text-gray-900 flex items-center gap-2 pb-4 border-b border-gray-200">
                        <i class="fas fa-info-circle text-indigo-600"></i>
                        Basic Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-900 mb-3">
                                <i class="fas fa-heading mr-2 text-indigo-600"></i>Ad Name (Internal)
                            </label>
                            <input type="text" name="name" id="name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 placeholder-gray-500"
                                placeholder="e.g., Summer Campaign 2026" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div>
                            <label for="placement" class="block text-sm font-semibold text-gray-900 mb-3">
                                <i class="fas fa-cube mr-2 text-indigo-600"></i>Placement
                            </label>
                            <select name="placement" id="placement"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900"
                                required>
                                <option value="">Select Placement</option>
                                <option value="header">📍 Header (Home Top)</option>
                                <option value="sidebar">📍 Sidebar</option>
                                <option value="in_article">📍 Inside Article</option>
                                <option value="footer">📍 Footer</option>
                            </select>
                            @error('placement')
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="type" class="block text-sm font-semibold text-gray-900 mb-3">
                                <i class="fas fa-image mr-2 text-indigo-600"></i>Ad Type
                            </label>
                            <select name="type" id="type"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900"
                                required x-data x-on:change="$dispatch('type-change', $event.target.value)">
                                <option value="banner" {{ old('type') == 'banner' ? 'selected' : '' }}>🖼️ Image Banner
                                </option>
                                <option value="adsense" {{ old('type') == 'adsense' ? 'selected' : '' }}>📊 AdSense
                                </option>
                                <option value="script" {{ old('type') == 'script' ? 'selected' : '' }}>💻 Custom Script
                                </option>
                            </select>
                            @error('type')
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Ad Content Section -->
                <div class="space-y-6">
                    <h2
                        class="text-lg font-semibold text-gray-900 flex items-center gap-2 pb-4 border-b border-gray-200">
                        <i class="fas fa-file-alt text-indigo-600"></i>
                        Ad Content
                    </h2>

                    <div x-data="{ type: '{{ old('type', 'banner') }}' }" x-on:type-change.window="type = $event.detail" class="space-y-6">

                        <!-- Banner Image Section -->
                        <div x-show="type === 'banner'" class="p-6 bg-blue-50 rounded-lg border border-blue-200">
                            <div>
                                <label for="image" class="block text-sm font-semibold text-gray-900 mb-3">
                                    <i class="fas fa-image mr-2 text-indigo-600"></i>Ad Image
                                </label>
                                <div
                                    class="relative border-2 border-dashed border-blue-300 rounded-lg p-6 text-center hover:border-blue-400 transition">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-blue-400 mb-2"></i>
                                    <input type="file" name="image" id="image" accept="image/*"
                                        class="absolute inset-0 opacity-0 cursor-pointer" aria-label="Upload image">
                                    <p class="text-sm text-gray-600">Click to upload or drag and drop</p>
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 10MB</p>
                                </div>
                                @error('image')
                                    <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div class="mt-6">
                                <label for="url" class="block text-sm font-semibold text-gray-900 mb-3">
                                    <i class="fas fa-link mr-2 text-indigo-600"></i>Destination URL
                                </label>
                                <input type="url" name="url" id="url"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 placeholder-gray-500"
                                    placeholder="https://example.com" value="{{ old('url') }}">
                                @error('url')
                                    <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Script Section -->
                        <div x-show="type === 'script' || type === 'adsense'" style="display: none;"
                            class="p-6 bg-purple-50 rounded-lg border border-purple-200">
                            <label for="script" class="block text-sm font-semibold text-gray-900 mb-3">
                                <i class="fas fa-code mr-2 text-indigo-600"></i>Script / Code
                            </label>
                            <textarea name="script" id="script" rows="6"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 font-mono text-sm placeholder-gray-500"
                                placeholder="Paste your AdSense code or custom script here...">{{ old('script') }}</textarea>
                            @error('script')
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Schedule Section -->
                <div class="space-y-6">
                    <h2
                        class="text-lg font-semibold text-gray-900 flex items-center gap-2 pb-4 border-b border-gray-200">
                        <i class="fas fa-calendar-alt text-indigo-600"></i>
                        Schedule (Optional)
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-semibold text-gray-900 mb-3">
                                <i class="fas fa-play-circle mr-2 text-green-600"></i>Start Date
                            </label>
                            <input type="date" name="start_date" id="start_date"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900"
                                value="{{ old('start_date') }}">
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-semibold text-gray-900 mb-3">
                                <i class="fas fa-stop-circle mr-2 text-red-600"></i>End Date
                            </label>
                            <input type="date" name="end_date" id="end_date"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900"
                                value="{{ old('end_date') }}">
                        </div>
                    </div>
                </div>

                <!-- Status Section -->
                <div class="space-y-6">
                    <h2
                        class="text-lg font-semibold text-gray-900 flex items-center gap-2 pb-4 border-b border-gray-200">
                        <i class="fas fa-toggle-on text-indigo-600"></i>
                        Status
                    </h2>

                    <label
                        class="flex items-center gap-3 p-4 bg-indigo-50 border border-indigo-200 rounded-lg cursor-pointer hover:bg-indigo-100 transition">
                        <input type="checkbox" name="is_active" value="1"
                            class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="font-semibold text-gray-900">Activate this ad</span>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between pt-8 border-t border-gray-200">
                    <a href="{{ route('admin.ads.index') }}"
                        class="px-6 py-3 bg-gray-200 text-gray-900 rounded-lg font-semibold hover:bg-gray-300 transition-colors flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Ads</span>
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg font-semibold hover:shadow-lg transition-shadow flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Create Ad</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-x-admin-layout-modern>
