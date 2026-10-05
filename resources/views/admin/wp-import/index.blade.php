<x-admin.layout-modern>
    <x-slot name="header">
        WordPress Import
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">WordPress Import</h1>
            <p class="text-gray-600">Import articles from WordPress XML export file with automatic image downloading and
                content cleaning</p>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <div class="text-red-600 mt-0.5">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-red-900 mb-2">Errors occurred:</h3>
                        <ul class="text-red-700 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="text-green-600">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-green-900 font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="text-red-600">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <p class="text-red-900 font-semibold">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200">
                    <form action="{{ route('admin.wp-import.import') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <!-- File Upload -->
                        <div>
                            <label for="xml_file" class="block text-sm font-semibold text-gray-900 mb-3">
                                WordPress XML Export File
                            </label>
                            <div class="relative">
                                <input type="file" id="xml_file" name="xml_file" accept=".xml" required
                                    class="block w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition-colors cursor-pointer"
                                    onchange="updateFileName(this)">
                                <p class="mt-2 text-sm text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Select a WordPress WXR export file (.xml)
                                </p>
                            </div>
                            <p id="fileName" class="mt-3 text-sm font-medium text-gray-700 hidden">
                                <i class="fas fa-file-check text-green-600 mr-2"></i>
                                <span id="fileNameText"></span>
                            </p>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i>
                                What will be imported:
                            </h3>
                            <ul class="text-blue-800 text-sm space-y-2">
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-check text-blue-600 mt-0.5"></i>
                                    <span>All published articles with titles and content</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-check text-blue-600 mt-0.5"></i>
                                    <span>Featured images downloaded from WordPress URLs</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-check text-blue-600 mt-0.5"></i>
                                    <span>Images extracted from article content</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-check text-blue-600 mt-0.5"></i>
                                    <span>Content cleaned (removed HTML formatting tags)</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-check text-blue-600 mt-0.5"></i>
                                    <span>Categories and tags automatically created</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-check text-blue-600 mt-0.5"></i>
                                    <span>Publication dates preserved</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Warning Box -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h3 class="font-semibold text-yellow-900 mb-2 flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle"></i>
                                Important:
                            </h3>
                            <p class="text-yellow-800 text-sm">
                                All existing articles will be deleted before importing. Make sure you have a backup of
                                your current articles.
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-3 pt-4">
                            <button type="submit"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-upload"></i>
                                Import Articles
                            </button>
                            <a href="{{ route('admin.dashboard') }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-900 font-semibold py-3 px-6 rounded-lg transition-colors">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar Info -->
            <aside class="space-y-6">
                <!-- Instructions Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-book"></i>
                        How to Export from WordPress
                    </h3>
                    <ol class="space-y-3 text-sm text-gray-700">
                        <li class="flex gap-3">
                            <span class="font-bold text-blue-600 flex-shrink-0">1.</span>
                            <span>Log in to your WordPress admin panel</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="font-bold text-blue-600 flex-shrink-0">2.</span>
                            <span>Go to <strong>Tools → Export</strong></span>
                        </li>
                        <li class="flex gap-3">
                            <span class="font-bold text-blue-600 flex-shrink-0">3.</span>
                            <span>Select <strong>Posts</strong> (or other content types)</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="font-bold text-blue-600 flex-shrink-0">4.</span>
                            <span>Click <strong>Download Export File</strong></span>
                        </li>
                        <li class="flex gap-3">
                            <span class="font-bold text-blue-600 flex-shrink-0">5.</span>
                            <span>Upload the .xml file here</span>
                        </li>
                    </ol>
                </div>

                <!-- Features Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-sparkles"></i>
                        Features
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-green-600 mt-0.5 flex-shrink-0"></i>
                            <span>Automatic image download</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-green-600 mt-0.5 flex-shrink-0"></i>
                            <span>Content auto-cleaning</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-green-600 mt-0.5 flex-shrink-0"></i>
                            <span>Category/tag creation</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-green-600 mt-0.5 flex-shrink-0"></i>
                            <span>Batch processing</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-green-600 mt-0.5 flex-shrink-0"></i>
                            <span>Progress tracking</span>
                        </li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const fileName = document.getElementById('fileName');
            const fileNameText = document.getElementById('fileNameText');

            if (input.files && input.files[0]) {
                fileNameText.textContent = input.files[0].name;
                fileName.classList.remove('hidden');
            } else {
                fileName.classList.add('hidden');
            }
        }
    </script>
    </x-admin.layout-modern>
