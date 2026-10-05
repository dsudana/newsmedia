@php
    $featuredStrip = $featuredStrip ?? collect();
@endphp

<div class="bg-rn-panel  border-rn-line">
    <div class="max-w-6xl mx-auto px-4 lg:px-8 py-4">
        <div class="carousel-breaking-strip swiper">
            <div class="swiper-wrapper">
                @foreach ($featuredStrip as $post)
                    <div class="swiper-slide">
                        <a href="{{ route('blog.show', $post->slug) }}" class="flex items-center gap-3 group">
                            <img src="{{ $post->featured_image ? '/storage/' . $post->featured_image : '/images/placeholder.jpg' }}" alt="{{ $post->title }}"
                                class="w-16 h-16 object-cover shrink-0 rounded">
                            <div class="flex-1 min-w-0">
                                <p class="byline text-xs">By {{ $post->user?->name ?? 'Admin' }} <span
                                        class="date">{{ $post->published_at?->translatedFormat('d M Y') }}</span></p>
                                <p
                                    class="text-[13px] font-semibold text-rn-ink leading-snug group-hover:text-rn-red transition-colors line-clamp-2">
                                    {{ $post->title }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
