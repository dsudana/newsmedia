<x-admin.layout-modern>
    <div class="space-y-6 pr-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Newsletter Subscribers</h1>
                <p class="text-sm text-gray-600 mt-1">Kelola subscriber newsletter</p>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-4 rounded-md flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-md p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Total Subscribers</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $totalSubscribers }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-envelope text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-md p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Active</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $activeSubscribers }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-md p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Inactive</p>
                        <p class="text-2xl font-bold text-gray-600 mt-1">{{ $totalSubscribers - $activeSubscribers }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-ban text-gray-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-md shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Subscribers</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal Subscribe</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($subscribers as $subscriber)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ $subscriber->email }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($subscriber->is_active)
                                        <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-green-100 text-green-700">
                                            <i class="fas fa-check-circle mr-1.5"></i>Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-700">
                                            <i class="fas fa-ban mr-1.5"></i>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600">{{ $subscriber->subscribed_at->format('d M Y, H:i') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($subscriber->is_active)
                                            <form action="{{ route('admin.newsletter.unsubscribe', $subscriber) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-md transition"
                                                        title="Nonaktifkan" onclick="return confirm('Nonaktifkan subscriber ini?')">
                                                    <i class="fas fa-pause"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.newsletter.delete-subscriber', $subscriber) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-md transition"
                                                    title="Hapus" onclick="return confirm('Hapus subscriber ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-600">
                                    Belum ada subscriber
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($subscribers->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $subscribers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin.layout-modern>
