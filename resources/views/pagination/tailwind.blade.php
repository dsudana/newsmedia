@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center gap-2 mt-8">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-3 py-2 text-gray-500">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif
    </nav>
@endif
