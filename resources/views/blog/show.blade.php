@extends('layouts.app-modern')

@section('extra_head')
<!-- SweetAlert2 for notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@endsection

@section('content')
<!-- Advertisement Section (Like Homepage) -->
<div class="bg-white border-b border-gray-200 py-3 sm:py-6">
    <div class="max-w-6xl mx-auto px-3 sm:px-4">
        <div class="bg-gray-200 rounded-lg flex items-center justify-center min-h-16 sm:min-h-24">
            @component('components.advertisement', ['placement' => 'header_banner'])
            @endcomponent
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="bg-gray-50 py-6 sm:py-12">
    <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8">
        <!-- Success Message (for non-JS users) -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
                <i class="fas fa-check-circle text-green-600 mt-0.5 text-lg flex-shrink-0"></i>
                <div>
                    <h3 class="font-semibold text-green-900">Terima kasih!</h3>
                    <p class="text-sm text-green-800 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Article Content (Main) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Article Title -->
                <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                    <div class="mb-3 sm:mb-4">
                        <a href="{{ route('blog.category', $article->category->slug) }}" class="inline-block bg-red-600 text-white px-2.5 py-1 rounded text-xs font-bold uppercase hover:bg-red-700 transition">
                            {{ $article->category->name }}
                        </a>
                    </div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 leading-tight">
                        {{ $article->title }}
                    </h1>
                </div>

                <!-- Meta Information & Share Buttons -->
                <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                    <!-- Meta -->
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs text-gray-600 mb-3 sm:mb-4 pb-3 sm:pb-4 border-b border-gray-200">
                        @if($article->user)
                            <div class="flex items-center gap-1">
                                <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-red-600 flex items-center justify-center text-white font-bold text-xs">
                                    {{ substr($article->user->name, 0, 1) }}
                                </div>
                                <span class="font-medium text-gray-900 text-xs sm:text-sm truncate">{{ $article->user->name }}</span>
                            </div>
                        @endif
                        <span class="text-gray-400 hidden sm:inline">•</span>
                        <span class="text-xs">{{ $article->published_at->format('M d, Y') }}</span>
                        @if($article->read_time)
                            <span class="text-gray-400 hidden sm:inline">•</span>
                            <span class="text-xs"><i class="fas fa-clock mr-1"></i>{{ $article->read_time }}m</span>
                        @endif
                        <span class="text-gray-400 hidden sm:inline">•</span>
                        <span class="text-xs"><i class="fas fa-eye mr-1"></i>{{ number_format($article->views_count ?? 0) }}</span>
                    </div>

                    <!-- Share Buttons -->
                    <div>
                        <p class="text-xs font-semibold text-gray-900 mb-3 uppercase">Share</p>
                        <div class="flex flex-wrap gap-1.5 sm:gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-1 bg-blue-600 text-white px-2.5 sm:px-3 py-1.5 rounded hover:bg-blue-700 transition text-xs font-medium touch-active" title="Share on Facebook">
                                <i class="fab fa-facebook-f text-sm"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-1 bg-sky-500 text-white px-2.5 sm:px-3 py-1.5 rounded hover:bg-sky-600 transition text-xs font-medium" title="Share on Twitter">
                                <i class="fab fa-twitter text-sm"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-1 bg-blue-700 text-white px-2.5 sm:px-3 py-1.5 rounded hover:bg-blue-800 transition text-xs font-medium" title="Share on LinkedIn">
                                <i class="fab fa-linkedin-in text-sm"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . request()->url()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-1 bg-green-600 text-white px-2.5 sm:px-3 py-1.5 rounded hover:bg-green-700 transition text-xs font-medium" title="Share on WhatsApp">
                                <i class="fab fa-whatsapp text-sm"></i>
                            </a>
                            <button onclick="copyToClipboard('{{ request()->url() }}')" class="inline-flex items-center justify-center gap-1 bg-gray-600 text-white px-2.5 sm:px-3 py-1.5 rounded hover:bg-gray-700 transition text-xs font-medium" title="Copy link">
                                <i class="fas fa-link text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="bg-white rounded-lg overflow-hidden shadow-sm">
                    @if($article->featured_image)
                        <img src="{{ featuredImageUrl($article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-48 sm:h-64 object-cover" loading="lazy">
                    @else
                        <div class="w-full h-48 sm:h-64 bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                            <i class="fas fa-image text-5xl sm:text-6xl text-gray-300"></i>
                        </div>
                    @endif
                    @if($article->excerpt)
                        <div class="px-4 sm:px-6 py-2 sm:py-3 bg-gray-50 border-t border-gray-200 text-xs text-gray-600 line-clamp-2">
                            <i class="fas fa-info-circle mr-2 text-gray-500"></i>{{ $article->excerpt }}
                        </div>
                    @endif
                </div>

                <!-- Article Content -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="article-content p-6 sm:p-8 md:p-10 max-w-none">
                        {!! $article->content !!}
                    </div>
                </div>

                <!-- Tags -->
                @if($article->tags && $article->tags->count() > 0)
                    <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4 uppercase">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($article->tags as $tag)
                                <a href="{{ route('blog.tag', $tag->slug) }}" class="bg-gray-100 text-gray-700 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm hover:bg-gray-200 transition">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Previous/Next Navigation -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    @if($previousArticle)
                        <a href="{{ route('blog.show', $previousArticle->slug) }}" class="group bg-white p-6 rounded-lg border border-gray-200 hover:shadow-lg hover:border-red-300 transition">
                            <p class="text-xs font-bold text-gray-500 uppercase mb-2">← Previous Article</p>
                            <p class="font-semibold text-gray-900 group-hover:text-red-600 line-clamp-2">{{ $previousArticle->title }}</p>
                        </a>
                    @endif
                    @if($nextArticle)
                        <a href="{{ route('blog.show', $nextArticle->slug) }}" class="group bg-white p-6 rounded-lg border border-gray-200 hover:shadow-lg hover:border-red-300 transition">
                            <p class="text-xs font-bold text-gray-500 uppercase mb-2 text-right">Next Article →</p>
                            <p class="font-semibold text-gray-900 group-hover:text-red-600 line-clamp-2">{{ $nextArticle->title }}</p>
                        </a>
                    @endif
                </div>

                <!-- Related Articles Below Navigation -->
                @if($relatedArticles && $relatedArticles->count() > 0)
                    <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Related Articles</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            @foreach($relatedArticles->take(4) as $related)
                                <a href="{{ route('blog.show', $related->slug) }}" class="group">
                                    <div class="overflow-hidden rounded-lg">
                                        @if($related->featured_image)
                                            <img src="{{ featuredImageUrl($related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-40 object-cover group-hover:scale-105 transition" loading="lazy">
                                        @else
                                            <div class="w-full h-40 bg-gray-300 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400 text-3xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="font-semibold text-gray-900 group-hover:text-red-600 line-clamp-2 mt-3">{{ $related->title }}</p>
                                    <p class="text-xs text-gray-500 mt-2">{{ $related->published_at->format('M d, Y') }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Comments Section -->
                <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4 sm:mb-6">Comments</h2>

                    @php $approvedComments = $article->comments?->where('is_approved', true)->whereNull('parent_id') ?? collect(); @endphp

                    @if($approvedComments->count() > 0)
                        <div class="space-y-6 mb-8 border-b border-gray-200 pb-8">
                            @foreach($approvedComments as $comment)
                                @include('blog._comment-item', ['comment' => $comment, 'article' => $article])
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No comments yet. Be the first to comment!</p>
                    @endif

                    <!-- Comment Form -->
                    <div>
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Leave a Comment</h3>
                        <form action="{{ route('comments.store', $article->slug) }}" method="POST" class="space-y-3 sm:space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <input type="text" name="name" required placeholder="Your Name" class="px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                <input type="email" name="email" required placeholder="Your Email" class="px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <textarea name="content" required rows="4" placeholder="Your comment..." class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"></textarea>
                            <button type="submit" class="w-full sm:w-auto bg-red-600 text-white font-semibold py-2 sm:py-3 px-4 sm:px-6 rounded-lg hover:bg-red-700 transition text-sm sm:text-base">
                                Post Comment
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <aside class="lg:col-span-1">
                <div class="sticky top-24 space-y-4 sm:space-y-6">
                    <!-- Social Media Buttons -->
                    <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm border border-gray-200">
                        <h3 class="text-xs sm:text-sm font-bold text-gray-900 mb-3 uppercase">Follow Us</h3>
                        <div class="flex gap-2">
                            <a href="#" class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded hover:bg-blue-700 transition" title="Facebook">
                                <i class="fab fa-facebook-f text-sm"></i>
                            </a>
                            <a href="#" class="flex items-center justify-center w-10 h-10 bg-sky-500 text-white rounded hover:bg-sky-600 transition" title="Twitter">
                                <i class="fab fa-twitter text-sm"></i>
                            </a>
                            <a href="#" class="flex items-center justify-center w-10 h-10 bg-pink-600 text-white rounded hover:bg-pink-700 transition" title="Instagram">
                                <i class="fab fa-instagram text-sm"></i>
                            </a>
                            <a href="#" class="flex items-center justify-center w-10 h-10 bg-red-600 text-white rounded hover:bg-red-700 transition" title="YouTube">
                                <i class="fab fa-youtube text-sm"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Search -->
                    <form action="{{ route('blog.search') }}" method="GET" class="flex">
                        <input type="text" name="search" placeholder="Search..." class="flex-1 px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                        <button type="submit" class="bg-red-600 text-white px-3 sm:px-4 rounded-r-lg hover:bg-red-700 text-sm">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <!-- Advertisement Top -->
                    <div class="bg-gray-200 rounded-lg overflow-hidden shadow-sm border border-gray-200 min-h-80 flex items-center justify-center">
                        @component('components.advertisement', ['placement' => 'sidebar_top'])
                        @endcomponent
                    </div>

                    <!-- Popular Articles -->
                    @if($recentArticles && $recentArticles->count() > 0)
                        <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm border border-gray-200">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Popular Articles</h3>
                            <div class="space-y-4">
                                @foreach($recentArticles->take(5) as $popular)
                                    <a href="{{ route('blog.show', $popular->slug) }}" class="group flex gap-3 pb-4 border-b border-gray-200 last:border-0 last:pb-0">
                                        <!-- Square Image Left -->
                                        <div class="flex-shrink-0 w-20 h-20">
                                            @if($popular->featured_image)
                                                <img src="{{ featuredImageUrl($popular->featured_image) }}" alt="" class="w-20 h-20 object-cover rounded group-hover:opacity-80 transition" loading="lazy">
                                            @else
                                                <div class="w-20 h-20 bg-gray-300 rounded flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400 text-lg"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Title & View Count Right -->
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-sm text-gray-900 group-hover:text-red-600 line-clamp-2">{{ $popular->title }}</p>
                                            <p class="text-xs text-gray-500 mt-2">
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
                        <p class="text-xs sm:text-sm text-red-100 mb-3 sm:mb-4">Get latest news delivered to your inbox</p>
                        <x-newsletter-form placeholder="Your email" buttonText="Subscribe" />
                    </div>

                    <!-- Advertisement Bottom -->
                    <div class="bg-gray-200 rounded-lg overflow-hidden shadow-sm border border-gray-200 min-h-80 flex items-center justify-center">
                        @component('components.advertisement', ['placement' => 'sidebar_bottom'])
                        @endcomponent
                    </div>


                    <!-- Popular Tags -->
                    @if($popularTags && $popularTags->count() > 0)
                        <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm border border-gray-200">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Popular Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($popularTags->take(12) as $tag)
                                    <a href="{{ route('blog.tag', $tag->slug) }}" class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs hover:bg-red-100 hover:text-red-700 transition">
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
// Show success alert when comment is submitted
@if(session('success'))
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'success',
        title: 'Komentar Terkirim!',
        html: '<p class="text-gray-600">{{ session("success") }}</p>',
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#dc2626',
        allowOutsideClick: false,
        didOpen: function() {
            // Auto close after 5 seconds
            setTimeout(function() {
                Swal.close();
            }, 5000);
        }
    });
});
@endif

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Link telah disalin ke clipboard',
            timer: 2000,
            showConfirmButton: false,
            position: 'bottom-end',
            toast: true,
            background: '#1f2937',
            color: '#fff'
        });
    });
}

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
            Swal.fire({
                icon: 'success',
                title: 'Subscription Berhasil!',
                text: 'Terima kasih telah berlangganan newsletter kami',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc2626'
            });
            this.email = '';
        }
    })
    .catch(() => {
        this.loading = false;
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: 'Terjadi kesalahan saat berlangganan',
            confirmButtonText: 'OK',
            confirmButtonColor: '#dc2626'
        });
    });
}
</script>
@endsection
