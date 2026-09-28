<x-admin-layout-modern>
    <div class="space-y-6 pr-4">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Analytics</h1>
                <p class="text-sm text-gray-600 mt-1">Article views and engagement metrics</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-md p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Total Views</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($totalViews) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-eye text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-md p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Unique Visitors</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($totalUniqueVisitors) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-md p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Avg Scroll Depth</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">{{ number_format($averageScrollDepth, 1) }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-arrow-down text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-md p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Top Article Views</p>
                        <p class="text-2xl font-bold text-orange-600 mt-1">{{ $topArticles->first()?->views_count ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-star text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Articles -->
        <div class="bg-white rounded-md shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Top 10 Articles</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Article</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Views</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Published</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($topArticles as $article)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ Str::limit($article->title, 50) }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-semibold bg-blue-100 text-blue-700">
                                        {{ number_format($article->views_count) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $article->published_at?->format('M d, Y') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.analytics.show', $article) }}"
                                       class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-600">
                                    No articles with views yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Views -->
        <div class="bg-white rounded-md shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Recent Views (Last 30)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Article ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Views</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Unique Visitors</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Scroll Depth</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentViews as $view)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-gray-900">Article #{{ $view->article_id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">{{ number_format($view->views) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">{{ number_format($view->unique_visitors) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">{{ number_format($view->scroll_depth, 1) }}%</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $view->created_at->format('M d, Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-600">
                                    No analytics data yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout-modern>
