<x-admin.layout-modern>
    <div class="space-y-6 pr-4 max-w-4xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ isset($page) ? 'Edit' : 'Buat' }} Halaman Legal</h1>
                <p class="text-sm text-gray-600 mt-1">{{ isset($page) ? $page->title : 'Halaman Legal Baru' }}</p>
            </div>
            <a href="{{ route('admin.legal-pages.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="bg-white rounded-md shadow-sm border border-gray-200 p-6">
            <form action="{{ isset($page) ? route('admin.legal-pages.update', $page) : route('admin.legal-pages.store') }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($page)) @method('PUT') @endif

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Judul</label>
                    <input type="text" name="title" value="{{ isset($page) ? $page->title : old('title') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           placeholder="Contoh: Privacy Policy">
                    @error('title')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Slug (URL)</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-4 py-2 border border-r-0 border-gray-300 bg-gray-50 text-gray-600">/</span>
                        <input type="text" name="slug" value="{{ isset($page) ? $page->slug : old('slug') }}" required
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="privacy-policy" readonly>
                    </div>
                    @error('slug')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Konten (HTML)</label>
                    <textarea name="content" rows="12"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm"
                              placeholder="<h1>Privacy Policy</h1><p>Your content here...</p>" required>{{ isset($page) ? $page->content : old('content') }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">Gunakan HTML untuk formatting. Link akan dibuka di /{{ isset($page) ? $page->slug : 'privacy-policy' }}</p>
                    @error('content')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t">
                    <a href="{{ route('admin.legal-pages.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-{{ isset($page) ? 'save' : 'plus' }} mr-2"></i>{{ isset($page) ? 'Simpan' : 'Buat' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin.layout-modern>
