<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Get articles list (JSON API)
     */
    public function index(Request $request)
    {
        $query = Article::query();

        // Filter by status
        if ($request->has('status') && $request->status === 'published') {
            $query->where('status', 'published');
        }

        // Filter by featured image
        if ($request->has('featured_image') && $request->featured_image == 1) {
            $query->whereNotNull('featured_image')
                  ->where('featured_image', '!=', '');
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Sort by
        $sortBy = $request->input('sort_by', 'latest');
        switch ($sortBy) {
            case 'popular':
            case 'views':
                $query->orderBy('views_count', 'desc');
                break;
            case 'oldest':
                $query->oldest('published_at');
                break;
            case 'latest':
            default:
                $query->latest('published_at');
        }

        // Limit
        $limit = min($request->input('limit', 100), 100);
        $articles = $query->with(['category', 'user'])
                         ->limit($limit)
                         ->get()
                         ->map(function($article) {
                             return [
                                 'id' => $article->id,
                                 'title' => $article->title,
                                 'slug' => $article->slug,
                                 'featured_image' => $article->featured_image,
                                 'published_at' => $article->published_at?->toIso8601String(),
                                 'category' => $article->category ? [
                                     'id' => $article->category->id,
                                     'name' => $article->category->name,
                                     'slug' => $article->category->slug,
                                 ] : null,
                                 'user' => $article->user ? [
                                     'id' => $article->user->id,
                                     'name' => $article->user->name,
                                 ] : null,
                             ];
                         });

        return response()->json([
            'success' => true,
            'articles' => $articles,
            'count' => $articles->count(),
        ]);
    }
}
