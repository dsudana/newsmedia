<div id="configModal_breaking_news_strip"
    class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-red-600 to-red-700 text-white p-6 border-b">
            <h3 class="text-2xl font-bold">Breaking News Strip Settings</h3>
            <p class="text-sm text-red-50 mt-1">Configure the breaking news horizontal carousel</p>
        </div>

        <form id="configForm_breaking_news_strip" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_breaking_news_strip" value="">

            <div>
                <label for="title_breaking" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-heading text-red-600 mr-2"></i>Section Title (Optional)
                </label>
                <input type="text" id="title_breaking" name="title" placeholder="e.g., Breaking News"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="limit_breaking" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-list text-red-600 mr-2"></i>Number of Articles
                    </label>
                    <input type="number" id="limit_breaking" name="config[limit]" value="12" min="4"
                        max="30"
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

                <div>
                    <label for="speed_breaking" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-tachometer-alt text-red-600 mr-2"></i>Auto-scroll Speed (ms)
                    </label>
                    <input type="number" id="speed_breaking" name="config[slider_speed]" value="3000" min="1000"
                        max="10000" step="500"
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
            </div>
        </form>

        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('breaking_news_strip')"
                class="px-6 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveSectionConfig('breaking_news_strip')"
                class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:opacity-90 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('loadSectionConfigBreakingNewsStrip', function(e) {
        const section = e.detail.section;
        document.getElementById('title_breaking').value = section.title || '';
        document.getElementById('limit_breaking').value = section.config?.limit || '12';
        document.getElementById('speed_breaking').value = section.config?.slider_speed || '3000';
    });
</script>
