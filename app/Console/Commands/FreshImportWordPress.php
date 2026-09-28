<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FreshImportWordPress extends Command
{
    protected $signature = 'wordpress:fresh-import {xmlfile}';
    protected $description = 'Fresh import: Delete all articles and re-import from WordPress XML with proper images';

    public function handle()
    {
        $xmlFile = $this->argument('xmlfile');

        if (!file_exists($xmlFile)) {
            $this->error("File not found: {$xmlFile}");
            return 1;
        }

        // Confirm deletion
        if (!$this->confirm('This will DELETE all existing articles. Continue?')) {
            $this->warn('Import cancelled');
            return 1;
        }

        $this->info('Deleting all existing articles...');
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Article::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->info('✓ All articles deleted');

        $this->info("\nParsing WordPress XML: {$xmlFile}");

        $xml = simplexml_load_file($xmlFile);
        $xml->registerXPathNamespace('wp', 'http://wordpress.org/export/1.2/');
        $xml->registerXPathNamespace('content', 'http://purl.org/rss/1.0/modules/content/');

        // Extract all data in first pass
        $attachmentUrls = [];
        $categoryData = [];
        $tagData = [];
        $postData = [];

        // Pass 1: Extract attachments
        foreach ($xml->channel->item as $item) {
            $postType = (string)$item->children('http://wordpress.org/export/1.2/')->post_type;

            if ($postType === 'attachment') {
                $postId = (int)$item->children('http://wordpress.org/export/1.2/')->post_id;
                $attachmentUrl = (string)$item->children('http://wordpress.org/export/1.2/')->attachment_url;
                $attachmentUrls[$postId] = $attachmentUrl;
            }
        }

        $this->info("Found " . count($attachmentUrls) . " attachments");

        // Pass 2: Extract categories and tags
        foreach ($xml->channel->item as $item) {
            foreach ($item->category as $cat) {
                $domain = (string)$cat['domain'];
                $niceName = (string)$cat['nicename'];
                $name = (string)$cat;

                if ($domain === 'category' && !isset($categoryData[$niceName])) {
                    $categoryData[$niceName] = [
                        'name' => $name,
                        'slug' => $niceName,
                    ];
                }

                if ($domain === 'post_tag' && !isset($tagData[$niceName])) {
                    $tagData[$niceName] = [
                        'name' => $name,
                        'slug' => $niceName,
                    ];
                }
            }
        }

        $this->info("Found " . count($categoryData) . " categories");
        $this->info("Found " . count($tagData) . " tags");

        // Pass 3: Extract posts
        foreach ($xml->channel->item as $item) {
            $postType = (string)$item->children('http://wordpress.org/export/1.2/')->post_type;
            $postStatus = (string)$item->children('http://wordpress.org/export/1.2/')->status;

            if ($postType === 'post' && $postStatus === 'publish') {
                $postId = (int)$item->children('http://wordpress.org/export/1.2/')->post_id;
                $title = (string)$item->title;
                $content = (string)$item->children('http://purl.org/rss/1.0/modules/content/')->encoded;
                $pubDate = (string)$item->pubDate;
                $creator = (string)$item->children('http://purl.org/dc/elements/1.1/')->creator;

                $thumbnailId = null;
                $thumbMetas = $item->xpath('.//wp:postmeta[wp:meta_key = "_thumbnail_id"]/wp:meta_value');
                if (!empty($thumbMetas)) {
                    $thumbnailId = (int)$thumbMetas[0];
                }

                $categories = [];
                $tags = [];

                foreach ($item->category as $cat) {
                    $domain = (string)$cat['domain'];
                    $niceName = (string)$cat['nicename'];

                    if ($domain === 'category') {
                        $categories[] = $niceName;
                    } elseif ($domain === 'post_tag') {
                        $tags[] = $niceName;
                    }
                }

                $imageUrl = null;
                if ($thumbnailId && isset($attachmentUrls[$thumbnailId])) {
                    $imageUrl = $attachmentUrls[$thumbnailId];
                }

                $postData[$postId] = [
                    'title' => $title,
                    'content' => $this->cleanContent($content),
                    'pubDate' => $pubDate,
                    'creator' => $creator,
                    'categories' => $categories,
                    'tags' => $tags,
                    'imageUrl' => $imageUrl,
                ];
            }
        }

        $this->info("Found " . count($postData) . " posts to import\n");

        // Create categories first
        $categoryMap = [];
        foreach ($categoryData as $slug => $data) {
            $category = Category::firstOrCreate(
                ['slug' => $slug],
                ['name' => $data['name']]
            );
            $categoryMap[$slug] = $category->id;
        }

        // Create tags first
        $tagMap = [];
        foreach ($tagData as $slug => $data) {
            $tag = Tag::firstOrCreate(
                ['slug' => $slug],
                ['name' => $data['name']]
            );
            $tagMap[$slug] = $tag->id;
        }

        // Import posts
        $imported = 0;
        $failed = 0;

        foreach ($postData as $postId => $data) {
            try {
                $article = new Article();
                $article->title = $data['title'];
                $article->slug = Str::slug($data['title']);
                $article->content = $data['content'];
                $article->excerpt = Str::limit(strip_tags($data['content']), 150);
                $article->published_at = $data['pubDate'] ? Carbon::parse($data['pubDate']) : now();
                $article->status = 'published';
                $article->user_id = 1;

                // Assign category (first one if multiple)
                if (!empty($data['categories']) && isset($categoryMap[$data['categories'][0]])) {
                    $article->category_id = $categoryMap[$data['categories'][0]];
                } else {
                    // Default to first category if none specified
                    $defaultCategory = Category::first();
                    $article->category_id = $defaultCategory ? $defaultCategory->id : null;
                }

                // Download and assign featured image if available
                if ($data['imageUrl']) {
                    $this->line("Processing: {$data['title']}");
                    $imagePath = $this->downloadImage($data['imageUrl']);
                    if ($imagePath) {
                        $article->featured_image = $imagePath;
                        $this->line("  ✓ Image: " . basename($imagePath));
                    } else {
                        $this->warn("  ✗ Failed to download image");
                    }
                } else {
                    $this->line("Processing: {$data['title']} (no image in XML)");
                }

                $article->save();

                // Assign tags
                if (!empty($data['tags']) && is_array($data['tags'])) {
                    $tagIds = [];
                    foreach ($data['tags'] as $tagSlug) {
                        if (isset($tagMap[$tagSlug])) {
                            $tagIds[] = $tagMap[$tagSlug];
                        }
                    }
                    if (!empty($tagIds)) {
                        $article->tags()->sync($tagIds);
                    }
                }

                $imported++;
            } catch (\Exception $e) {
                $this->warn("  Error: " . $e->getMessage());
                $failed++;
            }
        }

        $this->info("\n=== Import Complete ===");
        $this->info("✓ Imported: {$imported} articles");
        $this->warn("✗ Failed: {$failed} articles");
        $this->info("Categories created: " . count($categoryMap));
        $this->info("Tags created: " . count($tagMap));

        return 0;
    }

    private function cleanContent(string $content): string
    {
        $content = preg_replace('/<!-- \/?wp:[^>]*-->/is', '', $content);
        $content = preg_replace('/<(p|br)\s*\/?>\s*(&nbsp;|\s)*<\/(p|br)>/is', '', $content);
        return trim($content);
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
                $filename = 'image-' . time() . '.jpg';
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
