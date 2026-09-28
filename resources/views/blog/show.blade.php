@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative w-full bg-gray-900 overflow-hidden">
    @if($article->featured_image)
        <img src="{{ '/storage/' . $article->featured_image }}" alt="{{ $article->title }}" class="absolute inset-0 w-full h-full object-cover opacity-30">
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
            <a href="/" class="hover:text-white transition">Home</a>
            <span>/</span>
            <a href="{{ route('blog.category', $article->category->slug) }}" class="hover:text-white transition">{{ $article->category->name }}</a>
        </nav>

        <!-- Category Badge -->
        <div class="mb-4">
            <a href="{{ route('blog.category', $article->category->slug) }}" class="inline-block bg-indigo-600 text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide hover:bg-indigo-700">
                {{ $article->category->name }}
            </a>
        </div>

        <!-- Title -->
        <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight">{{ $article->title }}</h1>

        <!-- Meta -->
        <div class="flex flex-wrap items-center gap-4 text-gray-300 text-base">
            @if($article->user)
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">{{ substr($article->user->name, 0, 1) }}</div>
                    <span class="font-medium">{{ $article->user->name }}</span>
                </div>
            @endif
            <span class="hidden sm:inline text-gray-500">•</span>
            <span>{{ $article->published_at->format('M d, Y') }}</span>
            @if($article->read_time)
                <span class="hidden sm:inline text-gray-500">•</span>
                <span><i class="fas fa-clock mr-1.5"></i>{{ $article->read_time }} min read</span>
            @endif
            <span class="hidden sm:inline text-gray-500">•</span>
            <span><i class="fas fa-eye mr-1.5"></i>{{ number_format($article->views_count ?? 0) }} views</span>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
            <!-- Article -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Excerpt -->
                @if($article->excerpt)
                    <div class="text-xl text-gray-700 italic border-l-4 border-indigo-600 pl-6 py-4 bg-indigo-50 rounded-r-lg">
                        {{ $article->excerpt }}
                    </div>
                @endif

                <!-- Content -->
                <div class="prose prose-lg prose-indigo max-w-none">
                    {!! $article->content !!}
                </div>

                <!-- Share Section -->
                <div class="py-8 px-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-200">
                    <p class="text-base font-bold text-gray-900 uppercase tracking-widest mb-4">Share This Article</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition transform hover:scale-105">
                            <i class="fab fa-facebook-f"></i> <span class="hidden sm:inline">Share</span>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-sky-500 text-white px-4 py-2 rounded-lg hover:bg-sky-600 transition transform hover:scale-105">
                            <i class="fab fa-twitter"></i> <span class="hidden sm:inline">Tweet</span>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition transform hover:scale-105">
                            <i class="fab fa-linkedin-in"></i> <span class="hidden sm:inline">Share</span>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . request()->url()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition transform hover:scale-105">
                            <i class="fab fa-whatsapp"></i> <span class="hidden sm:inline">Send</span>
                        </a>
                        <button onclick="copyToClipboard('{{ request()->url() }}')" class="inline-flex items-center gap-2 bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition transform hover:scale-105">
                            <i class="fas fa-link"></i> <span class="hidden sm:inline">Copy</span>
                        </button>
                    </div>
                </div>

                <!-- Tags -->
                @if($article->tags && $article->tags->count() > 0)
                    <div class="py-6 border-t border-b border-gray-200">
                        <p class="text-base font-bold text-gray-900 uppercase tracking-widest mb-4">Tags</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($article->tags as $tag)
                                <a href="{{ route('blog.tag', $tag->slug) }}" class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-full text-base font-medium hover:bg-indigo-200 transition">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Navigation -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-12">
                    @if($previousArticle)
                        <a href="{{ route('blog.show', $previousArticle->slug) }}" class="group p-6 bg-gray-50 border border-gray-200 rounded-lg hover:shadow-lg hover:border-indigo-300 transition">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Previous Article</p>
                            <p class="font-semibold text-gray-900 group-hover:text-indigo-600 line-clamp-2">{{ $previousArticle->title }}</p>
                        </a>
                    @endif
                    @if($nextArticle)
                        <a href="{{ route('blog.show', $nextArticle->slug) }}" class="group p-6 bg-indigo-50 border border-indigo-200 rounded-lg hover:shadow-lg hover:border-indigo-400 transition">
                            <p class="text-xs font-bold text-indigo-600 uppercase tracking-wide mb-2 text-right">Next Article</p>
                            <p class="font-semibold text-gray-900 group-hover:text-indigo-700 line-clamp-2">{{ $nextArticle->title }}</p>
                        </a>
                    @endif
                </div>

                <!-- Comments -->
                <div class="mt-16 pt-12 border-t border-gray-200">
                    <h2 class="text-4xl font-bold text-gray-900 mb-8">Comments</h2>

                    @php $approvedComments = $article->comments?->where('is_approved', true)->whereNull('parent_id') ?? collect(); @endphp

                    @if($approvedComments->count() > 0)
                        <div class="space-y-6 mb-12">
                            @foreach($approvedComments as $comment)
                                @include('blog._comment-item', ['comment' => $comment, 'article' => $article])
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No comments yet. Be the first!</p>
                    @endif

                    <!-- Comment Form -->
                    <div class="bg-white rounded-lg border border-gray-200 p-8 mt-12">
                        <h3 class="text-3xl font-bold text-gray-900 mb-6">Leave a Comment</h3>
                        <form action="{{ route('comments.store', $article->slug) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" name="name" required placeholder="Your Name" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <input type="email" name="email" required placeholder="Your Email" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <textarea name="content" required rows="4" placeholder="Your comment..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                            <button type="submit" class="bg-indigo-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-indigo-700 transition">
                                Post Comment
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="lg:col-span-1">
                <div class="sticky top-4 space-y-6">
                    <!-- Search -->
                    <form action="{{ route('blog.search') }}" method="GET" class="flex">
                        <input type="text" name="search" placeholder="Search..." class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <button type="submit" class="bg-indigo-600 text-white px-4 rounded-r-lg hover:bg-indigo-700">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <!-- Related Articles -->
                    @if($relatedArticles && $relatedArticles->count() > 0)
                        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Related Articles</h3>
                            <div class="space-y-3">
                                @foreach($relatedArticles->take(4) as $related)
                                    <a href="{{ route('blog.show', $related->slug) }}" class="group flex gap-3 pb-3 border-b border-gray-200 last:border-0 last:pb-0 hover:opacity-80">
                                        @if($related->featured_image)
                                            <img src="{{ '/storage/' . $related->featured_image }}" alt="" class="w-16 h-16 object-cover rounded group-hover:scale-110 transition">
                                        @else
                                            <div class="w-16 h-16 bg-gray-300 rounded"></div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-sm text-gray-900 group-hover:text-indigo-600 line-clamp-2">{{ $related->title }}</p>
                                            <p class="text-sm text-gray-500 mt-1">{{ $related->published_at->format('M d, Y') }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Newsletter -->
                    <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg p-6 text-white shadow-lg">
                        <h3 class="text-xl font-bold mb-2">Stay Updated</h3>
                        <p class="text-base text-indigo-100 mb-4">Get latest news in your inbox</p>
                        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="email" name="email" placeholder="Your email" class="w-full px-4 py-2 rounded-lg bg-white/90 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
                            <button type="submit" class="w-full bg-white text-indigo-600 font-semibold py-2 rounded-lg hover:bg-indigo-50 transition text-sm">Subscribe</button>
                        </form>
                    </div>

                    <!-- Recent -->
                    @if($recentArticles && $recentArticles->count() > 0)
                        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Recent Articles</h3>
                            <div class="space-y-4">
                                @foreach($recentArticles->take(5) as $recent)
                                    <a href="{{ route('blog.show', $recent->slug) }}" class="group block pb-4 border-b border-gray-200 last:border-0 last:pb-0">
                                        <p class="font-semibold text-base text-gray-900 group-hover:text-indigo-600 line-clamp-2">{{ $recent->title }}</p>
                                        <p class="text-sm text-gray-500 mt-1">{{ $recent->published_at->format('M d, Y') }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Tags -->
                    @if($popularTags && $popularTags->count() > 0)
                        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Popular Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($popularTags->take(12) as $tag)
                                    <a href="{{ route('blog.tag', $tag->slug) }}" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-sm font-medium hover:bg-indigo-100 hover:text-indigo-700 transition">
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
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Link copied to clipboard!');
    });
}
</script>
@endsection
