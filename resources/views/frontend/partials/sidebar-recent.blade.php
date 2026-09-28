<!-- Sidebar Recent Posts -->
<div>
    <div class="border-l-4 border-red-600 pl-3 mb-6">
        <h3 class="font-bold text-lg text-gray-900">Recent Post</h3>
    </div>
    <div class="space-y-4">
        @foreach($latestArticles->take(5) as $article)
            <a href="{{ route('articles.show', $article->slug) }}" class="group flex gap-3">
                <div class="w-16 h-16 bg-gray-300 rounded-sm flex-shrink-0 overflow-hidden">
                    @if($article->featured_image)
                        <img src="{{ asset('storage/' . $article->featured_image) }}"
                             alt="{{ $article->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition">
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    @if($article->category)
                        <span class="inline-block px-2 py-0.5 bg-red-600 text-white text-xs font-bold mb-1">
                            {{ strtoupper($article->category->name) }}
                        </span>
                    @endif
                    <h4 class="font-bold text-sm text-gray-900 line-clamp-2 group-hover:text-red-600 transition">
                        {{ $article->title }}
                    </h4>
                </div>
            </a>
        @endforeach
    </div>
</div>
