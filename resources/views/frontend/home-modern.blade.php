@extends('layouts.app-modern')

@section('title', 'RET NEWS - Professional News Portal')

@section('content')
    <!-- Top Breaking News Bar -->
    @if ($announcements->count() > 0)
        <div class="bg-red-700 text-white py-3 sticky top-0 z-40">
            <div class="max-w-6xl mx-auto px-4 flex items-center gap-4">
                <span class="bg-red-900 px-3 py-1 rounded text-xs font-bold uppercase">BREAKING</span>
                <div class="overflow-hidden flex-1">
                    <div class="animate-marquee whitespace-nowrap text-base">
                        @foreach ($announcements as $announcement)
                            <span class="mr-12">• {{ $announcement->title }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Trending Topics -->
    <div class="bg-gray-900 text-white py-2 sticky top-16 z-30">
        <div class="max-w-6xl mx-auto px-4 flex items-center gap-3">
            <span class="text-xs font-bold uppercase whitespace-nowrap">🔥 Trending:</span>
            <div class="overflow-hidden flex-1">
                <div class="animate-marquee whitespace-nowrap text-sm">
                    @php
                        $trendingArticles = $latestArticles->take(10);
                    @endphp
                    @foreach ($trendingArticles as $article)
                        <span class="mr-8">
                            <a href="{{ route('blog.show', $article->slug) }}" class="hover:text-red-500 transition">
                                {{ $article->title }}
                            </a>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Advertisement Section -->
    <div class="bg-white border-b border-gray-200 py-6">
        <div class="max-w-6xl mx-auto px-4">
            <div class="bg-gray-200 rounded-lg h-32 flex items-center justify-center">
                <div class="text-center">
                    <p class="text-gray-600 font-semibold text-lg">Advertisement</p>
                    <p class="text-gray-500 text-sm mt-1">Space for ads - 1200x128px</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Hero Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="grid grid-cols-3 gap-6 items-stretch">
                <!-- Large Featured Article (Col 1-2) -->
                @php $featured = $latestArticles->first() @endphp
                @if ($featured)
                    <div class="col-span-2 group relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition">
                        <div class="absolute inset-0">
                            <img src="{{ $featured->image }}" alt="{{ $featured->title }}"
                                class="w-full h-96 object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <div class="relative h-96 p-8 flex flex-col justify-end">
                            <div class="mb-3">
                                <span class="inline-block bg-red-600 text-white text-xs font-bold px-3 py-1 rounded">
                                    {{ $featured->category->name }}
                                </span>
                            </div>
                            <h1 class="text-4xl font-bold text-white mb-2 leading-tight">{{ $featured->title }}</h1>
                            <p class="text-gray-200 text-base mb-4">{{ $featured->excerpt }}</p>
                            <div class="flex items-center gap-4 text-xs text-gray-300">
                                <span>{{ $featured->date }}</span>
                                <span>{{ $featured->read_time }} min read</span>
                                <a href="{{ $featured->url }}" class="text-red-400 font-semibold hover:text-red-300">Read
                                    →</a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Side Featured Articles (Col 3) -->
                <div class="space-y-4">
                    @foreach ($latestArticles->skip(1)->take(2) as $article)
                        <a href="{{ $article->url }}" class="block group">
                            <div class="relative overflow-hidden rounded-lg h-40 mb-3">
                                <img src="{{ $article->image }}" alt="{{ $article->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            </div>
                            <h3 class="font-bold text-base text-gray-900 line-clamp-2 group-hover:text-red-600">
                                {{ $article->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $article->date }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Featured Grid -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Latest Stories</h2>
            <div class="grid grid-cols-4 gap-4">
                @foreach ($latestArticles->skip(4)->take(4) as $article)
                    <a href="{{ $article->url }}" class="group">
                        <div class="relative overflow-hidden rounded-lg h-40 mb-3 bg-gray-200">
                            <img src="{{ $article->image }}" alt="{{ $article->title }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                        </div>
                        <div class="text-sm font-bold text-red-600 mb-1 uppercase">{{ $article->category->name }}</div>
                        <h3 class="font-bold text-base text-gray-900 line-clamp-2 group-hover:text-red-600">
                            {{ $article->title }}</h3>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="bg-white">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="grid grid-cols-3 gap-8">
                <!-- Main Content (2/3) -->
                <div class="col-span-2">
                    <!-- Most Read Section -->
                    <section class="mb-12">
                        <div class="flex items-center justify-between mb-6 pb-3 border-b-2 border-red-600">
                            <h2 class="text-3xl font-bold text-gray-900">Most Read</h2>
                            <a href="{{ route('blog.index') }}"
                                class="text-red-600 font-semibold text-base hover:text-red-700">View All →</a>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            @foreach ($latestArticles->skip(8)->take(6) as $article)
                                <a href="{{ route('blog.show', $article->slug) }}" class="group">
                                    <!-- Image -->
                                    <div class="relative overflow-hidden rounded-lg h-48 mb-3 bg-gray-200">
                                        <img src="{{ $article->image }}" alt="{{ $article->title }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                    </div>
                                    <!-- Category -->
                                    <div class="text-sm font-bold text-red-600 mb-2 uppercase">{{ $article->category->name }}</div>
                                    <!-- Title -->
                                    <h3 class="font-bold text-base text-gray-900 line-clamp-2 group-hover:text-red-600 mb-2">
                                        {{ $article->title }}</h3>
                                    <!-- Date -->
                                    <p class="text-sm text-gray-500">{{ $article->date }}</p>
                                </a>
                            @endforeach
                        </div>
                    </section>

                    <!-- Advertisement Section -->
                    <div class="mb-12 bg-gray-200 rounded-lg h-40 flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-gray-600 font-semibold text-lg">Advertisement</p>
                            <p class="text-gray-500 text-sm mt-1">Space for ads</p>
                        </div>
                    </div>

                    <!-- Regular News Section -->
                    <section class="mb-12">
                        <div class="mb-6 pb-3 border-b-2 border-gray-300">
                            <h2 class="text-3xl font-bold text-gray-900">News</h2>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            @foreach ($latestArticles->skip(13)->take(4) as $article)
                                <article class="group">
                                    <div class="relative overflow-hidden rounded-lg h-48 mb-4 bg-gray-200">
                                        <img src="{{ $article->image }}" alt="{{ $article->title }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                    </div>
                                    <div class="text-sm font-bold text-red-600 mb-2 uppercase">
                                        {{ $article->category->name }}</div>
                                    <h3
                                        class="font-bold text-base text-gray-900 line-clamp-2 group-hover:text-red-600 mb-2">
                                        <a href="{{ $article->url }}">{{ $article->title }}</a>
                                    </h3>
                                    <p class="text-sm text-gray-500">{{ $article->date }}</p>
                                </article>
                            @endforeach
                        </div>
                    </section>

                    <!-- Categories Spotlight Section -->
                    @if ($categories->count() > 0)
                        @php
                            $categoryArticles = [];
                            $dummyTitles = [
                                'Inovasi Terbaru dalam Industri Teknologi',
                                'Tren Global yang Mengubah Perspektif Bisnis',
                                'Wawancara Eksklusif dengan Tokoh Inspiratif',
                                'Analisis Mendalam tentang Perkembangan Pasar',
                            ];

                            foreach ($categories->take(3) as $category) {
                                $articles = $category->articles()->published()->latest('published_at')->take(3)->get();

                                // Tambahkan dummy articles jika kurang
                                while ($articles->count() < 3) {
                                    $dummyArticle = (object)[
                                        'id' => 'dummy-' . $category->id . '-' . ($articles->count() + 1),
                                        'slug' => 'dummy-article-' . $category->id,
                                        'title' => $dummyTitles[($articles->count() + ($category->id % 4)) % 4] . ' - ' . $category->name,
                                        'image' => 'https://via.placeholder.com/400x300?text=' . urlencode($category->name),
                                        'excerpt' => 'Artikel menarik tentang ' . $category->name,
                                        'published_at' => now()->subDays(5 - $articles->count()),
                                        'category' => $category,
                                        'slug' => '#',
                                    ];
                                    $articles->push($dummyArticle);
                                }

                                $categoryArticles[$category->id] = $articles;
                            }
                        @endphp
                        @foreach ($categories->take(3) as $category)
                            <section class="mb-12 last:mb-0">
                                <!-- Category Header -->
                                <div class="flex items-center justify-between mb-6 pb-3 border-b-2 border-gray-300">
                                    <h2 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h2>
                                    <a href="{{ route('blog.category', $category->slug) }}"
                                        class="text-red-600 font-semibold text-base hover:text-red-700">View All →</a>
                                </div>

                                <!-- Category Content - 3 Column Grid -->
                                @if ($categoryArticles[$category->id]->count() > 0)
                                    <div class="grid grid-cols-3 gap-6">
                                        @foreach ($categoryArticles[$category->id]->take(3) as $article)
                                            <article class="group">
                                                <div class="relative overflow-hidden rounded-lg h-48 mb-4 bg-gray-200">
                                                    <img src="{{ $article->image }}" alt="{{ $article->title }}"
                                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                                </div>
                                                <h3 class="font-bold text-base text-gray-900 line-clamp-2 group-hover:text-red-600 mb-2">
                                                    <a href="{{ $article->slug === '#' ? '#' : route('blog.show', $article->slug) }}">{{ $article->title }}</a>
                                                </h3>
                                                <p class="text-sm text-gray-500">{{ $article->published_at->format('M d, Y') }}</p>
                                            </article>
                                        @endforeach
                                    </div>
                                @endif
                            </section>
                        @endforeach
                    @endif
                </div>

                <!-- Sidebar (1/3) -->
                <aside class="space-y-6">
                    <!-- Search Box -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <form action="{{ route('blog.search') }}" method="GET" class="space-y-3">
                            <input type="text" name="search" placeholder="Search news..."
                                value="{{ request('search') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-red-600">
                            <button type="submit"
                                class="w-full bg-red-600 text-white py-2 rounded font-semibold text-sm hover:bg-red-700">
                                Search
                            </button>
                        </form>
                    </div>

                    <!-- Trending Now -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="font-bold text-xl text-gray-900 mb-4 pb-3 border-b-2 border-red-600">Trending Now</h3>
                        <div class="space-y-0 -mx-6 -mb-6">
                            @foreach ($latestArticles->take(5) as $article)
                                <a href="{{ route('blog.show', $article->slug) }}"
                                    class="flex gap-3 p-4 hover:bg-white transition group border-b border-gray-200 last:border-0">
                                    <!-- Thumbnail -->
                                    <div class="flex-shrink-0 w-16 h-16 overflow-hidden rounded">
                                        <img src="{{ $article->image }}" alt="{{ $article->title }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="font-semibold text-base text-gray-900 line-clamp-2 group-hover:text-red-600">
                                            {{ $article->title }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">{{ $article->date }}</p>
                                    </div>

                                    <!-- Comment Count -->
                                    <div class="flex-shrink-0 text-right">
                                        @php
                                            $commentCount = $article->comments
                                                ? $article->comments->where('is_approved', true)->count()
                                                : 0;
                                        @endphp
                                        <div class="text-sm font-semibold text-gray-900">
                                            <i class="fas fa-comment text-gray-400"></i>
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $commentCount }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                        <h3 class="font-bold text-lg text-gray-900 mb-4">Follow Us</h3>
                        <div class="flex gap-3 justify-center">
                            <a href="#" class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:scale-110 transition">
                                <i class="fab fa-facebook-f text-base"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-black text-white flex items-center justify-center hover:scale-110 transition">
                                <i class="fab fa-x-twitter text-base"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-blue-400 text-white flex items-center justify-center hover:scale-110 transition">
                                <i class="fab fa-telegram text-base"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-pink-500 text-white flex items-center justify-center hover:scale-110 transition">
                                <i class="fab fa-instagram text-base"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center hover:scale-110 transition">
                                <i class="fab fa-youtube text-base"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center hover:scale-110 transition">
                                <i class="fab fa-whatsapp text-base"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-black text-white flex items-center justify-center hover:scale-110 transition">
                                <i class="fab fa-tiktok text-base"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Advertisement Section -->
                    <div class="bg-gray-200 rounded-lg h-40 flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-gray-600 font-semibold text-base">Advertisement</p>
                            <p class="text-gray-500 text-sm mt-1">Space for ads</p>
                        </div>
                    </div>

                    <!-- Newsletter -->
                    <div class="bg-gradient-to-b from-blue-600 to-blue-700 text-white p-6 rounded-lg">
                        <h3 class="font-bold text-xl mb-2">Newsletter</h3>
                        <p class="text-base text-blue-100 mb-4">Get the latest news delivered to your inbox daily.</p>
                        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="email" name="email" required placeholder="Your email"
                                class="w-full px-4 py-2 rounded text-gray-900 text-base focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <button type="submit"
                                class="w-full bg-red-600 text-white py-2 rounded font-semibold text-base hover:bg-red-700">
                                Subscribe
                            </button>
                        </form>
                    </div>

                    <!-- Rekomendasi (Affiliasi) -->
                    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                        <h3 class="font-bold text-lg text-gray-900 mb-4">Rekomendasi</h3>
                        <div class="space-y-4">
                            @php
                                $recommendedProducts = [
                                    [
                                        'title' => 'Paket Internet Unlimited 100Mbps',
                                        'price' => 'Rp 299.000',
                                        'image' => 'https://via.placeholder.com/150x100?text=Internet',
                                    ],
                                    [
                                        'title' => 'VPS Cloud Hosting Pro',
                                        'price' => 'Rp 149.000',
                                        'image' => 'https://via.placeholder.com/150x100?text=Hosting',
                                    ],
                                    [
                                        'title' => 'Domain .COM 1 Tahun',
                                        'price' => 'Rp 79.000',
                                        'image' => 'https://via.placeholder.com/150x100?text=Domain',
                                    ],
                                    [
                                        'title' => 'SSL Certificate Pro',
                                        'price' => 'Rp 199.000',
                                        'image' => 'https://via.placeholder.com/150x100?text=SSL',
                                    ],
                                ];
                            @endphp
                            @foreach ($recommendedProducts as $product)
                                <a href="#" class="group flex gap-3 pb-4 border-b border-gray-200 last:border-0 last:pb-0 hover:opacity-80 transition">
                                    <!-- Thumbnail -->
                                    <div class="flex-shrink-0 w-20 h-20 overflow-hidden rounded bg-gray-200">
                                        <img src="{{ $product['image'] }}" alt="{{ $product['title'] }}"
                                            class="w-full h-full object-cover">
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-bold text-gray-900 line-clamp-2 group-hover:text-red-600 mb-1">
                                            {{ $product['title'] }}</h4>
                                        <p class="text-sm font-semibold text-red-600">{{ $product['price'] }}/bulan</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <!-- Events Section -->
    @if ($upcomingEvents->count() > 0)
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-6xl mx-auto px-4 py-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold text-gray-900">Upcoming Events</h2>
                    <a href="#" class="text-red-600 font-semibold text-sm hover:text-red-700">View All →</a>
                </div>
                <div class="grid grid-cols-3 gap-6">
                    @foreach ($upcomingEvents->take(3) as $event)
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition">
                            <div class="bg-red-600 text-white p-6">
                                <div class="text-5xl font-bold leading-none mb-2">{{ $event->event_date->format('d') }}
                                </div>
                                <div class="text-base font-semibold">{{ $event->event_date->format('F Y') }}</div>
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-xl text-gray-900 mb-2">{{ $event->title }}</h3>
                                <p class="text-base text-gray-600 mb-4 line-clamp-2">{{ $event->description }}</p>
                                <button class="text-red-600 font-semibold text-base hover:text-red-700">Learn More →</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Advertisement Banner -->
    <div class="bg-gray-100 py-12">
        <div class="max-w-6xl mx-auto px-4">
            <div class="bg-gray-300 rounded-lg h-32 flex items-center justify-center">
                <p class="text-gray-600 font-semibold">Advertisement Space</p>
            </div>
        </div>
    </div>

    <style>
        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .animate-marquee {
            animation: marquee 30s linear infinite;
        }

        .animate-marquee:hover {
            animation-play-state: paused;
        }
    </style>
@endsection
