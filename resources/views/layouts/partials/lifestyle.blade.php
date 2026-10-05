<section class="border-rn-line">
    <h2 class="section-heading">Lifestyle</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach (($lifestylePosts ?? []) as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group block overflow-hidden">
                <div class="aspect-video overflow-hidden mb-3 bg-gray-900 group-hover:opacity-90 transition-opacity"
                    style="background-image: url('{{ $post->featured_image ? '/storage/' . $post->featured_image : '/images/placeholder.jpg' }}'); background-size: cover; background-position: center;">
                </div>
                <div>
                    <p class="byline">By {{ $post->user?->name ?? 'Admin' }} <span class="date">{{ $post->published_at?->format('M d, Y') }}</span></p>
                    <h3 class="text-sm font-bold text-rn-ink leading-snug mt-2 line-clamp-2 group-hover:text-rn-red transition-colors">
                        {{ $post->title }}
                    </h3>
                </div>
            </a>
        @endforeach
    </div>
</section>
