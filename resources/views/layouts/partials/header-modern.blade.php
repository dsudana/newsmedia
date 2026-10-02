<!-- Header (Kompas.com style) -->
<header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 sticky top-0 z-50 transition-colors duration-300" x-data="{ categoryMenuOpen: false, mobileMenuOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }">
    <!-- Logo & Navigation Row -->
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-1 hover:opacity-80 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2 shrink-0">
            <span class="text-xl lg:text-2xl font-black text-gray-900 dark:text-white">NEWS</span>
            <span class="text-xl lg:text-2xl font-black">
                <i class="fas fa-bolt text-red-600" aria-hidden="true"></i><span class="text-red-600">MEDIA</span>
            </span>
        </a>

        <!-- Category Navigation (Desktop) -->
        <nav class="hidden lg:flex items-center gap-6 flex-1 mx-8" aria-label="Category navigation">
            @php
                $allCategories = \App\Models\Category::active()
                    ->withCount(['articles' => fn($q) => $q->published()])
                    ->having('articles_count', '>', 0)
                    ->orderBy('articles_count', 'desc')
                    ->get();
                $displayCategories = $allCategories->take(8);
                $moreCategories = $allCategories->skip(8);
            @endphp

            @foreach ($displayCategories as $category)
                <a href="{{ route('blog.category', $category->slug) }}"
                    class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition whitespace-nowrap">
                    {{ $category->name }}
                </a>
            @endforeach

            @if ($moreCategories->isNotEmpty())
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-1 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition">
                        <span>Lainnya</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition class="absolute top-full left-0 mt-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 min-w-max z-50">
                        @foreach ($moreCategories as $category)
                            <a href="{{ route('blog.category', $category->slug) }}"
                                class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition first:rounded-t-lg last:rounded-b-lg">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </nav>

        <!-- Right Actions -->
        <div class="flex items-center gap-2">
            <!-- Dark Mode Toggle -->
            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" aria-label="Toggle dark mode" class="text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded p-2">
                <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="text-lg" aria-hidden="true"></i>
            </button>

            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" :aria-expanded="mobileMenuOpen" aria-label="Toggle mobile menu" class="lg:hidden text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded p-2">
                <i :class="mobileMenuOpen ? 'fas fa-times' : 'fas fa-bars'" class="text-lg" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <nav x-show="mobileMenuOpen" x-transition class="lg:hidden bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700" aria-label="Mobile navigation">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-2">
            @php
                $allCategories = \App\Models\Category::active()
                    ->withCount(['articles' => fn($q) => $q->published()])
                    ->having('articles_count', '>', 0)
                    ->orderBy('articles_count', 'desc')
                    ->get();
            @endphp

            @foreach ($allCategories as $category)
                <a href="{{ route('blog.category', $category->slug) }}"
                    class="block py-2 px-3 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </nav>
</header>
