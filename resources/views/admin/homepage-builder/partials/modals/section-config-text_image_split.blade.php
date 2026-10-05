<!-- Text Image Split Config Modal -->
<div id="configModal_text_image_split"
    class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Text Image Split Settings</h2>
                <p class="text-sm text-gray-600 mt-1">Configure the side-by-side text and image layout</p>
            </div>
            <button type="button" onclick="closeConfigModal('text_image_split')"
                class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-times text-gray-600 text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="configForm_text_image_split" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_text_image_split" value="">

            <!-- Title -->
            <div>
                <label for="title_text_image_split" class="block text-sm font-semibold text-gray-900 mb-2">
                    Title
                </label>
                <input type="text" id="title_text_image_split" name="config[title]" placeholder="Section Title"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Description -->
            <div>
                <label for="description_text_image_split" class="block text-sm font-semibold text-gray-900 mb-2">
                    Description
                </label>
                <textarea id="description_text_image_split" name="config[description]" rows="4"
                    placeholder="Enter section description"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
            </div>

            <!-- CTA Text -->
            <div>
                <label for="cta_text_text_image_split" class="block text-sm font-semibold text-gray-900 mb-2">
                    Call-to-Action Text
                </label>
                <input type="text" id="cta_text_text_image_split" name="config[cta_text]" placeholder="Click Here"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- CTA URL -->
            <div>
                <label for="cta_url_text_image_split" class="block text-sm font-semibold text-gray-900 mb-2">
                    Call-to-Action URL
                </label>
                <input type="url" id="cta_url_text_image_split" name="config[cta_url]"
                    placeholder="https://example.com"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Image URL -->
            <div>
                <label for="image_url_text_image_split" class="block text-sm font-semibold text-gray-900 mb-2">
                    Image URL
                </label>
                <input type="url" id="image_url_text_image_split" name="config[image_url]"
                    placeholder="https://example.com/image.jpg"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Image Position -->
            <div>
                <label for="image_position_text_image_split" class="block text-sm font-semibold text-gray-900 mb-2">
                    Image Position
                </label>
                <select id="image_position_text_image_split" name="config[image_position]"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="left">Image on Left</option>
                    <option value="right" selected>Image on Right</option>
                </select>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeConfigModal('text_image_split')"
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
