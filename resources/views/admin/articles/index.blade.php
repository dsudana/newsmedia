<x-admin.layout-modern>
    <div class="space-y-6 pr-4">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Articles</h1>
                <p class="text-sm text-gray-600 mt-1">Manage all your articles in one place</p>
            </div>
            <a href="{{ route('admin.articles.create') }}"
                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg font-semibold hover:shadow-lg transition-shadow flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>New Article</span>
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
            @php
                $totalArticles = \App\Models\Article::count();
                $published = \App\Models\Article::where('status', 'published')->count();
                $draft = \App\Models\Article::where('status', 'draft')->count();
                $scheduled = \App\Models\Article::where('status', 'scheduled')->count();
            @endphp

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Total</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalArticles }}</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-newspaper text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Published</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $published }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Draft</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $draft }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-pen text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Scheduled</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $scheduled }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Articles Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Article</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Category</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Author</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Published</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($articles as $article)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 bg-gray-200">
                                            <img src="{{ $article->featured_image ? '/storage/' . $article->featured_image : '/images/placeholder.jpg' }}"
                                                alt="{{ $article->title }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ Str::limit($article->title, 40) }}</p>
                                            <p class="text-sm text-gray-600 mt-0.5">
                                                {{ Str::limit($article->excerpt ?? $article->content, 60) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                        {{ $article->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600">{{ $article->user->name }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $article->status === 'published' ? 'bg-green-100 text-green-700' : ($article->status === 'draft' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                                        <i
                                            class="fas {{ $article->status === 'published' ? 'fa-check-circle' : ($article->status === 'draft' ? 'fa-file-pen' : 'fa-clock') }} mr-1.5"></i>
                                        {{ ucfirst($article->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600">
                                        {{ $article->published_at ? $article->published_at->translatedFormat('d M Y') : '-' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.articles.edit', $article) }}"
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <x-delete-button
                                            :route="route('admin.articles.destroy', $article)"
                                            model="Article"
                                            title="Delete Article"
                                        />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-600 font-medium">No articles found</p>
                                    <p class="text-sm text-gray-500 mt-1">Create your first article to get started</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (method_exists($articles, 'hasPages') && $articles->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    </div>
</x-x-admin-layout-modern>
