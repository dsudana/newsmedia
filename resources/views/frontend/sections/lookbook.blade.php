{{-- Lookbook Section - Modern Design --}}
@php
    $tagFilter = $section->config['tag_filter'] ?? '';
    $title = $section->config['title'] ?? 'Lookbook Gallery';
@endphp

<section class="py-16 px-4 sm:px-6 lg:px-8 bg-white">
    <div class="max-w-6xl mx-auto">
        {{-- Section Header --}}
        @if ($title)
            <div class="text-center mb-12">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="h-1 w-16 bg-red-600 rounded"></div>
                    <span class="text-sm font-bold text-red-600 uppercase tracking-widest">Gallery</span>
                    <div class="h-1 w-16 bg-red-600 rounded"></div>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
                @if ($section->description)
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ $section->description }}</p>
                @endif
            </div>
        @endif

        {{-- Lookbook Items Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($data['lookbook_items'] ?? [] as $item)
                <div
                    class="relative h-72 rounded-xl overflow-hidden group shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                    {{-- Background Image --}}
                    @if ($item->image_url ?? false)
                        <img src="{{ $item->image_url }}" alt="{{ $item->title ?? 'Lookbook Item' }}"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500" />
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-red-100 to-red-50"></div>
                    @endif

                    {{-- Gradient Overlay --}}
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60 group-hover:opacity-70 transition-all duration-300">
                    </div>

                    {{-- Content --}}
                    <div class="absolute inset-0 flex flex-col justify-end p-6">
                        {{-- Badge --}}
                        <div class="mb-4">
                            <span
                                class="inline-block px-3 py-1 bg-red-600 text-white text-xs font-bold uppercase tracking-widest rounded">
                                Featured
                            </span>
                        </div>

                        {{-- Title --}}
                        @if ($item->title ?? false)
                            <h3 class="text-2xl font-bold text-white mb-2 leading-tight">
                                {{ $item->title }}
                            </h3>
                        @endif

                        {{-- Description --}}
                        @if ($item->description ?? false)
                            <p class="text-sm text-gray-100 line-clamp-2 mb-4">
                                {{ $item->description }}
                            </p>
                        @endif

                        {{-- CTA Link --}}
                        @if ($item->link_url ?? false)
                            <a href="{{ $item->link_url }}"
                                class="inline-flex items-center gap-2 text-red-300 font-bold text-sm group-hover:text-white group-hover:gap-3 transition-all">
                                View More
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-16">
                        <i class="fas fa-image text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">No lookbook items available.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Optional: Tag Filter Info --}}
        @if ($tagFilter)
            <div class="text-center mt-12">
                <p class="text-sm text-gray-600">
                    Filtering by: <span class="font-bold text-red-600">{{ $tagFilter }}</span>
                </p>
            </div>
        @endif
    </div>
</section>
