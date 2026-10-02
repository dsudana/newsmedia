<section class="py-8">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        @if($title ?? null)
            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $title }}</h2>
        @endif

        <div class="carousel-category-strip-{{ $section->id }} swiper">
            <div class="swiper-wrapper">
                @forelse ($data['articles'] ?? [] as $article)
                    <div class="swiper-slide">
                        <a href="{{ route('blog.show', $article->slug) }}" class="block group">
                            <div class="overflow-hidden aspect-[4/3] mb-3 bg-gray-900 group-hover:opacity-90 transition-opacity">
                                <img src="{{ $article->featured_image ? featuredImageUrl($article->featured_image) : '/images/placeholder.jpg' }}"
                                     alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <p class="text-xs text-gray-500">By {{ $article->user?->name ?? 'Admin' }} • {{ $article->published_at?->format('M d') }}</p>
                            <p class="text-sm font-bold text-gray-900 line-clamp-2 group-hover:text-red-600 transition-colors">
                                {{ $article->title }}
                            </p>
                        </a>
                    </div>
                @empty
                    <div class="swiper-slide text-center py-8">
                        <p class="text-gray-500">No articles available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<script>
    new Swiper('.carousel-category-strip-{{ $section->id }}', {
        slidesPerView: 3,
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
