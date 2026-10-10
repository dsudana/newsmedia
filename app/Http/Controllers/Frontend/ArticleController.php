<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleView;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ArticleController extends Controller
{
    const PER_PAGE = 12;
    const CACHE_TTL = 3600; // 1 hour

    private function getArticleSelect()
    {
        return ['id', 'title', 'slug', 'featured_image', 'category_id', 'user_id', 'published_at', 'views_count', 'excerpt'];
    }

    private function getCachedCategories()
    {
        return Cache::remember('categories_with_counts', self::CACHE_TTL, function () {
            return Category::active()
                ->withCount(['articles' => fn($q) => $q->published()])
                ->select('id', 'name', 'slug', 'is_active', 'order')
                ->orderBy('order')
                ->get();
        });
    }

    private function getCachedTags()
    {
        return Cache::remember('popular_tags', self::CACHE_TTL, function () {
            return Tag::whereHas('articles', fn($q) => $q->published())
                ->withCount('articles')
                ->select('id', 'name', 'slug')
                ->orderByDesc('articles_count')
                ->limit(10)
                ->get();
        });
    }

    public function index(Request $request)
    {
        $query = Article::published()
            ->select($this->getArticleSelect())
            ->with(['category:id,name,slug', 'user:id,name', 'tags:id,name,slug']);

        $category = null;

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('slug', $request->tag));
        }

        $articles = $query->recent()->paginate(self::PER_PAGE);

        $categories = $this->getCachedCategories();

        // Get truly popular articles (by views) - category-aware
        $sidebarArticles = $category
            ? Article::published()
                ->where('category_id', $category->id)
                ->select($this->getArticleSelect())
                ->with(['category:id,name,slug', 'user:id,name'])
                ->orderByDesc('views_count')
                ->take(5)
                ->get()
            : Article::published()
                ->select($this->getArticleSelect())
                ->with(['category:id,name,slug', 'user:id,name'])
                ->orderByDesc('views_count')
                ->take(5)
                ->get();

        $popularTags = $this->getCachedTags();

        $title = 'Semua Artikel';
        if ($request->filled('search')) {
            $title = 'Pencarian: ' . $request->search;
        } elseif ($category) {
            $title = 'Kategori: ' . $category->name;
        }

        return view('blog.index', compact('articles', 'categories', 'sidebarArticles', 'popularTags', 'category', 'title'));
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }

    public function show(Article $article)
    {
        abort_if($article->status !== 'published', 404);

        $article->load(['user', 'category', 'tags', 'meta', 'faqs', 'analytics']);

        // Track article view asynchronously (non-blocking)
        ArticleView::create([
            'article_id' => $article->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'user_id' => auth()->id(),
            'viewed_at' => now(),
        ]);

        $article->increment('views_count');

        // Get related articles (same category)
        $relatedArticles = Article::published()
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->select($this->getArticleSelect())
            ->with(['user:id,name', 'category:id,name,slug'])
            ->recent()
            ->limit(5)
            ->get();

        // Get previous and next articles for navigation
        $previousArticle = Article::published()
            ->where('published_at', '<', $article->published_at)
            ->select(['id', 'title', 'slug', 'published_at'])
            ->latest('published_at')
            ->first();

        $nextArticle = Article::published()
            ->where('published_at', '>', $article->published_at)
            ->select(['id', 'title', 'slug', 'published_at'])
            ->oldest('published_at')
            ->first();

        // Sidebar data
        $categories = $this->getCachedCategories();

        $sidebarArticles = Article::published()
            ->select($this->getArticleSelect())
            ->with(['user:id,name', 'category:id,name,slug'])
            ->recent()
            ->limit(5)
            ->get();

        $popularTags = $this->getCachedTags();

        return view('blog.show', compact(
            'article',
            'relatedArticles',
            'previousArticle',
            'nextArticle',
            'categories',
            'sidebarArticles',
            'popularTags'
        ));
    }

    public function category(Category $category)
    {
        abort_if(!$category->is_active, 404);

        $articles = Article::published()
            ->where('category_id', $category->id)
            ->select($this->getArticleSelect())
            ->with(['user:id,name', 'category:id,name,slug', 'tags:id,name,slug'])
            ->recent()
            ->paginate(self::PER_PAGE);

        $categories = $this->getCachedCategories();

        $sidebarArticles = Article::published()
            ->where('category_id', $category->id)
            ->select($this->getArticleSelect())
            ->with(['category:id,name,slug', 'user:id,name'])
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        $popularTags = $this->getCachedTags();

        $title = $category->name;

        return view('blog.index', compact(
            'articles',
            'categories',
            'sidebarArticles',
            'popularTags',
            'category',
            'title'
        ));
    }

    public function tag(Tag $tag)
    {
        $articles = Article::published()
            ->whereHas('tags', fn($q) => $q->where('tags.id', $tag->id))
            ->select($this->getArticleSelect())
            ->with(['user:id,name', 'category:id,name,slug', 'tags:id,name,slug'])
            ->recent()
            ->paginate(self::PER_PAGE);

        $categories = $this->getCachedCategories();

        $sidebarArticles = Article::published()
            ->whereHas('tags', fn($q) => $q->where('tags.id', $tag->id))
            ->select($this->getArticleSelect())
            ->with(['category:id,name,slug', 'user:id,name'])
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        $popularTags = $this->getCachedTags();

        $title = 'Tag: ' . $tag->name;
        $category = null;

        return view('blog.index', compact(
            'articles',
            'categories',
            'sidebarArticles',
            'popularTags',
            'tag',
            'title',
            'category'
        ));
    }
}
