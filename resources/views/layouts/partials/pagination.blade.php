<nav class="flex justify-center items-center gap-2 pb-10" aria-label="Pagination">
    @if ($paginator->onFirstPage())
        <span class="w-9 h-9 flex items-center justify-center border border-gray-300 text-gray-400">«</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}"
           class="w-9 h-9 flex items-center justify-center border border-gray-300 text-gray-700 hover:border-red-500 hover:text-red-500 transition-colors">«</a>
    @endif

    @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
        @if ($page == $paginator->currentPage())
            <span class="w-9 h-9 flex items-center justify-center text-[13px] font-semibold bg-red-500 text-white">{{ $page }}</span>
        @else
            <a href="{{ $url }}"
               class="w-9 h-9 flex items-center justify-center text-[13px] font-semibold border border-gray-300 text-gray-700 hover:border-red-500 hover:text-red-500 transition-colors">{{ $page }}</a>
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
           class="w-9 h-9 flex items-center justify-center border border-gray-300 text-gray-700 hover:border-red-500 hover:text-red-500 transition-colors">»</a>
    @else
        <span class="w-9 h-9 flex items-center justify-center border border-gray-300 text-gray-400">»</span>
    @endif
</nav>
