<x-admin.layout-modern>
    <x-slot name="header">
        Edit Affiliate Link: {{ $link->name }}
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        <form action="{{ route('admin.affiliates.update', $link) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Internal Name</label>
                <input type="text" name="name" id="name"
                    class="w-fullborder-gray-400 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    value="{{ old('name', $link->name) }}" required>
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="destination_url" class="block text-gray-700 font-bold mb-2">Target URL (Affiliate
                    Link)</label>
                <input type="url" name="destination_url" id="destination_url"
                    class="w-fullborder-gray-400 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    value="{{ old('destination_url', $link->destination_url) }}" required>
                @error('destination_url')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="slug" class="block text-gray-700 font-bold mb-2">Slug (Optional - Auto-generated if
                    empty)</label>
                <div class="flex">
                    <span
                        class="inline-flex items-center px-3 rounded-l-md border border-r-0border-gray-400 bg-gray-50 text-gray-500 text-sm">
                        {{ url('go/') }}/
                    </span>
                    <input type="text" name="slug" id="slug"
                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-mdborder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        value="{{ old('slug', $link->slug) }}">
                </div>
                @error('slug')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1"
                        class="roundedborder-gray-400 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        {{ old('is_active', $link->is_active) ? 'checked' : '' }}>
                    <span class="ml-2 font-bold text-gray-700">Active</span>
                </label>
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('admin.affiliates.index') }}"
                    class="mr-3 px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update
                    Link</button>
            </div>
        </form>
    </div>
</x-admin.layout-modern>
