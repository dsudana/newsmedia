@props([
    'categories',
    'activeId' => null,
    'limit' => null,
    'showCount' => true,
    'route' => 'blog.category',
    'routeParam' => 'slug'
])

@php
    // Always ensure articles_count is loaded
    if ($categories && $categories->count() > 0) {
        // Re-load all categories with withCount to ensure we have the count
        $allCategories = \App\Models\Category::active()
            ->withCount(['articles' => function ($q) {
                $q->where('status', 'published')->whereNotNull('published_at')->whereNull('deleted_at');
            }])
            ->orderBy('order')
            ->get();

        // Filter: only show categories with articles
        $allCategories = $allCategories->filter(function ($cat) {
            return $cat->articles_count > 0;
        });

        $categoriesToShow = $limit ? $allCategories->take($limit) : $allCategories;
    } else {
        $categoriesToShow = $categories;
    }
@endphp

@if($categoriesToShow && $categoriesToShow->count() > 0)
    <section class="dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700/50 shadow-sm dark:shadow-md transition-shadow duration-300">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <span class="w-1 h-6 bg-red-600 dark:bg-red-500 rounded-full"></span>
            {{ $slot }}
        </h3>
        <div class="space-y-2">
            @foreach ($categoriesToShow as $cat)
                @if($route === 'blog.index')
                    <a href="{{ route('blog.index', ['category' => $cat->slug]) }}"
                        class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-red-100 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 rounded-lg transition {{ (isset($activeId) && $activeId === $cat->id) ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 font-semibold' : '' }}">
                        {{ $cat->name }}
                        @if($showCount)
                            <span class="text-xs text-gray-500 dark:text-gray-400">({{ $cat->articles_count }})</span>
                        @endif
                    </a>
                @else
                    <a href="{{ route('blog.category', $cat->slug) }}"
                        class="group flex items-center justify-between px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-red-100 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 rounded-lg transition">
                        <span>{{ $cat->name }}</span>
                        @if($showCount)
                            <span class="text-xs bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 group-hover:bg-red-200 dark:group-hover:bg-red-900/50 px-2 py-1 rounded transition">
                                {{ $cat->articles_count }}
                            </span>
                        @endif
                    </a>
                @endif
            @endforeach
        </div>
    </section>
@endif
