<header class="bg-white shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center">
            @if(!empty($settings['site_logo']))
                <img src="{{ asset('storage/' . $settings['site_logo']) }}"
                    alt="{{ $settings['site_name'] ?? config('app.name') }}" class="h-10 w-auto">
            @else
                <span class="text-xl font-bold text-gray-800">{{ $settings['site_name'] ?? config('app.name') }}</span>
            @endif
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex space-x-6">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 font-medium">Home</a>
            @foreach(\App\Models\Category::whereNull('parent_id')->take(5)->get() as $category)
                <a href="{{ route('categories.show', $category) }}"
                    class="text-gray-600 hover:text-blue-600 font-medium">{{ $category->name }}</a>
            @endforeach
        </nav>

        <!-- Search & Auth -->
        <div class="flex items-center space-x-4">
            <form action="{{ route('articles.search') }}" method="GET" class="hidden md:block">
                <div class="relative">
                    <input type="text" name="q" placeholder="Search..."
                        class="border border-gray-300 rounded-full py-1 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-48 transition-all focus:w-64">
                    <button type="submit" class="absolute right-2 top-1.5 text-gray-500 hover:text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </form>

            {{-- Mobile Search Toggle (Optional) --}}
            <button class="md:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>

            @auth
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open"
                        class="flex items-center text-gray-600 hover:text-gray-900 focus:outline-none">
                        <span class="mr-2">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <!-- Dropdown -->
                </div>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 font-medium">Login</a>
                <a href="{{ route('register') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-medium">Register</a>
            @endauth
        </div>

        <!-- Mobile Menu Button -->
        <button class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>
    </div>
</header>