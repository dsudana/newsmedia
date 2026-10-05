<div id="topbar" class="bg-black text-white text-[13px]">
    <div class="max-w-6xl mx-auto px-4 lg:px-8 h-9 flex items-center justify-between">
        <span id="current-date" class="font-medium">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>

        <div id="topbar-links" class="flex items-center gap-5">

            <a id="link-contact" href="{{ route('contact') }}" class="hover:text-rn-red transition-colors">Contact Us</a>
            <a id="link-login" href="{{ route('login') }}" class="hover:text-rn-red transition-colors">Login /
                Register</a>

            <div id="social-media-topbar" class="flex items-center gap-3 pl-3 ml-1 border-l border-white/20">
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
