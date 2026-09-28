<!-- Trending News Carousel Configuration Modal -->
<div id="trendingCarouselModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-96 overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-gradient-to-r from-orange-500 to-yellow-600 text-white p-6 border-b">
            <h3 class="text-2xl font-bold">Configure Trending News Carousel</h3>
            <p class="text-sm text-orange-50 mt-1">Customize the carousel behavior and appearance</p>
        </div>

        <!-- Content -->
        <form id="trendingCarouselForm" class="p-6 space-y-6">
            <!-- Title -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-heading text-orange-500 mr-2"></i>Section Title
                </label>
                <input type="text" name="title" placeholder="e.g., Trending Now"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-align-left text-orange-500 mr-2"></i>Description
                </label>
                <textarea name="description" rows="2" placeholder="Optional description for this section"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"></textarea>
            </div>

            <!-- Grid: Columns Per Slide -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-columns text-orange-500 mr-2"></i>Columns Per Slide
                    </label>
                    <select name="columns" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="1">1 Column</option>
                        <option value="2">2 Columns</option>
                        <option value="3" selected>3 Columns</option>
                        <option value="4">4 Columns</option>
                        <option value="5">5 Columns</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Articles shown per slide</p>
                </div>

                <!-- Article Limit -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-list text-orange-500 mr-2"></i>Total Articles
                    </label>
                    <input type="number" name="limit" value="12" min="3" max="50"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Total articles to display</p>
                </div>
            </div>

            <!-- Grid: Container Width & Slider Speed -->
            <div class="grid grid-cols-2 gap-4">
                <!-- Container Width -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-expand text-orange-500 mr-2"></i>Container Width
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="range" name="container_width" value="100" min="50" max="100" step="5"
                               class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                               oninput="document.querySelector('[data-width-display]').textContent = this.value + '%'">
                        <span data-width-display class="text-sm font-bold text-orange-600 min-w-12">100%</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Set carousel width percentage</p>
                </div>

                <!-- Slider Speed -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-tachometer-alt text-orange-500 mr-2"></i>Slider Speed (ms)
                    </label>
                    <input type="number" name="slider_speed" value="3000" min="1000" max="10000" step="100"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Auto-scroll speed in milliseconds</p>
                </div>
            </div>

            <!-- Sort By -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-sort text-orange-500 mr-2"></i>Sort By
                </label>
                <select name="sort_by" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <option value="views" selected>Most Views (Trending)</option>
                    <option value="popular">Most Popular</option>
                    <option value="latest">Latest Published</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">How to sort articles in carousel</p>
            </div>

            <!-- Slide Padding -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-ruler-horizontal text-orange-500 mr-2"></i>Slide Padding
                </label>
                <select name="slide_padding" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <option value="px-2">Small (8px)</option>
                    <option value="px-3">Medium (12px)</option>
                    <option value="px-4" selected>Large (16px)</option>
                    <option value="px-6">Extra Large (24px)</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Space around each slide</p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-900">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    <strong>Preview:</strong> The carousel will auto-scroll continuously with navigation arrows.
                </p>
            </div>
        </form>

        <!-- Footer -->
        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeTrendingCarouselModal()"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveTrendingCarouselConfig()"
                    class="px-6 py-2 bg-gradient-to-r from-orange-500 to-yellow-600 text-white rounded-lg hover:opacity-90 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Configuration
            </button>
        </div>
    </div>
</div>

<script>
    function openTrendingCarouselModal(sectionData = null) {
        const modal = document.getElementById('trendingCarouselModal');
        const form = document.getElementById('trendingCarouselForm');

        if (sectionData) {
            // Edit mode
            form.title.value = sectionData.title || '';
            form.description.value = sectionData.description || '';
            form.columns.value = sectionData.config?.columns || '3';
            form.limit.value = sectionData.config?.limit || '12';
            form.container_width.value = sectionData.config?.container_width || '100';
            form.slider_speed.value = sectionData.config?.slider_speed || '3000';
            form.sort_by.value = sectionData.config?.sort_by || 'views';
            form.slide_padding.value = sectionData.config?.slide_padding || 'px-4';
            document.querySelector('[data-width-display]').textContent = form.container_width.value + '%';
        } else {
            // Create mode - reset form
            form.reset();
            document.querySelector('[data-width-display]').textContent = '100%';
        }

        modal.classList.remove('hidden');
    }

    function closeTrendingCarouselModal() {
        document.getElementById('trendingCarouselModal').classList.add('hidden');
    }

    function saveTrendingCarouselConfig() {
        const form = document.getElementById('trendingCarouselForm');
        const config = {
            columns: parseInt(form.columns.value),
            limit: parseInt(form.limit.value),
            container_width: parseInt(form.container_width.value),
            slider_speed: parseInt(form.slider_speed.value),
            sort_by: form.sort_by.value,
            slide_padding: form.slide_padding.value,
        };

        // Dispatch event for parent component to handle
        window.dispatchEvent(new CustomEvent('trendingCarouselConfigSaved', {
            detail: {
                title: form.title.value,
                description: form.description.value,
                config: config,
                type: 'trending_news_carousel',
            }
        }));

        closeTrendingCarouselModal();
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeTrendingCarouselModal();
        }
    });
</script>
