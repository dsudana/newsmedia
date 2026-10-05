<!DOCTYPE html>
<html lang="en">
    <script>
        // Set dark mode immediately before Alpine loads to prevent flash
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'NEWSMEDIA - Latest News & Stories')</title>
        <meta name="description" content="@yield('meta_description', 'Get the latest news and stories from around the world')">
        <meta name="color-scheme" content="light dark">

        <!-- Open Graph / Social Sharing Meta Tags -->
        <meta property="og:type" content="@yield('og:type', 'website')">
        <meta property="og:url" content="{{ request()->url() }}">
        <meta property="og:title" content="@yield('og:title', 'NEWSMEDIA')">
        <meta property="og:description" content="@yield('og:description', 'Get the latest news and stories from around the world')">
        <meta property="og:image" content="@yield('og:image', url('/images/og-default.png'))">
        <meta property="og:site_name" content="NEWSMEDIA">

        <!-- Twitter Card Meta Tags -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ request()->url() }}">
        <meta property="twitter:title" content="@yield('twitter:title', 'NEWSMEDIA')">
        <meta property="twitter:description" content="@yield('twitter:description', 'Get the latest news and stories from around the world')">
        <meta property="twitter:image" content="@yield('twitter:image', url('/images/og-default.png'))">

        <!-- Google Fonts: Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @yield('extra_head')
    </head>

    <body class="bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-300" id="appBody">
        <style>
            /* Explicit dark mode styles for testing */
            html.dark body {
                background-color: rgb(3, 7, 18) !important;
                color: rgb(243, 244, 246) !important;
            }
            html.dark .dark\:bg-gray-900 {
                background-color: rgb(17, 24, 39) !important;
            }
            html.dark .dark\:bg-black {
                background-color: rgb(0, 0, 0) !important;
            }
            html.dark .dark\:text-white {
                color: rgb(255, 255, 255) !important;
            }
            html.dark .dark\:text-gray-100 {
                color: rgb(243, 244, 246) !important;
            }
            html.dark .dark\:border-gray-800 {
                border-color: rgb(31, 41, 55) !important;
            }
        </style>
        <!-- Reading Progress Bar -->
        <div id="readingProgress"
            class="fixed top-0 left-0 h-1 bg-gradient-to-r from-red-600 to-red-700 z-50 transition-all duration-300"
            style="width: 0%;"></div>

        <!-- Top Bar -->
        @include('layouts.partials.topbar')

        <!-- Header -->
        @include('layouts.partials.header-modern')

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        @include('layouts.partials.footer-modern')

        <!-- Reading Progress Indicator -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const progressBar = document.getElementById('readingProgress');
                if (!progressBar) return;

                window.addEventListener('scroll', function() {
                    const windowHeight = document.documentElement.scrollHeight - window.innerHeight;
                    const scrolled = (window.scrollY / windowHeight) * 100;
                    progressBar.style.width = scrolled + '%';
                });

                // Optional: animate progress on page load
                let progress = 0;
                const interval = setInterval(() => {
                    progress += Math.random() * 30;
                    progress = Math.min(progress, 100);
                    progressBar.style.width = progress + '%';

                    if (document.readyState === 'complete') {
                        clearInterval(interval);
                        progressBar.style.width = '100%';
                        setTimeout(() => {
                            progressBar.style.opacity = '0';
                        }, 500);
                    }
                }, 300);
            });
        </script>

        @yield('extra_scripts')
    </body>

</html>
