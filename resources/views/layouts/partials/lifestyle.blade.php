<section class="border-rn-line">
    <h2 class="section-heading">Lifestyle</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach (($lifestylePosts ?? []) as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group block rounded-lg bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-shadow overflow-visible">
                <div class="aspect-video overflow-hidden mb-3 sm:mb-4 bg-gray-200 dark:bg-gray-700 group-hover:opacity-90 transition-opacity image-zoom-container"
                    style="background-image: url('{{ $post->featured_image ? '/storage/' . $post->featured_image : '/images/placeholder.jpg' }}'); background-size: cover; background-position: center;">
                </div>
                <div class="px-3 sm:px-4 pb-3 sm:pb-4">
                    <p class="text-[10px] sm:text-xs font-semibold text-red-600 dark:text-red-400 uppercase tracking-widest mb-2">Lifestyle</p>
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white leading-snug line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors mb-2 sm:mb-3">
                        {{ $post->title }}
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $post->published_at?->format('d M Y') }}</p>
                </div>
            </a>
        @endforeach
    </div>
</section>
