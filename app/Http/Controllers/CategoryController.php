<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $categories = Category::with(['parent'])->paginate(10);
        if (request()->ajax()) {
            return response()->json(['categories' => $categories]);
        }
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = $this->categoryRepository->getParents();
        if (request()->ajax()) {
            return response()->json(['parents' => $parents]);
        }
        return view('admin.categories.create', compact('parents'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $category = $this->categoryRepository->create($validated);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'category' => $category
            ]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        $parents = $this->categoryRepository->where('id', '!=', $category->id)->whereNull('parent_id')->get();
        if (request()->ajax()) {
            return response()->json(['category' => $category, 'parents' => $parents]);
        }
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        if ($request->name !== $category->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $this->categoryRepository->update($category->id, $validated);
        $category->refresh();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'category' => $category
            ]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $this->categoryRepository->delete($category->id);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
