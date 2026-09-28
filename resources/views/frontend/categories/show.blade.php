@extends('layouts.app-modern')

@section('title', $category->meta_title ?? $category->name)
@section('meta_description', $category->meta_description ?? $category->description)

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-12 md:py-20">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-indigo-200 mb-6 text-base">
            <a href="/" class="hover:text-white transition">Home</a>
            <span>/</span>
            <span>{{ $category->name }}</span>
        </nav>

        <h1 class="text-5xl md:text-6xl font-bold mb-4">{{ $category->name }}</h1>
        @if($category->description)
            <p class="text-xl text-indigo-100 max-w-2xl">{{ $category->description }}</p>
        @endif

        <div class="mt-6 flex items-center gap-4 text-base">
            <span><i class="fas fa-newspaper mr-2"></i>{{ $articles->total() }} articles</span>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 py-12 md:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Articles Grid -->
            <div class="lg:col-span-3">
                <!-- Sort Options -->
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
                    <h2 class="text-3xl font-bold text-gray-900">Articles</h2>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-indigo-500" onchange="window.location.href = this.value">
                        <option value="?sort=latest" selected>Newest First</option>
                        <option value="?sort=oldest">Oldest First</option>
                        <option value="?sort=popular">Most Viewed</option>
                    </select>
                </div>

                <!-- Articles List -->
                <div class="space-y-0 bg-white border border-gray-200 rounded-lg overflow-hidden mb-12">
                    @forelse($articles as $article)
                        <article class="flex gap-4 p-5 border-b border-gray-200 hover:bg-gray-50 transition group last:border-0 cursor-pointer">
                            <!-- Thumbnail -->
                            <a href="{{ route('blog.show', $article->slug) }}" class="flex-shrink-0 w-24 h-24 overflow-hidden rounded">
                                <img src="{{ $article->image }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            </a>

                            <!-- Content -->
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <h3 class="text-lg font-bold text-gray-900 line-clamp-2 group-hover:text-red-600 transition mb-2">
                                    <a href="{{ route('blog.show', $article->slug) }}">{{ $article->title }}</a>
                                </h3>

                                <p class="text-gray-600 text-base line-clamp-1 mb-2">{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 80) }}</p>

                                <div class="flex items-center gap-3 text-sm text-gray-500">
                                    <span>{{ $article->user->name }}</span>
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
                    @empty
                        <div class="p-12 text-center">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-xl">No articles found in this category yet.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($articles->hasPages())
                    <div class="flex justify-center">
                        {{ $articles->links() }}
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-4 space-y-6">
                    <!-- Search -->
                    <form action="{{ route('blog.search') }}" method="GET" class="flex">
                        <input type="text" name="search" placeholder="Search..." class="flex-1 px-4 py-2 text-base border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <button type="submit" class="bg-indigo-600 text-white px-4 rounded-r-lg hover:bg-indigo-700">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <!-- Category Info Card -->
                    <div class="bg-white rounded-lg p-6 shadow-md">
                        <h3 class="font-bold text-lg text-gray-900 mb-3">About This Category</h3>
                        <div class="space-y-3 text-base text-gray-600">
                            <div>
                                <span class="font-semibold text-gray-900">Total Articles</span>
                                <p class="text-xl font-bold text-indigo-600">{{ $articles->total() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Related Categories -->
                    @php
                        $relatedCategories = \App\Models\Category::where('id', '!=', $category->id)
                            ->active()
                            ->withCount('articles')
                            ->orderByDesc('articles_count')
                            ->take(5)
                            ->get();
                    @endphp

                    @if($relatedCategories->count() > 0)
                        <div class="bg-white rounded-lg p-6 shadow-md">
                            <h3 class="font-bold text-lg text-gray-900 mb-4">Other Categories</h3>
                            <div class="space-y-3">
                                @foreach($relatedCategories as $related)
                                    <a href="{{ route('blog.category', $related->slug) }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-indigo-50 transition group">
                                        <span class="font-medium text-base text-gray-900 group-hover:text-indigo-600">{{ $related->name }}</span>
                                        <span class="text-sm bg-gray-200 text-gray-700 px-2 py-1 rounded">{{ $related->articles_count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Newsletter CTA -->
                    <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-lg p-6 text-white shadow-lg">
                        <h3 class="font-bold text-lg mb-2">Stay Updated</h3>
                        <p class="text-base text-red-100 mb-4">Get the latest {{ $category->name }} news</p>
                        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="email" name="email" placeholder="Your email" class="w-full px-3 py-2 rounded bg-white/90 text-gray-900 text-base focus:outline-none focus:ring-2 focus:ring-red-400" required>
                            <button type="submit" class="w-full bg-white text-red-600 font-semibold py-2 rounded hover:bg-red-50 transition text-base">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
