<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\SeoSetting;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Sitemap index - lists all sitemap files
     */
    public function index()
    {
        $sitemaps = Cache::remember('sitemap.index', 3600, function () {
            return [
                ['url' => route('sitemap.static'), 'lastmod' => now()->toAtomString()],
                ['url' => route('sitemap.articles'), 'lastmod' => Article::where('status', 'published')->latest('updated_at')->value('updated_at')],
                ['url' => route('sitemap.categories'), 'lastmod' => Category::latest('updated_at')->value('updated_at')],
                ['url' => route('sitemap.tags'), 'lastmod' => Tag::latest('updated_at')->value('updated_at')],
            ];
        });

        return response()->view('sitemap.index', compact('sitemaps'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Static pages sitemap
     */
    public function static()
    {
        $pages = Cache::remember('sitemap.static', 3600, function () {
            $staticPages = [
                ['url' => route('home'), 'changefreq' => 'daily', 'priority' => 1.0],
                ['url' => route('blog.index'), 'changefreq' => 'daily', 'priority' => 0.9],
                ['url' => route('contact'), 'changefreq' => 'monthly', 'priority' => 0.5],
                ['url' => route('about'), 'changefreq' => 'monthly', 'priority' => 0.6],
            ];

            // Add SEO settings for these pages if they exist
            $seoPages = ['home', 'blog', 'contact', 'about'];
            foreach ($seoPages as $page) {
                $seo = SeoSetting::getByPageName($page);
                if ($seo) {
                    $key = array_search(['url' => route($this->getRoute($page))], array_map(function ($p) { return ['url' => $p['url']]; }, $staticPages));
                    if ($key !== false) {
                        $staticPages[$key]['changefreq'] = $seo->sitemap_changefreq ?? 'monthly';
                        $staticPages[$key]['priority'] = $seo->sitemap_priority ?? 0.5;
                    }
                }
            }

            return $staticPages;
        });

        return response()->view('sitemap.static', compact('pages'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Articles sitemap
     */
    public function articles()
    {
        $articles = Cache::remember('sitemap.articles', 3600, function () {
            return Article::where('status', 'published')
                ->select(['id', 'slug', 'updated_at'])
                ->latest('published_at')
                ->get();
        });

        $seo = SeoSetting::getByPageName('blog');
        $priority = $seo?->sitemap_priority ?? 0.8;
        $changefreq = $seo?->sitemap_changefreq ?? 'daily';

        return response()->view('sitemap.articles', compact('articles', 'priority', 'changefreq'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Categories sitemap
     */
    public function categories()
    {
        $categories = Cache::remember('sitemap.categories', 3600, function () {
            return Category::where('is_active', true)
                ->select(['id', 'slug', 'updated_at'])
                ->latest()
                ->get();
        });

        return response()->view('sitemap.categories', compact('categories'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Tags sitemap
     */
    public function tags()
    {
        $tags = Cache::remember('sitemap.tags', 3600, function () {
            return Tag::select(['id', 'slug', 'updated_at'])
                ->whereHas('articles', function ($q) {
                    $q->where('status', 'published');
                })
                ->latest()
                ->get();
        });

        return response()->view('sitemap.tags', compact('tags'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Map page names to routes
     */
    private function getRoute($page)
    {
        $routes = [
            'home' => 'home',
            'blog' => 'blog.index',
            'contact' => 'contact',
            'about' => 'about',
        ];

        return $routes[$page] ?? 'home';
    }
}
