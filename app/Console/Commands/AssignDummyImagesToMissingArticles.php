<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AssignDummyImagesToMissingArticles extends Command
{
    protected $signature = 'images:assign-missing-articles {--count=20}';
    protected $description = 'Assign unique dummy images from picsum.photos to articles without featured images';

    public function handle()
    {
        $limit = (int)$this->option('count');

        $articles = Article::where(function($q) {
            $q->whereNull('featured_image')
              ->orWhere('featured_image', '=', '')
              ->orWhere('featured_image', 'like', '%Group-14.jpg%');
        })
        ->orderBy('id')
        ->limit($limit)
        ->get();

        $this->info("Found " . $articles->count() . " articles needing images");

        $updated = 0;
        $failed = 0;

        foreach ($articles as $key => $article) {
            $imageId = 1000 + $key;
            $imageUrl = "https://picsum.photos/800/600?random={$imageId}";

            $this->line("Processing: {$article->title}");

            $imagePath = $this->downloadImage($imageUrl, $key);
            if ($imagePath) {
                $article->update(['featured_image' => $imagePath]);
                $this->line("  ✓ Updated with: {$imagePath}");
                $updated++;
            } else {
                $this->warn("  ✗ Failed to download");
                $failed++;
            }
        }

        $this->info("\n=== Summary ===");
        $this->info("Updated: {$updated} articles");
        $this->warn("Failed: {$failed} articles");

        return 0;
    }

    private function downloadImage(string $imageUrl, int $index): ?string
    {
        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 15,
                    'follow_location' => true,
                    'max_redirects' => 5,
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);

            $imageContent = @file_get_contents($imageUrl, false, $context);

            if (!$imageContent || strlen($imageContent) < 100) {
                return null;
            }

            $filename = "placeholder-" . $index . ".jpg";

            $year = now()->format('Y');
            $month = now()->format('m');
            $storagePath = "articles/{$year}/{$month}";

            Storage::disk('public')->makeDirectory($storagePath, 0755, true);

            $relativePath = "{$storagePath}/{$filename}";
            Storage::disk('public')->put($relativePath, $imageContent);

            return $relativePath;
        } catch (\Exception) {
            return null;
        }
    }
}
