<x-admin.layout-modern>
    <div class="space-y-8 pr-4">
        <!-- Hero Banner -->
        <div class="hero-banner p-8 text-white rounded-xl shadow-lg"
            style="background: linear-gradient(135deg, #ec4899 0%, #f59e0b 100%);">
            <div class="relative z-10 max-w-2xl">
                <div class="inline-block mb-4">
                    <span class="text-xs font-semibold uppercase tracking-widest opacity-90">📢 Advertising</span>
                </div>
                <h1 class="text-4xl font-bold mb-3 leading-tight">Manage Your Advertisements</h1>
                <p class="text-lg opacity-90 mb-6">Create and manage banner ads, AdSense, and custom script placements</p>
                <a href="{{ route('admin.advertisements.create') }}"
                    class="inline-flex px-6 py-3 bg-white text-pink-600 rounded-full font-semibold hover:bg-gray-50 transition items-center gap-2">
                    <span>Create New Ad</span>
                    <i class="fas fa-arrow-right text-sm"></i>
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-3">
                <i class="fas fa-check-circle text-green-600"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Statistics -->
        <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Advertisement Statistics</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total Ads -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Ads</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $total_ads }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Active Ads -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Active Ads</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $active_ads }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Inactive Ads -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Inactive Ads</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $inactive_ads }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-times-circle text-gray-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Placements -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Placements</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $placements_count }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cube text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advertisements Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">AD NAME</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">TYPE</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">PLACEMENT</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">STATUS</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ACTIONS</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($advertisements as $ad)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $ad->name }}</p>
                                    @if ($ad->deleted_at)
                                        <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded inline-block mt-1">Dihapus</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if ($ad->type === 'banner')
                                <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">Banner</span>
                            @elseif ($ad->type === 'adsense')
                                <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">AdSense</span>
                            @elseif ($ad->type === 'script')
                                <span class="inline-block bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-xs font-medium">Script</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @switch($ad->placement)
                                @case('header_banner')
                                    header
                                    @break
                                @case('sidebar_top')
                                    sidebar_top
                                    @break
                                @case('sidebar_bottom')
                                    sidebar_bottom
                                    @break
                                @case('content_middle')
                                    content_middle
                                    @break
                                @default
                                    {{ $ad->placement }}
                            @endswitch
                        </td>
                        <td class="px-6 py-4">
                            @if ($ad->deleted_at)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-trash-alt text-xs"></i> Dihapus
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium {{ $ad->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <i class="fas {{ $ad->is_active ? 'fa-check-circle' : 'fa-circle' }} text-xs"></i> {{ $ad->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.advertisements.edit', $ad) }}" class="text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 p-2 rounded-lg transition" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                @if (!$ad->deleted_at)
                                    <form action="{{ route('admin.advertisements.destroy', $ad) }}" method="POST" class="inline" onsubmit="return confirm('Hapus iklan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 hover:bg-red-50 p-2 rounded-lg transition" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.advertisements.restore', $ad->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 hover:bg-green-50 p-2 rounded-lg transition" title="Restore">
                                            <i class="fas fa-undo-alt"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                            <p class="text-gray-500 mb-4">Tidak ada iklan. Buat iklan pertama Anda sekarang.</p>
                            <a href="{{ route('admin.advertisements.create') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                                <i class="fas fa-plus mr-2"></i>Buat Iklan Baru
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        <!-- Pagination -->
        @if ($advertisements->hasPages())
            <div class="mt-6">
                {{ $advertisements->links() }}
            </div>
        @endif
    </div>
</x-admin.layout-modern>

<style>
    /* Pagination styling */
    .pagination {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        padding: 1.5rem 0;
    }

    .pagination a,
    .pagination span {
        padding: 0.5rem 0.75rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        font-size: 0.875rem;
    }

    .pagination a:hover {
        background-color: #f3f4f6;
    }

    .pagination .active span {
        background-color: #ec4899;
        color: white;
        border-color: #ec4899;
    }

    .pagination .disabled span {
        color: #9ca3af;
        cursor: not-allowed;
    }
</style>
