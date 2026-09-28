<x-admin-layout-modern header="View Comment">
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Comment Details</h1>
            <a href="{{ route('admin.comments.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm mt-2">
                ← Back to Comments
            </a>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="col-span-2 space-y-6">
            <!-- Comment Card -->
            <div class="bg-white rounded-md border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $comment->name }}
                        </h3>
                        <p class="text-sm text-gray-600">{{ $comment->email }}</p>
                    </div>
                    <div>
                        @if($comment->status === 'pending')
                            <span class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-2"></i> Pending Review
                            </span>
                        @elseif($comment->status === 'approved')
                            <span class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-2"></i> Approved
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times mr-2"></i> Rejected
                            </span>
                        @endif
                    </div>
                </div>

                <div class="prose prose-sm max-w-none mb-6 p-4 bg-gray-50 rounded-md border border-gray-200">
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $comment->content }}</p>
                </div>

                <div class="flex items-center text-sm text-gray-600 space-x-4">
                    <span><i class="fas fa-calendar mr-2"></i>{{ $comment->created_at->format('d M Y H:i') }}</span>
                    @if($comment->user)
                        <span><i class="fas fa-user mr-2"></i>Logged-in user</span>
                    @endif
                </div>
            </div>

            <!-- Article Info -->
            <div class="bg-white rounded-md border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Article</h3>
                <a href="{{ route('admin.articles.show', $comment->article) }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                    {{ $comment->article->title }}
                </a>
                <p class="text-sm text-gray-600 mt-2">
                    Category: <span class="font-medium">{{ $comment->article->category->name }}</span>
                </p>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-4">
            <!-- Status Actions -->
            <div class="bg-white rounded-md border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-2">
                    @if($comment->status !== 'approved')
                        <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition font-medium">
                                <i class="fas fa-check mr-2"></i> Approve
                            </button>
                        </form>
                    @endif

                    @if($comment->status !== 'rejected')
                        <form action="{{ route('admin.comments.reject', $comment) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition font-medium">
                                <i class="fas fa-ban mr-2"></i> Reject
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Delete this comment permanently?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition font-medium">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-white rounded-md border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-4">Comment Info</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Status</p>
                        <p class="font-medium text-gray-900 capitalize">{{ $comment->status }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Posted On</p>
                        <p class="font-medium text-gray-900">{{ $comment->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Author</p>
                        <p class="font-medium text-gray-900">
                            @if($comment->user)
                                {{ $comment->user->name }}
                            @else
                                {{ $comment->name }} (Anonymous)
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout-modern>
