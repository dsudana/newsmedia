<div class="h-full flex flex-col justify-between">
    <div>
        <div class="p-6 border-b">
            <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-gray-800">
                NewsMedia
            </a>
        </div>

        <nav class="mt-6 px-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-900 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                {{ __('Dashboard') }}
            </a>

            @can('manage_articles')
                <a href="{{ route('admin.articles.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.articles.*') ? 'bg-indigo-100 text-indigo-900 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                    {{ __('Articles') }}
                </a>
            @endcan

            @can('manage_categories')
                <a href="{{ route('admin.categories.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-100 text-indigo-900 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                    {{ __('Categories') }}
                </a>
            @endcan

            @can('manage_tags')
                <a href="{{ route('admin.tags.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.tags.*') ? 'bg-indigo-100 text-indigo-900 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                    {{ __('Tags') }}
                </a>
            @endcan

            <!-- CMS Features -->
            <div class="mt-4 pt-4 border-t">
                <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase">CMS Features</p>

                <a href="{{ route('admin.announcements.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.announcements.*') ? 'bg-indigo-100 text-indigo-900 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                    📢 {{ __('Announcements') }}
                </a>

                <a href="{{ route('admin.events.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.events.*') ? 'bg-indigo-100 text-indigo-900 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                    📅 {{ __('Events') }}
                </a>

                <a href="{{ route('admin.seo-settings.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.seo-settings.*') ? 'bg-indigo-100 text-indigo-900 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                    🔍 {{ __('SEO Settings') }}
                </a>

                <a href="{{ route('admin.comments.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.comments.*') ? 'bg-indigo-100 text-indigo-900 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                    💬 {{ __('Moderate Comments') }}
                </a>
            </div>

            @can('manage_users')
                <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-100 text-indigo-900 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                    {{ __('Users') }}
                </a>
            @endcan

            @can('manage_ads')
                <a href="{{ route('admin.ads.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.ads.*') ? 'bg-indigo-100 text-indigo-900 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                    {{ __('Ads') }}
                </a>
            @endcan

            @can('manage_ads')
                <a href="{{ route('admin.affiliates.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.affiliates.*') ? 'bg-indigo-100 text-indigo-900 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                    {{ __('Affiliate Links') }}
                </a>
            @endcan

            @can('manage_settings')
                <a href="{{ route('admin.settings.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-100 text-indigo-900 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                    {{ __('Settings') }}
                </a>
            @endcan
        </nav>
    </div>

    <div class="p-4 border-t">
        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-900">
            &larr; Back to App
        </a>
    </div>
</div>