<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dark Mode Test - NEWSMEDIA</title>
    <script>
        // Set dark mode immediately before Alpine loads to prevent flash
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <div class="min-h-screen flex flex-col items-center justify-center p-8">
        <!-- Test Container -->
        <div class="max-w-2xl w-full bg-white dark:bg-gray-900 rounded-lg shadow-lg p-8 border border-gray-200 dark:border-gray-700">
            <!-- Header -->
            <h1 class="text-4xl font-bold mb-2 text-gray-900 dark:text-white">Dark Mode Test</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-8">Test all components in light and dark modes</p>

            <!-- Dark Mode Toggle -->
            <div class="mb-8 flex items-center gap-4">
                <button id="darkModeToggle" aria-label="Toggle dark mode" class="text-gray-400 hover:text-red-500 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded p-2 bg-gray-100 dark:bg-gray-800">
                    <i class="text-2xl fas fa-moon" aria-hidden="true"></i>
                </button>
                <span class="text-sm text-gray-600 dark:text-gray-400">Click the moon/sun icon above to toggle dark mode</span>
            </div>

            <hr class="my-8 border-gray-200 dark:border-gray-700">

            <!-- Component Tests -->
            <div class="space-y-8">
                <!-- Card Component -->
                <div>
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Card Component</h2>
                    <div class="dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-md">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Sample Card</h3>
                        <p class="text-gray-600 dark:text-gray-400">This card should change background color when switching between light and dark modes.</p>
                    </div>
                </div>

                <!-- Button Component -->
                <div>
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Button Components</h2>
                    <div class="flex gap-4 flex-wrap">
                        <button class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Primary Button</button>
                        <button class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">Secondary Button</button>
                        <button class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">Outlined Button</button>
                    </div>
                </div>

                <!-- Input Component -->
                <div>
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Form Components</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Text Input</label>
                            <input type="text" placeholder="Enter text..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Textarea</label>
                            <textarea placeholder="Enter message..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" rows="4"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Component -->
                <div>
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Sidebar Component</h2>
                    <div class="dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-md">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <span class="w-1 h-6 bg-red-600 dark:bg-red-500 rounded-full"></span>
                            Recent Articles
                        </h3>
                        <div class="space-y-3">
                            <a href="#" class="block text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition">
                                <span class="font-semibold">Article Title 1</span>
                                <p class="text-sm text-gray-500 dark:text-gray-400">12 Oct 2026</p>
                            </a>
                            <a href="#" class="block text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition">
                                <span class="font-semibold">Article Title 2</span>
                                <p class="text-sm text-gray-500 dark:text-gray-400">11 Oct 2026</p>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Alert Component -->
                <div>
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Alert Components</h2>
                    <div class="space-y-4">
                        <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                            <p class="text-blue-900 dark:text-blue-200"><strong>Info:</strong> This is an info alert.</p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-4">
                            <p class="text-green-900 dark:text-green-200"><strong>Success:</strong> This is a success alert.</p>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg p-4">
                            <p class="text-red-900 dark:text-red-200"><strong>Error:</strong> This is an error alert.</p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-8 border-gray-200 dark:border-gray-700">

            <!-- Status Display -->
            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4">
                <h3 class="font-bold text-gray-900 dark:text-white mb-2">Dark Mode Status:</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    <strong>Current Mode:</strong> <span id="modeStatus">Light</span>
                </p>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">
                    <strong>localStorage Value:</strong> <span id="storageValue">null</span>
                </p>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">
                    <strong>HTML Class:</strong> <span id="htmlClass">no 'dark' class</span>
                </p>
            </div>
        </div>
    </div>

    <script>
        function updateStatus() {
            const isDark = document.documentElement.classList.contains('dark');
            document.getElementById('modeStatus').textContent = isDark ? 'Dark' : 'Light';
            document.getElementById('storageValue').textContent = localStorage.getItem('darkMode') || 'null';
            document.getElementById('htmlClass').textContent = isDark ? "contains 'dark'" : "no 'dark' class";
        }

        // Update on page load
        updateStatus();

        // Update whenever the html element class changes
        const observer = new MutationObserver(updateStatus);
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    </script>
</body>
</html>
