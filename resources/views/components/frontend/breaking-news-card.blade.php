@props(['breakingNews'])

@if($breakingNews->isNotEmpty())
    <div class="mb-8">
        <div x-data="{ show: true }" x-show="show" class="bg-gradient-to-r from-red-600 to-red-700 dark:from-red-700 dark:to-red-800 text-white rounded-lg p-5 border-l-4 border-red-900 shadow-lg">
            <div class="flex items-start justify-between gap-4">
                <!-- Breaking News Content -->
                <div class="flex-1 min-w-0">
                    <!-- Header with Badge -->
                    <div class="flex items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1 bg-white text-red-600 px-3 py-1 text-xs font-bold rounded-full">
                            <i class="fas fa-circle-exclamation"></i> BREAKING
                        </span>
                        <span class="text-xs font-semibold opacity-90">Berita Terbaru</span>
                    </div>

                    <!-- News Items -->
                    <div class="space-y-2">
                        @foreach($breakingNews as $news)
                            <a href="{{ route('blog.show', $news->slug) }}" class="block group">
                                <h3 class="font-bold text-base leading-tight group-hover:underline transition">
                                    {{ $news->title }}
                                </h3>
                                <p class="text-xs opacity-90 mt-1">
                                    {{ $news->category?->name }} •
                                    <span title="{{ $news->published_at->format('d M Y H:i') }}">
                                        {{ \App\Helpers\DateHelper::relativeTime($news->published_at) }}
                                    </span>
                                </p>
                            </a>
                        @endforeach
                    </div>

                    <!-- CTA Button -->
                    <div class="mt-4 pt-3 border-t border-red-500/30">
                        <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold hover:opacity-80 transition">
                            Lihat Semua Berita
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Close Button -->
                <button @click="show = false" class="mt-1 text-white/70 hover:text-white transition p-2 -m-2">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
    </div>
@endif
