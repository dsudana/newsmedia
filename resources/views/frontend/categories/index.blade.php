@extends('layouts.app-modern')

@section('title', 'Indeks Berita - NEWSMEDIA')

@section('content')
<main class="bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Page Header -->
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-4">📑 Indeks Berita</h1>
            <p class="text-lg text-slate-600 dark:text-gray-400">Jelajahi semua kategori berita kami</p>
        </div>

        <!-- Categories Grid -->
        @php
            $categories = \App\Models\Category::withCount(['articles' => fn($q) => $q->where('status', 'published')])->get();
        @endphp
        @if($categories->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('blog.category', $category->slug) }}" class="group">
                        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-lg p-6 border border-red-200 dark:border-red-800/30 hover:shadow-lg dark:hover:shadow-red-900/20 transition-all h-full flex flex-col">
                            <!-- Category Icon/Badge -->
                            <div class="w-12 h-12 bg-red-600 dark:bg-red-700 rounded-lg flex items-center justify-center text-white text-xl mb-4 group-hover:scale-110 transition-transform">
                                @switch($category->name)
                                    @case('Gaya Hidup')
                                        👗
                                    @break
                                    @case('Pemerintahan')
                                        🏛️
                                    @break
                                    @case('Pendidikan')
                                        📚
                                    @break
                                    @case('Kesehatan')
                                        🏥
                                    @break
                                    @case('Bisnis')
                                        💼
                                    @break
                                    @case('Olahraga')
                                        ⚽
                                    @break
                                    @default
                                        📰
                                @endswitch
                            </div>

                            <!-- Category Name -->
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition">
                                {{ $category->name }}
                            </h3>

                            <!-- Article Count -->
                            <p class="text-sm text-slate-600 dark:text-gray-400 mb-4 flex-1">
                                {{ $category->articles_count ?? 0 }} artikel
                            </p>

                            <!-- Arrow -->
                            <div class="text-red-600 dark:text-red-400 group-hover:translate-x-1 transition-transform">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-slate-50 dark:bg-gray-800 rounded-lg">
                <p class="text-slate-600 dark:text-gray-400 text-lg">Belum ada kategori tersedia</p>
            </div>
        @endif
    </div>
</main>
@endsection
