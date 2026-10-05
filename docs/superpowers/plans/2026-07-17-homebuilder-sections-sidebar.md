# Homebuilder Section Types & Sidebar Components Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add 6 new homebuilder section types (breaking_news_strip, recent_and_popular, category_strip_carousel, category_grid_section, category_list_section, sports_carousel) and create a comprehensive sidebar with tag cloud, social media links, ads, and newsletter components.

**Architecture:**

- Each section type follows existing pattern: database model (HomepageSection), service method for data resolution, Blade view for rendering, admin config modal for settings
- Sidebar components are reusable partials placed in `resources/views/partials/sidebar/` with their own data fetching logic
- Section types are registered in HomepageSection model's SECTION_TYPES constant with metadata (label, icon, description, color)
- Config modals follow existing pattern with form fields that map to JSON `config` column

**Tech Stack:** Laravel 11, Blade templates, Swiper.js (carousels), Tailwind CSS, existing HomepageBuilderService pattern

## Global Constraints

- All section types must follow existing naming conventions (snake_case for section_type, camelCase for JavaScript functions)
- Config fields must map to underscore keys in `config` JSON array (e.g., `config[slider_speed]` → `$config['slider_speed']`)
- Admin modals use consistent header colors: section-specific gradient backgrounds
- All carousels use Swiper.js with auto-initialization via unique carousel IDs
- Section views must handle empty data gracefully with placeholder messages
- Sidebar components are independent and can be included in layouts individually

---

## File Structure

**New Files to Create:**

Sidebar Components:

- `resources/views/partials/sidebar/tag-cloud.blade.php` - Tag cloud display
- `resources/views/partials/sidebar/social-media.blade.php` - Social media links
- `resources/views/partials/sidebar/ads.blade.php` - Advertisement section
- `resources/views/partials/sidebar/newsletter.blade.php` - Newsletter signup form

Section Views:

- `resources/views/frontend/sections/breaking_news_strip.blade.php`
- `resources/views/frontend/sections/recent_and_popular.blade.php`
- `resources/views/frontend/sections/category_strip_carousel.blade.php`
- `resources/views/frontend/sections/category_grid_section.blade.php`
- `resources/views/frontend/sections/category_list_section.blade.php`
- `resources/views/frontend/sections/sports_carousel.blade.php`

Admin Config Modals:

- `resources/views/admin/homepage-builder/partials/modals/section-config-breaking_news_strip.blade.php`
- `resources/views/admin/homepage-builder/partials/modals/section-config-recent_and_popular.blade.php`
- `resources/views/admin/homepage-builder/partials/modals/section-config-category_strip_carousel.blade.php`
- `resources/views/admin/homepage-builder/partials/modals/section-config-category_grid_section.blade.php`
- `resources/views/admin/homepage-builder/partials/modals/section-config-category_list_section.blade.php`
- `resources/views/admin/homepage-builder/partials/modals/section-config-sports_carousel.blade.php`

**Files to Modify:**

- `app/Models/HomepageSection.php` - Add 6 new section types to SECTION_TYPES constant
- `app/Services/HomepageBuilderService.php` - Add 6 data resolver methods
- `resources/views/admin/homepage-builder/index.blade.php` - Include 6 new config modals
- `resources/views/frontend/home-modern.blade.php` - Include sidebar components (if using modern layout)

---

### Task 1: Create Sidebar Infrastructure & Tag Cloud Component

**Files:**

- Create: `resources/views/partials/sidebar/tag-cloud.blade.php`
- Create: `resources/views/partials/sidebar/social-media.blade.php`
- Create: `resources/views/partials/sidebar/ads.blade.php`
- Create: `resources/views/partials/sidebar/newsletter.blade.php`
- Modify: `app/Models/HomepageSection.php` - Add 6 new section type entries

**Interfaces:**

- Consumes: Existing Article model with tags, existing database schema
- Produces: Reusable sidebar partials that can be included in any layout

---

- [ ] **Step 1: Create sidebar directory structure**

Run:

```bash
mkdir -p resources/views/partials/sidebar
```

- [ ] **Step 2: Create tag-cloud sidebar component**

Create `resources/views/partials/sidebar/tag-cloud.blade.php`:

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

- [ ] **Step 3: Create social-media sidebar component**

Create `resources/views/partials/sidebar/social-media.blade.php`:

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

- [ ] **Step 4: Create ads sidebar component**

Create `resources/views/partials/sidebar/ads.blade.php`:

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

- [ ] **Step 5: Create newsletter sidebar component**

Create `resources/views/partials/sidebar/newsletter.blade.php`:

```blade
<div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-lg border border-red-200 p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-2">
        <i class="fas fa-envelope text-red-600 mr-2"></i>Newsletter
    </h3>
    <p class="text-sm text-gray-600 mb-4">Subscribe to get latest news and updates</p>

    <form class="space-y-3" onsubmit="handleNewsletterSubscribe(event)">
        @csrf
        <input type="email" name="email" placeholder="Enter your email" required
               class="w-full px-4 py-2 border border-gray-400 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">
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

- [ ] **Step 6: Add 6 new section types to HomepageSection model**

Modify `app/Models/HomepageSection.php`, update SECTION_TYPES constant:

```php
const SECTION_TYPES = [
    // ... existing types ...
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
];
```

- [ ] **Step 7: Commit**

```bash
git add resources/views/partials/sidebar/ app/Models/HomepageSection.php
git commit -m "feat: add sidebar components and section type constants"
```

---

### Task 2: Add Service Methods for Breaking News Strip & Recent & Popular

**Files:**

- Modify: `app/Services/HomepageBuilderService.php` - Add 2 data resolver methods

**Interfaces:**

- Consumes: Article model queries, view needs to receive `$breakingNews` and `$recentPopular` arrays
- Produces: `getBreakingNewsStripData()` method returning articles, `getRecentAndPopularData()` method returning recent + popular arrays

---

- [ ] **Step 1: Add getBreakingNewsStripData() method**

Add to `app/Services/HomepageBuilderService.php` in the data resolver section:

```php
/**
 * Get breaking news strip section data
 */
public function getBreakingNewsStripData($config = []): array
{
    $limit = $config['limit'] ?? 12;
    $sliderSpeed = $config['slider_speed'] ?? 3000;

    $articles = Article::where('status', 'published')
        ->orderByDesc('published_at')
        ->limit($limit)
        ->get(['id', 'title', 'slug', 'featured_image', 'published_at']);

    return [
        'articles' => $articles,
        'slider_speed' => $sliderSpeed,
    ];
}
```

- [ ] **Step 2: Add getRecentAndPopularData() method**

Add to `app/Services/HomepageBuilderService.php`:

```php
/**
 * Get recent and popular articles data
 */
public function getRecentAndPopularData($config = []): array
{
    $recentLimit = $config['recent_limit'] ?? 6;
    $popularLimit = $config['popular_limit'] ?? 4;

    $recent = Article::where('status', 'published')
        ->orderByDesc('published_at')
        ->limit($recentLimit)
        ->get();

    $popular = Article::where('status', 'published')
        ->orderByDesc('views_count')
        ->limit($popularLimit)
        ->get();

    return [
        'recent_articles' => $recent,
        'popular_articles' => $popular,
    ];
}
```

- [ ] **Step 3: Verify methods are accessible via controller**

In `app/Http/Controllers/Admin/HomepageBuilderController.php`, verify `resolveSection()` method calls these new methods. The existing pattern handles method name → method call automatically.

- [ ] **Step 4: Commit**

```bash
git add app/Services/HomepageBuilderService.php
git commit -m "feat: add breaking news and recent/popular data resolvers"
```

---

### Task 3: Create Breaking News Strip Section View & Admin Config

**Files:**

- Create: `resources/views/frontend/sections/breaking_news_strip.blade.php`
- Create: `resources/views/admin/homepage-builder/partials/modals/section-config-breaking_news_strip.blade.php`
- Modify: `resources/views/admin/homepage-builder/index.blade.php` - Include new modal

**Interfaces:**

- Consumes: `$articles` array, `$slider_speed` config value
- Produces: Rendered section view with Swiper carousel

---

- [ ] **Step 1: Create breaking news strip section view**

Create `resources/views/frontend/sections/breaking_news_strip.blade.php`:

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

- [ ] **Step 2: Create admin config modal for breaking news strip**

Create `resources/views/admin/homepage-builder/partials/modals/section-config-breaking_news_strip.blade.php`:

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
                       class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="limit_breaking" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-list text-red-600 mr-2"></i>Number of Articles
                    </label>
                    <input type="number" id="limit_breaking" name="config[limit]" value="12" min="4" max="30"
                           class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

                <div>
                    <label for="speed_breaking" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-tachometer-alt text-red-600 mr-2"></i>Auto-scroll Speed (ms)
                    </label>
                    <input type="number" id="speed_breaking" name="config[slider_speed]" value="3000" min="1000" max="10000" step="500"
                           class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
            </div>
        </form>

        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('breaking_news_strip')"
                    class="px-6 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-100 transition font-semibold">
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

- [ ] **Step 3: Add modal include to admin index**

Modify `resources/views/admin/homepage-builder/index.blade.php`, add after existing modals:

```blade
@include('admin.homepage-builder.partials.modals.section-config-breaking_news_strip')
```

- [ ] **Step 4: Commit**

```bash
git add resources/views/frontend/sections/breaking_news_strip.blade.php resources/views/admin/homepage-builder/partials/modals/section-config-breaking_news_strip.blade.php resources/views/admin/homepage-builder/index.blade.php
git commit -m "feat: add breaking news strip section type"
```

---

### Task 4: Create Recent & Popular Section View & Admin Config

**Files:**

- Create: `resources/views/frontend/sections/recent_and_popular.blade.php`
- Create: `resources/views/admin/homepage-builder/partials/modals/section-config-recent_and_popular.blade.php`
- Modify: `resources/views/admin/homepage-builder/index.blade.php` - Include modal

---

- [ ] **Step 1: Create recent and popular section view**

Create `resources/views/frontend/sections/recent_and_popular.blade.php`:

```blade
<section class="py-8">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-8">
            {{-- Left: Recent Posts --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">Recent Posts</h2>

                {{-- Featured Grid (2) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    @foreach (($data['recent_articles'] ?? [])->take(2) as $article)
                        <a href="{{ route('blog.show', $article->slug) }}" class="group overflow-hidden">
                            <div class="aspect-[4/3] overflow-hidden mb-3 bg-gray-900 group-hover:opacity-90 transition-opacity"
                                style="background-image: url('{{ $article->featured_image ? '/storage/' . $article->featured_image : '/images/placeholder.jpg' }}'); background-size: cover; background-position: center;">
                            </div>
                            <div>
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-600 text-xs font-bold rounded">
                                    {{ $article->category?->name ?? 'News' }}
                                </span>
                                <h3 class="text-sm font-bold text-gray-900 leading-snug mt-2 line-clamp-2 group-hover:text-red-600 transition-colors">
                                    {{ $article->title }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-2">By {{ $article->user?->name ?? 'Admin' }} • {{ $article->published_at?->format('M d, Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Additional List (4) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach (($data['recent_articles'] ?? [])->skip(2)->take(4) as $article)
                        <a href="{{ route('blog.show', $article->slug) }}" class="group flex gap-3 pb-4 hover:opacity-80 transition-opacity">
                            <img src="{{ $article->featured_image ? '/storage/' . $article->featured_image : '/images/placeholder.jpg' }}"
                                 alt="{{ $article->title }}" class="w-20 h-16 object-cover rounded flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500">By {{ $article->user?->name ?? 'Admin' }} • {{ $article->published_at?->format('M d, Y') }}</p>
                                <h4 class="text-sm font-bold text-gray-900 line-clamp-2 group-hover:text-red-600 transition-colors">
                                    {{ $article->title }}
                                </h4>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Right: Popular Posts --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">Popular Posts</h2>

                <ol class="space-y-4">
                    @forelse (($data['popular_articles'] ?? [])->take(4) as $i => $article)
                        <li class="flex gap-3 pb-4">
                            <span class="w-6 h-6 rounded-full bg-red-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mt-1">
                                {{ $i + 1 }}
                            </span>
                            <div class="flex-1">
                                <a href="{{ route('blog.show', $article->slug) }}" class="group">
                                    <span class="inline-block px-2 py-1 bg-red-100 text-red-600 text-xs font-bold rounded">
                                        {{ $article->category?->name ?? 'News' }}
                                    </span>
                                    <p class="text-sm font-bold text-gray-900 leading-snug hover:text-red-600 transition-colors line-clamp-2 mt-1">
                                        {{ $article->title }}
                                    </p>
                                </a>
                            </div>
                        </li>
                    @empty
                        <li class="text-gray-500 text-sm">No popular articles</li>
                    @endforelse
                </ol>
            </div>
        </div>
    </div>
</section>
```

- [ ] **Step 2: Create admin config modal for recent & popular**

Create `resources/views/admin/homepage-builder/partials/modals/section-config-recent_and_popular.blade.php`:

```blade
<div id="configModal_recent_and_popular" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-purple-600 to-purple-700 text-white p-6 border-b">
            <h3 class="text-2xl font-bold">Recent & Popular Settings</h3>
            <p class="text-sm text-purple-50 mt-1">Configure recent and popular articles display</p>
        </div>

        <form id="configForm_recent_and_popular" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_recent_and_popular" value="">

            <div>
                <label for="title_recentpop" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-heading text-purple-600 mr-2"></i>Section Title (Optional)
                </label>
                <input type="text" id="title_recentpop" name="title" placeholder="e.g., Latest Updates"
                       class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="recent_limit" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-clock text-purple-600 mr-2"></i>Recent Articles
                    </label>
                    <input type="number" id="recent_limit" name="config[recent_limit]" value="6" min="2" max="12"
                           class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <div>
                    <label for="popular_limit" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-fire text-purple-600 mr-2"></i>Popular Articles
                    </label>
                    <input type="number" id="popular_limit" name="config[popular_limit]" value="4" min="2" max="10"
                           class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
            </div>
        </form>

        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('recent_and_popular')"
                    class="px-6 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveSectionConfig('recent_and_popular')"
                    class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:opacity-90 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('loadSectionConfigRecentAndPopular', function(e) {
        const section = e.detail.section;
        document.getElementById('title_recentpop').value = section.title || '';
        document.getElementById('recent_limit').value = section.config?.recent_limit || '6';
        document.getElementById('popular_limit').value = section.config?.popular_limit || '4';
    });
</script>
```

- [ ] **Step 3: Add modal include to admin index**

Modify `resources/views/admin/homepage-builder/index.blade.php`, add new modal include.

- [ ] **Step 4: Commit**

```bash
git add resources/views/frontend/sections/recent_and_popular.blade.php resources/views/admin/homepage-builder/partials/modals/section-config-recent_and_popular.blade.php resources/views/admin/homepage-builder/index.blade.php
git commit -m "feat: add recent and popular section type"
```

---

### Task 5: Add Service Methods for Carousel Section Types

**Files:**

- Modify: `app/Services/HomepageBuilderService.php` - Add 3 data resolver methods

---

- [ ] **Step 1: Add getCategoryStripCarouselData() method**

Add to `app/Services/HomepageBuilderService.php`:

```php
/**
 * Get category strip carousel data
 */
public function getCategoryStripCarouselData($config = []): array
{
    $limit = $config['limit'] ?? 12;
    $category = $config['category'] ?? null;
    $sliderSpeed = $config['slider_speed'] ?? 3000;

    $query = Article::where('status', 'published')
        ->orderByDesc('published_at');

    if ($category) {
        $query->whereHas('category', function($q) use ($category) {
            $q->where('slug', $category);
        });
    }

    $articles = $query->limit($limit)->get();

    return [
        'articles' => $articles,
        'slider_speed' => $sliderSpeed,
    ];
}
```

- [ ] **Step 2: Add getCategoryGridSectionData() method**

Add to `app/Services/HomepageBuilderService.php`:

```php
/**
 * Get category grid section data
 */
public function getCategoryGridSectionData($config = []): array
{
    $limit = $config['limit'] ?? 8;
    $category = $config['category'] ?? null;

    $query = Article::where('status', 'published')
        ->orderByDesc('published_at');

    if ($category) {
        $query->whereHas('category', function($q) use ($category) {
            $q->where('slug', $category);
        });
    }

    $articles = $query->limit($limit)->get();

    return ['articles' => $articles];
}
```

- [ ] **Step 3: Add getCategoryListSectionData() method**

Add to `app/Services/HomepageBuilderService.php`:

```php
/**
 * Get category list section data
 */
public function getCategoryListSectionData($config = []): array
{
    $limit = $config['limit'] ?? 6;
    $category = $config['category'] ?? null;

    $query = Article::where('status', 'published')
        ->orderByDesc('published_at');

    if ($category) {
        $query->whereHas('category', function($q) use ($category) {
            $q->where('slug', $category);
        });
    }

    $articles = $query->limit($limit)->get();

    return ['articles' => $articles];
}
```

- [ ] **Step 4: Add getSportsCarouselData() method**

Add to `app/Services/HomepageBuilderService.php`:

```php
/**
 * Get sports carousel data
 */
public function getSportsCarouselData($config = []): array
{
    $limit = $config['limit'] ?? 10;
    $sliderSpeed = $config['slider_speed'] ?? 3000;

    $articles = Article::where('status', 'published')
        ->whereHas('category', function($q) {
            $q->where('slug', 'sports');
        })
        ->orderByDesc('published_at')
        ->limit($limit)
        ->get();

    return [
        'articles' => $articles,
        'slider_speed' => $sliderSpeed,
    ];
}
```

- [ ] **Step 5: Commit**

```bash
git add app/Services/HomepageBuilderService.php
git commit -m "feat: add carousel section data resolvers"
```

---

### Task 6: Create Category Strip Carousel & Category Grid Section Views

**Files:**

- Create: `resources/views/frontend/sections/category_strip_carousel.blade.php`
- Create: `resources/views/frontend/sections/category_grid_section.blade.php`
- Create: Admin config modals for both
- Modify: `resources/views/admin/homepage-builder/index.blade.php` - Include 2 modals

---

- [ ] **Step 1: Create category strip carousel view**

Create `resources/views/frontend/sections/category_strip_carousel.blade.php`:

```blade
<section class="py-8">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        @if($title ?? null)
            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $title }}</h2>
        @endif

        <div class="carousel-category-strip-{{ $section->id }} swiper">
            <div class="swiper-wrapper">
                @forelse ($data['articles'] ?? [] as $article)
                    <div class="swiper-slide">
                        <a href="{{ route('blog.show', $article->slug) }}" class="block group">
                            <div class="overflow-hidden aspect-[4/3] mb-3 bg-gray-900 group-hover:opacity-90 transition-opacity">
                                <img src="{{ $article->featured_image ? '/storage/' . $article->featured_image : '/images/placeholder.jpg' }}"
                                     alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <p class="text-xs text-gray-500">By {{ $article->user?->name ?? 'Admin' }} • {{ $article->published_at?->format('M d') }}</p>
                            <p class="text-sm font-bold text-gray-900 line-clamp-2 group-hover:text-red-600 transition-colors">
                                {{ $article->title }}
                            </p>
                        </a>
                    </div>
                @empty
                    <div class="swiper-slide text-center py-8">
                        <p class="text-gray-500">No articles available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<script>
    new Swiper('.carousel-category-strip-{{ $section->id }}', {
        slidesPerView: 3,
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

- [ ] **Step 2: Create category strip carousel admin modal**

Create `resources/views/admin/homepage-builder/partials/modals/section-config-category_strip_carousel.blade.php`:

```blade
<div id="configModal_category_strip_carousel" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6 border-b">
            <h3 class="text-2xl font-bold">Category Strip Carousel Settings</h3>
            <p class="text-sm text-blue-50 mt-1">Configure category carousel display</p>
        </div>

        <form id="configForm_category_strip_carousel" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_category_strip_carousel" value="">

            <div>
                <label for="title_catstrip" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-heading text-blue-600 mr-2"></i>Section Title
                </label>
                <input type="text" id="title_catstrip" name="title" placeholder="e.g., Latest News"
                       class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category_catstrip" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-folder text-blue-600 mr-2"></i>Category
                    </label>
                    <select id="category_catstrip" name="config[category]"
                            class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Category::all() as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="limit_catstrip" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-list text-blue-600 mr-2"></i>Number of Articles
                    </label>
                    <input type="number" id="limit_catstrip" name="config[limit]" value="12" min="6" max="30"
                           class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label for="speed_catstrip" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-tachometer-alt text-blue-600 mr-2"></i>Auto-scroll Speed (ms)
                </label>
                <input type="number" id="speed_catstrip" name="config[slider_speed]" value="3000" min="1000" max="10000" step="500"
                       class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </form>

        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('category_strip_carousel')"
                    class="px-6 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveSectionConfig('category_strip_carousel')"
                    class="px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:opacity-90 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('loadSectionConfigCategoryStripCarousel', function(e) {
        const section = e.detail.section;
        document.getElementById('title_catstrip').value = section.title || '';
        document.getElementById('category_catstrip').value = section.config?.category || '';
        document.getElementById('limit_catstrip').value = section.config?.limit || '12';
        document.getElementById('speed_catstrip').value = section.config?.slider_speed || '3000';
    });
</script>
```

- [ ] **Step 3: Create category grid section view**

Create `resources/views/frontend/sections/category_grid_section.blade.php`:

```blade
<section class="py-8">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        @if($title ?? null)
            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $title }}</h2>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($data['articles'] ?? [] as $article)
                <a href="{{ route('blog.show', $article->slug) }}" class="group block overflow-hidden">
                    <div class="aspect-video overflow-hidden mb-3 bg-gray-900 group-hover:opacity-90 transition-opacity">
                        <img src="{{ $article->featured_image ? '/storage/' . $article->featured_image : '/images/placeholder.jpg' }}"
                             alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div>
                        <span class="inline-block px-2 py-1 bg-teal-100 text-teal-700 text-xs font-bold rounded mb-2">
                            {{ $article->category?->name ?? 'News' }}
                        </span>
                        <h3 class="text-sm font-bold text-gray-900 leading-snug line-clamp-2 group-hover:text-red-600 transition-colors">
                            {{ $article->title }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-2">By {{ $article->user?->name ?? 'Admin' }} • {{ $article->published_at?->format('M d, Y') }}</p>
                    </div>
                </a>
            @empty
                <div class="col-span-2 text-center py-12">
                    <p class="text-gray-500">No articles available</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
```

- [ ] **Step 4: Create category grid section admin modal**

Create `resources/views/admin/homepage-builder/partials/modals/section-config-category_grid_section.blade.php`:

```blade
<div id="configModal_category_grid_section" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-teal-600 to-teal-700 text-white p-6 border-b">
            <h3 class="text-2xl font-bold">Category Grid Settings</h3>
            <p class="text-sm text-teal-50 mt-1">Configure category grid display</p>
        </div>

        <form id="configForm_category_grid_section" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_category_grid_section" value="">

            <div>
                <label for="title_catgrid" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-heading text-teal-600 mr-2"></i>Section Title
                </label>
                <input type="text" id="title_catgrid" name="title" placeholder="e.g., Lifestyle"
                       class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category_catgrid" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-folder text-teal-600 mr-2"></i>Category
                    </label>
                    <select id="category_catgrid" name="config[category]"
                            class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        @foreach(\App\Models\Category::all() as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="limit_catgrid" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-list text-teal-600 mr-2"></i>Number of Articles
                    </label>
                    <input type="number" id="limit_catgrid" name="config[limit]" value="8" min="4" max="20"
                           class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>
            </div>
        </form>

        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('category_grid_section')"
                    class="px-6 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveSectionConfig('category_grid_section')"
                    class="px-6 py-2 bg-gradient-to-r from-teal-600 to-teal-700 text-white rounded-lg hover:opacity-90 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('loadSectionConfigCategoryGridSection', function(e) {
        const section = e.detail.section;
        document.getElementById('title_catgrid').value = section.title || '';
        document.getElementById('category_catgrid').value = section.config?.category || '';
        document.getElementById('limit_catgrid').value = section.config?.limit || '8';
    });
</script>
```

- [ ] **Step 5: Add modal includes to admin index**

Modify `resources/views/admin/homepage-builder/index.blade.php`, add both new modal includes.

- [ ] **Step 6: Commit**

```bash
git add resources/views/frontend/sections/category_strip_carousel.blade.php resources/views/frontend/sections/category_grid_section.blade.php resources/views/admin/homepage-builder/partials/modals/section-config-category_*.blade.php resources/views/admin/homepage-builder/index.blade.php
git commit -m "feat: add category strip carousel and grid section types"
```

---

### Task 7: Create Category List & Sports Carousel Section Views

**Files:**

- Create: `resources/views/frontend/sections/category_list_section.blade.php`
- Create: `resources/views/frontend/sections/sports_carousel.blade.php`
- Create: Admin config modals for both
- Modify: `resources/views/admin/homepage-builder/index.blade.php` - Include 2 modals

---

- [ ] **Step 1: Create category list section view**

Create `resources/views/frontend/sections/category_list_section.blade.php`:

```blade
<section class="py-8">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        @if($title ?? null)
            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $title }}</h2>
        @endif

        <div class="space-y-6">
            @forelse ($data['articles'] ?? [] as $article)
                <a href="{{ route('blog.show', $article->slug) }}" class="flex gap-4 group hover:opacity-80 transition-opacity">
                    <div class="w-40 h-32 overflow-hidden shrink-0 bg-gray-900 rounded-lg"
                        style="background-image: url('{{ $article->featured_image ? '/storage/' . $article->featured_image : '/images/placeholder.jpg' }}'); background-size: cover; background-position: center;">
                    </div>
                    <div class="flex-1">
                        <span class="inline-block px-3 py-1 bg-orange-100 text-orange-700 text-xs font-bold rounded mb-2">
                            {{ $article->category?->name ?? 'News' }}
                        </span>
                        <h3 class="text-sm font-bold text-gray-900 leading-snug line-clamp-2 group-hover:text-red-600 transition-colors">
                            {{ $article->title }}
                        </h3>
                        <p class="text-xs text-gray-600 mt-2 leading-relaxed line-clamp-2">
                            {{ $article->excerpt ?? 'Read more...' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-2">By {{ $article->user?->name ?? 'Admin' }} • {{ $article->published_at?->format('M d, Y') }}</p>
                    </div>
                </a>
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500">No articles available</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
```

- [ ] **Step 2: Create category list section admin modal**

Create `resources/views/admin/homepage-builder/partials/modals/section-config-category_list_section.blade.php`:

```blade
<div id="configModal_category_list_section" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-orange-600 to-orange-700 text-white p-6 border-b">
            <h3 class="text-2xl font-bold">Category List Settings</h3>
            <p class="text-sm text-orange-50 mt-1">Configure category list display</p>
        </div>

        <form id="configForm_category_list_section" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_category_list_section" value="">

            <div>
                <label for="title_catlist" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-heading text-orange-600 mr-2"></i>Section Title
                </label>
                <input type="text" id="title_catlist" name="title" placeholder="e.g., Technology"
                       class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category_catlist" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-folder text-orange-600 mr-2"></i>Category
                    </label>
                    <select id="category_catlist" name="config[category]"
                            class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        @foreach(\App\Models\Category::all() as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="limit_catlist" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-list text-orange-600 mr-2"></i>Number of Articles
                    </label>
                    <input type="number" id="limit_catlist" name="config[limit]" value="6" min="3" max="15"
                           class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
            </div>
        </form>

        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('category_list_section')"
                    class="px-6 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveSectionConfig('category_list_section')"
                    class="px-6 py-2 bg-gradient-to-r from-orange-600 to-orange-700 text-white rounded-lg hover:opacity-90 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('loadSectionConfigCategoryListSection', function(e) {
        const section = e.detail.section;
        document.getElementById('title_catlist').value = section.title || '';
        document.getElementById('category_catlist').value = section.config?.category || '';
        document.getElementById('limit_catlist').value = section.config?.limit || '6';
    });
</script>
```

- [ ] **Step 3: Create sports carousel view**

Create `resources/views/frontend/sections/sports_carousel.blade.php`:

```blade
<section class="py-8">
    <div class="max-w-6xl mx-auto px-4 lg:px-8">
        @if($title ?? null)
            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $title }}</h2>
        @endif

        <div class="carousel-sports-{{ $section->id }} swiper">
            <div class="swiper-wrapper">
                @forelse ($data['articles'] ?? [] as $article)
                    <div class="swiper-slide">
                        <a href="{{ route('blog.show', $article->slug) }}" class="group block overflow-hidden">
                            <div class="aspect-[3/2] overflow-hidden mb-3 bg-gray-900 group-hover:opacity-90 transition-opacity">
                                <img src="{{ $article->featured_image ? '/storage/' . $article->featured_image : '/images/placeholder.jpg' }}"
                                     alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div>
                                <span class="inline-block px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded mb-2">
                                    {{ $article->category?->name ?? 'Sports' }}
                                </span>
                                <h3 class="text-sm font-bold text-gray-900 leading-snug line-clamp-2 group-hover:text-red-600 transition-colors">
                                    {{ $article->title }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-2">By {{ $article->user?->name ?? 'Admin' }} • {{ $article->published_at?->format('M d') }}</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="swiper-slide text-center py-8">
                        <p class="text-gray-500">No sports articles available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<script>
    new Swiper('.carousel-sports-{{ $section->id }}', {
        slidesPerView: 3,
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

- [ ] **Step 4: Create sports carousel admin modal**

Create `resources/views/admin/homepage-builder/partials/modals/section-config-sports_carousel.blade.php`:

```blade
<div id="configModal_sports_carousel" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-red-600 to-red-700 text-white p-6 border-b">
            <h3 class="text-2xl font-bold">Sports Carousel Settings</h3>
            <p class="text-sm text-red-50 mt-1">Configure sports news carousel</p>
        </div>

        <form id="configForm_sports_carousel" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="sectionId_sports_carousel" value="">

            <div>
                <label for="title_sports" class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-heading text-red-600 mr-2"></i>Section Title
                </label>
                <input type="text" id="title_sports" name="title" placeholder="e.g., Sports News"
                       class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="limit_sports" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-list text-red-600 mr-2"></i>Number of Articles
                    </label>
                    <input type="number" id="limit_sports" name="config[limit]" value="10" min="5" max="25"
                           class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>

                <div>
                    <label for="speed_sports" class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-tachometer-alt text-red-600 mr-2"></i>Auto-scroll Speed (ms)
                    </label>
                    <input type="number" id="speed_sports" name="config[slider_speed]" value="3000" min="1000" max="10000" step="500"
                           class="w-full px-4 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
            </div>
        </form>

        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t flex justify-end gap-3">
            <button type="button" onclick="closeConfigModal('sports_carousel')"
                    class="px-6 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-100 transition font-semibold">
                Cancel
            </button>
            <button type="button" onclick="saveSectionConfig('sports_carousel')"
                    class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:opacity-90 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('loadSectionConfigSportsCarousel', function(e) {
        const section = e.detail.section;
        document.getElementById('title_sports').value = section.title || '';
        document.getElementById('limit_sports').value = section.config?.limit || '10';
        document.getElementById('speed_sports').value = section.config?.slider_speed || '3000';
    });
</script>
```

- [ ] **Step 5: Add modal includes to admin index**

Modify `resources/views/admin/homepage-builder/index.blade.php`, add both new modal includes.

- [ ] **Step 6: Commit**

```bash
git add resources/views/frontend/sections/category_list_section.blade.php resources/views/frontend/sections/sports_carousel.blade.php resources/views/admin/homepage-builder/partials/modals/section-config-category_list_section.blade.php resources/views/admin/homepage-builder/partials/modals/section-config-sports_carousel.blade.php resources/views/admin/homepage-builder/index.blade.php
git commit -m "feat: add category list and sports carousel section types"
```

---

### Task 8: Final Setup - Clear Caches & Test All Sections

**Files:**

- No files to create/modify

---

- [ ] **Step 1: Clear all caches**

Run:

```bash
php artisan cache:clear && php artisan view:clear && php artisan route:clear && php artisan config:clear
```

Expected: All cache cleared successfully messages.

- [ ] **Step 2: Test homebuilder access**

Navigate to `http://localhost/admin/homepage-builder` in browser. Verify:

- All 16 section types visible in "Add Section" modal
- No console errors
- Admin interface responsive

- [ ] **Step 3: Test adding each new section**

For each section type, click card → config modal opens → set values → click Save. Verify:

- Section appears in list
- Success toast displays
- Form values persisted on re-edit

- [ ] **Step 4: Test frontend rendering**

Add one section of each type to homepage. View homepage at `http://localhost/`. Verify:

- All sections render without errors
- Carousels auto-scroll
- Images load
- Links work
- Responsive on mobile

- [ ] **Step 5: Commit**

```bash
git add -A
git commit -m "chore: clear caches and verify all section types working"
```

---

## Summary

This plan adds:

- **4 Sidebar Components** (Tag Cloud, Social Media, Ads, Newsletter)
- **6 New Section Types** (Breaking News Strip, Recent & Popular, Category Strip Carousel, Category Grid, Category List, Sports Carousel)
- **Service methods** for data resolution
- **Admin config modals** with configurable options for each section

Total tasks: 8, estimated 4-6 hours.
