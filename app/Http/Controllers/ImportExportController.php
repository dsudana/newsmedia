<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportExportController extends Controller
{
    /**
     * Show import/export page
     */
    public function index()
    {
        $articlesCount = Article::count();
        return view('admin.import-export.index', compact('articlesCount'));
    }

    /**
     * Export articles to CSV with error handling and logging
     */
    public function export()
    {
        try {
            $articles = Article::with(['category', 'user'])->get();

            if ($articles->isEmpty()) {
                return redirect()->route('admin.import-export.index')
                    ->with('error', 'Tidak ada artikel untuk diexport');
            }

            $filename = 'articles_' . now()->format('Y-m-d_His') . '.csv';

            \Log::info('Article export started', [
                'count' => $articles->count(),
                'user' => auth()->user()?->name ?? 'Unknown',
            ]);

            return response()->stream(function () use ($articles) {
                $handle = fopen('php://output', 'w');

                if (!$handle) {
                    throw new \Exception('Gagal membuka output stream');
                }

                // Header row
                fputcsv($handle, [
                    'ID',
                    'Title',
                    'Slug',
                    'Content',
                    'Excerpt',
                    'Category',
                    'Author',
                    'Featured Image',
                    'Published At',
                    'Status',
                ]);

                // Data rows
                foreach ($articles as $article) {
                    fputcsv($handle, [
                        $article->id,
                        $article->title,
                        $article->slug,
                        $article->content,
                        $article->excerpt,
                        $article->category?->name,
                        $article->user?->name,
                        $article->featured_image,
                        $article->published_at?->format('Y-m-d H:i:s'),
                        $article->status,
                    ]);
                }

                fclose($handle);
            }, 200, [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ]);
        } catch (\Exception $e) {
            \Log::error('Article export failed', [
                'error' => $e->getMessage(),
                'user' => auth()->user()?->name ?? 'Unknown',
            ]);

            return redirect()->route('admin.import-export.index')
                ->with('error', 'Error saat export: ' . $e->getMessage());
        }
    }

    /**
     * Show import form
     */
    public function showImportForm()
    {
        return view('admin.import-export.import');
    }

    /**
     * Process import from CSV with validation and transactions
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
            'skip_duplicates' => 'boolean',
        ]);

        $file = $request->file('file');
        $skipDuplicates = $request->boolean('skip_duplicates', true);

        $results = [
            'imported' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        try {
            // Validate file is readable
            if (!is_readable($file->getRealPath())) {
                throw new \Exception('File tidak dapat dibaca');
            }

            $handle = fopen($file->getRealPath(), 'r');
            if (!$handle) {
                throw new \Exception('Gagal membuka file');
            }

            $headers = fgetcsv($handle);
            if (empty($headers)) {
                throw new \Exception('File CSV kosong atau invalid');
            }

            // Validate required headers
            $requiredHeaders = ['Title', 'Slug', 'Content'];
            $missingHeaders = array_diff($requiredHeaders, $headers);
            if (!empty($missingHeaders)) {
                throw new \Exception('Kolom yang diperlukan tidak ditemukan: ' . implode(', ', $missingHeaders));
            }

            $rowNum = 1;
            $articlesToCreate = [];

            // Read all rows first for validation
            while (($row = fgetcsv($handle)) !== false) {
                $rowNum++;

                try {
                    if (count($row) < count($headers)) {
                        $results['errors'][] = "Row $rowNum: Jumlah kolom tidak sesuai";
                        continue;
                    }

                    $data = array_combine($headers, $row);

                    // Validate required fields
                    if (empty($data['Title']) || empty($data['Slug']) || empty($data['Content'])) {
                        $results['errors'][] = "Row $rowNum: Title, Slug, dan Content wajib diisi";
                        continue;
                    }

                    // Validate slug format
                    if (!preg_match('/^[a-z0-9\-]+$/', $data['Slug'])) {
                        $results['errors'][] = "Row $rowNum: Slug hanya boleh berisi huruf, angka, dan tanda hubung";
                        continue;
                    }

                    // Validate URL format for featured image if provided
                    if (!empty($data['Featured Image'])) {
                        if (!filter_var($data['Featured Image'], FILTER_VALIDATE_URL)) {
                            $results['errors'][] = "Row $rowNum: Featured Image URL tidak valid";
                            continue;
                        }
                    }

                    // Validate date format if provided
                    if (!empty($data['Published At'])) {
                        try {
                            $publishedAt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data['Published At']);
                        } catch (\Exception $e) {
                            $results['errors'][] = "Row $rowNum: Format Published At harus YYYY-MM-DD HH:mm:ss";
                            continue;
                        }
                    }

                    // Check for duplicate
                    if ($skipDuplicates && Article::where('slug', trim($data['Slug']))->exists()) {
                        $results['skipped']++;
                        continue;
                    }

                    // Sanitize data
                    $allowedTags = '<b><i><u><strong><em>';
                    $articlesToCreate[] = [
                        'row' => $rowNum,
                        'data' => [
                            'title' => strip_tags(trim($data['Title']), $allowedTags),
                            'slug' => Str::slug(trim($data['Slug'])),
                            'content' => trim($data['Content']),
                            'excerpt' => strip_tags(trim($data['Excerpt'] ?? '')),
                            'category_name' => trim($data['Category'] ?? ''),
                            'author_name' => trim($data['Author'] ?? ''),
                            'featured_image' => trim($data['Featured Image'] ?? ''),
                            'published_at' => !empty($data['Published At']) ? $data['Published At'] : now(),
                            'status' => in_array(trim($data['Status'] ?? 'published'), ['published', 'draft', 'scheduled'])
                                ? trim($data['Status'])
                                : 'published',
                        ]
                    ];
                } catch (\Exception $e) {
                    $results['errors'][] = "Row $rowNum: " . $e->getMessage();
                }
            }
            fclose($handle);

            // Use transaction to ensure data consistency
            DB::transaction(function () use ($articlesToCreate, &$results) {
                foreach ($articlesToCreate as $item) {
                    try {
                        $itemData = $item['data'];

                        // Find or create category
                        $category = null;
                        if (!empty($itemData['category_name'])) {
                            $category = Category::firstOrCreate(
                                ['name' => $itemData['category_name']],
                                ['slug' => Str::slug($itemData['category_name'])]
                            );
                        }

                        // Find user by name or use default
                        $user = !empty($itemData['author_name'])
                            ? User::where('name', $itemData['author_name'])->first()
                            : null;

                        if (!$user) {
                            $user = auth()->user();

                            if (!$user) {
                                // No authenticated user - require an explicit admin
                                // user for import instead of creating one with
                                // hardcoded credentials.
                                throw new \Exception('Authentication required. Please log in first.');
                            }
                        }

                        // Create or update article
                        Article::updateOrCreate(
                            ['slug' => $itemData['slug']],
                            [
                                'title' => $itemData['title'],
                                'content' => $itemData['content'],
                                'excerpt' => $itemData['excerpt'],
                                'category_id' => $category?->id,
                                'user_id' => $user->id,
                                'featured_image' => $itemData['featured_image'],
                                'published_at' => $itemData['published_at'],
                                'status' => $itemData['status'],
                            ]
                        );

                        $results['imported']++;
                    } catch (\Exception $e) {
                        $results['errors'][] = "Row {$item['row']}: " . $e->getMessage();
                        throw $e; // Rollback transaction on error
                    }
                }
            });

            \Log::info('Article import completed', [
                'imported' => $results['imported'],
                'skipped' => $results['skipped'],
                'errors' => count($results['errors']),
            ]);

            return redirect()->route('admin.import-export.index')
                ->with('success', "Import berhasil! {$results['imported']} artikel diimpor, {$results['skipped']} dilewati.")
                ->with('errors', $results['errors']);
        } catch (\Exception $e) {
            \Log::error('Article import failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
            ]);

            return redirect()->route('admin.import-export.index')
                ->with('error', 'Error saat mengimpor file: ' . $e->getMessage());
        }
    }

    /**
     * Download template CSV with error handling
     */
    public function downloadTemplate()
    {
        try {
            $filename = 'articles_template_' . now()->format('Y-m-d_His') . '.csv';

            \Log::info('Template download', [
                'user' => auth()->user()?->name ?? 'Unknown',
            ]);

            return response()->stream(function () {
                $handle = fopen('php://output', 'w');

                if (!$handle) {
                    throw new \Exception('Gagal membuka output stream');
                }

                fputcsv($handle, [
                    'ID',
                    'Title',
                    'Slug',
                    'Content',
                    'Excerpt',
                    'Category',
                    'Author',
                    'Featured Image',
                    'Published At',
                    'Status',
                ]);

                // Example row
                fputcsv($handle, [
                    '',
                    'Contoh Artikel Baru',
                    'contoh-artikel-baru',
                    'Isi artikel di sini... Bisa termasuk HTML seperti <b>bold</b> dan <i>italic</i>',
                    'Ringkasan singkat artikel',
                    'News',
                    'Admin User',
                    'https://example.com/image.jpg',
                    now()->format('Y-m-d H:i:s'),
                    'published',
                ]);

                // Second example
                fputcsv($handle, [
                    '',
                    'Artikel Lainnya',
                    'artikel-lainnya',
                    'Konten artikel di sini...',
                    'Deskripsi singkat',
                    'Technology',
                    'Editor User',
                    'https://example.com/image2.jpg',
                    now()->format('Y-m-d H:i:s'),
                    'draft',
                ]);

                fclose($handle);
            }, 200, [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ]);
        } catch (\Exception $e) {
            \Log::error('Template download failed', [
                'error' => $e->getMessage(),
                'user' => auth()->user()?->name ?? 'Unknown',
            ]);

            return redirect()->route('admin.import-export.index')
                ->with('error', 'Error saat download template: ' . $e->getMessage());
        }
    }
}
