<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class DownloadWordPressImages extends Command
{
    protected $signature = 'wordpress:download-images {file} {--dry-run}';

    protected $description = 'Download featured images from WordPress XML export and link to articles';

    private $stats = [
        'processed' => 0,
        'downloaded' => 0,
        'failed' => 0,
        'skipped' => 0,
    ];

    private $attachments = [];

    public function handle()
    {
        $filePath = $this->argument('file');
        $dryRun = $this->option('dry-run');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info('Starting WordPress image download...');

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No images will be downloaded');
        }

        try {
            $xml = simplexml_load_file($filePath);
            $xml->registerXPathNamespace('wp', 'http://wordpress.org/export/1.2/');
            $xml->registerXPathNamespace('content', 'http://purl.org/rss/1.0/modules/content/');

            // Map attachment IDs to URLs
            $this->mapAttachments($xml);

            // Download images for articles
            $this->processArticles($xml, $dryRun);

            $this->displayStats();

            if (!$dryRun) {
                $this->info('✅ Image download completed successfully!');
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('Process failed: ' . $e->getMessage());
            return 1;
        }
    }

    private function mapAttachments($xml)
    {
        $this->info('Mapping attachment URLs...');

        $items = $xml->channel->item;

        foreach ($items as $item) {
            $postType = (string)$item->children('http://wordpress.org/export/1.2/')->{'post_type'};

            if ($postType !== 'attachment') {
                continue;
            }

            $attachmentId = (string)$item->children('http://wordpress.org/export/1.2/')->{'post_id'};
            $attachmentUrl = (string)$item->children('http://wordpress.org/export/1.2/')->{'attachment_url'};

            if ($attachmentId && $attachmentUrl) {
                $this->attachments[$attachmentId] = $attachmentUrl;
            }
        }

        $this->line("  Found " . count($this->attachments) . " attachments");
    }

    private function processArticles($xml, $dryRun)
    {
        $this->info('Processing articles...');

        $items = $xml->channel->item;

        foreach ($items as $item) {
            $postType = (string)$item->children('http://wordpress.org/export/1.2/')->{'post_type'};

            if ($postType !== 'post') {
                continue;
            }

            $title = (string)$item->title;
            $this->stats['processed']++;

            try {
                // Get thumbnail ID from post meta
                $thumbnailId = $this->getThumbnailId($item);

                if (!$thumbnailId || !isset($this->attachments[$thumbnailId])) {
                    $this->stats['skipped']++;
                    continue;
                }

                $imageUrl = $this->attachments[$thumbnailId];

                // Find article by title
                $article = Article::where('title', $title)->first();

                if (!$article) {
                    $this->stats['skipped']++;
                    continue;
                }

                if (!$dryRun) {
                    $this->downloadAndSaveImage($article, $imageUrl);
                    $this->line("  ✓ Image: {$title}");
                } else {
                    $this->line("  ✓ Image (dry-run): {$title}");
                }

                $this->stats['downloaded']++;
            } catch (\Exception $e) {
                $this->warn("  ✗ Failed for '{$title}': {$e->getMessage()}");
                $this->stats['failed']++;
            }
        }
    }

    private function getThumbnailId($item)
    {
        // Look for _thumbnail_id in post meta
        $meta = $item->xpath('wp:postmeta');

        foreach ($meta as $m) {
            $key = (string)$m->children('http://wordpress.org/export/1.2/')->{'meta_key'};
            $value = (string)$m->children('http://wordpress.org/export/1.2/')->{'meta_value'};

            if ($key === '_thumbnail_id' && $value) {
                return $value;
            }
        }

        return null;
    }

    private function downloadAndSaveImage($article, $imageUrl)
    {
        try {
            $response = Http::timeout(15)->get($imageUrl);

            if (!$response->successful()) {
                throw new \Exception("HTTP {$response->status()}");
            }

            $filename = basename(parse_url($imageUrl, PHP_URL_PATH));
            $filename = $this->sanitizeFilename($filename);

            if (empty($filename) || !preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $filename)) {
                $ext = $this->getExtensionFromMime($response->header('content-type'));
                $filename = 'article-' . $article->id . '.' . $ext;
            }

            $year = $article->published_at ? $article->published_at->format('Y') : date('Y');
            $month = $article->published_at ? $article->published_at->format('m') : date('m');
            $path = "articles/{$year}/{$month}/{$filename}";

            // Create directory if not exists
            $fullPath = storage_path("app/public/{$path}");
            @mkdir(dirname($fullPath), 0755, true);

            // Save image
            file_put_contents($fullPath, $response->body());

            // Update article
            $article->update(['featured_image' => "/storage/{$path}"]);

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Failed to download image: {$e->getMessage()}");
        }
    }

    private function getExtensionFromMime($mimeType)
    {
        $mimes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];

        return $mimes[$mimeType] ?? 'jpg';
    }

    private function sanitizeFilename($filename)
    {
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '-', $filename);
        $filename = preg_replace('/-+/', '-', $filename);
        return trim($filename, '-');
    }

    private function displayStats()
    {
        $this->newLine();
        $this->info('📊 Image Download Statistics:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Articles Processed', $this->stats['processed']],
                ['Images Downloaded', $this->stats['downloaded']],
                ['Images Skipped', $this->stats['skipped']],
                ['Errors', $this->stats['failed']],
            ]
        );
    }
}
