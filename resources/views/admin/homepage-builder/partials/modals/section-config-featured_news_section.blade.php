<div id="configModal_featured_news_section" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-md shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-red-600 to-red-700 text-white p-6 border-b">
            <h3 class="text-2xl font-bold">Featured News Section Settings</h3>
            <p class="text-sm text-red-50 mt-1">Configure featured news with carousel main article and side articles</p>
        </div>

        <form id="configForm_featured_news_section" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_featured_news_section" value="">

            <!-- Section Title -->
            <div>
                <label for="title_featured" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-heading text-red-600 mr-2"></i>Section Title
                </label>
                <input type="text" id="title_featured" name="title" placeholder="e.g., Featured News"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>

            <!-- Basic Settings -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category_featured" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-folder text-red-600 mr-2"></i>Category Filter (Optional)
                    </label>
                    <select id="category_featured" name="config[category]" onchange="loadFeaturedArticles()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">All Categories</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="sort_featured" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-sort text-red-600 mr-2"></i>Default Sort
                    </label>
                    <select id="sort_featured" name="config[sort_by]"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="latest" selected>Latest</option>
                        <option value="popular">Most Popular</option>
                        <option value="views">Most Viewed</option>
                    </select>
                </div>
            </div>

            <!-- Carousel Settings -->
            <div class="border-t pt-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-sliders-h text-red-600 mr-2"></i>Main Article Carousel Settings
                </h4>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="carousel_enabled" class="flex items-center cursor-pointer">
                            <input type="checkbox" id="carousel_enabled" name="config[carousel_enabled]" value="1"
                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-2 focus:ring-red-500">
                            <span class="ml-3 text-sm font-semibold text-gray-900">Enable Carousel</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">Main article rotates automatically</p>
                    </div>

                    <div>
                        <label for="carousel_speed" class="block text-sm font-semibold text-gray-900 mb-2">
                            <i class="fas fa-tachometer-alt text-red-600 mr-2"></i>Carousel Speed (ms)
                        </label>
                        <input type="number" id="carousel_speed" name="config[carousel_speed]" value="5000" min="2000" max="10000" step="500"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="main_articles_count" class="block text-sm font-semibold text-gray-900 mb-2">
                            <i class="fas fa-images text-red-600 mr-2"></i>Main Articles
                        </label>
                        <input type="number" id="main_articles_count" name="config[main_limit]" value="5" min="1" max="20"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                </div>

                <div class="mt-3">
                    <label for="side_articles_count" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-list text-red-600 mr-2"></i>Side Articles (Right Column)
                    </label>
                    <input type="number" id="side_articles_count" name="config[side_limit]" value="2" min="1" max="10"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
            </div>

            <!-- Manual Article Selection -->
            <div class="border-t pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-hand-pointer text-red-600 mr-2"></i>Manual Article Selection
                    </h4>
                    <div class="flex gap-2">
                        <button type="button" id="selectAllBtn" class="px-3 py-1 text-xs bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                            Select All
                        </button>
                        <button type="button" id="deselectAllBtn" class="px-3 py-1 text-xs bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                            Deselect All
                        </button>
                    </div>
                </div>

                <!-- Article List -->
                <div id="articlesList" class="space-y-2 max-h-96 overflow-y-auto border border-gray-200 rounded-md p-4 bg-gray-50">
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Loading articles...
                    </div>
                </div>

                <input type="hidden" id="selected_articles" name="config[selected_articles]" value="">
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Leave empty to use auto-selection based on sort order
                </p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <p class="text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Layout:</strong> 1 featured article (carousel) on left (66%), side articles stacked on right (33%)
                </p>
            </div>
        </form>

        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('featured_news_section')"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveSectionConfig('featured_news_section')"
                    class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-md hover:opacity-90 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </div>
</div>

@php
    $articles = \App\Models\Article::where('status', 'published')
        ->whereNotNull('featured_image')
        ->where('featured_image', '!=', '')
        ->with(['category', 'user'])
        ->latest('published_at')
        ->limit(100)
        ->get()
        ->map(function($a) {
            return [
                'id' => $a->id,
                'title' => $a->title,
                'category_name' => $a->category?->name ?? 'Uncategorized',
                'category_slug' => $a->category?->slug ?? '',
                'published_at' => $a->published_at?->format('Y-m-d'),
            ];
        });
@endphp

<script>
    // Get all articles data embedded in the page
    const allArticlesData = @json($articles);

    console.log('Loaded', allArticlesData.length, 'articles');

    // Load articles list
    function loadFeaturedArticles() {
        const categoryFilter = document.getElementById('category_featured')?.value || '';
        const articlesList = document.getElementById('articlesList');

        let filtered = allArticlesData;
        if (categoryFilter) {
            filtered = allArticlesData.filter(a => a.category_slug === categoryFilter);
        }

        console.log('Filtered:', filtered.length, 'articles');

        if (filtered.length === 0) {
            articlesList.innerHTML = '<div class="text-center py-8 text-gray-500">No articles found</div>';
            return;
        }

        articlesList.innerHTML = filtered.map(article => `
            <label class="flex items-start p-3 hover:bg-white rounded cursor-pointer transition">
                <input type="checkbox" class="article-checkbox mt-1" value="${article.id}">
                <div class="ml-3 flex-1">
                    <p class="text-sm font-semibold text-gray-900">${article.title}</p>
                    <p class="text-xs text-gray-500">
                        ${article.category_name} • ${article.published_at}
                    </p>
                </div>
            </label>
        `).join('');

        // Load previously selected articles
        const selectedArticles = document.getElementById('selected_articles')?.value;
        if (selectedArticles) {
            try {
                const selectedIds = JSON.parse(selectedArticles);
                document.querySelectorAll('.article-checkbox').forEach(checkbox => {
                    if (selectedIds.includes(parseInt(checkbox.value))) {
                        checkbox.checked = true;
                    }
                });
            } catch (e) {
                console.warn('Could not parse selected articles');
            }
        }
    }

    // Select/Deselect all
    document.getElementById('selectAllBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.article-checkbox').forEach(checkbox => checkbox.checked = true);
    });

    document.getElementById('deselectAllBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.article-checkbox').forEach(checkbox => checkbox.checked = false);
    });

    // Update selected articles on change
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('article-checkbox')) {
            const selectedIds = Array.from(document.querySelectorAll('.article-checkbox:checked'))
                .map(checkbox => parseInt(checkbox.value));
            document.getElementById('selected_articles').value = JSON.stringify(selectedIds);
        }
    });

    // Load config when modal opens
    document.addEventListener('loadSectionConfigFeaturedNewsSection', function(e) {
        const section = e.detail.section;
        document.getElementById('title_featured').value = section.title || '';
        document.getElementById('category_featured').value = section.config?.category || '';
        document.getElementById('sort_featured').value = section.config?.sort_by || 'latest';
        document.getElementById('carousel_enabled').checked = section.config?.carousel_enabled == 1;
        document.getElementById('carousel_speed').value = section.config?.carousel_speed || '5000';
        document.getElementById('main_articles_count').value = section.config?.main_limit || '5';
        document.getElementById('side_articles_count').value = section.config?.side_limit || '2';

        if (section.config?.selected_articles) {
            document.getElementById('selected_articles').value = section.config.selected_articles;
        }

        loadFeaturedArticles();
    });
</script>
