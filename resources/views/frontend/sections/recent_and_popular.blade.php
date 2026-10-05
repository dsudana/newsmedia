<section class="py-8">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-8">
            {{-- Left: Recent Posts --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 pb-3 border-b border-gray-200 dark:border-gray-700">Recent Posts</h2>

                {{-- Featured Grid (2) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    @foreach (($data['recent_articles'] ?? [])->take(2) as $article)
                        <a href="{{ route('blog.show', $article->slug) }}" class="group overflow-hidden">
                            <div class="aspect-[4/3] overflow-hidden mb-3 bg-gray-900 group-hover:opacity-90 transition-opacity"
                                style="background-image: url('{{ $article->featured_image ? asset('storage/' . $article->featured_image) : '/images/placeholder.jpg' }}'); background-size: cover; background-position: center;">
                            </div>
                            <div>
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-600 text-xs font-bold rounded">
                                    {{ $article->category?->name ?? 'News' }}
                                </span>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white leading-snug mt-2 line-clamp-2 group-hover:text-red-600 transition-colors">
                                    {{ $article->title }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">By {{ $article->user?->name ?? 'Admin' }} • {{ AppHelpersDateHelper::relativeTime($article->published_at) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Additional List (4) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach (($data['recent_articles'] ?? [])->skip(2)->take(4) as $article)
                        <a href="{{ route('blog.show', $article->slug) }}" class="group flex gap-3 pb-4 hover:opacity-80 transition-opacity">
                            <img src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : '/images/placeholder.jpg' }}"
                                 alt="{{ $article->title }}" class="w-20 h-16 object-cover rounded flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-600 dark:text-gray-400">By {{ $article->user?->name ?? 'Admin' }} • {{ AppHelpersDateHelper::relativeTime($article->published_at) }}</p>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-2 group-hover:text-red-600 transition-colors">
                                    {{ $article->title }}
                                </h4>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Right: Popular Posts --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 pb-3 border-b border-gray-200 dark:border-gray-700">Popular Posts</h2>

                <ol class="space-y-4">
                    @forelse (($data['popular_articles'] ?? [])->take(4) as $i => $article)
                        <li class="flex gap-3 pb-4">
                            <span class="w-6 h-6 rounded-full bg-red-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mt-1">
                                {{ $i + 1 }}
                            </span>
                            <div class="flex-1">
                                <a href="{{ route('blog.show', $article->slug) }}" class="group">
                                    <span class="inline-block px-2 py-1 bg-red-100 text-red-600 text-xs font-bold rounded">
                                        {{ $article->category?->name ?? 'News' }}
                                    </span>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white leading-snug hover:text-red-600 transition-colors line-clamp-2 mt-1">
                                        {{ $article->title }}
                                    </p>
                                </a>
                            </div>
                        </li>
                    @empty
                        <li class="text-gray-500 dark:text-gray-400 text-sm">No popular articles</li>
                    @endforelse
                </ol>
            </div>
        </div>
    </div>
</section>
