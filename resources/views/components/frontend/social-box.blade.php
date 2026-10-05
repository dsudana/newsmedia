@props(['icon', 'bgClass', 'count', 'cta', 'url' => '#'])

@php
    $bgColor = match($bgClass) {
        'bg-rn-fb' => '#3B5998',
        'bg-rn-tw' => '#1DA1F2',
        'bg-rn-red' => '#ED1C29',
        default => '#6B7280',
    };
@endphp

<a href="{{ $url }}" class="block text-white rounded-lg overflow-hidden hover:shadow-lg transition-all duration-300 group" style="background-color: {{ $bgColor }};">
    <div class="flex items-center gap-4 px-5 py-4">
        <div class="w-14 h-14 rounded-lg flex items-center justify-center transition-all duration-300 shrink-0" style="background-color: rgba(255, 255, 255, 0.2);">
            <i class="fa-brands {{ $icon }} text-2xl"></i>
        </div>
        <div class="flex-1">
            <p class="text-[11px] font-medium uppercase opacity-75">{{ $cta }}</p>
            <p class="text-[16px] font-bold leading-tight">{{ $count }}</p>
        </div>
    </div>
</a>
