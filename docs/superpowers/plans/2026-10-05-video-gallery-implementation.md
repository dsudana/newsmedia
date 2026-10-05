# Video Gallery Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add a video gallery feature with carousel section on home page and a dedicated public gallery page with pagination and category filtering.

**Architecture:** Reuse existing `Video` model and database. Create new `Frontend\GalleryController` for public gallery page. Add video carousel section to home page via `HomepageController`. Create reusable `video-card` component and `video-carousel` partial. All views built with Tailwind CSS following existing design patterns.

**Tech Stack:**

- Laravel 12 (existing)
- Tailwind CSS (existing)
- Blade templating (existing)
- Swiper.js for carousel (existing in project)

**Spec:** `docs/superpowers/specs/2026-10-05-video-gallery-design.md`

## Global Constraints

- All video gallery queries must use `Video::published()` scope to show only published videos
- Pagination limit: 12 videos per gallery page
- Home page carousel: 8 featured videos
- All styling must use Tailwind CSS dark mode compatible classes (following existing patterns)
- Responsive breakpoints: mobile (1 col), tablet (2 col), desktop (3 col)
- Category filtering: dropdown with "Semua Kategori" option + all active categories
- Sorting options: Terbaru (newest), Popular (most views), A-Z (title alphabetical)

## Review Focus

1. **Pagination edge case:** Gallery page with no videos published should show empty state, not pagination controls
2. **Category filter validation:** Invalid or non-existent category_id in URL should gracefully show all videos without error
3. **Dark mode compatibility:** Video card component must display correctly in both light and dark modes (check shadows, text contrast, badge colors)
4. **Performance:** Home page carousel queries should use eager loading (`->with(['category', 'user'])`) to avoid N+1 queries
5. **Empty state handling:** Home page should show nothing if no published videos exist (no broken carousel layout)

---

## File Structure

### New Files

- `app/Http/Controllers/Frontend/GalleryController.php` — Public gallery page logic with filtering and sorting
- `resources/views/gallery/index.blade.php` — Full gallery page with filter bar, grid, pagination
- `resources/views/layouts/partials/video-carousel.blade.php` — Carousel component for home page
- `resources/views/components/video-card.blade.php` — Reusable video card (thumbnail, title, metadata)

### Modified Files

- `app/Http/Controllers/Frontend/HomepageController.php` — Add video gallery data query
- `resources/views/frontend/home-modern.blade.php` — Include video carousel partial
- `routes/web.php` — Add gallery routes

### No Changes

- `app/Models/Video.php` — Already complete
- `database/migrations/*create_videos_table.php` — Already created and has all fields
- `app/Http/Controllers/Admin/VideoController.php` — Already complete
- Admin video views — Already complete

---

## Tasks

### Task 1: Create Frontend\GalleryController

**Files:**

- Create: `app/Http/Controllers/Frontend/GalleryController.php`

**Interfaces:**

- Consumes: `Video` model (published scope, relations with category/user), `Category` model (active scope)
- Produces: `GalleryController` class with `index()` and `category()` methods
    - `index(Request $request)` → returns view with `$videos` (paginated), `$categories`, optional `$category`
    - `category(Category $category, Request $request)` → returns filtered view

- [ ] **Step 1: Create the GalleryController file with proper namespace**

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Category;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::active()->orderBy('name')->get();

        $videos = Video::published()
            ->when($request->category_id,
                fn($q) => $q->where('category_id', $request->category_id)
            )
            ->when($request->sort === 'popular',
                fn($q) => $q->orderBy('views_count', 'desc')
            )
            ->when($request->sort === 'oldest',
                fn($q) => $q->orderBy('published_at', 'asc')
            )
            ->orderBy('published_at', 'desc') // default
            ->paginate(12);

        return view('gallery.index', compact('videos', 'categories'));
    }

    public function category(Category $category, Request $request)
    {
        $categories = Category::active()->orderBy('name')->get();

        $videos = Video::published()
            ->where('category_id', $category->id)
            ->when($request->sort === 'popular',
                fn($q) => $q->orderBy('views_count', 'desc')
            )
            ->when($request->sort === 'oldest',
                fn($q) => $q->orderBy('published_at', 'asc')
            )
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('gallery.index', compact('videos', 'categories', 'category'));
    }
}
```

- [ ] **Step 2: Verify the file is created and syntax is correct**

Run: `php artisan tinker` and test the controller loads without errors:

```
>>> use App\Http\Controllers\Frontend\GalleryController;
>>> class_exists('App\Http\Controllers\Frontend\GalleryController')
```

Expected: Output `true`

- [ ] **Step 3: Commit**

```bash
git add app/Http/Controllers/Frontend/GalleryController.php
git commit -m "feat: create frontend GalleryController for public video gallery"
```

---

### Task 2: Add Gallery Routes

**Files:**

- Modify: `routes/web.php` (add 2 new routes)

**Interfaces:**

- Consumes: `Frontend\GalleryController` with methods `index()` and `category(Category $category, Request $request)`
- Produces: Two public routes: `/galeri` and `/galeri/kategori/{category:slug}`

- [ ] **Step 1: Open routes/web.php and find the public routes section**

Look for the lines near line 40-90 where article routes are defined (lines starting with `Route::get('/berita'...)`).

- [ ] **Step 2: Add gallery routes after article routes, before admin middleware group**

Insert these lines after line 65 (after the legacy `/categories/{category:slug}` route):

```php
// Public gallery routes
Route::get('/galeri', [Frontend\GalleryController::class, 'index'])->name('gallery.index')->middleware('throttle:60,60');
Route::get('/galeri/kategori/{category:slug}', [Frontend\GalleryController::class, 'category'])->name('gallery.category')->middleware('throttle:60,60');
```

Make sure to import the controller at the top of the file:

```php
use App\Http\Controllers\Frontend\GalleryController;
```

Check if `Frontend` is already imported. If not, add this line near the other `use` statements around line 16-18.

- [ ] **Step 3: Verify routes are accessible**

Run: `php artisan route:list | grep galeri`

Expected output: Two routes listed:

```
GET       /galeri                           gallery.index
GET       /galeri/kategori/{category:slug}  gallery.category
```

- [ ] **Step 4: Commit**

```bash
git add routes/web.php
git commit -m "feat: add public gallery routes /galeri and /galeri/kategori/{slug}"
```

---

### Task 3: Create Video Card Component

**Files:**

- Create: `resources/views/components/video-card.blade.php`

**Interfaces:**

- Consumes: `$video` (Video model with title, thumbnail_url, views_count, published_at, category, youtube_url)
- Produces: Reusable Blade component `<x-video-card :$video />`

- [ ] **Step 1: Create the component file**

```php
@props(['video'])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-lg transition-shadow overflow-hidden group">
    <!-- Thumbnail Container -->
    <div class="relative h-48 bg-gray-200 dark:bg-gray-700 overflow-hidden">
        <!-- Thumbnail Image -->
        <img
            src="{{ $video->thumbnail_url }}"
            alt="{{ $video->title }}"
            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
            loading="lazy"
        >

        <!-- YouTube Play Icon Overlay -->
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors flex items-center justify-center">
            <div class="bg-red-600 rounded-full p-3 opacity-0 group-hover:opacity-100 transition-opacity transform scale-0 group-hover:scale-100 transition-transform">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path>
                </svg>
            </div>
        </div>

        <!-- Status Badge -->
        <span class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full">
            {{ $video->status === 'published' ? 'Live' : 'Draft' }}
        </span>
    </div>

    <!-- Content -->
    <div class="p-4">
        <!-- Title -->
        <h3 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-2 mb-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
            {{ $video->title }}
        </h3>

        <!-- Category Badge -->
        @if($video->category)
            <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold px-2.5 py-1 rounded mb-3">
                {{ $video->category->name }}
            </span>
        @endif

        <!-- Metadata -->
        <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
            <span class="flex items-center gap-1">
                <i class="fas fa-eye"></i>
                {{ number_format($video->views_count) }}
            </span>
            <span>{{ $video->published_at?->format('d M Y') }}</span>
        </div>
    </div>
</div>
```

- [ ] **Step 2: Verify component renders without errors**

Test in a Blade view by adding this line temporarily:

```blade
<x-video-card :video="$videos->first()" />
```

Expected: No PHP errors, HTML renders correctly with thumbnail, title, category, views, date

- [ ] **Step 3: Commit**

```bash
git add resources/views/components/video-card.blade.php
git commit -m "feat: create video-card component with thumbnail and metadata"
```

---

### Task 4: Create Video Carousel Partial

**Files:**

- Create: `resources/views/layouts/partials/video-carousel.blade.php`

**Interfaces:**

- Consumes: `$videoGallery` (Collection of 8 Video models)
- Produces: Swiper carousel HTML for embedding in home page

- [ ] **Step 1: Create the partial**

```blade
<section class="mb-12">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
            Featured Videos
            <span class="text-red-600 text-2xl">›</span>
        </h2>
        <a href="{{ route('gallery.index') }}" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-semibold transition">
            Lihat Semua Videos →
        </a>
    </div>

    @if($videoGallery && $videoGallery->count() > 0)
        <div class="swiper video-carousel-swiper">
            <div class="swiper-wrapper">
                @foreach($videoGallery as $video)
                    <div class="swiper-slide">
                        <x-video-card :$video />
                    </div>
                @endforeach
            </div>
            <!-- Navigation -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof Swiper !== 'undefined') {
                        new Swiper('.video-carousel-swiper', {
                            slidesPerView: 1,
                            spaceBetween: 20,
                            autoplay: {
                                delay: 5000,
                                disableOnInteraction: false,
                            },
                            pagination: {
                                el: '.swiper-pagination',
                                clickable: true,
                            },
                            navigation: {
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                            },
                            breakpoints: {
                                640: {
                                    slidesPerView: 2,
                                    spaceBetween: 15,
                                },
                                1024: {
                                    slidesPerView: 4,
                                    spaceBetween: 20,
                                },
                            },
                        });
                    }
                });
            </script>
        @endpush
    @else
        <div class="text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <i class="fas fa-video text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <p class="text-gray-500 dark:text-gray-400">No videos available</p>
        </div>
    @endif
</section>
```

- [ ] **Step 2: Verify partial structure**

Ensure the file:

- Has correct Blade syntax
- Uses existing `<x-video-card>` component
- Has Swiper configuration for responsive slides
- Has empty state message

- [ ] **Step 3: Commit**

```bash
git add resources/views/layouts/partials/video-carousel.blade.php
git commit -m "feat: create video-carousel partial with Swiper configuration"
```

---

### Task 5: Create Gallery Index Page

**Files:**

- Create: `resources/views/gallery/index.blade.php`

**Interfaces:**

- Consumes: `$videos` (LengthAwarePaginator), `$categories` (Collection), optional `$category` (Category model)
- Produces: Gallery page view with filter, sort, grid, pagination

- [ ] **Step 1: Create gallery/index.blade.php**

```blade
@extends('layouts.app-modern')

@section('title', 'Video Gallery - NEWSMEDIA')

@section('content')
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 py-12 mb-12">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-4xl font-bold text-white mb-2">Video Gallery</h1>
            <p class="text-red-100">Discover the latest videos from our newsroom</p>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 pb-12">
        <!-- Filter & Sort Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Category
                    </label>
                    <select
                        id="category"
                        name="category_id"
                        class="w-full px-4 py-2 border border-gray-400 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort Filter -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Sort By
                    </label>
                    <select
                        id="sort"
                        name="sort"
                        class="w-full px-4 py-2 border border-gray-400 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    >
                        <option value="newest" @selected(request('sort') === 'newest' || !request('sort'))>Newest</option>
                        <option value="popular" @selected(request('sort') === 'popular')>Most Popular</option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>Oldest</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="md:col-span-2">
                    <button
                        type="submit"
                        class="w-full md:w-auto bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-2 rounded-lg transition"
                    >
                        Filter Videos
                    </button>
                    <a
                        href="{{ route('gallery.index') }}"
                        class="ml-3 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white text-sm"
                    >
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>

        <!-- Active Filters Display -->
        @if(request('category_id') || (request('sort') && request('sort') !== 'newest'))
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg">
                <p class="text-sm text-blue-800 dark:text-blue-300">
                    <strong>Filters:</strong>
                    @if(request('category_id'))
                        Category: {{ $categories->find(request('category_id'))?->name ?? 'Unknown' }}
                        @if(request('sort') && request('sort') !== 'newest')
                            •
                        @endif
                    @endif
                    @if(request('sort') && request('sort') !== 'newest')
                        Sort: {{ ucfirst(request('sort')) }}
                    @endif
                </p>
            </div>
        @endif

        <!-- Videos Grid -->
        @if($videos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach($videos as $video)
                    <a href="{{ $video->youtube_url }}" target="_blank" class="group">
                        <x-video-card :$video />
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $videos->links('pagination::tailwind') }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-12 text-center">
                <i class="fas fa-video text-6xl text-gray-300 dark:text-gray-600 mb-4 block"></i>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Videos Found</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Try adjusting your filters or check back later for new content.
                </p>
                <a
                    href="{{ route('gallery.index') }}"
                    class="inline-block bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition"
                >
                    Reset Filters
                </a>
            </div>
        @endif
    </main>
@endsection
```

- [ ] **Step 2: Verify the gallery directory exists**

Check if `resources/views/gallery/` directory exists. If not, create it:

```bash
mkdir -p resources/views/gallery
```

- [ ] **Step 3: Test page structure**

Verify the file:

- Has proper Blade extends and sections
- Uses existing `<x-video-card>` component
- Has filter form with category and sort dropdowns
- Has pagination links
- Has empty state message

- [ ] **Step 4: Commit**

```bash
git add resources/views/gallery/index.blade.php
git commit -m "feat: create gallery index page with filtering and pagination"
```

---

### Task 6: Update HomepageController with Video Data

**Files:**

- Modify: `app/Http/Controllers/Frontend/HomepageController.php` (add video gallery query)

**Interfaces:**

- Consumes: `Video` model with published scope
- Produces: `$videoGallery` variable passed to view (collection of 8 videos)

- [ ] **Step 1: Open HomepageController and add import for Video model**

At the top of the file (around line 6-11), add `Video` to the imports:

```php
use App\Models\Video;
```

- [ ] **Step 2: Add video gallery query in the index method**

Inside the `index()` method, right after the `upcomingEvents` cache query (around line 91), add:

```php
// Get featured videos for gallery section
$videoGallery = Cache::remember('homepage_video_gallery', now()->addHours(1), function () {
    return Video::published()
        ->with(['category:id,name', 'user:id,name'])
        ->latest('published_at')
        ->take(8)
        ->get(['id', 'title', 'youtube_id', 'thumbnail_url', 'category_id', 'views_count', 'published_at']);
});
```

- [ ] **Step 3: Update the return statement for modern view**

On line 95, modify the `compact()` call to include `'videoGallery'`:

Change:

```php
return view('frontend.home-modern', compact('latestArticles', 'categories', 'sidebarCategories', 'sidebarArticles', 'announcements', 'upcomingEvents'));
```

To:

```php
return view('frontend.home-modern', compact('latestArticles', 'categories', 'sidebarCategories', 'sidebarArticles', 'announcements', 'upcomingEvents', 'videoGallery'));
```

- [ ] **Step 4: Verify the changes**

Check that:

- Video model is imported
- Cache key is unique: `homepage_video_gallery`
- Query uses eager loading with `with()`
- Only necessary fields are selected
- Variable is passed to view via compact

- [ ] **Step 5: Commit**

```bash
git add app/Http/Controllers/Frontend/HomepageController.php
git commit -m "feat: add video gallery query to HomepageController with caching"
```

---

### Task 7: Update Home-Modern View with Video Carousel

**Files:**

- Modify: `resources/views/frontend/home-modern.blade.php` (add video carousel section)

**Interfaces:**

- Consumes: `$videoGallery` (passed from HomepageController)
- Produces: Rendered video carousel section in home page

- [ ] **Step 1: Locate the News Update section**

Open `resources/views/frontend/home-modern.blade.php` and find the "News Update" section (around line 234-290).

- [ ] **Step 2: Add video carousel before the News Update section**

Insert this code right before the `<!-- Category/News Update Section -->` comment (around line 234):

```blade
<!-- Featured Videos Carousel Section -->
@if($videoGallery && $videoGallery->count() > 0)
    <section class="mb-12">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                Featured Videos
                <span class="text-red-600 text-2xl">›</span>
            </h2>
            <a href="{{ route('gallery.index') }}" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-semibold transition">
                Lihat Semua Videos →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($videoGallery as $video)
                <a href="{{ $video->youtube_url }}" target="_blank" class="group">
                    <x-video-card :$video />
                </a>
            @endforeach
        </div>
    </section>
@endif
```

- [ ] **Step 3: Verify the placement**

Make sure the new section:

- Is placed between featured hero and news update sections
- Uses the same header styling as other sections
- Has "Lihat Semua Videos" link pointing to gallery
- Uses 4-column grid (responsive: 1 mobile, 2 tablet, 4 desktop)
- Uses `<x-video-card>` component

- [ ] **Step 4: Commit**

```bash
git add resources/views/frontend/home-modern.blade.php
git commit -m "feat: add featured video section to home page"
```

---

### Task 8: End-to-End Testing

**No files created/modified — testing only**

- [ ] **Step 1: Test database connectivity and data**

Run artisan tinker:

```bash
php artisan tinker
```

Inside tinker:

```php
$videos = \App\Models\Video::published()->take(8)->get();
$videos->count()
// Expected: Integer >= 0 (number of published videos)
```

Exit tinker with `exit`.

- [ ] **Step 2: Test home page renders with video carousel**

```bash
php artisan serve
```

Visit `http://localhost:8000` in browser.

Expected:

- Page loads without errors
- "Featured Videos" section appears
- Up to 8 video cards display in a 4-column grid
- Each card shows thumbnail, title, category, views, date
- "Lihat Semua Videos" link is clickable
- On mobile: grid collapses to 1 column
- On tablet: grid shows 2 columns

**Testing checklist:**

- [ ] Videos load with thumbnails
- [ ] Hover effect on cards (thumbnail scale, shadow change)
- [ ] Category badge displays
- [ ] View count and date display correctly
- [ ] Links point to YouTube
- [ ] Responsive layout works

- [ ] **Step 3: Test gallery page loads**

Visit `http://localhost:8000/galeri` in browser.

Expected:

- Page loads with "Video Gallery" header
- Filter dropdown for categories shows
- Sort dropdown shows 3 options
- Video grid displays (if videos exist)
- Pagination shows (if > 12 videos)
- Empty state message shows (if no videos)

**Testing checklist:**

- [ ] Filter form submits without errors
- [ ] Category filter works (page shows only selected category)
- [ ] Sort options work (displays in correct order)
- [ ] Pagination links work
- [ ] Clear Filters link resets filters
- [ ] Mobile responsive layout (1 col, 2 col, 3 col)
- [ ] Dark mode displays correctly

- [ ] **Step 4: Test category-specific gallery page**

Assuming a category exists (e.g., with slug "technology"):

Visit `http://localhost:8000/galeri/kategori/technology` in browser.

Expected:

- Page loads
- Only videos in "Technology" category display
- Category dropdown shows "Technology" selected
- URL shows correct category slug

- [ ] **Step 5: Test edge cases**

**No videos published:**

```bash
php artisan tinker
\App\Models\Video::query()->update(['status' => 'draft']);
exit
```

Visit home page and gallery page.

Expected:

- Home page: Video section hidden (no "Featured Videos" heading)
- Gallery page: Empty state message displays
- No pagination shows

Restore videos:

```bash
php artisan tinker
\App\Models\Video::query()->update(['status' => 'published']);
exit
```

**Invalid category in URL:**

Visit `http://localhost:8000/galeri/kategori/nonexistent`

Expected:

- 404 error or graceful redirect (depending on Laravel's implicit route model binding)

**Performance check:**

Open browser DevTools → Network tab

Visit home page.

Expected:

- Single database query for `$videoGallery` (not N+1 queries)
- Page loads in < 1 second
- No console errors

- [ ] **Step 6: All tests passed?**

If any test failed, investigate and fix before proceeding to step 7.

If all tests passed, proceed to step 7.

- [ ] **Step 7: Commit (if needed)**

If no code changes were made during testing:

```bash
git status
# Expected: working tree clean
```

If any bug fixes were made:

```bash
git add .
git commit -m "fix: [description of what was fixed]"
```

---

## Implementation Notes

### Routes Import

Make sure to import `Frontend\GalleryController` or use the full namespace in routes. Check existing route imports for consistency.

### Swiper.js

The carousel uses Swiper.js which should already be available in the project (used by category-strip). If not, ensure it's included in the layout's `@push('scripts')` section.

### Pagination View

The gallery page uses `{{ $videos->links('pagination::tailwind') }}` which should work with Laravel 12's default Tailwind pagination view. If custom pagination view exists, adjust the path accordingly.

### Component Path

Ensure `resources/views/components/` directory exists. Blade automatically looks there for components referenced as `<x-component-name />`.

### Database Indexes

The migration already has indexes on `status`, `published_at`, and `category_id` columns which will optimize the gallery queries. No additional indexes needed.

### Caching

Both `HomepageController` and `GalleryController` cache-friendly queries use eager loading to prevent N+1 issues. The `videoGallery` is cached for 1 hour on home page. Consider adding cache invalidation in admin when videos are created/updated/deleted (optional future enhancement).

### Testing Tools

- Laravel Tinker for database testing
- Browser DevTools for performance and responsive testing
- PHP artisan serve for local development

---

## Success Criteria (Verification Checklist)

- [ ] Home page displays video carousel with up to 8 featured videos
- [ ] Gallery page `/galeri` loads and displays video grid with pagination
- [ ] Category filter on gallery works correctly
- [ ] Sort options (Newest, Popular, Oldest) work on gallery page
- [ ] Empty state displays when no videos published
- [ ] Responsive design: 1 col (mobile), 2 col (tablet), 3-4 col (desktop)
- [ ] Dark mode styling works on all components
- [ ] Links to YouTube open in new tab
- [ ] No console errors on home page or gallery page
- [ ] Database queries optimized (eager loading, caching)
- [ ] All routes working without 404 errors
- [ ] Pagination displays only when > 12 videos
