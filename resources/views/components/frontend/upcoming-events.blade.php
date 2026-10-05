@if ($events->count() > 0)
    <section class="mb-12">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">📅 Acara Mendatang</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($events as $event)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        @if ($event->featured_image)
                            <div class="h-40 bg-gray-200 overflow-hidden">
                                <img src="{{ $event->featured_image }}" alt="{{ $event->title }}"
                                    class="w-full h-full object-cover">
                            </div>
                        @else
                            <div
                                class="h-40 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                <span class="text-4xl">📅</span>
                            </div>
                        @endif

                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span
                                    class="text-xs font-semibold text-blue-600 uppercase">{{ $event->category ?? 'Event' }}</span>
                                <span class="text-xs px-2 py-1 rounded {{ $event->status_color }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </div>

                            <h3 class="font-bold text-gray-900 mb-2 line-clamp-2">{{ $event->title }}</h3>

                            <div class="space-y-1 text-sm text-gray-600 mb-3">
                                <p>📍 {{ $event->location ?? 'TBD' }}</p>
                                <p>🕐 {{ $event->formatted_date }} {{ $event->time }}</p>
                                @if ($event->capacity)
                                    <p>👥 {{ $event->registered }}/{{ $event->capacity }} terdaftar</p>
                                @endif
                            </div>

                            <a href="#"
                                class="inline-block w-full text-center bg-blue-600 text-white px-4 py-2 rounded font-medium hover:bg-blue-700 transition-colors">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
