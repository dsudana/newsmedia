<x-admin-layout-modern>
    <div class="space-y-6 pr-4 max-w-4xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Buat Template Newsletter</h1>
                <p class="text-sm text-gray-600 mt-1">Buat template email baru untuk newsletter</p>
            </div>
            <a href="{{ route('admin.newsletter.templates') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="bg-white rounded-md shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.newsletter.store-template') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Nama Template</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           placeholder="Contoh: Daily Digest">
                    @error('name')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Subject Email</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: Berita Hari Ini">
                    @error('subject')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Tipe Template</label>
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="manual">Manual (Kirim Kapan Saja)</option>
                        <option value="daily">Daily (Otomatis Setiap Hari)</option>
                        <option value="weekly">Weekly (Otomatis Setiap Minggu)</option>
                    </select>
                    @error('type')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Konten HTML</label>
                    <textarea name="html_content" rows="10" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm"
                              placeholder="<h1>Hello Newsletter!</h1>
<p>Your HTML content here...</p>">{{ old('html_content') }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">Gunakan HTML murni. Gunakan {{site_name}}, {{contact_email}} untuk variable dinamis.</p>
                    @error('html_content')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t">
                    <a href="{{ route('admin.newsletter.templates') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Buat Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout-modern>
