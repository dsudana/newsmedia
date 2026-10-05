/**
 * Dark Mode Toggle Handler
 * Manages light/dark mode switching with localStorage persistence
 */

function initializeDarkMode() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const html = document.documentElement;

    console.log('[Dark Mode] Initializing...', { button: darkModeToggle, currentDark: html.classList.contains('dark') });

    if (!darkModeToggle) {
        console.warn('[Dark Mode] Toggle button not found!');
        return;
    }

    function updateDarkModeIcon() {
        const icon = darkModeToggle.querySelector('i');
        if (!icon) {
            console.warn('[Dark Mode] Icon element not found!');
            return;
        }

        if (html.classList.contains('dark')) {
            icon.className = 'text-lg fas fa-sun';
            console.log('[Dark Mode] Icon updated to sun');
        } else {
            icon.className = 'text-lg fas fa-moon';
            console.log('[Dark Mode] Icon updated to moon');
        }
    }

    // Handle toggle button click
    darkModeToggle.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('[Dark Mode] Toggle clicked');
        html.classList.toggle('dark');
        const isDark = html.classList.contains('dark');
        console.log('[Dark Mode] Dark mode is now:', isDark);
        localStorage.setItem('darkMode', isDark ? 'true' : 'false');
        updateDarkModeIcon();
    });

    // Update icon on page load
    updateDarkModeIcon();

    // Listen for dark mode changes from other tabs/windows
    window.addEventListener('storage', function(e) {
        if (e.key === 'darkMode') {
            console.log('[Dark Mode] Storage changed from another tab:', e.newValue);
            if (e.newValue === 'true') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
            updateDarkModeIcon();
        }
    });

    console.log('[Dark Mode] Initialized successfully');
}

// Export function for module usage
export { initializeDarkMode };

// Initialize when DOM is ready (for inline script execution too)
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeDarkMode);
} else {
    initializeDarkMode();
}
