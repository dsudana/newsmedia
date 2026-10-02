@extends('layouts.app-modern')

@section('title', 'NEWSMEDIA - Professional News Portal')

@section('content')
    <!-- Skip to main content link for accessibility -->
   
    <!-- Breaking News Carousel & Trending Section -->
    <div class="max-w-7xl mx-auto px-4 mt-6">
        <x-breaking-news-carousel :announcements="$announcements" :articles="$latestArticles" />
        <x-trending-section :categories="$categories" />
    </div>

    <!-- Advertisement Banner -->
    <div class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 py-6 mb-6">
        <div class="max-w-7xl mx-auto px-4">
            <x-advertisement placement="header_banner" />
        </div>
    </div>

    <!-- Main Content -->
    <main class="bg-white dark:bg-slate-900 transition-colors duration-300">
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
                            src="/images/default.jpg"
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

                        <div class="mt-3 flex items-center gap-3 text-xs text-slate-300">
                            <time>
                                {{ $featured->published_at?->format('M d, Y') }}
                            </time>

                            @if($featured->read_time)
                                <span>•</span>
                                <span>{{ $featured->read_time }} min read</span>
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
                        class="flex h-full overflow-hidden rounded-xl bg-slate-50 shadow-sm ring-1 ring-slate-200/70 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md dark:bg-slate-800 dark:ring-slate-700"
                    >

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
                                    src="/images/default.jpg"
                                    alt="{{ $article->title }}"
                                    loading="lazy"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                >

                            @endif

                        </div>


                        {{-- Content --}}
                        <div class="flex min-w-0 flex-1 flex-col justify-between p-3 sm:p-4">

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

                            <div class="mt-2 flex items-center gap-2 text-[10px] text-slate-500 dark:text-slate-400">

                                <time>
                                    {{ $article->published_at?->format('M d, Y') }}
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

            <!-- Latest News Grid (Full Width, 4 Columns) -->
            <section class="mb-12">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Latest News</h2>
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
                                    <img src="/images/default.jpg" alt="{{ $article->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                @endif
                            </div>
                            <div class="flex-1 flex flex-col">
                                <span class="text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wide mb-2">
                                    {{ $article->category->name }}
                                </span>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition mb-3">
                                    {{ $article->title }}
                                </h3>
                                <p class="text-xs text-slate-600 dark:text-slate-400">
                                    {{ $article->published_at->format('M d, Y') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Content (2/3) -->
                <div class="lg:col-span-2 space-y-12">
                    <!-- Category/News Update Section -->
                    <section>
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                News Update
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
                                    <div class="relative overflow-hidden rounded-xl h-56 mb-4 bg-slate-200 dark:bg-slate-700">
                                        @if ($article->featured_image)
                                            <picture>
                                                <source media="(min-width: 1024px)" srcset="{{ asset('storage/' . $article->featured_image) }}?w=400&q=80" width="400" height="300">
                                                <source media="(min-width: 640px)" srcset="{{ asset('storage/' . $article->featured_image) }}?w=300&q=75" width="300" height="225">
                                                <img src="{{ asset('storage/' . $article->featured_image) }}?w=250&q=70" alt="{{ $article->title }}" loading="lazy" width="250" height="188" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                            </picture>
                                        @else
                                            <img src="/images/default.jpg" alt="{{ $article->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
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
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-auto">
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
                                <h2 class="text-3xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    {{ $category->name }}
                                    <span class="text-red-600 text-2xl">›</span>
                                </h2>
                                <a href="{{ route('blog.category', $category->slug) }}" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-2xl transition">
                                    ›
                                </a>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @foreach ($categoryArticles->take(3) as $article)
                                    <a href="{{ route('blog.show', $article->slug) }}" class="group flex flex-col h-full hover:opacity-85 transition">
                                        <!-- Article Card with Image -->
                                        <div class="relative overflow-hidden rounded-xl h-56 mb-4 bg-slate-200 dark:bg-slate-700">
                                            @if ($article->featured_image)
                                                <picture>
                                                    <source media="(min-width: 1024px)" srcset="{{ asset('storage/' . $article->featured_image) }}?w=400&q=80" width="400" height="300">
                                                    <source media="(min-width: 640px)" srcset="{{ asset('storage/' . $article->featured_image) }}?w=300&q=75" width="300" height="225">
                                                    <img src="{{ asset('storage/' . $article->featured_image) }}?w=250&q=70" alt="{{ $article->title }}" loading="lazy" width="250" height="188" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                                </picture>
                                            @else
                                                <img src="/images/default.jpg" alt="{{ $article->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
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
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-auto">
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
                    @endforeach
                </div>

                <!-- Right Sidebar (1/3) -->
                <aside class="space-y-8">
                    <!-- Social Media Section -->
                    <section class="bg-slate-50 dark:bg-slate-800 rounded-lg p-6 border border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Follow Us</h3>
                        <div class="flex flex-wrap gap-3">
                            @php
                                $socialLinks = \App\Models\SocialMedia::active()->ordered()->get();
                            @endphp
                            @forelse ($socialLinks as $social)
                                <a href="{{ $social->url }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   title="{{ $social->platform }}"
                                   class="w-10 h-10 flex items-center justify-center rounded-lg bg-red-600 hover:bg-red-700 text-white transition duration-300 transform hover:scale-110">
                                    <i class="{{ $social->icon }}"></i>
                                </a>
                            @empty
                                <p class="text-sm text-slate-500 dark:text-slate-400">No social media links available</p>
                            @endforelse
                        </div>
                    </section>

                    <!-- Advertisement Top -->
                    <div
                        class="bg-slate-100 dark:bg-slate-800 rounded-lg p-6 h-80 flex items-center justify-center border border-slate-200 dark:border-slate-700">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-slate-300 dark:bg-slate-600 rounded-lg mx-auto mb-3"></div>
                            <p class="text-slate-600 dark:text-slate-400 font-semibold text-sm">Advertisement</p>
                            <p class="text-slate-500 dark:text-slate-500 text-xs mt-1">300×250</p>
                        </div>
                    </div>

                    <!-- Popular Articles -->
                    <section
                        class="bg-slate-50 dark:bg-slate-800 rounded-lg p-6 border border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                            <span class="w-1 h-6 bg-red-600 rounded-full"></span>
                            Popular
                        </h3>
                        <div class="space-y-5">
                            @foreach ($latestArticles->skip(11)->take(5) as $article)
                                <a href="{{ route('blog.show', $article->slug) }}"
                                    class="group flex gap-4 pb-5 border-b border-slate-200 dark:border-slate-700 last:pb-0 last:border-0 hover:opacity-75 transition">
                                    <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
                                        <img src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : '/images/default.jpg' }}"
                                            alt="{{ $article->title }}" loading="lazy" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4
                                            class="text-sm font-bold text-slate-900 dark:text-white line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition mb-1">
                                            {{ $article->title }}
                                        </h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            {{ $article->published_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>

                    <!-- Newsletter -->
                    <section class="bg-gradient-to-br from-red-600 to-red-700 rounded-lg p-6 text-white">
                        <h3 class="text-lg font-bold mb-2">Newsletter</h3>
                        <p class="text-sm text-red-100 mb-4">Get the latest stories delivered to your inbox daily</p>

                        <x-newsletter-form placeholder="your@email.com" buttonText="Subscribe" />
                    </section>

                    <!-- Advertisement Bottom -->
                    <div
                        class="bg-slate-100 dark:bg-slate-800 rounded-lg p-6 h-80 flex items-center justify-center border border-slate-200 dark:border-slate-700">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-slate-300 dark:bg-slate-600 rounded-lg mx-auto mb-3"></div>
                            <p class="text-slate-600 dark:text-slate-400 font-semibold text-sm">Advertisement</p>
                            <p class="text-slate-500 dark:text-slate-500 text-xs mt-1">300×250</p>
                        </div>
                    </div>

                    <!-- Categories -->
                    <section
                        class="bg-slate-50 dark:bg-slate-800 rounded-lg p-6 border border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                            <span class="w-1 h-6 bg-red-600 rounded-full"></span>
                            Categories
                        </h3>
                        <div class="space-y-2">
                            @php
                                $categories = ['Technology', 'Business', 'Entertainment', 'Sports', 'Health'];
                            @endphp
                            @foreach ($categories as $category)
                                <a href="#"
                                    class="block px-3 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-red-100 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 rounded-lg transition">
                                    {{ $category }}
                                </a>
                            @endforeach
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    </main>
@endsection

