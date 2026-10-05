<div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">
        <i class="fas fa-tag text-red-600 mr-2"></i>Tags
    </h3>

    @php
        $tags = $tags ?? \App\Models\Tag::orderByDesc('articles_count')
            ->limit(30)
            ->pluck('name', 'slug');
    @endphp

    <div class="flex flex-wrap gap-2">
        @forelse ($tags as $slug => $name)
            <a href="{{ route('tags.show', $slug) }}"
               class="inline-block px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded hover:bg-red-50 hover:text-red-600 transition-colors">
                {{ $name }}
            </a>
        @empty
            <p class="text-gray-500 text-sm">No tags available</p>
        @endforelse
    </div>
</div>
