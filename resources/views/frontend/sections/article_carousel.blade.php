{{-- Article Carousel Section - Modern Design --}}
<section class="py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-6xl mx-auto">
        {{-- Section Header --}}
        @if ($section->title)
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-1 w-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded"></div>
                    <span class="text-sm font-bold text-indigo-600 uppercase tracking-widest">Featured</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ $section->title }}</h2>
                @if ($section->description)
                    <p class="text-lg text-gray-600 max-w-2xl">{{ $section->description }}</p>
                @endif
            </div>
        @endif

        {{-- Articles Grid --}}
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
                    class="group bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                    {{-- Featured Image --}}
                    @if (in_array('image', $section->config['show_fields'] ?? []))
                        <div class="h-56 w-full overflow-hidden bg-gradient-to-br from-gray-200 to-gray-300 relative">
                            @if ($article->featured_image)
                                <img src="/storage/{{ $article->featured_image }}" alt="{{ $article->title }}"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="fas fa-image text-gray-400 text-4xl mb-2"></i>
                                        <p class="text-gray-400 text-sm">No image</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Category Badge --}}
                            @if (in_array('category', $section->config['show_fields'] ?? []) && $article->category)
                                <div class="absolute top-4 right-4">
                                    <span
                                        class="inline-block px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-xs font-bold uppercase tracking-widest rounded-full shadow-lg">
                                        {{ $article->category->name }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Content --}}
                    <div class="p-6">
                        {{-- Title --}}
                        @if (in_array('title', $section->config['show_fields'] ?? []))
                            <h3 class="text-xl font-bold mb-3 leading-tight line-clamp-2">
                                <a href="{{ route('articles.show', $article) }}"
                                    class="text-gray-900 group-hover:text-indigo-600 transition-colors">
                                    {{ $article->title }}
                                </a>
                            </h3>
                        @endif

                        {{-- Excerpt --}}
                        @if (in_array('excerpt', $section->config['show_fields'] ?? []))
                            <p class="text-gray-600 text-sm line-clamp-2 mb-4">
                                {{ $article->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($article->content), 120) }}
                            </p>
                        @endif

                        {{-- Meta Info --}}
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-3">
                                {{-- Author Avatar --}}
                                @if (in_array('author', $section->config['show_fields'] ?? []) && $article->user)
                                    <div
                                        class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold">
                                        {{ strtoupper(substr($article->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-900">{{ $article->user->name }}</p>
                                        @if (in_array('date', $section->config['show_fields'] ?? []))
                                            <p class="text-xs text-gray-500">
                                                {{ $article->published_at ? $article->published_at->format('M d') : 'Draft' }}
                                            </p>
                                        @endif
                                    </div>
                                @elseif(in_array('date', $section->config['show_fields'] ?? []))
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $article->published_at ? $article->published_at->format('M d, Y') : 'Draft' }}
                                    </p>
                                @endif
                            </div>

                            {{-- Read More Arrow --}}
                            <a href="{{ route('articles.show', $article) }}"
                                class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-16">
                        <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">No articles available.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
