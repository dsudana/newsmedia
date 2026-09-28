<x-layout :$title :$appname>
    <x-navbar :$appname :$navs :navsgroup="$navsGroup"></x-navbar>

    <section class="py-5 px-5 max-w-screen-xl mx-auto">
        <div class="mb-5">
            <x-subhead>
                <x-slot:subtitle>Categories</x-slot:subtitle>
                <x-slot:title>Browse All Categories</x-slot:title>
            </x-subhead>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 my-5">
            @foreach ($categories as $category)
                <a href="{{ route('categories.show', $category) }}"
                    class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                    <div class="flex items-center mb-4">
                        @if($category->icon)
                            <img src="/storage/{{ $category->icon }}" class="w-10 h-10 mr-3 rounded"
                                alt="{{ $category->name }}">
                        @else
                            <div class="w-10 h-10 mr-3 rounded bg-blue-100 flex items-center justify-center text-blue-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                            </div>
                        @endif
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ $category->name }}</h5>
                    </div>
                    <p class="font-normal text-gray-700 dark:text-gray-400 mb-3">
                        {{ $category->description ?? 'Explore articles in this category.' }}</p>
                    <span
                        class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                        {{ $category->articles_count }} Articles
                    </span>
                </a>
            @endforeach
        </div>

        <div class="mt-5 text-center">
            <button onclick="history.back()" type="button"
                class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Back</button>
        </div>
    </section>
</x-layout>