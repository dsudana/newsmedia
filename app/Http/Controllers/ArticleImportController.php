<?php

namespace App\Http\Controllers;

use App\Services\ArticleImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ArticleImportController extends Controller
{
    protected $importService;

    public function __construct(ArticleImportService $importService)
    {
        $this->importService = $importService;
        $this->middleware('auth');
    }

    /**
     * Show import form
     */
    public function create()
    {
        return view('admin.articles.import', [
            'categories' => \App\Models\Category::where('is_active', true)->get(),
        ]);
    }

    /**
     * Import articles from XML
     *
     * POST /admin/articles/import
     *
     * Body:
     * {
     *   "xml_content": "...WordPress XML...",
     *   "category_id": 1,
     *   "download_images": true
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'xml_content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'download_images' => 'boolean',
        ], [
            'xml_content.required' => 'XML content is required',
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Selected category does not exist',
        ]);

        try {
            // Parse XML
            $xml = $this->importService->parseXML($validated['xml_content']);

            // Extract articles
            $articles = $this->importService->extractArticles($xml);

            if (empty($articles)) {
                return redirect()->back()
                    ->with('error', 'No articles found in the provided XML');
            }

            // Prepare category mapping
            $categoryMapping = [
                'default' => $validated['category_id'],
            ];

            // Import articles
            $results = $this->importService->importBatch(
                $articles,
                Auth::id(),
                $categoryMapping,
                [], // keywordMapping
                $validated['download_images'] ?? false
            );

            // Log results
            Log::info('Article import completed', $results);

            // Return results
            return redirect()->route('admin.articles.index')
                ->with('success', "Import completed! {$results['imported']} articles imported, {$results['failed']} failed.")
                ->with('import_results', $results);

        } catch (\Exception $e) {
            Log::error('Import failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Import failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Upload and import XML file
     *
     * POST /admin/articles/import-file
     */
    public function importFile(Request $request)
    {
        $validated = $request->validate([
            'xml_file' => 'required|file|mimes:xml',
            'category_id' => 'required|exists:categories,id',
            'download_images' => 'boolean',
        ], [
            'xml_file.required' => 'XML file is required',
            'xml_file.mimes' => 'File must be an XML file',
            'category_id.required' => 'Category is required',
        ]);

        try {
            // Read file
            $xmlContent = file_get_contents($validated['xml_file']);

            // Parse XML
            $xml = $this->importService->parseXML($xmlContent);

            // Extract articles
            $articles = $this->importService->extractArticles($xml);

            if (empty($articles)) {
                return back()->with('error', 'No articles found in the provided XML file');
            }

            // Category mapping
            $categoryMapping = [
                'default' => $validated['category_id'],
            ];

            // Import articles
            $results = $this->importService->importBatch(
                $articles,
                Auth::id(),
                $categoryMapping,
                [],
                $validated['download_images'] ?? false
            );

            Log::info('File import completed', $results);

            return redirect()->route('admin.articles.index')
                ->with('success', "File import completed! {$results['imported']} articles imported, {$results['failed']} failed.")
                ->with('import_results', $results);

        } catch (\Exception $e) {
            Log::error('File import failed: ' . $e->getMessage());

            return back()
                ->with('error', 'File import failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * API endpoint for programmatic import
     *
     * POST /api/articles/import
     */
    public function apiImport(Request $request)
    {
        $validated = $request->validate([
            'xml_content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'download_images' => 'boolean',
        ]);

        try {
            // Parse XML
            $xml = $this->importService->parseXML($validated['xml_content']);

            // Extract articles
            $articles = $this->importService->extractArticles($xml);

            if (empty($articles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No articles found in XML',
                ], 400);
            }

            // Category mapping
            $categoryMapping = [
                'default' => $validated['category_id'],
            ];

            // Import articles
            $results = $this->importService->importBatch(
                $articles,
                Auth::id(),
                $categoryMapping,
                [],
                $validated['download_images'] ?? false
            );

            return response()->json([
                'success' => true,
                'message' => 'Import completed',
                'results' => $results,
            ]);

        } catch (\Exception $e) {
            Log::error('API import failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
