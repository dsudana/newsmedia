<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($title ?? null)
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-900">{{ $title }}</h2>
                <a href="#" class="text-red-600 hover:text-red-700 transition">
                    <i class="fas fa-chevron-right text-2xl"></i>
                </a>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($data['articles'] ?? [] as $article)
                <a href="{{ route('blog.show', $article->slug) }}" class="group flex flex-col h-full">
                    <!-- Image Container 16:9 -->
                    <div class="relative overflow-hidden rounded-lg mb-4 aspect-video bg-gray-200">
                        <img src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : '/images/placeholder.jpg' }}"
                             alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <!-- Category Badge Overlay -->
                        <div class="absolute top-3 left-3">
                            <span class="inline-block px-3 py-1.5 bg-red-600 text-white text-xs font-bold uppercase rounded-sm">
                                {{ $article->category?->name ?? 'News' }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 flex flex-col">
                        <h3 class="text-base font-bold text-gray-900 leading-tight line-clamp-3 group-hover:text-red-600 transition-colors mb-3">
                            {{ $article->title }}
                        </h3>
                        <p class="text-sm text-gray-600 mt-auto">
                            {{ $article->published_at?->format('d M Y') }}
                        </p>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">No articles available</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
