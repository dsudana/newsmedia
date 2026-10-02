@props(['categories'])

@if ($categories->count() > 0)
    <div class="mb-8" x-data="trendingScroll()">
        <div class="flex items-center gap-4 overflow-x-auto pb-4 scroll-smooth" id="trendingContainer">
            <!-- Label -->
            <span class="text-blue-600 font-bold text-sm uppercase whitespace-nowrap shrink-0">Trending:</span>

            <!-- Trending Items -->
            @foreach ($categories as $category)
                <a href="{{ route('blog.category', $category->slug) }}"
                    class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-blue-500 hover:text-white dark:hover:bg-blue-600 rounded-full whitespace-nowrap transition text-sm font-medium">
                    {{ $category->name }}
                </a>
            @endforeach

            <!-- Search trending topics dynamically -->
            @php
                $trendingTags = \App\Models\Tag::withCount('articles')
                    ->orderBy('articles_count', 'desc')
                    ->limit(5)
                    ->get();
            @endphp

            @foreach ($trendingTags as $tag)
                <a href="{{ route('blog.tag', $tag->slug) }}"
                    class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-blue-500 hover:text-white dark:hover:bg-blue-600 rounded-full whitespace-nowrap transition text-sm font-medium">
                    {{ $tag->name }}
                </a>
            @endforeach

            <!-- Navigation Arrow -->
            <button @click="scrollRight()" class="shrink-0 text-blue-600 hover:text-blue-700 p-2 ml-4" aria-label="More trending">
                <i class="fas fa-chevron-right text-xl"></i>
            </button>
        </div>
    </div>

    <script>
        function trendingScroll() {
            return {
                scrollRight() {
                    const container = document.getElementById('trendingContainer');
                    container.scrollBy({ left: 200, behavior: 'smooth' });
                }
            }
        }
    </script>
@endif
