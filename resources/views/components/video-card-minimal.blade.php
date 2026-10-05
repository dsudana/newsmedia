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
    </div>

    <!-- Content - Minimal -->
    <div class="p-4">
        <!-- Title Only -->
        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors" style="text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);">
            {{ $video->title }}
        </h3>
    </div>
</div>
