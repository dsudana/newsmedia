<x-admin.layout-modern>
    <div class="space-y-6 pr-4">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tags</h1>
                <p class="text-sm text-gray-600 mt-1">Organize and manage content tags</p>
            </div>
            <a href="{{ route('admin.tags.create') }}"
                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg font-semibold hover:shadow-lg transition-shadow flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>New Tag</span>
            </a>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-4 rounded-lg flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Stats Card -->
        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-semibold uppercase">Total Tags</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ \App\Models\Tag::count() }}</p>
                </div>
                <div
                    class="w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tags text-indigo-600 text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Tags Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($tags as $tag)
                <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="inline-flex items-center gap-2 mb-2">
                                <i class="fas fa-tag text-indigo-600"></i>
                                <h3 class="text-lg font-bold text-gray-900">{{ $tag->name }}</h3>
                            </div>
                            <p class="text-xs text-gray-500 font-mono">{{ $tag->slug }}</p>
                        </div>
                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition">
                            <a href="{{ route('admin.tags.edit', $tag) }}"
                                class="p-2 text-indigo-600 hover:bg-indigo-50 rounded transition" title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="inline"
                                onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded transition"
                                    title="Delete">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Articles tagged</span>
                            <span class="font-bold text-indigo-600 text-lg">{{ $tag->articles_count ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-lg border border-gray-200 p-12 text-center">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 font-medium text-lg">No tags found</p>
                    <p class="text-gray-500 text-sm mt-1">Create your first tag to organize articles</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if (method_exists($tags, 'hasPages') && $tags->hasPages())
            <div class="flex justify-center">
                {{ $tags->links() }}
            </div>
        @endif
    </div>
</x-x-admin-layout-modern>
