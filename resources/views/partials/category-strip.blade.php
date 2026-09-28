<section>
    <div class="carousel-category-strip swiper">
        <div class="swiper-wrapper">
            @foreach ($categoryStrip ?? [] as $post)
                <div class="swiper-slide">
                    <a href="{{ route('blog.show', $post->slug) }}" class="block group">
                        <div class="overflow-hidden aspect-[4/3] mb-2">
                            <img src="{{ $post->featured_image ? '/storage/' . $post->featured_image : '/images/placeholder.jpg' }}" alt="{{ $post->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <p class="byline">By {{ $post->user?->name ?? 'Admin' }}, <span
                                class="date">{{ $post->published_at?->format('M d, Y') }}</span></p>
                        <p class="card-title group-hover:text-rn-red transition-colors">{{ $post->title }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
