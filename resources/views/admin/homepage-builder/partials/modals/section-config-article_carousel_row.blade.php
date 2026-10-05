<div id="configModal_article_carousel_row"
    class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-md shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-red-600 to-red-700 text-white p-6 border-b">
            <h3 class="text-2xl font-bold">Article Carousel (1-Row) Settings</h3>
            <p class="text-sm text-red-50 mt-1">Configure single-row carousel display</p>
        </div>

        <form id="configForm_article_carousel_row" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_article_carousel_row" value="">

            <div>
                <label for="title_carousel_row" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-heading text-red-600 mr-2"></i>Section Title
                </label>
                <input type="text" id="title_carousel_row" name="title" placeholder="e.g., Latest Articles"
                    class="w-full px-4 py-2 border border-gray-400 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="limit_carousel_row" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-list text-red-600 mr-2"></i>Number of Articles
                    </label>
                    <input type="number" id="limit_carousel_row" name="config[limit]" value="10" min="5"
                        max="25"
                        class="w-full px-4 py-2 border border-gray-400 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

                <div>
                    <label for="columns_carousel_row" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-columns text-red-600 mr-2"></i>Columns Per View
                    </label>
                    <select id="columns_carousel_row" name="config[columns]"
                        class="w-full px-4 py-2 border border-gray-400 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="2">2 Columns</option>
                        <option value="3" selected>3 Columns</option>
                        <option value="4">4 Columns</option>
                        <option value="5">5 Columns</option>
                        <option value="6">6 Columns</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category_carousel_row" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-folder text-red-600 mr-2"></i>Category
                    </label>
                    <select id="category_carousel_row" name="config[category]"
                        class="w-full px-4 py-2 border border-gray-400 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">All Categories</option>
                        @foreach ($categories ?? [] as $category)
                            <option value="{{ $category->slug }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="sort_carousel_row" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-sort text-red-600 mr-2"></i>Sort By
                    </label>
                    <select id="sort_carousel_row" name="config[sort_by]"
                        class="w-full px-4 py-2 border border-gray-400 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="latest">Latest</option>
                        <option value="popular">Most Popular</option>
                        <option value="views">Most Viewed</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="speed_carousel_row" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-tachometer-alt text-red-600 mr-2"></i>Auto-scroll Speed (ms)
                </label>
                <input type="number" id="speed_carousel_row" name="config[slider_speed]" value="3000" min="1000"
                    max="10000" step="500"
                    class="w-full px-4 py-2 border border-gray-400 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent">
                <p class="text-sm text-gray-600 mt-2">Time in milliseconds before slide auto-advances (3000ms = 3
                    seconds)</p>
            </div>
        </form>

        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('article_carousel_row')"
                class="px-6 py-2 border border-gray-400 text-gray-700 rounded-md hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveSectionConfig('article_carousel_row')"
                class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-md hover:opacity-90 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('loadSectionConfigArticleCarouselRow', function(e) {
        const section = e.detail.section;
        document.getElementById('title_carousel_row').value = section.title || '';
        document.getElementById('limit_carousel_row').value = section.config?.limit || '10';
        document.getElementById('columns_carousel_row').value = section.config?.columns || '3';
        document.getElementById('speed_carousel_row').value = section.config?.slider_speed || '3000';
        document.getElementById('category_carousel_row').value = section.config?.category || '';
        document.getElementById('sort_carousel_row').value = section.config?.sort_by || 'latest';
    });
</script>
