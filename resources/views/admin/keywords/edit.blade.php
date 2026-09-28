<x-admin-layout-modern>
    <div class="space-y-6 pr-4 max-w-3xl">
        <!-- Header Section -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Keyword</h1>
            <p class="text-sm text-gray-600 mt-1">Update keyword settings and SEO configuration</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('admin.keywords.update', $keyword) }}" method="POST" class="p-8 space-y-8">
                @csrf
                @method('PUT')

                <!-- Basic Info Section -->
                <div class="space-y-6">
                    <h2
                        class="text-lg font-semibold text-gray-900 flex items-center gap-2 pb-4 border-b border-gray-200">
                        <i class="fas fa-info-circle text-indigo-600"></i>
                        Basic Information
                    </h2>

                    <div>
                        <label for="keyword" class="block text-sm font-semibold text-gray-900 mb-3">
                            <i class="fas fa-key mr-2 text-indigo-600"></i>Keyword
                        </label>
                        <input type="text" name="keyword" id="keyword"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900"
                            value="{{ old('keyword', $keyword->keyword) }}" required>
                        @error('keyword')
                            <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-gray-900 mb-3">
                            <i class="fas fa-folder mr-2 text-indigo-600"></i>Category
                        </label>
                        <select name="category_id" id="category_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900"
                            required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $keyword->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-900 mb-3">
                            <i class="fas fa-align-left mr-2 text-indigo-600"></i>Description (Optional)
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 placeholder-gray-500">{{ old('description', $keyword->description) }}</textarea>
                    </div>
                </div>

                <!-- SEO Settings Section -->
                <div class="space-y-6">
                    <h2
                        class="text-lg font-semibold text-gray-900 flex items-center gap-2 pb-4 border-b border-gray-200">
                        <i class="fas fa-search text-indigo-600"></i>
                        SEO Settings
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="intent" class="block text-sm font-semibold text-gray-900 mb-3">
                                <i class="fas fa-bullseye mr-2 text-indigo-600"></i>Search Intent
                            </label>
                            <select name="intent" id="intent"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900"
                                required>
                                <option value="">Select Intent</option>
                                <option value="informational"
                                    {{ old('intent', $keyword->intent) == 'informational' ? 'selected' : '' }}>
                                    📚 Informational (Learn)
                                </option>
                                <option value="navigational"
                                    {{ old('intent', $keyword->intent) == 'navigational' ? 'selected' : '' }}>
                                    🧭 Navigational (Find)
                                </option>
                                <option value="transactional"
                                    {{ old('intent', $keyword->intent) == 'transactional' ? 'selected' : '' }}>
                                    🛒 Transactional (Buy)
                                </option>
                                <option value="commercial"
                                    {{ old('intent', $keyword->intent) == 'commercial' ? 'selected' : '' }}>
                                    💼 Commercial (Compare)
                                </option>
                            </select>
                            @error('intent')
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div>
                            <label for="focus_tone" class="block text-sm font-semibold text-gray-900 mb-3">
                                <i class="fas fa-microphone mr-2 text-indigo-600"></i>Focus Tone
                            </label>
                            <input type="text" name="focus_tone" id="focus_tone"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900"
                                value="{{ old('focus_tone', $keyword->focus_tone) }}" required>
                            @error('focus_tone')
                                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="target_words" class="block text-sm font-semibold text-gray-900 mb-3">
                            <i class="fas fa-list-ol mr-2 text-indigo-600"></i>Target Word Count
                        </label>
                        <div class="relative">
                            <input type="number" name="target_words" id="target_words"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900"
                                min="500" max="5000" value="{{ old('target_words', $keyword->target_words) }}"
                                required>
                            <p class="text-xs text-gray-500 mt-2">Minimum: 500 words, Maximum: 5000 words</p>
                        </div>
                        @error('target_words')
                            <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- AI Settings Section -->
                <div class="space-y-6">
                    <h2
                        class="text-lg font-semibold text-gray-900 flex items-center gap-2 pb-4 border-b border-gray-200">
                        <i class="fas fa-robot text-indigo-600"></i>
                        AI Settings
                    </h2>

                    <label
                        class="flex items-center gap-3 p-4 bg-indigo-50 border border-indigo-200 rounded-lg cursor-pointer hover:bg-indigo-100 transition">
                        <input type="checkbox" name="use_humanizer" value="1"
                            class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            {{ old('use_humanizer', $keyword->use_humanizer) ? 'checked' : '' }}>
                        <span class="font-semibold text-gray-900">Use AI Humanizer for generated content</span>
                    </label>
                </div>

                <!-- Status Info -->
                <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase">Status</p>
                        <div class="mt-2">
                            <span
                                class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $keyword->status === 'generated' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                <i
                                    class="fas {{ $keyword->status === 'generated' ? 'fa-check-circle' : 'fa-hourglass-half' }} mr-1.5"></i>
                                {{ ucfirst($keyword->status) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase">Generated Articles</p>
                        <p class="text-lg font-bold text-indigo-600 mt-2">{{ $keyword->articles_count ?? 0 }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between pt-8 border-t border-gray-200">
                    <a href="{{ route('admin.keywords.index') }}"
                        class="px-6 py-3 bg-gray-200 text-gray-900 rounded-lg font-semibold hover:bg-gray-300 transition-colors flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Keywords</span>
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg font-semibold hover:shadow-lg transition-shadow flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Update Keyword</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-x-admin-layout-modern>
