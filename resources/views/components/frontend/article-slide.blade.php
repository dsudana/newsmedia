@php
    $imageUrl =
        $article->featured_image && !str_contains($article->featured_image, 'placeholder')
            ? '/storage/' . $article->featured_image
            : '/images/placeholder-news-media.svg';
@endphp

<a href="{{ route('articles.show', $article) }}" class="stagger-item">
    <div class="border-2 rounded-lg p-3 flex gap-2 mb-3 card-lift border-smooth shadow-depth">
        <div class="w-20 h-20 rounded overflow-hidden image-zoom-container">
            <img src="{{ $imageUrl }}" alt="{{ $article->title }}" class="w-20 h-20 object-cover image-zoom">
        </div>

        <div>
            <h5 class="text-base font-semibold line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300 link-accent">
                {{ $article->title }}
            </h5>

            <span
                class="inline-block bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors duration-300">
                {{ $article->category->name ?? 'Uncategorized' }}
            </span>

            <span
                class="inline-block bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300 group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition-colors duration-300">
                {{ $article->user?->name ?? 'Unknown' }}
            </span>
        </div>
    </div>
</a>
