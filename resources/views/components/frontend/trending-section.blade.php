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
            <span class="text-blue-600 font-bold text-sm uppercase whitespace-nowrap shrink-0">Trending:</span>

            <!-- Trending Keywords/Tags from Articles -->
            @foreach ($trendingKeywords as $item)
                @if ($item->is_tag ?? false)
                    <a href="{{ route('blog.tag', $item->slug) }}"
                        class="px-4 py-2 bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-200 hover:bg-purple-500 hover:text-white dark:hover:bg-purple-600 rounded-full whitespace-nowrap transition text-sm font-medium"
                        title="{{ $item->keyword }} ({{ $item->articles_count }} artikel)">
                        #{{ $item->keyword }}
                    </a>
                @else
                    <a href="{{ route('blog.search') }}?q={{ urlencode($item->keyword) }}"
                        class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-blue-500 hover:text-white dark:hover:bg-blue-600 rounded-full whitespace-nowrap transition text-sm font-medium"
                        title="{{ $item->keyword }} ({{ $item->articles_count }} artikel)">
                        {{ $item->keyword }}
                    </a>
                @endif
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
