<!-- Admin Footer -->
<div class="mt-12 pt-8 border-t border-gray-200">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- About -->
        <div>
            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-newspaper text-indigo-600"></i>
                NewSMedia
            </h3>
            <p class="text-sm text-gray-600 leading-relaxed">
                Platform manajemen konten berita modern dengan fitur lengkap untuk penerbitan dan distribusi konten.
            </p>
        </div>

        <!-- Quick Links -->
        <div>
            <h4 class="font-semibold text-gray-900 mb-4">Akses Cepat</h4>
            <ul class="space-y-2 text-sm">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-indigo-600 transition">
                        <i class="fas fa-chevron-right w-4"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.articles.index') }}" class="text-gray-600 hover:text-indigo-600 transition">
                        <i class="fas fa-chevron-right w-4"></i> Artikel
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:text-indigo-600 transition">
                        <i class="fas fa-chevron-right w-4"></i> Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tags.index') }}" class="text-gray-600 hover:text-indigo-600 transition">
                        <i class="fas fa-chevron-right w-4"></i> Tag
                    </a>
                </li>
            </ul>
        </div>

        <!-- Resources -->
        <div>
            <h4 class="font-semibold text-gray-900 mb-4">Fitur</h4>
            <ul class="space-y-2 text-sm">
                <li>
                    <a href="{{ route('admin.seo-settings.index') }}" class="text-gray-600 hover:text-indigo-600 transition">
                        <i class="fas fa-chevron-right w-4"></i> SEO Settings
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.analytics.index') }}" class="text-gray-600 hover:text-indigo-600 transition">
                        <i class="fas fa-chevron-right w-4"></i> Analytics
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.keywords.index') }}" class="text-gray-600 hover:text-indigo-600 transition">
                        <i class="fas fa-chevron-right w-4"></i> Keywords
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.comments.index') }}" class="text-gray-600 hover:text-indigo-600 transition">
                        <i class="fas fa-chevron-right w-4"></i> Comments
                    </a>
                </li>
            </ul>
        </div>

        <!-- Info -->
        <div>
            <h4 class="font-semibold text-gray-900 mb-4">Informasi</h4>
            <ul class="space-y-2 text-sm">
                <li class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-code text-indigo-600"></i>
                    <span>Laravel {{ \Illuminate\Foundation\Application::VERSION }}</span>
                </li>
                <li class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-database text-indigo-600"></i>
                    <span>Database Ready</span>
                </li>
                <li class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-shield-alt text-indigo-600"></i>
                    <span>Secure Admin</span>
                </li>
                <li class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-user text-indigo-600"></i>
                    <span>{{ Auth::user()->name }}</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="border-t border-gray-200 pt-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <p class="text-sm text-gray-600">
            &copy; {{ now()->year }} NewSMedia. Semua hak dilindungi.
        </p>
        <div class="flex items-center gap-6">
            <a href="#" class="text-gray-600 hover:text-indigo-600 transition text-sm">Kebijakan Privasi</a>
            <a href="#" class="text-gray-600 hover:text-indigo-600 transition text-sm">Syarat Layanan</a>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <i class="fas fa-server"></i>
                <span>Status: Aktif</span>
            </div>
        </div>
    </div>
</div>
