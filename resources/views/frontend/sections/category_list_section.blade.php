<section class="py-8">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        @if($title ?? null)
            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $title }}</h2>
        @endif

        <div class="space-y-6">
            @forelse ($data['articles'] ?? [] as $article)
                <a href="{{ route('blog.show', $article->slug) }}" class="flex gap-4 group hover:opacity-80 transition-opacity">
                    <div class="w-40 h-32 overflow-hidden shrink-0 bg-gray-900 rounded-lg"
                        style="background-image: url('{{ $article->featured_image ? '/storage/' . $article->featured_image : '/images/placeholder.jpg' }}'); background-size: cover; background-position: center;">
                    </div>
                    <div class="flex-1">
                        <span class="inline-block px-3 py-1 bg-orange-100 text-orange-700 text-xs font-bold rounded mb-2">
                            {{ $article->category?->name ?? 'News' }}
                        </span>
                        <h3 class="text-sm font-bold text-gray-900 leading-snug line-clamp-2 group-hover:text-red-600 transition-colors">
                            {{ $article->title }}
                        </h3>
                        <p class="text-xs text-gray-600 mt-2 leading-relaxed line-clamp-2">
                            {{ $article->excerpt ?? 'Read more...' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-2">By {{ $article->user?->name ?? 'Admin' }} • {{ $article->published_at?->format('M d, Y') }}</p>
                    </div>
                </a>
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500">No articles available</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
