@props(['announcements', 'articles'])

@if ($announcements->count() > 0 || $articles->count() > 0)
    <div class="bg-gradient-to-r from-red-900 to-red-800 rounded-2xl overflow-hidden mb-8">
        <div class="p-6 lg:p-8 flex flex-col lg:flex-row items-start lg:items-center gap-6 lg:gap-8">
            <!-- Left: Carousel Section -->
            <div class="flex-1 min-w-0" x-data="breakingCarousel()" x-init="init()">
                <!-- Header -->
                <div class="mb-6 flex items-center gap-3">
                    <div class="bg-white px-3 py-1 rounded-full">
                        <span class="text-red-900 font-black text-sm">● BREAKING</span>
                    </div>
                    <div class="bg-blue-400 px-3 py-1 rounded-full">
                        <span class="text-white font-bold text-sm">NEWS</span>
                    </div>
                    <h3 class="text-white font-black text-lg lg:text-xl ml-2">{{ $announcements->first()?->title ?? 'Breaking News' }}</h3>
                </div>

                <!-- Carousel Container -->
                <div class="relative">
                    <div class="overflow-hidden">
                        <div class="flex gap-4 transition-transform duration-500" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
                            @php
                                $carouselItems = $articles->take(10);
                                $totalSlides = ceil($carouselItems->count() / 3);
                            @endphp
                            @for ($slideIdx = 0; $slideIdx < $totalSlides; $slideIdx++)
                                <div class="min-w-full flex gap-4">
                                    @for ($i = 0; $i < 3; $i++)
                                        @php
                                            $article = $carouselItems->skip($slideIdx * 3 + $i)->first();
                                        @endphp
                                        @if ($article)
                                            <div class="flex-1 flex flex-col group cursor-pointer">
                                                <!-- Image -->
                                                <div class="relative overflow-hidden rounded-lg mb-3 aspect-video flex-shrink-0">
                                                    @if ($article->featured_image)
                                                        @php
                                                            $imageUrl = str_starts_with($article->featured_image, 'http')
                                                                ? $article->featured_image
                                                                : asset('storage/' . $article->featured_image);
                                                        @endphp
                                                        <img src="{{ $imageUrl }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                    @else
                                                        <img src="/images/default.jpg" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                    @endif
                                                </div>

                                                <!-- Title -->
                                                <h4 class="text-white font-bold text-sm line-clamp-3 leading-tight">{{ $article->title }}</h4>
                                            </div>
                                        @else
                                            <div class="flex-1"></div>
                                        @endif
                                    @endfor
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Navigation Arrows -->
                    <button @click="prev()" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-12 lg:translate-x-0 bg-white/20 hover:bg-white/40 text-white w-10 h-10 rounded-full flex items-center justify-center transition" aria-label="Previous">
                        <i class="fas fa-chevron-left text-lg"></i>
                    </button>
                    <button @click="next()" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-12 lg:translate-x-0 bg-white/20 hover:bg-white/40 text-white w-10 h-10 rounded-full flex items-center justify-center transition" aria-label="Next">
                        <i class="fas fa-chevron-right text-lg"></i>
                    </button>

                    <!-- Dots -->
                    <div class="flex justify-center gap-2 mt-6">
                        @for ($i = 0; $i < $totalSlides; $i++)
                            <button @click="currentSlide = {{ $i }}"
                                :class="currentSlide === {{ $i }} ? 'bg-white w-3' : 'bg-white/50 w-2'"
                                class="h-2 rounded-full transition" aria-label="Slide {{ $i + 1 }}">
                            </button>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Right: QR Code -->
            <div class="flex-shrink-0 text-center w-full lg:w-auto">
                <div class="bg-white p-4 rounded-lg mb-3 inline-block mx-auto lg:mx-0">
                    <svg class="w-24 h-24" viewBox="0 0 24 24">
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
                <p class="text-white text-xs lg:text-sm font-medium">Ikuti berita terupdate di<br>App NEWSMEDIA.<br><span class="font-bold">Scan & unduh sekarang</span></p>
            </div>
        </div>
    </div>

    <script>
        function breakingCarousel() {
            return {
                currentSlide: 0,
                totalSlides: {{ $totalSlides }},
                init() {
                    setInterval(() => {
                        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                    }, 6000);
                },
                next() {
                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                },
                prev() {
                    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                }
            }
        }
    </script>
@endif
