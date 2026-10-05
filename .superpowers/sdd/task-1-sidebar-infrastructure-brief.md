# Task 1: Create Sidebar Infrastructure & Components

## Overview

Create reusable sidebar components (tag cloud, social media, ads, newsletter) and register 6 new homebuilder section types in the model's SECTION_TYPES constant.

## Files to Create

1. `resources/views/partials/sidebar/tag-cloud.blade.php` - Tag cloud with links
2. `resources/views/partials/sidebar/social-media.blade.php` - Social media icon buttons
3. `resources/views/partials/sidebar/ads.blade.php` - Advertisement placeholder
4. `resources/views/partials/sidebar/newsletter.blade.php` - Newsletter subscription form

## Files to Modify

1. `app/Models/HomepageSection.php` - Add 6 new section type entries to SECTION_TYPES constant

## Step-by-Step Implementation

### Step 1: Create sidebar directory structure

```bash
mkdir -p resources/views/partials/sidebar
```

### Step 2: Create tag-cloud component

File: `resources/views/partials/sidebar/tag-cloud.blade.php`

```blade
<div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">
        <i class="fas fa-tag text-red-600 mr-2"></i>Tags
    </h3>

    @php
        $tags = $tags ?? \App\Models\Tag::orderByDesc('articles_count')
            ->limit(30)
            ->pluck('name', 'slug');
    @endphp

    <div class="flex flex-wrap gap-2">
        @forelse ($tags as $slug => $name)
            <a href="{{ route('tags.show', $slug) }}"
               class="inline-block px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded hover:bg-red-50 hover:text-red-600 transition-colors">
                {{ $name }}
            </a>
        @empty
            <p class="text-gray-500 text-sm">No tags available</p>
        @endforelse
    </div>
</div>
```

### Step 3: Create social-media component

File: `resources/views/partials/sidebar/social-media.blade.php`

```blade
<div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">
        <i class="fas fa-share-alt text-red-600 mr-2"></i>Follow Sosial Media kami:
    </h3>

    <div class="flex gap-3">
        <a href="https://facebook.com" target="_blank" rel="noopener"
           class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:bg-blue-700 transition-colors">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://twitter.com" target="_blank" rel="noopener"
           class="w-10 h-10 bg-sky-500 text-white rounded-lg flex items-center justify-center hover:bg-sky-600 transition-colors">
            <i class="fab fa-twitter"></i>
        </a>
        <a href="https://instagram.com" target="_blank" rel="noopener"
           class="w-10 h-10 bg-pink-600 text-white rounded-lg flex items-center justify-center hover:bg-pink-700 transition-colors">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="https://linkedin.com" target="_blank" rel="noopener"
           class="w-10 h-10 bg-blue-700 text-white rounded-lg flex items-center justify-center hover:bg-blue-800 transition-colors">
            <i class="fab fa-linkedin-in"></i>
        </a>
        <a href="https://youtube.com" target="_blank" rel="noopener"
           class="w-10 h-10 bg-red-600 text-white rounded-lg flex items-center justify-center hover:bg-red-700 transition-colors">
            <i class="fab fa-youtube"></i>
        </a>
    </div>
</div>
```

### Step 4: Create ads component

File: `resources/views/partials/sidebar/ads.blade.php`

```blade
<div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">
        <i class="fas fa-bullhorn text-red-600 mr-2"></i>Advertisement
    </h3>

    {{-- Ad Space (Placeholder) --}}
    <div class="bg-gray-100 rounded-lg p-8 text-center">
        <div class="aspect-square flex items-center justify-center">
            <div>
                <i class="fas fa-image text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-500 text-sm font-medium">Ad Space 300x300</p>
                <p class="text-gray-400 text-xs mt-1">Your ad here</p>
            </div>
        </div>
    </div>

    {{-- Configurable ads would go here in production --}}
    @if(isset($ads) && count($ads) > 0)
        @foreach($ads as $ad)
            <a href="{{ $ad['url'] ?? '#' }}" target="_blank" rel="noopener"
               class="block mt-4 rounded-lg overflow-hidden hover:opacity-80 transition-opacity">
                <img src="{{ $ad['image'] ?? '' }}" alt="Advertisement" class="w-full">
            </a>
        @endforeach
    @endif
</div>
```

### Step 5: Create newsletter component

File: `resources/views/partials/sidebar/newsletter.blade.php`

```blade
<div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-lg border border-red-200 p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-2">
        <i class="fas fa-envelope text-red-600 mr-2"></i>Newsletter
    </h3>
    <p class="text-sm text-gray-600 mb-4">Subscribe to get latest news and updates</p>

    <form class="space-y-3" onsubmit="handleNewsletterSubscribe(event)">
        @csrf
        <input type="email" name="email" placeholder="Enter your email" required
               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">
        <button type="submit"
                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors text-sm">
            Subscribe
        </button>
    </form>

    <p class="text-xs text-gray-500 mt-3">We don't spam. Unsubscribe at any time.</p>
</div>

<script>
    function handleNewsletterSubscribe(e) {
        e.preventDefault();
        const email = e.target.email.value;

        fetch('/api/newsletter/subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ email })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast('Thank you for subscribing!', 'success');
                e.target.reset();
            } else {
                showToast(data.message || 'Subscription failed', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('An error occurred', 'error');
        });
    }
</script>
```

### Step 6: Add 6 new section types to HomepageSection model

Modify `app/Models/HomepageSection.php` - in the SECTION_TYPES constant array, add these entries AFTER existing types:

```php
'breaking_news_strip' => [
    'label' => 'Breaking News Strip',
    'icon' => 'newspaper',
    'description' => 'Horizontal carousel with breaking news thumbnails',
    'color' => '#E74C3C',
],
'recent_and_popular' => [
    'label' => 'Recent & Popular',
    'icon' => 'fire',
    'description' => 'Recent posts + popular posts in 2-column layout',
    'color' => '#9B59B6',
],
'category_strip_carousel' => [
    'label' => 'Category Strip Carousel',
    'icon' => 'images',
    'description' => 'Category-specific article carousel',
    'color' => '#3498DB',
],
'category_grid_section' => [
    'label' => 'Category Grid',
    'icon' => 'th-large',
    'description' => 'Grid layout for category articles',
    'color' => '#1ABC9C',
],
'category_list_section' => [
    'label' => 'Category List',
    'icon' => 'list',
    'description' => 'Horizontal card list for category articles',
    'color' => '#F39C12',
],
'sports_carousel' => [
    'label' => 'Sports Carousel',
    'icon' => 'futbol',
    'description' => 'Sports news carousel section',
    'color' => '#E67E22',
],
```

### Step 7: Commit changes

```bash
git add resources/views/partials/sidebar/ app/Models/HomepageSection.php
git commit -m "feat: add sidebar components and section type constants"
```

## Acceptance Criteria

- ✅ Sidebar directory created
- ✅ All 4 sidebar components created with proper structure
- ✅ All 6 section types added to SECTION_TYPES with correct metadata
- ✅ All files follow Blade and Laravel conventions
- ✅ Files committed with proper commit message

## Context

This is Task 1 of 8 in the Homebuilder Sections & Sidebar Implementation plan. This task sets up the foundation for both sidebar components (which can be included in layouts) and registers the new section types that will be implemented in subsequent tasks.

Following tasks will add:

- Task 2: Service methods for data resolution
- Task 3-4: Breaking News & Recent/Popular views and configs
- Task 5: Additional service methods
- Task 6-7: Grid, List, and Carousel section views
- Task 8: Testing and final verification
