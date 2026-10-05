@extends('layouts.app-modern')

@section('title', $category->meta_title ?? $category->name)
@section('meta_description', $category->meta_description ?? $category->description)

@section('content')
<!-- Advertisement Section (Full Width) -->
<div class="bg-white border-b border-gray-200 py-3 sm:py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-200 rounded-lg flex items-center justify-center min-h-16 sm:min-h-24">
            @component('components.advertisement', ['placement' => 'header_banner'])
            @endcomponent
        </div>
    </div>
</div>

<!-- Hero Section with Search -->
<div class="bg-gradient-to-r from-red-600 to-red-700 text-white py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-red-200 mb-6 text-xs sm:text-sm">
            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
            <span class="text-red-300">/</span>
            <span class="text-white font-semibold">{{ $category->name }}</span>
        </nav>

        <!-- Hero Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight">
                    {{ $category->name }}
                </h1>
                @if($category->description)
                    <p class="text-base sm:text-lg text-red-100 leading-relaxed max-w-2xl">
                        {{ $category->description }}
                    </p>
                @endif

                <div class="mt-6 flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2 bg-white/20 px-3 py-2 rounded-lg">
                        <i class="fas fa-newspaper text-red-200"></i>
                        <span>{{ $articles->total() }} Artikel</span>
                    </div>
                </div>
            </div>

            <!-- Search Bar in Hero -->
            <div class="lg:col-span-1">
                <form action="{{ route('blog.search') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" placeholder="Cari di kategori ini..."
                        class="flex-1 px-4 py-3 text-sm text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-300 placeholder-gray-500" required>
                    <button type="submit" class="bg-white text-red-600 px-4 py-3 rounded-lg hover:bg-red-50 transition font-semibold">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Main Content with Sidebar -->
<div class="bg-white py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Articles Section (Left - 2 columns) -->
            <div class="lg:col-span-2">
                <!-- Featured Article (1 Latest Article) -->
                @php
                    $featuredArticle = $articles->first();
                @endphp
                @if($featuredArticle)
                    <div class="mb-12">
                        <a href="{{ route('blog.show', $featuredArticle->slug) }}" class="group block">
                            <div class="rounded-lg overflow-hidden mb-4 aspect-video bg-gray-100">
                                @if($featuredArticle->featured_image)
                                    <img src="{{ asset('storage/' . $featuredArticle->featured_image) }}" alt="{{ $featuredArticle->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition" loading="lazy">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-500 flex items-center justify-center">
                                        <i class="fas fa-image text-6xl text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <span class="inline-block bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold">{{ $featuredArticle->category->name }}</span>
                                </div>
                                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 group-hover:text-red-600 transition leading-tight">
                                    {{ $featuredArticle->title }}
                                </h2>
                                <p class="text-gray-600 text-base leading-relaxed">
                                    {{ $featuredArticle->excerpt ?? Str::limit(strip_tags($featuredArticle->content), 150) }}
                                </p>
                                <div class="flex items-center gap-4 text-sm text-gray-500 pt-2">
                                    <span>{{ $featuredArticle->user->name }}</span>
                                    <span>•</span>
                                    <span>{{ $featuredArticle->published_at->format('d M Y') }}</span>
                                    @if($featuredArticle->read_time)
                                        <span>•</span>
                                        <span><i class="fas fa-clock mr-1"></i>{{ $featuredArticle->read_time }} min</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="border-t-2 border-gray-300 pt-12 mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Artikel Lainnya</h3>
                    </div>
                @endif

                <!-- Article Grid (3 Columns) -->
                @php
                    $gridArticles = $featuredArticle ? $articles->skip(1) : $articles;
                @endphp
                @if($gridArticles->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                        @foreach($gridArticles as $article)
                            <a href="{{ route('blog.show', $article->slug) }}" class="group">
                                <div class="rounded-lg overflow-hidden mb-3 aspect-video bg-gray-100">
                                    @if($article->featured_image)
                                        <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition" loading="lazy">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-500 flex items-center justify-center">
                                            <i class="fas fa-image text-3xl text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="font-bold text-gray-900 group-hover:text-red-600 transition line-clamp-2 mb-2">
                                    {{ $article->title }}
                                </h3>
                                <p class="text-xs text-gray-600 mb-3">
                                    {{ $article->published_at->format('d M Y') }}
                                </p>
                                <p class="text-xs text-gray-600 group-hover:text-red-600 transition">
                                    <i class="fas fa-eye mr-1"></i>{{ number_format($article->views_count ?? 0) }} dibaca
                                </p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Belum ada artikel di kategori ini.</p>
                    </div>
                @endif

                <!-- Pagination -->
                @if($articles->hasPages())
                    <div class="flex justify-center mt-12">
                        {{ $articles->links() }}
                    </div>
                @endif
            </div>

            <!-- Right Sidebar -->
            <aside class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    <!-- Advertisement Top -->
                    <div class="bg-gray-200 rounded-lg overflow-hidden border border-gray-200 min-h-80 flex items-center justify-center">
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
                        <div class="bg-white rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Artikel Populer</h3>
                            <div class="space-y-4">
                                @foreach($popularArticles as $popular)
                                    <a href="{{ route('blog.show', $popular->slug) }}" class="group flex gap-3 pb-4 border-b border-gray-200 last:border-0 last:pb-0">
                                        <div class="flex-shrink-0 w-20 h-20">
                                            @if($popular->featured_image)
                                                <img src="{{ asset('storage/' . $popular->featured_image) }}" alt=""
                                                    class="w-20 h-20 object-cover rounded group-hover:opacity-80 transition" loading="lazy">
                                            @else
                                                <div class="w-20 h-20 bg-gray-300 rounded flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400 text-lg"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-sm text-gray-900 group-hover:text-red-600 line-clamp-2">{{ $popular->title }}</p>
                                            <p class="text-xs text-gray-500 mt-2">
                                                <i class="fas fa-eye mr-1"></i>{{ number_format($popular->views_count ?? 0) }} dibaca
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Newsletter -->
                    <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-lg p-6 text-white">
                        <h3 class="text-lg font-bold mb-2">Tetap Update</h3>
                        <p class="text-sm text-red-100 mb-4">Dapatkan {{ $category->name }} langsung ke email</p>
                        <x-newsletter-form placeholder="Email Anda" buttonText="Berlangganan" />
                    </div>

                    <!-- Advertisement Bottom -->
                    <div class="bg-gray-200 rounded-lg overflow-hidden border border-gray-200 min-h-80 flex items-center justify-center">
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
                        <div class="bg-white rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Tag Populer</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($popularTags as $tag)
                                    <a href="{{ route('blog.tag', $tag->slug) }}"
                                        class="bg-gray-100 text-gray-700 px-2.5 py-1.5 rounded text-xs hover:bg-red-100 hover:text-red-700 transition">
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
            alert('Berlangganan berhasil!');
            this.email = '';
        }
    })
    .catch(() => {
        this.loading = false;
        alert('Terjadi kesalahan');
    });
}
</script>
@endsection
