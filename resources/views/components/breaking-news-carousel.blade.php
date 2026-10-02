@props(['announcements', 'articles'])

@if ($announcements->count() > 0 || $articles->count() > 0)
    <div class="bg-gradient-to-r from-red-900 to-red-800 rounded-2xl overflow-hidden mb-8">
        <div class="p-4 lg:p-8" x-data="breakingCarousel()" x-init="init()">
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

            <!-- 3 Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-center">
                <!-- Column 1: Left Image -->
                <div class="hidden lg:block relative overflow-hidden rounded-lg aspect-square">
                    @php
                        $leftArticle = $articles->get(0);
                    @endphp
                    @if ($leftArticle)
                        @php
                            $imageUrl = str_starts_with($leftArticle->featured_image, 'http')
                                ? $leftArticle->featured_image
                                : asset('storage/' . $leftArticle->featured_image);
                        @endphp
                        <img src="{{ $imageUrl }}" alt="{{ $leftArticle->title }}" class="w-full h-full object-cover">
                    @else
                        <img src="/images/default.jpg" alt="Breaking News" class="w-full h-full object-cover">
                    @endif
                </div>

                <!-- Column 2: Center Carousel -->
                <div class="relative">
                    @php
                        $carouselItems = $articles->take(8);
                    @endphp

                    <div class="overflow-hidden rounded-lg">
                        <div class="flex transition-transform duration-500" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
                            @foreach ($carouselItems as $article)
                                <div class="min-w-full">
                                    <!-- Image -->
                                    <div class="relative overflow-hidden rounded-lg aspect-square mb-4">
                                        @php
                                            $imageUrl = str_starts_with($article->featured_image, 'http')
                                                ? $article->featured_image
                                                : asset('storage/' . $article->featured_image);
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="{{ $article->title }}" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                    </div>

                                    <!-- Title -->
                                    <h4 class="text-white font-bold text-base lg:text-lg line-clamp-4 leading-tight">{{ $article->title }}</h4>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Navigation Arrows (Inside Carousel) -->
                    <button @click="prev()" class="absolute left-2 top-1/3 -translate-y-1/2 bg-white/30 hover:bg-white/50 text-white w-10 h-10 rounded-full flex items-center justify-center transition z-10" aria-label="Previous">
                        <i class="fas fa-chevron-left text-lg"></i>
                    </button>
                    <button @click="next()" class="absolute right-2 top-1/3 -translate-y-1/2 bg-white/30 hover:bg-white/50 text-white w-10 h-10 rounded-full flex items-center justify-center transition z-10" aria-label="Next">
                        <i class="fas fa-chevron-right text-lg"></i>
                    </button>

                    <!-- Dots -->
                    <div class="flex justify-center gap-2 mt-4">
                        @for ($i = 0; $i < $carouselItems->count(); $i++)
                            <button @click="currentSlide = {{ $i }}"
                                :class="currentSlide === {{ $i }} ? 'bg-white w-3' : 'bg-white/50 w-2'"
                                class="h-2 rounded-full transition" aria-label="Slide {{ $i + 1 }}">
                            </button>
                        @endfor
                    </div>
                </div>

                <!-- Column 3: Right Image + QR Code -->
                <div class="flex flex-col gap-4">
                    <!-- Right Image -->
                    <div class="hidden lg:block relative overflow-hidden rounded-lg aspect-square">
                        @php
                            $rightArticle = $articles->get(1);
                        @endphp
                        @if ($rightArticle)
                            @php
                                $imageUrl = str_starts_with($rightArticle->featured_image, 'http')
                                    ? $rightArticle->featured_image
                                    : asset('storage/' . $rightArticle->featured_image);
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $rightArticle->title }}" class="w-full h-full object-cover">
                        @else
                            <img src="/images/default.jpg" alt="Breaking News" class="w-full h-full object-cover">
                        @endif
                    </div>

                    <!-- QR Code (Mobile: below carousel, Desktop: below right image) -->
                    <div class="text-center lg:hidden py-4">
                        <div class="bg-white p-3 rounded-lg mb-3 inline-block">
                            <svg class="w-20 h-20" viewBox="0 0 24 24">
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
                        <p class="text-white text-xs font-medium">Ikuti berita terupdate di App NEWSMEDIA. Scan & unduh sekarang</p>
                    </div>

                    <!-- QR Code (Desktop only) -->
                    <div class="hidden lg:block text-center">
                        <div class="bg-white p-4 rounded-lg mb-3 inline-block">
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
                        <p class="text-white text-xs font-medium">Ikuti berita terupdate di<br>App NEWSMEDIA.<br><span class="font-bold">Scan & unduh sekarang</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function breakingCarousel() {
            return {
                currentSlide: 0,
                totalSlides: {{ $carouselItems->count() }},
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
