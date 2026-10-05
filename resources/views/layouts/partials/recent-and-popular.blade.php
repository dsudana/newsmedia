<section>
    <div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-8">
        {{-- Recent Posts Section --}}
        <div>
            <h2 class="section-heading">Recent Post</h2>

            {{-- Featured Articles (2 grid) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                @foreach (($recentFeatured ?? [])->take(2) as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="group overflow-hidden">
                        <div class="aspect-[4/3] overflow-hidden mb-3 relative bg-gray-900 group-hover:opacity-90 transition-opacity"
                            style="background-image: url('{{ $post->featured_image ? '/storage/' . $post->featured_image : '/images/placeholder.jpg' }}'); background-size: cover; background-position: center;">
                        </div>
                        <div>
                            <x-frontend.tag-pill :label="$post->category?->name ?? 'Uncategorized'" />
                            <h3
                                class="text-sm font-bold text-rn-ink leading-snug mt-2 line-clamp-2 group-hover:text-rn-red transition-colors">
                                {{ $post->title }}
                            </h3>
                            <p class="byline mt-2">By {{ $post->user?->name ?? 'Admin' }} <span
                                    class="date">{{ $post->published_at?->translatedFormat('d M Y') }}</span></p>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Additional Articles List (2 columns) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach (($recentFeatured ?? [])->skip(2)->take(4) as $post)
                    <a href="{{ route('blog.show', $post->slug) }}"
                        class="group flex gap-3 pb-4 hover:opacity-80 transition-opacity">
                        <img src="{{ $post->featured_image ? '/storage/' . $post->featured_image : '/images/placeholder.jpg' }}" alt="{{ $post->title }}"
                            class="w-20 h-16 object-cover flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <p class="byline">By {{ $post->user?->name ?? 'Admin' }} <span
                                    class="date">{{ $post->published_at?->translatedFormat('d M Y') }}</span></p>
                            <h4
                                class="text-sm font-bold text-rn-ink line-clamp-2 group-hover:text-rn-red transition-colors">
                                {{ $post->title }}
                            </h4>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Popular Posts Section --}}
        <div>
            <h2 class="section-heading">Popular Post</h2>

            <ol class="space-y-4">
                @foreach (($popularPosts ?? [])->take(4) as $i => $post)
                    <li class="flex gap-3 pb-4">
                        <span
                            class="w-6 h-6 rounded-full bg-rn-red text-white text-[12px] font-bold flex items-center justify-center shrink-0 mt-1">
                            {{ $i + 1 }}
                        </span>
                        <div class="flex-1">
                            <a href="{{ route('blog.show', $post->slug) }}" class="group">
                                <x-frontend.tag-pill :label="$post->category?->name ?? 'Uncategorized'" />
                                <p
                                    class="text-[13px] font-bold text-rn-ink leading-snug hover:text-rn-red transition-colors line-clamp-2 mt-1">
                                    {{ $post->title }}
                                </p>
                            </a>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>
