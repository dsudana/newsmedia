@props(['article', 'class' => 'aspect-[4/3]'])

<a href="{{ $article->url ?? '#' }}" class="overlay-card block {{ $class }} stagger-item image-zoom-container">
    <img src="{{
        (!$article->featured_image)
            ? '/images/placeholder.jpg'
            : '/storage/' . $article->featured_image
    }}" alt="{{ $article->title }}">
    <div class="overlay-content group-hover:from-black/95 transition-all duration-300">
        @if($article->category)
            <span class="tag-pill group-hover:shadow-lg transition-shadow duration-300">{{ $article->category->name ?? 'Uncategorized' }}</span>
        @endif
        <h3 class="text-white text-[15px] font-bold leading-snug group-hover:text-red-300 transition-colors duration-300">{{ $article->title }}</h3>
        <p class="byline text-white/90 group-hover:text-white/95 mt-1 transition-colors duration-300">
            By {{ $article->user?->name ?? 'Admin' }}
            <span class="date text-white/70 group-hover:text-white/80 transition-colors duration-300">{{ $article->date ?? $article->created_at?->translatedFormat('d M Y') }}</span>
        </p>
    </div>
</a>

