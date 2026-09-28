# Performance Optimization Guide

## 📊 Current Status
- ✅ Lazy loading component created
- ✅ Modern responsive design (CSS optimized)
- ⏳ Image optimization
- ⏳ Database query optimization
- ⏳ Caching strategy

---

## 🖼️ Image Optimization

### 1. Using Lazy Loading Component
Replace standard `<img>` tags with lazy-loading component:

```blade
<!-- Before -->
<img src="{{ $article->image }}" alt="{{ $article->title }}">

<!-- After -->
<x-lazy-image src="{{ $article->image }}" alt="{{ $article->title }}" class="w-full h-full object-cover" />
```

### 2. Image Format Optimization
Serve WebP with JPG fallback:

```php
// In model accessor or controller
public function getImageAttribute()
{
    if (empty($this->featured_image)) {
        return asset('images/placeholder.jpg');
    }
    
    $path = 'storage/' . ltrim($this->featured_image, '/');
    return asset($path); // Serve original, optimize via CDN/image service
}
```

### 3. Recommended Image Sizes
- **Hero images:** 1200x600px (16:9)
- **Article cards:** 400x300px (4:3)
- **Thumbnails:** 200x150px (4:3)
- **User avatars:** 80x80px (1:1)

---

## 💾 Caching Strategy

### 1. Database Query Caching
Cache frequently accessed data:

```php
// Categories with article counts (cache 1 day)
$categories = Cache::remember('categories:with_counts', now()->addDay(), function () {
    return Category::active()
        ->withCount('articles')
        ->orderBy('articles_count', 'desc')
        ->get();
});

// Popular tags (cache 1 week)
$popularTags = Cache::remember('tags:popular', now()->addWeek(), function () {
    return Tag::whereHas('articles', fn($q) => $q->published())
        ->withCount('articles')
        ->orderByDesc('articles_count')
        ->limit(20)
        ->get();
});
```

### 2. HTTP Caching Headers
Add to `bootstrap/app.php` or middleware:

```php
// Cache public pages for 1 hour
header('Cache-Control: public, max-age=3600');

// Cache blog articles for 24 hours (cache bust on publish)
if (request()->route()->getName() === 'blog.show') {
    header('Cache-Control: public, max-age=86400');
}
```

### 3. Blade Template Caching
Cache expensive computations:

```blade
@php
    $recentArticles = Cache::remember('articles:recent:5', now()->addHours(6), function () {
        return Article::published()
            ->with(['category', 'user'])
            ->latest('published_at')
            ->take(5)
            ->get();
    });
@endphp
```

---

## 🚀 Implementation Checklist

### High Priority (Immediate Impact)
- [ ] Add lazy loading to all `<img>` tags in frontend
- [ ] Implement database query caching for categories, tags, announcements
- [ ] Add HTTP cache headers for public pages
- [ ] Optimize database indexes (see below)

### Medium Priority (Performance Improvement)
- [ ] Implement Redis cache (if available)
- [ ] Add image CDN integration
- [ ] Minify CSS/JS assets
- [ ] Gzip compression (server-side)

### Low Priority (Polish)
- [ ] Implement service worker for offline support
- [ ] Add WebP image format support
- [ ] Implement HTTP/2 server push
- [ ] Add analytics for performance monitoring

---

## 🗄️ Database Optimization

### 1. Critical Indexes
Create these indexes for common queries:

```sql
-- Articles table
ALTER TABLE articles ADD INDEX idx_published_status (status, published_at DESC);
ALTER TABLE articles ADD INDEX idx_category_published (category_id, published_at DESC);
ALTER TABLE articles ADD INDEX idx_user_published (user_id, published_at DESC);
ALTER TABLE articles ADD INDEX idx_slug (slug);

-- Categories table
ALTER TABLE categories ADD INDEX idx_active_order (is_active, order);

-- Comments table
ALTER TABLE comments ADD INDEX idx_article_approved (article_id, is_approved, parent_id);

-- Tags table
ALTER TABLE tags ADD INDEX idx_slug (slug);
```

### 2. Query Optimization Examples

```php
// ❌ Bad: N+1 queries
$articles = Article::published()->get(); // 1 query
foreach ($articles as $article) {
    $category = $article->category; // N queries
}

// ✅ Good: Eager loading
$articles = Article::published()
    ->with(['category', 'user', 'tags'])
    ->get();
```

---

## 📈 Performance Monitoring

### Metrics to Track
- **LCP (Largest Contentful Paint):** < 2.5s
- **FID (First Input Delay):** < 100ms
- **CLS (Cumulative Layout Shift):** < 0.1
- **Time to First Byte:** < 600ms

### Tools
- Google PageSpeed Insights
- WebPageTest.org
- Lighthouse (Chrome DevTools)
- GTmetrix

---

## 🔧 Code Examples

### Caching Implementation
```php
// app/Http/Controllers/Frontend/HomepageController.php
public function index()
{
    $latestArticles = Cache::remember('articles:latest:20', now()->addHours(2), function () {
        return Article::published()
            ->with(['category', 'user'])
            ->latest('published_at')
            ->take(20)
            ->get();
    });

    $categories = Cache::remember('categories:active', now()->addDay(), function () {
        return Category::active()
            ->withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->take(10)
            ->get();
    });

    return view('frontend.home-modern', compact('latestArticles', 'categories'));
}
```

### Invalidate Cache on Update
```php
// app/Http/Controllers/ArticleController.php
public function store(StoreArticleRequest $request)
{
    $article = Article::create($request->validated());

    // Invalidate related caches
    Cache::forget('articles:latest:20');
    Cache::forget('homepage:featured');
    
    return redirect()->route('admin.articles.index')->with('success', 'Article created');
}
```

---

## 📊 Expected Performance Improvements

After implementing these optimizations:
- **Page Load Time:** 30-50% faster
- **TTFB:** Reduced by 20-30%
- **Database Queries:** Reduced by 40-60%
- **Bandwidth:** Reduced by 15-25% (with lazy loading)

---

## 🎯 Next Steps

1. ✅ Add lazy loading to all images
2. ✅ Implement basic query caching
3. ✅ Add HTTP cache headers
4. ⏳ Optimize database indexes
5. ⏳ Monitor performance with Lighthouse

