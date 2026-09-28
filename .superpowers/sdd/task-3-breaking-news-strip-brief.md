# Task 3: Create Breaking News Strip Section View & Admin Config

## Overview
Create the Blade view for breaking news strip carousel section and the admin configuration modal. This is the first section type that uses the data resolver from Task 2.

## Files to Create
1. `resources/views/frontend/sections/breaking_news_strip.blade.php` - Section view with Swiper carousel
2. `resources/views/admin/homepage-builder/partials/modals/section-config-breaking_news_strip.blade.php` - Admin config modal

## Files to Modify
- `resources/views/admin/homepage-builder/index.blade.php` - Include new modal (one line)

## Step 1: Create breaking news strip section view

File: `resources/views/frontend/sections/breaking_news_strip.blade.php`

```blade
<section class="bg-gray-50 border-y border-gray-200 py-4">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        @if($title ?? null)
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
        @endif
        
        <div class="carousel-breaking-strip-{{ $section->id }} swiper">
            <div class="swiper-wrapper">
                @forelse ($data['articles'] ?? [] as $article)
                    <div class="swiper-slide">
                        <a href="{{ route('blog.show', $article->slug) }}" class="flex items-center gap-3 group">
                            <img src="{{ $article->featured_image ? '/storage/' . $article->featured_image : '/images/placeholder.jpg' }}" 
                                 alt="{{ $article->title }}"
                                 class="w-16 h-16 object-cover rounded shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500">{{ $article->published_at?->format('M d, Y') }}</p>
                                <p class="text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-red-600 transition-colors">
                                    {{ $article->title }}
                                </p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="swiper-slide">
                        <p class="text-gray-500">No breaking news available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<script>
    new Swiper('.carousel-breaking-strip-{{ $section->id }}', {
        slidesPerView: 'auto',
        spaceBetween: 20,
        autoplay: {
            delay: {{ $data['slider_speed'] ?? 3000 }},
            disableOnInteraction: false,
        },
        breakpoints: {
            640: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 },
        },
    });
</script>
```

## Step 2: Create admin config modal

File: `resources/views/admin/homepage-builder/partials/modals/section-config-breaking_news_strip.blade.php`

```blade
<div id="configModal_breaking_news_strip" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-red-600 to-red-700 text-white p-6 border-b">
            <h3 class="text-2xl font-bold">Breaking News Strip Settings</h3>
            <p class="text-sm text-red-50 mt-1">Configure the breaking news horizontal carousel</p>
        </div>

        <form id="configForm_breaking_news_strip" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_breaking_news_strip" value="">

            <div>
                <label for="title_breaking" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-heading text-red-600 mr-2"></i>Section Title (Optional)
                </label>
                <input type="text" id="title_breaking" name="title" placeholder="e.g., Breaking News"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="limit_breaking" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-list text-red-600 mr-2"></i>Number of Articles
                    </label>
                    <input type="number" id="limit_breaking" name="config[limit]" value="12" min="4" max="30"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

                <div>
                    <label for="speed_breaking" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-tachometer-alt text-red-600 mr-2"></i>Auto-scroll Speed (ms)
                    </label>
                    <input type="number" id="speed_breaking" name="config[slider_speed]" value="3000" min="1000" max="10000" step="500"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
            </div>
        </form>

        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('breaking_news_strip')"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveSectionConfig('breaking_news_strip')"
                    class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:opacity-90 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('loadSectionConfigBreakingNewsStrip', function(e) {
        const section = e.detail.section;
        document.getElementById('title_breaking').value = section.title || '';
        document.getElementById('limit_breaking').value = section.config?.limit || '12';
        document.getElementById('speed_breaking').value = section.config?.slider_speed || '3000';
    });
</script>
```

## Step 3: Add modal include to admin index

Modify `resources/views/admin/homepage-builder/index.blade.php` in the "Config Modals" section. Add this line with the other modal includes:

```blade
@include('admin.homepage-builder.partials.modals.section-config-breaking_news_strip')
```

Place it after the other existing modal includes around line 81.

## Step 4: Commit

```bash
git add resources/views/frontend/sections/breaking_news_strip.blade.php resources/views/admin/homepage-builder/partials/modals/section-config-breaking_news_strip.blade.php resources/views/admin/homepage-builder/index.blade.php
git commit -m "feat: add breaking news strip section type"
```

## Acceptance Criteria
- ✅ Section view file created with Swiper carousel
- ✅ Carousel uses unique ID based on section->id
- ✅ Admin config modal created with title and slider configuration fields
- ✅ Modal event listener populates form with existing section data
- ✅ Modal included in admin index.blade.php
- ✅ Form field names match config keys: `config[limit]`, `config[slider_speed]`
- ✅ All 3 files committed with proper message

## Data Flow
1. Admin creates "Breaking News Strip" section → POST to /admin/homepage-builder
2. Controller calls HomepageBuilderService::resolveSection() with section.section_type = 'breaking_news_strip'
3. Service calls getBreakingNewsStripData() with section.config
4. Frontend view receives $section and $data
5. View renders with data['articles'] and data['slider_speed']
