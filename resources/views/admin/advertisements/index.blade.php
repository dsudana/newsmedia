@extends('layouts.admin')

@section('title', 'Kelola Iklan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Advertisements</h1>
            <p class="text-gray-600 text-sm mt-1">Manage advertising campaigns and placements</p>
        </div>
        <a href="{{ route('admin.advertisements.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition inline-flex items-center gap-2">
            <i class="fas fa-plus"></i>New Ad
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg flex items-center gap-3">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Ads -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">TOTAL ADS</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $total_ads }}</p>
                </div>
                <div class="bg-blue-100 text-blue-600 rounded-lg p-3">
                    <i class="fas fa-image text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Ads -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">ACTIVE</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $active_ads }}</p>
                </div>
                <div class="bg-green-100 text-green-600 rounded-lg p-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Inactive Ads -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-gray-400">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">INACTIVE</p>
                    <p class="text-3xl font-bold text-gray-600 mt-2">{{ $inactive_ads }}</p>
                </div>
                <div class="bg-gray-100 text-gray-600 rounded-lg p-3">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Placements -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">PLACEMENTS</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $placements_count }}</p>
                </div>
                <div class="bg-purple-100 text-purple-600 rounded-lg p-3">
                    <i class="fas fa-cube text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Advertisements Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
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
        background-color: #4f46e5;
        color: white;
        border-color: #4f46e5;
    }

    .pagination .disabled span {
        color: #9ca3af;
        cursor: not-allowed;
    }
</style>
@endsection
