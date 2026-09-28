<x-admin-layout-modern>
    <div class="space-y-6 pr-4">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Affiliate Links</h1>
                <p class="text-sm text-gray-600 mt-1">Manage and track your affiliate links</p>
            </div>
            <a href="{{ route('admin.affiliates.create') }}"
                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg font-semibold hover:shadow-lg transition-shadow flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>New Link</span>
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Total Links</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ \App\Models\AffiliateLink::count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-link text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Active</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">
                            {{ \App\Models\AffiliateLink::where('is_active', true)->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Total Clicks</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">
                            {{ \App\Models\AffiliateLink::sum('clicks_count') ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-mouse text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Affiliate Links Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Cloaked URL
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Clicks</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($links as $link)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ $link->name }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ url('go/' . $link->slug) }}" target="_blank"
                                        class="text-indigo-600 hover:text-indigo-700 text-sm break-all">
                                        {{ url('go/' . $link->slug) }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-mouse text-gray-400"></i>
                                        <span class="font-bold text-gray-900">{{ $link->clicks_count ?? 0 }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $link->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        <i
                                            class="fas {{ $link->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1.5"></i>
                                        {{ $link->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.affiliates.edit', $link) }}"
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.affiliates.destroy', $link) }}" method="POST"
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
                                    <p class="text-gray-600 font-medium">No affiliate links found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-x-admin-layout-modern>
