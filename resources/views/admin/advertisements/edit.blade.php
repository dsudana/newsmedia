<x-admin.layout-modern>
    <x-slot name="header">
        Edit Advertisement
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.advertisements.update', $advertisement) }}" method="POST"
                enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama Iklan -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Advertisement
                        Name</label>
                    <input type="text" id="name" name="name" required placeholder="e.g., Google Ads Banner"
                        class="w-full px-3 py-2 border border-gray-400 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
                        value="{{ old('name', $advertisement->name) }}">
                    @error('name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tipe Iklan -->
                <div>
                    <label for="type" class="block text-sm font-semibold text-gray-900 mb-2">Advertisement
                        Type</label>
                    <select id="type" name="type" required onchange="updateAdType()"
                        class="w-full px-3 py-2 border border-gray-400 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('type') border-red-500 @enderror">
                        <option value="">-- Select Type --</option>
                        @foreach ($types as $key => $value)
                            <option value="{{ $key }}"
                                {{ old('type', $advertisement->type) == $key ? 'selected' : '' }}>{{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Posisi Iklan -->
                <div>
                    <label for="placement" class="block text-sm font-semibold text-gray-900 mb-2">Placement
                        Position</label>
                    <select id="placement" name="placement" required onchange="updateSize()"
                        class="w-full px-3 py-2 border border-gray-400 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('placement') border-red-500 @enderror">
                        <option value="">-- Select Placement --</option>
                        @foreach ($placements as $key => $value)
                            <option value="{{ $key }}"
                                {{ old('placement', $advertisement->placement) == $key ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                    @error('placement')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Image Fields (untuk Banner type) -->
                <div id="imageField">
                    <!-- Upload Gambar -->
                    <div>
                        <label for="image" class="block text-sm font-semibold text-gray-900 mb-2">Advertisement Image
                            (JPG, PNG)</label>
                        @if ($advertisement->image)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $advertisement->image) }}"
                                    alt="{{ $advertisement->name }}" class="max-h-64 rounded-lg">
                                <p class="text-sm text-gray-600 mt-2">Current image</p>
                            </div>
                        @endif
                        <div
                            class="border-2 border-dashedborder-gray-400 rounded-lg p-6 text-center cursor-pointer hover:border-indigo-500 transition">
                            <input type="file" id="image" name="image" accept="image/*" class="hidden"
                                onchange="previewImage(event)">
                            <label for="image" class="cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600 font-medium">Click to upload or drag & drop</p>
                                <p class="text-gray-500 text-sm">Max 5MB (optional)</p>
                            </label>
                        </div>
                        <img id="imagePreview" src="" alt="Preview" class="mt-4 rounded-lg max-h-64 hidden">
                        @error('image')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- URL Tujuan -->
                    <div>
                        <label for="url" class="block text-sm font-semibold text-gray-900 mb-2">Destination
                            URL</label>
                        <input type="url" id="url" name="url" placeholder="https://example.com"
                            class="w-full px-3 py-2 border border-gray-400 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('url') border-red-500 @enderror"
                            value="{{ old('url', $advertisement->url) }}">
                        @error('url')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4" placeholder="Enter advertisement description..."
                        class="w-full px-3 py-2 border border-gray-400 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $advertisement->description) }}</textarea>
                    @error('description')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Script (untuk AdSense & Custom Script) -->
                <div id="scriptField" style="display: none;">
                    <label for="script" class="block text-sm font-semibold text-gray-900 mb-2">Script Code
                        (AdSense/Custom)</label>
                    <textarea id="script" name="script" rows="6" placeholder="Paste your AdSense or custom script here..."
                        class="w-full px-3 py-2 border border-gray-400 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('script') border-red-500 @enderror font-mono text-sm">{{ old('script', $advertisement->script) }}</textarea>
                    <p class="text-gray-500 text-xs mt-2">Example: &lt;script async
                        src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"&gt;&lt;/script&gt;</p>
                    @error('script')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Ukuran Iklan -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="size" class="block text-sm font-semibold text-gray-900 mb-2">Size Preset</label>
                        <select id="size" name="size" onchange="updateDimensions()"
                            class="w-full px-3 py-2 border border-gray-400 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="">-- Select Size --</option>
                            @foreach ($sizes as $key => $value)
                                <option value="{{ $key }}"
                                    {{ old('size', $advertisement->size) == $key ? 'selected' : '' }}>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                        @error('size')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="width" class="block text-sm font-semibold text-gray-900 mb-2">Width (px)</label>
                        <input type="number" id="width" name="width" required min="100"
                            class="w-full px-3 py-2 border border-gray-400 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('width') border-red-500 @enderror"
                            value="{{ old('width', $advertisement->width) }}">
                        @error('width')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="height" class="block text-sm font-semibold text-gray-900 mb-2">Height
                            (px)</label>
                        <input type="number" id="height" name="height" required min="50"
                            class="w-full px-3 py-2 border border-gray-400 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('height') border-red-500 @enderror"
                            value="{{ old('height', $advertisement->height) }}">
                        @error('height')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Tanggal Tayang -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-semibold text-gray-900 mb-2">Start
                            Date</label>
                        <input type="date" id="start_date" name="start_date"
                            class="w-full px-3 py-2 border border-gray-400 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            value="{{ old('start_date', $advertisement->start_date?->format('Y-m-d')) }}">
                        @error('start_date')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-semibold text-gray-900 mb-2">End Date</label>
                        <input type="date" id="end_date" name="end_date"
                            class="w-full px-3 py-2 border border-gray-400 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                            value="{{ old('end_date', $advertisement->end_date?->format('Y-m-d')) }}">
                        @error('end_date')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $advertisement->is_active) ? 'checked' : '' }}
                            class="w-5 h-5 text-indigo-600border-gray-400 rounded focus:ring-2 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-gray-900">Active Advertisement</span>
                    </label>
                </div>

                <!-- Statistik -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-900 mb-3">Statistics</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Views</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $advertisement->view_count }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Clicks</p>
                            <p class="text-2xl font-bold text-green-600">{{ $advertisement->click_count }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="flex gap-4 pt-6 border-t">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                        <i class="fas fa-save mr-2"></i>Update Advertisement
                    </button>
                    <a href="{{ route('admin.advertisements.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-900 rounded-lg hover:bg-gray-300 transition font-semibold">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateAdType() {
            const type = document.getElementById('type').value;
            const imageField = document.getElementById('imageField');
            const scriptField = document.getElementById('scriptField');

            if (type === 'banner') {
                if (imageField) imageField.style.display = 'block';
                if (scriptField) scriptField.style.display = 'none';
            } else if (type === 'adsense' || type === 'script') {
                if (imageField) imageField.style.display = 'none';
                if (scriptField) scriptField.style.display = 'block';
            } else {
                if (imageField) imageField.style.display = 'block';
                if (scriptField) scriptField.style.display = 'none';
            }
        }

        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function updateDimensions() {
            const size = document.getElementById('size').value;
            const sizes = {
                '1200x128': [1200, 128],
                '300x250': [300, 250],
                '300x600': [300, 600],
                '300x400': [300, 400],
                '728x90': [728, 90],
                '970x90': [970, 90],
            };

            if (sizes[size]) {
                document.getElementById('width').value = sizes[size][0];
                document.getElementById('height').value = sizes[size][1];
            }
        }

        function updateSize() {
            const placement = document.getElementById('placement').value;
            const defaults = {
                'header_banner': '1200x128',
                'sidebar_top': '300x250',
                'sidebar_bottom': '300x600',
                'content_middle': '300x400',
            };

            if (defaults[placement]) {
                document.getElementById('size').value = defaults[placement];
                updateDimensions();
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateAdType();
        });
    </script>
</x-admin.layout-modern>
