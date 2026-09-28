{{-- Category Highlight Section - Modern Design --}}
<section class="py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        {{-- Section Header --}}
        @if ($section->title)
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-1 w-16 bg-red-600 rounded"></div>
                    <span class="text-sm font-bold text-red-600 uppercase tracking-widest">Categories</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ $section->title }}</h2>
                @if ($section->description)
                    <p class="text-lg text-gray-600 max-w-2xl">{{ $section->description }}</p>
                @endif
            </div>
        @endif

        {{-- Categories Grid --}}
        @php
            $columns = $section->config['columns'] ?? 3;
            $gridClass = match ($columns) {
                2 => 'grid-cols-1 md:grid-cols-2',
                3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
                4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
                6 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6',
                default => 'grid-cols-1 md:grid-cols-3',
            };
            $showCount = $section->config['show_article_count'] ?? true;
        @endphp

        <div class="grid {{ $gridClass }} gap-6">
            @forelse($data['categories'] ?? [] as $category)
                <a href="{{ route('categories.show', $category) }}"
                    class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100 p-6 flex flex-col justify-between">

                    {{-- Category Icon/Badge --}}
                    <div class="mb-4">
                        <span
                            class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100 text-red-600 font-bold text-lg">
                            @php
                                $icons = [
                                    'sports' => '⚽',
                                    'technology' => '💻',
                                    'lifestyle' => '✨',
                                    'business' => '💼',
                                    'entertainment' => '🎬',
                                    'health' => '🏥',
                                    'travel' => '✈️',
                                    'food' => '🍽️',
                                ];
                                echo $icons[strtolower($category->slug ?? 'default')] ?? '📰';
                            @endphp
                        </span>
                    </div>

                    {{-- Category Name --}}
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-red-600 transition-colors">
                        {{ $category->name }}
                    </h3>

                    {{-- Article Count --}}
                    @if ($showCount)
                        <p class="text-sm text-gray-600 mb-4">
                            <span class="font-bold text-red-600">{{ $category->articles_count ?? 0 }}</span>
                            {{ \Illuminate\Support\Str::plural('Article', $category->articles_count ?? 0) }}
                        </p>
                    @endif

                    {{-- View All Link with Arrow --}}
                    <div
                        class="flex items-center gap-2 text-red-600 font-bold text-sm group-hover:gap-3 transition-all">
                        <span>View All</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </div>
                </a>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-16">
                        <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">No categories available.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
