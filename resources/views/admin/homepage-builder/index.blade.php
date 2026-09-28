<x-admin-layout-modern header="Homepage Builder">
    <div class="space-y-6 pr-4">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-paint-brush text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Homepage Builder</h1>
                    <p class="text-sm text-gray-600 mt-1">Customize your website layout and content sections</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank"
                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-colors flex items-center gap-2">
                    <i class="fas fa-eye"></i>
                    <span>Preview</span>
                </a>
                <button type="button" onclick="openAddSectionModal()"
                    class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg font-semibold hover:shadow-lg transition-shadow flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Section</span>
                </button>
            </div>
        </div>

        <!-- Page Type Tabs -->
        <div class="flex gap-2 border-b border-gray-200">
            <a href="{{ route('admin.homepage-builder.index', 'homepage') }}"
                class="px-6 py-3 font-semibold border-b-2 transition-colors {{ $pageType === 'homepage' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                <i class="fas fa-home mr-2"></i>Homepage
            </a>
            <a href="{{ route('admin.homepage-builder.index', 'blog_index') }}"
                class="px-6 py-3 font-semibold border-b-2 transition-colors {{ $pageType === 'blog_index' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                <i class="fas fa-newspaper mr-2"></i>Blog Index
            </a>
        </div>

        <!-- Info Alert -->
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-4 rounded-lg flex items-start gap-3">
            <i class="fas fa-info-circle text-lg mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="font-semibold">Drag & drop sections untuk mengubah urutan</p>
                <p class="text-sm text-blue-600 mt-1">Seret dan lepaskan item di bawah untuk mengatur ulang urutan
                    tampilan.</p>
            </div>
        </div>

        <!-- Sections List -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div id="sections-container" class="divide-y divide-gray-200">
                @forelse ($sections as $section)
                    @include('admin.homepage-builder.partials.section-item', ['section' => $section])
                @empty
                    <div class="px-6 py-12 text-center">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600 font-medium text-lg">No sections configured</p>
                        <p class="text-sm text-gray-500 mt-2">Add your first section to customize this page</p>
                        <button type="button" onclick="openAddSectionModal()"
                            class="mt-4 px-6 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors inline-flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            Add Section
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Config Modals -->
    @include('admin.homepage-builder.partials.modals.section-config-article_carousel', ['categories' => $categories ?? []])
    @include('admin.homepage-builder.partials.modals.section-config-category_highlight')
    @include('admin.homepage-builder.partials.modals.section-config-newsletter')
    @include('admin.homepage-builder.partials.modals.section-config-image_banner')
    @include('admin.homepage-builder.partials.modals.section-config-text_image_split')
    @include('admin.homepage-builder.partials.modals.section-config-blog_preview')
    @include('admin.homepage-builder.partials.modals.section-config-testimonial')
    @include('admin.homepage-builder.partials.modals.section-config-lookbook')
    @include('admin.homepage-builder.partials.modals.section-config-trending_news_carousel')
    @include('admin.homepage-builder.partials.modals.section-config-hero_carousel')
    @include('admin.homepage-builder.partials.modals.section-config-breaking_news_strip')
    @include('admin.homepage-builder.partials.modals.section-config-recent_and_popular')
    @include('admin.homepage-builder.partials.modals.section-config-category_strip_carousel')
    @include('admin.homepage-builder.partials.modals.section-config-category_grid_section')
    @include('admin.homepage-builder.partials.modals.section-config-category_list_section')
    @include('admin.homepage-builder.partials.modals.section-config-article_carousel_row', ['categories' => $categories ?? []])
    @include('admin.homepage-builder.partials.modals.section-config-featured_news_section', ['categories' => $categories ?? []])
    @include('admin.homepage-builder.partials.modals.section-config-sports_carousel')
    @include('admin.homepage-builder.partials.modals.section-config-video_grid')

    <!-- Add Section Modal -->
    <div id="addSectionModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Add New Section</h2>
                    <p class="text-sm text-gray-600 mt-1">Choose a section type to add to your page</p>
                </div>
                <button type="button" onclick="closeAddSectionModal()"
                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-600 text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($sectionTypes as $type => $config)
                        @php
                            $iconMap = [
                                'carousel' => 'fa-carousel',
                                'category' => 'fa-folder-open',
                                'mail' => 'fa-envelope',
                                'image' => 'fa-image',
                                'layout' => 'fa-th-large',
                                'blog' => 'fa-newspaper',
                                'quote' => 'fa-quote-left',
                                'gallery' => 'fa-images',
                            ];
                            $iconClass = $iconMap[$config['icon']] ?? 'fa-layer-group';
                        @endphp
                        <button type="button" onclick="addSection('{{ $type }}')"
                            class="p-4 border-2 border-gray-200 rounded-lg hover:border-indigo-600 hover:bg-indigo-50 transition-all group">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center mb-3 group-hover:from-indigo-200 group-hover:to-purple-200 transition-colors">
                                <i class="fas {{ $iconClass }} text-indigo-600 text-lg"></i>
                            </div>
                            <p class="font-semibold text-gray-900 text-left">
                                {{ $config['label'] ?? ucfirst(str_replace('_', ' ', $type)) }}</p>
                            <p class="text-xs text-gray-600 mt-1 text-left">
                                {{ $config['description'] ?? 'Add a new section' }}</p>
                        </button>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-600">No section types available</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Page type data attribute for JavaScript -->
    <div data-page-type="{{ $pageType }}" style="display: none;"></div>

    <!-- Sortable.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" crossorigin="anonymous"></script>

    <!-- Homepage Builder JavaScript -->
    <script src="{{ asset('js/homepage-builder.js') }}"></script>
</x-admin-layout-modern>
