{{-- Image Banner Section --}}
@php
    $imageUrl = $section->config['image_url'] ?? '';
    $title = $section->config['title'] ?? '';
    $subtitle = $section->config['subtitle'] ?? '';
    $ctaText = $section->config['cta_text'] ?? '';
    $ctaUrl = $section->config['cta_url'] ?? '#';
    $heightMode = $section->config['height_mode'] ?? 'sm';
    $alignment = $section->config['alignment'] ?? 'center';

    $heightClass = match ($heightMode) {
        'auto' => 'h-auto',
        'sm' => 'h-72',
        'md' => 'h-96',
        'lg' => 'h-[60vh]',
        'xl' => 'h-screen',
        default => 'h-96',
    };

    $alignmentClass = match ($alignment) {
        'left' => 'text-left items-start justify-center',
        'center' => 'text-center items-center justify-center',
        'right' => 'text-right items-end justify-center',
        default => 'text-center items-center justify-center',
    };
@endphp

<style>
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .banner-container {
        border-radius: 8px;
        overflow: hidden;
    }

    .banner-content {
        animation: slideInUp 0.8s ease-out;
        border-radius: 8px;
    }

    .banner-image {
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 8px;
    }

    .banner-overlay {
        border-radius: 8px;
    }

    .banner-container:hover .banner-image {
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .banner-content h2 {
            font-size: 2rem;
            line-height: 1.2;
        }

        .banner-content p {
            font-size: 1rem;
        }
    }
</style>

<section class="relative w-full rounded-lg overflow-hidden banner-container">

    <div class="{{ $heightClass }} relative w-full bg-gray-900 rounded-lg overflow-hidden group">

        {{-- Background Image --}}
        @if ($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $title }}" class="banner-image w-full h-full object-cover" />
        @else
            <div class="w-full h-full bg-gradient-to-r from-gray-800 to-gray-900"></div>
        @endif

        {{-- Overlay --}}
        <div class="banner-overlay absolute inset-0 bg-gradient-to-r from-black/50 via-black/25 to-black/40">
        </div>

        {{-- Content --}}
        <div class="banner-content absolute inset-0 flex flex-col {{ $alignmentClass }} px-6 sm:px-8 lg:px-12">

            <div class="max-w-3xl">

                {{-- Accent line --}}
                <div
                    class="h-1 w-20 bg-gradient-to-r from-red-600 to-red-400 mb-6 {{ $alignment === 'left' ? '' : ($alignment === 'right' ? 'ml-auto' : 'mx-auto') }}">
                </div>

                {{-- Title --}}
                @if ($title)
                    <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-4 leading-tight drop-shadow-xl">
                        {{ $title }}
                    </h2>
                @endif

                {{-- Subtitle --}}
                @if ($subtitle)
                    <p
                        class="text-lg sm:text-xl lg:text-2xl text-gray-100 mb-8 leading-relaxed drop-shadow-lg max-w-2xl">
                        {{ $subtitle }}
                    </p>
                @endif

                {{-- Button --}}
                @if ($ctaText && $ctaUrl)
                    <a href="{{ $ctaUrl }}"
                        class="inline-flex items-center gap-3 px-8 py-4 rounded-lg bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-red-500/40 hover:from-red-700 hover:to-red-800">

                        <span>{{ $ctaText }}</span>

                        <i class="fas fa-arrow-right text-sm"></i>

                    </a>
                @endif

            </div>

        </div>

    </div>

</section>
