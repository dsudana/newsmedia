@extends('layouts.admin')

@section('title', 'Edit Iklan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.advertisements.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Edit Iklan</h1>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <form action="{{ route('admin.advertisements.update', $advertisement) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Iklan -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Iklan *</label>
                <input type="text" id="name" name="name" required placeholder="Contoh: Iklan Google Ads"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 @error('name') border-red-500 @enderror"
                    value="{{ old('name', $advertisement->name) }}">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipe Iklan -->
            <div>
                <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Tipe Iklan *</label>
                <select id="type" name="type" required onchange="updateAdType()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 @error('type') border-red-500 @enderror">
                    <option value="">-- Pilih Tipe --</option>
                    @foreach($types as $key => $value)
                        <option value="{{ $key }}" {{ old('type', $advertisement->type) == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Posisi Iklan -->
            <div>
                <label for="placement" class="block text-sm font-semibold text-gray-700 mb-2">Posisi Iklan *</label>
                <select id="placement" name="placement" required onchange="updateSize()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 @error('placement') border-red-500 @enderror">
                    <option value="">-- Pilih Posisi --</option>
                    @foreach($placements as $key => $value)
                        <option value="{{ $key }}" {{ old('placement', $advertisement->placement) == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
                @error('placement')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Fields (untuk Banner type) -->
            <div id="imageField">
                <!-- Upload Gambar -->
                <div>
                    <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">Gambar Iklan (JPG, PNG)</label>
                    @if ($advertisement->image)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $advertisement->image) }}" alt="{{ $advertisement->name }}" class="max-h-64 rounded-lg">
                            <p class="text-sm text-gray-600 mt-2">Gambar saat ini</p>
                        </div>
                    @endif
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-red-600 transition">
                        <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewImage(event)">
                        <label for="image" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-600 font-medium">Klik untuk upload atau drag & drop</p>
                            <p class="text-gray-500 text-sm">Max 5MB (opsional)</p>
                        </label>
                    </div>
                    <img id="imagePreview" src="" alt="Preview" class="mt-4 rounded-lg max-h-64 hidden">
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- URL Tujuan -->
                <div>
                    <label for="url" class="block text-sm font-semibold text-gray-700 mb-2">URL Tujuan (opsional)</label>
                    <input type="url" id="url" name="url" placeholder="https://contoh.com"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 @error('url') border-red-500 @enderror"
                        value="{{ old('url', $advertisement->url) }}">
                    @error('url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (opsional)</label>
                <textarea id="description" name="description" rows="4" placeholder="Deskripsi iklan..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 @error('description') border-red-500 @enderror">{{ old('description', $advertisement->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Script (untuk AdSense & Custom Script) -->
            <div id="scriptField" style="display: none;">
                <label for="script" class="block text-sm font-semibold text-gray-700 mb-2">Script Code (untuk AdSense/Custom Script) *</label>
                <textarea id="script" name="script" rows="6" placeholder="Paste AdSense script atau custom script di sini..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 @error('script') border-red-500 @enderror font-mono text-sm">{{ old('script', $advertisement->script) }}</textarea>
                <p class="text-gray-500 text-xs mt-2">Contoh AdSense: &lt;script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"&gt;&lt;/script&gt;</p>
                @error('script')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ukuran Iklan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="size" class="block text-sm font-semibold text-gray-700 mb-2">Ukuran Preset *</label>
                    <select id="size" name="size" onchange="updateDimensions()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600">
                        <option value="">-- Pilih Ukuran --</option>
                        @foreach($sizes as $key => $value)
                            <option value="{{ $key }}" {{ old('size', $advertisement->size) == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('size')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="width" class="block text-sm font-semibold text-gray-700 mb-2">Lebar (px) *</label>
                    <input type="number" id="width" name="width" required min="100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 @error('width') border-red-500 @enderror"
                        value="{{ old('width', $advertisement->width) }}">
                    @error('width')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="height" class="block text-sm font-semibold text-gray-700 mb-2">Tinggi (px) *</label>
                    <input type="number" id="height" name="height" required min="50"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 @error('height') border-red-500 @enderror"
                        value="{{ old('height', $advertisement->height) }}">
                    @error('height')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tanggal Tayang -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai (opsional)</label>
                    <input type="date" id="start_date" name="start_date"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600"
                        value="{{ old('start_date', $advertisement->start_date?->format('Y-m-d')) }}">
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Berakhir (opsional)</label>
                    <input type="date" id="end_date" name="end_date"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600"
                        value="{{ old('end_date', $advertisement->end_date?->format('Y-m-d')) }}">
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $advertisement->is_active) ? 'checked' : '' }}
                        class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-2 focus:ring-red-600">
                    <span class="text-sm font-semibold text-gray-700">Aktifkan Iklan</span>
                </label>
            </div>

            <!-- Statistik -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-3">Statistik</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Views</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $advertisement->view_count }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Klik</p>
                        <p class="text-2xl font-bold text-green-600">{{ $advertisement->click_count }}</p>
                    </div>
                </div>
            </div>

            <!-- Tombol -->
            <div class="flex gap-4 pt-6 border-t">
                <button type="submit" class="bg-red-600 text-white px-8 py-2 rounded-lg hover:bg-red-700 transition font-semibold">
                    <i class="fas fa-save mr-2"></i>Perbarui Iklan
                </button>
                <a href="{{ route('admin.advertisements.index') }}" class="bg-gray-200 text-gray-900 px-8 py-2 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Batal
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
@endsection
