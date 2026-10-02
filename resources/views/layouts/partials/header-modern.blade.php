<!-- Header -->
<header class="bg-black dark:bg-gray-900 border-b border-gray-700 dark:border-gray-800 py-4 sticky top-0 z-50 transition-colors duration-300" x-data="{ mobileMenuOpen: false, searchOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }">
    <div class="max-w-6xl mx-auto px-4 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-1 hover:opacity-80 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">
            <span class="text-2xl font-black text-white">NEWS</span>
            <span class="text-2xl font-black">
                <i class="fas fa-bolt text-red-600" aria-hidden="true"></i><span class="text-red-600">MEDIA</span>
            </span>
        </a>

        <!-- Navigation -->
        <nav class="hidden md:flex items-center gap-8" aria-label="Main navigation">
            <a href="{{ route('home') }}" class="font-bold text-xs text-white hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2 py-1">HOME</a>
            <a href="{{ route('news.index') }}" class="font-bold text-xs text-white hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2 py-1">NEWS</a>
            @php
                $navCategories = \App\Models\Category::active()
                    ->withCount(['articles' => fn($q) => $q->published()])
                    ->having('articles_count', '>', 0)
                    ->orderBy('articles_count', 'desc')
                    ->limit(4)
                    ->get();
            @endphp
            @foreach ($navCategories as $category)
                <a href="{{ route('blog.category', $category->slug) }}" class="font-bold text-xs text-white hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2 py-1 uppercase">
                    {{ $category->name }}
                </a>
            @endforeach
            <a href="{{ route('about') }}" class="font-bold text-xs text-white hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2 py-1">ABOUT</a>
            <a href="{{ route('contact') }}" class="font-bold text-xs text-white hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2 py-1">CONTACT</a>
        </nav>

        <!-- Dark Mode Toggle -->
        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" aria-label="Toggle dark mode" class="text-gray-400 hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded p-2">
            <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="text-lg" aria-hidden="true"></i>
        </button>

        <!-- Search -->
        <button @click="searchOpen = !searchOpen" aria-label="Open search" class="text-gray-400 hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded p-2">
            <i class="fas fa-search text-lg" aria-hidden="true"></i>
        </button>

        <!-- Mobile Menu Button -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" :aria-expanded="mobileMenuOpen" aria-label="Toggle mobile menu" class="md:hidden text-gray-400 hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded p-2">
            <i :class="mobileMenuOpen ? 'fas fa-times' : 'fas fa-bars'" class="text-lg" aria-hidden="true"></i>
        </button>
    </div>

    <!-- Mobile Menu -->
    <nav x-show="mobileMenuOpen" x-transition class="md:hidden bg-gray-900 border-t border-gray-700" aria-label="Mobile navigation">
        <div class="max-w-6xl mx-auto px-4 py-4 space-y-2">
            <a href="{{ route('home') }}" class="block py-2 text-white hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">HOME</a>
            <a href="{{ route('news.index') }}" class="block py-2 text-white hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">NEWS</a>
            @php
                $navCategories = \App\Models\Category::active()
                    ->withCount(['articles' => fn($q) => $q->published()])
                    ->having('articles_count', '>', 0)
                    ->orderBy('articles_count', 'desc')
                    ->limit(4)
                    ->get();
            @endphp
            @foreach ($navCategories as $category)
                <a href="{{ route('blog.category', $category->slug) }}" class="block py-2 text-white hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2 uppercase">
                    {{ $category->name }}
                </a>
            @endforeach
            <a href="{{ route('about') }}" class="block py-2 text-white hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">ABOUT</a>
            <a href="{{ route('contact') }}" class="block py-2 text-white hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">CONTACT</a>
        </div>
    </nav>

    <!-- Search Modal -->
    <div x-show="searchOpen" x-transition class="fixed inset-0 bg-black/50 z-40 flex items-start justify-center pt-20" @click.self="searchOpen = false" @keydown.escape="searchOpen = false">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-900">Search Articles</h2>
                    <button @click="searchOpen = false" aria-label="Close search" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl" aria-hidden="true"></i>
                    </button>
                </div>
                <form action="{{ route('blog.search') }}" method="GET" class="space-y-4">
                    <div>
                        <label for="modal-search" class="sr-only">Search articles</label>
                        <input type="text" id="modal-search" name="search" placeholder="Type to search articles..." autofocus
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-base focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600/20">
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 focus-visible:ring-2 ring-offset-2 ring-red-600 transition">
                            <i class="fas fa-search mr-2" aria-hidden="true"></i>Search
                        </button>
                        <button type="button" @click="searchOpen = false" class="flex-1 bg-gray-200 text-gray-900 py-3 rounded-lg font-semibold hover:bg-gray-300 focus-visible:ring-2 ring-offset-2 ring-gray-400 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>
