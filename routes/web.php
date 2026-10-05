<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\HomepageBuilderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleImportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AffiliateLinkController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\ArticleAnalyticsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Frontend\ArticleController as PublicArticleController;
use App\Http\Controllers\Frontend\CategoryController as PublicCategoryController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\HomepageController as FrontendHomepageController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SeoSettingController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\AdvertisementController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendHomepageController::class, 'index'])->name('home');

// Dark mode test page (for development only)
Route::get('/dark-mode-test', fn() => view('dark-mode-test'))->name('dark-mode-test');

// Newsletter subscription
Route::post('/newsletter/subscribe', [SubscriberController::class, 'subscribe'])->name('newsletter.subscribe');

// Advertisement tracking (API endpoints)
Route::post('/api/advertisements/{id}/view', [AdvertisementController::class, 'recordView'])->name('advertisements.view');
Route::post('/api/advertisements/{id}/click', [AdvertisementController::class, 'recordClick'])->name('advertisements.click');

// Berita routes (from retnews) - with rate limiting
Route::get('/berita', [PublicArticleController::class, 'index'])->name('blog.index')->middleware('throttle:100,60');
Route::get('/berita/search', [PublicArticleController::class, 'search'])->name('blog.search')->middleware('throttle:50,60');
Route::get('/berita/kategori/{category:slug}', [PublicArticleController::class, 'category'])->name('blog.category')->middleware('throttle:100,60');
Route::get('/berita/tag/{tag:slug}', [PublicArticleController::class, 'tag'])->name('blog.tag')->middleware('throttle:100,60');
Route::get('/berita/{article:slug}', [PublicArticleController::class, 'show'])->name('blog.show')->middleware('throttle:100,60');

// Comment routes
Route::post('/berita/{article:slug}/comments', [\App\Http\Controllers\PublicCommentController::class, 'store'])->name('comments.store')->middleware('throttle:30,60');
Route::delete('/comments/{comment}', [\App\Http\Controllers\PublicCommentController::class, 'destroy'])->name('comments.destroy')->middleware('auth');

// Legacy blog routes (for backward compatibility)
Route::get('/blog', [PublicArticleController::class, 'index'])->name('blog.index.legacy')->middleware('throttle:100,60');
Route::get('/blog/search', [PublicArticleController::class, 'search'])->name('blog.search.legacy')->middleware('throttle:50,60');
Route::get('/blog/kategori/{category:slug}', [PublicArticleController::class, 'category'])->name('blog.category.legacy')->middleware('throttle:100,60');
Route::get('/blog/tag/{tag:slug}', [PublicArticleController::class, 'tag'])->name('blog.tag.legacy')->middleware('throttle:100,60');
Route::get('/blog/{article:slug}', [PublicArticleController::class, 'show'])->name('blog.show.legacy')->middleware('throttle:100,60');

// Legacy articles routes (for backward compatibility)
Route::get('/articles', [PublicArticleController::class, 'index'])->name('articles.index');
Route::get('/search', [PublicArticleController::class, 'search'])->name('articles.search');
Route::get('/articles/{article:slug}', [PublicArticleController::class, 'show'])->name('articles.show');
Route::get('/categories', [PublicCategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [PublicCategoryController::class, 'show'])->name('categories.show');
Route::get('/go/{slug}', \App\Http\Controllers\RedirectController::class)->name('affiliate.redirect');

// Public gallery routes
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index')->middleware('throttle:60,60');
Route::get('/galeri/kategori/{category:slug}', [GalleryController::class, 'category'])->name('gallery.category')->middleware('throttle:60,60');

// Sitemap routes
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap/static.xml', [\App\Http\Controllers\SitemapController::class, 'static'])->name('sitemap.static');
Route::get('/sitemap/articles.xml', [\App\Http\Controllers\SitemapController::class, 'articles'])->name('sitemap.articles');
Route::get('/sitemap/categories.xml', [\App\Http\Controllers\SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemap/tags.xml', [\App\Http\Controllers\SitemapController::class, 'tags'])->name('sitemap.tags');

// Placeholder routes for navigation (topbar/header links)
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/career', function () {
    return view('career');
})->name('career');

Route::get('/pages', function () {
    return view('pages.index');
})->name('pages');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/news', [PublicArticleController::class, 'index'])->name('news.index');
Route::get('/category', [PublicCategoryController::class, 'index'])->name('category.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('tags', TagController::class);
    Route::resource('articles', ArticleController::class);

    // Article import routes
    Route::get('/articles/import/create', [ArticleImportController::class, 'create'])->name('articles.import.create');
    Route::post('/articles/import', [ArticleImportController::class, 'store'])->name('articles.import');
    Route::post('/articles/import-file', [ArticleImportController::class, 'importFile'])->name('articles.import-file');

    Route::resource('users', UserController::class);
    Route::resource('affiliates', AffiliateLinkController::class);
    Route::get('affiliates/performance/dashboard', [AffiliateLinkController::class, 'performance'])->name('affiliates.performance');
    Route::resource('keywords', KeywordController::class);
    Route::resource('seo-settings', SeoSettingController::class);
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('events', EventController::class);
    Route::resource('videos', VideoController::class);
    Route::resource('advertisements', AdvertisementController::class);
    Route::post('/advertisements/{id}/restore', [AdvertisementController::class, 'restore'])->name('advertisements.restore');

    // Social media management
    Route::resource('social-media', SocialMediaController::class);

    // Comment moderation
    Route::get('/comments', [\App\Http\Controllers\CommentModerationController::class, 'index'])->name('comments.index');
    Route::post('/comments/{comment}/approve', [\App\Http\Controllers\CommentModerationController::class, 'approve'])->name('comments.approve');
    Route::delete('/comments/{comment}/reject', [\App\Http\Controllers\CommentModerationController::class, 'reject'])->name('comments.reject');

    // Import/Export routes
    Route::prefix('import-export')->name('import-export.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ImportExportController::class, 'index'])->name('index');
        Route::post('/import', [\App\Http\Controllers\ImportExportController::class, 'import'])->name('import');
        Route::get('/export', [\App\Http\Controllers\ImportExportController::class, 'export'])->name('export');
        Route::get('/template', [\App\Http\Controllers\ImportExportController::class, 'downloadTemplate'])->name('template');
    });

    // WordPress Import routes
    Route::prefix('wp-import')->name('wp-import.')->group(function () {
        Route::get('/', [\App\Http\Controllers\WpImportController::class, 'show'])->name('index');
        Route::post('/', [\App\Http\Controllers\WpImportController::class, 'import'])->name('import');
    });

    Route::post('/keywords/{keyword}/generate', [KeywordController::class, 'generate'])->name('keywords.generate');

    Route::get('/analytics', [ArticleAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/{article}', [ArticleAnalyticsController::class, 'show'])->name('analytics.show');
    Route::post('/analytics/{article}/record-view', [ArticleAnalyticsController::class, 'recordView'])->name('analytics.record-view');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Homepage Builder routes
    Route::prefix('homepage-builder')->name('homepage-builder.')->group(function () {
        Route::post('/reorder', [HomepageBuilderController::class, 'reorder'])->name('reorder');
        Route::get('/{section}', [HomepageBuilderController::class, 'show'])->name('show');
        Route::patch('/{section}/toggle', [HomepageBuilderController::class, 'toggle'])->name('toggle');
        Route::patch('/{section}', [HomepageBuilderController::class, 'update'])->name('update');
        Route::delete('/{section}', [HomepageBuilderController::class, 'destroy'])->name('destroy');
        Route::post('/', [HomepageBuilderController::class, 'store'])->name('store');
        Route::get('/{pageType?}', [HomepageBuilderController::class, 'index'])->name('index');
    });

    // Platform Showcase
    Route::get('/showcase', [App\Http\Controllers\Admin\ShowcaseController::class, 'index'])->name('showcase.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
