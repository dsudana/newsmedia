@props(['tags'])

<div class="flex flex-wrap gap-2">
    @foreach ($tags as $tag)
        <a href="{{ route('blog.tag', $tag->slug) }}"
           class="text-xs font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600/40 px-3 py-1.5 rounded-full hover:border-red-600 hover:text-red-600 dark:hover:text-red-400 dark:hover:border-red-500 transition-colors">
            @if(is_string($tag))
                #{{ $tag }}
            @else
                #{{ $tag->name ?? $tag }}
            @endif
        </a>
    @endforeach
</div>
