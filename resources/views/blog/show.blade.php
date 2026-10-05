@extends('layouts.app-modern')

@section('extra_head')
<style>
    .article-content {
        font-size: 1.0625rem;
        line-height: 1.8;
        color: #333;
    }
    html.dark .article-content {
        color: #e5e7eb;
    }
    .article-content p {
        margin-bottom: 1.5rem;
    }
    .article-content h2, .article-content h3, .article-content h4 {
        color: #1a1a1a;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    html.dark .article-content h2,
    html.dark .article-content h3,
    html.dark .article-content h4 {
        color: #f3f4f6;
    }
    .article-content h2 {
        font-size: 1.75rem;
        border-bottom: 3px solid #dc2626;
        padding-bottom: 0.75rem;
    }
    .article-content h3 {
        font-size: 1.5rem;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        margin: 2rem 0;
        border-radius: 8px;
    }
    .article-content blockquote {
        border-left: 4px solid #dc2626;
        padding-left: 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #555;
    }
    html.dark .article-content blockquote {
        color: #d1d5db;
    }
    .article-content ul, .article-content ol {
        margin: 1.5rem 0 1.5rem 2rem;
    }
    .article-content li {
        margin-bottom: 0.5rem;
    }
</style>
@endsection

@section('content')
<!-- Advertisement Section -->
<div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 py-2 sm:py-3 lg:py-4 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-200 dark:bg-gray-800 rounded-lg flex items-center justify-center h-14 sm:h-20 lg:h-24 overflow-hidden">
            <x-frontend.advertisement placement="header_banner" />
        </div>
    </div>
</div>

<!-- Main Content with Sidebar -->
<div class="bg-white dark:bg-gray-900 py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-8 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded flex items-start gap-3">
                <i class="fas fa-check-circle text-green-600 dark:text-green-400 mt-0.5 text-lg flex-shrink-0"></i>
                <div>
                    <h3 class="font-semibold text-green-900 dark:text-green-100">Terima kasih!</h3>
                    <p class="text-sm text-green-800 dark:text-green-300 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Article Content (Left - 2 columns) -->
            <div class="lg:col-span-2">
                <article class="mb-12">
                    <!-- Category Badge -->
                    <div class="mb-4">
                        <a href="{{ route('blog.category', $article->category->slug) }}" class="inline-block bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold uppercase hover:bg-red-700 transition">
                            {{ $article->category->name }}
                        </a>
                    </div>

                    <!-- Article Title -->
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white leading-tight mb-6">
                        {{ $article->title }}
                    </h1>

                    <!-- Meta Information -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 pb-6 border-b-2 border-gray-300 dark:border-gray-700 mb-6">
                        <div class="flex items-center gap-4">
                            @if($article->user)
                                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400 font-bold text-lg flex-shrink-0">
                                    {{ substr($article->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-gray-900 dark:text-gray-100">{{ $article->user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $article->published_at->format('d M Y H:i') }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400 sm:ml-auto">
                            @if($article->read_time)
                                <span><i class="fas fa-clock text-red-600 mr-2"></i>{{ $article->read_time }} menit baca</span>
                            @endif
                            <span class="text-gray-400 dark:text-gray-600">•</span>
                            <span><i class="fas fa-eye text-red-600 mr-2"></i>{{ number_format($article->views_count ?? 0) }} dibaca</span>
                        </div>
                    </div>

                    <!-- Share Buttons -->
                    <div class="flex items-center gap-3 mb-8 py-4">
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Bagikan ke:</span>
                        <div class="flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition" title="Facebook">
                                <i class="fab fa-facebook-f text-sm"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-sky-500 text-white flex items-center justify-center hover:bg-sky-600 transition" title="Twitter">
                                <i class="fab fa-twitter text-sm"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . request()->url()) }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-green-600 text-white flex items-center justify-center hover:bg-green-700 transition" title="WhatsApp">
                                <i class="fab fa-whatsapp text-sm"></i>
                            </a>
                            <button onclick="copyToClipboard('{{ request()->url() }}')" class="w-9 h-9 rounded-full bg-gray-400 text-white flex items-center justify-center hover:bg-gray-500 transition" title="Salin tautan">
                                <i class="fas fa-link text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <div class="mb-8 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800 aspect-video">
                        @if($article->featured_image)
                            <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover" loading="lazy">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-500 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                                <i class="fas fa-image text-6xl text-gray-400 dark:text-gray-600"></i>
                            </div>
                        @endif
                    </div>

                    @if($article->excerpt)
                        <div class="mb-8 p-5 bg-gray-50 dark:bg-gray-800 border-l-4 border-red-600">
                            <p class="text-base text-gray-800 dark:text-white leading-relaxed">{{ $article->excerpt }}</p>
                        </div>
                    @endif

                    <!-- Article Content -->
                    <div class="article-content mb-12">
                        {!! $article->getSafeContent() !!}
                    </div>

                    <!-- Tags -->
                    @if($article->tags && $article->tags->count() > 0)
                        <div class="border-t-2 border-gray-300 dark:border-gray-700 pt-6 mb-8">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-4 uppercase">Tags Terkait</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($article->tags as $tag)
                                    <a href="{{ route('blog.tag', $tag->slug) }}" class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-full text-sm hover:bg-red-100 dark:hover:bg-red-900/30 hover:text-red-700 dark:hover:text-red-400 transition">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Previous/Next Navigation -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                        @if($previousArticle)
                            <a href="{{ route('blog.show', $previousArticle->slug) }}" class="group border border-gray-200 dark:border-gray-700 p-5 rounded-lg hover:shadow-lg hover:border-red-300 dark:hover:border-red-600 transition">
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">← Artikel Sebelumnya</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-red-600 line-clamp-2">{{ $previousArticle->title }}</p>
                            </a>
                        @endif
                        @if($nextArticle)
                            <a href="{{ route('blog.show', $nextArticle->slug) }}" class="group border border-gray-200 dark:border-gray-700 p-5 rounded-lg hover:shadow-lg hover:border-red-300 dark:hover:border-red-600 transition md:text-right">
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Artikel Selanjutnya →</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-red-600 line-clamp-2">{{ $nextArticle->title }}</p>
                            </a>
                        @endif
                    </div>

                    <!-- Related Articles -->
                    @if($relatedArticles && $relatedArticles->count() > 0)
                        <div class="border-t-2 border-gray-300 dark:border-gray-700 pt-8 mb-12">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Artikel Terkait</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @foreach($relatedArticles->take(7) as $related)
                                    <a href="{{ route('blog.show', $related->slug) }}" class="group">
                                        <div class="overflow-hidden rounded-lg mb-3 aspect-video bg-gray-100 dark:bg-gray-800">
                                            @if($related->featured_image)
                                                <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition" loading="lazy">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-500 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400 dark:text-gray-600 text-3xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-red-600 line-clamp-2 mb-1">{{ $related->title }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $related->published_at->format('d M Y') }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Comments Section -->
                    <div class="border-t-2 border-gray-300 dark:border-gray-700 pt-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Komentar</h2>

                        @php $approvedComments = $article->comments?->where('is_approved', true)->whereNull('parent_id') ?? collect(); @endphp

                        @if($approvedComments->count() > 0)
                            <div class="space-y-6 mb-8 pb-8">
                                @foreach($approvedComments as $comment)
                                    @include('blog._comment-item', ['comment' => $comment, 'article' => $article])
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-center py-8">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                        @endif

                        <!-- Comment Form -->
                        <div class="border border-gray-300 dark:border-gray-600 dark:bg-gray-800 p-6 rounded-lg">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Tinggalkan Komentar</h3>
                            <form action="{{ route('comments.store', $article->slug) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <input type="text" name="name" required placeholder="Nama Anda" class="px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent placeholder-gray-400 dark:placeholder-gray-500">
                                    <input type="email" name="email" required placeholder="Email Anda" class="px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent placeholder-gray-400 dark:placeholder-gray-500">
                                </div>
                                <textarea name="content" required rows="5" placeholder="Komentar Anda..." class="w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none placeholder-gray-400 dark:placeholder-gray-500"></textarea>
                                <button type="submit" class="bg-red-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-red-700 transition text-sm">
                                    Kirim Komentar
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Right Sidebar -->
            <aside class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    <!-- Advertisement Top -->
                    <div class=" dark:bg-gray-800 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 min-h-80 flex items-center justify-center">
                        <x-frontend.advertisement placement="sidebar_top" />
                        
                    </div>

                    <!-- Recent Articles (labeled "Populer" for consistency) -->
                    <x-sidebar.popular-articles :articles="$sidebarArticles" :limit="5" :showThumbnail="true" :showDate="false" :showViews="true">
                        Artikel Populer
                    </x-sidebar.popular-articles>

                    <!-- Newsletter -->
                    <x-sidebar.newsletter-card
                        title="Tetap Update"
                        subtitle="Dapatkan berita terbaru langsung ke inbox Anda"
                        placeholder="Email Anda"
                        buttonText="Berlangganan"
                    />

                    <!-- Advertisement Bottom -->
                    <div class=" dark:bg-gray-800 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 min-h-80 flex items-center justify-center">
                        <x-frontend.advertisement placement="sidebar_bottom" />
                        
                    </div>

                    <!-- Popular Tags -->
                    @if($popularTags && $popularTags->count() > 0)
                        <div class= dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tag Populer</h3>
                            <x-frontend.tag-cloud :tags="$popularTags->take(12)" />
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
            text: 'Tautan telah disalin ke clipboard',
            timer: 2000,
            showConfirmButton: false,
            position: 'bottom-end',
            toast: true,
            background: '#1f2937',
            color: '#fff'
        });
    });
}
</script>
@endsection
