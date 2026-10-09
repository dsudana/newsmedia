<div
    class="max-w-sm bg-white dark:bg-gray-800 rounded-lg overflow-hidden card-lift shadow-depth stagger-item group">
    <a class="block" href="{{ route('articles.show', $article) }}">
        <!-- Image Container -->
        <div class="w-full h-48 overflow-hidden relative image-zoom-container">
            <img class="w-full h-full object-cover image-zoom"
                src="{{ $article->featured_image ? '/storage/' . $article->featured_image : '/images/placeholder-news-media.svg' }}"
                alt="{{ $article->title }}" />
        </div>

        <!-- Content Section with Professional Spacing -->
        <div class="p-5 flex flex-col h-full">
            <!-- Category Badge -->
            @if($article->category)
                <span class="inline-block bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 text-xs font-semibold tracking-wider uppercase px-3 py-1.5 rounded-md mb-3 group-hover:bg-blue-200 dark:group-hover:bg-blue-800/50 transition-colors duration-300 w-fit">
                    {{ $article->category->name }}
                </span>
            @else
                <span class="inline-block bg-gray-100 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300 text-xs font-semibold uppercase px-3 py-1.5 rounded-md mb-3 w-fit">
                    Uncategorized
                </span>
            @endif

            <!-- Title with Professional Typography -->
            <h3 class="text-base lg:text-lg font-semibold leading-snug text-gray-900 dark:text-gray-50 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300 mb-4 link-accent">
                {{ $article->title }}
            </h3>

            <!-- Author Info with Better Spacing -->
            <div class="flex items-center gap-3 mt-auto pt-4 border-t border-gray-200 dark:border-gray-700">
                @if($article->user)
                    <img class="w-10 h-10 rounded-full shadow-md flex-shrink-0"
                        src="{{ $article->user->avatar ? '/storage/' . $article->user->avatar : 'https://ui-avatars.com/api/?name=' . urlencode($article->user->name) }}"
                        alt="{{ $article->user->name }}" />
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate">{{ $article->user->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Author</p>
                    </div>
                @else
                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-gray-400 dark:text-gray-500 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-100">Unknown Author</p>
                    </div>
                @endif
            </div>
        </div>
    </a>
</div>