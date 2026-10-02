@extends('layouts.app-modern')

@section('content')

    @if($useBuilder ?? false)
        <!-- Render homebuilder sections -->
        @foreach($sections ?? [] as $item)
            @include("frontend.sections.{$item['section']->section_type}", [
                'section' => $item['section'],
                'data' => $item['data'],
            ])
        @endforeach
    @else
        <!-- Fallback: render current hardcoded homepage -->
        <x-ad-slot placement="home_top" />

        <!-- Featured Article -->
        @if($latestFeatured)
            <section class="mb-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-64 md:h-96 w-full">
                        @if($latestFeatured->featured_image)
                            <img src="{{ featuredImageUrl($latestFeatured->featured_image) }}" alt="{{ $latestFeatured->title }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
                        @endif
                    </div>
                    <div class="p-8">
                        <span
                            class="text-blue-600 font-bold text-sm tracking-wide uppercase">{{ $latestFeatured->category->name ?? 'Uncategorized' }}</span>
                        <h1 class="text-3xl md:text-4xl font-bold mt-2 mb-4 hover:text-blue-800 transition">
                            <a href="{{ route('articles.show', $latestFeatured) }}">
                                {{ $latestFeatured->title }}
                            </a>
                        </h1>
                        <p class="text-gray-600 mb-6 line-clamp-3">
                            {{ $latestFeatured->excerpt ?? Str::limit(strip_tags($latestFeatured->content), 150) }}
                        </p>
                        <div class="flex items-center text-sm text-gray-500">
                            <span class="font-medium mr-2">{{ $latestFeatured->user->name ?? 'Admin' }}</span>
                            <span>&bull;</span>
                            <span
                                class="ml-2">{{ $latestFeatured->published_at ? $latestFeatured->published_at->format('M d, Y') : 'Draft' }}</span>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- Latest News -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6 border-b-2 border-blue-600 inline-block pb-1">Latest News</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($heroSlides as $article)
                    <div class="bg-white rounded shadow hover:shadow-md transition">
                        <div class="h-48 w-full overflow-hidden rounded-t">
                            <a href="{{ route('articles.show', $article) }}">
                                @if($article->featured_image)
                                    <img src="{{ featuredImageUrl($article->featured_image) }}" alt="{{ $article->title }}"
                                        class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
                                @endif
                            </a>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span
                                    class="text-xs font-bold text-blue-600 uppercase">{{ $article->category->name ?? 'News' }}</span>
                                <span
                                    class="text-xs text-gray-500">{{ $article->published_at ? $article->published_at->diffForHumans() : '' }}</span>
                            </div>
                            <h3 class="text-lg font-bold mb-2 leading-tight hover:text-blue-600">
                                <a href="{{ route('articles.show', $article) }}">
                                    {{ Str::limit($article->title, 60) }}
                                </a>
                            </h3>
                            <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 100) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-3">No articles found.</p>
                @endforelse
            </div>
        </section>

        <!-- Category Sections -->
        @foreach($categories as $category)
            @if($category->articles->isNotEmpty())
                <section class="mb-12">
                    <div class="flex justify-between items-end mb-6 border-b border-gray-200 pb-2">
                        <h2 class="text-2xl font-bold border-b-2 border-blue-600 -mb-2.5 pb-2">{{ $category->name }}</h2>
                        <a href="{{ route('categories.show', $category) }}"
                            class="text-blue-600 text-sm font-medium hover:underline">View All &rarr;</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        @foreach($category->articles as $article)
                            <div class="bg-white rounded shadow-sm hover:shadow-md transition">
                                <div class="h-40 w-full overflow-hidden rounded-t">
                                    <a href="{{ route('articles.show', $article) }}">
                                        @if($article->featured_image)
                                            <img src="{{ featuredImageUrl($article->featured_image) }}" alt="{{ $article->title }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
                                        @endif
                                    </a>
                                </div>
                                <div class="p-3">
                                    <h3 class="text-md font-bold mb-1 leading-snug hover:text-blue-600">
                                        <a href="{{ route('articles.show', $article) }}">
                                            {{ Str::limit($article->title, 50) }}
                                        </a>
                                    </h3>
                                    <span
                                        class="text-xs text-gray-500">{{ $article->published_at ? $article->published_at->format('M d') : '' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        @endforeach
    @endif

@endsection
