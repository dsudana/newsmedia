@props(['article'])

<a href="{{ $article->url ?? '#' }}" class="flex items-center gap-3 group stagger-item card-lift rounded-lg p-2 -mx-2 shadow-depth">
    <div class="w-14 h-14 rounded overflow-hidden image-zoom-container">
        <img src="{{
            (!$article->featured_image)
                ? '/images/placeholder.jpg'
                : '/storage/' . $article->featured_image
        }}" alt="" class="w-14 h-14 object-cover shrink-0 image-zoom">
    </div>
    <div>
        <p class="byline group-hover:text-red-600 transition-colors duration-300">
            By {{ $article->user?->name ?? 'Admin' }}
            <span class="date group-hover:text-red-600/70 transition-colors duration-300">{{ $article->date ?? $article->created_at?->translatedFormat('d M Y') }}</span>
        </p>
        <p class="text-[13px] font-semibold text-rn-ink leading-snug group-hover:text-rn-red transition-colors duration-300 link-accent">
            {{ $article->title }}
        </p>
    </div>
</a>

