<section class="py-4 border-rn-line">
    <h2 class="section-heading">Technology</h2>

    <div class="space-y-6">
        @foreach ($technologyPosts ?? [] as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="flex gap-3 sm:gap-4 group hover:shadow-md transition-shadow p-3 sm:p-4 rounded-lg bg-white dark:bg-gray-800 overflow-visible">
                <div class="w-28 sm:w-40 h-24 sm:h-32 overflow-hidden shrink-0 bg-gray-200 dark:bg-gray-700 rounded-lg image-zoom-container"
                    style="background-image: url('{{ $post->featured_image ? '/storage/' . $post->featured_image : '/images/placeholder.jpg' }}'); background-size: cover; background-position: center;">
                </div>
                <div class="flex-1 flex flex-col">
                    <p class="text-[10px] sm:text-xs font-semibold text-red-600 dark:text-red-400 uppercase tracking-widest mb-1">{{ $post->category?->name ?? 'Technology' }}</p>
                    <h3
                        class="text-base sm:text-lg font-bold text-gray-900 dark:text-white leading-snug line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors mb-2">
                        {{ $post->title }}
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-auto">
                        {{ $post->published_at?->format('d M Y') }}</p>
                </div>
            </a>
        @endforeach
    </div>
</section>
