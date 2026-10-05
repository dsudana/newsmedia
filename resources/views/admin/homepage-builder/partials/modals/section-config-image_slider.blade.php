<div id="configModal_image_slider"
    class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-md shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-gradient-to-r from-purple-600 to-purple-700 text-white p-6 border-b">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold flex items-center gap-2">
                        <i class="fas fa-sliders-h text-purple-300"></i>Image Slider Settings
                    </h3>
                    <p class="text-sm text-purple-50 mt-1">Manage slider images, captions and CTAs</p>
                </div>
                <button type="button" onclick="closeConfigModal('image_slider')"
                    class="p-2 hover:bg-purple-500 rounded-md transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6">
            <form id="configForm_image_slider" class="space-y-6">
                @csrf
                <input type="hidden" id="sectionId_image_slider" value="">

                <!-- Section Title -->
                <div>
                    <label for="title_image_slider" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-heading text-purple-600 mr-2"></i>Section Title (Optional)
                    </label>
                    <input type="text" id="title_image_slider" name="title" placeholder="e.g., Featured Campaign"
                        class="w-full px-4 py-2 border border-gray-400 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <!-- Slider Items Management -->
                <div class="border-t pt-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-images text-purple-600"></i>Slider Items
                    </h4>

                    <div id="sliderItemsContainer_image_slider" class="space-y-4 mb-4">
                        <!-- Items will be added here dynamically -->
                    </div>

                    <button type="button" onclick="addSliderItem('image_slider')"
                        class="w-full px-4 py-3 border-2 border-dashed border-purple-300 text-purple-700 rounded-md hover:bg-purple-50 transition font-semibold flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i>Add Slider Item
                    </button>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex gap-3">
                        <i class="fas fa-info-circle text-blue-600 text-lg flex-shrink-0 mt-0.5"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-2">📸 Image Slider Features:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>Full-width auto-height slider (16:9 aspect ratio)</li>
                                <li>Auto-rotating carousel (5s per slide)</li>
                                <li>Fade transition effect</li>
                                <li>Image overlay with caption and CTA button</li>
                                <li>Responsive design (works on all devices)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('image_slider')"
                class="px-6 py-2 border border-gray-400 text-gray-700 rounded-md hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveSectionConfig('image_slider')"
                class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-md hover:opacity-90 transition font-semibold flex items-center gap-2">
                <i class="fas fa-save"></i>Save Settings
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('loadSectionConfigimageslider', function(e) {
        const section = e.detail.section;
        document.getElementById('title_image_slider').value = section.title || '';

        const itemsContainer = document.getElementById('sliderItemsContainer_image_slider');
        itemsContainer.innerHTML = '';

        const items = section.config?.items || [];
        items.forEach((item, index) => {
            renderSliderItem('image_slider', index, item);
        });

        if (items.length === 0) {
            addSliderItem('image_slider');
        }
    });

    function addSliderItem(sectionType) {
        const container = document.getElementById(`sliderItemsContainer_${sectionType}`);
        const index = container.querySelectorAll('.slider-item-card').length;
        renderSliderItem(sectionType, index, {});
    }

    function renderSliderItem(sectionType, index, item) {
        const container = document.getElementById(`sliderItemsContainer_${sectionType}`);
        const itemHtml = `
            <div class="slider-item-card border border-gray-400 rounded-md p-4 bg-gray-50">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="font-semibold text-gray-900">Slide #${index + 1}</h5>
                    <button type="button" onclick="removeSliderItem(this)" class="text-red-600 hover:text-red-800 text-sm font-semibold">
                        <i class="fas fa-trash mr-1"></i>Remove
                    </button>
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-700 mb-2">
                        <i class="fas fa-image text-purple-600 mr-1"></i>Image
                    </label>
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <input type="hidden" class="slider-image-url" name="config[items][${index}][image_url]" value="${item.image_url || ''}">
                            <div class="slider-image-preview bg-gray-200 rounded border-2 border-dashedborder-gray-400 p-3 text-center cursor-pointer hover:bg-gray-300 transition"
                                 onclick="document.querySelector('.slider-image-input-${index}').click()">
                                ${item.image_url ? `<img src="${item.image_url}" alt="Preview" class="max-h-32 mx-auto rounded">` : '<i class="fas fa-cloud-upload-alt text-3xl text-gray-500 mb-2"></i><p class="text-xs text-gray-600">Click to upload</p>'}
                            </div>
                            <input type="file" class="slider-image-input-${index} hidden" accept="image/*"
                                   onchange="uploadSliderImage(this, ${index}, '${sectionType}')">
                        </div>
                    </div>
                </div>

                <!-- Caption -->
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-700 mb-2">
                        <i class="fas fa-heading text-purple-600 mr-1"></i>Short Caption
                    </label>
                    <textarea name="config[items][${index}][caption]" maxlength="100" rows="2" placeholder="Max 100 characters..."
                              class="w-full px-3 py-2 border border-gray-400 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm resize-none">${item.caption || ''}</textarea>
                </div>

                <!-- CTA Text -->
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-700 mb-2">
                        <i class="fas fa-button text-purple-600 mr-1"></i>CTA Button Text
                    </label>
                    <input type="text" name="config[items][${index}][cta_text]" maxlength="50" placeholder="e.g., Learn More"
                           class="w-full px-3 py-2 border border-gray-400 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm"
                           value="${item.cta_text || ''}">
                </div>

                <!-- CTA URL -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">
                        <i class="fas fa-link text-purple-600 mr-1"></i>CTA URL
                    </label>
                    <input type="url" name="config[items][${index}][cta_url]" placeholder="https://example.com"
                           class="w-full px-3 py-2 border border-gray-400 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm"
                           value="${item.cta_url || ''}">
                </div>
            </div>
        `;

        const div = document.createElement('div');
        div.innerHTML = itemHtml;
        container.appendChild(div.firstElementChild);
    }

    function removeSliderItem(btn) {
        btn.closest('.slider-item-card').remove();
    }

    function uploadSliderImage(input, index, sectionType) {
        const file = input.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);

        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            alert('CSRF token not found');
            return;
        }

        // Show loading state
        const preview = input.closest('.slider-item-card').querySelector('.slider-image-preview');
        preview.innerHTML = '<p class="text-sm text-gray-600">Uploading...</p>';

        fetch('/admin/upload-section-image', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                },
                body: formData
            })
            .then(response => {
                // Log response for debugging
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Server response:', text);
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.url) {
                    const urlInput = input.closest('.slider-item-card').querySelector('.slider-image-url');
                    const preview = input.closest('.slider-item-card').querySelector('.slider-image-preview');
                    urlInput.value = data.url;
                    preview.innerHTML = `<img src="${data.url}" alt="Preview" class="max-h-32 mx-auto rounded">`;
                } else if (data.message) {
                    alert('Upload error: ' + data.message);
                    preview.innerHTML =
                        '<i class="fas fa-cloud-upload-alt text-3xl text-gray-500 mb-2"></i><p class="text-xs text-gray-600">Click to upload</p>';
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                alert('Upload failed: ' + error.message);
                preview.innerHTML =
                    '<i class="fas fa-cloud-upload-alt text-3xl text-gray-500 mb-2"></i><p class="text-xs text-gray-600">Click to upload</p>';
            });
    }
</script>
