@props([
    'articles',
    'limit' => 5,
    'showThumbnail' => true,
    'showDate' => true,
    'showViews' => false
])

@php
    $articlesToShow = $articles instanceof \Illuminate\Pagination\Paginator
        ? $articles->items()
        : ($articles ? $articles->take($limit)->values() : collect());
@endphp

@if($articlesToShow && count($articlesToShow) > 0)
    <div class="dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700/50 shadow-sm dark:shadow-md transition-shadow duration-300">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-red-600 dark:bg-red-500 rounded-full"></span>
            {{ $slot }}
        </h3>
        <div class="space-y-4">
            @foreach($articlesToShow as $article)
                <a href="{{ route('blog.show', $article->slug) }}" class="group flex gap-3 pb-4 border-b border-gray-200 dark:border-gray-700/50 last:border-0 last:pb-0 hover:opacity-75 transition">
                    @if($showThumbnail)
                        <div class="flex-shrink-0 w-20 h-20">
                            @if($article->featured_image)
                                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="" class="w-20 h-20 object-cover rounded group-hover:opacity-80 transition" loading="lazy">
                            @else
                                <div class="w-20 h-20 bg-gray-300 dark:bg-gray-700 rounded flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 dark:text-gray-500 text-lg"></i>
                                </div>
                            @endif
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-gray-900 dark:text-gray-100 group-hover:text-red-600 line-clamp-2">{{ $article->title }}</p>
                        @if($showDate || $showViews)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                @if($showDate)
                                    <i class="fas fa-calendar mr-1"></i>{{ $article->published_at->format('d M Y') }}
                                @endif
                                @if($showViews)
                                    @if($showDate) • @endif
                                    <i class="fas fa-eye mr-1"></i>{{ number_format($article->views_count ?? 0) }}
                                @endif
                            </p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif
