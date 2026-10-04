<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleView;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    const PER_PAGE = 12;

    public function index(Request $request)
    {
        $query = Article::published()
            ->with(['category', 'user', 'tags']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('slug', $request->tag));
        }

        $articles = $query->recent()->paginate(self::PER_PAGE);

        $categories = Category::active()
            ->withCount('articles')
            ->orderBy('order')
            ->get();

        $popularTags = Tag::whereHas('articles', fn($q) => $q->published())
            ->withCount('articles')
            ->orderByDesc('articles_count')
            ->limit(10)
            ->get();

        $title = 'Semua Artikel';
        if ($request->filled('search')) {
            $title = 'Pencarian: ' . $request->search;
        } elseif ($request->filled('category')) {
            $title = 'Kategori: ' . $request->category;
        }

        return view('blog.index', compact('articles', 'categories', 'popularTags', 'title'));
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }

    public function show(Article $article)
    {
        abort_if($article->status !== 'published', 404);

        $article->load(['user', 'category', 'tags', 'meta', 'faqs', 'analytics']);

        // Record article view
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
            ->recent()
            ->with(['user', 'category'])
            ->limit(5)
            ->get();

        // Get previous and next articles for navigation
        $previousArticle = Article::published()
            ->where('published_at', '<', $article->published_at)
            ->latest('published_at')
            ->first();

        $nextArticle = Article::published()
            ->where('published_at', '>', $article->published_at)
            ->oldest('published_at')
            ->first();

        // Sidebar data
        $categories = Category::active()
            ->withCount('articles')
            ->orderBy('order')
            ->get();

        $recentArticles = Article::published()
            ->recent()
            ->with(['user', 'category'])
            ->limit(5)
            ->get();

        $popularTags = Tag::whereHas('articles', fn($q) => $q->published())
            ->withCount('articles')
            ->orderByDesc('articles_count')
            ->limit(10)
            ->get();

        return view('blog.show', compact(
            'article',
            'relatedArticles',
            'previousArticle',
            'nextArticle',
            'categories',
            'recentArticles',
            'popularTags'
        ));
    }

    public function category(Category $category)
    {
        abort_if(!$category->is_active, 404);

        $articles = Article::published()
            ->where('category_id', $category->id)
            ->recent()
            ->with(['user', 'category', 'tags'])
            ->paginate(self::PER_PAGE);

        $categories = Category::active()
            ->withCount('articles')
            ->orderBy('order')
            ->get();

        $popularTags = Tag::whereHas('articles', fn($q) => $q->published())
            ->withCount('articles')
            ->orderByDesc('articles_count')
            ->limit(10)
            ->get();

        $title = $category->name;

        return view('blog.index', compact(
            'articles',
            'categories',
            'popularTags',
            'category',
            'title'
        ));
    }

    public function tag(Tag $tag)
    {
        $articles = Article::published()
            ->whereHas('tags', fn($q) => $q->where('id', $tag->id))
            ->recent()
            ->with(['user', 'category', 'tags'])
            ->paginate(self::PER_PAGE);

        $categories = Category::active()
            ->withCount('articles')
            ->orderBy('order')
            ->get();

        $popularTags = Tag::whereHas('articles', fn($q) => $q->published())
            ->withCount('articles')
            ->orderByDesc('articles_count')
            ->limit(10)
            ->get();

        return view('blog.index', compact(
            'articles',
            'categories',
            'popularTags',
            'tag'
        ));
    }
}
