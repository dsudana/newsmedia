<!-- Category Highlight Config Modal -->
<div id="configModal_category_highlight" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Category Highlight Settings</h2>
                <p class="text-sm text-gray-600 mt-1">Configure how categories are highlighted</p>
            </div>
            <button type="button" onclick="closeConfigModal('category_highlight')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-times text-gray-600 text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="configForm_category_highlight" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_category_highlight" value="">

            <!-- Limit -->
            <div>
                <label for="limit_category_highlight" class="block text-sm font-semibold text-gray-900 mb-2">
                    Number of Categories
                </label>
                <input type="number" id="limit_category_highlight" name="config[limit]" min="1" max="20" value="6"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="text-sm text-gray-600 mt-1">Number of categories to display</p>
            </div>

            <!-- Columns -->
            <div>
                <label for="columns_category_highlight" class="block text-sm font-semibold text-gray-900 mb-2">
                    Columns Per Row
                </label>
                <select id="columns_category_highlight" name="config[columns]"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="2">2 Columns</option>
                    <option value="3" selected>3 Columns</option>
                    <option value="4">4 Columns</option>
                    <option value="6">6 Columns</option>
                </select>
            </div>

            <!-- Show Article Count -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="config[show_article_count]" value="true" checked
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                    <span class="ml-3 text-gray-700 font-semibold">Display Article Count</span>
                </label>
                <p class="text-sm text-gray-600 mt-1">Show the number of articles in each category</p>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeConfigModal('category_highlight')"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
