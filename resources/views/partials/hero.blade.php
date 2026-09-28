<section id="hero-section">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
        {{-- Main featured article carousel (2/3 width) --}}
        <div class="lg:col-span-2">
            <div class="carousel-hero-main swiper">
                <div class="swiper-wrapper">
                    @forelse (($heroSlides ?? []) as $mainArticle)
                        <div class="swiper-slide">
                            <a href="{{ route('blog.show', $mainArticle->slug) }}" class="block group">
                                @php
                                    $backgroundStyle = $mainArticle->featured_image
                                        ? "background-image: url('" .
                                            addslashes(e('/storage/' . $mainArticle->featured_image)) .
                                            "'); background-size: cover; background-position: center;"
                                        : "background-image: url('/images/placeholder.jpg'); background-size: cover; background-position: center;";
                                @endphp
                                <div class="relative h-[400px] overflow-hidden bg-gray-900 group-hover:opacity-90 transition-opacity duration-300"
                                    style="{{ $backgroundStyle }}">
                                    {{-- Dark overlay --}}
                                    <div
                                        class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors duration-300">
                                    </div>

                                    {{-- Content overlay --}}
                                    <div class="absolute inset-0 flex flex-col justify-end p-6">
                                        {{-- Category Badge --}}
                                        <div class="mb-4">
                                            <span
                                                class="inline-block bg-rn-red text-white px-3 py-1 text-xs font-bold uppercase">
                                                {{ $mainArticle->category->name }}
                                            </span>
                                        </div>

                                        {{-- Title --}}
                                        <h2
                                            class="text-3xl lg:text-4xl font-bold text-white mb-3 leading-tight line-clamp-3">
                                            {{ $mainArticle->title }}
                                        </h2>

                                        {{-- Meta info --}}
                                        <div class="flex items-center gap-4 text-gray-200 text-sm">
                                            <span class="font-semibold">By
                                                {{ $mainArticle->author->name ?? 'David Hall' }}</span>
                                            <span>{{ $mainArticle->published_at->format('F d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div class="relative h-[400px] overflow-hidden bg-gray-900">
                                <div class="absolute inset-0 bg-black/40"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <p class="text-white text-lg">No articles available</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Side articles (1/3 width) - 2 static cards (1 top, 1 bottom) --}}
        <div class="lg:col-span-1">
            <div class="flex flex-col h-[400px] gap-0">
                @foreach ($sideCards ?? [] as $index => $card)
                    <a href="{{ route('blog.show', $card->slug) }}" class="group block flex-1">
                        @php
                            $cardBackgroundStyle = $card->featured_image
                                ? "background-image: url('" .
                                    addslashes(e('/storage/' . $card->featured_image)) .
                                    "'); background-size: cover; background-position: center;"
                                : "background-image: url('/images/placeholder.jpg'); background-size: cover; background-position: center;";
                        @endphp
                        <div class="relative h-full overflow-hidden bg-gray-900 group-hover:opacity-90 transition-opacity duration-300"
                            style="{{ $cardBackgroundStyle }}">
                            {{-- Dark overlay --}}
                            <div
                                class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors duration-300">
                            </div>

                            {{-- Content overlay --}}
                            <div class="absolute inset-0 flex flex-col justify-end p-4">
                                {{-- Category Badge --}}
                                <div class="mb-3">
                                    <span
                                        class="inline-block bg-rn-red text-white px-2 py-1 text-xs font-bold uppercase">
                                        {{ $card->category->name }}
                                    </span>
                                </div>

                                {{-- Title --}}
                                <h3 class="text-sm font-bold text-white line-clamp-2">
                                    {{ $card->title }}
                                </h3>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
