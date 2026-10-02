<section class="py-8">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        @if($title ?? null)
            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $title }}</h2>
        @endif

        <div class="carousel-sports-{{ $section->id }} swiper">
            <div class="swiper-wrapper">
                @forelse ($data['articles'] ?? [] as $article)
                    <div class="swiper-slide">
                        <a href="{{ route('blog.show', $article->slug) }}" class="group block overflow-hidden">
                            <div class="aspect-[3/2] overflow-hidden mb-3 bg-gray-900 group-hover:opacity-90 transition-opacity">
                                <img src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : '/images/placeholder.jpg' }}"
                                     alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div>
                                <span class="inline-block px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded mb-2">
                                    {{ $article->category?->name ?? 'Sports' }}
                                </span>
                                <h3 class="text-sm font-bold text-gray-900 leading-snug line-clamp-2 group-hover:text-red-600 transition-colors">
                                    {{ $article->title }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-2">By {{ $article->user?->name ?? 'Admin' }} • {{ $article->published_at?->format('M d') }}</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="swiper-slide text-center py-8">
                        <p class="text-gray-500">No sports articles available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<script>
    new Swiper('.carousel-sports-{{ $section->id }}', {
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
