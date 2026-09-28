<x-admin-layout-modern>
<div class="space-y-6 pr-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Import/Export Artikel</h1>
        <p class="text-gray-600 mt-2">Kelola artikel dengan fitur import dan export CSV</p>
    </div>

    {{-- Messages --}}
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <strong>Error:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if (session('errors') && session('errors') is not empty)
        <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
            <strong>Peringatan:</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach (session('errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Export Section --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-download text-blue-500 text-2xl mr-3"></i>
                <h2 class="text-xl font-bold text-gray-900">Export Artikel</h2>
            </div>

            <p class="text-gray-600 mb-4">
                Download semua artikel ({{ $articlesCount }}) dalam format CSV
            </p>

            <div class="space-y-3">
                <form action="{{ route('admin.import-export.export') }}" method="GET">
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition">
                        <i class="fas fa-file-csv mr-2"></i> Export CSV
                    </button>
                </form>

                <p class="text-sm text-gray-500 text-center">
                    Termasuk: Title, Content, Category, Author, Featured Image
                </p>
            </div>
        </div>

        {{-- Import Section --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-upload text-green-500 text-2xl mr-3"></i>
                <h2 class="text-xl font-bold text-gray-900">Import Artikel</h2>
            </div>

            <p class="text-gray-600 mb-4">
                Upload file CSV untuk menambahkan atau update artikel
            </p>

            <form action="{{ route('admin.import-export.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">
                        File CSV
                    </label>
                    <input type="file" name="file" id="file" accept=".csv,.txt" required
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">Max 10MB, format: CSV atau TXT</p>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="skip_duplicates" id="skip_duplicates" value="1" checked
                        class="h-4 w-4 text-blue-500">
                    <label for="skip_duplicates" class="ml-2 text-sm text-gray-700">
                        Lewati artikel yang sudah ada (berdasarkan slug)
                    </label>
                </div>

                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition">
                    <i class="fas fa-upload mr-2"></i> Import CSV
                </button>
            </form>
        </div>
    </div>

    {{-- Template Section --}}
    <div class="mt-8 bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-3">
            <i class="fas fa-file-download mr-2 text-purple-500"></i> Download Template
        </h3>
        <p class="text-gray-600 mb-4">
            Download template CSV untuk melihat format yang benar sebelum import
        </p>
        <a href="{{ route('admin.import-export.template') }}" class="inline-block bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-6 rounded transition">
            <i class="fas fa-download mr-2"></i> Download Template
        </a>
    </div>

    {{-- Instructions --}}
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-bold text-blue-900 mb-3">
            <i class="fas fa-info-circle mr-2"></i> Panduan Penggunaan
        </h3>
        <ul class="list-disc list-inside space-y-2 text-blue-800 text-sm">
            <li>Kolom yang diperlukan: <strong>Title, Slug, Content, Status</strong></li>
            <li>Kolom optional: Category, Author, Featured Image, Excerpt, Published At</li>
            <li>Slug harus unik dan digunakan untuk deteksi duplikat</li>
            <li>Author akan di-match dengan nama user yang ada, atau gunakan admin default</li>
            <li>Gunakan format: <code>YYYY-MM-DD HH:mm:ss</code> untuk Published At</li>
            <li>Status bisa: published, draft, scheduled</li>
        </ul>
    </div>
</div>
</x-x-admin-layout-modern>
