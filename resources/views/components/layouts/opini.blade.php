<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom styles to mimic the template */
        .rt-container {
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
    </style>
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body
    class="font-sans antialiased bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-white transition-colors duration-300">

    <!-- Top Ad Section (Parallax mimic) -->
    <div class="hidden md:block bg-gray-100 dark:bg-gray-800 py-2 border-b border-gray-200 dark:border-gray-700">
        <div
            class="rt-container flex justify-center items-center h-[250px] bg-gray-200 dark:bg-gray-700 text-gray-400 text-sm">
            <!-- Ad Placeholder -->
            <x-ad-slot placement="header_top" class="max-w-full" />
        </div>
        <div class="bg-blue-900 text-white text-xs text-center py-2 font-bold tracking-wider">
            SCROLL TO CONTINUE WITH CONTENT
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-50 transition-colors duration-300">
        <!-- Middle Header: Logo, Search, Socials -->
        <div class="border-b border-gray-100 py-4">
            <div class="rt-container flex items-center justify-between">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}">
                        @if(!empty($settings['site_logo']))
                            <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="{{ config('app.name') }}"
                                class="h-10 w-auto">
                        @else
                            <span class="text-2xl font-bold text-gray-900">{{ config('app.name') }}</span>
                        @endif
                    </a>
                </div>

                <!-- Search Form -->
                <div class="flex-1 max-w-md mx-8 hidden md:block">
                    <form action="{{ route('articles.search') }}" method="GET" class="relative">
                        <input type="search" name="query" placeholder="Pencarian berita..."
                            class="w-full pl-4 pr-10 py-2 bg-gray-100 border-none rounded-full text-sm focus:ring-2 focus:ring-blue-500">
                        <button type="submit"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Socials & Tools -->
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex gap-3 text-gray-500">
                        <a href="#" class="hover:text-blue-600"><svg class="w-4 h-4" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg></a>
                        <a href="#" class="hover:text-blue-600"><svg class="w-4 h-4" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg></a>
                    </div>
                    <!-- Dark Mode Toggle -->
                    <button id="theme-toggle"
                        class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline-none rounded-lg text-sm p-2.5">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <!-- Mobile Menu Button -->
                    <button class="md:hidden text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation Bar -->
        <div
            class="py-3 hidden md:block border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 transition-colors duration-300">
            <div class="rt-container">
                <nav class="flex justify-center">
                    <ul
                        class="flex space-x-8 text-sm font-bold uppercase tracking-wide text-gray-700 dark:text-gray-200">
                        @foreach(\App\Models\Category::take(7)->get() as $category)
                            <li><a href="{{ route('categories.show', $category) }}"
                                    class="hover:text-blue-600 hover:underline decoration-2 underline-offset-4">{{ $category->name }}</a>
                            </li>
                        @endforeach
                        <li>
                            <a href="#" class="flex items-center gap-1 hover:text-blue-600">
                                Lainnya
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white pt-16 pb-8 mt-12">
        <div class="rt-container grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <!-- Col 1: Logo & About -->
            <div>
                <h3 class="text-xl font-bold mb-4">{{ config('app.name') }}</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Website berita terpercaya menyajikan informasi terkini dan akurat dari berbagai kategori pilihan.
                </p>
            </div>
            <!-- Col 2: Links -->
            <div>
                <h4 class="font-bold mb-4 uppercase tracking-wider text-sm">Kategori</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    @foreach(\App\Models\Category::take(5)->get() as $category)
                        <li><a href="{{ route('categories.show', $category) }}"
                                class="hover:text-white">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <!-- Col 3: Links -->
            <div>
                <h4 class="font-bold mb-4 uppercase tracking-wider text-sm">Informasi</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-white">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-white">Redaksi</a></li>
                    <li><a href="#" class="hover:text-white">Pedoman Media Siber</a></li>
                    <li><a href="#" class="hover:text-white">Kontak</a></li>
                </ul>
            </div>
            <!-- Col 4: Newsletter -->
            <div>
                <h4 class="font-bold mb-4 uppercase tracking-wider text-sm">Berlangganan</h4>
                <p class="text-gray-400 text-sm mb-4">Dapatkan berita terbaru langsung di inbox Anda.</p>
                <form class="flex">
                    <input type="email" placeholder="Email Address"
                        class="w-full px-3 py-2 text-gray-900 text-sm rounded-l-md focus:outline-none">
                    <button class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-r-md text-sm font-bold">OK</button>
                </form>
            </div>
        </div>
        <div class="rt-container border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <div class="flex space-x-4 mt-4 md:mt-0 opacity-50">
                <a href="#" class="hover:opacity-100"><i class="fab fa-facebook"></i></a>
                <a href="#" class="hover:opacity-100"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:opacity-100"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>
    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function () {

            // toggle icons inside button
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // if set via local storage previously
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }

                // if NOT set via local storage previously
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    </script>
</body>

</html>