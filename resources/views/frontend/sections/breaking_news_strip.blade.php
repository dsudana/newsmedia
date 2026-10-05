<section class="bg-gray-50 border-y border-gray-200 py-4">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        @if($title ?? null)
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
        @endif

        <div class="carousel-breaking-strip-{{ $section->id }} swiper">
            <div class="swiper-wrapper">
                @forelse ($data['articles'] ?? [] as $article)
                    <div class="swiper-slide">
                        <a href="{{ route('blog.show', $article->slug) }}" class="flex items-center gap-3 group">
                            <img src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : '/images/placeholder.jpg' }}"
                                 alt="{{ $article->title }}"
                                 class="w-16 h-16 object-cover rounded shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-600">{{ AppHelpersDateHelper::relativeTime($article->published_at) }}</p>
                                <p class="text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-red-600 transition-colors">
                                    {{ $article->title }}
                                </p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="swiper-slide">
                        <p class="text-gray-500">No breaking news available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<script>
    new Swiper('.carousel-breaking-strip-{{ $section->id }}', {
        slidesPerView: 'auto',
        spaceBetween: 20,
        autoplay: {
            delay: {{ $data['slider_speed'] ?? 3000 }},
            disableOnInteraction: false,
        },
        breakpoints: {
            640: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 },
        },
    });
</script>
