# Priority 1 Implementation Guide

## Status: In Progress ✅

### 1. Accessibility Improvements ✅ DONE

#### a. ARIA Labels & Semantic HTML ✅
- [x] Added aria-label to trending topics region
- [x] Added aria-live="polite" to marquee for screen readers
- [x] Added aria-hidden="true" to duplicated trending items
- [x] Added role="region" and role="marquee"
- [x] Added skip-to-main-content link in layouts/app-modern.blade.php

#### b. Focus States ✅
- [x] Added focus:ring-2 focus:ring-red-500 to trending links
- [x] Added focus:ring-offset-2 dark:focus:ring-offset-slate-900
- [x] CSS already has :focus-visible and high-contrast mode support (in custom.css)
- [x] Button and input elements have min-height: 44px (touch-friendly targets)

### 2. Performance Optimization (In Progress)

#### a. Image Optimization TODO
**Task:** Add srcset and picture elements to hero image
**Files to update:**
- `resources/views/frontend/home-modern.blade.php` - Hero section image

**Implementation:**
```blade
<!-- Replace static img with picture element -->
<picture>
  <source media="(min-width: 1024px)" srcset="{{ $featured->featured_image ? asset('storage/' . $featured->featured_image) : 'https://via.placeholder.com/1000x500' }}" width="1000" height="500">
  <source media="(min-width: 640px)" srcset="{{ $featured->featured_image ? asset('storage/' . $featured->featured_image) : 'https://via.placeholder.com/800x400' }}" width="800" height="400">
  <img src="{{ $featured->featured_image ? asset('storage/' . $featured->featured_image) : 'https://via.placeholder.com/600x300' }}" alt="{{ $featured->title }}" loading="lazy" class="w-full h-full object-cover">
</picture>
```

**Estimated effort:** 30 minutes

#### b. Query Optimization TODO
**Task:** Add eager loading and caching to ArticleController

**Implementation locations:**
- `app/Http/Controllers/Frontend/HomepageController.php` or
- `app/Http/Controllers/Frontend/ArticleController.php`

**Changes needed:**
```php
// Add eager loading
$latestArticles = Article::with('category', 'user', 'comments')
    ->published()
    ->orderByDesc('published_at')
    ->take(20)
    ->get();

// Add caching
$latestArticles = Cache::remember('homepage_articles', now()->addHours(1), function () {
    return Article::with('category', 'user', 'comments')
        ->published()
        ->orderByDesc('published_at')
        ->take(20)
        ->get();
});
```

**Estimated effort:** 45 minutes

#### c. Skeleton Screens TODO
**Task:** Create skeleton/placeholder components for loading states

**Files to create:**
- `resources/views/components/skeleton-card.blade.php`
- `resources/views/components/skeleton-article.blade.php`
- `resources/views/components/skeleton-image.blade.php`

**CSS to add in custom.css:**
```css
@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.skeleton {
  background-color: #e5e7eb;
  border-radius: 0.375rem;
}

.dark .skeleton {
  background-color: #374151;
}
```

**Estimated effort:** 1 hour

### 3. Accessibility Refinement (Completed via CSS)

#### a. Color Contrast ✅
- Already verified in review
- Custom.css has proper contrast support
- Focus indicators visible (red #dc2626 on both light/dark)

#### b. Reduced Motion Support ✅
- Already implemented in custom.css (lines 307-324)
- Marquee disabled for users with prefers-reduced-motion

### 4. Additional Improvements Implemented ✅

#### a. Touch-Friendly Targets ✅
- Already in custom.css: min-height: 44px for buttons/links
- Trending topic links have padding for better touch targets

#### b. Form Validation ✅
- Already in custom.css (lines 296-304)
- Input validation feedback colors: red for invalid, green for valid

## Implementation Checklist

### Phase 1: Accessibility (COMPLETE) ✅
- [x] ARIA labels for marquee
- [x] Skip-to-main link
- [x] Focus ring styles
- [x] Semantic role attributes

### Phase 2: Performance (TODO - 2-3 hours)
- [ ] Image srcset implementation (30 min)
- [ ] Database query optimization (45 min)
- [ ] Skeleton screen components (1 hour)

### Phase 3: Testing (TODO)
- [ ] Accessibility testing with screen reader
- [ ] Performance testing with Lighthouse
- [ ] Mobile responsiveness testing

## Expected Outcomes

**After Phase 1 (Accessibility):** ✅ COMPLETE
- Improved screen reader support
- Better keyboard navigation
- Clearer focus states

**After Phase 2 (Performance):**
- Faster image loading (50-70% size reduction)
- Better database performance (N+1 query fixes)
- Improved perceived performance (skeleton screens)

**Estimated total time:** 3-4 hours
**Estimated score improvement:** 8.1 → 8.7/10

## Next Steps

1. Implement image optimization with srcset
2. Add database query optimization with eager loading
3. Create skeleton screen components
4. Run Lighthouse audit to verify improvements
5. Test on mobile devices
