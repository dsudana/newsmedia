<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

    <!-- Card 1: Blog Management -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg hover:-translate-y-1 transition">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white text-2xl mb-4">
            📰
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Blog Management</h3>
        <p class="text-sm text-gray-600 mb-4">Create, edit, and organize articles with categories and tags</p>
        <a href="{{ route('admin.articles.index') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">View Dashboard →</a>
    </div>

    <!-- Card 2: Homepage Builder -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg hover:-translate-y-1 transition">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-2xl mb-4">
            🎨
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Homepage Builder</h3>
        <p class="text-sm text-gray-600 mb-4">Drag-and-drop page builder with 8 section types</p>
        <a href="{{ route('admin.homepage-builder.index') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">Configure →</a>
    </div>

    <!-- Card 3: Search & Discovery -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg hover:-translate-y-1 transition">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center text-white text-2xl mb-4">
            🔍
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Search & Discovery</h3>
        <p class="text-sm text-gray-600 mb-4">Full-text search with category and tag filters</p>
        <a href="{{ route('blog.search') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">View →</a>
    </div>

    <!-- Card 4: Content Analytics -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg hover:-translate-y-1 transition">
        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center text-white text-2xl mb-4">
            📊
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Content Analytics</h3>
        <p class="text-sm text-gray-600 mb-4">Track article views, engagement, and performance</p>
        <a href="{{ route('admin.analytics.index') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">View Analytics →</a>
    </div>

    <!-- Card 5: User Management -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg hover:-translate-y-1 transition">
        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center text-white text-2xl mb-4">
            👥
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">User Management</h3>
        <p class="text-sm text-gray-600 mb-4">Manage registered users, roles, and permissions</p>
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">View Users →</a>
    </div>

    <!-- Card 6: Newsletter System -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg hover:-translate-y-1 transition">
        <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg flex items-center justify-center text-white text-2xl mb-4">
            📧
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Newsletter System</h3>
        <p class="text-sm text-gray-600 mb-4">Subscriber management and email campaigns</p>
        @if(Route::has('admin.subscribers.index'))
            <a href="{{ route('admin.subscribers.index') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">Manage Subscribers →</a>
        @else
            <a href="#" class="text-gray-400 cursor-not-allowed font-medium text-sm" title="Coming soon">Manage Subscribers →</a>
        @endif
    </div>

    <!-- Card 7: WordPress Import -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg hover:-translate-y-1 transition">
        <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-lg flex items-center justify-center text-white text-2xl mb-4">
            📥
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">WordPress Import</h3>
        <p class="text-sm text-gray-600 mb-4">Bulk import articles and images from WordPress</p>
        <a href="{{ route('admin.wp-import.index') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">Import →</a>
    </div>

    <!-- Card 8: Ad Management -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 hover:shadow-lg hover:-translate-y-1 transition">
        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center text-white text-2xl mb-4">
            📢
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Ad Management</h3>
        <p class="text-sm text-gray-600 mb-4">Create, schedule, and track ad placements</p>
        <a href="{{ route('admin.ads.index') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">Manage Ads →</a>
    </div>

</div>
