<style>
    @keyframes marquee {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    .animate-marquee {
        animation: marquee 25s linear infinite;
    }

    .animate-marquee:hover {
        animation-play-state: paused;
    }
</style>

<div class="max-w-screen-xl mx-auto px-5 py-6 overflow-hidden">
    <div class="flex w-[200%] animate-marquee">
        <!-- Original List -->
        <div class="flex gap-4 pr-4">
            @foreach ($categories as $category)
                <a href="{{ route('articles.index', ['category' => $category->slug]) }}"
                    class="flex items-center space-x-2 bg-white border border-gray-200 rounded-full px-4 py-2 hover:border-blue-500 hover:shadow-sm transition whitespace-nowrap">
                    @if ($category->icon)
                        <img src="/storage/{{ $category->icon }}" class="w-5 h-5 object-contain">
                    @endif
                    <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>

        <!-- Duplicated List for Infinite Effect -->
        <div class="flex gap-4 pr-4">
            @foreach ($categories as $category)
                <a href="{{ route('articles.index', ['category' => $category->slug]) }}"
                    class="flex items-center space-x-2 bg-white border border-gray-200 rounded-full px-4 py-2 hover:border-blue-500 hover:shadow-sm transition whitespace-nowrap">
                    @if ($category->icon)
                        <img src="/storage/{{ $category->icon }}" class="w-5 h-5 object-contain">
                    @endif
                    <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
