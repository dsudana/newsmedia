<x-admin-layout-modern>
    <div class="space-y-6 pr-4">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Advertisements</h1>
                <p class="text-sm text-gray-600 mt-1">Manage advertising campaigns and placements</p>
            </div>
            <a href="{{ route('admin.ads.create') }}"
                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg font-semibold hover:shadow-lg transition-shadow flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>New Ad</span>
            </a>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-4 rounded-lg flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Total Ads</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ \App\Models\Ad::count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-image text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Active</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">
                            {{ \App\Models\Ad::where('is_active', true)->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Inactive</p>
                        <p class="text-2xl font-bold text-gray-600 mt-1">
                            {{ \App\Models\Ad::where('is_active', false)->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-gray-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Placements</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">
                            {{ \App\Models\Ad::distinct('placement')->count('placement') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cube text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ads Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Ad Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Placement</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($ads as $ad)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ $ad->name }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        {{ ucfirst($ad->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600 font-mono">{{ $ad->placement }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $ad->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        <i
                                            class="fas {{ $ad->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1.5"></i>
                                        {{ $ad->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.ads.edit', $ad) }}"
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.ads.destroy', $ad) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-600 font-medium">No ads found</p>
                                    <p class="text-sm text-gray-500 mt-1">Create your first advertisement</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (method_exists($ads, 'hasPages') && $ads->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $ads->links() }}
                </div>
            @endif
        </div>
    </div>
</x-x-admin-layout-modern>
