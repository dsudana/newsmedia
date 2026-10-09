<!-- Article Card Component - Clean Minimal Design with Icon Overlay -->
<article {{ $attributes->merge(['class' => 'group flex flex-col h-full stagger-item card-lift bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300']) }}>
    <a href="{{ route('articles.show', $article->slug) }}" class="block relative flex-1">
        <!-- Large Visual Area with Icon Overlay -->
        <div class="relative w-full h-48 sm:h-52 md:h-56 bg-gray-300 dark:bg-gray-700 overflow-hidden image-zoom-container group/image">
            @if($article->featured_image)
                <img src="{{ asset('storage/' . $article->featured_image) }}"
                     alt="{{ $article->title }}"
                     class="w-full h-full object-cover image-zoom transition-transform duration-300">
            @else
                <div class="w-full h-full bg-gradient-to-br from-red-50 via-red-100 to-red-200 dark:from-red-900/30 dark:via-red-800/30 dark:to-red-700/30 flex items-center justify-center">
                </div>
            @endif

            <!-- Icon Overlay - Centered -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white/90 dark:bg-gray-800/90 rounded-full flex items-center justify-center backdrop-blur-sm transition-transform duration-300 group-hover:scale-110">
                    @if($article->category)
                        @switch($article->category->name)
                            @case('Gaya Hidup')
                                <i class="fas fa-heart text-red-600 dark:text-red-400 text-2xl sm:text-3xl"></i>
                            @break
                            @case('Pemerintahan')
                                <i class="fas fa-landmark text-red-600 dark:text-red-400 text-2xl sm:text-3xl"></i>
                            @break
                            @case('Pendidikan')
                                <i class="fas fa-book text-red-600 dark:text-red-400 text-2xl sm:text-3xl"></i>
                            @break
                            @case('Kesehatan')
                                <i class="fas fa-stethoscope text-red-600 dark:text-red-400 text-2xl sm:text-3xl"></i>
                            @break
                            @case('Bisnis')
                                <i class="fas fa-briefcase text-red-600 dark:text-red-400 text-2xl sm:text-3xl"></i>
                            @break
                            @case('Olahraga')
                                <i class="fas fa-futbol text-red-600 dark:text-red-400 text-2xl sm:text-3xl"></i>
                            @break
                            @default
                                <i class="fas fa-newspaper text-red-600 dark:text-red-400 text-2xl sm:text-3xl"></i>
                        @endswitch
                    @else
                        <i class="fas fa-newspaper text-red-600 dark:text-red-400 text-2xl sm:text-3xl"></i>
                    @endif
                </div>
            </div>
        </div>
    </a>

    <!-- Content Section - Clean & Minimal -->
    <div class="flex-1 flex flex-col px-4 py-4 sm:px-5 sm:py-5">
        <!-- Metadata Label -->
        <p class="text-xs sm:text-sm font-semibold text-red-600 dark:text-red-400 uppercase tracking-widest mb-2 group-hover:text-red-700 dark:group-hover:text-red-300 transition-colors">
            @if($article->category)
                {{ $article->category->name }} · {{ \App\Models\Article::where('category_id', $article->category->id)->where('status', 'published')->count() }} Artikel
            @else
                Artikel
            @endif
        </p>

        <!-- Title - Bold & Prominent -->
        <h3 class="font-bold text-base sm:text-lg leading-snug text-gray-900 dark:text-gray-50 line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors duration-300 mb-3 link-accent">
            {{ $article->title }}
        </h3>

        <!-- Published Date / Last Updated -->
        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors mt-auto">
            Dipublikasikan {{ \App\Helpers\DateHelper::relativeTime($article->published_at) }}
        </p>
    </div>
</article>
