{{-- Trending News Carousel Section --}}
@php
    $columnsPerSlide = $section->config['columns'] ?? 3;
    $containerWidth = $section->config['container_width'] ?? '100';
    $sliderSpeed = $section->config['slider_speed'] ?? 3000;
    $sortBy = $section->config['sort_by'] ?? 'views';
    $slidePadding = $section->config['slide_padding'] ?? 'px-4';
    $carouselId = 'trending-carousel-' . $section->id;
@endphp

<section class="py-12 px-4 sm:px-6 lg:px-8 bg-white">
    <div class="max-w-6xl mx-auto" style="width: {{ $containerWidth }}%; margin-left: auto; margin-right: auto;">
        {{-- Section Header --}}
        @if ($section->title)
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-1 w-16 bg-red-600 rounded"></div>
                    <span class="text-sm font-bold text-red-600 uppercase tracking-widest">Trending</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ $section->title }}</h2>
                @if ($section->description)
                    <p class="text-lg text-gray-600 max-w-2xl">{{ $section->description }}</p>
                @endif
            </div>
        @endif

        {{-- Carousel Container --}}
        @if ($data['articles'] ?? false)
            <div class="swiper {{ $carouselId }}" data-slides-per-view="{{ $columnsPerSlide }}"
                data-speed="{{ $sliderSpeed }}">
                <div class="swiper-wrapper">
                    @foreach ($data['articles'] as $article)
                        <div class="swiper-slide {{ $slidePadding }}">
                            {{-- Article Card --}}
                            <article
                                class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100 h-full flex flex-col">
                                {{-- Featured Image --}}
                                <a href="{{ route('articles.show', $article->slug) }}"
                                    class="block overflow-hidden mb-0">
                                    <div class="h-48 bg-gray-200 overflow-hidden">
                                        @if ($article->featured_image)
                                            <img src="{{ asset('storage/' . $article->featured_image) }}"
                                                alt="{{ $article->title }}"
                                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div
                                                class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </a>

                                {{-- Content --}}
                                <div class="p-4 flex flex-col flex-grow">
                                    {{-- Category Badge --}}
                                    @if ($article->category)
                                        <span
                                            class="inline-block px-3 py-1 bg-red-600 text-white text-xs font-bold uppercase rounded mb-3 w-fit">
                                            {{ $article->category->name }}
                                        </span>
                                    @endif

                                    {{-- Title --}}
                                    <h3 class="text-lg font-bold mb-3 leading-tight line-clamp-2 flex-grow">
                                        <a href="{{ route('articles.show', $article->slug) }}"
                                            class="text-gray-900 group-hover:text-red-600 transition-colors">
                                            {{ $article->title }}
                                        </a>
                                    </h3>

                                    {{-- Meta Information --}}
                                    <div
                                        class="flex items-center justify-between pt-3 border-t border-gray-200 mt-auto">
                                        <div class="flex items-center gap-2">
                                            {{-- Author Avatar --}}
                                            <div
                                                class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center text-white text-xs font-bold">
                                                {{ strtoupper(substr($article->user->name ?? 'A', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-900">
                                                    {{ $article->user->name ?? 'Editor' }}</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $article->created_at->format('M d') }}</p>
                                            </div>
                                        </div>

                                        {{-- Read More --}}
                                        <a href="{{ route('articles.show', $article->slug) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600 group-hover:bg-red-600 group-hover:text-white transition-all">
                                            <i class="fas fa-arrow-right text-xs"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

                {{-- Navigation Buttons --}}
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>

            {{-- Initialize Swiper --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const carousel = document.querySelector('.{{ $carouselId }}');
                    if (carousel) {
                        new Swiper('.{{ $carouselId }}', {
                            slidesPerView: parseInt(carousel.dataset.slidesPerView) || 3,
                            spaceBetween: 24,
                            speed: parseInt(carousel.dataset.speed) || 3000,
                            loop: true,
                            autoplay: {
                                delay: 0,
                                disableOnInteraction: false,
                            },
                            navigation: {
                                nextEl: '.{{ $carouselId }} .swiper-button-next',
                                prevEl: '.{{ $carouselId }} .swiper-button-prev',
                            },
                            breakpoints: {
                                320: {
                                    slidesPerView: 1,
                                    spaceBetween: 12,
                                },
                                640: {
                                    slidesPerView: 2,
                                    spaceBetween: 16,
                                },
                                1024: {
                                    slidesPerView: parseInt(carousel.dataset.slidesPerView) || 3,
                                    spaceBetween: 24,
                                },
                            },
                        });
                    }
                });
            </script>
        @else
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No articles available for this carousel.</p>
            </div>
        @endif
    </div>
</section>

<style>
    .{{ $carouselId }} {
        position: relative;
        padding: 0 50px;
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
    }

    .{{ $carouselId }} .swiper-button-prev:hover,
    .{{ $carouselId }} .swiper-button-next:hover {
        background-color: rgb(239, 68, 68);
        transform: scale(1.1);
    }

    .{{ $carouselId }} .swiper-button-prev::after,
    .{{ $carouselId }} .swiper-button-next::after {
        font-size: 18px;
    }
</style>
