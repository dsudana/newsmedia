<!-- Top Bar (Black) -->
<div class="bg-black text-white text-xs py-2 px-4">
    <div class="max-w-6xl mx-auto flex items-center justify-between">
        <span>{{ now()->format('l, F d, Y') }}</span>
        <div class="flex items-center gap-6">
            <a href="#" class="hover:text-red-600 transition">Career</a>
            <a href="#" class="hover:text-red-600 transition">Contact Us</a>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('logout') }}" class="hover:text-red-600 transition">{{ Auth::user()->name }}</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-red-600 transition">Login</a>
                    <span>/</span>
                    <a href="{{ route('register') }}" class="hover:text-red-600 transition">Register</a>
                @endauth
            </div>
            <div class="flex items-center gap-3">
                <a href="#" class="hover:text-red-600 transition"><i class="fab fa-facebook text-sm"></i></a>
                <a href="#" class="hover:text-red-600 transition"><i class="fab fa-twitter text-sm"></i></a>
                <a href="#" class="hover:text-red-600 transition"><i class="fab fa-instagram text-sm"></i></a>
            </div>
        </div>
    </div>
</div>
