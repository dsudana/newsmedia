@extends('layouts.app-modern')

@section('title', 'Video Gallery - NEWSMEDIA')

@section('content')
    <div x-data="{ selectedVideo: null }">
        <!-- Video Modal Component -->
        <x-video-modal modalName="galleryVideoModal" />

        <!-- Page Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 py-12 mb-12">
            <div class="max-w-6xl mx-auto px-4">
                <h1 class="text-4xl font-bold text-white mb-2">Video Gallery</h1>
                <p class="text-red-100">Discover the latest videos from our newsroom</p>
            </div>
        </div>

        <main class="max-w-6xl mx-auto px-4 pb-12">
            <!-- Filter & Sort Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-8">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category Filter -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Category
                        </label>
                        <select id="category" name="category_id"
                            class="w-full px-4 py-2 border border-gray-400 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort Filter -->
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Sort By
                        </label>
                        <select id="sort" name="sort"
                            class="w-full px-4 py-2 border border-gray-400 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="newest" @selected(request('sort') === 'newest' || !request('sort'))>Newest</option>
                            <option value="popular" @selected(request('sort') === 'popular')>Most Popular</option>
                            <option value="oldest" @selected(request('sort') === 'oldest')>Oldest</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="md:col-span-2">
                        <button type="submit"
                            class="w-full md:w-auto bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-2 rounded-lg transition">
                            Filter Videos
                        </button>
                        <a href="{{ route('gallery.index') }}"
                            class="ml-3 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white text-sm">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            <!-- Active Filters Display -->
            @if (request('category_id') || (request('sort') && request('sort') !== 'newest'))
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-300">
                        <strong>Filters:</strong>
                        @if (request('category_id'))
                            Category: {{ $categories->find(request('category_id'))?->name ?? 'Unknown' }}
                            @if (request('sort') && request('sort') !== 'newest')
                                •
                            @endif
                        @endif
                        @if (request('sort') && request('sort') !== 'newest')
                            Sort: {{ ucfirst(request('sort')) }}
                        @endif
                    </p>
                </div>
            @endif

            <!-- Videos Grid -->
            @if ($videos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    @foreach ($videos as $video)
                        @if ($video->youtube_id)
                            <button type="button"
                                @click="
                                    selectedVideo = {
                                        id: {{ $video->id }},
                                        title: @js($video->title),
                                        youtube_id: @js($video->youtube_id),
                                        youtube_url: @js($video->youtube_url),
                                        description: @js($video->description),
                                        category: @js($video->category?->name),
                                        views_count: {{ $video->views_count }},
                                        published_at: @js($video->published_at?->format('d M Y'))
                                    };
                                    $dispatch('open-modal', 'galleryVideoModal');
                                "
                                class="w-full text-left stagger-item">
                                <x-video-card :$video />
                            </button>
                        @endif
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $videos->links('pagination::tailwind') }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-12 text-center">
                    <i class="fas fa-video text-6xl text-gray-300 dark:text-gray-600 mb-4 block"></i>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Videos Found</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Try adjusting your filters or check back later for new content.
                    </p>
                    <a href="{{ route('gallery.index') }}"
                        class="inline-block bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                        Reset Filters
                    </a>
                </div>
            @endif
        </main>
    </div>
@endsection
