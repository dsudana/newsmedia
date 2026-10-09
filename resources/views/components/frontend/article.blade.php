<div
    class="max-w-sm bg-white dark:bg-gray-800 rounded-lg overflow-hidden card-lift shadow-depth stagger-item group">
    <a class="block" href="{{ route('articles.show', $article) }}">
        <!-- Large Image Container with Icon Overlay -->
        <div class="w-full h-52 overflow-hidden relative image-zoom-container group/image">
            <img class="w-full h-full object-cover image-zoom transition-transform duration-300"
                src="{{ $article->featured_image ? '/storage/' . $article->featured_image : '/images/placeholder-news-media.svg' }}"
                alt="{{ $article->title }}" />

            <!-- Icon Overlay - Centered -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-16 h-16 bg-white/90 dark:bg-gray-800/90 rounded-full flex items-center justify-center backdrop-blur-sm transition-transform duration-300 group-hover:scale-110">
                    @if($article->category)
                        @switch($article->category->name)
                            @case('Gaya Hidup')
                                <i class="fas fa-heart text-red-600 dark:text-red-400 text-2xl"></i>
                            @break
                            @case('Pemerintahan')
                                <i class="fas fa-landmark text-red-600 dark:text-red-400 text-2xl"></i>
                            @break
                            @case('Pendidikan')
                                <i class="fas fa-book text-red-600 dark:text-red-400 text-2xl"></i>
                            @break
                            @case('Kesehatan')
                                <i class="fas fa-stethoscope text-red-600 dark:text-red-400 text-2xl"></i>
                            @break
                            @case('Bisnis')
                                <i class="fas fa-briefcase text-red-600 dark:text-red-400 text-2xl"></i>
                            @break
                            @case('Olahraga')
                                <i class="fas fa-futbol text-red-600 dark:text-red-400 text-2xl"></i>
                            @break
                            @default
                                <i class="fas fa-newspaper text-red-600 dark:text-red-400 text-2xl"></i>
                        @endswitch
                    @else
                        <i class="fas fa-newspaper text-red-600 dark:text-red-400 text-2xl"></i>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content Section - Clean Minimal -->
        <div class="p-5 flex flex-col h-full">
            <!-- Category Label -->
            <p class="text-xs font-semibold text-red-600 dark:text-red-400 uppercase tracking-widest mb-2 group-hover:text-red-700 dark:group-hover:text-red-300 transition-colors">
                @if($article->category)
                    {{ $article->category->name }}
                @else
                    Artikel
                @endif
            </p>

            <!-- Title -->
            <h3 class="text-base font-bold leading-snug text-gray-900 dark:text-gray-50 line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors duration-300 mb-4 link-accent">
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