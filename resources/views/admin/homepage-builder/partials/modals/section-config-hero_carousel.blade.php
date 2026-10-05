<!-- Hero Carousel Config Modal Wrapper -->
<div id="configModal_hero_carousel"
    class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-gradient-to-r from-red-600 to-red-700 text-white p-6 border-b">
            <h3 class="text-2xl font-bold">Hero Carousel Settings</h3>
            <p class="text-sm text-red-50 mt-1">Configure the main carousel and side featured articles</p>
        </div>

        <!-- Modal Body -->
        <form id="configForm_hero_carousel" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_hero_carousel" value="">

            <!-- Title -->
            <div>
                <label for="title_hero" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-heading text-red-600 mr-2"></i>Section Title (Optional)
                </label>
                <input type="text" id="title_hero" name="title" placeholder="e.g., Featured News"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>

            <!-- Description -->
            <div>
                <label for="desc_hero" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-align-left text-red-600 mr-2"></i>Description (Optional)
                </label>
                <textarea id="desc_hero" name="description" rows="2" placeholder="Optional description"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"></textarea>
            </div>

            <!-- Grid: Main & Side Limits -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="main_limit_hero" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-images text-red-600 mr-2"></i>Main Carousel Articles
                    </label>
                    <input type="number" id="main_limit_hero" name="config[main_limit]" value="5" min="2"
                        max="10"
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <p class="text-sm text-gray-600 mt-1">Articles in main carousel (left)</p>
                </div>

                <div>
                    <label for="side_limit_hero" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-list text-red-600 mr-2"></i>Side Featured Cards
                    </label>
                    <input type="number" id="side_limit_hero" name="config[side_limit]" value="2" min="1"
                        max="5"
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <p class="text-sm text-gray-600 mt-1">Static cards (right side)</p>
                </div>
            </div>

            <!-- Grid: Autoplay & Container Width -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="autoplay_hero" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-film text-red-600 mr-2"></i>Autoplay Speed (ms)
                    </label>
                    <input type="number" id="autoplay_hero" name="config[autoplay_speed]" value="4000" min="1000"
                        max="10000" step="500"
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <p class="text-sm text-gray-600 mt-1">Time before auto-advance</p>
                </div>

                <div>
                    <label for="container_width_hero" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-expand text-red-600 mr-2"></i>Container Width (%)
                    </label>
                    <input type="number" id="container_width_hero" name="config[container_width]" value="100"
                        min="50" max="100" step="5"
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-900">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    <strong>Auto-populated:</strong> Main carousel and side cards are automatically filled with the most
                    popular articles
                </p>
            </div>
        </form>

        <!-- Modal Footer -->
        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('hero_carousel')"
                class="px-6 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveSectionConfig('hero_carousel')"
                class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:opacity-90 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </div>
</div>

<script>
    // Auto-populate fields when loading section config for hero carousel
    document.addEventListener('loadSectionConfigHeroCarousel', function(e) {
        const section = e.detail.section;
        document.getElementById('title_hero').value = section.title || '';
        document.getElementById('desc_hero').value = section.description || '';
        document.getElementById('main_limit_hero').value = section.config?.main_limit || '5';
        document.getElementById('side_limit_hero').value = section.config?.side_limit || '2';
        document.getElementById('autoplay_hero').value = section.config?.autoplay_speed || '4000';
        document.getElementById('container_width_hero').value = section.config?.container_width || '100';
    });
</script>
