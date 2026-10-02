@extends('layouts.app-modern')

@section('title', 'Search Results for ' . $query)

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Search Results</h1>
        <p class="text-gray-600 mt-2">Showing results for: <span class="font-bold">"{{ $query }}"</span></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $article)
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
                        <span class="text-xs font-bold text-blue-600 uppercase">{{ $article->category->name }}</span>
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
            <div class="col-span-3 text-center py-10">
                <p class="text-gray-500 text-lg">No articles found matching your criteria.</p>
                <a href="{{ route('home') }}" class="text-blue-600 hover:underline mt-4 inline-block">Back to Home</a>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $articles->appends(['q' => $query])->links() }}
    </div>
@endsection