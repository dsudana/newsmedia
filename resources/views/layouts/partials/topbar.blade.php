<!-- Top Bar (White with Search) -->
<div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 py-3 px-4">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-6">
        <!-- Left: Date -->
        <span class="text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ now()->format('l, F d, Y') }}</span>

        <!-- Center: Search Bar -->
        <form action="{{ route('blog.search') }}" method="GET" class="flex-1 max-w-md">
            <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded-full px-4 py-2">
                <input type="text" name="search" placeholder="Cari tokoh, topik atau peristiwa..."
                    class="bg-transparent flex-1 text-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 outline-none">
                <button type="submit" aria-label="Search" class="text-gray-600 dark:text-gray-400 hover:text-red-600 transition">
                    <i class="fas fa-search text-sm"></i>
                </button>
            </div>
        </form>

        <!-- Right: Auth & Social -->
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex items-center gap-3 text-xs">
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="inline-block">
                        @csrf
                        <button type="submit" class="text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition bg-transparent border-none cursor-pointer p-0">
                            {{ Auth::user()->name }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition">Login</a>
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('register') }}" class="text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition">Register</a>
                @endauth
            </div>
            <div class="hidden sm:flex items-center gap-3">
                <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition"><i class="fab fa-facebook text-sm"></i></a>
                <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition"><i class="fab fa-twitter text-sm"></i></a>
                <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition"><i class="fab fa-instagram text-sm"></i></a>
            </div>
        </div>
    </div>
</div>
