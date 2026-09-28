<!-- Article Card Component - Modern RET/NEWS Style -->
<article {{ $attributes->merge(['class' => 'group']) }}>
    <a href="{{ route('articles.show', $article->slug) }}" class="block mb-3">
        <div class="bg-gray-300 h-40 overflow-hidden">
            @if($article->featured_image)
                <img src="{{ asset('storage/' . $article->featured_image) }}"
                     alt="{{ $article->title }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            @endif
        </div>
    </a>
    <div>
        @if($article->category)
            <span class="inline-block px-2 py-0.5 bg-red-600 text-white text-xs font-bold mb-1">
                {{ strtoupper($article->category->name) }}
            </span>
        @endif
        <p class="text-xs text-gray-700 mb-1">
            <span class="font-bold text-red-600">By {{ $article->user->name ?? 'Editor' }}</span>
        </p>
        <h3 class="font-bold text-sm text-gray-900 line-clamp-2 group-hover:text-red-600 transition">
            {{ $article->title }}
        </h3>
    </div>
</article>
