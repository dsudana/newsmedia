# Homebuilder Adoption Design Spec

**Date:** 2026-07-16  
**Status:** Design Approved  
**Effort Estimate:** 3-4 hours  
**Approach:** Hybrid (Adopt from tokoonlinelar + Refactor for news)

---

## 1. Overview

This specification defines the adoption of a drag-and-drop **Homepage Builder** system from the tokoonlinelar e-commerce platform into the newsmedia news/blog platform.

**Objective:** Allow admins to visually build and customize the homepage (/) and blog index (/blog) by selecting from pre-built section types, configuring content, and reordering via drag-drop — without code.

**Scope:**
- ✅ Homepage (/) — customizable sections
- ✅ Blog Index (/blog) — customizable sections  
- ✅ 8 section types tailored for news content
- ✅ Admin interface with drag-drop reordering
- ✅ Coexist with existing hardcoded layout (fallback if no sections configured)

---

## 2. Architecture

### 2.1 High-Level Flow

**Admin Panel:**
```
Admin Interface (/admin/homepage)
  ↓
HomepageBuilderController (request handler)
  ↓
HomepageBuilderService (business logic + data resolution)
  ↓
HomepageSection Model (data persistence)
  ↓
Database: homepage_sections table
```

**Frontend:**
```
GET / or GET /blog
  ↓
HomepageController@index
  ↓
HomepageBuilderService::getActiveSections($pageType)
  ↓
Resolve related data (articles, categories, etc)
  ↓
Render section partials dynamically
  ↓
Fallback to hardcoded layout if no sections exist
```

### 2.2 Design Principles

- **Single Table, Multiple Pages:** `homepage_sections` table with `page_type` field ("homepage" | "blog_index") eliminates duplicate schema for different pages
- **Service Layer:** `HomepageBuilderService` encapsulates all business logic, making logic reusable and testable
- **JSON Config:** Flexible `config` field stores section-specific settings without schema changes
- **Coexist Pattern:** New homebuilder sections render if available; fallback to existing hardcoded layout if empty
- **Admin-First:** Admin UI drives frontend rendering — no content hardcoded in views

---

## 3. Database Design

### 3.1 Table Schema: `homepage_sections`

```sql
CREATE TABLE homepage_sections (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    page_type ENUM('homepage', 'blog_index') NOT NULL,
    section_type VARCHAR(50) NOT NULL,
    title VARCHAR(255) NULL,
    subtitle TEXT NULL,
    config JSON NOT NULL DEFAULT '{}',
    order INT DEFAULT 0,
    status BOOLEAN DEFAULT 1,
    settings JSON NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_page_type (page_type),
    INDEX idx_section_type (section_type),
    INDEX idx_order (order),
    INDEX idx_status (status)
);
```

**Fields:**
- `page_type` — Which page section belongs to (homepage or blog_index)
- `section_type` — Type of section (article_carousel, category_highlight, etc)
- `title` / `subtitle` — Display titles for the section
- `config` — JSON object storing section-specific config (fields selection, limit, colors, etc)
- `order` — Display order (reordered via drag-drop)
- `status` — Enable/disable section without deleting
- `settings` — Reserved for future use

### 3.2 Model: `HomepageSection`

```php
class HomepageSection extends Model {
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    protected $casts = [
        'config' => 'array',
        'settings' => 'array',
        'status' => 'boolean',
        'order' => 'integer',
    ];
    
    protected $appends = ['type_label'];
    
    // Section type definitions
    const SECTION_TYPES = [
        'article_carousel' => ['label' => 'Article Carousel', 'icon' => 'fas fa-newspaper', ...],
        'category_highlight' => [...],
        // ... 6 more types
    ];
    
    // Scopes
    public function scopeActive($query) { return $query->where('status', true); }
    public function scopeOrdered($query) { return $query->orderBy('order'); }
    public function scopeByPage($query, $pageType) { return $query->where('page_type', $pageType); }
    
    // Accessors
    public function getTypeLabelAttribute() { /* returns human label */ }
}
```

---

## 4. Section Types & Configuration

Eight section types tailored for news platforms:

| Type | Purpose | Key Config Fields |
|------|---------|-------------------|
| `article_carousel` | Featured/latest articles in carousel | limit, columns, field_selection (image, title, date, author, category, excerpt), category_filter, sort_by |
| `category_highlight` | Featured categories grid | category_ids, limit, columns, show_article_count |
| `newsletter` | Email subscription form | button_text, placeholder_text, background_color |
| `image_banner` | Editorial hero banner with CTA | image_url, title, subtitle, cta_text, cta_url, height_mode, alignment |
| `text_image_split` | Article showcase + description (2-col layout) | title, description, cta_text, cta_url, image_url, image_position (left/right) |
| `blog_preview` | Latest blog articles grid | limit, columns, field_selection, sort_by |
| `testimonial` | Reader quotes/reviews | limit, source, background_color |
| `lookbook` | Editorial stories/features | tag_filter, title, style (light/dark) |

**Example Config JSON (article_carousel):**
```json
{
  "limit": 10,
  "columns": 3,
  "fields": ["image", "title", "date", "category"],
  "category_filter": null,
  "sort_by": "latest"
}
```

---

## 5. Service Layer: `HomepageBuilderService`

Centralized business logic for managing and resolving sections:

```php
class HomepageBuilderService {
    // CRUD
    public function getAllSections($pageType) {}
    public function getActiveSections($pageType) {}
    public function createSection(array $data) {}
    public function updateSection(HomepageSection $section, array $data) {}
    public function deleteSection(HomepageSection $section) {}
    
    // Status & Ordering
    public function toggleStatus(HomepageSection $section) {}
    public function reorderSections(array $orders) {}
    
    // Data Resolution
    public function getSectionData(HomepageSection $section) {}
    private function getArticleCarouselData($config) {}
    private function getCategoryHighlightData($config) {}
    // ... resolvers for each section type
    
    // Admin
    public function getSectionTypes() {}
}
```

**Key Methods:**
- `getActiveSections($pageType)` — Fetch active sections for a page, ordered
- `getSectionData($section)` — Resolve related data (articles, categories) based on config
- Data resolution methods query database and return formatted data for view rendering

---

## 6. Admin Interface

### 6.1 Routes

```php
// Admin Homepage Builder Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('homepage-builder')->name('homepage-builder.')->group(function () {
        Route::get('/{pageType}', [HomepageBuilderController::class, 'index'])->name('index');
        Route::post('/', [HomepageBuilderController::class, 'store'])->name('store');
        Route::put('/{section}', [HomepageBuilderController::class, 'update'])->name('update');
        Route::delete('/{section}', [HomepageBuilderController::class, 'destroy'])->name('destroy');
        Route::patch('/{section}/toggle', [HomepageBuilderController::class, 'toggle'])->name('toggle');
        Route::post('/reorder', [HomepageBuilderController::class, 'reorder'])->name('reorder');
        Route::get('/config-form/{type}', [HomepageBuilderController::class, 'configForm'])->name('config-form');
    });
});
```

### 6.2 Admin UI Layout

**Main Page: `/admin/homepage-builder/homepage`**

```
┌─────────────────────────────────────────────────────────┐
│ Homepage Builder          [Preview Homepage] [+ Add Section] │
├─────────────────────────────────────────────────────────┤
│ ℹ️ Drag & drop to reorder sections. Click Config to edit. │
│                                                             │
│ ┌─ [⋮≡ Drag Handle] Article Carousel                     │
│ │  Config: Latest 10 articles, 3 columns, with date      │
│ │  [✓ Active] [Config] [Duplicate] [Delete]              │
│ │                                                          │
│ ┌─ [⋮≡ Drag Handle] Newsletter Signup                    │
│ │  Config: Button "Subscribe", placeholder "Enter email" │
│ │  [✓ Active] [Config] [Duplicate] [Delete]              │
│ │                                                          │
│ └─ [⋮≡ Drag Handle] Category Highlight                  │
│    Config: 6 categories, show article count             │
│    [✗ Inactive] [Config] [Duplicate] [Delete]            │
└─────────────────────────────────────────────────────────┘

Modal: Add Section
  Step 1: Select section type (grid of 8 cards with icons/descriptions)
  Step 2: Fill title + section-specific config
  Submit: Create section

Modal: Configure Section
  Load config form based on section_type
  Edit fields dynamically
  Save: Update section
```

### 6.3 Admin Views Structure

```
resources/views/admin/homepage-builder/
├── index.blade.php                    (main listing page)
├── partials/
│   ├── section-item.blade.php         (single section card in list)
│   └── modals/
│       ├── section-config-article_carousel.blade.php
│       ├── section-config-category_highlight.blade.php
│       ├── section-config-newsletter.blade.php
│       ├── section-config-image_banner.blade.php
│       ├── section-config-text_image_split.blade.php
│       ├── section-config-blog_preview.blade.php
│       ├── section-config-testimonial.blade.php
│       └── section-config-lookbook.blade.php
```

### 6.4 Admin Features

- **Drag-Drop Reordering:** Sortable.js library for visual reordering
- **Toggle On/Off:** Quickly disable sections without deleting
- **Duplicate Section:** Copy existing section configuration
- **AJAX Config Form:** Load section-specific config form without page reload
- **Image Upload:** Upload images for banners (AJAX, preview before save)
- **Live Search:** Search articles/categories in modals
- **Error Handling:** Validation messages + success notifications

---

## 7. Frontend Rendering

### 7.1 Homepage (/) Rendering Flow

```
GET /
  ↓
HomepageController@index
  ↓
$activeSections = HomepageBuilderService::getActiveSections('homepage')
  ↓
if ($activeSections->isEmpty()) {
    // No sections: render hardcoded homepage (fallback)
    return view('frontend.home.hardcoded');
} else {
    // Sections exist: resolve data & render
    $sectionsData = $activeSections->map(fn($section) => 
        HomepageBuilderService::getSectionData($section)
    );
    return view('frontend.home', ['sections' => $sectionsData]);
}
  ↓
View loops sections and includes partials:
  @foreach($sections as $section)
    @include("frontend.sections.{$section->section_type}")
  @endforeach
```

### 7.2 Blog Index (/blog) Rendering Flow

Same as homepage, but with `page_type = 'blog_index'`.

### 7.3 Frontend Partials

```
resources/views/frontend/sections/
├── article_carousel.blade.php    (grid of article cards)
├── category_highlight.blade.php  (grid of category boxes)
├── newsletter.blade.php          (email subscription form)
├── image_banner.blade.php        (full-width hero image + optional CTA)
├── text_image_split.blade.php    (2-col layout: text left, image right)
├── blog_preview.blade.php        (grid of blog post cards)
├── testimonial.blade.php         (carousel or grid of quotes)
└── lookbook.blade.php            (editorial story section)
```

**Data Available in Partials:**
```php
$section              // HomepageSection model
$section->config      // JSON config
$section->title       // Display title
$data                 // Resolved content (articles, categories, etc)
```

**Example: article_carousel.blade.php**
```blade
<section class="py-12">
    <h2>{{ $section->title }}</h2>
    <div class="grid grid-cols-{{ $section->config['columns'] }}">
        @foreach($data['articles'] as $article)
            <article>
                @if(in_array('image', $section->config['fields']))
                    <img src="{{ $article->featured_image_url }}" alt="">
                @endif
                @if(in_array('title', $section->config['fields']))
                    <h3>{{ $article->title }}</h3>
                @endif
                @if(in_array('date', $section->config['fields']))
                    <span>{{ $article->published_at->format('d M Y') }}</span>
                @endif
            </article>
        @endforeach
    </div>
</section>
```

### 7.4 Fallback Logic

If no `HomepageSection` exists for a page type, frontend renders the existing hardcoded layout:
- Homepage → current `frontend.home` (hero carousel, sidebar, etc)
- Blog Index → current `/blog` or `/news` page

This ensures **zero breaking changes** during adoption.

---

## 8. File Changes

### 8.1 New Files to Create

```
app/Http/Controllers/Admin/HomepageBuilderController.php
app/Services/HomepageBuilderService.php
app/Models/HomepageSection.php
database/migrations/YYYY_MM_DD_HHMMSS_create_homepage_sections_table.php

resources/views/admin/homepage-builder/
  ├── index.blade.php
  └── partials/section-item.blade.php
  └── partials/modals/section-config-*.blade.php (8 files)

resources/views/frontend/sections/
  ├── article_carousel.blade.php
  ├── category_highlight.blade.php
  ├── newsletter.blade.php
  ├── image_banner.blade.php
  ├── text_image_split.blade.php
  ├── blog_preview.blade.php
  ├── testimonial.blade.php
  └── lookbook.blade.php

public/js/homepage-builder.js
```

### 8.2 Files to Modify

```
routes/web.php                                  (add homebuilder routes)
app/Http/Controllers/Frontend/HomepageController.php  (add section logic)
resources/views/frontend/home.blade.php         (add fallback check + section loop)
resources/views/layouts/admin.blade.php         (add menu link, optional)
```

---

## 9. Implementation Phases

**Phase 1: Database & Models (30 min)**
- Create migration
- Create HomepageSection model
- Create HomepageBuilderService

**Phase 2: Admin Backend (45 min)**
- Create HomepageBuilderController
- Add routes
- Implement CRUD + reorder logic

**Phase 3: Admin UI (60 min)**
- Build admin views & modals
- Add JavaScript for drag-drop
- Add image upload & validation

**Phase 4: Frontend (45 min)**
- Create frontend controller logic
- Build 8 section partials
- Implement fallback logic

**Phase 5: Testing (15 min)**
- Manual testing: admin CRUD, drag-drop, frontend rendering
- Test fallback logic
- Add success/error messages

**Total: ~3-4 hours**

---

## 10. Error Handling & Edge Cases

| Case | Handling |
|------|----------|
| No sections configured | Render hardcoded layout (fallback) |
| Invalid section_type in config modal | Show error, don't save |
| Article/category deleted after section references it | Service returns empty data, partial handles gracefully |
| Image upload fails | AJAX error, show toast notification |
| Drag-drop reorder fails | Show error, reload page |
| Section-specific field validation | Form validates before submit (client + server) |

---

## 11. Testing Strategy

**Admin:**
- ✅ Create section → appears in list
- ✅ Edit section config → updates correctly
- ✅ Delete section → removed from list
- ✅ Toggle status → on/off works
- ✅ Drag-drop reorder → order persists
- ✅ Image upload → file saved, preview shown

**Frontend:**
- ✅ Sections render on homepage (/)
- ✅ Sections render on blog index (/blog)
- ✅ Fallback to hardcoded if no sections
- ✅ Section data resolves correctly (articles, categories, etc)
- ✅ Each section partial displays without errors
- ✅ Responsive design on mobile

---

## 12. Future Enhancements

- [ ] Section templates (pre-built section layouts for quick reuse)
- [ ] Preview modal in admin (live preview before save)
- [ ] Schedule sections (publish/unpublish by date/time)
- [ ] Section analytics (track which sections drive engagement)
- [ ] Extend to other pages (categories, author pages, etc)
- [ ] Section versioning (rollback to previous config)

---

## 13. Success Criteria

✅ Homepage builder accessible at `/admin/homepage-builder/homepage` and `/admin/homepage-builder/blog_index`  
✅ Admins can create/edit/delete/reorder sections via drag-drop  
✅ 8 section types fully functional with proper data resolution  
✅ Frontend renders sections dynamically on / and /blog  
✅ Fallback to hardcoded layout if no sections configured  
✅ No breaking changes to existing homepage  
✅ All admin operations work via AJAX (no page reloads)  
✅ Mobile-responsive admin UI and frontend rendering  

---

**Prepared by:** Claude Code  
**Date:** 2026-07-16  
**Status:** ✅ Design Approved, Ready for Implementation
