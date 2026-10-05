<x-admin.layout-modern>
    <x-slot name="header">
        Edit Article: {{ $article->title }}
    </x-slot>

    <div class="max-w-5xl mx-auto">
        <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" id="ArticleForm">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">Article Title</label>
                    <input type="text" name="title" id="title"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                        placeholder="Enter article title" value="{{ old('title', $article->title) }}" required>
                    @error('title')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-semibold text-gray-900 mb-2">Slug</label>
                    <input type="text" name="slug" id="slug"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                        placeholder="URL-friendly slug" value="{{ old('slug', $article->slug ?? '') }}" required>
                    <p class="text-xs text-gray-500 mt-1">Used for article URL. Auto-generated from title.</p>
                    @error('slug')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Excerpt -->
                <div>
                    <label for="excerpt" class="block text-sm font-semibold text-gray-900 mb-2">Excerpt</label>
                    <textarea name="excerpt" id="excerpt" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                        placeholder="Short description of the article" required>{{ old('excerpt', $article->excerpt) }}</textarea>
                    @error('excerpt')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Content Editor -->
                <div>
                    <label for="content" class="block text-sm font-semibold text-gray-900 mb-2">Content</label>
                    <textarea name="content" id="content" class="summernote" required>{{ old('content', $article->content) }}</textarea>
                    @error('content')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Category & Date Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-gray-900 mb-2">Category</label>
                        <select name="category_id" id="category_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Published Date -->
                    <div>
                        <label for="published_at" class="block text-sm font-semibold text-gray-900 mb-2">Publish Date</label>
                        <input type="date" name="published_at" id="published_at"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d') : now()->format('Y-m-d')) }}">
                        @error('published_at')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Tags -->
                <div>
                    <label for="tags" class="block text-sm font-semibold text-gray-900 mb-2">Tags</label>
                    <select name="tags[]" id="tags" class="w-full" multiple style="width: 100%;">
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}"
                                {{ in_array($tag->id, old('tags', $article->tags?->pluck('id')?->toArray() ?? [])) ? 'selected' : '' }}>
                                {{ $tag->name }}</option>
                        @endforeach
                    </select>
                    @error('tags')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Featured Image -->
                <div>
                    <label for="featured_image" class="block text-sm font-semibold text-gray-900 mb-2">Featured Image</label>
                    @if ($article->featured_image)
                        <div class="mb-4 p-4 bg-gray-100 rounded-lg">
                            <img src="{{ str_starts_with($article->featured_image, 'http') ? $article->featured_image : asset('storage/' . $article->featured_image) }}"
                                alt="Current featured image" class="max-h-48 rounded">
                        </div>
                    @endif
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-500 transition cursor-pointer" id="imageDropZone">
                        <input type="file" name="featured_image" id="featured_image" class="hidden" accept="image/*">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-image text-3xl text-gray-400 mb-2"></i>
                            <p class="text-gray-600 font-medium">Click to upload or drag and drop</p>
                            <p class="text-gray-500 text-sm">PNG, JPG, GIF up to 5MB</p>
                        </div>
                        <div id="imagePreview" class="hidden mt-4">
                            <img id="previewImg" src="" alt="Preview" class="max-h-48 mx-auto rounded">
                        </div>
                    </div>
                    @error('featured_image')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Status & Featured -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">Status</label>
                        <div class="space-y-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="draft"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    {{ old('status', $article->status) == 'draft' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Draft</span>
                            </label>
                            <label class="inline-flex items-center ml-4">
                                <input type="radio" name="status" value="published"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    {{ old('status', $article->status) == 'published' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Publish</span>
                            </label>
                        </div>
                        @error('status')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Featured Checkbox -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">Options</label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_featured" value="1"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Make this a featured/headline article</span>
                        </label>
                    </div>
                </div>

                <!-- Affiliate Links Section -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-link text-amber-600 mr-2"></i>Affiliate Links
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Select products to feature in this article</p>
                    <select name="affiliate_links[]" id="affiliate_links" class="w-full" multiple style="width: 100%;">
                        @foreach ($affiliateLinks ?? [] as $link)
                            <option value="{{ $link->id }}"
                                {{ in_array($link->id, old('affiliate_links', $article->affiliateLinks()?->pluck('id')?->toArray() ?? [])) ? 'selected' : '' }}>
                                {{ $link->name }} ({{ $link->commission_type === 'percentage' ? $link->commission_value . '%' : 'Rp ' . number_format($link->commission_value) }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Leave empty if you don't want to feature any affiliate products in this article</p>
                </div>

                <!-- SEO Section -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Search Engine Optimization</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-semibold text-gray-900 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                placeholder="SEO title (50-60 characters)" value="{{ old('meta_title', $article->meta->meta_title ?? '') }}">
                            <p class="text-xs text-gray-500 mt-1">Optimal length: 50-60 characters</p>
                            @error('meta_title')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-semibold text-gray-900 mb-2">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                placeholder="SEO description (150-160 characters)">{{ old('meta_description', $article->meta->meta_description ?? '') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Optimal length: 150-160 characters</p>
                            @error('meta_description')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        @if (isset($keywords))
                            <div>
                                <label for="keywords" class="block text-sm font-semibold text-gray-900 mb-2">Keywords (SEO)</label>
                                <select name="keywords[]" id="keywords" class="w-full" multiple style="width: 100%;">
                                    @foreach ($keywords as $keyword)
                                        <option value="{{ $keyword->id }}"
                                            {{ in_array($keyword->id, old('keywords', $article->keywords?->pluck('id')?->toArray() ?? [])) ? 'selected' : '' }}>
                                            {{ $keyword->keyword }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.articles.index') }}"
                    class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium flex items-center gap-2">
                    <i class="fas fa-save"></i>Update Article
                </button>
            </div>
        </form>
    </div>

    <!-- jQuery (required for Select2 and Summernote) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS & JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Summernote CSS & JS (without Bootstrap dependency) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

    <script>
        const errorAfterInput = [];

        function resetErrorAfterInput() {
            errorAfterInput.forEach(field => {
                $(`${field}`).removeClass('border-red-500 focus:ring-red-500');
                $(`${field}`).next('.error-message').remove();
            });
        }

        function setErrorAfterInput(errors, fieldId) {
            if (Array.isArray(errors)) {
                errors.forEach(error => {
                    $(fieldId).addClass('border-red-500 focus:ring-red-500');
                    $(fieldId).after(`<span class="text-red-500 text-sm mt-1 error-message">${error}</span>`);
                });
            } else {
                $(fieldId).addClass('border-red-500 focus:ring-red-500');
                $(fieldId).after(`<span class="text-red-500 text-sm mt-1 error-message">${errors}</span>`);
            }
        }

        function setBtnLoading(selector, text = 'Loading...', isLoading = true) {
            const btn = $(selector);
            if (isLoading) {
                btn.prop('disabled', true).html(`<i class="fas fa-spinner fa-spin mr-2"></i>${text}`);
            } else {
                btn.prop('disabled', false).html(text);
            }
        }

        $(document).ready(function() {
            // Initialize Select2 for Tags
            $('#tags').select2({
                placeholder: 'Select tags',
                allowClear: true,
                width: '100%'
            });

            // Initialize Select2 for Keywords
            if ($('#keywords').length) {
                $('#keywords').select2({
                    placeholder: 'Select keywords',
                    allowClear: true,
                    width: '100%'
                });
            }

            // Initialize Summernote
            $('.summernote').summernote({
                height: 500,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol']],
                    ['insert', ['link', 'picture', 'hr']],
                    ['mybutton', ['myVideo']],
                    ['view', ['fullscreen', 'codeview']],
                ],
                buttons: {
                    myVideo: function(context) {
                        var ui = $.summernote.ui;
                        var button = ui.button({
                            contents: '<i class="fa fa-video-camera"/>',
                            click: function() {
                                var url = prompt('Enter YouTube URL:');
                                if (url) {
                                    var videoId = youtube_parser(url);
                                    if (videoId) {
                                        var div = document.createElement('div');
                                        div.classList.add('embed-container');
                                        var iframe = document.createElement('iframe');
                                        iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=0&fs=1&showinfo=1&rel=0&cc_load_policy=1&controls=1`;
                                        iframe.setAttribute('frameborder', 0);
                                        iframe.setAttribute('width', '100%');
                                        iframe.setAttribute('height', '500px');
                                        iframe.setAttribute('allowfullscreen', true);
                                        div.appendChild(iframe);
                                        context.invoke('editor.insertNode', div);
                                    } else {
                                        Swal.fire('Error', 'Invalid YouTube URL', 'error');
                                    }
                                }
                            }
                        });
                        return button.render();
                    }
                }
            });

            // Auto-generate slug from title
            $('#title').keyup(function() {
                var text = $(this).val();
                var slug = text.toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
                $('#slug').val(slug);
            });

            // Image preview
            $('#featured_image').on('change', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').removeClass('hidden');
                        $('#previewImg').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Drag and drop
            $('#imageDropZone').on('dragover', function(e) {
                e.preventDefault();
                $(this).addClass('border-indigo-500 bg-indigo-50');
            });

            $('#imageDropZone').on('dragleave', function() {
                $(this).removeClass('border-indigo-500 bg-indigo-50');
            });

            $('#imageDropZone').on('drop', function(e) {
                e.preventDefault();
                $(this).removeClass('border-indigo-500 bg-indigo-50');
                var files = e.originalEvent.dataTransfer.files;
                if (files.length) {
                    $('#featured_image')[0].files = files;
                    $('#featured_image').trigger('change');
                }
            });

            $('#imageDropZone').on('click', function() {
                $('#featured_image').click();
            });

            // Form submission
            $('#ArticleForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                resetErrorAfterInput();
                setBtnLoading('button[type=submit]', 'Updating...');

                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.articles.update", $article) }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Article Updated Successfully!',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(function() {
                            window.location.reload();
                        });
                    },
                    error: function(data) {
                        const res = data.responseJSON ?? {};
                        errorAfterInput.length = 0;

                        if (res.errors) {
                            for (const property in res.errors) {
                                errorAfterInput.push(`#${property}`);
                                setErrorAfterInput(res.errors[property], `#${property}`);
                            }
                        }

                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: res.message ?? 'Something went wrong',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                    complete: function() {
                        setBtnLoading('button[type=submit]', '<i class="fas fa-save"></i>Update Article', false);
                    }
                });
            });
        });

        function youtube_parser(url) {
            var regExp = /^https?\:\/\/(?:www\.youtube(?:\-nocookie)?\.com\/|m\.youtube\.com\/|youtube\.com\/)?(?:ytscreeningroom\?vi?=|youtu\.be\/|vi?\/|user\/.+\/u\/\w{1,2}\/|embed\/|watch\?(?:.*\&)?vi?=|\&vi?=|\?(?:.*\&)?vi?=)([^#\&\?\n\/<>"']*)/i;
            var match = url.match(regExp);
            return (match && match[1].length == 11) ? match[1] : false;
        }
    </script>

    <style>
        /* Summernote Styling */
        .summernote {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem;
            font-size: 1rem;
        }

        .note-editor {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .note-editor .note-toolbar {
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
        }

        .note-toolbar .note-btn-group {
            display: flex;
            gap: 0.25rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .note-toolbar .note-btn {
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background-color: white;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s;
        }

        .note-toolbar .note-btn:hover {
            background-color: #e5e7eb;
            border-color: #9ca3af;
        }

        .note-toolbar .note-btn.active {
            background-color: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }

        .note-toolbar .note-btn-group > div {
            display: flex;
            gap: 0.25rem;
        }

        .note-editor .note-editable {
            background-color: white;
            border-radius: 0 0 0.5rem 0.5rem;
            padding: 1rem;
            min-height: 500px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            line-height: 1.6;
        }

        .note-editor .note-editable p {
            margin-bottom: 1rem;
        }

        .note-editor .note-editable h1,
        .note-editor .note-editable h2,
        .note-editor .note-editable h3,
        .note-editor .note-editable h4,
        .note-editor .note-editable h5,
        .note-editor .note-editable h6 {
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        /* Dropdown menus for font selection */
        .note-toolbar .note-btn-group .note-dropdown-menu {
            min-width: 150px;
        }

        .embed-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            margin: 1rem 0;
            border-radius: 0.375rem;
        }

        .embed-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 0.375rem;
        }

        /* Improve Select2 styling */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* Responsive toolbar on mobile */
        @media (max-width: 768px) {
            .note-editor .note-toolbar {
                padding: 0.375rem;
            }

            .note-toolbar .note-btn {
                padding: 0.25rem 0.375rem;
                font-size: 0.75rem;
            }

            .note-editor .note-editable {
                padding: 0.75rem;
                min-height: 300px;
            }
        }
    </style>
</x-admin.layout-modern>
