<div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 py-3">
    <div class="rt-container flex items-center">
        <div
            class="bg-blue-600 text-white text-xs font-bold uppercase px-3 py-1 rounded-sm mr-4 flex-shrink-0 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M5.52.359A.5.5 0 0 1 6 0h4a.5.5 0 0 1 .474.658L8.694 6H12.5a.5.5 0 0 1 .395.807l-7 9a.5.5 0 0 1-.873-.454L6.823 9.5H3.5a.5.5 0 0 1-.48-.641z" />
            </svg>
            Trending
        </div>

        <div class="flex-1 overflow-hidden relative group">
            <div class="animate-marquee inline-block whitespace-nowrap hover:[animation-play-state:paused]">
                @foreach($articles as $article)
                    <a href="{{ route('articles.show', $article->slug) }}"
                        class="text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 mr-8 font-medium">
                        <span class="text-gray-400 mr-1">#{{ $loop->iteration }}</span> {{ $article->title }}
                    </a>
                    <span class="text-gray-300 mr-8">|</span>
                @endforeach
                <!-- Duplicate for seamless loop -->
                @foreach($articles as $article)
                    <a href="{{ route('articles.show', $article->slug) }}"
                        class="text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 mr-8 font-medium">
                        <span class="text-gray-400 mr-1">#{{ $loop->iteration }}</span> {{ $article->title }}
                    </a>
                    <span class="text-gray-300 mr-8">|</span>
                @endforeach
            </div>
        </div>
    </div>
</div>

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
        animation: marquee 30s linear infinite;
    }
</style>