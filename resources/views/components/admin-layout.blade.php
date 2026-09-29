@props(['header' => 'Dashboard'])

<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $header }} - NewSMedia Admin</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        @vite(['resources/css/app.css'])
        <style>
            * {
                font-family: 'Inter', sans-serif;
            }

            .sidebar-collapse {
                margin-left: -300px;
            }

            .content-expand {
                width: calc(100% + 300px);
            }

            .transition-all {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
        </style>
    </head>

    <body class="bg-gray-50">
        <div class="flex h-screen bg-gray-100">
            <!-- Sidebar -->
            <aside id="sidebar"
                class="w-72 bg-gradient-to-b from-gray-950 via-gray-900 to-gray-900 text-white shadow-2xl transition-all duration-300 overflow-y-auto fixed lg:relative h-full z-50">
                <!-- Logo Section -->
                <div
                    class="sticky top-0 bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-6 border-b border-purple-500">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-lg">
                            <i class="fas fa-newspaper text-indigo-600 text-lg font-bold"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-white">NewSMedia</h1>
                            <p class="text-xs text-indigo-100">Admin Console</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="px-4 py-6 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg' : 'text-gray-300 hover:bg-gray-700/50' }}">
                        <i class="fas fa-home text-lg group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">Dashboard</span>
                        @if (request()->routeIs('admin.dashboard'))
                            <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                        @endif
                    </a>

                    <!-- Content Management Section -->
                    <div class="mt-8">
                        <p
                            class="px-4 text-xs font-bold text-indigo-300 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-layer-group text-indigo-400"></i>Content</p>

                        <!-- Articles -->
                        <a href="{{ route('admin.articles.index') }}"
                            class="nav-link group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.articles*') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg' : 'text-gray-300 hover:bg-gray-700/50' }}">
                            <i class="fas fa-newspaper text-lg group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium">Articles</span>
                            @if (request()->routeIs('admin.articles*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>

                        <!-- Categories -->
                        <a href="{{ route('admin.categories.index') }}"
                            class="nav-link group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.categories*') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg' : 'text-gray-300 hover:bg-gray-700/50' }}">
                            <i class="fas fa-folder-open text-lg group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium">Categories</span>
                            @if (request()->routeIs('admin.categories*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>

                        <!-- Tags -->
                        <a href="{{ route('admin.tags.index') }}"
                            class="nav-link group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.tags*') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg' : 'text-gray-300 hover:bg-gray-700/50' }}">
                            <i class="fas fa-tags text-lg group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium">Tags</span>
                            @if (request()->routeIs('admin.tags*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>

                        <!-- Homepage Builder -->
                        <a href="{{ route('admin.homepage-builder.index') }}"
                            class="nav-link group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.homepage-builder*') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg' : 'text-gray-300 hover:bg-gray-700/50' }}">
                            <i class="fas fa-paint-brush text-lg group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium">Homepage Builder</span>
                            @if (request()->routeIs('admin.homepage-builder*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>

                        <!-- Platform Showcase -->
                        <a href="{{ route('admin.showcase.index') }}"
                            class="nav-link group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.showcase*') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg' : 'text-gray-300 hover:bg-gray-700/50' }}">
                            <i class="fas fa-chart-pie text-lg group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium">Platform Showcase</span>
                            @if (request()->routeIs('admin.showcase*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>
                    </div>

                    <!-- Management Section -->
                    <div class="mt-8">
                        <p
                            class="px-4 text-xs font-bold text-indigo-300 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-sliders-h text-indigo-400"></i>Management</p>

                        <!-- Users -->
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users*') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg' : 'text-gray-300 hover:bg-gray-700/50' }}">
                            <i class="fas fa-users text-lg group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium">Users</span>
                            @if (request()->routeIs('admin.users*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>

                        <!-- Ads -->
                        <a href="{{ route('admin.advertisements.index') }}"
                            class="nav-link group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.advertisements*') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg' : 'text-gray-300 hover:bg-gray-700/50' }}">
                            <i class="fas fa-image text-lg group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium">Advertisements</span>
                            @if (request()->routeIs('admin.advertisements*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>

                        <!-- Affiliates -->
                        <a href="{{ route('admin.affiliates.index') }}"
                            class="nav-link group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.affiliates*') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg' : 'text-gray-300 hover:bg-gray-700/50' }}">
                            <i class="fas fa-link text-lg group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium">Affiliate Links</span>
                            @if (request()->routeIs('admin.affiliates*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>
                    </div>

                    <!-- Tools Section -->
                    <div class="mt-8">
                        <p
                            class="px-4 text-xs font-bold text-indigo-300 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-wrench text-indigo-400"></i>Tools</p>

                        <!-- Keywords -->
                        <a href="{{ route('admin.keywords.index') }}"
                            class="nav-link group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.keywords*') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg' : 'text-gray-300 hover:bg-gray-700/50' }}">
                            <i class="fas fa-key text-lg group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium">Keywords</span>
                            @if (request()->routeIs('admin.keywords*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>

                        <!-- Analytics -->
                        <a href="{{ route('admin.analytics.index') }}"
                            class="nav-link group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.analytics*') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg' : 'text-gray-300 hover:bg-gray-700/50' }}">
                            <i class="fas fa-chart-line text-lg group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium">Analytics</span>
                            @if (request()->routeIs('admin.analytics*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>

                        <!-- Import/Export -->
                        <a href="{{ route('admin.import-export.index') }}"
                            class="nav-link group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.import-export*') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg' : 'text-gray-300 hover:bg-gray-700/50' }}">
                            <i class="fas fa-exchange-alt text-lg group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium">Import/Export</span>
                            @if (request()->routeIs('admin.import-export*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>

                        <!-- WordPress Import -->
                        <a href="{{ route('admin.wp-import.index') }}"
                            class="nav-link group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.wp-import*') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg' : 'text-gray-300 hover:bg-gray-700/50' }}">
                            <i class="fab fa-wordpress text-lg group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium">WordPress Import</span>
                            @if (request()->routeIs('admin.wp-import*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>

                        <!-- Settings -->
                        <a href="{{ route('admin.settings.index') }}"
                            class="nav-link group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings*') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg' : 'text-gray-300 hover:bg-gray-700/50' }}">
                            <i class="fas fa-cog text-lg group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium">Settings</span>
                            @if (request()->routeIs('admin.settings*'))
                                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
                            @endif
                        </a>
                    </div>
                </nav>

                <!-- Sidebar Footer -->
                <div
                    class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-gray-900 to-transparent border-t border-gray-700">
                    <div class="text-xs text-gray-400">
                        <p class="font-semibold">NEWSMEDIA v2.0</p>
                        <p class="text-gray-500">Production Ready</p>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Navigation Bar -->
                <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-40">
                    <div class="px-4 lg:px-8 py-4 flex items-center justify-between">
                        <!-- Mobile Menu Toggle -->
                        <button onclick="toggleSidebar()"
                            class="lg:hidden p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-bars text-gray-700 text-xl"></i>
                        </button>

                        <!-- Page Header -->
                        <div class="flex-1 ml-4 lg:ml-0">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $header }}</h1>
                            <p class="text-sm text-gray-600 mt-1">{{ now()->format('l, d F Y') }}</p>
                        </div>

                        <!-- Top Right Actions -->
                        <div class="flex items-center gap-4">
                            <!-- Search -->
                            <div class="hidden md:flex relative">
                                <input type="text" placeholder="Search..."
                                    class="w-64 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                            </div>

                            <!-- Notifications -->
                            <button class="relative p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="fas fa-bell text-gray-700 text-xl"></i>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>

                            <!-- User Menu -->
                            <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-600">Administrator</p>
                                </div>
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ auth()->user()->id }}"
                                    alt="User" class="w-10 h-10 rounded-full">
                            </div>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="p-2 hover:bg-red-50 rounded-lg transition-colors text-gray-700 hover:text-red-600">
                                    <i class="fas fa-sign-out-alt text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-auto">
                    <div class="p-4 lg:p-8">
                        <!-- Alerts -->
                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                                <div class="flex gap-3">
                                    <i class="fas fa-exclamation-circle text-red-500 text-lg mt-0.5"></i>
                                    <div>
                                        <h3 class="font-semibold text-red-900">Error</h3>
                                        <ul class="text-sm text-red-800 mt-2 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg animate-fade-in">
                                <div class="flex gap-3">
                                    <i class="fas fa-check-circle text-green-500 text-lg mt-0.5"></i>
                                    <div>
                                        <h3 class="font-semibold text-green-900">Success</h3>
                                        <p class="text-sm text-green-800 mt-1">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                                <div class="flex gap-3">
                                    <i class="fas fa-times-circle text-red-500 text-lg mt-0.5"></i>
                                    <div>
                                        <h3 class="font-semibold text-red-900">Error</h3>
                                        <p class="text-sm text-red-800 mt-1">{{ session('error') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Main Slot -->
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('sidebar-collapse');
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const sidebar = document.getElementById('sidebar');
                const btn = event.target.closest('button[onclick*="toggleSidebar"]');

                if (!sidebar.contains(event.target) && !btn && window.innerWidth < 1024) {
                    sidebar.classList.add('sidebar-collapse');
                }
            });

            // Auto-close sidebar on resize to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    document.getElementById('sidebar').classList.remove('sidebar-collapse');
                }
            });
        </script>

        @vite(['resources/js/app.js'])
    </body>

</html>
