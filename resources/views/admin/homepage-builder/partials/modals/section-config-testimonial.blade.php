<!-- Testimonial Config Modal -->
<div id="configModal_testimonial" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Testimonial Settings</h2>
                <p class="text-sm text-gray-600 mt-1">Configure customer testimonials display</p>
            </div>
            <button type="button" onclick="closeConfigModal('testimonial')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-times text-gray-600 text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="configForm_testimonial" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_testimonial" value="">

            <!-- Limit -->
            <div>
                <label for="limit_testimonial" class="block text-sm font-semibold text-gray-900 mb-2">
                    Number of Testimonials
                </label>
                <input type="number" id="limit_testimonial" name="config[limit]" min="1" max="20" value="5"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="text-sm text-gray-600 mt-1">Number of testimonials to display</p>
            </div>

            <!-- Background Color -->
            <div>
                <label for="background_color_testimonial" class="block text-sm font-semibold text-gray-900 mb-2">
                    Background Color
                </label>
                <div class="flex items-center gap-3">
                    <input type="color" id="background_color_testimonial" name="config[background_color]" value="#F9FAFB"
                           class="w-14 h-10 border border-gray-300 rounded-lg cursor-pointer">
                    <input type="text" id="background_color_text_testimonial" name="config[background_color_text]" value="#F9FAFB"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-mono text-sm">
                </div>
                <p class="text-sm text-gray-600 mt-1">Choose a background color for the testimonials section</p>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeConfigModal('testimonial')"
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

<script>
    // Sync color picker and text input
    const colorPicker_testimonial = document.getElementById('background_color_testimonial');
    const colorText_testimonial = document.getElementById('background_color_text_testimonial');

    if (colorPicker_testimonial && colorText_testimonial) {
        colorPicker_testimonial.addEventListener('input', function() {
            colorText_testimonial.value = this.value;
        });

        colorText_testimonial.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                colorPicker_testimonial.value = this.value;
            }
        });
    }
</script>
