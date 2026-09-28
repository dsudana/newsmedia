@php
    $sliders = $data['sliders'] ?? collect();
    $title = $data['title'] ?? '';
@endphp

<section class="bg-white py-8 lg:py-12">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        @if ($title)
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">{{ $title }}</h2>
            </div>
        @endif

        @if ($sliders->count())
            <div class="image-slider-carousel">
                @foreach ($sliders as $slider)
                    <div class="slider-item">
                        <a href="{{ $slider->cta_url ?? '#' }}"
                           class="block group overflow-hidden rounded-lg bg-gray-100 relative">
                            <!-- Image Container -->
                            <div class="relative w-full" style="aspect-ratio: 16/9;">
                                <img src="{{ $slider->image_url }}"
                                     alt="{{ $slider->caption }}"
                                     class="w-full h-full object-cover transition duration-500 group-hover:scale-105">

                                <!-- Overlay Gradient -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                                <!-- Caption & CTA -->
                                <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                                    @if ($slider->caption)
                                        <p class="text-sm md:text-base lg:text-lg font-semibold mb-3 line-clamp-2">
                                            {{ $slider->caption }}
                                        </p>
                                    @endif

                                    @if ($slider->cta_text)
                                        <span class="inline-block px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded transition duration-300">
                                            {{ $slider->cta_text }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-lg bg-gray-100 py-20 px-6 text-center">
                <i class="fas fa-image text-5xl text-gray-400 mb-4 block"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No Sliders Available</h3>
                <p class="text-gray-500">Slider content will be displayed here once added.</p>
            </div>
        @endif
    </div>

    <style>
        .image-slider-carousel {
            position: relative;
        }

        .image-slider-carousel .slick-slide {
            padding: 0;
        }

        /* Hide arrows and dots */
        .image-slider-carousel .slick-prev,
        .image-slider-carousel .slick-next,
        .image-slider-carousel .slick-dots {
            display: none !important;
        }

        /* Smooth transitions */
        .image-slider-carousel .slick-slide {
            transition: opacity 0.3s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.image-slider-carousel') && typeof jQuery !== 'undefined') {
                jQuery('.image-slider-carousel').slick({
                    infinite: true,
                    speed: 500,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    arrows: false,
                    dots: false,
                    fade: true,
                    cssEase: 'ease-in-out'
                });
            }
        });
    </script>
</section>
