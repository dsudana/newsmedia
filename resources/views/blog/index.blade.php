@extends('layouts.app-modern')

@section('content')
<!-- Hero Section with Search -->
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-12 md:py-20">
    <div class="max-w-6xl mx-auto px-4">
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-indigo-200 text-base mb-4">
                <a href="/" class="hover:text-white transition">Home</a>
                <span>/</span>
                <span>{{ $title ?? 'All Articles' }}</span>
            </nav>
            <h1 class="text-5xl md:text-6xl font-bold mb-2">{{ $title ?? 'All Articles' }}</h1>
            <p class="text-xl text-indigo-100">Discover the latest news and stories from our publication</p>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('blog.search') }}" class="flex gap-3">
            <input type="text" name="search" placeholder="Search articles..." value="{{ request('search') }}"
                   class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 text-base md:text-lg">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition transform hover:scale-105">
                <i class="fas fa-search mr-2"></i>Search
            </button>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 py-12 md:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Articles List -->
            <div class="lg:col-span-3">
                <!-- Filter Bar -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-200">
                    <div class="flex items-center gap-2 text-base text-gray-600">
                        <i class="fas fa-filter"></i>
                        <span>Showing <strong>{{ $articles->count() }}</strong> of <strong>{{ $articles->total() }}</strong> articles</span>
                    </div>

                    <select class="px-4 py-2 border border-gray-300 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" onchange="window.location.href = this.value">
                        <option value="{{ route('blog.index') }}?sort=latest" {{ request('sort') === 'latest' || !request('sort') ? 'selected' : '' }}>
                            Newest First
                        </option>
                        <option value="{{ route('blog.index') }}?sort=oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                            Oldest First
                        </option>
                        <option value="{{ route('blog.index') }}?sort=popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>
                            Most Viewed
                        </option>
                    </select>
                </div>

                <!-- Articles List -->
                @if($articles->count() > 0)
                    <div class="space-y-0 mb-12">
                        @foreach($articles as $article)
                            <article class="flex gap-4 p-5 border-b border-gray-200 hover:bg-gray-50 transition group cursor-pointer">
                                <!-- Thumbnail -->
                                <a href="{{ route('blog.show', $article->slug) }}" class="flex-shrink-0 w-24 h-24 overflow-hidden rounded">
                                    <img src="{{ $article->image }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                </a>

                                <!-- Content -->
                                <div class="flex-1 min-w-0 flex flex-col justify-center">
                                    <h3 class="text-lg font-bold text-gray-900 line-clamp-2 group-hover:text-red-600 transition mb-2">
                                        <a href="{{ route('blog.show', $article->slug) }}">{{ $article->title }}</a>
                                    </h3>

                                    <div class="flex items-center gap-3 text-sm text-gray-500">
                                        <a href="{{ route('blog.category', $article->category->slug) }}" class="text-red-600 font-semibold hover:text-red-700">
                                            {{ $article->category->name }}
                                        </a>
                                        <span>•</span>
                                        <span>{{ $article->published_at->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                <!-- Comment Count -->
                                <div class="flex-shrink-0 text-right">
                                    @php
                                        $commentCount = $article->comments ? $article->comments->where('is_approved', true)->count() : 0;
                                    @endphp
                                    <div class="text-base font-semibold text-gray-900">
                                        <i class="fas fa-comment text-gray-400 mr-1"></i>{{ $commentCount }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $commentCount === 1 ? 'Comment' : 'Comments' }}
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center">
                        {{ $articles->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-lg p-16 text-center">
                        <i class="fas fa-search text-5xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No articles found</h3>
                        <p class="text-gray-500 mb-6">
                            @if(request('search'))
                                We couldn't find any articles matching "<strong>{{ request('search') }}</strong>"
                            @else
                                No articles available in this category
                            @endif
                        </p>
                        <a href="{{ route('blog.index') }}" class="inline-block bg-indigo-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                            Back to All Articles
                        </a>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-4 space-y-6">
                    <!-- Filters Card -->
                    <div class="bg-white rounded-lg p-6 shadow-md">
                        <h3 class="font-bold text-lg text-gray-900 mb-4 pb-3 border-b border-gray-200">Filters</h3>

                        <!-- Category Filter -->
                        <div class="mb-6">
                            <label class="block text-base font-semibold text-gray-900 mb-3">Category</label>
                            <div class="space-y-2">
                                <a href="{{ route('blog.index') }}" class="flex items-center gap-2 p-2 rounded hover:bg-indigo-50 transition {{ !request('category') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700' }}">
                                    <i class="fas fa-check {{ !request('category') ? 'text-indigo-600' : 'text-gray-300' }}"></i>
                                    <span class="text-base font-medium">All Categories</span>
                                </a>

                                @forelse($categories as $category)
                                    <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="flex items-center justify-between p-2 rounded hover:bg-indigo-50 transition {{ request('category') === $category->slug ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700' }}">
                                        <span class="text-base font-medium">{{ $category->name }}</span>
                                        <span class="text-sm bg-gray-200 text-gray-700 px-2 py-1 rounded-full">{{ $category->articles_count ?? 0 }}</span>
                                    </a>
                                @empty
                                    <p class="text-base text-gray-500">No categories available</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Tags Card -->
                    @if($popularTags && $popularTags->count() > 0)
                        <div class="bg-white rounded-lg p-6 shadow-md">
                            <h3 class="font-bold text-lg text-gray-900 mb-4 pb-3 border-b border-gray-200">Popular Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($popularTags->take(15) as $tag)
                                    <a href="{{ route('blog.tag', $tag->slug) }}" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-sm font-medium hover:bg-indigo-100 hover:text-indigo-700 transition">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Newsletter CTA -->
                    <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-lg p-6 text-white shadow-lg">
                        <h3 class="font-bold text-lg mb-2">Stay Updated</h3>
                        <p class="text-base text-red-100 mb-4">Get the latest news delivered to your inbox</p>
                        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="email" name="email" placeholder="Your email" class="w-full px-3 py-2 rounded bg-white/90 text-gray-900 text-base focus:outline-none focus:ring-2 focus:ring-red-400" required>
                            <button type="submit" class="w-full bg-white text-red-600 font-semibold py-2 rounded hover:bg-red-50 transition text-base">
                                Subscribe
                            </button>
                        </form>
                    </div>

                    <!-- Statistics -->
                    <div class="bg-white rounded-lg p-6 shadow-md">
                        <h3 class="font-bold text-lg text-gray-900 mb-4">Statistics</h3>
                        <div class="space-y-3 text-base">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Articles</span>
                                <span class="font-bold text-indigo-600">{{ $articles->total() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Categories</span>
                                <span class="font-bold text-indigo-600">{{ $categories->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tags</span>
                                <span class="font-bold text-indigo-600">{{ $popularTags ? $popularTags->count() : 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
