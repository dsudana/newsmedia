@props(['article'])

<a href="{{ $article->url ?? '#' }}" class="flex items-center gap-3 group">
    <img src="{{
        (!$article->featured_image)
            ? '/images/placeholder.jpg'
            : '/storage/' . $article->featured_image
    }}" alt="" class="w-14 h-14 object-cover shrink-0">
    <div>
        <p class="byline">
            By {{ $article->user?->name ?? 'Admin' }}
            <span class="date">{{ $article->date ?? $article->created_at?->format('M d, Y') }}</span>
        </p>
        <p class="text-[13px] font-semibold text-rn-ink leading-snug group-hover:text-rn-red transition-colors">
            {{ $article->title }}
        </p>
    </div>
</a>

