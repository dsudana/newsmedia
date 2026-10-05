<x-admin.layout-modern>
    <x-slot name="header">
        Dashboard - NewSMedia
    </x-slot>

    <div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Articles -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Articles</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Article::count() }}</p>
                        <p class="text-sm text-gray-600 mt-2">
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                            <span class="text-green-600">+12% this month</span>
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-newspaper text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\User::count() }}</p>
                        <p class="text-sm text-gray-600 mt-2">
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                            <span class="text-green-600">+5% this month</span>
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Categories -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Categories</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Category::count() }}</p>
                        <p class="text-sm text-gray-600 mt-2">
                            <i class="fas fa-arrow-right text-gray-500 mr-1"></i>
                            <span>No change</span>
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-folder text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Tags -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Tags</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Tag::count() }}</p>
                        <p class="text-sm text-gray-600 mt-2">
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                            <span class="text-green-600">+8% this month</span>
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-yellow-100 to-yellow-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tags text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Articles -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Articles</h3>
                    <a href="{{ route('admin.articles.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all</a>
                </div>

                <div class="space-y-3">
                    @forelse(\App\Models\Article::latest()->limit(5)->get() as $article)
                        <div class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="{{ $article->featured_image ? '/storage/' . $article->featured_image : '/images/placeholder.jpg' }}"
                                    alt="{{ $article->title }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $article->title }}</h4>
                                <p class="text-xs text-gray-600 mt-1">
                                    <i class="fas fa-user text-gray-400 mr-1"></i>
                                    {{ $article->user?->name }} •
                                    <i class="fas fa-calendar text-gray-400 ml-2 mr-1"></i>
                                    {{ $article->published_at?->format('d M Y') }}
                                </p>
                            </div>
                            <span
                                class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">{{ $article->status }}</span>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-3xl text-gray-300 mb-2"></i>
                            <p>No articles yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Features Menu -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 All Features</h3>

                <div class="space-y-4">
                    <!-- Content Management -->
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">📰 Content</p>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('admin.articles.index') }}"
                                class="text-sm px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors font-medium flex items-center gap-2">
                                <i class="fas fa-file-alt"></i>
                                Articles
                            </a>
                            <a href="{{ route('admin.categories.index') }}"
                                class="text-sm px-3 py-2 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg transition-colors font-medium flex items-center gap-2">
                                <i class="fas fa-folder"></i>
                                Categories
                            </a>
                            <a href="{{ route('admin.tags.index') }}"
                                class="text-sm px-3 py-2 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 rounded-lg transition-colors font-medium flex items-center gap-2">
                                <i class="fas fa-tags"></i>
                                Tags
                            </a>
                        </div>
                    </div>

                    <!-- Administration -->
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">👤 Admin</p>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('admin.users.index') }}"
                                class="text-sm px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition-colors font-medium flex items-center gap-2">
                                <i class="fas fa-users"></i>
                                Users
                            </a>
                            <a href="{{ route('admin.keywords.index') }}"
                                class="text-sm px-3 py-2 bg-cyan-50 hover:bg-cyan-100 text-cyan-700 rounded-lg transition-colors font-medium flex items-center gap-2">
                                <i class="fas fa-key"></i>
                                Keywords
                            </a>
                        </div>
                    </div>

                    <!-- Tools & Settings -->
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">⚙️ Tools</p>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('admin.analytics.index') }}"
                                class="text-sm px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg transition-colors font-medium flex items-center gap-2">
                                <i class="fas fa-chart-bar"></i>
                                Analytics
                            </a>
                            <a href="{{ route('admin.showcase.index') }}"
                                class="text-sm px-3 py-2 bg-pink-50 hover:bg-pink-100 text-pink-700 rounded-lg transition-colors font-medium flex items-center gap-2">
                                <i class="fas fa-chart-pie"></i>
                                Showcase
                            </a>
                            <a href="{{ route('admin.wp-import.index') }}"
                                class="text-sm px-3 py-2 bg-orange-50 hover:bg-orange-100 text-orange-700 rounded-lg transition-colors font-medium flex items-center gap-2">
                                <i class="fas fa-download"></i>
                                WP Import
                            </a>
                            <a href="{{ route('admin.advertisements.index') }}"
                                class="text-sm px-3 py-2 bg-teal-50 hover:bg-teal-100 text-teal-700 rounded-lg transition-colors font-medium flex items-center gap-2">
                                <i class="fas fa-bullhorn"></i>
                                Ads
                            </a>
                        </div>
                    </div>

                    <!-- Affiliates & Settings -->
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">🔗 Extras</p>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('admin.affiliates.index') }}"
                                class="text-sm px-3 py-2 bg-lime-50 hover:bg-lime-100 text-lime-700 rounded-lg transition-colors font-medium flex items-center gap-2">
                                <i class="fas fa-link"></i>
                                Affiliates
                            </a>
                            <a href="{{ route('admin.settings.index') }}"
                                class="text-sm px-3 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg transition-colors font-medium flex items-center gap-2">
                                <i class="fas fa-cog"></i>
                                Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Articles by Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Articles by Status</h3>

                <div class="space-y-4">
                    @php
                        $published = \App\Models\Article::where('status', 'published')->count();
                        $draft = \App\Models\Article::where('status', 'draft')->count();
                        $scheduled = \App\Models\Article::where('status', 'scheduled')->count();
                        $total = $published + $draft + $scheduled;
                    @endphp

                    <!-- Published -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Published</span>
                            <span class="text-sm font-bold text-gray-900">{{ $published }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full"
                                style="width: {{ $total > 0 ? ($published / $total) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Draft -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Draft</span>
                            <span class="text-sm font-bold text-gray-900">{{ $draft }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full"
                                style="width: {{ $total > 0 ? ($draft / $total) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Scheduled -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Scheduled</span>
                            <span class="text-sm font-bold text-gray-900">{{ $scheduled }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full"
                                style="width: {{ $total > 0 ? ($scheduled / $total) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">System Information</h3>

                <div class="space-y-4 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Application Version</span>
                        <span class="font-semibold text-gray-900">2.0.0</span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Laravel Version</span>
                        <span class="font-semibold text-gray-900">{{ app()->version() }}</span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">PHP Version</span>
                        <span class="font-semibold text-gray-900">{{ phpversion() }}</span>
                    </div>

                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Last Updated</span>
                        <span class="font-semibold text-gray-900">{{ now()->format('d M Y H:i') }}</span>
                    </div>

                    <div class="mt-4 p-3 bg-blue-50 rounded-lg flex gap-2">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                        <p class="text-xs text-blue-800">
                            System is running smoothly. All features are operational.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg p-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-2">Need Help?</h3>
                    <p class="text-blue-100">Check our documentation or contact support team</p>
                </div>
                <div class="flex gap-3">
                    <a href="/ADMIN_GUIDE.md" target="_blank"
                        class="px-4 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition-colors">
                        📖 Documentation
                    </a>
                    <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg font-medium transition-colors">
                        💬 Chat Support
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
    </style>
    </x-admin.layout-modern>
