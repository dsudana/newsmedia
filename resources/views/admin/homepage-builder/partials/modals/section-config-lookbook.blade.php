<!-- Lookbook Config Modal -->
<div id="configModal_lookbook"
    class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Lookbook Settings</h2>
                <p class="text-sm text-gray-600 mt-1">Configure visual lookbook gallery</p>
            </div>
            <button type="button" onclick="closeConfigModal('lookbook')"
                class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-times text-gray-600 text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="configForm_lookbook" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_lookbook" value="">

            <!-- Tag Filter -->
            <div>
                <label for="tag_filter_lookbook" class="block text-sm font-semibold text-gray-900 mb-2">
                    Tag Filter (Optional)
                </label>
                <input type="text" id="tag_filter_lookbook" name="config[tag_filter]"
                    placeholder="e.g., summer, promotion, featured"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="text-sm text-gray-600 mt-1">Filter lookbook items by tag (comma-separated)</p>
            </div>

            <!-- Title -->
            <div>
                <label for="title_lookbook" class="block text-sm font-semibold text-gray-900 mb-2">
                    Section Title
                </label>
                <input type="text" id="title_lookbook" name="config[title]" placeholder="Lookbook Gallery"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Style -->
            <div>
                <label for="style_lookbook" class="block text-sm font-semibold text-gray-900 mb-2">
                    Display Style
                </label>
                <select id="style_lookbook" name="config[style]"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="light" selected>Light</option>
                    <option value="dark">Dark</option>
                </select>
                <p class="text-sm text-gray-600 mt-1">Choose the color scheme for the lookbook display</p>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeConfigModal('lookbook')"
                    class="flex-1 px-4 py-2 border border-gray-400 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
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
