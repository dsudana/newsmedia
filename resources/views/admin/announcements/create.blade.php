<x-admin.layout-modern>
    <x-slot name="header">
        Create Announcement - Add New Message
    </x-slot>

    <div class="max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.announcements.index') }}"
                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                <i class="fas fa-arrow-left"></i> Back to Announcements
            </a>
        </div>

        <form action="{{ route('admin.announcements.store') }}" method="POST"
            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">Title *</label>
                <input type="text" id="title" name="title" required
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                    value="{{ old('title') }}" placeholder="Enter announcement title">
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="content" class="block text-sm font-semibold text-gray-900 mb-2">Content *</label>
                <textarea id="content" name="content" required rows="6"
                    class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('content') border-red-500 @enderror"
                    placeholder="Enter announcement content">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-sm font-semibold text-gray-900 mb-2">Category</label>
                    <select id="category" name="category"
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select category...</option>
                        <option value="info" {{ old('category') === 'info' ? 'selected' : '' }}>Info</option>
                        <option value="urgent" {{ old('category') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="warning" {{ old('category') === 'warning' ? 'selected' : '' }}>Warning</option>
                        <option value="maintenance" {{ old('category') === 'maintenance' ? 'selected' : '' }}>
                            Maintenance</option>
                    </select>
                </div>
                <div>
                    <label for="priority" class="block text-sm font-semibold text-gray-900 mb-2">Priority *</label>
                    <select id="priority" name="priority" required
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('priority') border-red-500 @enderror">
                        <option value="1" {{ old('priority') === '1' ? 'selected' : '' }}>Low</option>
                        <option value="2" {{ old('priority') === '2' ? 'selected' : '' }}>Medium</option>
                        <option value="3" {{ old('priority') === '3' ? 'selected' : '' }}>High/Urgent</option>
                    </select>
                    @error('priority')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="starts_at" class="block text-sm font-semibold text-gray-900 mb-2">Starts At *</label>
                    <input type="datetime-local" id="starts_at" name="starts_at" required
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('starts_at') border-red-500 @enderror"
                        value="{{ old('starts_at') }}">
                    @error('starts_at')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="ends_at" class="block text-sm font-semibold text-gray-900 mb-2">Ends At</label>
                    <input type="datetime-local" id="ends_at" name="ends_at"
                        class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('ends_at') border-red-500 @enderror"
                        value="{{ old('ends_at') }}">
                    @error('ends_at')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }}
                        class="w-4 h-4 roundedborder-gray-400 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-900">Pin this announcement</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 roundedborder-gray-400 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-900">Active</span>
                </label>
            </div>

            <div class="flex gap-3 pt-6 border-t">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium transition-colors">
                    <i class="fas fa-check"></i> Create Announcement
                </button>
                <a href="{{ route('admin.announcements.index') }}"
                    class="inline-flex items-center gap-2 bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300 font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin.layout-modern>
