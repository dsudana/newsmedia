{{-- Blog Preview Section - Modern Design --}}
<section class="py-16 px-4 sm:px-6 lg:px-8 bg-white">
    <div class="max-w-6xl mx-auto">
        {{-- Section Header --}}
        @if ($section->title)
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-1 w-16 bg-red-600 rounded"></div>
                    <span class="text-sm font-bold text-red-600 uppercase tracking-widest">Blog</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ $section->title }}</h2>
                @if ($section->description)
                    <p class="text-lg text-gray-600 max-w-2xl">{{ $section->description }}</p>
                @endif
            </div>
        @endif

        {{-- Blog Articles Grid --}}
        @php
            $columns = $section->config['columns'] ?? 3;
            $gridClass = match ($columns) {
                1 => 'grid-cols-1',
                2 => 'grid-cols-1 md:grid-cols-2',
                3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
                4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
                default => 'grid-cols-1 md:grid-cols-3',
            };
        @endphp

        <div class="grid {{ $gridClass }} gap-8">
            @forelse($data['articles'] ?? [] as $article)
                <article
                    class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100 flex flex-col">
                    {{-- Featured Image --}}
                    @if (in_array('image', $section->config['fields'] ?? []))
                        <div class="h-56 w-full overflow-hidden bg-gray-200 relative">
                            @if ($article->featured_image)
                                <img src="/storage/{{ $article->featured_image }}" alt="{{ $article->title }}"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-red-100 to-red-50 flex items-center justify-center">
                                    <i class="fas fa-image text-red-300 text-4xl"></i>
                                </div>
                            @endif

                            {{-- Category Badge --}}
                            @if ($article->category)
                                <div class="absolute top-4 left-4">
                                    <span
                                        class="inline-block px-3 py-1 bg-red-600 text-white text-xs font-bold uppercase tracking-widest rounded">
                                        {{ $article->category->name }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Content --}}
                    <div class="p-6 flex flex-col flex-grow">
                        {{-- Title --}}
                        @if (in_array('title', $section->config['fields'] ?? []))
                            <h3 class="text-xl font-bold mb-3 leading-tight line-clamp-2">
                                <a href="{{ route('articles.show', $article) }}"
                                    class="text-gray-900 group-hover:text-red-600 transition-colors">
                                    {{ $article->title }}
                                </a>
                            </h3>
                        @endif

                        {{-- Excerpt --}}
                        @if (in_array('excerpt', $section->config['fields'] ?? []))
                            <p class="text-gray-600 text-sm line-clamp-2 mb-4 flex-grow">
                                {{ $article->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($article->content), 100) }}
                            </p>
                        @endif

                        {{-- Meta Information --}}
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="flex items-center gap-3">
                                {{-- Author Avatar --}}
                                @if (in_array('author', $section->config['fields'] ?? []) && $article->user)
                                    <div
                                        class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($article->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-900">{{ $article->user->name }}</p>
                                        @if (in_array('date', $section->config['fields'] ?? []))
                                            <p class="text-xs text-gray-500">
                                                {{ $article->published_at ? $article->published_at->format('M d') : 'Draft' }}
                                            </p>
                                        @endif
                                    </div>
                                @elseif(in_array('date', $section->config['fields'] ?? []))
                                    <p class="text-xs text-gray-600">
                                        <i class="fas fa-calendar text-red-600 mr-2"></i>
                                        {{ $article->published_at ? $article->published_at->format('M d, Y') : 'Draft' }}
                                    </p>
                                @endif
                            </div>

                            {{-- Read More Arrow --}}
                            <a href="{{ route('articles.show', $article) }}"
                                class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-red-100 text-red-600 group-hover:bg-red-600 group-hover:text-white transition-all">
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-16">
                        <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">No blog articles available.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
