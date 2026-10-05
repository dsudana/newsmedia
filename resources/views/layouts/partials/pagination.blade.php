<nav class="flex justify-center items-center gap-1 pb-10" aria-label="Pagination">
    <!-- Previous Button -->
    <div>
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 rounded-lg text-gray-400 cursor-not-allowed flex items-center gap-2">
                <i class="fas fa-chevron-left text-sm"></i>
                <span class="hidden sm:inline text-sm font-medium">Previous</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:border-red-500 hover:bg-red-50 hover:text-red-600 transition-all flex items-center gap-2 font-medium">
                <i class="fas fa-chevron-left text-sm"></i>
                <span class="hidden sm:inline text-sm">Previous</span>
            </a>
        @endif
    </div>

    <!-- Page Numbers -->
    <div class="flex items-center gap-1 px-2">
        @php
            $totalPages = $paginator->lastPage();
            $currentPage = $paginator->currentPage();
            $start = 1;
            $end = $totalPages;

            // Show at most 5 page buttons
            if ($totalPages > 7) {
                if ($currentPage <= 3) {
                    $end = 5;
                } elseif ($currentPage >= $totalPages - 2) {
                    $start = $totalPages - 4;
                } else {
                    $start = $currentPage - 2;
                    $end = $currentPage + 2;
                }
            }
        @endphp

        <!-- First page -->
        @if ($start > 1)
            <a href="{{ $paginator->url(1) }}"
               class="w-10 h-10 flex items-center justify-center text-sm font-semibold border border-gray-300 text-gray-700 rounded-lg hover:border-red-500 hover:bg-red-50 hover:text-red-600 transition-all">
                1
            </a>
            @if ($start > 2)
                <span class="w-10 h-10 flex items-center justify-center text-gray-500">...</span>
            @endif
        @endif

        <!-- Page range -->
        @foreach ($paginator->getUrlRange($start, $end) as $page => $url)
            @if ($page == $currentPage)
                <span class="w-10 h-10 flex items-center justify-center text-sm font-bold rounded-lg bg-red-600 text-white shadow-md">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $url }}"
                   class="w-10 h-10 flex items-center justify-center text-sm font-semibold border border-gray-300 text-gray-700 rounded-lg hover:border-red-500 hover:bg-red-50 hover:text-red-600 transition-all">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        <!-- Last page -->
        @if ($end < $totalPages)
            @if ($end < $totalPages - 1)
                <span class="w-10 h-10 flex items-center justify-center text-gray-500">...</span>
            @endif
            <a href="{{ $paginator->url($totalPages) }}"
               class="w-10 h-10 flex items-center justify-center text-sm font-semibold border border-gray-300 text-gray-700 rounded-lg hover:border-red-500 hover:bg-red-50 hover:text-red-600 transition-all">
                {{ $totalPages }}
            </a>
        @endif
    </div>

    <!-- Next Button -->
    <div>
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:border-red-500 hover:bg-red-50 hover:text-red-600 transition-all flex items-center gap-2 font-medium">
                <span class="hidden sm:inline text-sm">Next</span>
                <i class="fas fa-chevron-right text-sm"></i>
            </a>
        @else
            <span class="px-4 py-2 rounded-lg text-gray-400 cursor-not-allowed flex items-center gap-2">
                <span class="hidden sm:inline text-sm font-medium">Next</span>
                <i class="fas fa-chevron-right text-sm"></i>
            </span>
        @endif
    </div>

    <!-- Page Info (optional) -->
    <div class="ml-4 text-xs text-gray-600 dark:text-gray-400 hidden md:block">
        <span class="font-semibold">{{ $currentPage }}</span> / <span>{{ $totalPages }}</span>
    </div>
</nav>
