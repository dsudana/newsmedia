<x-admin-layout-modern>
    <div class="space-y-6">
        <h1 class="text-2xl font-bold">Edit Social Media Link</h1>

                <form method="POST" action="{{ route('admin.social-media.update', $socialMedia) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="platform" class="block text-sm font-medium text-gray-700">Platform Name</label>
                        <input type="text" id="platform" name="platform" value="{{ old('platform', $socialMedia->platform) }}" required class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('platform')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="icon" class="block text-sm font-medium text-gray-700">Icon Class (Font Awesome)</label>
                        <input type="text" id="icon" name="icon" value="{{ old('icon', $socialMedia->icon) }}" placeholder="fab fa-facebook-f" required class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">e.g., fab fa-facebook-f, fab fa-twitter, fab fa-instagram</p>
                    </div>

                    <div class="mb-6">
                        <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
                        <input type="url" id="url" name="url" value="{{ old('url', $socialMedia->url) }}" placeholder="https://example.com" required class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $socialMedia->sort_order) }}" class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="is_active" class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $socialMedia->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Update
            </button>
            <a href="{{ route('admin.social-media.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Cancel
            </a>
        </div>
        </form>
    </div>
</x-admin-layout-modern>
