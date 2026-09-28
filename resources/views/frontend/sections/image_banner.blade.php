{{-- Image Banner Section - Modern Design --}}
@php
    $imageUrl = $section->config['image_url'] ?? '';
    $title = $section->config['title'] ?? '';
    $subtitle = $section->config['subtitle'] ?? '';
    $ctaText = $section->config['cta_text'] ?? '';
    $ctaUrl = $section->config['cta_url'] ?? '#';
    $heightMode = $section->config['height_mode'] ?? 'sm';
    $alignment = $section->config['alignment'] ?? 'center';

    $heightClass = match($heightMode) {
        'auto' => 'h-auto',
        'sm' => 'h-72',
        'md' => 'h-96',
        'lg' => 'h-screen-1/2',
        'xl' => 'h-screen',
        default => 'h-96'
    };

    $alignmentClass = match($alignment) {
        'left' => 'text-left items-start justify-center',
        'center' => 'text-center items-center justify-center',
        'right' => 'text-right items-end justify-center',
        default => 'text-center items-center justify-center'
    };
@endphp

<section class="relative w-full overflow-hidden">
    {{-- Background Image --}}
    <div class="{{ $heightClass }} w-full bg-gray-900 relative">
        @if($imageUrl)
            <img src="{{ $imageUrl }}"
                 alt="{{ $title }}"
                 class="w-full h-full object-cover"
            />
        @else
            <div class="w-full h-full bg-gradient-to-r from-gray-800 to-gray-900"></div>
        @endif

        {{-- Dark Overlay with gradient --}}
        <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent opacity-60"></div>

        {{-- Content --}}
        <div class="absolute inset-0 flex flex-col {{ $alignmentClass }} px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                {{-- Accent Line --}}
                <div class="h-1 w-16 bg-red-600 mb-6 {{ $alignment === 'left' ? '' : ($alignment === 'right' ? 'ml-auto' : 'mx-auto') }}"></div>

                {{-- Title --}}
                @if($title)
                    <h2 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-4 leading-tight">
                        {{ $title }}
                    </h2>
                @endif

                {{-- Subtitle --}}
                @if($subtitle)
                    <p class="text-lg sm:text-xl md:text-2xl text-gray-100 mb-8 leading-relaxed">
                        {{ $subtitle }}
                    </p>
                @endif

                {{-- CTA Button --}}
                @if($ctaText && $ctaUrl)
                    <a href="{{ $ctaUrl }}"
                       class="inline-flex items-center gap-2 px-8 py-4 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition-all transform hover:scale-105 shadow-lg">
                        {{ $ctaText }}
                        <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
