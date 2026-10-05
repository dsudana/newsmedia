<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Category;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::active()->orderBy('name')->get();

        $videos = Video::published()
            ->when($request->category_id,
                fn($q) => $q->where('category_id', $request->category_id)
            )
            ->when($request->sort === 'popular',
                fn($q) => $q->orderBy('views_count', 'desc')
            )
            ->when($request->sort === 'oldest',
                fn($q) => $q->orderBy('published_at', 'asc')
            )
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('gallery.index', compact('videos', 'categories'));
    }

    public function category(Category $category, Request $request)
    {
        $categories = Category::active()->orderBy('name')->get();

        $videos = Video::published()
            ->where('category_id', $category->id)
            ->when($request->sort === 'popular',
                fn($q) => $q->orderBy('views_count', 'desc')
            )
            ->when($request->sort === 'oldest',
                fn($q) => $q->orderBy('published_at', 'asc')
            )
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('gallery.index', compact('videos', 'categories', 'category'));
    }
}
