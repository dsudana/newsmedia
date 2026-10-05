@extends('layouts.app-modern')

@section('content')
    <!-- Advertisement Section (Full Width) -->
    <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 py-2 sm:py-3 lg:py-4 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-200 dark:bg-gray-800 rounded-lg flex items-center justify-center h-14 sm:h-20 lg:h-24 overflow-hidden">
                <x-frontend.advertisement placement="header_banner" />
            </div>
        </div>
    </div>

    <!-- Hero Section with Breadcrumb -->
    <div class="bg-gradient-to-r from-red-600 via-red-600 to-red-700 text-white py-4 md:py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-red-100 mb-6 text-xs sm:text-sm">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                @if ($category ?? null)
                    <span class="text-red-300">/</span>
                    <span class="text-white font-semibold">{{ $category->name }}</span>
                @else
                    <span class="text-red-300">/</span>
                    <span class="text-white font-semibold">{{ $title ?? 'Semua Artikel' }}</span>
                @endif
            </nav>

            <!-- Hero Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <div class="lg:col-span-2">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 leading-tight">
                        @if ($category ?? null)
                            {{ $category->name }}
                        @else
                            {{ $title ?? 'Semua Artikel' }}
                        @endif
                    </h1>
                    @if ($category ?? null)
                        @if ($category->description)
                            <p class="text-base sm:text-lg text-red-50 leading-relaxed max-w-2xl">
                                {{ $category->description }}
                            </p>
                        @endif
                        <div class="mt-6 flex items-center gap-4 text-sm">
                            <div
                                class="flex items-center gap-2 bg-white/20 px-3 py-2 rounded-lg hover:bg-white/30 transition">
                                <i class="fas fa-newspaper text-red-100"></i>
                                <span>{{ $articles->total() }} Artikel</span>
                            </div>
                        </div>
                    @else
                        <p class="text-base sm:text-lg text-red-50 leading-relaxed">
                            Temukan berita terbaru dan cerita menarik dari publikasi kami
                        </p>
                    @endif
                </div>

                <!-- Search Bar in Hero -->
                <div class="lg:col-span-1">
                    <form action="{{ route('blog.search') }}" method="GET" class="flex gap-2">
                        <input type="text" name="search" placeholder="Cari artikel..."
                            class="flex-1 px-4 py-3 text-sm text-gray-900 rounded-lg border-2 border-white/40 focus:outline-none focus:ring-2 focus:ring-white focus:border-white bg-white/95 hover:bg-white transition placeholder-gray-600"
                            required>
                        <button type="submit"
                            class="bg-white text-red-600 px-4 py-3 rounded-lg hover:bg-red-50 transition font-semibold border-2 border-white hover:border-red-50 shadow-lg hover:shadow-xl">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white dark:bg-gray-900 py-8 sm:py-12 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Articles Section (Left - 2 columns) -->
                <div class="lg:col-span-2">
                    <!-- Featured Article (1st Article) -->
                    @if ($articles->count() > 0)
                        @php $featuredArticle = $articles->first(); @endphp
                        <div class="mb-12">
                            <a href="{{ route('blog.show', $featuredArticle->slug) }}" class="group block">
                                <div class="rounded-lg overflow-hidden mb-4 aspect-video bg-gray-100">
                                    @if ($featuredArticle->featured_image)
                                        <img src="{{ asset('storage/' . $featuredArticle->featured_image) }}"
                                            alt="{{ $featuredArticle->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition"
                                            loading="lazy">
                                    @else
                                        <img src="/images/default.jpg" alt="{{ $featuredArticle->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition"
                                            loading="lazy">
                                    @endif
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <span
                                            class="inline-block bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold">
                                            {{ $featuredArticle->category->name }}
                                        </span>
                                    </div>
                                    <h2
                                        class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white group-hover:text-red-600 transition leading-tight">
                                        {{ $featuredArticle->title }}
                                    </h2>
                                    <p class="text-gray-600 text-base leading-relaxed">
                                        {{ $featuredArticle->excerpt ?? Str::limit(strip_tags($featuredArticle->content), 150) }}
                                    </p>
                                    <div class="flex items-center gap-4 text-sm text-gray-500 pt-2">
                                        <span>{{ $featuredArticle->user->name }}</span>
                                        <span>•</span>
                                        <span>{{ $featuredArticle->published_at->format('d M Y') }}</span>
                                        @if ($featuredArticle->read_time)
                                            <span>•</span>
                                            <span><i class="fas fa-clock mr-1"></i>{{ $featuredArticle->read_time }}
                                                min</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>

                        @if ($articles->count() > 1)
                            <div class="border-t-2 border-gray-300 pt-12 mb-8">
                                <h3 class="text-xl font-bold text-gray-900 mb-6">Artikel Lainnya</h3>
                            </div>

                            <!-- Article Grid (3 Columns) -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                                @foreach ($articles->slice(1) as $article)
                                    <a href="{{ route('blog.show', $article->slug) }}" class="group">
                                        <div class="rounded-lg overflow-hidden mb-3 aspect-video bg-gray-100">
                                            @if ($article->featured_image)
                                                <img src="{{ asset('storage/' . $article->featured_image) }}"
                                                    alt="{{ $article->title }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition"
                                                    loading="lazy">
                                            @else
                                                <img src="/images/default.jpg" alt="{{ $article->title }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition"
                                                    loading="lazy">
                                            @endif
                                        </div>
                                        <h3
                                            class="text-base font-bold text-slate-900 dark:text-white line-clamp-3 group-hover:text-red-600 dark:group-hover:text-red-400 transition mb-2">
                                            {{ $article->title }}
                                        </h3>
                                        <p class="text-xs text-gray-400 mb-3">
                                            {{ $article->published_at->format('d M Y') }}
                                        </p>
                                        <p class="text-xs text-gray-400 group-hover:text-red-600 transition">
                                            <i class="fas fa-eye mr-1"></i>{{ number_format($article->views_count ?? 0) }}
                                            dibaca
                                        </p>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="bg-gray-50 rounded-lg p-12 text-center">
                            <i class="fas fa-search text-5xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak ada artikel</h3>
                            <p class="text-gray-600 mb-6">
                                @if (request('search'))
                                    Kami tidak menemukan artikel yang cocok dengan "{{ request('search') }}"
                                @elseif($category ?? null)
                                    Belum ada artikel di kategori ini
                                @else
                                    Tidak ada artikel yang tersedia
                                @endif
                            </p>
                            <a href="{{ route('blog.index') }}"
                                class="inline-block bg-red-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-red-700 transition">
                                Kembali ke Semua Artikel
                            </a>
                        </div>
                    @endif

                    <!-- Pagination -->
                    @if ($articles->hasPages())
                        <div class="flex justify-center mt-12">
                            {{ $articles->links('layouts.partials.pagination') }}
                        </div>
                    @endif
                </div>

                <!-- Right Sidebar -->
                <aside class="lg:col-span-1">
                    <div class="sticky top-24 space-y-8">
                        <!-- Social Media Section -->
                        <x-sidebar.social-links title="Ikuti Kami" />

                        <!-- Advertisement Top -->
                        <div
                            class="bg-gray-200 dark:bg-gray-800 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-md transition-shadow duration-300 min-h-80 flex items-center justify-center">
                            <x-frontend.advertisement placement="sidebar_top" />

                        </div>

                        <!-- Recent Articles -->
                        @php
                            $recentArticles = isset($category)
                                ? \App\Models\Article::published()
                                    ->where('category_id', $category->id)
                                    ->latest('published_at')
                                    ->take(5)
                                    ->get()
                                : \App\Models\Article::published()->latest('published_at')->take(5)->get();
                        @endphp
                        @if ($recentArticles && $recentArticles->count() > 0)
                            <section
                                class="dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-md transition-shadow duration-300">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                                    <span class="w-1 h-6 bg-red-600 dark:bg-red-500 rounded-full"></span>
                                    Artikel Terbaru
                                </h3>
                                <div class="space-y-5">
                                    @foreach ($recentArticles as $article)
                                        <a href="{{ route('blog.show', $article->slug) }}"
                                            class="group flex gap-4 pb-5 border-b border-gray-200 dark:border-gray-700 last:pb-0 last:border-0 hover:opacity-75 transition">
                                            <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
                                                <img src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : '/images/default.jpg' }}"
                                                    alt="{{ $article->title }}" loading="lazy"
                                                    class="w-full h-full object-cover">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4
                                                    class="text-base font-bold text-slate-900 dark:text-white line-clamp-3 group-hover:text-red-600 dark:group-hover:text-red-400 transition mb-1">
                                                    {{ $article->title }}
                                                </h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $article->published_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </section>
                        @endif

                        <!-- Popular Articles (by views count) -->
                        <x-sidebar.popular-articles :articles="$sidebarArticles" :limit="5" :showThumbnail="true" :showDate="false"
                            :showViews="true">
                            Artikel Populer
                        </x-sidebar.popular-articles>

                        <!-- Newsletter -->
                        <x-sidebar.newsletter-card title="Tetap Update" subtitle="Dapatkan berita terbaru langsung ke email"
                            placeholder="Email Anda" buttonText="Berlangganan" />

                        <!-- Advertisement Bottom -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-md transition-shadow duration-300 min-h-80 flex items-center justify-center">
                            <x-frontend.advertisement placement="sidebar_bottom" />

                        </div>

                        <!-- Categories (Only with articles) -->
                        <x-sidebar.categories :categories="$categories" :activeId="$category?->id" route="blog.index" :showCount="true">
                            Kategori
                        </x-sidebar.categories>
                    </div>
                </aside>
            </div>
        </div>
    </div>
@endsection
