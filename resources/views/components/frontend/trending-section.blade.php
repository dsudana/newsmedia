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

    // If no keywords, get trending tags instead
    if ($trendingKeywords->isEmpty()) {
        $trendingKeywords = \App\Models\Tag::select('tags.id', 'tags.name', 'tags.slug')
            ->selectRaw('COUNT(article_tag.article_id) as articles_count')
            ->join('article_tag', 'tags.id', '=', 'article_tag.tag_id')
            ->join('articles', 'articles.id', '=', 'article_tag.article_id')
            ->where('articles.status', 'published')
            ->groupBy('tags.id', 'tags.name', 'tags.slug')
            ->orderByDesc('articles_count')
            ->limit(10)
            ->get()
            ->map(fn($tag) => (object)[
                'keyword' => $tag->name,
                'slug' => $tag->slug,
                'articles_count' => $tag->articles_count,
                'is_tag' => true
            ]);
    } else {
        $trendingKeywords = $trendingKeywords->map(fn($kw) => (object)[
            'keyword' => $kw->keyword,
            'articles_count' => $kw->articles_count,
            'is_tag' => false
        ]);
    }
@endphp

@if ($trendingKeywords->count() > 0)
    <div class="mb-8" x-data="trendingScroll()">
        <div class="flex items-center gap-4 overflow-x-auto pb-4 scroll-smooth" id="trendingContainer">
            <!-- Label -->
            <span class="text-red-600 dark:text-red-500 font-bold text-sm uppercase whitespace-nowrap shrink-0">Trending:</span>

            <!-- Trending Keywords/Tags from Articles -->
            @foreach ($trendingKeywords as $item)
                @if ($item->is_tag ?? false)
                    <a href="{{ route('blog.tag', $item->slug) }}"
                        class="px-4 py-2 bg-red-50 dark:bg-red-950 text-red-700 dark:text-red-200 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 rounded-full whitespace-nowrap transition text-sm font-medium border border-red-200 dark:border-red-800"
                        title="{{ $item->keyword }} ({{ $item->articles_count }} artikel)">
                        #{{ $item->keyword }}
                    </a>
                @else
                    <a href="{{ route('blog.search') }}?q={{ urlencode($item->keyword) }}"
                        class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 rounded-full whitespace-nowrap transition text-sm font-medium border border-gray-200 dark:border-gray-700/50"
                        title="{{ $item->keyword }} ({{ $item->articles_count }} artikel)">
                        {{ $item->keyword }}
                    </a>
                @endif
            @endforeach

            <!-- Navigation Arrow -->
            <button @click="scrollRight()" class="shrink-0 text-red-600 dark:text-red-500 hover:text-red-700 dark:hover:text-red-400 p-2 ml-4 transition" aria-label="More trending">
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
