<x-admin-layout-modern>
    <x-slot name="header">
        Create Article
    </x-slot>

    <div class="max-w-5xl mx-auto">
        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" id="ArticleForm">
            @csrf

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">Article Title</label>
                    <input type="text" name="title" id="title"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                        placeholder="Enter article title" value="{{ old('title') }}" required>
                    @error('title')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-semibold text-gray-900 mb-2">Slug</label>
                    <input type="text" name="slug" id="slug"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                        placeholder="URL-friendly slug" value="{{ old('slug') }}" required>
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
                        placeholder="Short description of the article" required>{{ old('excerpt') }}</textarea>
                    @error('excerpt')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Content Editor -->
                <div>
                    <label for="content" class="block text-sm font-semibold text-gray-900 mb-2">Content</label>
                    <textarea name="content" id="content" class="summernote" required>{{ old('content') }}</textarea>
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
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                            value="{{ old('published_at', now()->format('Y-m-d')) }}">
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
                                {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
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
                                    {{ old('status', 'draft') == 'draft' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Draft</span>
                            </label>
                            <label class="inline-flex items-center ml-4">
                                <input type="radio" name="status" value="published"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    {{ old('status') == 'published' ? 'checked' : '' }}>
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
                                {{ old('is_featured') ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Make this a featured/headline article</span>
                        </label>
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Search Engine Optimization</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-semibold text-gray-900 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                placeholder="SEO title (50-60 characters)" value="{{ old('meta_title') }}">
                            <p class="text-xs text-gray-500 mt-1">Optimal length: 50-60 characters</p>
                            @error('meta_title')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-semibold text-gray-900 mb-2">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                placeholder="SEO description (150-160 characters)">{{ old('meta_description') }}</textarea>
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
                                            {{ in_array($keyword->id, old('keywords', [])) ? 'selected' : '' }}>
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
                    <i class="fas fa-save"></i>Create Article
                </button>
            </div>
        </form>
    </div>

    <!-- jQuery (required for Select2 and Summernote) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS & JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Summernote CSS & JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

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
                    ['fontsize', ['fontsize']],
                    ['fontname', ['fontname']],
                    ['style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['color', ['color']],
                    ['float', ['floatLeft', 'floatRight', 'floatNone']],
                    ['remove', ['removeMedia']],
                    ['table', ['table']],
                    ['insert', ['link', 'unlink', 'audio', 'hr', 'picture']],
                    ['mybutton', ['myVideo']],
                    ['view', ['fullscreen', 'codeview']],
                    ['help', ['help']],
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
                setBtnLoading('button[type=submit]', 'Creating...');

                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.articles.store") }}',
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
                            title: 'Article Created Successfully!',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(function() {
                            window.location.href = '{{ route("admin.articles.index") }}';
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
                        setBtnLoading('button[type=submit]', '<i class="fas fa-save"></i>Create Article', false);
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
        .summernote {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
        }

        .note-editor .note-toolbar {
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .note-editor .note-editable {
            background-color: white;
            border-radius: 0 0 0.5rem 0.5rem;
        }

        .embed-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            margin: 1rem 0;
        }

        .embed-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</x-admin-layout-modern>
