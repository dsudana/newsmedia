<x-admin-layout-modern>
    <x-slot name="header">
        Edit Article: {{ $article->title }}
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4 col-span-2">
                    <label for="title" class="block text-gray-700 font-bold mb-2">Title</label>
                    <input type="text" name="title" id="title"
                        class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old('title', $article->title) }}" required>
                    @error('title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="category_id" class="block text-gray-700 font-bold mb-2">Category</label>
                    <select name="category_id" id="category_id"
                        class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-gray-700 font-bold mb-2">Status</label>
                    <select name="status" id="status"
                        class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft
                        </option>
                        <option value="published"
                            {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>
                            Published</option>
                        <option value="archived" {{ old('status', $article->status) == 'archived' ? 'selected' : '' }}>
                            Archived</option>
                    </select>
                    @error('status')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4 col-span-2">
                    <label for="content" class="block text-gray-700 font-bold mb-2">Content</label>
                    <textarea name="content" id="content" rows="10"
                        class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content', $article->content) }}</textarea>
                    @error('content')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4 col-span-2">
                    <label for="excerpt" class="block text-gray-700 font-bold mb-2">Excerpt (Short Description)</label>
                    <textarea name="excerpt" id="excerpt" rows="3"
                        class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('excerpt', $article->excerpt) }}</textarea>
                    @error('excerpt')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="featured_image" class="block text-gray-700 font-bold mb-2">Featured Image</label>
                    @if ($article->featured_image)
                        <div class="mb-2">
                            <img src="/storage/{{ $article->featured_image }}" alt="Current Image"
                                class="h-32 w-auto object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="featured_image" id="featured_image"
                        class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('featured_image')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="published_at" class="block text-gray-700 font-bold mb-2">Published At</label>
                    <input type="datetime-local" name="published_at" id="published_at"
                        class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}">
                    @error('published_at')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4 col-span-2">
                    <label class="block text-gray-700 font-bold mb-2">Tags</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($tags as $tag)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    {{ in_array($tag->id, old('tags', $article->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <span class="ml-2 mr-4">{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4 col-span-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_featured" value="1"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}>
                        <span class="ml-2 font-bold text-gray-700">Feature this article</span>
                    </label>
                </div>

                <div class="mb-4">
                    <label for="meta_title" class="block text-gray-700 font-bold mb-2">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title"
                        class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old('meta_title', $article->meta_title) }}">
                    @error('meta_title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="meta_description" class="block text-gray-700 font-bold mb-2">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="2"
                        class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('meta_description', $article->meta_description) }}</textarea>
                    @error('meta_description')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('admin.articles.index') }}"
                    class="mr-3 px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update
                    Article</button>
            </div>
        </form>
    </div>

    <!-- TinyMCE -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
            toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            height: 500
        });
    </script>
    </x-admin-layout-modern>
