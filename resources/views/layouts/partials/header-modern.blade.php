<!-- Header -->
<header class="bg-black border-b border-gray-700 py-4 sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-1 hover:opacity-80 transition">
            <span class="text-2xl font-black text-white">RET</span>
            <span class="text-2xl font-black">
                <i class="fas fa-bolt text-red-600"></i><span class="text-red-600">NEWS</span>
            </span>
        </a>

        <!-- Navigation -->
        <nav class="hidden md:flex items-center gap-8">
            <a href="{{ route('home') }}" class="font-bold text-xs text-white hover:text-red-600 transition">HOME</a>
            <a href="#" class="font-bold text-xs text-white hover:text-red-600 transition">PAGES</a>
            <a href="#" class="font-bold text-xs text-white hover:text-red-600 transition">ABOUT</a>
            <a href="#" class="font-bold text-xs text-white hover:text-red-600 transition">NEWS</a>
            <a href="#" class="font-bold text-xs text-white hover:text-red-600 transition">CATEGORY</a>
            <a href="#" class="font-bold text-xs text-white hover:text-red-600 transition">CONTACT</a>
        </nav>

        <!-- Search -->
        <button class="text-gray-400 hover:text-red-600 transition">
            <i class="fas fa-search text-lg"></i>
        </button>

        <!-- Mobile Menu Button -->
        <button class="md:hidden text-gray-400 hover:text-red-600 transition">
            <i class="fas fa-bars text-lg"></i>
        </button>
    </div>
</header>
