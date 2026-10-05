<div
    class="max-w-sm bg-white border-2 border-gray-200 rounded-2xl hover:border-blue-500 dark:bg-gray-800 dark:border-gray-700/50">
    <a class="p-4 block" href="{{ route('articles.show', $article) }}">
        <img class="rounded-lg mb-3 w-full h-48 object-cover"
            src="{{ $article->featured_image ? '/storage/' . $article->featured_image : '/images/default.jpg' }}"
            alt="{{ $article->title }}" />
        <h5 class="mb-2 text-base lg:text-lg font-bold tracking-tight text-gray-800 dark:text-white line-clamp-2">
            {{ $article->title }}</h5>
        <span
            class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ $article->category->name ?? 'Uncategorized' }}</span>
        <div class="flex mt-3 items-center gap-3">
            @if($article->user)
                <img class="w-9 h-9 rounded-full shadow-lg"
                    src="{{ $article->user->avatar ? '/storage/' . $article->user->avatar : 'https://ui-avatars.com/api/?name=' . urlencode($article->user->name) }}"
                    alt="{{ $article->user->name }}" />
                <h5 class="text-xs lg:text-sm text-gray-700">{{$article->user->name}}</h5>
            @else
                <span class="text-xs lg:text-sm text-gray-700">Unknown Author</span>
            @endif
        </div>
    </a>
</div>