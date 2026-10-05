@extends('layouts.app-modern')

@section('title', 'Acara Mendatang - NEWSMEDIA')

@section('content')
<main class="bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Page Header -->
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-4">📅 Acara Mendatang</h1>
            <p class="text-lg text-slate-600 dark:text-gray-400">Temukan dan ikuti acara-acara menarik yang akan datang</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Content (3/4) -->
            <div class="lg:col-span-3">
                @if($events && $events->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($events as $event)
                            <a href="{{ "/acara/{$event->slug}" }}" class="group">
                                <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden hover:shadow-lg transition-shadow h-full flex flex-col">
                                    @if ($event->featured_image)
                                        <div class="h-48 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                            <img src="{{ $event->featured_image }}" alt="{{ $event->title }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                    @else
                                        <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center group-hover:from-blue-500 group-hover:to-blue-700 transition-colors">
                                            <span class="text-6xl">📅</span>
                                        </div>
                                    @endif

                                    <div class="p-5 flex-1 flex flex-col">
                                        <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase mb-2">{{ $event->category ?? 'Event' }}</span>

                                        <h3 class="font-bold text-slate-900 dark:text-white mb-3 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition text-lg">{{ $event->title }}</h3>

                                        <div class="space-y-2 text-sm text-slate-600 dark:text-gray-400 mb-4 flex-1">
                                            <p class="flex items-center gap-2">
                                                <span>📍</span>
                                                <span class="line-clamp-1">{{ $event->location ?? 'TBD' }}</span>
                                            </p>
                                            <p class="flex items-center gap-2">
                                                <span>🕐</span>
                                                <span>{{ $event->formatted_date }}, {{ $event->time }}</span>
                                            </p>
                                        </div>

                                        <div class="pt-3 border-t border-slate-200 dark:border-gray-700">
                                            <p class="text-sm text-slate-600 dark:text-gray-500">
                                                {{ $event->status ? ucfirst($event->status) : 'Scheduled' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($events->hasPages())
                        <div class="mt-12">
                            {{ $events->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-16 bg-slate-50 dark:bg-gray-800 rounded-lg">
                        <i class="fas fa-calendar text-5xl text-slate-300 dark:text-gray-600 mb-4 block"></i>
                        <p class="text-slate-600 dark:text-gray-400 text-lg">Tidak ada acara yang tersedia</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar (1/4) -->
            <aside class="space-y-8">
                <!-- Search Widget -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-slate-200 dark:border-gray-700">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-4">Cari Acara</h3>
                    <form action="{{ route('events.index') }}" method="GET" class="space-y-4">
                        <div>
                            <input type="text" name="search" placeholder="Cari nama acara..."
                                value="{{ request('search') }}"
                                class="w-full px-4 py-2 rounded border border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition-colors">
                            Cari
                        </button>
                    </form>
                </div>

                <!-- Info Widget -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-lg p-6 border border-blue-200 dark:border-blue-700">
                    <h3 class="font-bold text-blue-900 dark:text-blue-100 mb-3">Tentang Acara</h3>
                    <p class="text-sm text-blue-800 dark:text-blue-200 mb-4">
                        Ikuti acara-acara menarik kami dan jadilah bagian dari komunitas yang dinamis.
                    </p>
                    <ul class="space-y-2 text-sm text-blue-800 dark:text-blue-200">
                        <li class="flex items-center gap-2">
                            <span>✓</span>
                            <span>Acara interaktif</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>✓</span>
                            <span>Berbagi pengetahuan</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>✓</span>
                            <span>Networking opportunity</span>
                        </li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</main>
@endsection
