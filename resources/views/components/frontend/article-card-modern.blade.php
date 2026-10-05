<!-- Article Card Component - Modern RET/NEWS Style -->
<article {{ $attributes->merge(['class' => 'group flex flex-col h-full']) }}>
    <a href="{{ route('articles.show', $article->slug) }}" class="block mb-3 relative">
        <!-- Image 16:9 -->
        <div class="bg-gray-300 dark:bg-gray-800 aspect-video overflow-hidden rounded-lg relative group/image shadow-sm dark:shadow-md transition-shadow duration-300">
            @if($article->featured_image)
                <img src="{{ asset('storage/' . $article->featured_image) }}"
                     alt="{{ $article->title }}"
                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
            @else
                <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-400 dark:from-gray-800 dark:to-gray-700 flex items-center justify-center">
                    <i class="fas fa-image text-gray-500 dark:text-gray-500 text-4xl"></i>
                </div>
            @endif

            <!-- Category Badge Overlay -->
            @if($article->category)
                <div class="absolute top-3 left-3">
                    <span class="inline-block px-3 py-1.5 bg-red-600 text-white text-xs font-bold uppercase rounded-sm">
                        {{ strtoupper($article->category->name) }}
                    </span>
                </div>
            @endif
        </div>
    </a>

    <!-- Content -->
    <div class="flex-1 flex flex-col">
        <h3 class="font-bold text-sm text-gray-900 dark:text-gray-100 line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition mb-2">
            {{ $article->title }}
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-auto">
            {{ $article->published_at?->format('d M Y') ?? 'No date' }}
        </p>
    </div>
</article>
