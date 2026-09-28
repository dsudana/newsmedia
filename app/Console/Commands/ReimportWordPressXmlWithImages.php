<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReimportWordPressXmlWithImages extends Command
{
    protected $signature = 'wordpress:reimport-with-images {xmlfile}';
    protected $description = 'Re-import WordPress XML articles with correct featured images';

    public function handle()
    {
        $xmlFile = $this->argument('xmlfile');

        if (!file_exists($xmlFile)) {
            $this->error("File not found: {$xmlFile}");
            return 1;
        }

        $this->info("Parsing WordPress XML: {$xmlFile}");

        $xml = simplexml_load_file($xmlFile);
        $xml->registerXPathNamespace('wp', 'http://wordpress.org/export/1.2/');
        $xml->registerXPathNamespace('content', 'http://purl.org/rss/1.0/modules/content/');

        $attachmentUrls = [];
        $postData = [];

        foreach ($xml->channel->item as $item) {
            $postType = (string)$item->children('http://wordpress.org/export/1.2/')->post_type;
            $postId = (int)$item->children('http://wordpress.org/export/1.2/')->post_id;

            if ($postType === 'attachment') {
                $attachmentUrl = (string)$item->children('http://wordpress.org/export/1.2/')->attachment_url;
                $attachmentUrls[$postId] = $attachmentUrl;
            }
        }

        $this->info("Found " . count($attachmentUrls) . " attachments");

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

                $categoryNames = [];
                foreach ($item->category as $cat) {
                    $domain = (string)$cat['domain'];
                    if ($domain === 'category') {
                        $categoryNames[] = (string)$cat;
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
                    'categories' => $categoryNames,
                    'imageUrl' => $imageUrl,
                ];
            }
        }

        $this->info("Found " . count($postData) . " posts to import");

        $updated = 0;
        $created = 0;
        $skipped = 0;

        foreach ($postData as $postId => $data) {
            $slug = Str::slug($data['title']);

            $article = Article::where('slug', $slug)->first();

            if (!$article) {
                $article = new Article();
                $created++;
            } else {
                $updated++;
            }

            $article->title = $data['title'];
            $article->slug = $slug;
            $article->content = $data['content'];
            $article->excerpt = Str::limit(strip_tags($data['content']), 150);
            $article->published_at = $data['pubDate'] ? \Carbon\Carbon::parse($data['pubDate']) : now();
            $article->status = 'published';
            $article->user_id = 1;

            if ($data['imageUrl']) {
                $this->line("Processing: {$data['title']}");
                $imagePath = $this->downloadImage($data['imageUrl']);
                if ($imagePath) {
                    $article->featured_image = $imagePath;
                    $this->line("  ✓ Image downloaded: {$imagePath}");
                } else {
                    $this->warn("  ✗ Failed to download image");
                }
            }

            if (!empty($data['categories']) && count($data['categories']) > 0) {
                $category = Category::firstOrCreate(
                    ['name' => $data['categories'][0]],
                    ['slug' => Str::slug($data['categories'][0])]
                );
                $article->category_id = $category->id;
            }

            $article->save();
        }

        $this->info("\n=== Summary ===");
        $this->info("Created: {$created} articles");
        $this->info("Updated: {$updated} articles");
        $this->warn("Skipped: {$skipped} articles");

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
