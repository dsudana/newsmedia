<x-admin.layout-modern>
    <div class="space-y-8 pr-4">
        <!-- Hero Banner -->
        <div class="hero-banner p-8 text-white rounded-xl shadow-lg"
            style="background: linear-gradient(135deg, #f59e0b 0%, #ec4899 100%);">
            <div class="relative z-10 max-w-2xl">
                <div class="inline-block mb-4">
                    <span class="text-xs font-semibold uppercase tracking-widest opacity-90">📊 Analytics</span>
                </div>
                <h1 class="text-4xl font-bold mb-3 leading-tight">Affiliate Performance Dashboard</h1>
                <p class="text-lg opacity-90 mb-6">Track clicks, performance metrics, and earnings from affiliate links</p>
                <a href="{{ route('admin.affiliates.index') }}"
                    class="inline-flex px-6 py-3 bg-white text-pink-600 rounded-full font-semibold hover:bg-gray-50 transition items-center gap-2">
                    <span>Manage Affiliate Links</span>
                    <i class="fas fa-arrow-right text-sm"></i>
                </a>
            </div>
        </div>

        <!-- Key Statistics -->
        <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Overall Performance</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Clicks -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Clicks</p>
                            <p class="text-4xl font-bold text-gray-900 mt-2">{{ number_format($totalClicks) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-amber-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-mouse text-amber-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Active Links -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Active Links</p>
                            <p class="text-4xl font-bold text-gray-900 mt-2">{{ $activeLinks }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-link text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Average Clicks Per Link -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Avg Clicks/Link</p>
                            <p class="text-4xl font-bold text-gray-900 mt-2">
                                {{ $activeLinks > 0 ? round($totalClicks / $activeLinks, 1) : 0 }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Affiliate Links -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-star text-amber-500"></i>
                    Top 10 Affiliate Links
                </h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">PRODUCT NAME</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">COMMISSION</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">CLICKS</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($topLinks as $link)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $link->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1"><i class="fas fa-external-link-alt mr-1"></i>{{ $link->url }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($link->commission_type === 'percentage')
                                    <span class="inline-block bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $link->commission_value }}%
                                    </span>
                                @else
                                    <span class="inline-block bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-sm font-medium">
                                        Rp {{ number_format($link->commission_value, 0, ',', '.') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-semibold">
                                    <i class="fas fa-mouse text-xs"></i>
                                    {{ number_format($link->clicks_count ?? 0) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium {{ $link->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <i class="fas {{ $link->is_active ? 'fa-check-circle' : 'fa-circle' }} text-xs"></i>
                                    {{ $link->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-gray-500">No affiliate links yet. Create one to get started.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Articles with Most Affiliate Links -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-file-alt text-pink-500"></i>
                    Articles with Most Affiliate Links
                </h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ARTICLE TITLE</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">LINKS</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">TOTAL CLICKS</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($linksByArticle as $article)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-900 line-clamp-2">{{ $article->title }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($article->published_at)->translatedFormat('d M Y') }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $article->affiliate_links_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-sm font-semibold">
                                    <i class="fas fa-mouse text-xs"></i>
                                    {{ number_format($article->affiliateLinks()->select('affiliate_links.clicks_count')->sum('clicks_count') ?? 0) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('blog.show', $article->slug) }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition font-semibold text-sm">
                                    <i class="fas fa-external-link-alt text-sm"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-gray-500">No articles with affiliate links yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Info Banner -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-600 mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="text-sm text-blue-900 font-medium">Performance Tips</p>
                <p class="text-sm text-blue-800 mt-1">
                    Track affiliate link performance to identify top-performing products. Use this data to optimize article placement and commission structures for better conversions.
                </p>
            </div>
        </div>
    </div>
</x-admin.layout-modern>
