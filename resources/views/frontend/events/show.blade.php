@extends('layouts.app-modern')

@section('title', $event->title . ' - NEWSMEDIA')

@section('content')
<main class="bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content (2/3) -->
            <div class="lg:col-span-2">
                <!-- Back Link -->
                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 mb-6 transition">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Daftar Acara
                </a>

                <!-- Event Header Image -->
                @if($event->featured_image)
                    <div class="rounded-lg overflow-hidden mb-8 h-96">
                        <img src="{{ $event->featured_image }}" alt="{{ $event->title }}"
                            class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="rounded-lg overflow-hidden mb-8 h-96 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                        <span class="text-9xl">📅</span>
                    </div>
                @endif

                <!-- Event Title & Meta -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase px-3 py-1 bg-blue-100 dark:bg-blue-900 rounded-full">
                            {{ $event->category ?? 'Event' }}
                        </span>
                        <span class="text-xs font-semibold px-3 py-1 rounded {{ $event->status_color }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>

                    <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-4">{{ $event->title }}</h1>

                    <div class="space-y-3 text-slate-600 dark:text-gray-400 mb-6">
                        <p class="flex items-center gap-3 text-lg">
                            <span class="text-2xl">📍</span>
                            <span>{{ $event->location ?? 'Lokasi TBD' }}</span>
                        </p>
                        <p class="flex items-center gap-3 text-lg">
                            <span class="text-2xl">📅</span>
                            <span>{{ $event->formatted_date }}</span>
                        </p>
                        <p class="flex items-center gap-3 text-lg">
                            <span class="text-2xl">🕐</span>
                            <span>{{ $event->time }}</span>
                        </p>
                        @if($event->capacity)
                            <p class="flex items-center gap-3 text-lg">
                                <span class="text-2xl">👥</span>
                                <span>{{ $event->registered }}/{{ $event->capacity }} Terdaftar ({{ $event->available_spots }} tempat tersedia)</span>
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Event Description -->
                @if($event->description)
                    <div class="prose dark:prose-invert max-w-none mb-12">
                        {!! clean($event->description) !!}
                    </div>
                @endif

                <!-- Event Details -->
                <div class="bg-slate-50 dark:bg-gray-800 rounded-lg p-6 mb-12">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Informasi Acara</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Tanggal & Waktu</h3>
                            <p class="text-slate-600 dark:text-gray-400">
                                {{ $event->formatted_date }}<br>
                                Pukul {{ $event->time }}
                            </p>
                        </div>

                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Lokasi</h3>
                            <p class="text-slate-600 dark:text-gray-400">{{ $event->location ?? 'Lokasi TBD' }}</p>
                            @if($event->location_details)
                                <p class="text-sm text-slate-500 dark:text-gray-500 mt-2">{{ $event->location_details }}</p>
                            @endif
                        </div>

                        @if($event->capacity)
                            <div>
                                <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Kapasitas</h3>
                                <p class="text-slate-600 dark:text-gray-400">{{ $event->capacity }} Peserta</p>
                                <div class="mt-2 w-full bg-slate-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full"
                                        style="width: {{ ($event->registered / $event->capacity * 100) }}%"></div>
                                </div>
                                <p class="text-sm text-slate-500 dark:text-gray-500 mt-1">
                                    {{ $event->registered }} dari {{ $event->capacity }} terdaftar
                                </p>
                            </div>
                        @endif

                        <div>
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Status</h3>
                            <p class="text-slate-600 dark:text-gray-400 capitalize">{{ $event->status }}</p>
                        </div>
                    </div>
                </div>

                @if($event->event_url && preg_match('/^https?:\/\//i', $event->event_url))
                    <a href="{{ $event->event_url }}" target="_blank" rel="noopener noreferrer"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition-colors mb-12">
                        Daftar Sekarang <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                @endif
            </div>

            <!-- Sidebar (1/3) -->
            <aside class="space-y-8">
                <!-- Event Card Info -->
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900 text-white rounded-lg p-6 shadow-lg">
                    <h3 class="text-xl font-bold mb-4">Detail Acara</h3>

                    <div class="space-y-4">
                        <div class="border-b border-blue-500 pb-4">
                            <p class="text-blue-200 text-sm">Tanggal</p>
                            <p class="font-semibold">{{ $event->formatted_date }}</p>
                        </div>

                        <div class="border-b border-blue-500 pb-4">
                            <p class="text-blue-200 text-sm">Waktu Mulai</p>
                            <p class="font-semibold">{{ $event->time }}</p>
                        </div>

                        <div class="border-b border-blue-500 pb-4">
                            <p class="text-blue-200 text-sm">Kategori</p>
                            <p class="font-semibold capitalize">{{ $event->category ?? 'Umum' }}</p>
                        </div>

                        <div>
                            <p class="text-blue-200 text-sm">Status</p>
                            <p class="font-semibold capitalize">{{ $event->status }}</p>
                        </div>
                    </div>
                </div>

                <!-- Share Widget -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-slate-200 dark:border-gray-700">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-4">Bagikan Acara</h3>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('events.show', $event->slug)) }}"
                            target="_blank" rel="noopener noreferrer"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded text-center transition-colors">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('events.show', $event->slug)) }}&text={{ urlencode($event->title) }}"
                            target="_blank" rel="noopener noreferrer"
                            class="flex-1 bg-blue-400 hover:bg-blue-500 text-white py-2 rounded text-center transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('events.show', $event->slug)) }}"
                            target="_blank" rel="noopener noreferrer"
                            class="flex-1 bg-blue-700 hover:bg-blue-800 text-white py-2 rounded text-center transition-colors">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>

                <!-- Related Events -->
                @if($relatedEvents && $relatedEvents->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-slate-200 dark:border-gray-700">
                        <h3 class="font-bold text-slate-900 dark:text-white mb-4">Acara Lainnya</h3>
                        <div class="space-y-3">
                            @foreach($relatedEvents->take(3) as $relEvent)
                                <a href="{{ "/acara/{$relEvent->slug}" }}"
                                    class="block p-3 bg-slate-50 dark:bg-gray-700 rounded hover:bg-blue-50 dark:hover:bg-gray-600 transition-colors">
                                    <p class="font-semibold text-slate-900 dark:text-white text-sm line-clamp-2 hover:text-blue-600 dark:hover:text-blue-400">
                                        {{ $relEvent->title }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-gray-400 mt-1">
                                        {{ $relEvent->formatted_date }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</main>
@endsection
