<x-admin.layout-modern>
    <x-slot name="header">
        Create Event - Add New Event
    </x-slot>

    <div class="max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                <i class="fas fa-arrow-left"></i> Back to Events
            </a>
        </div>

        <form action="{{ route('admin.events.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">Title *</label>
                <input type="text" id="title" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror" value="{{ old('title') }}" placeholder="Enter event title">
                @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">Description</label>
                <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Event description">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="event_date" class="block text-sm font-semibold text-gray-900 mb-2">Event Date *</label>
                    <input type="datetime-local" id="event_date" name="event_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('event_date') border-red-500 @enderror" value="{{ old('event_date') }}">
                    @error('event_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="event_end_date" class="block text-sm font-semibold text-gray-900 mb-2">Event End Date</label>
                    <input type="datetime-local" id="event_end_date" name="event_end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('event_end_date') }}">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="location" class="block text-sm font-semibold text-gray-900 mb-2">Location</label>
                    <input type="text" id="location" name="location" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('location') }}" placeholder="Event location">
                </div>
                <div>
                    <label for="capacity" class="block text-sm font-semibold text-gray-900 mb-2">Capacity</label>
                    <input type="number" id="capacity" name="capacity" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('capacity') }}" placeholder="Max attendees">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 pt-4 border-t">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600">
                    <span class="text-sm font-medium text-gray-900">Featured</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600">
                    <span class="text-sm font-medium text-gray-900">Published</span>
                </label>
            </div>

            <div class="flex gap-3 pt-6 border-t">
                <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium transition-colors">
                    <i class="fas fa-check"></i> Create Event
                </button>
                <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-2 bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300 font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin.layout-modern>
