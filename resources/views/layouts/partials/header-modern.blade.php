<!-- Header (Kompas.com style) -->
<header
    class="bg-black dark:bg-black border-b border-gray-800 dark:border-gray-800 sticky top-0 z-50 transition-colors duration-300 w-full"
    x-data="{ categoryMenuOpen: false, mobileMenuOpen: false }">
    <!-- Logo & Navigation Row -->
    <div class="max-w-7xl mx-auto px-4 py-2 sm:py-3 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('home') }}"
            class="flex items-center gap-1 hover:opacity-80 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2 shrink-0">
            <span class="text-lg sm:text-xl lg:text-2xl font-black text-white">NEWS</span>
            <span class="text-lg sm:text-xl lg:text-2xl font-black">
                <i class="fas fa-bolt text-red-600" aria-hidden="true"></i><span class="text-red-600">MEDIA</span>
            </span>
        </a>

        <!-- Category Navigation (Desktop) -->
        <nav class="hidden lg:flex items-center gap-3 lg:gap-6" aria-label="Category navigation">
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
                    class="text-sm font-bold text-white hover:text-red-500 transition whitespace-nowrap uppercase tracking-wide">
                    {{ $category->name }}
                </a>
            @endforeach

            @if ($moreCategories->isNotEmpty())
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center justify-center text-white hover:text-red-500 transition p-1.5 rounded"
                        aria-label="More categories">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition
                        class="absolute top-full left-0 mt-2 bg-gray-900 dark:bg-gray-900 rounded-lg shadow-lg border border-gray-700 dark:border-gray-700 min-w-max z-50">
                        @foreach ($moreCategories as $category)
                            <a href="{{ route('blog.category', $category->slug) }}"
                                class="block px-4 py-3 text-sm text-white hover:text-red-500 hover:bg-gray-800 transition first:rounded-t-lg last:rounded-b-lg uppercase font-semibold tracking-wide">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </nav>

        <!-- Right Actions -->
        <div class="flex items-center gap-1 sm:gap-2 ml-auto" x-data="{ searchOpen: false }">
            <!-- Search Button -->
            <button
                @click="searchOpen = !searchOpen; if (searchOpen) { $nextTick(() => document.querySelector('#search-input')?.focus()) }"
                aria-label="Search"
                class="text-gray-400 hover:text-red-500 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded p-1.5 sm:p-2">
                <i class="text-base sm:text-lg fas fa-magnifying-glass" aria-hidden="true"></i>
            </button>

            <!-- Dark Mode Toggle -->
            <button id="darkModeToggle" aria-label="Toggle dark mode"
                class="text-gray-400 hover:text-red-500 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded p-1.5 sm:p-2">
                <i class="text-base sm:text-lg fas fa-moon" aria-hidden="true"></i>
            </button>

            <!-- Mobile Menu Button (Hidden on Desktop) -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" :aria-expanded="mobileMenuOpen"
                aria-label="Toggle mobile menu"
                class="md:hidden lg:hidden text-gray-400 hover:text-red-500 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded p-1.5 sm:p-2"
                style="display: none !important;">
                <i :class="mobileMenuOpen ? 'fas fa-times' : 'fas fa-bars'" class="text-base sm:text-lg"
                    aria-hidden="true"></i>
            </button>

            <!-- Search Modal -->
            <div x-show="searchOpen" @click.outside="searchOpen = false" x-transition
                class="absolute top-full left-0 right-0 mt-1 bg-gray-900 dark:bg-gray-900 border-b border-gray-800 dark:border-gray-800 z-40">
                <div class="max-w-7xl mx-auto px-4 py-3">
                    <form action="{{ route('blog.search') }}" method="GET" class="flex gap-2">
                        <input type="text" id="search-input" name="q" placeholder="Search articles..."
                            value="{{ request('q') }}"
                            class="flex-1 px-3 py-2 rounded bg-gray-800 dark:bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-600" />
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition font-semibold">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <nav x-show="mobileMenuOpen" x-transition
        class="lg:hidden bg-gray-900 dark:bg-gray-900 border-t border-gray-800 dark:border-gray-800"
        aria-label="Mobile navigation">
        <div class="max-w-7xl mx-auto px-4 py-3 space-y-1">
            @php
                $allCategories = \App\Models\Category::active()
                    ->withCount(['articles' => fn($q) => $q->published()])
                    ->having('articles_count', '>', 0)
                    ->orderBy('articles_count', 'desc')
                    ->get();
            @endphp

            @foreach ($allCategories as $category)
                <a href="{{ route('blog.category', $category->slug) }}"
                    class="block py-2 px-3 text-sm font-bold text-white hover:text-red-500 hover:bg-gray-800 rounded transition uppercase tracking-wide">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </nav>
</header>
