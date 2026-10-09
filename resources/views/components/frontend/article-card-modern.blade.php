<!-- Article Card Component - Professional Typography & Spacing -->
<article {{ $attributes->merge(['class' => 'group flex flex-col h-full stagger-item card-lift bg-white dark:bg-gray-800 rounded-lg overflow-hidden']) }}>
    <a href="{{ route('articles.show', $article->slug) }}" class="block relative">
        <!-- Image 16:9 with Category Badge -->
        <div class="bg-gray-300 dark:bg-gray-700 aspect-video overflow-hidden relative group/image shadow-depth image-zoom-container">
            @if($article->featured_image)
                <img src="{{ asset('storage/' . $article->featured_image) }}"
                     alt="{{ $article->title }}"
                     class="w-full h-full object-cover image-zoom">
            @else
                <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-400 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 dark:text-gray-500 text-5xl"></i>
                </div>
            @endif

            <!-- Category Badge - Professional Styling -->
            @if($article->category)
                <div class="absolute top-3 left-3 right-3">
                    <span class="inline-block px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold uppercase tracking-wider rounded-md shadow-lg transition-colors duration-300 line-height: 1.4">
                        {{ strtoupper($article->category->name) }}
                    </span>
                </div>
            @endif
        </div>
    </a>

    <!-- Content Section with Professional Spacing -->
    <div class="flex-1 flex flex-col px-4 py-4 sm:px-5 sm:py-5">
        <!-- Title with Improved Typography -->
        <h3 class="font-semibold text-base leading-relaxed text-gray-900 dark:text-gray-50 line-clamp-3 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors duration-300 mb-3 link-accent">
            {{ $article->title }}
        </h3>

        <!-- Meta Information with Enhanced Spacing -->
        <div class="space-y-2 mt-auto">
            <!-- Date with Icon -->
            <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-colors duration-300 flex items-center gap-2">
                <i class="fas fa-calendar w-4 text-center text-gray-500 dark:text-gray-500 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors flex-shrink-0"></i>
                <span>{{ $article->published_at?->format('d M Y') ?? 'No date' }}</span>
            </p>

            <!-- Views Count (if available) -->
            @if(isset($article->views_count))
                <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-colors duration-300 flex items-center gap-2">
                    <i class="fas fa-eye w-4 text-center text-gray-500 dark:text-gray-500 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors flex-shrink-0"></i>
                    <span>{{ number_format($article->views_count ?? 0) }} dibaca</span>
                </p>
            @endif
        </div>
    </div>
</article>
