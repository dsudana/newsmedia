@props(['announcements', 'articles'])

@if ($announcements->count() > 0 || $articles->count() > 0)
    <div class="bg-gradient-to-r from-red-900 to-red-800 rounded-2xl overflow-hidden mb-8">
        <div class="p-8 flex flex-col lg:flex-row items-center gap-8">
            <!-- Left: Carousel -->
            <div class="flex-1 min-w-0" x-data="breakingCarousel()" x-init="init()">
                <!-- Header -->
                <div class="mb-6 flex items-center gap-3">
                    <div class="bg-white px-3 py-1 rounded-full">
                        <span class="text-red-900 font-black text-sm">BREAKING</span>
                    </div>
                    <div class="bg-blue-400 px-3 py-1 rounded-full">
                        <span class="text-white font-bold text-sm">NEWS</span>
                    </div>
                    <h3 class="text-white font-black text-xl lg:text-2xl ml-4">{{ $announcements->first()?->title ?? 'Breaking News' }}</h3>
                </div>

                <!-- Carousel -->
                <div class="relative">
                    <div class="overflow-hidden">
                        <div class="flex gap-4 transition-transform duration-500" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
                            @php
                                $carouselItems = $articles->take(5);
                            @endphp
                            @foreach ($carouselItems as $article)
                                <div class="min-w-full flex items-center gap-4">
                                    <!-- Image -->
                                    <div class="w-48 h-32 flex-shrink-0 rounded-lg overflow-hidden">
                                        @if ($article->featured_image)
                                            @php
                                                $imageUrl = str_starts_with($article->featured_image, 'http')
                                                    ? $article->featured_image
                                                    : asset('storage/' . $article->featured_image);
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="/images/default.jpg" alt="{{ $article->title }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>

                                    <!-- Title & Meta -->
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-white font-bold text-lg line-clamp-3 mb-2">{{ $article->title }}</h4>
                                        <p class="text-red-100 text-sm">{{ $article->published_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Navigation Arrows -->
                    <button @click="prev()" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-16 lg:translate-x-0 bg-white/20 hover:bg-white/40 text-white p-2 rounded-full transition" aria-label="Previous">
                        <i class="fas fa-chevron-left text-xl"></i>
                    </button>
                    <button @click="next()" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-16 lg:translate-x-0 bg-white/20 hover:bg-white/40 text-white p-2 rounded-full transition" aria-label="Next">
                        <i class="fas fa-chevron-right text-xl"></i>
                    </button>

                    <!-- Dots -->
                    <div class="flex justify-center gap-2 mt-4">
                        @for ($i = 0; $i < $carouselItems->count(); $i++)
                            <button @click="currentSlide = {{ $i }}"
                                :class="currentSlide === {{ $i }} ? 'bg-white' : 'bg-white/50'"
                                class="w-2 h-2 rounded-full transition" aria-label="Slide {{ $i + 1 }}">
                            </button>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Right: QR Code -->
            <div class="flex-shrink-0 text-center">
                <div class="bg-white p-4 rounded-lg mb-4 inline-block">
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
                <p class="text-white text-sm font-medium">Ikuti berita terupdate di<br>App NEWSMEDIA.<br><span class="font-bold">Scan & unduh sekarang</span></p>
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
                    }, 5000);
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
