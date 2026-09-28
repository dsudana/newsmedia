<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Article;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('articles')->get();
        return view('frontend.categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $articles = Article::with(['category', 'user'])
            ->where('status', 'published')
            ->where('category_id', $category->id)
            ->latest()
            ->paginate(12);

        $title = 'Category: ' . $category->name;

        // We can reuse the articles.index view but we need to trick it to show category header?
        // The index view checks `request('category')`. 
        // We can merge request?
        request()->merge(['category' => $category->name]);

        return view('frontend.articles.index', compact('articles', 'title'));
    }
}
