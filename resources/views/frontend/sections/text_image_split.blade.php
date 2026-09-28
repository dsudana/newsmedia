{{-- Text Image Split Section - Modern Design --}}
@php
    $title = $section->config['title'] ?? '';
    $description = $section->config['description'] ?? '';
    $ctaText = $section->config['cta_text'] ?? '';
    $ctaUrl = $section->config['cta_url'] ?? '#';
    $imageUrl = $section->config['image_url'] ?? '';
    $imagePosition = $section->config['image_position'] ?? 'right';
@endphp

<section class="py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            {{-- Text Content --}}
            <div class="{{ $imagePosition === 'left' ? 'lg:order-2' : '' }}">
                {{-- Accent Line --}}
                <div class="h-1 w-16 bg-red-600 rounded mb-6"></div>

                {{-- Title --}}
                @if ($title)
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        {{ $title }}
                    </h2>
                @endif

                {{-- Description --}}
                @if ($description)
                    <p class="text-gray-700 text-lg leading-relaxed mb-8 max-w-lg">
                        {{ $description }}
                    </p>
                @endif

                {{-- CTA Button --}}
                @if ($ctaText && $ctaUrl)
                    <a href="{{ $ctaUrl }}"
                        class="inline-flex items-center gap-2 px-8 py-4 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition-all transform hover:scale-105 shadow-lg">
                        {{ $ctaText }}
                        <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                @endif
            </div>

            {{-- Image --}}
            <div class="{{ $imagePosition === 'left' ? 'lg:order-1' : '' }}">
                <div
                    class="relative h-80 sm:h-96 md:h-full rounded-2xl overflow-hidden shadow-2xl border-4 border-gray-100">
                    @if ($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $title }}"
                            class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-300" />
                    @else
                        <div
                            class="w-full h-full bg-gradient-to-br from-red-100 to-red-50 flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-image text-red-300 text-5xl mb-4"></i>
                                <p class="text-red-400 font-semibold">No Image Available</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
