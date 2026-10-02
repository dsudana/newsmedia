@extends('layouts.app-modern')

@section('title', $category->meta_title ?? $category->name)
@section('meta_description', $category->meta_description ?? $category->description)

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-red-600 to-red-700 text-white py-12 md:py-20">
    <div class="max-w-6xl mx-auto px-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-red-200 mb-6 text-sm">
            <a href="/" class="hover:text-white transition">Home</a>
            <span>/</span>
            <a href="{{ route('blog.index') }}" class="hover:text-white transition">Blog</a>
            <span>/</span>
            <span>{{ $category->name }}</span>
        </nav>

        <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $category->name }}</h1>
        @if($category->description)
            <p class="text-lg text-red-100 max-w-2xl leading-relaxed">{{ $category->description }}</p>
        @endif

        <div class="mt-8 flex items-center gap-6 text-base">
            <div class="flex items-center gap-2">
                <i class="fas fa-newspaper text-red-200"></i>
                <span>{{ $articles->total() }} articles</span>
            </div>
        </div>
    </div>
</div>

<!-- Advertisement Banner -->
<div class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 py-6">
    <div class="max-w-6xl mx-auto px-6">
        <x-advertisement placement="header_banner" />
    </div>
</div>

<!-- Main Content -->
<div class="bg-white dark:bg-slate-900">
    <div class="max-w-6xl mx-auto px-6 py-12 md:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Articles Section -->
            <div class="lg:col-span-3">
                <!-- Sort Options -->
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Articles</h2>
                    <select class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white" onchange="window.location.href = this.value">
                        <option value="?sort=latest" selected>Newest First</option>
                        <option value="?sort=oldest">Oldest First</option>
                        <option value="?sort=popular">Most Viewed</option>
                    </select>
                </div>

                <!-- Articles List -->
                <div class="space-y-0 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mb-12">
                    @forelse($articles as $article)
                        <article class="flex gap-4 p-5 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 transition group last:border-0 cursor-pointer">
                            <!-- Thumbnail -->
                            <a href="{{ route('blog.show', $article->slug) }}" class="flex-shrink-0 w-24 h-24 overflow-hidden rounded">
                                <img src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : 'https://via.placeholder.com/150x150?text=' . urlencode($category->name) }}" alt="{{ $article->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            </a>

                            <!-- Content -->
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition mb-2">
                                    <a href="{{ route('blog.show', $article->slug) }}">{{ $article->title }}</a>
                                </h3>

                                <p class="text-gray-600 dark:text-gray-400 text-base line-clamp-1 mb-2">{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 80) }}</p>

                                <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
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
                                <div class="text-base font-semibold text-gray-900 dark:text-white">
                                    <i class="fas fa-comment text-gray-400 dark:text-gray-500 mr-1"></i>{{ $commentCount }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $commentCount === 1 ? 'Comment' : 'Comments' }}
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="p-12 text-center">
                            <i class="fas fa-inbox text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-xl">No articles found in this category yet.</p>
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

            <!-- Sidebar (Same as Article Detail Page) -->
            <aside class="lg:col-span-1">
                <div class="sticky top-4 space-y-6">
                    <!-- Social Media Icons -->
                    <div class="bg-white dark:bg-slate-800 rounded-lg p-4 sm:p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-4">Follow Us</h3>
                        <div class="flex flex-wrap gap-3">
                            @php
                                $socialLinks = \App\Models\SocialMedia::active()->ordered()->get();
                            @endphp
                            @forelse ($socialLinks as $social)
                                <a href="{{ $social->url }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   title="{{ $social->platform }}"
                                   class="w-10 h-10 flex items-center justify-center rounded-lg bg-red-600 hover:bg-red-700 text-white transition duration-300 transform hover:scale-110">
                                    <i class="{{ $social->icon }}"></i>
                                </a>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">Follow us on social media</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Search -->
                    <form action="{{ route('blog.search') }}" method="GET" class="flex">
                        <input type="text" name="search" placeholder="Search..." class="flex-1 px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 dark:border-gray-600 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white" required>
                        <button type="submit" class="bg-red-600 text-white px-3 sm:px-4 rounded-r-lg hover:bg-red-700 text-sm dark:hover:bg-red-700">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <!-- Advertisement Top -->
                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm border border-gray-200 dark:border-gray-600 min-h-80 flex items-center justify-center">
                        @component('components.advertisement', ['placement' => 'sidebar_top'])
                        @endcomponent
                    </div>

                    <!-- Popular Articles -->
                    @php
                        $popularArticles = \App\Models\Article::where('category_id', $category->id)
                            ->published()
                            ->orderByDesc('views_count')
                            ->take(5)
                            ->get();
                    @endphp
                    @if($popularArticles && $popularArticles->count() > 0)
                        <div class="bg-white dark:bg-slate-800 rounded-lg p-4 sm:p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-3 sm:mb-4">Popular Articles</h3>
                            <div class="space-y-4">
                                @foreach($popularArticles as $popular)
                                    <a href="{{ route('blog.show', $popular->slug) }}" class="group flex gap-3 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0 last:pb-0">
                                        <!-- Square Image Left -->
                                        <div class="flex-shrink-0 w-20 h-20">
                                            @if($popular->featured_image)
                                                <img src="{{ asset('/storage/' . $popular->featured_image) }}" alt="" class="w-20 h-20 object-cover rounded group-hover:opacity-80 transition" loading="lazy">
                                            @else
                                                <div class="w-20 h-20 bg-gray-300 dark:bg-gray-600 rounded flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400 dark:text-gray-400 text-lg"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Title & View Count Right -->
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-sm text-gray-900 dark:text-white group-hover:text-red-600 dark:group-hover:text-red-400 line-clamp-2">{{ $popular->title }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                                <i class="fas fa-eye mr-1"></i>{{ number_format($popular->views_count ?? 0) }} views
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Newsletter -->
                    <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-lg p-4 sm:p-6 text-white shadow-lg">
                        <h3 class="text-base sm:text-lg font-bold mb-2">Stay Updated</h3>
                        <p class="text-xs sm:text-sm text-red-100 mb-3 sm:mb-4">Get {{ $category->name }} news to your inbox</p>
                        <x-newsletter-form placeholder="Your email" buttonText="Subscribe" />
                    </div>

                    <!-- Advertisement Bottom -->
                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm border border-gray-200 dark:border-gray-600 min-h-80 flex items-center justify-center">
                        @component('components.advertisement', ['placement' => 'sidebar_bottom'])
                        @endcomponent
                    </div>

                    <!-- Popular Tags -->
                    @php
                        $popularTags = \App\Models\Tag::whereHas('articles', function($q) use ($category) {
                            $q->where('category_id', $category->id)->published();
                        })->withCount('articles')->orderByDesc('articles_count')->take(12)->get();
                    @endphp
                    @if($popularTags && $popularTags->count() > 0)
                        <div class="bg-white dark:bg-slate-800 rounded-lg p-4 sm:p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-3 sm:mb-4">Popular Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($popularTags as $tag)
                                    <a href="{{ route('blog.tag', $tag->slug) }}" class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-1 rounded text-xs hover:bg-red-100 hover:text-red-700 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</div>

<script>
function submitNewsletter() {
    this.loading = true;
    fetch('{{ route("newsletter.subscribe") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email: this.email })
    })
    .then(response => response.json())
    .then(data => {
        this.loading = false;
        if (data.success) {
            alert('Subscribed successfully!');
            this.email = '';
        } else {
            alert(data.message || 'Subscription failed');
        }
    })
    .catch(error => {
        this.loading = false;
        alert('Error subscribing');
    });
}
</script>
@endsection
