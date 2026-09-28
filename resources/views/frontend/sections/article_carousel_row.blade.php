<section class="py-6 bg-white overflow-hidden">

    @php
        $columns = (int) ($section->config['columns'] ?? 4);
        $sliderSpeed = (int) ($section->config['slider_speed'] ?? 3000);
        $carouselId = "carousel-article-row-{$section->id}";
    @endphp

    <style>
        .{{ $carouselId }} {
            width: 100%;
        }

        .{{ $carouselId }} .slick-list {
            overflow: hidden;
            margin: 0 -12px;
        }

        .{{ $carouselId }} .slick-slide {
            padding: 0 12px;
            box-sizing: border-box;
        }

        .{{ $carouselId }} .slick-slide>div {
            height: 100%;
        }

        .{{ $carouselId }} .item {
            height: 100%;
        }

        .{{ $carouselId }} .article__entry {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: #fff;
            transition: all .3s ease;
        }

        .{{ $carouselId }} .article__entry:hover {
            transform: translateY(-5px);
        }

        .{{ $carouselId }} .article__image {
            position: relative;
            overflow: hidden;
            width: 100%;
            aspect-ratio: 16 / 10;
            background: #f3f4f6;
            margin-bottom: 14px;
            border-radius: 8px;
        }

        .{{ $carouselId }} .article__image a {
            display: block;
            width: 100%;
            height: 100%;
        }

        .{{ $carouselId }} .article__image img {
            width: 100%;
            height: 100%;
            border-radius: 8px;
            object-fit: cover;
            transition: transform .4s ease;
            display: block;
        }

        .{{ $carouselId }} .article__entry:hover .article__image img {
            transform: scale(1.05);
        }

        .{{ $carouselId }} .article__content {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .{{ $carouselId }} .article__content ul {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 4px;
            margin: 0;
            padding: 0;

            font-size: 12px;
            color: #6b7280;
        }

        .{{ $carouselId }} .article__content li {
            display: inline-flex;
            align-items: center;
        }

        .{{ $carouselId }} .article__content li:not(:last-child)::after {
            content: ",";
            margin-left: 4px;
        }

        .{{ $carouselId }} .article__content .author {
            color: #ef4444;
            font-weight: 700;
        }

        .{{ $carouselId }} .article__content h5 {
            margin: 0;

            font-size: 18px;
            line-height: 1.3;
            font-weight: 700;

            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .{{ $carouselId }} .article__content h5 a {
            color: #111827;
            text-decoration: none;
            transition: color .25s ease;
        }

        .{{ $carouselId }} .article__entry:hover h5 a {
            color: #ef4444;
        }

        /* NAVIGATION */

        .{{ $carouselId }} .slick-prev,
        .{{ $carouselId }} .slick-next {
            width: 42px !important;
            height: 42px !important;

            background: rgba(0, 0, 0, .65) !important;

            border-radius: 4px;
            z-index: 20;

            top: 38%;
            transform: translateY(-50%);
        }

        .{{ $carouselId }} .slick-prev:hover,
        .{{ $carouselId }} .slick-next:hover {
            background: rgba(0, 0, 0, .85) !important;
        }

        .{{ $carouselId }} .slick-prev {
            left: 15px;
        }

        .{{ $carouselId }} .slick-next {
            right: 15px;
        }

        .{{ $carouselId }} .slick-prev::before,
        .{{ $carouselId }} .slick-next::before {
            opacity: 1;
            color: #fff;
            font-size: 16px;
        }

        .{{ $carouselId }} .slick-dots {
            display: none !important;
        }

        @media (max-width: 1024px) {
            .{{ $carouselId }} .article__content h5 {
                font-size: 16px;
            }
        }

        @media (max-width: 768px) {

            .{{ $carouselId }} .slick-prev,
            .{{ $carouselId }} .slick-next {
                display: none !important;
            }

            .{{ $carouselId }} .article__content h5 {
                font-size: 15px;
            }
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>


    <div class="max-w-6xl mx-auto">

        @if ($title ?? null)
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">
                    {{ $title }}
                </h2>
            </div>
        @endif

        <div class="{{ $carouselId }}">

            @forelse ($data['articles'] ?? [] as $article)
                <div class="item">

                    <article class="article__entry">

                        <div class="article__image">

                            <a href="{{ route('blog.show', $article->slug) }}">

                                <img src="{{ \App\Helpers\ImageHelper::articleImage($article) }}"
                                    alt="{{ $article->title }}" loading="lazy">

                            </a>

                        </div>

                        <div class="article__content">

                            <ul>

                                <li>
                                    <span class="author">
                                        By {{ $article->user?->name ?? 'Admin' }}
                                    </span>
                                </li>

                                <li>
                                    {{ $article->published_at?->format('F d, Y') }}
                                </li>

                            </ul>

                            <h5>

                                <a href="{{ route('blog.show', $article->slug) }}">
                                    {{ $article->title }}
                                </a>

                            </h5>

                        </div>

                    </article>

                </div>

            @empty

                <div class="text-center py-16">
                    <p class="text-gray-500">
                        No articles available
                    </p>
                </div>
            @endforelse

        </div>

    </div>

    <script>
        (function() {

            const selector = '.{{ $carouselId }}';

            function initCarousel() {

                if (typeof window.jQuery === 'undefined') {
                    return setTimeout(initCarousel, 100);
                }

                if (typeof $.fn.slick === 'undefined') {
                    return setTimeout(initCarousel, 100);
                }

                const $carousel = $(selector);

                if ($carousel.hasClass('slick-initialized')) {
                    return;
                }

                $carousel.slick({

                    slidesToShow: {{ $columns }},
                    slidesToScroll: 1,

                    infinite: true,

                    autoplay: true,
                    autoplaySpeed: {{ $sliderSpeed }},

                    speed: 500,

                    arrows: true,
                    dots: false,

                    pauseOnHover: true,

                    adaptiveHeight: false,
                    variableWidth: false,

                    responsive: [

                        {
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: Math.min({{ $columns }}, 4)
                            }
                        },

                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: Math.min({{ $columns }}, 3)
                            }
                        },

                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2
                            }
                        },

                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 1
                            }
                        }

                    ]

                });

            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCarousel);
            } else {
                initCarousel();
            }

        })();
    </script>

</section>
