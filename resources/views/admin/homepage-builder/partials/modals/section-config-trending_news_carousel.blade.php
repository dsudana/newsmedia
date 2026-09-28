<!-- Trending News Carousel Config Modal Wrapper -->
<div id="configModal_trending_news_carousel" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-gradient-to-r from-orange-500 to-yellow-600 text-white p-6 border-b">
            <h3 class="text-2xl font-bold">Trending News Carousel Settings</h3>
            <p class="text-sm text-orange-50 mt-1">Configure carousel behavior and appearance</p>
        </div>

        <!-- Modal Body -->
        <form id="configForm_trending_news_carousel" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_trending_news_carousel" value="">

            <!-- Title -->
            <div>
                <label for="title_trending" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-heading text-orange-500 mr-2"></i>Section Title
                </label>
                <input type="text" id="title_trending" name="title" placeholder="e.g., Trending Now"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            </div>

            <!-- Description -->
            <div>
                <label for="desc_trending" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-align-left text-orange-500 mr-2"></i>Description
                </label>
                <textarea id="desc_trending" name="description" rows="2" placeholder="Optional description"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"></textarea>
            </div>

            <!-- Grid: Columns & Limit -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="columns_trending" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-columns text-orange-500 mr-2"></i>Columns Per Slide
                    </label>
                    <select id="columns_trending" name="config[columns]"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="1">1 Column</option>
                        <option value="2">2 Columns</option>
                        <option value="3" selected>3 Columns</option>
                        <option value="4">4 Columns</option>
                        <option value="5">5 Columns</option>
                    </select>
                </div>

                <div>
                    <label for="limit_trending" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-list text-orange-500 mr-2"></i>Total Articles
                    </label>
                    <input type="number" id="limit_trending" name="config[limit]" value="12" min="3" max="50"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
            </div>

            <!-- Grid: Container Width & Speed -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="container_width_trending" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-expand text-orange-500 mr-2"></i>Container Width (%)
                    </label>
                    <input type="number" id="container_width_trending" name="config[container_width]" value="100" min="50" max="100" step="5"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>

                <div>
                    <label for="speed_trending" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-tachometer-alt text-orange-500 mr-2"></i>Slider Speed (ms)
                    </label>
                    <input type="number" id="speed_trending" name="config[slider_speed]" value="3000" min="1000" max="10000" step="100"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
            </div>

            <!-- Sort By -->
            <div>
                <label for="sort_trending" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-sort text-orange-500 mr-2"></i>Sort By
                </label>
                <select id="sort_trending" name="config[sort_by]"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <option value="views" selected>Most Views (Trending)</option>
                    <option value="popular">Most Popular</option>
                    <option value="latest">Latest Published</option>
                </select>
            </div>
        </form>

        <!-- Modal Footer -->
        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('trending_news_carousel')"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveSectionConfig('trending_news_carousel')"
                    class="px-6 py-2 bg-gradient-to-r from-orange-500 to-yellow-600 text-white rounded-lg hover:opacity-90 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </div>
</div>

<script>
    // Auto-populate fields when loading section config for trending carousel
    document.addEventListener('loadSectionConfigTrendingCarousel', function(e) {
        const section = e.detail.section;
        document.getElementById('title_trending').value = section.title || '';
        document.getElementById('desc_trending').value = section.description || '';
        document.getElementById('columns_trending').value = section.config?.columns || '3';
        document.getElementById('limit_trending').value = section.config?.limit || '12';
        document.getElementById('container_width_trending').value = section.config?.container_width || '100';
        document.getElementById('speed_trending').value = section.config?.slider_speed || '3000';
        document.getElementById('sort_trending').value = section.config?.sort_by || 'views';
    });
</script>
