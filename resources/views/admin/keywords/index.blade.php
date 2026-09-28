<x-admin-layout-modern>
    <div class="space-y-6 pr-4">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Keywords</h1>
                <p class="text-sm text-gray-600 mt-1">Manage SEO keywords and generate articles automatically</p>
            </div>
            <a href="{{ route('admin.keywords.create') }}"
                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg font-semibold hover:shadow-lg transition-shadow flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>New Keyword</span>
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
                        <p class="text-xs text-gray-600 font-semibold uppercase">Total Keywords</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ \App\Models\Keyword::count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-key text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1">
                            {{ \App\Models\Keyword::where('status', 'pending')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Generated</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">
                            {{ \App\Models\Keyword::where('status', 'generated')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Categories</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">{{ \App\Models\Category::count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-folder text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Keywords Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Keyword</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Category</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Intent</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Articles</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($keywords as $keyword)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $keyword->keyword }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ Str::limit($keyword->description, 60) }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                        {{ $keyword->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $keyword->intent === 'informational' ? 'bg-blue-100 text-blue-700' : ($keyword->intent === 'commercial' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700') }}">
                                        {{ ucfirst($keyword->intent) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $keyword->status === 'generated' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        <i
                                            class="fas {{ $keyword->status === 'generated' ? 'fa-check-circle' : 'fa-hourglass-half' }} mr-1.5"></i>
                                        {{ ucfirst($keyword->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-indigo-600">{{ $keyword->articles_count ?? 0 }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.keywords.show', $keyword) }}"
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                            title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.keywords.edit', $keyword) }}"
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if ($keyword->status === 'pending')
                                            <form action="{{ route('admin.keywords.generate', $keyword) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition"
                                                    title="Generate Article">
                                                    <i class="fas fa-magic"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.keywords.destroy', $keyword) }}" method="POST"
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
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-600 font-medium">No keywords found</p>
                                    <p class="text-sm text-gray-500 mt-1">Create your first keyword to start generating
                                        articles</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (method_exists($keywords, 'hasPages') && $keywords->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $keywords->links() }}
                </div>
            @endif
        </div>
    </div>
</x-x-admin-layout-modern>
