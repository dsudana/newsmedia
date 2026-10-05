<x-admin.layout-modern>
    <div class="space-y-8 pr-4">
        <!-- Hero Banner -->
        <div class="hero-banner p-8 text-white rounded-xl shadow-lg"
            style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
            <div class="relative z-10 max-w-2xl">
                <div class="inline-block mb-4">
                    <span class="text-xs font-semibold uppercase tracking-widest opacity-90">🔍 Keyword Management</span>
                </div>
                <h1 class="text-4xl font-bold mb-3 leading-tight">{{ $keyword->keyword }}</h1>
                <p class="text-lg opacity-90 mb-6">Category: <span class="font-semibold">{{ $keyword->category->name }}</span></p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.keywords.edit', $keyword) }}"
                        class="inline-flex px-6 py-3 bg-white text-cyan-600 rounded-full font-semibold hover:bg-gray-50 transition items-center gap-2">
                        <i class="fas fa-edit"></i>Edit Keyword
                    </a>
                    <form action="{{ route('admin.keywords.generate', $keyword) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="inline-flex px-6 py-3 bg-cyan-400 text-white rounded-full font-semibold hover:bg-cyan-500 transition items-center gap-2">
                            <i class="fas fa-sparkles"></i>Generate Article
                        </button>
                    </form>
                    <a href="{{ route('admin.keywords.index') }}"
                        class="inline-flex px-6 py-3 bg-white/20 text-white rounded-full font-semibold hover:bg-white/30 transition items-center gap-2">
                        <i class="fas fa-arrow-left"></i>Back to Keywords
                    </a>
                </div>
            </div>
        </div>

        <!-- Keyword Details -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Main Details -->
            <div class="md:col-span-2 space-y-6">
                <!-- Basic Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Keyword Details</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Keyword</label>
                            <p class="text-gray-900 font-semibold mt-1">{{ $keyword->keyword }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Category</label>
                            <p class="text-gray-900 mt-1">
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ $keyword->category->name }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Description</label>
                            <p class="text-gray-700 mt-1">{{ $keyword->description ?? 'No description' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Search Intent</label>
                                <p class="text-gray-900 font-semibold mt-1 capitalize">{{ $keyword->intent }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Target Words</label>
                                <p class="text-gray-900 font-semibold mt-1">{{ $keyword->target_words }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Focus Tone</label>
                                <p class="text-gray-900 font-semibold mt-1">{{ $keyword->focus_tone }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Humanizer</label>
                                <p class="text-gray-900 font-semibold mt-1">
                                    @if($keyword->use_humanizer)
                                        <span class="text-green-600"><i class="fas fa-check-circle"></i> Enabled</span>
                                    @else
                                        <span class="text-gray-500"><i class="fas fa-times-circle"></i> Disabled</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Generated Articles -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Generated Articles ({{ $keyword->articles->count() }})</h2>
                    @if($keyword->articles->count() > 0)
                        <div class="space-y-3">
                            @foreach($keyword->articles as $article)
                                <a href="{{ route('admin.articles.edit', $article) }}" class="block p-4 border border-gray-200 rounded-lg hover:border-cyan-400 hover:bg-cyan-50 transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900 hover:text-cyan-600">{{ $article->title }}</h3>
                                            <p class="text-sm text-gray-500 mt-1">
                                                Created: {{ $article->created_at->translatedFormat('d M Y') }}
                                                • Status: <span class="font-medium capitalize">{{ $article->status }}</span>
                                            </p>
                                        </div>
                                        <i class="fas fa-arrow-right text-cyan-400 text-sm"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                            <p class="text-gray-500 mb-4">No articles generated yet from this keyword</p>
                            <form action="{{ route('admin.keywords.generate', $keyword) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-block px-6 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition">
                                    <i class="fas fa-sparkles mr-2"></i>Generate First Article
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Stats Sidebar -->
            <div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Statistics</h3>
                    <div class="space-y-4">
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                            <p class="text-sm text-gray-600">Category</p>
                            <p class="text-2xl font-bold text-blue-600 mt-2">{{ $keyword->category->name }}</p>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                            <p class="text-sm text-gray-600">Articles Generated</p>
                            <p class="text-2xl font-bold text-green-600 mt-2">{{ $keyword->articles->count() }}</p>
                        </div>
                        <div class="p-4 bg-purple-50 rounded-lg border border-purple-100">
                            <p class="text-sm text-gray-600">Created</p>
                            <p class="text-lg font-semibold text-purple-600 mt-2">{{ $keyword->created_at->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t space-y-2">
                        <a href="{{ route('admin.keywords.edit', $keyword) }}" class="block w-full text-center px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition font-medium">
                            Edit Keyword
                        </a>
                        <form action="{{ route('admin.keywords.destroy', $keyword) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="w-full px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition font-medium delete-btn">
                                <i class="fas fa-trash mr-2"></i>Delete Keyword
                            </button>
                        </form>
                    </div>

                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const deleteBtn = document.querySelector('.delete-btn');
                        if (deleteBtn) {
                            deleteBtn.addEventListener('click', function(e) {
                                e.preventDefault();
                                const form = this.closest('form');

                                Swal.fire({
                                    title: 'Delete Keyword?',
                                    text: "You won't be able to undo this action!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#ef4444',
                                    cancelButtonColor: '#6b7280',
                                    confirmButtonText: 'Yes, delete it!',
                                    cancelButtonText: 'Cancel'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        form.submit();
                                    }
                                });
                            });
                        }
                    });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout-modern>
