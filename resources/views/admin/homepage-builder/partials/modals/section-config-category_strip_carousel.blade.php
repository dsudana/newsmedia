<div id="configModal_category_strip_carousel"
    class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6 border-b">
            <h3 class="text-2xl font-bold">Category Strip Carousel Settings</h3>
            <p class="text-sm text-blue-50 mt-1">Configure category carousel display</p>
        </div>

        <form id="configForm_category_strip_carousel" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_category_strip_carousel" value="">

            <div>
                <label for="title_catstrip" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-heading text-blue-600 mr-2"></i>Section Title
                </label>
                <input type="text" id="title_catstrip" name="title" placeholder="e.g., Berita Terbaru"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category_catstrip" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-folder text-blue-600 mr-2"></i>Category
                    </label>
                    <select id="category_catstrip" name="config[category]"
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Categories</option>
                        @foreach (\App\Models\Category::all() as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="limit_catstrip" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-list text-blue-600 mr-2"></i>Number of Articles
                    </label>
                    <input type="number" id="limit_catstrip" name="config[limit]" value="12" min="6"
                        max="30"
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label for="speed_catstrip" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-tachometer-alt text-blue-600 mr-2"></i>Auto-scroll Speed (ms)
                </label>
                <input type="number" id="speed_catstrip" name="config[slider_speed]" value="3000" min="1000"
                    max="10000" step="500"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </form>

        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('category_strip_carousel')"
                class="px-6 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveSectionConfig('category_strip_carousel')"
                class="px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:opacity-90 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('loadSectionConfigCategoryStripCarousel', function(e) {
        const section = e.detail.section;
        document.getElementById('title_catstrip').value = section.title || '';
        document.getElementById('category_catstrip').value = section.config?.category || '';
        document.getElementById('limit_catstrip').value = section.config?.limit || '12';
        document.getElementById('speed_catstrip').value = section.config?.slider_speed || '3000';
    });
</script>
