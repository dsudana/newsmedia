<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BackfillArticleImages extends Command
{
    protected $signature = 'articles:backfill-images {--dry-run}';
    protected $description = 'Extract and backfill featured images from article content';

    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('Running in DRY-RUN mode (no changes will be made)');
        }

        $articles = Article::whereNull('featured_image')
            ->orWhere('featured_image', '')
            ->get();

        $this->info("Found " . count($articles) . " articles without featured images");

        $processed = 0;
        $updated = 0;
        $failed = 0;

        foreach ($articles as $article) {
            $processed++;
            $this->line("[$processed/" . count($articles) . "] Processing: {$article->title}");

            $imageUrl = $this->extractImageFromContent($article->content);

            if (!$imageUrl) {
                $this->warn("  ✗ No image found in content");
                $failed++;
                continue;
            }

            if ($dryRun) {
                $this->info("  → Would extract: $imageUrl");
                continue;
            }

            $imagePath = $this->downloadImage($imageUrl);
            if ($imagePath) {
                $article->featured_image = $imagePath;
                $article->save();
                $this->info("  ✓ Image saved: {$imagePath}");
                $updated++;
            } else {
                $this->warn("  ✗ Failed to download image");
                $failed++;
            }
        }

        $this->info("\n=== Summary ===");
        $this->info("Processed: {$processed}");
        $this->info("Updated: {$updated}");
        $this->warn("Failed/No image: {$failed}");

        if ($dryRun) {
            $this->line("\nTo apply changes, run: php artisan articles:backfill-images");
        }

        return 0;
    }

    private function extractImageFromContent(string $content): ?string
    {
        if (empty($content)) {
            return null;
        }

        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $content, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function downloadImage(string $imageUrl): ?string
    {
        try {
            if (empty($imageUrl)) {
                return null;
            }

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

            $pathInfo = parse_url($imageUrl, PHP_URL_PATH);
            $filename = basename($pathInfo);

            if (empty($filename) || $filename === '/') {
                $ext = $this->getImageExtension($imageContent);
                $filename = 'image-' . time() . $ext;
            }

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

    private function getImageExtension(string $imageContent): string
    {
        $mimeType = mime_content_type('data://application/octet-stream;base64,' . base64_encode(substr($imageContent, 0, 12)));

        $mimeMap = [
            'image/jpeg' => '.jpg',
            'image/png' => '.png',
            'image/gif' => '.gif',
            'image/webp' => '.webp',
        ];

        return $mimeMap[$mimeType] ?? '.jpg';
    }
}
