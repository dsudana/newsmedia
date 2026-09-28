<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AssignDummyImages extends Command
{
    protected $signature = 'images:assign-dummy {--count=20}';
    protected $description = 'Assign dummy images to articles without featured images';

    public function handle()
    {
        // List of dummy image URLs from picsum.photos
        $dummyImages = [
            'https://picsum.photos/800/600?random=1',
            'https://picsum.photos/800/600?random=2',
            'https://picsum.photos/800/600?random=3',
            'https://picsum.photos/800/600?random=4',
            'https://picsum.photos/800/600?random=5',
            'https://picsum.photos/800/600?random=6',
            'https://picsum.photos/800/600?random=7',
            'https://picsum.photos/800/600?random=8',
            'https://picsum.photos/800/600?random=9',
            'https://picsum.photos/800/600?random=10',
        ];

        $count = (int) $this->option('count');
        $updated = 0;

        // Get articles without featured images, excluding those with placeholder
        $articles = Article::published()
            ->whereNull('featured_image')
            ->orWhereLike('featured_image', '%placeholder%')
            ->limit($count)
            ->get();

        $this->info("Found {$articles->count()} articles without images. Starting download...");

        foreach ($articles as $key => $article) {
            try {
                // Select random image from list
                $imageUrl = $dummyImages[$key % count($dummyImages)];

                // Download image
                $imageContent = file_get_contents($imageUrl);

                if ($imageContent === false) {
                    $this->warn("Failed to download image for: {$article->title}");
                    continue;
                }

                // Create storage path
                $year = $article->published_at?->format('Y') ?? now()->format('Y');
                $month = $article->published_at?->format('m') ?? now()->format('m');
                $storagePath = "articles/{$year}/{$month}";

                // Ensure directory exists
                Storage::disk('public')->makeDirectory($storagePath, 0755, true);

                // Generate filename
                $filename = "dummy-{$article->id}-" . time() . ".jpg";
                $path = "{$storagePath}/{$filename}";

                // Store image
                Storage::disk('public')->put($path, $imageContent);

                // Update article
                $article->update(['featured_image' => $path]);
                $updated++;

                $this->line("<info>✓</info> {$article->title} - {$path}");

            } catch (\Exception $e) {
                $this->warn("Error processing {$article->title}: " . $e->getMessage());
                continue;
            }

            // Add small delay to avoid rate limiting
            usleep(500000); // 0.5 second
        }

        $this->info("\n✓ Successfully updated {$updated} articles with dummy images!");
    }
}
