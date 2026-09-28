<x-admin-layout-modern>


<div class="max-w-2xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Video YouTube</h1>
        <p class="text-gray-600 mt-2">Perbarui informasi video</p>
    </div>

    <!-- Preview -->
    <div class="mb-8 bg-gray-100 rounded-md p-6">
        <h3 class="font-semibold text-gray-900 mb-4">Pratinjau Video</h3>
        <div class="relative w-full" style="padding-bottom: 56.25%;">
            <iframe class="absolute top-0 left-0 w-full h-full"
                    src="https://www.youtube.com/embed/{{ $video->youtube_id }}"
                    frameborder="0" allowfullscreen></iframe>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.videos.update', $video) }}" method="POST" class="bg-white rounded-md shadow p-8">
        @csrf
        @method('PUT')

        <!-- YouTube URL -->
        <div class="mb-6">
            <label for="youtube_url" class="block text-sm font-semibold text-gray-700 mb-2">
                URL YouTube <span class="text-red-600">*</span>
            </label>
            <input type="text" id="youtube_url" name="youtube_url"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="https://www.youtube.com/watch?v=dQw4w9WgXcQ"
                   value="{{ old('youtube_url', $video->youtube_url) }}" required>
            @error('youtube_url')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Title -->
        <div class="mb-6">
            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                Judul Video <span class="text-red-600">*</span>
            </label>
            <input type="text" id="title" name="title"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Judul video"
                   value="{{ old('title', $video->title) }}" required>
            @error('title')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                Deskripsi
            </label>
            <textarea id="description" name="description" rows="4"
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="Deskripsi video (opsional)">{{ old('description', $video->description) }}</textarea>
            @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Category -->
        <div class="mb-6">
            <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                Kategori
            </label>
            <select id="category_id" name="category_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $video->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        <div class="mb-6">
            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                Status <span class="text-red-600">*</span>
            </label>
            <select id="status" name="status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="published" {{ old('status', $video->status) === 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                <option value="draft" {{ old('status', $video->status) === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
            @error('status')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Published At -->
        <div class="mb-6">
            <label for="published_at" class="block text-sm font-semibold text-gray-700 mb-2">
                Tanggal Publikasi
            </label>
            <input type="datetime-local" id="published_at" name="published_at"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   value="{{ old('published_at', $video->published_at?->format('Y-m-d\TH:i')) }}">
            @error('published_at')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-semibold transition">
                Perbarui Video
            </button>
            <a href="{{ route('admin.videos.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-md font-semibold text-center transition">
                Batal
            </a>
        </div>
    </form>
</div>
</x-admin-layout-modern>