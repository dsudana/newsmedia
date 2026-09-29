<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="darkMode && 'dark'">

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

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
            crossorigin="anonymous" referrerpolicy="no-referrer">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
            crossorigin="anonymous" referrerpolicy="no-referrer">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @yield('extra_head')
    </head>

    <body class="bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-300">
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

        <!-- Swiper JS -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>

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
