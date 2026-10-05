@props(['title' => 'Follow Us'])

@php
    $socialLinks = \App\Models\SocialMedia::active()->ordered()->get();
@endphp

<section class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ $title }}</h3>
    <div class="flex flex-wrap gap-3">
        @forelse ($socialLinks as $social)
            <a href="{{ $social->url }}"
               target="_blank"
               rel="noopener noreferrer"
               title="{{ $social->platform }}"
               class="w-10 h-10 flex items-center justify-center rounded-lg bg-red-600 hover:bg-red-700 text-white transition duration-300 transform hover:scale-110">
                <i class="{{ $social->icon }}"></i>
            </a>
        @empty
            <p class="text-sm text-gray-500 dark:text-gray-400">No social media links available</p>
        @endforelse
    </div>
</section>
