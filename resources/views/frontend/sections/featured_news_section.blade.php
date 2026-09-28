<section class="border-b border-gray-100 last:border-b-0">

    @php
        $carouselEnabled = $data['carousel_enabled'] ?? false;
        $carouselSpeed = $data['carousel_speed'] ?? 5000;
        $mainArticles = $data['main_articles'] ?? [];
        $sideArticles = $data['side_articles'] ?? [];
        $sectionId = $section->id ?? rand(1000, 9999);
        $carouselId = "featured-carousel-{$sectionId}";
    @endphp

    <style>
        /* =========================
       MAIN LAYOUT
    ========================= */

        .featured-news-item,
        .featured-news-side-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            width: 100%;
            background: #f3f4f6;
        }

        /* =========================
       MOBILE FIRST
    ========================= */

        .featured-news-item {
            aspect-ratio: 16 / 10;
            min-height: 280px;
        }

        .featured-news-side-item {
            aspect-ratio: 16 / 9;
            min-height: 180px;
        }

        .featured-news-right {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
            height: 100%;
        }

        /* =========================
       IMAGE
    ========================= */

        .featured-news-item img,
        .featured-news-side-item img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .45s ease;
        }

        .featured-news-item:hover img,
        .featured-news-side-item:hover img {
            transform: scale(1.05);
        }

        /* =========================
       OVERLAY
    ========================= */

        .featured-news-overlay {
            position: absolute;
            inset: 0;
            z-index: 2;

            display: flex;
            flex-direction: column;
            justify-content: flex-end;

            padding: 24px;

            color: white;
            text-decoration: none;

            background:
                linear-gradient(to top,
                    rgba(0, 0, 0, .92) 0%,
                    rgba(0, 0, 0, .55) 55%,
                    rgba(0, 0, 0, 0) 100%);
        }

        /* =========================
       CATEGORY
    ========================= */

        .featured-news-category {
            display: inline-block;
            width: fit-content;

            background: #dc2626;
            color: white;

            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;

            padding: 6px 12px;
            border-radius: 4px;

            margin-bottom: 12px;
        }

        /* =========================
       TITLE
    ========================= */

        .featured-news-title {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.35;
            margin-bottom: 12px;

            color: white;
            transition: color .3s ease;
        }

        .featured-news-side-item .featured-news-title {
            font-size: 1rem;
            margin-bottom: 8px;
        }

        .featured-news-item:hover .featured-news-title,
        .featured-news-side-item:hover .featured-news-title {
            color: #fecaca;
        }

        /* =========================
       META
    ========================= */

        .featured-news-meta {
            font-size: 14px;
            color: #e5e7eb;
        }

        .featured-news-side-item .featured-news-meta {
            font-size: 12px;
        }

        /* =========================
       TABLET
    ========================= */

        @media (min-width: 640px) {

            .featured-news-right {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .featured-news-item {
                min-height: 380px;
            }

            .featured-news-side-item {
                min-height: 220px;
            }

            .featured-news-title {
                font-size: 1.5rem;
            }
        }

        /* =========================
       MOBILE OPTIMIZATION
    ========================= */

        @media (max-width: 640px) {

            .featured-news-overlay {
                padding: 16px;
            }

            .featured-news-title {
                font-size: 1.15rem;
                line-height: 1.4;
                margin-bottom: 8px;
            }

            .featured-news-side-item .featured-news-title {
                font-size: .95rem;
            }

            .featured-news-category {
                font-size: 10px;
                padding: 4px 8px;
                margin-bottom: 10px;
            }

            .featured-news-meta {
                font-size: 11px;
            }

            .featured-news-side-item .featured-news-meta {
                font-size: 10px;
            }

            .{{ $carouselId }} .slick-prev,
            .{{ $carouselId }} .slick-next {
                width: 34px !important;
                height: 34px !important;
            }
        }

        /* =========================
       DESKTOP
    ========================= */

        @media (min-width: 1024px) {

            .featured-news-item,
            .featured-news-side-item {
                aspect-ratio: auto;
                min-height: unset;
                height: 100%;
            }

            .featured-news-right {
                grid-template-columns: 1fr;
                grid-template-rows: repeat(2, minmax(0, 1fr));
                gap: 20px;
            }
        }

        /* =========================
       CAROUSEL
    ========================= */

        .{{ $carouselId }} {
            width: 100%;
            height: 100%;
        }

        .{{ $carouselId }},
        .{{ $carouselId }} .slick-list,
        .{{ $carouselId }} .slick-track,
        .{{ $carouselId }} .slick-slide,
        .{{ $carouselId }} .slick-slide>div {
            height: 100%;
        }

        .{{ $carouselId }} .slick-track {
            display: flex;
        }

        .{{ $carouselId }} .slick-slide {
            height: inherit !important;
            padding: 0;
            outline: none;
        }

        .{{ $carouselId }} .slick-prev,
        .{{ $carouselId }} .slick-next {
            width: 40px !important;
            height: 40px !important;

            background: rgba(0, 0, 0, .55) !important;
            border-radius: 4px !important;

            z-index: 10;

            transition: background .3s ease;
        }

        .{{ $carouselId }} .slick-prev:hover,
        .{{ $carouselId }} .slick-next:hover {
            background: rgba(0, 0, 0, .8) !important;
        }

        .{{ $carouselId }} .slick-prev {
            left: 15px !important;
        }

        .{{ $carouselId }} .slick-next {
            right: 15px !important;
        }

        .{{ $carouselId }} .slick-dots {
            display: flex !important;
            justify-content: center;

            gap: 8px;

            position: absolute;

            bottom: 16px;
            left: 0;
            right: 0;

            z-index: 10;
        }

        .{{ $carouselId }} .slick-dots li button:before {
            content: "";

            width: 8px;
            height: 8px;

            background: rgba(255, 255, 255, .5);
            border-radius: 9999px;

            display: block;
            opacity: 1;
        }

        .{{ $carouselId }} .slick-dots li.slick-active button:before {
            background: white;
        }
    </style>

    <div class="{{ isset($isBuilder) ? '' : 'max-w-6xl px-4 lg:px-8 mx-auto' }}">

        @if ($section->title ?? null)
            <div class="news-section-title mb-8">
                <h2 class="text-4xl font-bold text-black">
                    {{ $section->title }}
                </h2>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 lg:h-[560px]">

            <!-- Left -->
            <div class="lg:col-span-2 h-full">

                @if ($carouselEnabled && count($mainArticles) > 1)

                    <div class="{{ $carouselId }} carousel-featured h-full">

                        @forelse ($mainArticles as $article)
                            <div class="featured-news-item block group h-full">

                                <a href="{{ route('blog.show', $article->slug) }}" class="block h-full">

                                    <img src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : 'https://placehold.co/800x500/e5e7eb/6b7280?text=No+Image' }}"
                                        alt="{{ $article->title }}">

                                    <div class="featured-news-overlay">

                                        @if ($article->category)
                                            <div class="featured-news-category">
                                                {{ $article->category->name }}
                                            </div>
                                        @endif

                                        <h3 class="featured-news-title text-md">
                                            {{ $article->title }}
                                        </h3>

                                        <div class="featured-news-meta">
                                            <span>By {{ $article->user?->name ?? 'Admin' }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $article->published_at?->format('F d, Y') }}</span>
                                        </div>

                                    </div>

                                </a>

                            </div>

                        @empty

                            <div class="featured-news-item bg-gray-200 flex items-center justify-center h-full">
                                <p class="text-white">No articles available</p>
                            </div>
                        @endforelse

                    </div>
                @elseif (count($mainArticles) > 0)
                    @php
                        $featuredArticle = $mainArticles->first();
                    @endphp

                    <a href="{{ route('blog.show', $featuredArticle->slug) }}"
                        class="featured-news-item block group h-full">

                        <img src="{{ $featuredArticle->featured_image ? asset('storage/' . $featuredArticle->featured_image) : 'https://placehold.co/800x500/e5e7eb/6b7280?text=No+Image' }}"
                            alt="{{ $featuredArticle->title }}">

                        <div class="featured-news-overlay">

                            @if ($featuredArticle->category)
                                <div class="featured-news-category">
                                    {{ $featuredArticle->category->name }}
                                </div>
                            @endif

                            <h3 class="featured-news-title">
                                {{ $featuredArticle->title }}
                            </h3>

                            <div class="featured-news-meta">
                                <span>By {{ $featuredArticle->user?->name ?? 'Admin' }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $featuredArticle->published_at?->format('F d, Y') }}</span>
                            </div>

                        </div>

                    </a>

                @endif

            </div>

            <!-- Right -->
            <div class="lg:col-span-1 h-full">

                <div class="featured-news-right">

                    @forelse($sideArticles as $article)
                        <a href="{{ route('blog.show', $article->slug) }}" class="featured-news-side-item block group">

                            <img src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : 'https://placehold.co/400x300/e5e7eb/6b7280?text=No+Image' }}"
                                alt="{{ $article->title }}">

                            <div class="featured-news-overlay">

                                @if ($article->category)
                                    <div class="featured-news-category">
                                        {{ $article->category->name }}
                                    </div>
                                @endif

                                <h4 class="featured-news-title text-sm">
                                    {{ $article->title }}
                                </h4>

                                <div class="featured-news-meta text-xs">
                                    <span>By {{ $article->user?->name ?? 'Admin' }}</span>
                                </div>

                            </div>

                        </a>

                    @empty

                        <div class="flex items-center justify-center h-full">
                            <p class="text-gray-500">No articles available</p>
                        </div>
                    @endforelse

                </div>

            </div>

        </div>

    </div>

    @if ($carouselEnabled && count($mainArticles) > 1)
        <script>
            (function() {
                const selector = '.{{ $carouselId }}.carousel-featured';
                let retries = 0;
                const maxRetries = 50;

                function initCarousel() {
                    if (typeof window.jQuery === 'undefined') {
                        console.log('[{{ $carouselId }}] jQuery not ready, retrying...');
                        if (retries < maxRetries) {
                            retries++;
                            return setTimeout(initCarousel, 100);
                        }
                        return;
                    }

                    if (typeof jQuery.fn.slick === 'undefined') {
                        console.log('[{{ $carouselId }}] Slick not ready, retrying...');
                        if (retries < maxRetries) {
                            retries++;
                            return setTimeout(initCarousel, 100);
                        }
                        return;
                    }

                    try {
                        const $carousel = jQuery(selector);

                        if ($carousel.length === 0 || $carousel.hasClass('slick-initialized')) {
                            return;
                        }

                        $carousel.slick({
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            infinite: true,
                            autoplay: true,
                            autoplaySpeed: {{ $carouselSpeed }},
                            speed: 500,
                            arrows: true,
                            dots: true,
                            pauseOnHover: true,
                            adaptiveHeight: false,
                            variableWidth: false,
                        });

                        console.log('[{{ $carouselId }}] Featured carousel initialized');
                    } catch (error) {
                        console.error('[{{ $carouselId }}] Error:', error.message);
                    }
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initCarousel);
                } else {
                    initCarousel();
                }
            })();
        </script>
    @endif

</section>
