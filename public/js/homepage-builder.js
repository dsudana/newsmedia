/**
 * Homepage Builder - AJAX and Drag-Drop Functionality
 * Handles section management, reordering, and configuration
 */

// Global variables
let pageType = null;
let currentSectionId = null;
let currentSectionType = null;
let sortableInstance = null;

/**
 * Show toast notification
 * @param {string} message - The message to display
 * @param {string} type - The type of notification (success, error, info, warning)
 */
function showToast(message, type = 'info') {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'fixed bottom-4 right-4 z-50 space-y-2';
        document.body.appendChild(toastContainer);
    }

    // Determine toast styling based on type
    const typeStyles = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        info: 'bg-blue-500 text-white',
        warning: 'bg-yellow-500 text-white'
    };

    const typeClass = typeStyles[type] || typeStyles.info;

    // Create toast element
    const toast = document.createElement('div');
    toast.className = `${typeClass} px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 animate-fade-in min-w-[300px]`;

    const iconClassMap = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        info: 'fa-info-circle',
        warning: 'fa-warning'
    };

    // Create icon element
    const icon = document.createElement('i');
    icon.className = `fas ${iconClassMap[type] || iconClassMap.info}`;

    // Create message span
    const messageSpan = document.createElement('span');
    messageSpan.textContent = message;

    // Append elements
    toast.appendChild(icon);
    toast.appendChild(messageSpan);
    toastContainer.appendChild(toast);

    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.classList.add('animate-fade-out');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

/**
 * Open configuration modal for a section
 * @param {number} sectionId - The section ID
 * @param {string} sectionType - The section type
 */
function openConfigModal(sectionId, sectionType) {
    currentSectionId = sectionId;
    currentSectionType = sectionType;

    const modal = document.getElementById(`configModal_${sectionType}`);
    if (modal) {
        modal.classList.remove('hidden');
        loadSectionConfig(sectionId, sectionType);
    } else {
        showToast('Configuration modal not found for this section type', 'error');
    }
}

/**
 * Close configuration modal
 * @param {string} sectionType - The section type (optional, if provided closes only that modal)
 */
function closeConfigModal(sectionType) {
    if (sectionType) {
        const modal = document.getElementById(`configModal_${sectionType}`);
        if (modal) {
            modal.classList.add('hidden');
        }
    } else {
        const modals = document.querySelectorAll('[id^="configModal_"]');
        modals.forEach(modal => {
            modal.classList.add('hidden');
        });
    }

    currentSectionId = null;
    currentSectionType = null;
}

/**
 * Load section configuration via AJAX
 * @param {number} sectionId - The section ID
 * @param {string} sectionType - The section type
 */
function loadSectionConfig(sectionId, sectionType) {
    fetch(`/admin/homepage-builder/${sectionId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.section) {
            // Populate form fields with section data
            const section = data.section;
            const form = document.getElementById(`configForm_${sectionType}`);

            if (form) {
                // Set form values from section data
                if (section.title) {
                    const titleField = form.querySelector('[name="title"]');
                    if (titleField) titleField.value = section.title;
                }

                // Set config values
                if (section.config) {
                    Object.keys(section.config).forEach(key => {
                        const field = form.querySelector(`[name="config[${key}]"]`);
                        if (field) {
                            if (field.type === 'checkbox') {
                                field.checked = section.config[key];
                            } else {
                                field.value = section.config[key];
                            }
                        }
                    });
                }
            }
        } else {
            showToast('Failed to load section configuration', 'error');
        }
    })
    .catch(error => {
        console.error('Error loading configuration:', error);
        showToast('An error occurred while loading the configuration', 'error');
    });
}

/**
 * Delete a section via AJAX
 * @param {number} sectionId - The section ID to delete
 */
function deleteSection(sectionId) {
    if (!confirm('Are you sure you want to delete this section? This action cannot be undone.')) {
        return;
    }

    fetch(`/admin/homepage-builder/${sectionId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('Section deleted successfully', 'success');
            // Remove the section element from DOM
            const sectionElement = document.querySelector(`[data-section-id="${sectionId}"]`);
            if (sectionElement) {
                sectionElement.remove();
            }
        } else {
            showToast(data.message || 'Failed to delete section', 'error');
        }
    })
    .catch(error => {
        console.error('Error deleting section:', error);
        showToast('An error occurred while deleting the section', 'error');
    });
}

/**
 * Toggle section status via AJAX
 * @param {number} sectionId - The section ID
 */
function toggleSectionStatus(sectionId) {
    fetch(`/admin/homepage-builder/${sectionId}/toggle`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('Section status updated successfully', 'success');
            // Update the toggle button visually
            const sectionElement = document.querySelector(`[data-section-id="${sectionId}"]`);
            if (sectionElement) {
                const toggleButton = sectionElement.querySelector('button[onclick*="toggleSectionStatus"]').parentElement;
                if (data.status) {
                    toggleButton.classList.add('bg-green-500');
                    toggleButton.classList.remove('bg-gray-300');
                } else {
                    toggleButton.classList.add('bg-gray-300');
                    toggleButton.classList.remove('bg-green-500');
                }
            }
        } else {
            showToast(data.message || 'Failed to update section status', 'error');
        }
    })
    .catch(error => {
        console.error('Error toggling section status:', error);
        showToast('An error occurred while updating the section status', 'error');
    });
}

/**
 * Reorder sections via AJAX
 * Called when drag-drop is completed
 */
function reorderSections() {
    const sectionsContainer = document.getElementById('sections-container');
    if (!sectionsContainer) return;

    const orders = Array.from(sectionsContainer.querySelectorAll('[data-section-id]')).map((el, index) => ({
        id: el.dataset.sectionId,
        order: index + 1
    }));

    fetch('/admin/homepage-builder/reorder', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ orders: orders })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log('Sections reordered successfully');
        } else {
            showToast(data.message || 'Failed to reorder sections', 'error');
        }
    })
    .catch(error => {
        console.error('Error reordering sections:', error);
        showToast('An error occurred while reordering sections', 'error');
    });
}

/**
 * Add a new section via AJAX
 * @param {string} sectionType - The type of section to add
 */
function addSection(sectionType) {
    const pageTypeElement = document.querySelector('[data-page-type]');
    const currentPageType = pageTypeElement ? pageTypeElement.getAttribute('data-page-type') : pageType;

    fetch('/admin/homepage-builder', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            page_type: currentPageType,
            section_type: sectionType,
            title: null,
            config: {}
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('Section created successfully', 'success');
            closeAddSectionModal();
            // Reload the page to show the new section
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            showToast(data.message || 'Failed to create section', 'error');
        }
    })
    .catch(error => {
        console.error('Error creating section:', error);
        showToast('An error occurred while creating the section', 'error');
    });
}

/**
 * Modal management functions
 */
function openAddSectionModal() {
    const modal = document.getElementById('addSectionModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function closeAddSectionModal() {
    const modal = document.getElementById('addSectionModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

/**
 * Edit section - open config modal for editing
 * @param {number} sectionId - The section ID to edit
 */
function editSection(sectionId) {
    fetch(`/admin/homepage-builder/${sectionId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.section) {
            const section = data.section;
            openConfigModal(section.id, section.section_type);
        } else {
            showToast(data.message || 'Failed to load section', 'error');
        }
    })
    .catch(error => {
        console.error('Error loading section:', error);
        showToast('An error occurred while loading the section', 'error');
    });
}

/**
 * Save section configuration via PATCH request
 * @param {string} sectionType - The section type
 */
function saveSectionConfig(sectionType) {
    if (!currentSectionId) {
        showToast('Section ID not found', 'error');
        return;
    }

    const form = document.getElementById(`configForm_${sectionType}`);
    if (!form) {
        showToast('Configuration form not found', 'error');
        return;
    }

    // Collect form data
    const formData = new FormData(form);
    const data = {
        title: formData.get('title') || '',
        subtitle: formData.get('description') || formData.get('subtitle') || '',
        config: {}
    };

    // Collect config fields (fields with name="config[key]")
    for (const [key, value] of formData.entries()) {
        if (key.startsWith('config[')) {
            const configKey = key.replace('config[', '').replace(']', '');
            data.config[configKey] = isNaN(value) ? value : Number(value);
        }
    }

    // Send PATCH request
    fetch(`/admin/homepage-builder/${currentSectionId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('Section updated successfully', 'success');
            closeConfigModal(sectionType);
            // Reload the page to show updated content
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            showToast(data.message || 'Failed to update section', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating section:', error);
        showToast('An error occurred while updating the section', 'error');
    });
}

/**
 * Initialize sortable functionality
 */
function initSortable() {
    const sectionsContainer = document.getElementById('sections-container');

    if (!sectionsContainer) return;

    // Only initialize if there are child elements
    if (sectionsContainer.children.length > 0) {
        // Destroy existing instance if it exists
        if (sortableInstance) {
            sortableInstance.destroy();
        }

        // Create new sortable instance
        sortableInstance = Sortable.create(sectionsContainer, {
            animation: 150,
            ghostClass: 'bg-gray-100 opacity-50',
            dragClass: 'sortable-drag',
            handle: '.drag-handle',
            onEnd: function(evt) {
                // Call reorder function after drag-drop is complete
                reorderSections();
            }
        });
    }
}

/**
 * Initialize modal event listeners
 */
function initModalListeners() {
    const addSectionModal = document.getElementById('addSectionModal');

    if (addSectionModal) {
        // Close modal when clicking outside
        addSectionModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddSectionModal();
            }
        });
    }

    // Close all config modals when clicking outside
    const configModals = document.querySelectorAll('[id^="configModal_"]');
    configModals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfigModal();
            }
        });
    });
}

/**
 * Initialize form submission handlers
 */
function initFormHandlers() {
    // Handle form submissions for section configuration
    const forms = document.querySelectorAll('[id^="configForm_"]');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!currentSectionId || !currentSectionType) {
                showToast('Section information is missing', 'error');
                return;
            }

            // Extract form data
            const formData = new FormData(form);
            const data = {
                title: formData.get('title'),
                config: {}
            };

            // Collect config values
            for (let [key, value] of formData.entries()) {
                if (key.startsWith('config[')) {
                    const configKey = key.replace('config[', '').replace(']', '');
                    data.config[configKey] = value;
                }
            }

            // Send update request
            fetch(`/admin/homepage-builder/${currentSectionId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast('Section updated successfully', 'success');
                    closeConfigModal();
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showToast(data.message || 'Failed to update section', 'error');
                }
            })
            .catch(error => {
                console.error('Error updating section:', error);
                showToast('An error occurred while updating the section', 'error');
            });
        });
    });
}

/**
 * Initialize Sortable.js library
 * Dynamically load from CDN if not already loaded
 */
function loadSortableJS() {
    return new Promise((resolve) => {
        if (typeof Sortable !== 'undefined') {
            resolve();
        } else {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js';
            script.integrity = 'sha384-eeLEhtwdMwD3X9y+8P3Cn7Idl/M+w8H4uZqkgD/2eJVkWIN1yKzEj6XegJ9dL3q0';
            script.crossOrigin = 'anonymous';
            script.onload = resolve;
            document.head.appendChild(script);
        }
    });
}

/**
 * Add CSS for toast animations
 */
function addToastStyles() {
    if (!document.getElementById('toast-styles')) {
        const style = document.createElement('style');
        style.id = 'toast-styles';
        style.textContent = `
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(1rem);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fadeOut {
                from {
                    opacity: 1;
                    transform: translateY(0);
                }
                to {
                    opacity: 0;
                    transform: translateY(1rem);
                }
            }

            .animate-fade-in {
                animation: fadeIn 0.3s ease-in-out;
            }

            .animate-fade-out {
                animation: fadeOut 0.3s ease-in-out;
            }

            .sortable-drag {
                opacity: 0.5;
            }
        `;
        document.head.appendChild(style);
    }
}

/**
 * Main initialization function
 * Called when DOM is ready
 */
function initializeHomepageBuilder() {
    // Add toast styles
    addToastStyles();

    // Load Sortable.js and initialize
    loadSortableJS().then(() => {
        initSortable();
    });

    // Initialize modal listeners
    initModalListeners();

    // Initialize form handlers
    initFormHandlers();

    // Get page type from data attribute if available
    const pageTypeElement = document.querySelector('[data-page-type]');
    if (pageTypeElement) {
        pageType = pageTypeElement.getAttribute('data-page-type');
    }
}

/**
 * Execute initialization when DOM is ready
 */
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeHomepageBuilder);
} else {
    initializeHomepageBuilder();
}
