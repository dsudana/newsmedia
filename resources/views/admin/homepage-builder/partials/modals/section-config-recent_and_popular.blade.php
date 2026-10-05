<div id="configModal_recent_and_popular"
    class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-purple-600 to-purple-700 text-white p-6 border-b">
            <h3 class="text-2xl font-bold">Recent & Popular Settings</h3>
            <p class="text-sm text-purple-50 mt-1">Configure recent and popular articles display</p>
        </div>

        <form id="configForm_recent_and_popular" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_recent_and_popular" value="">

            <div>
                <label for="title_recentpop" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-heading text-purple-600 mr-2"></i>Section Title (Optional)
                </label>
                <input type="text" id="title_recentpop" name="title" placeholder="e.g., Latest Updates"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="recent_limit" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-clock text-purple-600 mr-2"></i>Recent Articles
                    </label>
                    <input type="number" id="recent_limit" name="config[recent_limit]" value="6" min="2"
                        max="12"
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <div>
                    <label for="popular_limit" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-fire text-purple-600 mr-2"></i>Popular Articles
                    </label>
                    <input type="number" id="popular_limit" name="config[popular_limit]" value="4" min="2"
                        max="10"
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
            </div>
        </form>

        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('recent_and_popular')"
                class="px-6 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveSectionConfig('recent_and_popular')"
                class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:opacity-90 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('loadSectionConfigRecentAndPopular', function(e) {
        const section = e.detail.section;
        document.getElementById('title_recentpop').value = section.title || '';
        document.getElementById('recent_limit').value = section.config?.recent_limit || '6';
        document.getElementById('popular_limit').value = section.config?.popular_limit || '4';
    });
</script>
