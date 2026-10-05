<x-admin.layout-modern>
    <div class="space-y-6 pr-4 max-w-3xl">
        <!-- Header Section -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create New Tag</h1>
            <p class="text-sm text-gray-600 mt-1">Add a new tag to organize your content</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('admin.tags.store') }}" method="POST" class="p-8 space-y-6">
                @csrf

                <!-- Tag Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-900 mb-3">
                        <i class="fas fa-tag mr-2 text-indigo-600"></i>Tag Name
                    </label>
                    <input type="text" name="name" id="name"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 placeholder-gray-500"
                        placeholder="e.g., Technology, Business, Health" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Description (Optional) -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-900 mb-3">
                        <i class="fas fa-align-left mr-2 text-indigo-600"></i>Description (Optional)
                    </label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 placeholder-gray-500"
                        placeholder="Describe what this tag is for...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Preview -->
                <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                    <p class="text-xs font-semibold text-indigo-600 uppercase mb-2">Preview</p>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-700">
                        <i class="fas fa-tag mr-1.5"></i>
                        <span id="namePreview">Tag Name</span>
                    </span>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.tags.index') }}"
                        class="px-6 py-3 bg-gray-200 text-gray-900 rounded-lg font-semibold hover:bg-gray-300 transition-colors flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Tags</span>
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg font-semibold hover:shadow-lg transition-shadow flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Create Tag</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const nameInput = document.getElementById('name');
        const namePreview = document.getElementById('namePreview');
        nameInput.addEventListener('input', (e) => {
            namePreview.textContent = e.target.value || 'Tag Name';
        });
    </script>
</x-x-admin-layout-modern>
