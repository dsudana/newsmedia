<!-- Featured Card Component - Modern RET/NEWS Style -->
<a href="{{ route('articles.show', $article->slug) }}" class="group block">
    <div class="relative overflow-hidden {{ $height ?? 'h-40' }} bg-gray-200 dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-md transition-shadow duration-300">
        @if($article->featured_image)
            <img src="{{ asset('storage/' . $article->featured_image) }}"
                 alt="{{ $article->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent"></div>

        <div class="absolute bottom-0 left-0 right-0 p-3 sm:p-4">
            @if($article->category)
                <span class="inline-block px-2 py-0.5 bg-red-600 text-white text-xs font-bold mb-2">
                    {{ strtoupper($article->category->name) }}
                </span>
            @endif
            <h3 class="text-white font-bold text-sm sm:text-base line-clamp-2">{{ $article->title }}</h3>
        </div>
    </div>
</a>
