<!-- News Ticker Carousel -->
@if (isset($latestArticles) && $latestArticles->count() > 0)
    <div class="bg-gray-100 py-4 border-b border-gray-300">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center gap-6 overflow-x-auto pb-2 scrollbar-hide">
                @foreach ($latestArticles->take(3) as $article)
                    <a href="{{ route('articles.show', $article->slug) }}"
                        class="flex items-center gap-4 flex-shrink-0 group">
                        <div class="w-16 h-16 bg-gray-300 rounded-sm overflow-hidden">
                            @if ($article->featured_image)
                                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-600 mb-1">
                                <span class="font-bold text-red-600">By {{ $article->user->name ?? 'Editor' }}</span>
                                <span class="text-gray-500"> · {{ $article->created_at->format('F d, Y') }}</span>
                            </p>
                            <h4
                                class="text-sm font-bold text-gray-900 line-clamp-2 group-hover:text-red-600 transition">
                                {{ $article->title }}
                            </h4>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
