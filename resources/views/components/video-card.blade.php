@props(['video'])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-lg transition-shadow overflow-hidden group">
    <!-- Thumbnail Container -->
    <div class="relative h-48 bg-gray-200 dark:bg-gray-700 overflow-hidden">
        <!-- Thumbnail Image -->
        <img
            src="{{ $video->thumbnail_url }}"
            alt="{{ $video->title }}"
            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
            loading="lazy"
        >

        <!-- YouTube Play Icon Overlay -->
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors flex items-center justify-center">
            <div class="bg-red-600 rounded-full p-3 opacity-0 group-hover:opacity-100 transition-opacity transform scale-0 group-hover:scale-100 transition-transform">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path>
                </svg>
            </div>
        </div>

        <!-- Status Badge -->
        <span class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full">
            {{ $video->status === 'published' ? 'Live' : 'Draft' }}
        </span>
    </div>

    <!-- Content -->
    <div class="p-4">
        <!-- Title -->
        <h3 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-2 mb-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
            {{ $video->title }}
        </h3>

        <!-- Category Badge -->
        @if($video->category)
            <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold px-2.5 py-1 rounded mb-3">
                {{ $video->category->name }}
            </span>
        @endif

        <!-- Metadata -->
        <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
            <span class="flex items-center gap-1">
                <i class="fas fa-eye"></i>
                {{ number_format($video->views_count) }}
            </span>
            <span>{{ $video->published_at?->format('d M Y') }}</span>
        </div>
    </div>
</div>
