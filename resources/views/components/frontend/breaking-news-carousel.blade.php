@props(['announcements', 'articles'])

@if ($announcements->count() > 0 || $articles->count() > 0)
    <div class="bg-gradient-to-r from-red-600 via-red-700 to-red-800 rounded-xl lg:rounded-2xl overflow-hidden mb-6 lg:mb-8 shadow-lg" x-data="breakingCarousel()" x-init="init()">
        <div class="p-3 sm:p-4 lg:p-6 flex items-center justify-between gap-2 sm:gap-3 lg:gap-4">
            <!-- Left: Header Section (Hidden on mobile) -->
            <div class="hidden sm:flex flex-shrink-0 pr-2 lg:pr-3 border-r border-red-700">
                <div class="flex flex-col justify-center w-32 lg:w-44 gap-2">
                    <!-- Breaking News Ribbon Badge -->
                    <svg class="w-full h-auto" viewBox="0 0 280 120" xmlns="http://www.w3.org/2000/svg">
                        <!-- Yellow "BREAKING" ribbon -->
                        <defs>
                            <filter id="shadow" x="-50%" y="-50%" width="200%" height="200%">
                                <feDropShadow dx="2" dy="2" stdDeviation="2" flood-opacity="0.3"/>
                            </filter>
                        </defs>

                        <!-- Yellow banner -->
                        <path d="M 10 15 L 180 10 L 175 35 L 5 40 Z" fill="#FFD500" filter="url(#shadow)" stroke="#E6B800" stroke-width="1"/>
                        <text x="95" y="32" font-family="Arial, sans-serif" font-size="18" font-weight="900" fill="#000" text-anchor="middle" letter-spacing="1">BREAKING</text>

                        <!-- Red "NEWS" banner -->
                        <path d="M 50 45 L 280 35 L 280 90 L 45 100 Z" fill="#E31C3D" filter="url(#shadow)" stroke="#C91630" stroke-width="1"/>
                        <path d="M 50 45 L 50 100 L 45 100 L 45 45 Z" fill="#B81529" opacity="0.8"/>

                        <!-- NEWS text -->
                        <text x="165" y="78" font-family="Arial, sans-serif" font-size="42" font-weight="900" fill="#FFFFFF" text-anchor="middle" letter-spacing="2">NEWS</text>
                    </svg>
                    <h3 class="text-white font-black text-xs lg:text-sm line-clamp-2 leading-tight">{{ $announcements->first()?->title ?? 'Breaking News' }}</h3>
                </div>
            </div>

            <!-- Center: Carousel Section -->
            <div class="flex-1 min-w-0 px-2 sm:px-3 lg:px-4">
                <div class="overflow-hidden">
                    <div class="flex gap-2 sm:gap-3 lg:gap-4 transition-transform duration-500" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
                        @php
                            $carouselItems = $articles->take(10);
                            $totalSlides = ceil($carouselItems->count() / 3);
                        @endphp
                        @for ($slideIdx = 0; $slideIdx < $totalSlides; $slideIdx++)
                            <div class="min-w-full flex gap-2 sm:gap-2.5 lg:gap-3">
                                @for ($i = 0; $i < 3; $i++)
                                    @php
                                        $article = $carouselItems->skip($slideIdx * 3 + $i)->first();
                                    @endphp
                                    @if ($article)
                                        <div class="flex-1 flex flex-col group">
                                            <!-- Image -->
                                            <div class="relative overflow-hidden rounded-md lg:rounded-lg mb-1.5 lg:mb-2 aspect-square sm:aspect-video shrink-0">
                                                @if ($article->featured_image)
                                                    @php
                                                        $imageUrl = str_starts_with($article->featured_image, 'http')
                                                            ? $article->featured_image
                                                            : asset('storage/' . $article->featured_image);
                                                    @endphp
                                                    <img src="{{ $imageUrl }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                                                @else
                                                    <img src="/images/default.jpg" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                @endif
                                            </div>

                                            <!-- Title -->
                                            <h4 class="text-white font-bold text-xs lg:text-sm line-clamp-2 lg:line-clamp-2 leading-tight">{{ $article->title }}</h4>
                                        </div>
                                    @else
                                        <div class="flex-1"></div>
                                    @endif
                                @endfor
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Dots -->
                <div class="flex justify-center gap-1.5 sm:gap-2 mt-2 lg:mt-3">
                    @for ($i = 0; $i < $totalSlides; $i++)
                        <button @click="currentSlide = {{ $i }}"
                            :class="currentSlide === {{ $i }} ? 'bg-white w-2.5' : 'bg-white/50 w-2'"
                            class="h-2 rounded-full transition" aria-label="Slide {{ $i + 1 }}">
                        </button>
                    @endfor
                </div>
            </div>

            <!-- Right: QR Code (Hidden on mobile and tablet, show on lg+) -->
            <div class="hidden lg:flex flex-col items-center justify-center shrink-0 pl-3 lg:pl-4 border-l border-red-700 min-w-max">
                <!-- QR Code -->
                <div class="text-center">
                    <div class="bg-white p-2 rounded-lg mb-1.5 inline-block">
                        <svg class="w-16 h-16" viewBox="0 0 24 24">
                            <rect x="2" y="2" width="6" height="6" fill="currentColor"/>
                            <rect x="9" y="2" width="1" height="1" fill="currentColor"/>
                            <rect x="12" y="2" width="1" height="1" fill="currentColor"/>
                            <rect x="14" y="2" width="6" height="6" fill="currentColor"/>
                            <rect x="2" y="9" width="1" height="1" fill="currentColor"/>
                            <rect x="6" y="9" width="1" height="1" fill="currentColor"/>
                            <rect x="9" y="5" width="1" height="1" fill="currentColor"/>
                            <rect x="14" y="9" width="1" height="1" fill="currentColor"/>
                            <rect x="18" y="9" width="1" height="1" fill="currentColor"/>
                            <rect x="2" y="14" width="6" height="6" fill="currentColor"/>
                            <rect x="9" y="14" width="1" height="1" fill="currentColor"/>
                            <rect x="12" y="14" width="1" height="1" fill="currentColor"/>
                            <rect x="14" y="14" width="6" height="6" fill="currentColor"/>
                        </svg>
                    </div>
                    <p class="text-white text-xs font-medium max-w-xs leading-tight">Ikuti berita terupdate di App KOMPAS.com. Scan & unduh sekarang</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function breakingCarousel() {
            return {
                currentSlide: 0,
                totalSlides: {{ $totalSlides }},
                autoplayInterval: null,
                init() {
                    this.startAutoplay();
                },
                startAutoplay() {
                    if (this.autoplayInterval) clearInterval(this.autoplayInterval);
                    this.autoplayInterval = setInterval(() => {
                        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                    }, 6000);
                },
                next() {
                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                    this.startAutoplay();
                },
                prev() {
                    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                    this.startAutoplay();
                }
            }
        }
    </script>
@endif
