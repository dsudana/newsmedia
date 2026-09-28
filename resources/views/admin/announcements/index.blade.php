<x-admin-layout-modern>
    <x-slot name="header">
        Announcements - Manage Important Messages
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Manage Announcements</h2>
                <p class="text-sm text-gray-600 mt-1">Create and manage important announcements for your users</p>
            </div>
            <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium transition-colors">
                <i class="fas fa-plus"></i> New Announcement
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex gap-3">
                <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                <div>
                    <p class="font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Announcements</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $announcements->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bullhorn text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Now</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Announcement::active()->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pinned</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Announcement::where('is_pinned', true)->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-100 to-yellow-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-thumbtack text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Title</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Priority</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Active From</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody divide-y>
                    @forelse($announcements as $announcement)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if($announcement->is_pinned)
                                        <span class="text-yellow-500" title="Pinned"><i class="fas fa-thumbtack"></i></span>
                                    @endif
                                    <span class="font-medium text-gray-900">{{ $announcement->title }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $announcement->getPriorityColor() }}">
                                    {{ $announcement->getPriorityLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $announcement->starts_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $announcement->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $announcement->is_active ? '✓ Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.announcements.edit', $announcement) }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm" onclick="return confirm('Delete this announcement?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <i class="fas fa-inbox text-3xl text-gray-300 mb-2 block"></i>
                                <p class="text-gray-500 font-medium">No announcements found</p>
                                <p class="text-sm text-gray-400 mt-1">Create your first announcement to get started</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($announcements->hasPages())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                {{ $announcements->links() }}
            </div>
        @endif
    </div>
</x-admin-layout-modern>
