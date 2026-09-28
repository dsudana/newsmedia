<div id="configModal_video_grid" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-gradient-to-r from-red-600 to-red-700 text-white p-6 border-b">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold flex items-center gap-2">
                        <i class="fas fa-youtube text-red-300"></i>Video Grid Settings
                    </h3>
                    <p class="text-sm text-red-50 mt-1">Configure YouTube video section with preview</p>
                </div>
                <button type="button" onclick="closeConfigModal('video_grid')" class="p-2 hover:bg-red-500 rounded-lg transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6">
            <form id="configForm_video_grid" class="space-y-6">
                @csrf
                <input type="hidden" id="sectionId_video_grid" value="">

                <!-- Section Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title_video_grid" class="block text-sm font-semibold text-gray-900 mb-2">
                            <i class="fas fa-heading text-red-600 mr-2"></i>Section Title
                        </label>
                        <input type="text" id="title_video_grid" name="title" placeholder="e.g., Our Latest Videos" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="limit_video_grid" class="block text-sm font-semibold text-gray-900 mb-2">
                            <i class="fas fa-list text-red-600 mr-2"></i>Number of Videos
                        </label>
                        <input type="number" id="limit_video_grid" name="config[limit]" value="9" min="1" max="50"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description_video_grid" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-align-left text-red-600 mr-2"></i>Section Description
                    </label>
                    <textarea id="description_video_grid" name="config[description]" rows="2" placeholder="Optional subtitle or description..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"></textarea>
                </div>

                <!-- Layout Options -->
                <div class="border-t pt-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-th text-red-600"></i>Layout & Display
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="columns_video_grid" class="block text-xs font-semibold text-gray-700 mb-2">
                                Columns (Desktop)
                            </label>
                            <select id="columns_video_grid" name="config[columns]"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                                <option value="1">1 Column</option>
                                <option value="2">2 Columns</option>
                                <option value="3" selected>3 Columns</option>
                                <option value="4">4 Columns</option>
                                <option value="5">5 Columns</option>
                                <option value="6">6 Columns</option>
                            </select>
                        </div>

                        <div>
                            <label for="aspect_ratio_video_grid" class="block text-xs font-semibold text-gray-700 mb-2">
                                Thumbnail Ratio
                            </label>
                            <select id="aspect_ratio_video_grid" name="config[aspect_ratio]"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                                <option value="16/9" selected>16:9 (Widescreen)</option>
                                <option value="4/3">4:3 (Standard)</option>
                                <option value="1/1">1:1 (Square)</option>
                                <option value="9/16">9:16 (Portrait)</option>
                            </select>
                        </div>

                        <div>
                            <label for="category_video_grid" class="block text-xs font-semibold text-gray-700 mb-2">
                                Filter by Category
                            </label>
                            <select id="category_video_grid" name="config[category_id]"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                                <option value="">All Videos</option>
                                @foreach(\App\Models\Category::orderBy('name')->get() as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Display Options -->
                <div class="border-t pt-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-eye text-red-600"></i>Display Options
                    </h4>

                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" id="show_title_video_grid" name="config[show_title]" value="1" checked
                                   class="w-4 h-4 text-red-600 rounded focus:ring-2 focus:ring-red-500">
                            <div>
                                <span class="text-sm font-semibold text-gray-900">Show Video Title</span>
                                <p class="text-xs text-gray-600">Display video name below thumbnail</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" id="show_category_video_grid" name="config[show_category]" value="1" checked
                                   class="w-4 h-4 text-red-600 rounded focus:ring-2 focus:ring-red-500">
                            <div>
                                <span class="text-sm font-semibold text-gray-900">Show Category Badge</span>
                                <p class="text-xs text-gray-600">Display category tag on thumbnail</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" id="show_views_video_grid" name="config[show_views]" value="1" checked
                                   class="w-4 h-4 text-red-600 rounded focus:ring-2 focus:ring-red-500">
                            <div>
                                <span class="text-sm font-semibold text-gray-900">Show View Count</span>
                                <p class="text-xs text-gray-600">Display number of views on thumbnail</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" id="open_youtube_video_grid" name="config[open_youtube]" value="1" checked
                                   class="w-4 h-4 text-red-600 rounded focus:ring-2 focus:ring-red-500">
                            <div>
                                <span class="text-sm font-semibold text-gray-900">Open on YouTube (New Tab)</span>
                                <p class="text-xs text-gray-600">Click video opens YouTube in new window</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Video Preview Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex gap-3">
                        <i class="fas fa-info-circle text-blue-600 text-lg flex-shrink-0 mt-0.5"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-2">📺 Video Grid Features:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>YouTube video thumbnails with play button overlay</li>
                                <li>Hover effects and smooth animations</li>
                                <li>Fully responsive (1 col mobile, 2 col tablet, 3+ col desktop)</li>
                                <li>Videos managed in Admin → Videos section</li>
                                <li>Auto-generated thumbnails from YouTube CDN</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <p class="text-xs font-semibold text-amber-900 mb-2">💡 Need to manage videos?</p>
                    <a href="{{ route('admin.videos.index') }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-amber-700 hover:text-amber-900 font-semibold">
                        <i class="fas fa-arrow-right"></i>Go to Video Management
                    </a>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('video_grid')"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveSectionConfig('video_grid')"
                    class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:opacity-90 transition font-semibold flex items-center gap-2">
                <i class="fas fa-save"></i>Save Settings
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('loadSectionConfigVideoGrid', function(e) {
        const section = e.detail.section;
        document.getElementById('title_video_grid').value = section.title || '';
        document.getElementById('description_video_grid').value = section.config?.description || '';
        document.getElementById('limit_video_grid').value = section.config?.limit || '9';
        document.getElementById('category_video_grid').value = section.config?.category_id || '';
        document.getElementById('columns_video_grid').value = section.config?.columns || '3';
        document.getElementById('aspect_ratio_video_grid').value = section.config?.aspect_ratio || '16/9';

        // Display options
        document.getElementById('show_title_video_grid').checked = section.config?.show_title !== false;
        document.getElementById('show_category_video_grid').checked = section.config?.show_category !== false;
        document.getElementById('show_views_video_grid').checked = section.config?.show_views !== false;
        document.getElementById('open_youtube_video_grid').checked = section.config?.open_youtube !== false;
    });
</script>
