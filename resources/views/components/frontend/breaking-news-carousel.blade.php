@props(['announcements', 'articles'])

@if ($announcements->count() > 0 || $articles->count() > 0)
    <div class="bg-gradient-to-r from-red-600 via-red-700 to-red-800 rounded-xl lg:rounded-2xl overflow-hidden mb-6 lg:mb-8 shadow-lg" x-data="breakingCarousel()" x-init="init()">
        <div class="p-3 sm:p-4 lg:p-6 flex items-center justify-between gap-2 sm:gap-3 lg:gap-4">
            <!-- Left: Header Section (Hidden on mobile) -->
            <div class="hidden sm:flex flex-shrink-0 pr-2 lg:pr-3 border-r border-red-700 min-w-max">
                <div class="flex flex-col justify-center">
                    <div class="flex items-center gap-1 mb-1.5 lg:mb-2">
                        <div class="bg-white px-1 py-0.5 rounded-full">
                            <span class="text-red-900 font-black text-xs">● BREAKING</span>
                        </div>
                        <div class="bg-blue-400 px-1 py-0.5 rounded-full">
                            <span class="text-white font-bold text-xs">NEWS</span>
                        </div>
                    </div>
                    <h3 class="text-white font-black text-xs lg:text-sm max-w-32 lg:max-w-44 line-clamp-2 leading-tight">{{ $announcements->first()?->title ?? 'Breaking News' }}</h3>
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
