<!-- Sidebar Popular Posts -->
<div>
    <div class="border-l-4 border-red-600 pl-3 mb-6">
        <h3 class="font-bold text-lg text-gray-900">Popular Post</h3>
    </div>
    <div class="space-y-4">
        @foreach($latestArticles->skip(5)->take(4) as $i => $article)
            <a href="{{ route('articles.show', $article->slug) }}" class="group flex gap-3">
                <div class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-xs flex-shrink-0">
                    {{ $i + 1 }}
                </div>
                <div class="flex-1 min-w-0">
                    @if($article->category)
                        <span class="inline-block px-1.5 py-0.5 bg-red-600 text-white text-xs font-bold mb-0.5">
                            {{ strtoupper($article->category->name) }}
                        </span>
                    @endif
                    <h4 class="font-bold text-xs text-gray-900 line-clamp-2 group-hover:text-red-600 transition">
                        {{ $article->title }}
                    </h4>
                </div>
            </a>
        @endforeach
    </div>
</div>
