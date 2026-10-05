<!-- Image Banner Config Modal -->
<div id="configModal_image_banner" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Image Banner Settings</h2>
                <p class="text-sm text-gray-600 mt-1">Configure the banner image and overlay text</p>
            </div>
            <button type="button" onclick="closeConfigModal('image_banner')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-times text-gray-600 text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="configForm_image_banner" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_image_banner" value="">

            <!-- Image URL -->
            <div>
                <label for="image_url_image_banner" class="block text-sm font-semibold text-gray-900 mb-2">
                    Image URL
                </label>
                <input type="url" id="image_url_image_banner" name="config[image_url]" placeholder="https://example.com/image.jpg"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="text-sm text-gray-600 mt-1">Full URL to the banner image</p>
            </div>

            <!-- Title -->
            <div>
                <label for="title_image_banner" class="block text-sm font-semibold text-gray-900 mb-2">
                    Title
                </label>
                <input type="text" id="title_image_banner" name="config[title]" placeholder="Banner Title"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Subtitle -->
            <div>
                <label for="subtitle_image_banner" class="block text-sm font-semibold text-gray-900 mb-2">
                    Subtitle
                </label>
                <input type="text" id="subtitle_image_banner" name="config[subtitle]" placeholder="Banner Subtitle"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- CTA Text -->
            <div>
                <label for="cta_text_image_banner" class="block text-sm font-semibold text-gray-900 mb-2">
                    Call-to-Action Text
                </label>
                <input type="text" id="cta_text_image_banner" name="config[cta_text]" placeholder="Learn More"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- CTA URL -->
            <div>
                <label for="cta_url_image_banner" class="block text-sm font-semibold text-gray-900 mb-2">
                    Call-to-Action URL
                </label>
                <input type="url" id="cta_url_image_banner" name="config[cta_url]" placeholder="https://example.com"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Height Mode -->
            <div>
                <label for="height_mode_image_banner" class="block text-sm font-semibold text-gray-900 mb-2">
                    Height Mode
                </label>
                <select id="height_mode_image_banner" name="config[height_mode]"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="auto">Auto (Image Aspect Ratio)</option>
                    <option value="sm" selected>Small (300px)</option>
                    <option value="md">Medium (500px)</option>
                    <option value="lg">Large (700px)</option>
                    <option value="xl">Extra Large (900px)</option>
                </select>
            </div>

            <!-- Alignment -->
            <div>
                <label for="alignment_image_banner" class="block text-sm font-semibold text-gray-900 mb-2">
                    Text Alignment
                </label>
                <select id="alignment_image_banner" name="config[alignment]"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="left">Left</option>
                    <option value="center" selected>Center</option>
                    <option value="right">Right</option>
                </select>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeConfigModal('image_banner')"
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
