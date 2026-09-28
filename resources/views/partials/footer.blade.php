<footer class="bg-black text-white mt-4">
    <div class="max-w-6xl mx-auto px-4 py-12 grid grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach ([
            'World' => ['Global Economy', 'Religion', 'Bitcoin', 'Conflict', 'Sports', 'Scandals'],
            'Entertainment' => ['Celebity News', 'Movies', 'Tv News', 'Music News', 'Life Style', 'Entertainment Video'],
            'Health' => ['Medical Research', 'Healthy Living', 'Mental Health', 'Virus Corona', "Children's Health"],
            'Business' => ['Markets', 'Technology', 'Features', 'Property', 'Business Leaders'],
        ] as $heading => $links)
            <div>
                <h3 class="text-[15px] font-bold uppercase mb-4 relative pb-3">
                    {{ $heading }}
                    <span class="absolute left-0 bottom-0 w-8 h-0.75 bg-rn-red"></span>
                </h3>
                <ul class="space-y-2 text-[13px] text-white/70">
                    @foreach ($links as $link)
                        <li><a href="#" class="hover:text-rn-red transition-colors">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    <div class="border-t border-white/10">
        <div class="max-w-6xl mx-auto px-4 py-6 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-1">
                <span class="text-xl font-extrabold text-white">RET</span>
                <svg class="w-4 h-6 text-rn-red -mx-0.5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M13 2 3 14h7l-1 8 11-14h-7l1-6z"/>
                </svg>
                <span class="text-xl font-extrabold text-rn-red">NEWS</span>
            </a>

            <div class="flex items-center gap-3">
                @foreach (['facebook-f', 'twitter', 'whatsapp', 'telegram', 'linkedin-in'] as $icon)
                    <a href="#" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-rn-red transition-colors">
                        <i class="fa-brands fa-{{ $icon }} text-sm"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-black">
        <div class="max-w-6xl mx-auto px-4 py-4 flex flex-col md:flex-row items-center justify-between gap-2 text-[12px] text-white/60">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('contact') }}" class="hover:text-rn-red">Contact Us</a>
                <a href="#" class="hover:text-rn-red">Terms Of Use</a>
                <a href="#" class="hover:text-rn-red">Adchoice</a>
                <a href="#" class="hover:text-rn-red">About Us</a>
                <a href="#" class="hover:text-rn-red">Newsletters</a>
                <a href="#" class="hover:text-rn-red">Sitemap</a>
                <a href="#" class="hover:text-rn-red">Magrenvi Store</a>
            </div>
            <p>Copyright © {{ now()->year }} News and Magazine template based on Bootstrap 4 Theme by Retenvi.</p>
        </div>
    </div>
</footer>
