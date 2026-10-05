<x-admin.layout-modern>
    <x-slot name="header">
        Import Articles from WordPress XML
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Info Alert -->
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg flex gap-3">
            <i class="fas fa-info-circle text-blue-600 mt-1 flex-shrink-0"></i>
            <div>
                <h3 class="font-semibold text-blue-900">Import from WordPress XML</h3>
                <p class="text-sm text-blue-800 mt-1">Import articles from ndskreasi project or other WordPress exports. You can either paste XML content or upload a .xml file.</p>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Method 1: Paste XML -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-code text-indigo-600 text-lg"></i>
                    <h2 class="text-lg font-semibold text-gray-900">Paste XML Content</h2>
                </div>

                <form action="{{ route('admin.articles.import') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Category Selection -->
                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-gray-900 mb-2">
                            Default Category <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" id="category_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="">Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- XML Content -->
                    <div>
                        <label for="xml_content" class="block text-sm font-semibold text-gray-900 mb-2">
                            XML Content <span class="text-red-500">*</span>
                        </label>
                        <textarea name="xml_content" id="xml_content" required rows="10"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-mono text-sm"
                            placeholder="Paste your WordPress XML export here...">{{ old('xml_content') }}</textarea>
                        @error('xml_content')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Options -->
                    <div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="download_images" value="1" checked
                                class="rounded border-gray-300 text-indigo-600 shadow-sm">
                            <span class="text-sm text-gray-700">Download and store featured images</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-upload"></i>Import Articles
                    </button>
                </form>
            </div>

            <!-- Method 2: Upload File -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-file-upload text-green-600 text-lg"></i>
                    <h2 class="text-lg font-semibold text-gray-900">Upload XML File</h2>
                </div>

                <form action="{{ route('admin.articles.import-file') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Category Selection -->
                    <div>
                        <label for="category_id_2" class="block text-sm font-semibold text-gray-900 mb-2">
                            Default Category <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" id="category_id_2" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-1 focus:ring-green-500">
                            <option value="">Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label for="xml_file" class="block text-sm font-semibold text-gray-900 mb-2">
                            XML File <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-500 hover:bg-green-50 transition cursor-pointer" id="dropZone">
                            <input type="file" name="xml_file" id="xml_file" class="hidden" accept=".xml" required>
                            <div class="flex flex-col items-center">
                                <i class="fas fa-file-xml text-3xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600 font-medium">Click to upload or drag and drop</p>
                                <p class="text-gray-500 text-sm">XML file only</p>
                            </div>
                            <div id="fileName" class="mt-4 text-sm text-green-600 font-medium hidden"></div>
                        </div>
                        @error('xml_file')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Options -->
                    <div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="download_images" value="1" checked
                                class="rounded border-gray-300 text-green-600 shadow-sm">
                            <span class="text-sm text-gray-700">Download and store featured images</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-upload"></i>Import from File
                    </button>
                </form>
            </div>
        </div>

        <!-- Documentation -->
        <div class="mt-8 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-6 border border-gray-200">
            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-book text-gray-600"></i>
                Import Guidelines
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">✅ Supported Fields</h4>
                    <ul class="text-sm text-gray-700 space-y-1">
                        <li>• Article title</li>
                        <li>• URL slug</li>
                        <li>• Article content (HTML)</li>
                        <li>• Excerpt / description</li>
                        <li>• Meta title & description</li>
                        <li>• Featured images</li>
                        <li>• Publication status & date</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">📋 Format Requirements</h4>
                    <ul class="text-sm text-gray-700 space-y-1">
                        <li>• WordPress XML (WXR) format</li>
                        <li>• Valid XML structure required</li>
                        <li>• Recommended from ndskreasi export</li>
                        <li>• Images will be downloaded & stored locally</li>
                        <li>• Duplicate slugs will be skipped</li>
                        <li>• Articles assigned to current user</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // File drag and drop
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('xml_file');
        const fileName = document.getElementById('fileName');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        dropZone.addEventListener('drop', handleDrop, false);
        dropZone.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', updateFileName);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight(e) {
            dropZone.classList.add('border-green-500', 'bg-green-50');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-green-500', 'bg-green-50');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            updateFileName();
        }

        function updateFileName() {
            if (fileInput.files.length > 0) {
                fileName.textContent = fileInput.files[0].name;
                fileName.classList.remove('hidden');
            } else {
                fileName.classList.add('hidden');
            }
        }
    </script>
</x-admin.layout-modern>
