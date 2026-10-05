<x-admin.layout-modern>
    <x-slot name="header">
        Create Category
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
                <input type="text" name="name" id="name"
                    class="w-fullborder-gray-400 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    value="{{ old('name') }}" required>
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="parent_id" class="block text-gray-700 font-bold mb-2">Parent Category (Optional)</label>
                <select name="parent_id" id="parent_id"
                    class="w-fullborder-gray-400 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">None</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}</option>
                    @endforeach
                </select>
                @error('parent_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-fullborder-gray-400 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="meta_title" class="block text-gray-700 font-bold mb-2">Meta Title</label>
                <input type="text" name="meta_title" id="meta_title"
                    class="w-fullborder-gray-400 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    value="{{ old('meta_title') }}">
                @error('meta_title')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="meta_description" class="block text-gray-700 font-bold mb-2">Meta Description</label>
                <textarea name="meta_description" id="meta_description" rows="2"
                    class="w-fullborder-gray-400 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('meta_description') }}</textarea>
                @error('meta_description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.categories.index') }}"
                    class="mr-3 px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Create
                    Category</button>
            </div>
        </form>
    </div>
</x-admin.layout-modern>
