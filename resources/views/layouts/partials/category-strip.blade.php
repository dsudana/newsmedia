<section>
    <div class="carousel-category-strip swiper">
        <div class="swiper-wrapper">
            @foreach ($categoryStrip ?? [] as $post)
                <div class="swiper-slide !h-auto">
                    <a href="{{ route('blog.show', $post->slug) }}" class="block group rounded-lg bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-shadow overflow-visible h-full flex flex-col">
                        <div class="overflow-hidden aspect-[4/3] image-zoom-container flex-shrink-0">
                            <img src="{{ $post->featured_image ? '/storage/' . $post->featured_image : '/images/placeholder.jpg' }}" alt="{{ $post->title }}"
                                class="w-full h-full object-cover image-zoom group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-3 sm:p-4 flex-1 flex flex-col">
                            <p class="text-[10px] sm:text-xs font-semibold text-red-600 dark:text-red-400 uppercase tracking-widest mb-2">{{ $post->category?->name ?? 'News' }}</p>
                            <p class="text-base sm:text-lg font-bold text-gray-900 dark:text-white line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors mb-2 flex-1">{{ $post->title }}</p>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 flex-shrink-0">{{ $post->published_at?->format('d M Y') }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
