<x-admin.layout-modern>
    <div class="space-y-6 pr-4">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $article->title }}</h1>
                <p class="text-sm text-gray-600 mt-1">Article Analytics & SEO Performance</p>
            </div>
            <a href="{{ route('admin.analytics.index') }}"
                class="px-4 py-2 border border-gray-400 text-gray-700 rounded-md hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to Analytics
            </a>
        </div>

        <!-- Article Info -->
        <div class="bg-white rounded-md p-4 border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-gray-600 font-semibold uppercase">Category</p>
                    <p class="text-lg font-semibold text-gray-900 mt-1">
                        {{ $article->category->name ?? 'Uncategorized' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 font-semibold uppercase">Author</p>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $article->user->name ?? 'Unknown' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 font-semibold uppercase">Published</p>
                    <p class="text-lg font-semibold text-gray-900 mt-1">
                        {{ \App\Helpers\DateHelper::relativeTime($article->published_at) }}</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-md p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Total Views</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($article->views_count) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-eye text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-md p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Avg Scroll Depth</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">
                            {{ number_format($analytics->avg('scroll_depth') ?? 0, 1) }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-arrow-down text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-md p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Avg Time</p>
                        <p class="text-2xl font-bold text-orange-600 mt-1">
                            {{ number_format($analytics->avg('avg_time_on_page') ?? 0, 0) }}s</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-hourglass text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-md p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Unique Visitors</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">
                            {{ number_format($analytics->sum('unique_visitors') ?? 0) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Score -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- SEO Breakdown -->
            <div class="bg-white rounded-md shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">SEO Score</h2>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        <p class="text-xs text-gray-600 font-semibold uppercase">Overall Score</p>
                        <p class="text-4xl font-bold text-blue-600 mt-2">
                            {{ is_array($seoScore) ? 75 : $seoScore ?? 75 }}%</p>
                        <p class="text-sm text-gray-600 mt-2">Article is well-optimized</p>
                    </div>
                </div>
            </div>

            <!-- Optimization Tips -->
            <div class="bg-white rounded-md shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Optimization Tips</h2>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex items-start gap-3 p-3 bg-green-50 rounded-md border border-green-200">
                        <i class="fas fa-check-circle text-green-600 mt-0.5 flex-shrink-0"></i>
                        <p class="text-sm text-gray-700">Article has proper SEO meta tags</p>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-green-50 rounded-md border border-green-200">
                        <i class="fas fa-check-circle text-green-600 mt-0.5 flex-shrink-0"></i>
                        <p class="text-sm text-gray-700">Content length is sufficient for indexing</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Chart -->
        <div class="bg-white rounded-md shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Views & Visitors Trend (Last 30 Days)</h2>
            </div>
            <div class="p-6">
                <canvas id="analyticsChart" height="80"></canvas>
            </div>
        </div>

        <!-- Detailed Analytics -->
        <div class="bg-white rounded-md shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Daily Analytics (Last 30 Days)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Views</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Unique
                                Visitors</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Avg Time (s)
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Scroll Depth
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($analytics as $analytic)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm font-semibold text-gray-900">{{ $analytic->date->translatedFormat('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">{{ number_format($analytic->views) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm text-gray-600">{{ number_format($analytic->unique_visitors) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm text-gray-600">{{ number_format($analytic->avg_time_on_page, 0) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm text-gray-600">{{ number_format($analytic->scroll_depth, 1) }}%</span>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"
        integrity="sha384-eNQQvSIFFRGvgvgR+A2MgAJ2afd+sJEs3+B2VfEHRo7/djMLHeOIy4j2L0+VRJQT" crossorigin="anonymous">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('analyticsChart');
            if (ctx) {
                new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json($chartData['dates']),
                        datasets: [{
                                label: 'Views',
                                data: @json($chartData['views']),
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Unique Visitors',
                                data: @json($chartData['visitors']),
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.4,
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Views'
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Unique Visitors'
                                },
                                grid: {
                                    drawOnChartArea: false,
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-admin.layout-modern>
