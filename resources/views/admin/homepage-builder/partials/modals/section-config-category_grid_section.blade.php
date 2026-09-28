<div id="configModal_category_grid_section" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-teal-600 to-teal-700 text-white p-6 border-b">
            <h3 class="text-2xl font-bold">Category Grid Settings</h3>
            <p class="text-sm text-teal-50 mt-1">Configure category grid display</p>
        </div>

        <form id="configForm_category_grid_section" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_category_grid_section" value="">

            <div>
                <label for="title_catgrid" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-heading text-teal-600 mr-2"></i>Section Title
                </label>
                <input type="text" id="title_catgrid" name="title" placeholder="e.g., Lifestyle"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category_catgrid" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-folder text-teal-600 mr-2"></i>Category
                    </label>
                    <select id="category_catgrid" name="config[category]"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        @foreach(\App\Models\Category::all() as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="limit_catgrid" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-list text-teal-600 mr-2"></i>Number of Articles
                    </label>
                    <input type="number" id="limit_catgrid" name="config[limit]" value="8" min="4" max="20"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>
            </div>
        </form>

        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('category_grid_section')"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveSectionConfig('category_grid_section')"
                    class="px-6 py-2 bg-gradient-to-r from-teal-600 to-teal-700 text-white rounded-lg hover:opacity-90 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('loadSectionConfigCategoryGridSection', function(e) {
        const section = e.detail.section;
        document.getElementById('title_catgrid').value = section.title || '';
        document.getElementById('category_catgrid').value = section.config?.category || '';
        document.getElementById('limit_catgrid').value = section.config?.limit || '8';
    });
</script>
