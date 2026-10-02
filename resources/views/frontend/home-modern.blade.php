@extends('layouts.app-modern')

@section('title', 'NEWSMEDIA - Professional News Portal')

@section('content')
    <!-- Skip to main content link for accessibility -->
    <a href="#main-content" class="skip-to-main">Skip to main content</a>
    <!-- Top Breaking News Bar -->
    @if ($announcements->count() > 0)
        <div class="bg-slate-900 text-white py-3 sticky top-0 z-40">
            <div class="max-w-6xl mx-auto px-6 flex items-center gap-4">
                <span class="bg-red-600 px-3 py-1 rounded text-xs font-bold uppercase tracking-wide">Breaking</span>
                <div class="overflow-hidden flex-1">
                    <div class="animate-marquee whitespace-nowrap text-sm">
                        @foreach ($announcements as $announcement)
                            <span class="mr-16">{{ $announcement->title }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Trending Topics Bar -->
    <div class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 py-3 sticky top-12 z-30" role="region" aria-label="Trending topics">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide whitespace-nowrap shrink-0">Trending Now:</span>
                <div class="overflow-hidden flex-1">
                    <div class="flex gap-8 animate-marquee" role="marquee" aria-live="polite" aria-label="Trending articles">
                        @php
                            $trendingArticles = $latestArticles->take(6);
                        @endphp
                        @foreach ($trendingArticles as $article)
                            <a href="{{ route('blog.show', $article->slug) }}"
                                class="text-sm text-slate-700 dark:text-slate-300 hover:text-red-600 dark:hover:text-red-400 transition whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 rounded px-2 py-1"
                                title="{{ $article->title }}">
                                {{ $article->title }}
                            </a>
                        @endforeach
                        @foreach ($trendingArticles as $article)
                            <a href="{{ route('blog.show', $article->slug) }}"
                                class="text-sm text-slate-700 dark:text-slate-300 hover:text-red-600 dark:hover:text-red-400 transition whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 rounded px-2 py-1"
                                title="{{ $article->title }}"
                                aria-hidden="true">
                                {{ $article->title }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advertisement Banner -->
    <div class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 py-6">
        <div class="max-w-6xl mx-auto px-6">
            <x-advertisement placement="header_banner" />
        </div>
    </div>

    <!-- Main Content -->
    <main class="bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="max-w-6xl mx-auto px-6 py-12">
            <!-- Featured Hero Section (Full Width, 2 Columns) -->
            <div class="mb-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column: 1 Large Article -->
                @php $featured = $latestArticles->first() @endphp
                @if ($featured)
                    <article class="group h-96">
                        <div class="relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition duration-300 h-full">
                            @if ($featured->featured_image)
                                @php
                                    $imageUrl = str_starts_with($featured->featured_image, 'http')
                                        ? $featured->featured_image
                                        : asset('storage/' . $featured->featured_image);
                                @endphp
                                <picture>
                                    <source media="(min-width: 1024px)" srcset="{{ $imageUrl }}" width="800" height="400">
                                    <source media="(min-width: 640px)" srcset="{{ $imageUrl }}" width="600" height="300">
                                    <img src="{{ $imageUrl }}" alt="{{ $featured->title }}" loading="lazy" width="400" height="200" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                </picture>
                            @else
                                <img src="https://via.placeholder.com/1000x500?text={{ urlencode($featured->category->name) }}" alt="{{ $featured->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent"></div>

                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                <div class="mb-3">
                                    <span class="inline-block bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                                        {{ $featured->category->name }}
                                    </span>
                                </div>
                                <h2 class="text-3xl font-bold text-white mb-2 leading-tight line-clamp-3">
                                    {{ $featured->title }}
                                </h2>
                                <div class="flex items-center gap-4 text-xs text-slate-300">
                                    <span>{{ $featured->published_at->format('M d, Y') }}</span>
                                    @if ($featured->read_time)
                                        <span>{{ $featured->read_time }} min read</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @endif

                <!-- Right Column: 2 Articles Stacked -->
                <div class="space-y-4">
                    @foreach ($latestArticles->skip(1)->take(2) as $article)
                        <a href="{{ route('blog.show', $article->slug) }}" class="group flex h-44 hover:opacity-85 transition">
                            <div class="relative overflow-hidden rounded-lg w-40 flex-shrink-0">
                                @if ($article->featured_image)
                                    @php $imgUrl = str_starts_with($article->featured_image, 'http') ? $article->featured_image : asset('storage/' . $article->featured_image); @endphp
                                    <picture>
                                        <source media="(min-width: 640px)" srcset="{{ $imgUrl }}" width="200" height="200">
                                        <img src="{{ $imgUrl }}" alt="{{ $article->title }}" loading="lazy" width="150" height="150" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    </picture>
                                @else
                                    <img src="https://via.placeholder.com/300x300?text={{ urlencode($article->category->name ?? 'News') }}" alt="{{ $article->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                @endif
                            </div>
                            <div class="flex-1 p-4 bg-slate-50 dark:bg-slate-800 rounded-r-lg flex flex-col justify-between">
                                <div>
                                    <span class="text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wide">
                                        {{ $article->category->name }}
                                    </span>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition mt-1">
                                        {{ $article->title }}
                                    </h3>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $article->published_at->format('M d, Y') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
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
                            <div class="relative overflow-hidden rounded-lg h-48 mb-4">
                                @if ($article->featured_image)
                                    @php $imgUrl = str_starts_with($article->featured_image, 'http') ? $article->featured_image : asset('storage/' . $article->featured_image); @endphp
                                    <picture>
                                        <source media="(min-width: 1024px)" srcset="{{ $imgUrl }}" width="400" height="300">
                                        <source media="(min-width: 640px)" srcset="{{ $imgUrl }}" width="300" height="225">
                                        <img src="{{ $imgUrl }}" alt="{{ $article->title }}" loading="lazy" width="250" height="188" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    </picture>
                                @else
                                    <img src="https://via.placeholder.com/400x300?text={{ urlencode($article->category->name ?? 'News') }}" alt="{{ $article->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
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
                                            <img src="https://via.placeholder.com/400x300?text={{ urlencode($article->category->name ?? 'News') }}" alt="{{ $article->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
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
                        // Get unique categories (limit to 3 for display)
                        $categoryGroups = $latestArticles->groupBy('category_id')->take(3);
                    @endphp
                    @foreach ($categoryGroups as $categoryId => $categoryArticles)
                        @php
                            // Get the first article to get category name
                            $firstArticle = $categoryArticles->first();
                            $categoryName = $firstArticle->category->name ?? 'News';
                        @endphp
                        <section>
                            <div class="flex items-center justify-between mb-8">
                                <h2 class="text-3xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    {{ $categoryName }}
                                    <span class="text-red-600 text-2xl">›</span>
                                </h2>
                                <a href="{{ route('blog.category', $firstArticle->category->slug ?? '') }}" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-2xl transition">
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
                                                <img src="https://via.placeholder.com/400x300?text={{ urlencode($article->category->name ?? 'News') }}" alt="{{ $article->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
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
                                        <img src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : 'https://via.placeholder.com/100x100?text=' . urlencode($article->category->name ?? 'News') }}"
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

                        <form class="space-y-3"
                            x-data="{ loading: false, email: '', submitNewsletter() { this.loading = true; fetch('{{ route('newsletter.subscribe') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'), 'Content-Type': 'application/json' }, body: JSON.stringify({ email: this.email }) }).then(r => r.json()).then(d => { this.loading = false; if (d.success) { Swal.fire({ icon: 'success', title: 'Subscribed!', text: 'Check your inbox', timer: 3000 }); this.email = ''; } else { alert(d.message || 'Failed'); } }).catch(e => { this.loading = false; alert('Error'); }); } }" @submit.prevent="submitNewsletter">
                            @csrf
                            <input type="email" name="email" x-model="email" placeholder="your@email.com"
                                class="w-full px-4 py-3 rounded-lg text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 placeholder-slate-500"
                                required>
                            <button type="submit" :disabled="loading"
                                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2 rounded-lg text-sm transition disabled:opacity-50">
                                <span x-show="!loading">Subscribe</span>
                                <span x-show="loading"><i class="fas fa-spinner fa-spin mr-2"></i>Subscribing</span>
                            </button>
                        </form>
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

