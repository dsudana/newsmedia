<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>NEWSMEDIA - Latest News & Stories</title>
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>

    <body class="bg-white">
        <!-- Top Bar (Black) -->
        <div class="bg-black text-white text-xs py-2 px-4">
            <div class="max-w-6xl mx-auto flex items-center justify-between">
                <span>{{ now()->format('l, F d, Y') }}</span>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-red-600">Career</a>
                    <a href="#" class="hover:text-red-600">Contact Us</a>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="hover:text-red-600">Login</a>
                        <span>/</span>
                        <a href="{{ route('register') }}" class="hover:text-red-600">Register</a>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="#" class="hover:text-red-600"><i class="fab fa-facebook text-sm"></i></a>
                        <a href="#" class="hover:text-red-600"><i class="fab fa-twitter text-sm"></i></a>
                        <a href="#" class="hover:text-red-600"><i class="fab fa-instagram text-sm"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header -->
        <header class="bg-white border-b border-gray-300 py-4">
            <div class="max-w-6xl mx-auto px-4 flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-1">
                    <span class="text-2xl font-black text-black">NEWS</span>
                    <span class="text-2xl font-black">
                        <i class="fas fa-bolt text-red-600"></i><span class="text-red-600">MEDIA</span>
                    </span>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="#" class="font-bold text-xs text-gray-900 hover:text-red-600">HOME</a>
                    <a href="#" class="font-bold text-xs text-gray-900 hover:text-red-600">PAGES</a>
                    <a href="#" class="font-bold text-xs text-gray-900 hover:text-red-600">ABOUT</a>
                    <a href="#" class="font-bold text-xs text-gray-900 hover:text-red-600">NEWS</a>
                    <a href="#" class="font-bold text-xs text-gray-900 hover:text-red-600">CATEGORY</a>
                    <a href="#" class="font-bold text-xs text-gray-900 hover:text-red-600">CONTACT</a>
                </nav>

                <!-- Search -->
                <button class="text-gray-700 hover:text-red-600">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </header>

        <!-- News Ticker Carousel -->
        @if (isset($latestArticles) && $latestArticles->count() > 0)
            <div class="bg-white border-b border-gray-300 py-4">
                <div class="max-w-6xl mx-auto px-4">
                    <div class="flex gap-8 overflow-x-auto pb-2 scrollbar-hide">
                        @foreach ($latestArticles->take(3) as $article)
                            <div class="flex gap-3 flex-shrink-0">
                                <div class="w-20 h-20 bg-gray-300 rounded-sm overflow-hidden">
                                    @if ($article->featured_image)
                                        <img src="{{ asset('storage/' . $article->featured_image) }}"
                                            class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="max-w-xs">
                                    <p class="text-xs text-gray-700">
                                        <span class="font-bold text-red-600">By
                                            {{ $article->user->name ?? 'Editor' }}</span>
                                        {{ $article->created_at->format('F d, Y') }}
                                    </p>
                                    <h4 class="text-sm font-bold text-gray-900 line-clamp-2">{{ $article->title }}</h4>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column (2/3) -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Featured Section: Large + 2 Small Cards -->
                    @if (isset($latestArticles) && $latestArticles->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Large Featured -->
                            <div class="md:col-span-2">
                                <a href="{{ route('articles.show', $latestArticles->first()->slug) }}"
                                    class="group block">
                                    <div class="bg-gray-900 h-80 relative overflow-hidden">
                                        @if ($latestArticles->first()->featured_image)
                                            <img src="{{ asset('storage/' . $latestArticles->first()->featured_image) }}"
                                                class="w-full h-full object-cover opacity-60 group-hover:opacity-80 transition">
                                        @endif
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black to-transparent flex flex-col justify-end p-4">
                                            @if ($latestArticles->first()->category)
                                                <span
                                                    class="inline-block px-2 py-1 bg-red-600 text-white text-xs font-bold w-fit mb-2">
                                                    {{ strtoupper($latestArticles->first()->category->name) }}
                                                </span>
                                            @endif
                                            <h2 class="text-white font-bold text-xl line-clamp-3">
                                                {{ $latestArticles->first()->title }}</h2>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- 2 Small Featured Cards -->
                            <div class="space-y-4">
                                @foreach ($latestArticles->skip(1)->take(2) as $article)
                                    <a href="{{ route('articles.show', $article->slug) }}" class="group block">
                                        <div class="bg-gray-200 h-40 relative overflow-hidden">
                                            @if ($article->featured_image)
                                                <img src="{{ asset('storage/' . $article->featured_image) }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition">
                                            @endif
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-black to-transparent flex flex-col justify-end p-3">
                                                @if ($article->category)
                                                    <span
                                                        class="inline-block px-2 py-0.5 bg-red-600 text-white text-xs font-bold w-fit mb-1">
                                                        {{ strtoupper($article->category->name) }}
                                                    </span>
                                                @endif
                                                <h3 class="text-white font-bold text-sm line-clamp-2">
                                                    {{ $article->title }}</h3>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Main Grid (4 Columns) -->
                    @if (isset($latestArticles) && $latestArticles->count() > 3)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach ($latestArticles->skip(3)->take(8) as $article)
                                <article class="group">
                                    <a href="{{ route('articles.show', $article->slug) }}" class="block mb-3">
                                        <div class="bg-gray-300 h-40 overflow-hidden">
                                            @if ($article->featured_image)
                                                <img src="{{ asset('storage/' . $article->featured_image) }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition">
                                            @endif
                                        </div>
                                    </a>
                                    <p class="text-xs text-gray-700 mb-1">
                                        <span class="font-bold text-red-600">By
                                            {{ $article->user->name ?? 'Editor' }}</span>
                                    </p>
                                    <h3 class="font-bold text-sm text-gray-900 line-clamp-2 group-hover:text-red-600">
                                        {{ $article->title }}
                                    </h3>
                                </article>
                            @endforeach
                        </div>
                    @endif

                </div>

                <!-- Right Sidebar (1/3) -->
                <div class="space-y-8">
                    <!-- Recent Posts -->
                    <div>
                        <div class="border-l-4 border-red-600 pl-3 mb-6">
                            <h3 class="font-bold text-lg text-gray-900">Recent Post</h3>
                        </div>
                        <div class="space-y-4">
                            @foreach ($latestArticles->take(5) as $article)
                                <a href="{{ route('articles.show', $article->slug) }}" class="group flex gap-3">
                                    <div class="w-16 h-16 bg-gray-300 rounded-sm flex-shrink-0 overflow-hidden">
                                        @if ($article->featured_image)
                                            <img src="{{ asset('storage/' . $article->featured_image) }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition">
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        @if ($article->category)
                                            <span
                                                class="inline-block px-2 py-0.5 bg-red-600 text-white text-xs font-bold mb-1">
                                                {{ strtoupper($article->category->name) }}
                                            </span>
                                        @endif
                                        <h4
                                            class="font-bold text-sm text-gray-900 line-clamp-2 group-hover:text-red-600">
                                            {{ $article->title }}
                                        </h4>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Popular Post -->
                    <div>
                        <div class="border-l-4 border-red-600 pl-3 mb-6">
                            <h3 class="font-bold text-lg text-gray-900">Popular Post</h3>
                        </div>
                        <div class="space-y-4">
                            @foreach ($latestArticles->skip(5)->take(4) as $i => $article)
                                <a href="{{ route('articles.show', $article->slug) }}" class="group flex gap-3">
                                    <div
                                        class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        {{ $i + 1 }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        @if ($article->category)
                                            <span
                                                class="inline-block px-1.5 py-0.5 bg-red-600 text-white text-xs font-bold mb-0.5">
                                                {{ strtoupper($article->category->name) }}
                                            </span>
                                        @endif
                                        <h4
                                            class="font-bold text-xs text-gray-900 line-clamp-2 group-hover:text-red-600">
                                            {{ $article->title }}
                                        </h4>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Categories Section -->
        @if (isset($categories) && $categories->count() > 0)
            @foreach ($categories->chunk(4) as $categoryChunk)
                <section class="border-t border-gray-300 py-8">
                    <div class="max-w-6xl mx-auto px-4">
                        @foreach ($categoryChunk as $category)
                            <div class="mb-8">
                                <div class="border-l-4 border-red-600 pl-3 mb-4">
                                    <h3 class="font-bold text-lg text-gray-900 uppercase">{{ $category->name }}</h3>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                    @if ($category->articles)
                                        @foreach ($category->articles->take(4) as $article)
                                            <article class="group">
                                                <a href="{{ route('articles.show', $article->slug) }}"
                                                    class="block mb-3">
                                                    <div class="bg-gray-300 h-40 overflow-hidden">
                                                        @if ($article->featured_image)
                                                            <img src="{{ asset('storage/' . $article->featured_image) }}"
                                                                class="w-full h-full object-cover group-hover:scale-105 transition">
                                                        @endif
                                                    </div>
                                                </a>
                                                <p class="text-xs text-gray-700 mb-1">
                                                    <span class="font-bold text-red-600">By
                                                        {{ $article->user->name ?? 'Editor' }}</span>
                                                </p>
                                                <h4
                                                    class="font-bold text-sm text-gray-900 line-clamp-2 group-hover:text-red-600">
                                                    {{ $article->title }}
                                                </h4>
                                            </article>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        @endif

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800">
            <div class="max-w-6xl mx-auto px-4">
                <!-- Footer Content Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                    <div>
                        <h3 class="font-bold text-white text-sm mb-4">World</h3>
                        <ul class="space-y-2 text-xs">
                            <li><a href="#" class="hover:text-white">Global Economy</a></li>
                            <li><a href="#" class="hover:text-white">Politic</a></li>
                            <li><a href="#" class="hover:text-white">Business</a></li>
                            <li><a href="#" class="hover:text-white">Conflict</a></li>
                            <li><a href="#" class="hover:text-white">Sports</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm mb-4">Entertainment</h3>
                        <ul class="space-y-2 text-xs">
                            <li><a href="#" class="hover:text-white">Celebrity News</a></li>
                            <li><a href="#" class="hover:text-white">Artistes</a></li>
                            <li><a href="#" class="hover:text-white">Music</a></li>
                            <li><a href="#" class="hover:text-white">Life Style</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm mb-4">Health</h3>
                        <ul class="space-y-2 text-xs">
                            <li><a href="#" class="hover:text-white">Medical Research</a></li>
                            <li><a href="#" class="hover:text-white">Diet</a></li>
                            <li><a href="#" class="hover:text-white">Virus Genetic</a></li>
                            <li><a href="#" class="hover:text-white">Children's Health</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm mb-4">Business</h3>
                        <ul class="space-y-2 text-xs">
                            <li><a href="#" class="hover:text-white">Inovation</a></li>
                            <li><a href="#" class="hover:text-white">Technology</a></li>
                            <li><a href="#" class="hover:text-white">Property</a></li>
                            <li><a href="#" class="hover:text-white">Business Startups</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Footer Bottom -->
                <div class="border-t border-gray-800 pt-8 flex items-center justify-between">
                    <div class="flex items-center gap-1">
                        <span class="text-xl font-black text-white">NEWS</span>
                        <i class="fas fa-bolt text-red-600 text-xl"></i><span
                            class="text-xl font-black text-red-600">MEDIA</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="#" class="text-gray-400 hover:text-red-600"><i
                                class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-red-600"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-red-600"><i
                                class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-gray-400 hover:text-red-600"><i
                                class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="text-center text-xs text-gray-500 mt-6 pt-6 border-t border-gray-800">
                    <p>Copyright © 2024 NewSMedia. All rights reserved.</p>
                </div>
            </div>
        </footer>

        @vite('resources/js/app.js')
    </body>

</html>
