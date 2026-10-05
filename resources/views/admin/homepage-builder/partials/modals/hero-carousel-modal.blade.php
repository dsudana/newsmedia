<!-- Hero Carousel Configuration Modal -->
<div id="heroCarouselModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-96 overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-gradient-to-r from-red-600 to-red-700 text-white p-6 border-b">
            <h3 class="text-2xl font-bold">Configure Hero Carousel</h3>
            <p class="text-sm text-red-50 mt-1">Set up the main carousel and side featured articles</p>
        </div>

        <!-- Content -->
        <form id="heroCarouselForm" class="p-6 space-y-6">
            <!-- Title -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-heading text-red-600 mr-2"></i>Section Title (Optional)
                </label>
                <input type="text" name="title" placeholder="e.g., Featured News"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-align-left text-red-600 mr-2"></i>Description (Optional)
                </label>
                <textarea name="description" rows="2" placeholder="Optional description for this section"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"></textarea>
            </div>

            <!-- Grid: Main & Side Limits -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-images text-red-600 mr-2"></i>Main Carousel Articles
                    </label>
                    <input type="number" name="main_limit" value="5" min="2" max="10"
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <p class="text-sm text-gray-600 mt-1">Articles in main carousel (left side)</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-list text-red-600 mr-2"></i>Side Featured Cards
                    </label>
                    <input type="number" name="side_limit" value="2" min="1" max="5"
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <p class="text-sm text-gray-600 mt-1">Static featured cards (right side)</p>
                </div>
            </div>

            <!-- Grid: Autoplay & Container Width -->
            <div class="grid grid-cols-2 gap-4">
                <!-- Autoplay Speed -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-film text-red-600 mr-2"></i>Autoplay Speed (ms)
                    </label>
                    <input type="number" name="autoplay_speed" value="4000" min="1000" max="10000"
                        step="500"
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <p class="text-sm text-gray-600 mt-1">Time before slide auto-advance</p>
                </div>

                <!-- Container Width -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-expand text-red-600 mr-2"></i>Container Width
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="range" name="container_width" value="100" min="50" max="100"
                            step="5" class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                            oninput="document.querySelector('[data-hero-width-display]').textContent = this.value + '%'">
                        <span data-hero-width-display class="text-sm font-bold text-red-600 min-w-12">100%</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Set carousel width percentage</p>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-900 mb-2">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    <strong>How it works:</strong>
                </p>
                <ul class="text-xs text-blue-800 space-y-1 ml-6">
                    <li>✓ Main carousel (left): Auto-advances with navigation arrows</li>
                    <li>✓ Side cards (right): Static featured articles</li>
                    <li>✓ Articles: Auto-selected by popularity (views count)</li>
                    <li>✓ Responsive: Adapts beautifully on mobile & tablet</li>
                </ul>
            </div>
        </form>

        <!-- Footer -->
        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeHeroCarouselModal()"
                class="px-6 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveHeroCarouselConfig()"
                class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:opacity-90 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Configuration
            </button>
        </div>
    </div>
</div>

<script>
    function openHeroCarouselModal(sectionData = null) {
        const modal = document.getElementById('heroCarouselModal');
        const form = document.getElementById('heroCarouselForm');

        if (sectionData) {
            // Edit mode
            form.title.value = sectionData.title || '';
            form.description.value = sectionData.description || '';
            form.main_limit.value = sectionData.config?.main_limit || '5';
            form.side_limit.value = sectionData.config?.side_limit || '2';
            form.autoplay_speed.value = sectionData.config?.autoplay_speed || '4000';
            form.container_width.value = sectionData.config?.container_width || '100';
            document.querySelector('[data-hero-width-display]').textContent = form.container_width.value + '%';
        } else {
            // Create mode - reset form
            form.reset();
            document.querySelector('[data-hero-width-display]').textContent = '100%';
        }

        modal.classList.remove('hidden');
    }

    function closeHeroCarouselModal() {
        document.getElementById('heroCarouselModal').classList.add('hidden');
    }

    function saveHeroCarouselConfig() {
        const form = document.getElementById('heroCarouselForm');
        const config = {
            main_limit: parseInt(form.main_limit.value),
            side_limit: parseInt(form.side_limit.value),
            autoplay_speed: parseInt(form.autoplay_speed.value),
            container_width: parseInt(form.container_width.value),
        };

        // Dispatch event for parent component to handle
        window.dispatchEvent(new CustomEvent('heroCarouselConfigSaved', {
            detail: {
                title: form.title.value,
                description: form.description.value,
                config: config,
                type: 'hero_carousel',
            }
        }));

        closeHeroCarouselModal();
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeHeroCarouselModal();
        }
    });
</script>
