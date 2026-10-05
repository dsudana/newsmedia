@props(['title', 'subtitle' => null, 'icon' => null])

<div class="mb-8">
    <div class="flex items-center gap-3">
        @if($icon)
            <span class="text-2xl">{{ $icon }}</span>
        @endif
        <div>
            <h2 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $title }}</h2>
            @if($subtitle)
                <p class="text-sm text-slate-600 dark:text-gray-400 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    <div class="h-1 w-16 bg-gradient-to-r from-red-600 to-red-400 mt-3 rounded-full"></div>
</div>
