<section class="py-4">
    <h2 class="section-heading">Sports</h2>

    <div class="carousel-sports swiper">
        <div class="swiper-wrapper">
            @foreach (($sportsPosts ?? []) as $post)
                <div class="swiper-slide !h-auto">
                    <a href="{{ route('blog.show', $post->slug) }}" class="group block rounded-lg bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-shadow overflow-visible h-full flex flex-col">
                        <div class="aspect-[3/2] overflow-hidden bg-gray-200 dark:bg-gray-700 group-hover:opacity-90 transition-opacity image-zoom-container flex-shrink-0"
                            style="background-image: url('{{ $post->featured_image ? '/storage/' . $post->featured_image : '/images/placeholder.jpg' }}'); background-size: cover; background-position: center;">
                        </div>
                        <div class="px-3 sm:px-4 py-2 sm:py-3 flex-1 flex flex-col">
                            <p class="text-[10px] sm:text-xs font-semibold text-red-600 dark:text-red-400 uppercase tracking-widest mb-1">{{ $post->category?->name ?? 'Sports' }}</p>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white leading-snug line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors mb-2 flex-1">
                                {{ $post->title }}
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 flex-shrink-0">{{ $post->published_at?->format('d M Y') }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
