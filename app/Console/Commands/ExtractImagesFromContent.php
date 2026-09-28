<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExtractImagesFromContent extends Command
{
    protected $signature = 'images:extract-from-content';
    protected $description = 'Extract images from article content and assign as featured images';

    public function handle()
    {
        $this->info('Extracting images from article content...');

        $articles = Article::whereNull('featured_image')
            ->orWhere('featured_image', '=', '')
            ->orWhere('featured_image', 'like', '%placeholder%')
            ->get();

        $this->info("Found " . $articles->count() . " articles without proper featured images");

        $updated = 0;
        $skipped = 0;

        foreach ($articles as $article) {
            $imageUrl = $this->extractImageFromContent($article->content);

            if ($imageUrl) {
                $this->line("Processing: {$article->title}");
                $this->line("  Found image in content: {$imageUrl}");

                $imagePath = $this->downloadImage($imageUrl);
                if ($imagePath) {
                    $article->update(['featured_image' => $imagePath]);
                    $this->line("  ✓ Updated with: {$imagePath}");
                    $updated++;
                } else {
                    $this->warn("  ✗ Failed to download image");
                    $skipped++;
                }
            } else {
                $skipped++;
            }
        }

        $this->info("\n=== Summary ===");
        $this->info("Updated: {$updated} articles");
        $this->warn("Skipped: {$skipped} articles");

        return 0;
    }

    private function extractImageFromContent(string $content): ?string
    {
        // Match WordPress figure/img tags with src attribute
        if (preg_match('/<figure[^>]*>.*?<img[^>]*src=["\']([^"\']+)["\'].*?<\/figure>/is', $content, $matches)) {
            return $matches[1];
        }

        // Match standalone img tags
        if (preg_match('/<img[^>]*src=["\']([^"\']+)["\'].*?>/is', $content, $matches)) {
            $src = $matches[1];
            // Make sure it's a valid image URL (not data: URI or relative path)
            if (filter_var($src, FILTER_VALIDATE_URL) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $src)) {
                return $src;
            }
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

            if (empty($filename) || $filename === '/' || strlen($filename) < 3) {
                $filename = 'extracted-' . time() . '.jpg';
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
}
