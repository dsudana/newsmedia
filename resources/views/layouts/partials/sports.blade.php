<section class="py-4">
    <h2 class="section-heading">Sports</h2>

    <div class="carousel-sports swiper">
        <div class="swiper-wrapper">
            @foreach (($sportsPosts ?? []) as $post)
                <div class="swiper-slide">
                    <a href="{{ route('blog.show', $post->slug) }}" class="group block overflow-hidden">
                        <div class="aspect-[3/2] overflow-hidden mb-3 bg-gray-900 group-hover:opacity-90 transition-opacity"
                            style="background-image: url('{{ $post->featured_image ? '/storage/' . $post->featured_image : '/images/placeholder.jpg' }}'); background-size: cover; background-position: center;">
                        </div>
                        <div>
                            <x-frontend.tag-pill :label="$post->category?->name ?? 'Sports'" />
                            <h3 class="text-sm font-bold text-rn-ink leading-snug mt-2 line-clamp-2 group-hover:text-rn-red transition-colors">
                                {{ $post->title }}
                            </h3>
                            <p class="byline mt-2">By {{ $post->user?->name ?? 'Admin' }} <span class="date">{{ $post->published_at?->format('M d, Y') }}</span></p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
