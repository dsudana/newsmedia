<div id="default-carousel" class="relative w-full" data-carousel="slide">
    <!-- Carousel wrapper -->
    <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
        @foreach ($slider as $s)
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="{{ $s->featured_image ? '/storage/' . $s->featured_image : '/images/placeholder-news-media.svg' }}"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 object-cover h-full"
                    alt="...">
                <div class="absolute inset-0 bg-black bg-opacity-40 transition-opacity duration-300"></div>
                <!-- Title -->
                <div
                    class="absolute z-20 max-w-[90%] md:max-w-[70%] left-5 bottom-0 inset-0 text-white flex flex-col justify-end pb-8 md:pb-12">
                    <span class="inline-block bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-sm mb-2 w-fit">
                        {{ $s->category->name ?? 'News' }}
                    </span>
                    <h3 class="text-lg md:text-3xl font-bold leading-tight mb-3 drop-shadow-md">
                        <a href="{{ route('articles.show', $s) }}" class="hover:underline">
                            {{ $s->title }}
                        </a>
                    </h3>
                    <div class="text-xs md:text-sm text-gray-200 flex items-center gap-3">
                        <span class="font-semibold">{{ $s->user->name ?? 'Redaksi' }}</span>
                        <span>&bull;</span>
                        <span>{{ $s->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Slider controls -->
    <button type="button"
        class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
        data-carousel-next>
        <span
            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
            <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 9 4-4-4-4" />
            </svg>
            <span class="sr-only">Next</span>
        </span>
    </button>
    <button type="button"
        class="absolute top-0 end-12 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
        data-carousel-prev>
        <span
            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
            <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 1 1 5l4 4" />
            </svg>
            <span class="sr-only">Previous</span>
        </span>
    </button>
</div>