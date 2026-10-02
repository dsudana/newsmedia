{{-- Hero Carousel Section --}}
@php
    $autoplaySpeed = $section->config['autoplay_speed'] ?? 4000;
    $containerWidth = $section->config['container_width'] ?? '100';
    $mainArticles = $data['main_articles'] ?? [];
    $sideArticles = $data['side_articles'] ?? [];
    $carouselId = 'hero-carousel-' . $section->id;
@endphp

<section id="hero-section" class="py-0">
    <div style="width: {{ $containerWidth }}%; margin-left: auto; margin-right: auto;">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
            {{-- Main Featured Article Carousel (2/3 Width) --}}
            <div class="lg:col-span-2">
                @if($mainArticles->count() > 0)
                    <div class="carousel-hero-main swiper {{ $carouselId }}" data-autoplay="{{ $autoplaySpeed }}">
                        <div class="swiper-wrapper">
                            @foreach($mainArticles as $mainArticle)
                                <div class="swiper-slide">
                                    <a href="{{ route('articles.show', $mainArticle->slug) }}" class="block group">
                                        @php
                                            $backgroundStyle = $mainArticle->featured_image
                                                ? "background-image: url('" .
                                                    addslashes(e(asset('storage/' . $mainArticle->featured_image))) .
                                                    "'); background-size: cover; background-position: center;"
                                                : "background-image: url('/images/placeholder.jpg'); background-size: cover; background-position: center;";
                                        @endphp
                                        <div class="relative h-96 lg:h-[400px] overflow-hidden bg-gray-900 group-hover:opacity-90 transition-opacity duration-300"
                                            style="{{ $backgroundStyle }}">
                                            {{-- Dark overlay --}}
                                            <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors duration-300"></div>

                                            {{-- Content Overlay --}}
                                            <div class="absolute inset-0 flex flex-col justify-end p-4 sm:p-6">
                                                {{-- Category Badge --}}
                                                <div class="mb-3 sm:mb-4">
                                                    <span class="inline-block bg-red-600 text-white px-3 py-1 text-xs font-bold uppercase">
                                                        {{ $mainArticle->category->name ?? 'News' }}
                                                    </span>
                                                </div>

                                                {{-- Title --}}
                                                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2 sm:mb-3 leading-tight line-clamp-3">
                                                    {{ $mainArticle->title }}
                                                </h2>

                                                {{-- Meta Info --}}
                                                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-gray-200 text-xs sm:text-sm">
                                                    <span class="font-semibold">By {{ $mainArticle->user->name ?? 'Editor' }}</span>
                                                    <span>{{ $mainArticle->published_at->format('F d, Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        {{-- Navigation --}}
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                @else
                    <div class="relative h-96 lg:h-[400px] overflow-hidden bg-gray-900">
                        <div class="absolute inset-0 bg-black/40"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <p class="text-white text-lg">No articles available</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Side Featured Articles (1/3 Width) --}}
            <div class="lg:col-span-1">
                <div class="flex flex-col h-96 lg:h-[400px] gap-0">
                    @if($sideArticles->count() > 0)
                        @foreach($sideArticles as $sideArticle)
                            <a href="{{ route('articles.show', $sideArticle->slug) }}" class="group block flex-1">
                                @php
                                    $sideBackgroundStyle = $sideArticle->featured_image
                                        ? "background-image: url('" .
                                            addslashes(e(asset('storage/' . $sideArticle->featured_image))) .
                                            "'); background-size: cover; background-position: center;"
                                        : "background-image: url('/images/placeholder.jpg'); background-size: cover; background-position: center;";
                                @endphp
                                <div class="relative h-full overflow-hidden bg-gray-900 group-hover:opacity-90 transition-opacity duration-300"
                                    style="{{ $sideBackgroundStyle }}">
                                    {{-- Dark overlay --}}
                                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors duration-300"></div>

                                    {{-- Content Overlay --}}
                                    <div class="absolute inset-0 flex flex-col justify-end p-3 sm:p-4">
                                        {{-- Category Badge --}}
                                        <div class="mb-2 sm:mb-3">
                                            <span class="inline-block bg-red-600 text-white px-2 py-1 text-xs font-bold uppercase">
                                                {{ $sideArticle->category->name ?? 'News' }}
                                            </span>
                                        </div>

                                        {{-- Title --}}
                                        <h3 class="text-sm sm:text-base font-bold text-white line-clamp-2">
                                            {{ $sideArticle->title }}
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        @for($i = 0; $i < 2; $i++)
                            <div class="flex-1 relative overflow-hidden bg-gray-900">
                                <div class="absolute inset-0 bg-black/40"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <p class="text-gray-400 text-xs text-center">No article</p>
                                </div>
                            </div>
                        @endfor
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Swiper Initialization --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.querySelector('.{{ $carouselId }}');
        if (carousel) {
            new Swiper('.{{ $carouselId }}', {
                slidesPerView: 1,
                spaceBetween: 0,
                autoplay: {
                    delay: parseInt(carousel.dataset.autoplay) || 4000,
                    disableOnInteraction: false,
                },
                loop: true,
                navigation: {
                    nextEl: '.{{ $carouselId }} .swiper-button-next',
                    prevEl: '.{{ $carouselId }} .swiper-button-prev',
                },
                speed: 800,
            });
        }
    });
</script>

<style>
    .{{ $carouselId }} {
        position: relative;
    }

    .{{ $carouselId }} .swiper-button-prev,
    .{{ $carouselId }} .swiper-button-next {
        width: 40px;
        height: 40px;
        background-color: rgba(239, 68, 68, 0.9);
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        top: 50%;
        transform: translateY(-50%);
    }

    .{{ $carouselId }} .swiper-button-prev {
        left: 20px;
    }

    .{{ $carouselId }} .swiper-button-next {
        right: 20px;
    }

    .{{ $carouselId }} .swiper-button-prev:hover,
    .{{ $carouselId }} .swiper-button-next:hover {
        background-color: rgb(239, 68, 68);
        transform: translateY(-50%) scale(1.1);
    }

    .{{ $carouselId }} .swiper-button-prev::after,
    .{{ $carouselId }} .swiper-button-next::after {
        font-size: 18px;
    }

    @media (max-width: 1024px) {
        .{{ $carouselId }} .swiper-button-prev,
        .{{ $carouselId }} .swiper-button-next {
            width: 35px;
            height: 35px;
        }

        .{{ $carouselId }} .swiper-button-prev {
            left: 10px;
        }

        .{{ $carouselId }} .swiper-button-next {
            right: 10px;
        }

        .{{ $carouselId }} .swiper-button-prev::after,
        .{{ $carouselId }} .swiper-button-next::after {
            font-size: 14px;
        }
    }
</style>
