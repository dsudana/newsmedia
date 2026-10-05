<section class="py-4 border-rn-line">
    <h2 class="section-heading">Technology</h2>

    <div class="space-y-6">
        @foreach ($technologyPosts ?? [] as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="flex gap-4 group hover:opacity-80 transition-opacity">
                <div class="w-40 h-32 overflow-hidden shrink-0 bg-gray-900"
                    style="background-image: url('{{ $post->featured_image ? '/storage/' . $post->featured_image : '/images/placeholder.jpg' }}'); background-size: cover; background-position: center;">
                </div>
                <div class="flex-1">
                    <x-frontend.tag-pill :label="$post->category?->name ?? 'Technology'" />
                    <h3
                        class="text-sm font-bold text-rn-ink leading-snug mt-2 line-clamp-2 group-hover:text-rn-red transition-colors">
                        {{ $post->title }}
                    </h3>
                    <p class="text-xs text-rn-body mt-2 leading-relaxed line-clamp-2">
                        {{ $post->excerpt ?? 'Read more...' }}</p>
                    <p class="byline mt-2">By {{ $post->user?->name ?? 'Admin' }} <span
                            class="date">{{ $post->published_at?->translatedFormat('d M Y') }}</span></p>
                </div>
            </a>
        @endforeach
    </div>
</section>
