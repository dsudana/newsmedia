<x-admin.layout-modern>
    <div class="space-y-6 pr-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Newsletter Templates</h1>
                <p class="text-sm text-gray-600 mt-1">Kelola template email newsletter</p>
            </div>
            <a href="{{ route('admin.newsletter.create-template') }}" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-semibold flex items-center gap-2">
                <i class="fas fa-plus"></i>Template Baru
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
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Nama Template</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Tipe</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Pengiriman</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($templates as $template)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ $template->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $template->subject }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-gray-600">{{ ucfirst($template->type) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($template->is_active)
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
                                    <p class="text-sm text-gray-600">{{ $template->sends_count }} pengiriman</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.newsletter.edit-template', $template) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-md transition">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.newsletter.delete-template', $template) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-md transition" onclick="return confirm('Hapus template ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-600">
                                    Belum ada template
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin.layout-modern>
