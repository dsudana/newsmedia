<x-admin.layout-modern>
    <div class="space-y-6 pr-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Halaman Legal</h1>
                <p class="text-sm text-gray-600 mt-1">Kelola Privacy Policy, Terms of Service, dll</p>
            </div>
            <a href="{{ route('admin.legal-pages.create') }}" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-semibold flex items-center gap-2">
                <i class="fas fa-plus"></i>Halaman Baru
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-4 rounded-md flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-md shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Judul</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Slug</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Diperbarui</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($pages as $page)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ $page->title }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600 font-mono">/{{ $page->slug }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600">{{ $page->updated_at->format('d M Y, H:i') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.legal-pages.edit', $page) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-md transition">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.legal-pages.destroy', $page) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-md transition" onclick="return confirm('Hapus halaman ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-600">
                                    Belum ada halaman legal. <a href="{{ route('admin.legal-pages.create') }}" class="text-blue-600 hover:underline">Buat yang pertama</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin.layout-modern>
