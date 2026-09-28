<x-admin-layout-modern>
    <div class="space-y-8 pr-4">
        <!-- Hero Banner -->
        <div class="hero-banner p-8 text-white rounded-xl shadow-lg"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="relative z-10 max-w-2xl">
                <div class="inline-block mb-4">
                    <span class="text-xs font-semibold uppercase tracking-widest opacity-90">📰 News Platform</span>
                </div>
                <h1 class="text-4xl font-bold mb-3 leading-tight">Master News Management with NewSMedia</h1>
                <p class="text-lg opacity-90 mb-6">Create, manage, and publish quality content across your platform</p>
                <a href="{{ route('admin.articles.create') }}"
                    class="inline-flex px-6 py-3 bg-white text-purple-600 rounded-full font-semibold hover:bg-gray-50 transition items-center gap-2">
                    <span>Create Article</span>
                    <i class="fas fa-arrow-right text-sm"></i>
                </a>
            </div>
        </div>

        <!-- Statistics -->
        <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Platform Statistics</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total Articles -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Articles</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Article::count() }}</p>
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
                        </div>
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-yellow-100 to-yellow-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tags text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Articles Grid -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">📰 Latest Articles</h2>
                <a href="{{ route('admin.articles.index') }}"
                    class="text-sm text-blue-600 hover:text-blue-700 font-medium">See all</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse(\App\Models\Article::latest()->limit(3)->get() as $article)
                    <div
                        class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-200 hover:shadow-lg transition-shadow">
                        <!-- Article Image -->
                        <div
                            class="h-48 bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-5xl overflow-hidden">
                            @if ($article->featured_image)
                                <img src="/storage/{{ $article->featured_image }}" alt="{{ $article->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-image opacity-50"></i>
                            @endif
                        </div>

                        <!-- Article Info -->
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <span
                                    class="badge badge-blue text-xs font-semibold px-3 py-1 bg-blue-100 text-blue-700 rounded-full">
                                    {{ $article->category?->name ?? 'Uncategorized' }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $article->status }}</span>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $article->title }}</h3>

                            <!-- Author Info -->
                            <div class="flex items-center gap-3 mb-4">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    {{ strtoupper(substr($article->user?->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="text-sm">
                                    <p class="font-medium text-gray-900">{{ $article->user?->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ $article->published_at?->format('d M Y') }}</p>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full"
                                    style="width: {{ rand(30, 90) }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No articles yet</p>
                        <a href="{{ route('admin.articles.create') }}"
                            class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Create
                            First Article</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Features Grid -->
        <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-4">🎯 All Features</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <!-- Articles -->
                <a href="{{ route('admin.articles.index') }}"
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center hover:shadow-lg transition-shadow hover:border-blue-300">
                    <div class="text-3xl mb-2">📰</div>
                    <p class="font-semibold text-gray-900 text-sm">Articles</p>
                    <p class="text-xs text-gray-500">Manage content</p>
                </a>

                <!-- Categories -->
                <a href="{{ route('admin.categories.index') }}"
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center hover:shadow-lg transition-shadow hover:border-green-300">
                    <div class="text-3xl mb-2">📁</div>
                    <p class="font-semibold text-gray-900 text-sm">Categories</p>
                    <p class="text-xs text-gray-500">Organize news</p>
                </a>

                <!-- Tags -->
                <a href="{{ route('admin.tags.index') }}"
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center hover:shadow-lg transition-shadow hover:border-yellow-300">
                    <div class="text-3xl mb-2">🏷️</div>
                    <p class="font-semibold text-gray-900 text-sm">Tags</p>
                    <p class="text-xs text-gray-500">Tag content</p>
                </a>

                <!-- Homepage Builder -->
                <a href="{{ route('admin.homepage-builder.index') }}"
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center hover:shadow-lg transition-shadow hover:border-purple-300">
                    <div class="text-3xl mb-2">🎨</div>
                    <p class="font-semibold text-gray-900 text-sm">Builder</p>
                    <p class="text-xs text-gray-500">Design pages</p>
                </a>

                <!-- Users -->
                <a href="{{ route('admin.users.index') }}"
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center hover:shadow-lg transition-shadow hover:border-indigo-300">
                    <div class="text-3xl mb-2">👥</div>
                    <p class="font-semibold text-gray-900 text-sm">Users</p>
                    <p class="text-xs text-gray-500">Manage team</p>
                </a>

                <!-- Analytics -->
                <a href="{{ route('admin.analytics.index') }}"
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center hover:shadow-lg transition-shadow hover:border-red-300">
                    <div class="text-3xl mb-2">📊</div>
                    <p class="font-semibold text-gray-900 text-sm">Analytics</p>
                    <p class="text-xs text-gray-500">View insights</p>
                </a>

                <!-- Showcase -->
                <a href="{{ route('admin.showcase.index') }}"
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center hover:shadow-lg transition-shadow hover:border-pink-300">
                    <div class="text-3xl mb-2">💎</div>
                    <p class="font-semibold text-gray-900 text-sm">Showcase</p>
                    <p class="text-xs text-gray-500">Platform stats</p>
                </a>

                <!-- WordPress Import -->
                <a href="{{ route('admin.wp-import.index') }}"
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center hover:shadow-lg transition-shadow hover:border-orange-300">
                    <div class="text-3xl mb-2">📥</div>
                    <p class="font-semibold text-gray-900 text-sm">WP Import</p>
                    <p class="text-xs text-gray-500">Import posts</p>
                </a>

                <!-- Ads -->
                <a href="{{ route('admin.ads.index') }}"
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center hover:shadow-lg transition-shadow hover:border-teal-300">
                    <div class="text-3xl mb-2">📢</div>
                    <p class="font-semibold text-gray-900 text-sm">Ads</p>
                    <p class="text-xs text-gray-500">Manage ads</p>
                </a>

                <!-- Keywords -->
                <a href="{{ route('admin.keywords.index') }}"
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center hover:shadow-lg transition-shadow hover:border-cyan-300">
                    <div class="text-3xl mb-2">🔑</div>
                    <p class="font-semibold text-gray-900 text-sm">Keywords</p>
                    <p class="text-xs text-gray-500">SEO keywords</p>
                </a>

                <!-- Affiliates -->
                <a href="{{ route('admin.affiliates.index') }}"
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center hover:shadow-lg transition-shadow hover:border-lime-300">
                    <div class="text-3xl mb-2">🔗</div>
                    <p class="font-semibold text-gray-900 text-sm">Affiliates</p>
                    <p class="text-xs text-gray-500">Affiliate links</p>
                </a>

                <!-- Settings -->
                <a href="{{ route('admin.settings.index') }}"
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center hover:shadow-lg transition-shadow hover:border-slate-300">
                    <div class="text-3xl mb-2">⚙️</div>
                    <p class="font-semibold text-gray-900 text-sm">Settings</p>
                    <p class="text-xs text-gray-500">Configure</p>
                </a>
            </div>
        </div>

        <!-- Continue Managing -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">📋 Quick Actions</h2>
                <a href="{{ route('admin.articles.index') }}"
                    class="text-sm text-blue-600 hover:text-blue-700 font-medium">See all</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.articles.create') }}"
                    class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 hover:shadow-lg transition-shadow border border-blue-200">
                    <div class="text-3xl mb-3">✍️</div>
                    <p class="font-semibold text-blue-900">Write Article</p>
                    <p class="text-sm text-blue-700 mt-1">Create new content</p>
                </a>

                <a href="{{ route('admin.categories.create') }}"
                    class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 hover:shadow-lg transition-shadow border border-green-200">
                    <div class="text-3xl mb-3">📂</div>
                    <p class="font-semibold text-green-900">New Category</p>
                    <p class="text-sm text-green-700 mt-1">Add category</p>
                </a>

                <a href="{{ route('admin.users.create') }}"
                    class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 hover:shadow-lg transition-shadow border border-purple-200">
                    <div class="text-3xl mb-3">👤</div>
                    <p class="font-semibold text-purple-900">Add User</p>
                    <p class="text-sm text-purple-700 mt-1">Create account</p>
                </a>

                <a href="{{ route('admin.showcase.index') }}"
                    class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl p-6 hover:shadow-lg transition-shadow border border-pink-200">
                    <div class="text-3xl mb-3">🎯</div>
                    <p class="font-semibold text-pink-900">Showcase</p>
                    <p class="text-sm text-pink-700 mt-1">View overview</p>
                </a>
            </div>
        </div>
    </div>

    <style>
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-blue {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    </x-admin-layout-modern>
