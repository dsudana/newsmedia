@if ($events->count() > 0)
    <section class="bg-gradient-to-br from-blue-600 to-blue-800 dark:from-blue-800 dark:to-blue-950 rounded-xl p-8 shadow-lg mb-12">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-white flex items-center gap-2">
                📅 Acara Mendatang
                <span class="text-blue-300 text-2xl">›</span>
            </h2>
            <a href="{{ route('events.index') }}" class="text-blue-300 hover:text-blue-200 text-sm font-semibold transition">
                Lihat Semua Acara →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($events->take(3) as $event)
                <a href="{{ "/acara/{$event->slug}" }}" class="group">
                    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden hover:shadow-lg transition-shadow h-full flex flex-col">
                        @if ($event->featured_image)
                            <div class="h-40 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                <img src="{{ $event->featured_image }}" alt="{{ $event->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                        @else
                            <div
                                class="h-40 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center group-hover:from-blue-500 group-hover:to-blue-700 transition-colors">
                                <span class="text-4xl">📅</span>
                            </div>
                        @endif

                        <div class="p-4 flex-1 flex flex-col">
                            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase mb-2">{{ $event->category ?? 'Event' }}</span>

                            <h3 class="font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">{{ $event->title }}</h3>

                            <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400 mb-3 flex-1">
                                <p class="flex items-center gap-2">
                                    <span>📍</span>
                                    <span class="line-clamp-1">{{ $event->location ?? 'TBD' }}</span>
                                </p>
                                <p class="flex items-center gap-2">
                                    <span>🕐</span>
                                    <span>{{ $event->formatted_date }}, {{ $event->time }}</span>
                                </p>
                            </div>

                            <div class="text-xs text-gray-500 dark:text-gray-500 pt-2 border-t border-gray-200 dark:border-gray-700">
                                {{ $event->status ? ucfirst($event->status) : 'Scheduled' }}
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endif
