<!-- Footer -->
<footer class="bg-gray-900 dark:bg-black text-gray-400 dark:text-gray-500 py-12 border-t border-gray-800 dark:border-gray-700 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Footer Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8 mb-12">
            <!-- Logo & Social Media Section -->
            <div class="md:col-span-1">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1 hover:opacity-80 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2 mb-6">
                    <span class="text-xl font-black text-white">NEWS</span>
                    <i class="fas fa-bolt text-red-600 text-xl" aria-hidden="true"></i>
                    <span class="text-xl font-black text-red-600">MEDIA</span>
                </a>

                <!-- Social Media Icons -->
                <div class="flex gap-3 mt-6">
                    @php
                        try {
                            $socialLinks = \App\Models\SocialMedia::active()->ordered()->get();
                        } catch (\Exception $e) {
                            $socialLinks = collect();
                        }
                    @endphp
                    @forelse($socialLinks as $social)
                        <a href="{{ $social->url }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           aria-label="Ikuti kami di {{ $social->platform }}"
                           title="{{ $social->platform }}"
                           class="w-10 h-10 rounded-lg bg-gray-800 dark:bg-gray-700 text-gray-400 hover:text-white hover:bg-red-600 dark:hover:bg-red-600 flex items-center justify-center transition-all duration-300 transform hover:scale-110 focus-visible:ring-2 ring-offset-2 ring-red-600">
                            <i class="{{ $social->icon }} text-sm" aria-hidden="true"></i>
                        </a>
                    @empty
                        <p class="text-sm text-gray-600">Tidak ada social media</p>
                    @endforelse
                </div>
            </div>

            <!-- Navigation Links -->
            <div>
                <h3 class="font-bold text-white dark:text-gray-100 text-sm mb-4 uppercase tracking-wide">Menu</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">Beranda</a></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">Semua Berita</a></li>
                    <li><a href="#" class="hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">Kontak</a></li>
                </ul>
            </div>

            <!-- Categories -->
            <div>
                <h3 class="font-bold text-white dark:text-gray-100 text-sm mb-4 uppercase tracking-wide">Kategori</h3>
                <ul class="space-y-3 text-sm">
                    @php
                        $categories = \App\Models\Category::active()
                            ->withCount(['articles' => fn($q) => $q->published()])
                            ->having('articles_count', '>', 0)
                            ->orderByDesc('articles_count')
                            ->limit(6)
                            ->get();
                    @endphp
                    @forelse($categories as $category)
                        <li>
                            <a href="{{ route('blog.category', $category->slug) }}" class="hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">
                                {{ $category->name }}
                                <span class="text-gray-600 dark:text-gray-700 text-xs ml-1">({{ $category->articles_count }})</span>
                            </a>
                        </li>
                    @empty
                        <li><span class="text-gray-600">Tidak ada kategori</span></li>
                    @endforelse
                </ul>
            </div>

            <!-- Company -->
            <div>
                <h3 class="font-bold text-white dark:text-gray-100 text-sm mb-4 uppercase tracking-wide">Perusahaan</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">Kebijakan Privasi</a></li>
                    <li><a href="#" class="hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">Hubungi Kami</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h3 class="font-bold text-white dark:text-gray-100 text-sm mb-4 uppercase tracking-wide">Newsletter</h3>
                <p class="text-sm text-gray-500 dark:text-gray-600 mb-4">Dapatkan berita terbaru langsung ke inbox Anda</p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-2">
                    @csrf
                    <input type="email" name="email" placeholder="Email Anda" required
                        class="w-full px-3 py-2 bg-gray-800 dark:bg-gray-700 text-white text-sm rounded border border-gray-700 dark:border-gray-600 focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600/20 transition">
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded text-sm transition focus-visible:ring-2 ring-offset-2 ring-red-600">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-800 dark:border-gray-700"></div>

        <!-- Footer Bottom -->
        <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-600 dark:text-gray-500">
                &copy; {{ date('Y') }} <span class="font-bold text-white">NEWS MEDIA</span>. Hak cipta dilindungi.
            </p>
            <div class="flex items-center gap-6 text-sm">
                <a href="#" class="text-gray-600 hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">Sitemap</a>
                <a href="#" class="text-gray-600 hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">Disclaimer</a>
                <a href="#" class="text-gray-600 hover:text-red-600 transition focus-visible:ring-2 ring-offset-2 ring-red-600 rounded px-2">Iklan</a>
            </div>
        </div>
    </div>
</footer>
