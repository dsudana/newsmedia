<aside>
    {{-- Latest Post --}}
    <x-section-heading title="Latest Post" />

    @if (!empty($latestFeatured))
        <div class="mb-8">
            <a href="{{ route('blog.show', $latestFeatured->slug) }}" class="block aspect-video overflow-hidden mb-4">
                <img src="{{ $latestFeatured->featured_image ? '/storage/' . $latestFeatured->featured_image : '/images/placeholder.jpg' }}" alt="{{ $latestFeatured->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
            </a>
            <x-tag-pill :label="$latestFeatured->category?->name ?? 'Uncategorized'" />
            <p class="byline mt-3">By {{ $latestFeatured->user?->name ?? 'Admin' }} <span class="date">{{ $latestFeatured->published_at?->format('M d, Y') }}</span></p>
            <h3 class="text-base font-bold text-rn-ink leading-tight mt-2">{{ $latestFeatured->title }}</h3>
            <p class="text-sm text-rn-body mt-3 leading-relaxed line-clamp-3">{{ $latestFeatured->excerpt }}</p>
            <a href="{{ route('blog.show', $latestFeatured->slug) }}"
               class="inline-block mt-4 border-2 border-rn-red text-rn-red text-xs font-bold uppercase px-5 py-2 hover:bg-rn-red hover:text-white transition-colors">
                Read More
            </a>
        </div>

        <div class="space-y-4">
            @foreach (($latestSidebarPosts ?? [])->take(2) as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="group flex gap-3 hover:opacity-80 transition-opacity">
                    <img src="{{ $post->featured_image ? '/storage/' . $post->featured_image : '/images/placeholder.jpg' }}" alt="{{ $post->title }}"
                        class="w-16 h-12 object-cover flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="byline text-xs">By {{ $post->user?->name ?? 'Admin' }} <span class="date">{{ $post->published_at?->format('M d, Y') }}</span></p>
                        <h4 class="text-xs font-bold text-rn-ink line-clamp-2 group-hover:text-rn-red transition-colors">
                            {{ $post->title }}
                        </h4>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    {{-- Stay Connected --}}
    <section id="section-stay-connected" class="mt-8">
        <x-section-heading title="Stay Connected" />
        <div class="space-y-3">
            @foreach ([
                ['icon' => 'fa-facebook-f', 'bg' => 'bg-rn-fb',   'count' => '19,243 Fans',     'cta' => 'Like'],
                ['icon' => 'fa-twitter',    'bg' => 'bg-rn-tw',   'count' => '2,076 Followers', 'cta' => 'Follow'],
                ['icon' => 'fa-rss',        'bg' => 'bg-rn-red',  'count' => '15,200 Followers','cta' => 'Subscribe'],
            ] as $social)
                <x-social-box :icon="$social['icon']" :bgClass="$social['bg']" :count="$social['count']" :cta="$social['cta']" />
            @endforeach
        </div>
    </section>

    {{-- Tags --}}
    <x-section-heading title="Tags" style="margin-top: 2.5rem;" />
    <x-tag-cloud :tags="$tags ?? []" />

    {{-- Advertise --}}
    <x-section-heading title="Advertise" style="margin-top: 2.5rem;" />
    <a href="#" class="block">
        <img src="{{ $adBanner ?? asset('images/ad-banner.svg') }}" alt="Advertisement" class="w-full h-auto">
    </a>

    {{-- Newsletter --}}
    <div style="margin-top: 2.5rem;">
        <x-newsletter-form
            title="Newsletter"
            description="The most important world news and events of the day."
            subdescription="Get our daily newsletter on your inbox."
        />
    </div>
</aside>
