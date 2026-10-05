@props(['header' => 'Dashboard'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $header }} - NewSMedia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        * { font-family: 'Inter', sans-serif; }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
        }

        .sidebar {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            color: #94a3b8;
        }

        .sidebar-item:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
            padding-left: 1.5rem;
        }

        .sidebar-item.active {
            background: linear-gradient(90deg, #6366f1 0%, #5b21b6 100%);
            color: #ffffff;
            border-radius: 0 8px 8px 0;
        }

        .hero-banner {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(99, 102, 241, 0.2);
            position: relative;
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(100px, -100px);
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .chart-container {
            position: relative;
            height: 200px;
            margin-top: 1rem;
        }

        .mentor-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            flex-shrink-0;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-purple {
            background: rgba(168, 85, 247, 0.1);
            color: #a855f7;
        }

        .scroll-container {
            overflow-y: auto;
            overflow-x: hidden;
        }

        .scroll-container::-webkit-scrollbar {
            width: 6px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .scroll-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="m-0 p-0">
    <div class="flex h-screen gap-0 relative" x-data="{ sidebarOpen: false }">
        <!-- Mobile Overlay -->
        <div class="fixed inset-0 bg-black/50 z-30 md:hidden cursor-pointer" x-show="sidebarOpen" @click="sidebarOpen = false" x-transition></div>

        <!-- Left Sidebar -->
        <div class="sidebar hidden md:flex fixed md:relative md:flex-shrink-0 w-64 md:w-80 h-full md:h-auto rounded-none p-6 scroll-container flex-col z-40 transform transition-transform duration-300 md:translate-x-0" :class="{'!flex': sidebarOpen}" x-cloak>
            <!-- Logo -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-newspaper text-white text-xl font-bold"></i>
                    </div>
                    <div>
                        <h2 class="text-white text-lg font-bold">NewSMedia</h2>
                    </div>
                </div>
            </div>

            <!-- Overview Section -->
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">OVERVIEW</p>
            <nav class="space-y-2 mb-8">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-chart-line text-lg w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-inbox text-lg w-5"></i>
                    <span class="font-medium">Inbox</span>
                </a>
            </nav>

            <!-- Content Management Section -->
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Content</p>
            <nav class="space-y-2 mb-8">
                <a href="{{ route('admin.articles.index') }}" class="sidebar-item {{ request()->routeIs('admin.articles*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-book text-lg w-5"></i>
                    <span class="font-medium">Articles</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="sidebar-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-folder text-lg w-5"></i>
                    <span class="font-medium">Categories</span>
                </a>
                <a href="{{ route('admin.tags.index') }}" class="sidebar-item {{ request()->routeIs('admin.tags*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-tag text-lg w-5"></i>
                    <span class="font-medium">Tags</span>
                </a>
                <a href="{{ route('admin.homepage-builder.index') }}" class="sidebar-item {{ request()->routeIs('admin.homepage-builder*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-paint-brush text-lg w-5"></i>
                    <span class="font-medium">Builder</span>
                </a>
                <a href="{{ route('admin.showcase.index') }}" class="sidebar-item {{ request()->routeIs('admin.showcase*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-chart-pie text-lg w-5"></i>
                    <span class="font-medium">Showcase</span>
                </a>
            </nav>

            <!-- CMS Features Section -->
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">CMS Features</p>
            <nav class="space-y-2 mb-8">
                <a href="{{ route('admin.announcements.index') }}" class="sidebar-item {{ request()->routeIs('admin.announcements*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-bullhorn text-lg w-5"></i>
                    <span class="font-medium">Announcements</span>
                </a>
                <a href="{{ route('admin.events.index') }}" class="sidebar-item {{ request()->routeIs('admin.events*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-calendar text-lg w-5"></i>
                    <span class="font-medium">Events</span>
                </a>
                <a href="{{ route('admin.seo-settings.index') }}" class="sidebar-item {{ request()->routeIs('admin.seo-settings*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-search text-lg w-5"></i>
                    <span class="font-medium">SEO Settings</span>
                </a>
                <a href="{{ route('admin.comments.index') }}" class="sidebar-item {{ request()->routeIs('admin.comments*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-comments text-lg w-5"></i>
                    <span class="font-medium">Moderate Comments</span>
                </a>
            </nav>

            <!-- Management Section -->
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Management</p>
            <nav class="space-y-2 mb-auto">
                <a href="{{ route('admin.users.index') }}" class="sidebar-item {{ request()->routeIs('admin.users*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-users text-lg w-5"></i>
                    <span class="font-medium">Users</span>
                </a>
                <a href="{{ route('admin.advertisements.index') }}" class="sidebar-item {{ request()->routeIs('admin.advertisements*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-image text-lg w-5"></i>
                    <span class="font-medium">Advertisements</span>
                </a>
                <a href="{{ route('admin.keywords.index') }}" class="sidebar-item {{ request()->routeIs('admin.keywords*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-key text-lg w-5"></i>
                    <span class="font-medium">Keywords</span>
                </a>
                <a href="{{ route('admin.analytics.index') }}" class="sidebar-item {{ request()->routeIs('admin.analytics*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-chart-bar text-lg w-5"></i>
                    <span class="font-medium">Analytics</span>
                </a>
                <a href="{{ route('admin.wp-import.index') }}" class="sidebar-item {{ request()->routeIs('admin.wp-import*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-download text-lg w-5"></i>
                    <span class="font-medium">WP Import</span>
                </a>
                <a href="{{ route('admin.affiliates.index') }}" class="sidebar-item {{ request()->routeIs('admin.affiliates*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-link text-lg w-5"></i>
                    <span class="font-medium">Affiliates</span>
                </a>
            </nav>

            <!-- Settings Section -->
            <div class="mt-8 pt-8 border-t border-gray-700">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Settings</p>
                <nav class="space-y-2">
                    <a href="{{ route('admin.settings.index') }}" class="sidebar-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                        <i class="fas fa-cog text-lg w-5"></i>
                        <span class="font-medium">Settings</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="sidebar-item w-full text-left flex items-center gap-3 px-4 py-3 rounded-lg">
                            <i class="fas fa-sign-out-alt text-lg w-5"></i>
                            <span class="font-medium">Logout</span>
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col w-full min-w-0 p-4 lg:p-6 overflow-y-auto">
            <!-- Header with Search -->
            <div class="flex items-center justify-between mb-6 lg:mb-8 gap-2 lg:gap-4 flex-wrap lg:flex-nowrap">
                <!-- Mobile Menu Button -->
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden block p-2 rounded-lg hover:bg-gray-100 border border-gray-200 bg-white">
                    <i class="fas fa-bars text-gray-600 text-lg"></i>
                </button>

                <!-- Search Bar -->
                <div class="flex-1 min-w-0">
                    <input type="text" placeholder="Search your content..." class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2 lg:gap-3 flex-shrink-0">
                    <button class="w-9 lg:w-10 h-9 lg:h-10 bg-white rounded-lg flex items-center justify-center hover:bg-gray-50 border border-gray-200">
                        <i class="fas fa-bell text-gray-600 text-sm lg:text-base"></i>
                    </button>
                    <button class="w-9 lg:w-10 h-9 lg:h-10 bg-white rounded-lg flex items-center justify-center hover:bg-gray-50 border border-gray-200">
                        <i class="fas fa-ellipsis-v text-gray-600 text-sm lg:text-base"></i>
                    </button>
                </div>
            </div>

            <!-- Main Content Slot -->
            <div>
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="pt-8 border-t border-gray-200">
                <x-admin.footer />
            </div>
        </div>

    </div>
</body>
</html>
