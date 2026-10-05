@props(['article', 'class' => 'aspect-[4/3]'])

<a href="{{ $article->url ?? '#' }}" class="overlay-card block {{ $class }}">
    <img src="{{
        (!$article->featured_image)
            ? '/images/placeholder.jpg'
            : '/storage/' . $article->featured_image
    }}" alt="{{ $article->title }}">
    <div class="overlay-content">
        @if($article->category)
            <span class="tag-pill">{{ $article->category->name ?? 'Uncategorized' }}</span>
        @endif
        <h3 class="text-white text-[15px] font-bold leading-snug">{{ $article->title }}</h3>
        <p class="byline text-white/90 mt-1">
            By {{ $article->user?->name ?? 'Admin' }}
            <span class="date text-white/70">{{ $article->date ?? $article->created_at?->translatedFormat('d M Y') }}</span>
        </p>
    </div>
</a>

