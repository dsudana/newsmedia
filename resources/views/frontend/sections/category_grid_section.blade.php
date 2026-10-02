<section class="py-8">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        @if($title ?? null)
            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $title }}</h2>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($data['articles'] ?? [] as $article)
                <a href="{{ route('blog.show', $article->slug) }}" class="group block overflow-hidden">
                    <div class="aspect-video overflow-hidden mb-3 bg-gray-900 group-hover:opacity-90 transition-opacity">
                        <img src="{{ $article->featured_image ? featuredImageUrl($article->featured_image) : '/images/placeholder.jpg' }}"
                             alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div>
                        <span class="inline-block px-2 py-1 bg-teal-100 text-teal-700 text-xs font-bold rounded mb-2">
                            {{ $article->category?->name ?? 'News' }}
                        </span>
                        <h3 class="text-sm font-bold text-gray-900 leading-snug line-clamp-2 group-hover:text-red-600 transition-colors">
                            {{ $article->title }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-2">By {{ $article->user?->name ?? 'Admin' }} • {{ $article->published_at?->format('M d, Y') }}</p>
                    </div>
                </a>
            @empty
                <div class="col-span-2 text-center py-12">
                    <p class="text-gray-500">No articles available</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
