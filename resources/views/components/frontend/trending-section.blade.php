@props(['categories'])

@php
    // Get trending keywords from published articles
    $trendingKeywords = \App\Models\Keyword::select('keywords.id', 'keywords.keyword')
        ->selectRaw('COUNT(article_keyword.article_id) as articles_count')
        ->join('article_keyword', 'keywords.id', '=', 'article_keyword.keyword_id')
        ->join('articles', 'articles.id', '=', 'article_keyword.article_id')
        ->where('articles.status', 'published')
        ->groupBy('keywords.id', 'keywords.keyword')
        ->orderByDesc('articles_count')
        ->limit(10)
        ->get();
@endphp

@if ($categories->count() > 0 || $trendingKeywords->count() > 0)
    <div class="mb-8" x-data="trendingScroll()">
        <div class="flex items-center gap-4 overflow-x-auto pb-4 scroll-smooth" id="trendingContainer">
            <!-- Label -->
            <span class="text-blue-600 font-bold text-sm uppercase whitespace-nowrap shrink-0">Trending:</span>

            <!-- Trending Keywords from Articles -->
            @foreach ($trendingKeywords as $keyword)
                <a href="{{ route('blog.search') }}?q={{ urlencode($keyword->keyword) }}"
                    class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-blue-500 hover:text-white dark:hover:bg-blue-600 rounded-full whitespace-nowrap transition text-sm font-medium"
                    title="{{ $keyword->keyword }} ({{ $keyword->articles_count }} artikel)">
                    {{ $keyword->keyword }}
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
