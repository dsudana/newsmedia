<x-admin-layout-modern>
    <x-slot name="header">
        Comment Moderation - Review Comments
    </x-slot>

    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Comment Moderation</h2>
            <p class="text-sm text-gray-600 mt-1">Review and approve pending comments from readers</p>
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
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-hourglass text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Approved</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['approved'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Comments</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-comments text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Comments Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="font-semibold text-gray-900">Pending Comments</h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Author</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Comment</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Article</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Date</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody divide-y>
                    @forelse($pendingComments as $comment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $comment->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $comment->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-md">
                                <p class="line-clamp-2">{{ $comment->content }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('blog.show', $comment->article) }}" target="_blank" class="text-blue-600 hover:underline">
                                    {{ Str::limit($comment->article->title, 30) }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $comment->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 text-green-600 hover:text-green-700 font-medium text-sm px-3 py-1.5 bg-green-50 hover:bg-green-100 rounded transition-colors">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.comments.reject', $comment) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 text-red-600 hover:text-red-700 font-medium text-sm px-3 py-1.5 bg-red-50 hover:bg-red-100 rounded transition-colors" onclick="return confirm('Delete this comment?')">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <i class="fas fa-check-circle text-4xl text-green-300 mb-2 block"></i>
                                <p class="text-gray-500 font-medium">No pending comments</p>
                                <p class="text-sm text-gray-400 mt-1">All comments have been reviewed! 🎉</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pendingComments->hasPages())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                {{ $pendingComments->links() }}
            </div>
        @endif
    </div>
</x-admin-layout-modern>
