<div id="topbar" class="bg-red-900 text-white text-[13px] sm:text-xs w-full">
    <div class="max-w-7xl mx-auto px-4 lg:px-8 h-9 sm:h-8 lg:h-9 flex items-center justify-between">
        <!-- Date (Hidden on mobile) -->
        <span id="current-date" class="hidden sm:inline font-medium">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>

        <!-- Mobile-only spacer -->
        <span class="sm:hidden"></span>

        <div id="topbar-links" class="flex items-center gap-2 sm:gap-5">
            <a id="link-contact" href="{{ route('contact') }}" class="hover:text-rn-red transition-colors text-xs sm:text-[13px] whitespace-nowrap">Contact Us</a>
            @auth
                <a id="link-dashboard" href="{{ route('admin.dashboard') }}" class="hover:text-rn-red transition-colors text-xs sm:text-[13px] whitespace-nowrap font-medium">Admin Dashboard</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a id="link-logout" href="#" onclick="document.getElementById('logout-form').submit(); return false;" class="hover:text-rn-red transition-colors text-xs sm:text-[13px] whitespace-nowrap">Logout</a>
            @else
                <a id="link-login" href="{{ route('login') }}" class="hover:text-rn-red transition-colors text-xs sm:text-[13px] whitespace-nowrap">Login / Register</a>
            @endauth

            <!-- Social Media Icons (Hidden on mobile) -->
            <div id="social-media-topbar" class="hidden sm:flex items-center gap-3 pl-3 ml-1 border-l border-white/20">
                <a href="#" id="social-facebook" aria-label="Facebook" class="hover:text-rn-red"><i
                        class="fa-brands fa-facebook-f"></i></a>
                <a href="#" id="social-twitter" aria-label="Twitter" class="hover:text-rn-red"><i
                        class="fa-brands fa-twitter"></i></a>
                <a href="#" id="social-instagram" aria-label="Instagram" class="hover:text-rn-red"><i
                        class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
    </div>
</div>
