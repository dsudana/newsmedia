<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\AffiliateLink;
use App\Models\Keyword;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\TagRepository;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    protected $articleRepository;
    protected $categoryRepository;
    protected $tagRepository;

    public function __construct(
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'category' => $request->input('category'),
        ];

        $articles = $this->articleRepository->getFiltered($filters, 10);

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = $this->categoryRepository->getActive();
        $tags = $this->tagRepository->all([], ['*']);
        $keywords = Keyword::where('status', 'done')->orderBy('keyword')->get();
        $affiliateLinks = AffiliateLink::where('is_active', true)->orderBy('name')->get();
        return view('admin.articles.create', compact('categories', 'tags', 'keywords', 'affiliateLinks'));
    }

    public function store(StoreArticleRequest $request)
    {
        $validated = $request->validated();

        $validated['user_id'] = Auth::id();
        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('articles', 'public');
        }

        $metaData = [
            'meta_title' => $validated['meta_title'],
            'meta_description' => $validated['meta_description'],
            'focus_keyword' => $validated['focus_keyword'] ?? null,
        ];
        unset($validated['meta_title'], $validated['meta_description'], $validated['focus_keyword']);

        $tagIds = $request->input('tags', []);
        $keywordIds = $request->input('keywords', []);
        $affiliateLinkIds = $request->input('affiliate_links', []);

        $article = $this->articleRepository->createWithRelations(
            $validated,
            $metaData,
            $tagIds,
            $keywordIds
        );

        if ($affiliateLinkIds) {
            $article->affiliateLinks()->attach($affiliateLinkIds);
        }

        return redirect()->route('admin.articles.edit', $article)
            ->with('success', 'Artikel berhasil dibuat. Tambahkan FAQ jika diperlukan.');
    }

    public function show(Article $article)
    {
        // Stub for show
    }

    public function edit(Article $article)
    {
        $article->load('meta', 'tags', 'keywords', 'faqs', 'affiliateLinks');
        $categories = $this->categoryRepository->getActive();
        $tags = $this->tagRepository->all([], ['*']);
        $keywords = Keyword::where('status', 'done')->orderBy('keyword')->get();
        $affiliateLinks = AffiliateLink::where('is_active', true)->orderBy('name')->get();

        return view('admin.articles.edit', compact('article', 'categories', 'tags', 'keywords', 'affiliateLinks'));
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        $validated = $request->validated();

        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('featured_image')) {
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('articles', 'public');
        }

        $metaData = [
            'meta_title' => $validated['meta_title'],
            'meta_description' => $validated['meta_description'],
            'focus_keyword' => $validated['focus_keyword'] ?? null,
        ];
        $faqs = $validated['faqs'] ?? [];
        $tagIds = $request->input('tags', []);
        $keywordIds = $request->input('keywords', []);
        $affiliateLinkIds = $request->input('affiliate_links', []);

        unset($validated['meta_title'], $validated['meta_description'], $validated['focus_keyword'], $validated['faqs']);

        $article = $this->articleRepository->updateWithRelations(
            $article->id,
            $validated,
            $metaData,
            $tagIds,
            $keywordIds,
            $faqs
        );

        $article->affiliateLinks()->sync($affiliateLinkIds);

        return redirect()->route('admin.articles.edit', $article)
            ->with('success', 'Artikel berhasil diperbarui');
    }

    public function destroy(Article $article)
    {
        $this->articleRepository->delete($article->id);
        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus ke trash');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids) || !$action) {
            return redirect()->route('admin.articles.index')
                ->with('error', 'Pilih artikel dan aksi terlebih dahulu');
        }

        switch ($action) {
            case 'publish':
                $this->articleRepository->bulkUpdate($ids, [
                    'status' => 'published',
                    'published_at' => now(),
                ]);
                return redirect()->route('admin.articles.index')
                    ->with('success', count($ids) . ' artikel berhasil dipublikasikan');

            case 'draft':
                $this->articleRepository->bulkUpdate($ids, ['status' => 'draft']);
                return redirect()->route('admin.articles.index')
                    ->with('success', count($ids) . ' artikel berhasil disimpan sebagai draft');

            case 'delete':
                $this->articleRepository->bulkDelete($ids);
                return redirect()->route('admin.articles.index')
                    ->with('success', count($ids) . ' artikel berhasil dihapus');

            default:
                return redirect()->route('admin.articles.index')
                    ->with('error', 'Aksi tidak dikenali');
        }
    }
}
