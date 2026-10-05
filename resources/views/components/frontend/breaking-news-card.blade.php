@props(['breakingNews'])

@if($breakingNews->isNotEmpty())
    <div class="mb-6">
        <div x-data="{ show: true }" x-show="show" class="flex h-12 bg-red-600 dark:bg-red-700 shadow-lg">
            <!-- Left Column: Breaking News Label (Black) -->
            <div class="bg-black dark:bg-gray-950 px-4 py-0 flex items-center flex-shrink-0 min-w-fit">
                <div class="flex items-center gap-2">
                    <i class="fas fa-circle-exclamation text-red-500 text-sm"></i>
                    <span class="text-white font-bold text-sm uppercase tracking-wider">Breaking News</span>
                </div>
            </div>

            <!-- Right Column: Running Text Carousel (Red) -->
            <div class="flex-1 flex items-center overflow-hidden relative">
                <div class="flex items-center gap-4 px-4 w-full" style="animation: marquee 30s linear infinite;">
                    @foreach($breakingNews as $news)
                        <a href="{{ route('blog.show', $news->slug) }}" class="flex-shrink-0 text-white hover:text-red-100 transition group whitespace-nowrap">
                            <span class="text-sm font-semibold">{{ $news->title }}</span>
                            <span class="mx-3 text-white/40">•</span>
                        </a>
                    @endforeach
                    {{-- Duplicate untuk seamless loop --}}
                    @foreach($breakingNews as $news)
                        <a href="{{ route('blog.show', $news->slug) }}" class="flex-shrink-0 text-white hover:text-red-100 transition group whitespace-nowrap">
                            <span class="text-sm font-semibold">{{ $news->title }}</span>
                            <span class="mx-3 text-white/40">•</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Close Button -->
            <button @click="show = false" class="px-4 flex-shrink-0 text-white/60 hover:text-white transition">
                <i class="fas fa-times text-lg"></i>
            </button>

            <style>
                @keyframes marquee {
                    0% {
                        transform: translateX(0);
                    }
                    100% {
                        transform: translateX(-50%);
                    }
                }
            </style>
        </div>
    </div>
@endif
