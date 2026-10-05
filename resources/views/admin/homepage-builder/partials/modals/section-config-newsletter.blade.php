<!-- Newsletter Config Modal -->
<div id="configModal_newsletter"
    class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Newsletter Signup Settings</h2>
                <p class="text-sm text-gray-600 mt-1">Configure the newsletter subscription section</p>
            </div>
            <button type="button" onclick="closeConfigModal('newsletter')"
                class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-times text-gray-600 text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="configForm_newsletter" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_newsletter" value="">

            <!-- Button Text -->
            <div>
                <label for="button_text_newsletter" class="block text-sm font-semibold text-gray-900 mb-2">
                    Button Text
                </label>
                <input type="text" id="button_text_newsletter" name="config[button_text]" value="Subscribe"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="text-sm text-gray-600 mt-1">Text displayed on the subscribe button</p>
            </div>

            <!-- Placeholder Text -->
            <div>
                <label for="placeholder_text_newsletter" class="block text-sm font-semibold text-gray-900 mb-2">
                    Email Placeholder Text
                </label>
                <input type="text" id="placeholder_text_newsletter" name="config[placeholder_text]"
                    value="Enter your email address"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="text-sm text-gray-600 mt-1">Placeholder text in the email input field</p>
            </div>

            <!-- Background Color -->
            <div>
                <label for="background_color_newsletter" class="block text-sm font-semibold text-gray-900 mb-2">
                    Background Color
                </label>
                <div class="flex items-center gap-3">
                    <input type="color" id="background_color_newsletter" name="config[background_color]"
                        value="#4F46E5" class="w-14 h-10 border border-gray-400 rounded-lg cursor-pointer">
                    <input type="text" id="background_color_text_newsletter" name="config[background_color_text]"
                        value="#4F46E5"
                        class="flex-1 px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-mono text-sm">
                </div>
                <p class="text-sm text-gray-600 mt-1">Choose a background color for the newsletter section</p>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeConfigModal('newsletter')"
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

<script>
    // Sync color picker and text input
    const colorPicker_newsletter = document.getElementById('background_color_newsletter');
    const colorText_newsletter = document.getElementById('background_color_text_newsletter');

    if (colorPicker_newsletter && colorText_newsletter) {
        colorPicker_newsletter.addEventListener('input', function() {
            colorText_newsletter.value = this.value;
        });

        colorText_newsletter.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                colorPicker_newsletter.value = this.value;
            }
        });
    }
</script>
