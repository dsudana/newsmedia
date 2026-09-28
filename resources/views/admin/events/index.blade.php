<x-admin-layout-modern>
    <x-slot name="header">
        Events - Manage Upcoming Events
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Manage Events</h2>
                <p class="text-sm text-gray-600 mt-1">Create and manage upcoming events and agenda</p>
            </div>
            <a href="{{ route('admin.events.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium transition-colors">
                <i class="fas fa-plus"></i> New Event
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex gap-3">
                <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                <div>
                    <p class="font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Events</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $events->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Upcoming</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Event::upcoming()->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Featured</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Event::featured()->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-star text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Title</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Location</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody divide-y>
                    @forelse($events as $event)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900">{{ $event->title }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar text-gray-400"></i>
                                    {{ $event->event_date->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-map-pin text-gray-400"></i>
                                    {{ $event->location ?? 'TBD' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $event->status_color }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.events.edit', $event) }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm" onclick="return confirm('Delete this event?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <i class="fas fa-inbox text-3xl text-gray-300 mb-2 block"></i>
                                <p class="text-gray-500 font-medium">No events found</p>
                                <p class="text-sm text-gray-400 mt-1">Create your first event to get started</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events->hasPages())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</x-admin-layout-modern>
