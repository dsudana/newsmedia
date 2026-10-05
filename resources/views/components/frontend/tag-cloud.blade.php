@props(['tags'])

<div class="flex flex-wrap gap-2">
    @foreach ($tags as $tag)
        <a href="{{ route('blog.tag', $tag->slug) }}"
           class="text-[11px] font-semibold text-rn-body border border-rn-line px-3 py-1.5 hover:border-rn-red hover:text-rn-red transition-colors">
            @if(is_string($tag))
                #{{ $tag }}
            @else
                #{{ $tag->name ?? $tag }}
            @endif
        </a>
    @endforeach
</div>
