{{-- Testimonial Section - Modern Design --}}
@php
    $bgColor = $section->config['background_color'] ?? '#f5f5f5';
@endphp

<section class="py-16 px-4 sm:px-6 lg:px-8" style="background-color: {{ $bgColor }};">
    <div class="max-w-6xl mx-auto">
        {{-- Section Header --}}
        @if ($section->title)
            <div class="text-center mb-12">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="h-1 w-16 bg-red-600 rounded"></div>
                    <span class="text-sm font-bold text-red-600 uppercase tracking-widest">Testimonials</span>
                    <div class="h-1 w-16 bg-red-600 rounded"></div>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ $section->title }}</h2>
                @if ($section->description)
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ $section->description }}</p>
                @endif
            </div>
        @endif

        {{-- Testimonials Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($data['testimonials'] ?? [] as $testimonial)
                <div
                    class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 p-8 flex flex-col">
                    {{-- Star Rating --}}
                    <div class="flex items-center gap-1 mb-6">
                        @for ($i = 0; $i < ($testimonial->rating ?? 5); $i++)
                            <i class="fas fa-star text-yellow-400"></i>
                        @endfor
                        @for ($i = $testimonial->rating ?? 5; $i < 5; $i++)
                            <i class="fas fa-star text-gray-300"></i>
                        @endfor
                    </div>

                    {{-- Quote Mark --}}
                    <div class="text-5xl text-red-200 font-bold mb-4 leading-none">{{-- --}}"</div>

                    {{-- Quote Text --}}
                    <p class="text-gray-700 italic text-lg leading-relaxed mb-6 flex-grow">
                        {{ $testimonial->quote ?? ($testimonial->content ?? '') }}
                    </p>

                    {{-- Divider --}}
                    <div class="h-1 w-12 bg-red-600 rounded mb-6"></div>

                    {{-- Author --}}
                    <div class="flex items-center gap-4">
                        {{-- Avatar --}}
                        <div
                            class="w-12 h-12 rounded-full bg-red-600 flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($testimonial->author_name ?? 'A', 0, 1)) }}
                        </div>

                        <div>
                            <p class="font-bold text-gray-900 text-sm">{{ $testimonial->author_name ?? 'Anonymous' }}
                            </p>
                            @if ($testimonial->author_title ?? false)
                                <p class="text-xs text-gray-600">{{ $testimonial->author_title }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-16">
                        <i class="fas fa-comments text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">No testimonials available.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
