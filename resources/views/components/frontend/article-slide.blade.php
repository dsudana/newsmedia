@php
    $imageUrl =
        $article->featured_image && !str_contains($article->featured_image, 'placeholder')
            ? '/storage/' . $article->featured_image
            : '/images/placeholder-news-media.svg';
@endphp

<a href="{{ route('articles.show', $article) }}">
    <div class="border-2 hover:border-blue-500 rounded-lg p-3 flex gap-2 mb-3">
        <img src="{{ $imageUrl }}" alt="{{ $article->title }}" class="w-20 h-20 object-cover rounded">

        <div>
            <h5 class="text-base font-semibold line-clamp-2">
                {{ $article->title }}
            </h5>

            <span
                class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                {{ $article->category->name ?? 'Uncategorized' }}
            </span>

            <span
                class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                {{ $article->user?->name ?? 'Unknown' }}
            </span>
        </div>
    </div>
</a>
