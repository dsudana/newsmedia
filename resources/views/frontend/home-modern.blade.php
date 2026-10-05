@extends('layouts.app-modern')

@section('title', 'NEWSMEDIA - Professional News Portal')

@section('content')
    <!-- Skip to main content link for accessibility -->
   
    <!-- Breaking News Carousel & Trending Section -->
    <div class="max-w-7xl mx-auto px-4 mt-6">
        <x-frontend.breaking-news-carousel :announcements="$announcements" :articles="$latestArticles" />
        <x-frontend.trending-section :categories="$categories" />
    </div>

    <!-- Advertisement Banner -->
    <div class="bg-white dark:bg-gray-900 border-b border-slate-200 dark:border-gray-800 py-2 sm:py-3 lg:py-4 mb-6 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-gray-200 dark:bg-gray-800 rounded-lg flex items-center justify-center h-14 sm:h-20 lg:h-24 overflow-hidden">
                <x-frontend.advertisement placement="header_banner" />
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <!-- Featured Hero Section (Full Width, 2 Columns) -->
          <div class="mb-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 lg:gap-6 items-stretch">

        {{-- ========================================
            LEFT: FEATURED ARTICLE
        ========================================= --}}
        @php
            $featured = $latestArticles->first();
        @endphp

        @if($featured)
            <article class="group h-[380px] lg:h-[420px]">
                <a
                    href="{{ route('blog.show', $featured->slug) }}"
                    class="relative block h-full overflow-hidden rounded-2xl bg-slate-200 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                >

                    {{-- Featured Image --}}
                    @if($featured->featured_image)
                        @php
                            $imageUrl = str_starts_with($featured->featured_image, 'http')
                                ? $featured->featured_image
                                : asset('storage/' . $featured->featured_image);
                        @endphp

                        <img
                            src="{{ $imageUrl }}"
                            alt="{{ $featured->title }}"
                            loading="lazy"
                            width="800"
                            height="420"
                            class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        >
                    @else
                        <img
                            src="/images/placeholder-news-media.svg"
                            alt="{{ $featured->title }}"
                            loading="lazy"
                            width="800"
                            height="420"
                            class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        >
                    @endif

                    {{-- Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>

                    {{-- Content --}}
                    <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6">

                        @if($featured->category)
                            <span class="mb-2 inline-flex rounded-full bg-red-600 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-white">
                                {{ $featured->category->name }}
                            </span>
                        @endif

                        <h2 class="line-clamp-3 text-2xl font-bold leading-tight text-white sm:text-3xl">
                            {{ $featured->title }}
                        </h2>

                        <div class="mt-3 flex items-center gap-3 text-sm text-slate-400">
                            <time>
                                {{ \App\Helpers\DateHelper::relativeTime($featured->published_at) }}
                            </time>

                            @if($featured->read_time)
                                <span>•</span>
                                <span>{{ $featured->read_time }} menit baca</span>
                            @endif
                        </div>

                    </div>
                </a>
            </article>
        @endif


        {{-- ========================================
            RIGHT: 3 ARTICLES
        ========================================= --}}
        <div class="grid grid-rows-3 gap-3 lg:h-[420px]">

            @foreach($latestArticles->skip(1)->take(3) as $article)

                <article class="group min-h-0">
                    <a
                        href="{{ route('blog.show', $article->slug) }}"
                        class="flex h-full overflow-hidden rounded-xl dark:bg-gray-800 shadow-sm ring-1 ring-slate-200/70 dark:ring-gray-700/70 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md dark:hover:shadow-lg"                    >

                        {{-- Image --}}
                        <div class="relative w-32 flex-shrink-0 overflow-hidden sm:w-36 lg:w-40">

                            @if($article->featured_image)

                                @php
                                    $imgUrl = str_starts_with($article->featured_image, 'http')
                                        ? $article->featured_image
                                        : asset('storage/' . $article->featured_image);
                                @endphp

                                <img
                                    src="{{ $imgUrl }}"
                                    alt="{{ $article->title }}"
                                    loading="lazy"
                                    width="400"
                                    height="250"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                >

                            @else

                                <img
                                    src="/images/placeholder-news-media.svg"
                                    alt="{{ $article->title }}"
                                    loading="lazy"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                >

                            @endif

                        </div>


                        {{-- Content --}}
                        <div class="flex min-w-0 flex-1 flex-col justify-between p-3 sm:p-4 ">

                            <div>

                                @if($article->category)
                                    <span class="text-[9px] font-bold uppercase tracking-wider text-red-600 dark:text-red-400">
                                        {{ $article->category->name }}
                                    </span>
                                @endif

                                <h3 class="mt-1.5 line-clamp-2 text-sm font-bold leading-snug text-slate-900 transition-colors group-hover:text-red-600 sm:text-base dark:text-white dark:group-hover:text-red-400">
                                    {{ $article->title }}
                                </h3>

                            </div>

                            <div class="mt-2 flex items-center gap-2 text-[10px] text-slate-500 dark:text-gray-400">

                                <time>
                                    {{ \App\Helpers\DateHelper::relativeTime($article->published_at) }}
                                </time>

                                @if($article->read_time)
                                    <span>•</span>
                                    <span>{{ $article->read_time }} min</span>
                                @endif

                            </div>

                        </div>

                    </a>
                </article>

            @endforeach

        </div>

    </div>
</div>

            <!-- Berita Terbaru Grid (Full Width, 4 Columns) -->
            <section class="mb-12">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Berita Terbaru</h2>
                    <div class="h-1 w-16 bg-gradient-to-r from-red-600 to-red-400 mt-3 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($latestArticles->skip(3)->take(4) as $article)
                        <a href="{{ route('blog.show', $article->slug) }}"
                            class="group flex flex-col h-full hover:opacity-85 transition">
                            <div class="relative overflow-hidden rounded-lg h-36 mb-4">
                                @if ($article->featured_image)
                                    @php $imgUrl = str_starts_with($article->featured_image, 'http') ? $article->featured_image : asset('storage/' . $article->featured_image); @endphp
                                    <picture>
                                        <source media="(min-width: 1024px)" srcset="{{ $imgUrl }}" width="400" height="300">
                                        <source media="(min-width: 640px)" srcset="{{ $imgUrl }}" width="300" height="225">
                                        <img src="{{ $imgUrl }}" alt="{{ $article->title }}" loading="lazy" width="250" height="188" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    </picture>
                                @else
                                    <img src="/images/placeholder-news-media.svg" alt="{{ $article->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                @endif
                            </div>
                            <div class="flex-1 flex flex-col">
                                <span class="text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wide mb-2">
                                    {{ $article->category->name }}
                                </span>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition mb-3">
                                    {{ $article->title }}
                                </h3>
                                <p class="text-sm text-slate-700 dark:text-gray-400">
                                    {{ \App\Helpers\DateHelper::relativeTime($article->published_at) }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Content (2/3) -->
                <div class="lg:col-span-2 space-y-12">
                    <!-- Category/Update Berita Section -->
                    <section>
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                Update Berita
                                <span class="text-red-600 text-2xl">›</span>
                            </h2>
                            <a href="#" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-2xl transition">
                                ›
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach ($latestArticles->skip(7)->take(3) as $article)
                                <a href="{{ route('blog.show', $article->slug) }}" class="group flex flex-col h-full hover:opacity-85 transition">
                                    <!-- Article Card with Image -->
                                    <div class="relative overflow-hidden rounded-xl h-56 mb-4 bg-slate-200 dark:bg-gray-700">
                                        @if ($article->featured_image)
                                            <picture>
                                                <source media="(min-width: 1024px)" srcset="{{ asset('storage/' . $article->featured_image) }}?w=400&q=80" width="400" height="300">
                                                <source media="(min-width: 640px)" srcset="{{ asset('storage/' . $article->featured_image) }}?w=300&q=75" width="300" height="225">
                                                <img src="{{ asset('storage/' . $article->featured_image) }}?w=250&q=70" alt="{{ $article->title }}" loading="lazy" width="250" height="188" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                            </picture>
                                        @else
                                            <img src="/images/placeholder-news-media.svg" alt="{{ $article->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                        @endif

                                        <!-- Overlay with category badge -->
                                        <div class="absolute top-3 left-3 right-3">
                                            <span class="inline-block bg-red-600/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full">
                                                {{ $article->category->name }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 flex flex-col">
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white line-clamp-3 group-hover:text-red-600 dark:group-hover:text-red-400 transition mb-2">
                                            {{ $article->title }}
                                        </h3>
                                        <p class="text-sm text-slate-600 dark:text-gray-400 mt-auto">
                                            @php
                                                $minutes = (int) abs($article->published_at->diffInMinutes(now()));
                                                if ($minutes < 60) {
                                                    echo $minutes . ' menit lalu';
                                                } elseif ($minutes < 1440) {
                                                    echo floor($minutes / 60) . ' jam lalu';
                                                } else {
                                                    echo $article->published_at->format('d M Y');
                                                }
                                            @endphp
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>

                    <!-- Video Pilihan Section -->
                    @if($videoGallery && $videoGallery->count() > 0)
                        <section class="bg-gradient-to-br from-slate-800 to-slate-900 dark:from-gray-800 dark:to-gray-900 rounded-xl p-8 shadow-lg mb-12">
                            <div class="flex items-center justify-between mb-8">
                                <h2 class="text-3xl font-bold text-white flex items-center gap-2">
                                    Video Pilihan
                                    <span class="text-red-600 text-2xl">›</span>
                                </h2>
                                <a href="{{ route('gallery.index') }}" class="text-red-600 hover:text-red-500 text-2xl transition">
                                    ›
                                </a>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @foreach($videoGallery->take(3) as $video)
                                    <a href="{{ $video->youtube_url }}" target="_blank" rel="noopener noreferrer" class="group">
                                        <x-video-card-minimal :$video />
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    <!-- Upcoming Events Carousel -->
                    <x-frontend.upcoming-events :events="$upcomingEvents" />

                    <!-- Category Sections -->
                    @php
                        // Get all active categories with their articles
                        $allCategories = \App\Models\Category::active()
                            ->with(['articles' => fn($q) => $q->published()->latest('published_at')->take(3)])
                            ->whereHas('articles', fn($q) => $q->published())
                            ->get();
                    @endphp
                    @foreach ($allCategories as $category)
                        @php $categoryArticles = $category->articles; @endphp
                        <section>
                            <div class="flex items-center justify-between mb-8">
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    {{ $category->name }}
                                    <span class="text-red-600 text-2xl">›</span>
                                </h2>
                                <a href="{{ route('blog.category', $category->slug) }}" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-2xl transition">
                                    ›
                                </a>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @foreach ($categoryArticles->take(3) as $article)
                                    <a href="{{ route('blog.show', $article->slug) }}" class="group flex flex-col h-full">
                                        <!-- Article Card with Image 16:9 -->
                                        <div class="relative overflow-hidden rounded-xl aspect-video mb-4 bg-slate-200 dark:bg-gray-700">
                                            @if ($article->featured_image)
                                                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-slate-300 to-slate-400 flex items-center justify-center">
                                                    <i class="fas fa-image text-slate-500 text-4xl"></i>
                                                </div>
                                            @endif

                                            
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1 flex flex-col">
                                            <!-- Overlay with category badge -->
                                           
                                                <span class="inline-block text-red-600 text-xs font-semibold py-1.5 rounded-sm uppercase">
                                                    {{ $article->category->name }}
                                                </span>
                                          
                                            <h3 class="text-base font-bold text-slate-900 dark:text-white line-clamp-3 group-hover:text-red-600 dark:group-hover:text-red-400 transition mb-2">
                                                {{ $article->title }}
                                            </h3>
                                            <p class="text-sm text-slate-600 dark:text-gray-400 mt-auto">
                                                {{ $article->published_at->format('d M Y') }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>

                <!-- Right Sidebar (1/3) -->
                <aside class="space-y-8">
                    <!-- Social Media Section -->
                    <x-sidebar.social-links title="Follow Us" />

                    <!-- Advertisement Top -->
                    <div class=" dark:bg-gray-800 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 min-h-80 flex items-center justify-center">
                        <x-frontend.advertisement placement="sidebar_home_top" />
                        
                    </div>

                    <!-- Latest Articles (Note: labeled "Popular" but actually Recent) -->
                    <x-sidebar.popular-articles :articles="$sidebarArticles" :limit="5" :showThumbnail="true" :showDate="true">
                        Popular
                    </x-sidebar.popular-articles>

                    <!-- Newsletter -->
                    <x-sidebar.newsletter-card
                        title="Newsletter"
                        subtitle="Get the latest stories delivered to your inbox daily"
                        placeholder="your@email.com"
                        buttonText="Subscribe"
                    />

                    <!-- Advertisement Bottom -->
                    <div class=" dark:bg-gray-800 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 min-h-80 flex items-center justify-center">
                        <x-frontend.advertisement placement="sidebar_home_bottom" />
                        
                    </div>

                    <!-- Categories -->
                    <x-sidebar.categories :categories="$sidebarCategories" route="blog.category" :showCount="true">
                        Categories
                    </x-sidebar.categories>
                </aside>
            </div>
        </div>
    </main>
@endsection

