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
    <div class="mb-8">
        <div class="flex items-center gap-3 pb-2">
            <!-- Fixed Label -->
            <span class="text-red-600 dark:text-red-500 font-bold text-sm uppercase whitespace-nowrap sticky left-0 z-10 bg-white dark:bg-gray-900">TRENDING:</span>

            <!-- Scrollable Container -->
            <div class="flex items-center gap-3 flex-1 overflow-x-auto scroll-smooth select-none"
                 id="trendingContainer"
                 data-dragging="false"
                 style="scrollbar-width: none; -ms-overflow-style: none;">

                <!-- Trending Keywords/Tags from Articles -->
                @foreach ($trendingKeywords as $item)
                    @if ($item->is_tag ?? false)
                        <a href="{{ route('blog.tag', $item->slug) }}"
                            class="px-4 py-2.5 bg-white dark:bg-gray-800 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 transition text-sm font-bold border border-red-300 dark:border-red-700/60 whitespace-nowrap cursor-pointer shrink-0"
                            title="{{ ucfirst($item->keyword) }} ({{ $item->articles_count }} artikel)"
                            draggable="false">
                            #{{ ucfirst($item->keyword) }}
                        </a>
                    @else
                        <a href="{{ route('blog.search') }}?q={{ urlencode($item->keyword) }}"
                            class="px-4 py-2.5 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-300 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 transition text-sm font-bold border border-gray-300 dark:border-gray-700/60 whitespace-nowrap cursor-pointer shrink-0"
                            title="{{ $item->keyword }} ({{ $item->articles_count }} artikel)"
                            draggable="false">
                            {{ $item->keyword }}
                        </a>
                    @endif
                @endforeach
            </div>

            <!-- Right Chevron Button -->
            <button id="trendingNextBtn"
                    class="shrink-0 text-red-600 dark:text-red-500 hover:text-red-700 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 p-2.5 rounded transition"
                    title="Scroll right">
                <i class="fas fa-chevron-right text-lg"></i><i class="fas fa-chevron-right text-lg -ml-2"></i>
            </button>
        </div>
    </div>

    <style>
        #trendingContainer::-webkit-scrollbar {
            display: none;
        }

        #trendingContainer {
            cursor: grab;
        }

        #trendingContainer.dragging {
            cursor: grabbing;
        }

        #trendingContainer a:hover {
            text-decoration: none;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('trendingContainer');
            const nextBtn = document.getElementById('trendingNextBtn');
            let isDown = false;
            let startX;
            let scrollLeft;

            container.addEventListener('mousedown', (e) => {
                isDown = true;
                container.classList.add('dragging');
                startX = e.pageX - container.offsetLeft;
                scrollLeft = container.scrollLeft;
            });

            container.addEventListener('mouseleave', () => {
                isDown = false;
                container.classList.remove('dragging');
            });

            container.addEventListener('mouseup', () => {
                isDown = false;
                container.classList.remove('dragging');
            });

            container.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - container.offsetLeft;
                const walk = (x - startX) * 1;
                container.scrollLeft = scrollLeft - walk;
            });

            // Touch support for mobile
            container.addEventListener('touchstart', (e) => {
                isDown = true;
                startX = e.touches[0].pageX - container.offsetLeft;
                scrollLeft = container.scrollLeft;
            });

            container.addEventListener('touchend', () => {
                isDown = false;
            });

            container.addEventListener('touchmove', (e) => {
                if (!isDown) return;
                const x = e.touches[0].pageX - container.offsetLeft;
                const walk = (x - startX) * 1;
                container.scrollLeft = scrollLeft - walk;
            });

            // Right chevron button scroll
            nextBtn.addEventListener('click', () => {
                container.scrollBy({
                    left: 300,
                    behavior: 'smooth'
                });
            });
        });
    </script>
@endif
