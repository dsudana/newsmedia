<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use App\Models\Category;
use App\Services\ArticleGeneratorService;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    public function index()
    {
        $keywords = Keyword::with('category')
            ->latest()
            ->paginate(15);

        return view('admin.keywords.index', compact('keywords'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.keywords.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'keyword' => 'required|string|unique:keywords',
            'description' => 'nullable|string',
            'intent' => 'required|in:informational,navigational,transactional,commercial',
            'focus_tone' => 'required|string',
            'target_words' => 'required|integer|min:500|max:5000',
            'use_humanizer' => 'boolean',
        ]);

        $keyword = Keyword::create($validated);

        return redirect()->route('admin.keywords.show', $keyword)
            ->with('success', 'Keyword berhasil dibuat. Klik tombol Generate untuk membuat artikel');
    }

    public function show(Keyword $keyword)
    {
        $keyword->load('category', 'articles');
        return view('admin.keywords.show', compact('keyword'));
    }

    public function edit(Keyword $keyword)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.keywords.edit', compact('keyword', 'categories'));
    }

    public function update(Request $request, Keyword $keyword)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'keyword' => 'required|string|unique:keywords,keyword,' . $keyword->id,
            'description' => 'nullable|string',
            'intent' => 'required|in:informational,navigational,transactional,commercial',
            'focus_tone' => 'required|string',
            'target_words' => 'required|integer|min:500|max:5000',
            'use_humanizer' => 'boolean',
        ]);

        $keyword->update($validated);

        return redirect()->route('admin.keywords.show', $keyword)
            ->with('success', 'Keyword berhasil diperbarui');
    }

    public function destroy(Keyword $keyword)
    {
        $keyword->delete();
        return redirect()->route('admin.keywords.index')
            ->with('success', 'Keyword berhasil dihapus');
    }

    public function generate(Keyword $keyword)
    {
        if (!config('services.anthropic.key')) {
            return back()->with('error', 'Anthropic API key belum dikonfigurasi. Hubungi admin untuk setup');
        }

        try {
            $service = new ArticleGeneratorService();
            $article = $service->generate($keyword);

            return redirect()->route('admin.articles.edit', $article)
                ->with('success', 'Artikel berhasil dibuat dari keyword. Silakan review dan publish');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate artikel: ' . $e->getMessage());
        }
    }

    public function filterByStatus(Request $request)
    {
        $status = $request->query('status', 'all');
        $query = Keyword::with('category');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $keywords = $query->latest()->paginate(15);

        return view('admin.keywords.index', compact('keywords', 'status'));
    }
}
