@php
    try {
        $socialLinks = \App\Models\SocialMedia::active()->ordered()->get();
    } catch (\Exception $e) {
        $socialLinks = collect();
    }
@endphp

<div class="flex gap-3">
    @forelse($socialLinks as $social)
        <a href="{{ $social->url }}"
           target="_blank"
           rel="noopener noreferrer"
           class="text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors"
           title="{{ $social->platform }}">
            <i class="fab {{ $social->icon }} text-lg"></i>
            <span class="sr-only">{{ $social->platform }}</span>
        </a>
    @empty
        <!-- No social media links configured -->
    @endforelse
</div>
