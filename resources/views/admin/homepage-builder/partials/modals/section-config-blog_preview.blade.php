<!-- Blog Preview Config Modal -->
<div id="configModal_blog_preview"
    class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Blog Preview Settings</h2>
                <p class="text-sm text-gray-600 mt-1">Configure how blog articles are displayed</p>
            </div>
            <button type="button" onclick="closeConfigModal('blog_preview')"
                class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-times text-gray-600 text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="configForm_blog_preview" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_blog_preview" value="">

            <!-- Limit -->
            <div>
                <label for="limit_blog_preview" class="block text-sm font-semibold text-gray-900 mb-2">
                    Number of Articles
                </label>
                <input type="number" id="limit_blog_preview" name="config[limit]" min="1" max="50"
                    value="6"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="text-sm text-gray-600 mt-1">Number of blog articles to display</p>
            </div>

            <!-- Columns -->
            <div>
                <label for="columns_blog_preview" class="block text-sm font-semibold text-gray-900 mb-2">
                    Columns Per Row
                </label>
                <select id="columns_blog_preview" name="config[columns]"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="1">1 Column</option>
                    <option value="2">2 Columns</option>
                    <option value="3" selected>3 Columns</option>
                    <option value="4">4 Columns</option>
                </select>
            </div>

            <!-- Display Fields -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-3">
                    Display Fields
                </label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="config[fields][]" value="image" checked
                            class="w-4 h-4 text-indigo-600border-gray-400 rounded focus:ring-2 focus:ring-indigo-500">
                        <span class="ml-3 text-gray-700">Featured Image</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="config[fields][]" value="title" checked
                            class="w-4 h-4 text-indigo-600border-gray-400 rounded focus:ring-2 focus:ring-indigo-500">
                        <span class="ml-3 text-gray-700">Title</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="config[fields][]" value="date" checked
                            class="w-4 h-4 text-indigo-600border-gray-400 rounded focus:ring-2 focus:ring-indigo-500">
                        <span class="ml-3 text-gray-700">Publication Date</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="config[fields][]" value="author"
                            class="w-4 h-4 text-indigo-600border-gray-400 rounded focus:ring-2 focus:ring-indigo-500">
                        <span class="ml-3 text-gray-700">Author</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="config[fields][]" value="excerpt" checked
                            class="w-4 h-4 text-indigo-600border-gray-400 rounded focus:ring-2 focus:ring-indigo-500">
                        <span class="ml-3 text-gray-700">Excerpt</span>
                    </label>
                </div>
            </div>

            <!-- Sort By -->
            <div>
                <label for="sort_by_blog_preview" class="block text-sm font-semibold text-gray-900 mb-2">
                    Sort By
                </label>
                <select id="sort_by_blog_preview" name="config[sort_by]"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="latest" selected>Latest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="popular">Most Popular</option>
                </select>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeConfigModal('blog_preview')"
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
