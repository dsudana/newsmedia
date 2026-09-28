<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class LinkWordPressImages extends Command
{
    protected $signature = 'wordpress:link-images {xmlfile}';
    protected $description = 'Parse WordPress XML and link correct featured images to articles based on _thumbnail_id mapping';

    public function handle()
    {
        $xmlFile = $this->argument('xmlfile');

        if (!file_exists($xmlFile)) {
            $this->error("File not found: {$xmlFile}");
            return 1;
        }

        $this->info("Parsing WordPress XML: {$xmlFile}");

        // Parse XML
        $xml = simplexml_load_file($xmlFile);
        $xml->registerXPathNamespace('wp', 'http://wordpress.org/export/1.2/');

        // Build mapping: attachment post ID => attachment URL
        $attachmentUrls = [];
        $postThumbnails = [];
        $postTitles = [];

        foreach ($xml->channel->item as $item) {
            $postType = (string)$item->children('http://wordpress.org/export/1.2/')->post_type;
            $postId = (int)$item->children('http://wordpress.org/export/1.2/')->post_id;
            $postTitle = (string)$item->title;

            if ($postType === 'attachment') {
                $attachmentUrl = (string)$item->children('http://wordpress.org/export/1.2/')->attachment_url;
                $attachmentUrls[$postId] = $attachmentUrl;
                $this->line("  Attachment {$postId}: {$attachmentUrl}");
            }
        }

        foreach ($xml->channel->item as $item) {
            $postType = (string)$item->children('http://wordpress.org/export/1.2/')->post_type;

            if ($postType === 'post') {
                $postId = (int)$item->children('http://wordpress.org/export/1.2/')->post_id;
                $postTitle = (string)$item->title;
                $postTitles[$postId] = $postTitle;

                $thumbMetas = $item->xpath('.//wp:postmeta[wp:meta_key = "_thumbnail_id"]/wp:meta_value');
                if (!empty($thumbMetas)) {
                    $thumbnailId = (int)$thumbMetas[0];
                    $postThumbnails[$postId] = $thumbnailId;
                    $this->line("  Post {$postId} ({$postTitle}): Thumbnail ID = {$thumbnailId}");
                }
            }
        }

        $this->info("Found " . count($attachmentUrls) . " attachments");
        $this->info("Found " . count($postThumbnails) . " posts with thumbnails");

        // Get all articles from database
        $articles = Article::all();
        $updated = 0;
        $skipped = 0;
        $imageDirs = [
            'articles/2026/07',
            'articles/2026/08',
            'articles/2024/11',
            'articles/2024/12',
        ];

        foreach ($articles as $article) {
            $matchedUrl = $this->findMatchingImageUrl($article->title, $postTitles, $postThumbnails, $attachmentUrls);

            if ($matchedUrl) {
                $this->info("\nProcessing: {$article->title}");
                $this->line("  Image URL: {$matchedUrl}");

                $imagePath = $this->downloadImage($matchedUrl);
                if ($imagePath) {
                    $article->update(['featured_image' => $imagePath]);
                    $this->line("  ✓ Updated with: {$imagePath}");
                    $updated++;
                } else {
                    $this->warn("  ✗ Failed to download");
                    $skipped++;
                }
            } else {
                $skipped++;
            }
        }

        $this->info("\n=== Summary ===");
        $this->info("Updated: {$updated} articles");
        $this->warn("Skipped: {$skipped} articles (no matching images found)");

        return 0;
    }

    private function findMatchingImageUrl($articleTitle, $postTitles, $postThumbnails, $attachmentUrls)
    {
        // Try to find exact or close title match
        foreach ($postTitles as $postId => $postTitle) {
            if ($this->titleMatches($articleTitle, $postTitle)) {
                if (isset($postThumbnails[$postId])) {
                    $thumbId = $postThumbnails[$postId];
                    if (isset($attachmentUrls[$thumbId])) {
                        return $attachmentUrls[$thumbId];
                    }
                }
            }
        }

        return null;
    }

    private function titleMatches($title1, $title2)
    {
        $t1 = strtolower(trim($title1));
        $t2 = strtolower(trim($title2));

        // Exact match
        if ($t1 === $t2) {
            return true;
        }

        // Substring match (at least 30% overlap)
        $len1 = strlen($t1);
        $len2 = strlen($t2);
        $shorter = min($len1, $len2);

        if ($shorter < 10) {
            return false;
        }

        // Check if one contains significant portion of the other
        if (stripos($t1, $t2) !== false || stripos($t2, $t1) !== false) {
            return true;
        }

        // Levenshtein distance for fuzzy matching
        $distance = levenshtein($t1, $t2);
        $maxLen = max($len1, $len2);
        $similarity = 1 - ($distance / $maxLen);

        return $similarity > 0.7;
    }

    private function downloadImage($imageUrl)
    {
        try {
            if (empty($imageUrl)) {
                return null;
            }

            // Download image with timeout and SSL verification disabled
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
                $this->warn("  Warning: Downloaded content seems too small");
                return null;
            }

            // Get filename from URL
            $pathInfo = parse_url($imageUrl, PHP_URL_PATH);
            $filename = basename($pathInfo);

            // Sanitize filename
            if (empty($filename) || $filename === '/') {
                $filename = 'image-' . time() . '.jpg';
            }

            // Determine storage directory based on current date
            $year = now()->format('Y');
            $month = now()->format('m');
            $storagePath = "articles/{$year}/{$month}";

            // Ensure directory exists
            Storage::disk('public')->makeDirectory($storagePath, 0755, true);

            // Store image
            $relativePath = "{$storagePath}/{$filename}";
            Storage::disk('public')->put($relativePath, $imageContent);

            return $relativePath;
        } catch (\Exception $e) {
            $this->warn("  Error: " . $e->getMessage());
            return null;
        }
    }
}
