# Task 2: Add Service Methods for Breaking News Strip & Recent & Popular

## Overview
Add 2 data resolver methods to HomepageBuilderService: one for breaking news strip carousel and one for recent & popular articles section. These methods fetch and format article data for use by section views.

## Files to Modify
- `app/Services/HomepageBuilderService.php` - Add 2 methods

## Step-by-Step Implementation

### Step 1: Add getBreakingNewsStripData() method

In `app/Services/HomepageBuilderService.php`, add this method to the data resolver section (after other `get*Data()` methods):

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

### Step 2: Add getRecentAndPopularData() method

In `app/Services/HomepageBuilderService.php`, add this method:

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

### Step 3: Verify existing integration

Confirm that the existing HomepageBuilderController method `resolveSection()` automatically calls these new methods by:
1. The controller method converts section_type (e.g., `breaking_news_strip`) to camelCase method name (e.g., `getBreakingNewsStripData`)
2. The service pattern handles this automatically, no changes needed to controller

Run a quick check (tinker or code review):
```php
// Verify the methods exist and are callable
$service = app(\App\Services\HomepageBuilderService::class);
method_exists($service, 'getBreakingNewsStripData') // should be true
method_exists($service, 'getRecentAndPopularData') // should be true
```

### Step 4: Commit changes

```bash
git add app/Services/HomepageBuilderService.php
git commit -m "feat: add breaking news and recent/popular data resolvers"
```

## Acceptance Criteria
- ✅ Both methods added to HomepageBuilderService
- ✅ Methods have correct signatures and return arrays with expected keys
- ✅ Article queries filter by status='published'
- ✅ Ordering is correct (published_at DESC for breaking, views_count DESC for popular)
- ✅ Config fallbacks are in place (default values for limit, speeds)
- ✅ No syntax errors in PHP
- ✅ Committed with proper message

## Data Contracts

### getBreakingNewsStripData() returns:
```php
[
    'articles' => Collection of Article objects (limited by config['limit'])
    'slider_speed' => int (ms, from config['slider_speed'])
]
```

### getRecentAndPopularData() returns:
```php
[
    'recent_articles' => Collection of Article objects (limited by config['recent_limit'])
    'popular_articles' => Collection of Article objects (limited by config['popular_limit'])
]
```

## Context
Task 2 of 8. These service methods are called automatically by HomepageBuilderController when rendering sections. Task 3 and 4 will create the Blade views and admin config modals that use these methods.

The pattern is:
1. User creates section in admin → controller calls service.resolveSection()
2. Controller converts section_type to method name → calls getBreakingNewsStripData(), etc.
3. Service returns data array
4. View receives $section and $data, renders with Laravel Blade
