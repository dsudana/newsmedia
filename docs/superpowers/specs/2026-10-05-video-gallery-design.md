# Video Gallery Feature Design

**Date:** 2026-10-05  
**Author:** Claude Code  
**Status:** Design Approved

## Overview

Add a comprehensive video gallery feature to the news media platform, enabling:
1. Video carousel section on home page (featured videos)
2. Dedicated public gallery page (`/galeri`) with pagination and filtering
3. Leverage existing admin video CRUD system for management

## Current State

✅ **Existing Infrastructure:**
- `Video` model with complete fields (title, description, youtube_url, youtube_id, thumbnail_url, category_id, order, status, views_count, user_id, published_at)
- `Admin\VideoController` with full CRUD (create, read, update, delete)
- Admin views for video management (index, create, edit)
- Routes configured: `admin/videos/*`
- YouTube URL parsing and thumbnail extraction already implemented in Video model

❌ **Missing:**
- Video section on home page
- Public gallery page
- Frontend gallery controller
- Gallery views and components

## Architecture

### Database & Models

**Video Model** (existing, no changes needed)
```php
class Video extends Model {
    protected $fillable = [
        'title', 'description', 'youtube_url', 'youtube_id',
        'thumbnail_url', 'category_id', 'order', 'status',
        'views_count', 'user_id', 'published_at'
    ];
    
    public function category() { /* belongs to category */ }
    public function user() { /* belongs to user */ }
    public function scopePublished() { /* published status */ }
    public function scopeRecent() { /* order by published_at desc */ }
    public function scopeByCategory() { /* filter by category */ }
}
```

**No new models needed** — reuse existing Video model with scopes and relationships.

### Routes

**Add to routes/web.php:**
```php
// Public gallery routes (not protected)
Route::get('/galeri', [Frontend\GalleryController::class, 'index'])->name('gallery.index')->middleware('throttle:60,60');
Route::get('/galeri/kategori/{category:slug}', [Frontend\GalleryController::class, 'category'])->name('gallery.category')->middleware('throttle:60,60');
```

**No changes to admin routes** — existing `admin/videos/*` routes remain.

### Controllers

#### 1. HomeController (modify existing)

**Change:** Add video carousel data to home page view
```php
public function index() {
    // ... existing code ...
    
    $videoGallery = Video::published()
        ->orderBy('published_at', 'desc')
        ->take(8)
        ->get();
    
    return view('home', compact(
        // ... existing compacts ...
        'videoGallery'
    ));
}
```

#### 2. Frontend\GalleryController (new)

**File:** `app/Http/Controllers/Frontend/GalleryController.php`

```php
class GalleryController extends Controller {
    public function index(Request $request) {
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
    
    public function category(Category $category, Request $request) {
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

### Views & Components

#### 1. Home Page Video Section

**File:** `resources/views/layouts/partials/video-carousel.blade.php`

Display 8 featured videos in Swiper carousel:
- Thumbnail with YouTube play icon overlay
- Title below thumbnail
- Hover: scale thumbnail + opacity change
- "Lihat Semua Videos" button linking to gallery

Similar structure to existing `category-strip.blade.php`.

#### 2. Gallery Page

**File:** `resources/views/gallery/index.blade.php`

Features:
- Page header with title + description
- **Filter Bar:**
  - Dropdown: Category filter (Semua Kategori + active categories)
  - Dropdown: Sort by (Terbaru, Popular, A-Z)
- **Video Grid:** 3 columns (responsive: 1 mobile, 2 tablet, 3 desktop)
- **Pagination:** 12 videos per page, using Tailwind pagination component
- **Empty State:** Message when no videos found

#### 3. Video Card Component (reusable)

**File:** `resources/views/components/video-card.blade.php`

Props:
- `$video` — Video model instance
- `$link` — Optional: link to video detail (if detail page exists)

Display:
- Thumbnail with play icon overlay
- Title (line-clamp-2)
- Category badge
- View count + published date
- Hover effect: thumbnail scale + shadow

### Data Flow

#### Home Page
1. `HomeController@index()` queries:
   - `Video::published()->latest('published_at')->take(8)`
2. Pass `$videoGallery` to `home` view
3. Home view renders `video-carousel.blade.php` partial
4. Carousel shows in "Featured Videos" section above footer

#### Gallery Page
1. User visits `/galeri`
2. `GalleryController@index()` queries:
   - Base: `Video::published()`
   - Filter by category if selected
   - Sort by selected order (default: newest)
   - Paginate: 12 per page
3. Render `gallery/index.blade.php`
4. User can filter/sort and re-query via form submission
5. Category-specific URL: `/galeri/kategori/{category:slug}`

### Responsive Design

**Breakpoints:**
- Mobile (< 640px): 1 column grid
- Tablet (640px - 1024px): 2 column grid
- Desktop (> 1024px): 3 column grid

**Carousel:**
- All breakpoints: Swiper responsive slides
- Auto-scroll pagination on mobile

### Performance Considerations

1. **Database:**
   - Use `->with(['category', 'user'])` for eager loading
   - Paginate to 12 items max to avoid large result sets
   - Index on `status` and `published_at` columns for queries

2. **Caching (optional future enhancement):**
   - Cache home gallery data for 1 hour
   - Invalidate on video create/update in admin

3. **Image Optimization:**
   - YouTube thumbnails already served by Google CDN
   - No additional image optimization needed

## Implementation Sequence

1. ✅ Create migration (if not exists)
2. ✅ Create `Frontend\GalleryController`
3. ✅ Add routes for gallery
4. ✅ Update `HomeController` with video data
5. ✅ Create `video-carousel.blade.php` partial
6. ✅ Create `gallery/index.blade.php` page
7. ✅ Create `components/video-card.blade.php` component
8. ✅ Update `home` view to include video carousel
9. ✅ Test end-to-end

## Testing

**Manual Testing:**
- [ ] Home page displays video carousel
- [ ] Gallery page loads with pagination
- [ ] Filter by category works
- [ ] Sort options work (newest, popular, A-Z)
- [ ] Responsive design on mobile/tablet/desktop
- [ ] Links to YouTube work correctly
- [ ] Empty states display properly

**Edge Cases:**
- [ ] No published videos → show empty state
- [ ] All videos in draft → no carousel on home
- [ ] Invalid category filter → handle gracefully

## Success Criteria

1. ✅ Video carousel displays on home page with 8 featured videos
2. ✅ Dedicated `/galeri` page exists with grid layout, pagination, and filters
3. ✅ Users can filter by category and sort by date/popularity
4. ✅ Admin can manage videos through existing admin panel
5. ✅ Responsive design works on all breakpoints
6. ✅ No performance degradation on home page load

## Future Enhancements (out of scope)

- Video detail page with full description and comments
- Video recommendations based on category
- Trending videos section
- Share to social media
- Video SEO optimization (sitemap)
- Caching layer for gallery queries

## Files to Create/Modify

**Create:**
- `app/Http/Controllers/Frontend/GalleryController.php`
- `resources/views/gallery/index.blade.php`
- `resources/views/layouts/partials/video-carousel.blade.php`
- `resources/views/components/video-card.blade.php`

**Modify:**
- `app/Http/Controllers/HomeController.php`
- `resources/views/home.blade.php`
- `routes/web.php`

**Database:**
- Migration for `videos` table (verify/create if not exists)

**No changes needed:**
- `app/Models/Video.php` — already complete
- `app/Http/Controllers/Admin/VideoController.php` — already complete
- Admin video views — already complete
