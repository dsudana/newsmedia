<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use SimpleXMLElement;

class ImportWordPressXml extends Command
{
    protected $signature = 'wordpress:import {file} {--user-id=1} {--skip-images} {--dry-run}';

    protected $description = 'Import articles from WordPress XML export file';

    private $stats = [
        'categories' => 0,
        'tags' => 0,
        'articles' => 0,
        'skipped' => 0,
        'images' => 0,
        'errors' => 0,
    ];

    private $categoryMap = [];
    private $tagMap = [];
    private $author = null;
    private $defaultCategoryId = null;

    public function handle()
    {
        $filePath = $this->argument('file');
        $userId = $this->option('user-id');
        $skipImages = $this->option('skip-images');
        $dryRun = $this->option('dry-run');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info('Starting WordPress XML import...');

        try {
            $this->author = User::findOrFail($userId);
            $this->info("Using author: {$this->author->name} (ID: {$this->author->id})");

            if ($dryRun) {
                $this->warn('DRY RUN MODE - No data will be saved');
            }

            $xml = simplexml_load_file($filePath);
            $this->registerNamespaces($xml);

            $this->importCategories($xml, $dryRun);
            $this->importTags($xml, $dryRun);
            $this->importArticles($xml, $skipImages, $dryRun);

            $this->displayStats();

            if (!$dryRun) {
                $this->info('✅ Import completed successfully!');
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('Import failed: ' . $e->getMessage());
            return 1;
        }
    }

    private function registerNamespaces($xml)
    {
        $xml->registerXPathNamespace('wp', 'http://wordpress.org/export/1.2/');
        $xml->registerXPathNamespace('content', 'http://purl.org/rss/1.0/modules/content/');
    }

    private function importCategories($xml, $dryRun)
    {
        $this->info('Importing categories...');

        if (!$dryRun) {
            $defaultCat = Category::firstOrCreate(
                ['slug' => 'uncategorized'],
                ['name' => 'Uncategorized', 'is_active' => true]
            );
            $this->defaultCategoryId = $defaultCat->id;
        } else {
            $this->defaultCategoryId = 0;
        }

        $categories = $xml->xpath('//wp:category');

        foreach ($categories as $cat) {
            try {
                $catSlug = (string)$cat->{'category_nicename'};
                $catName = (string)$cat->{'cat_name'};
                $catDesc = (string)$cat->{'category_description'};

                if (!$catSlug || !$catName) {
                    continue;
                }

                if (!$dryRun) {
                    $category = Category::firstOrCreate(
                        ['slug' => $catSlug],
                        [
                            'name' => $catName,
                            'description' => $catDesc,
                            'is_active' => true,
                        ]
                    );
                    $this->categoryMap[$catSlug] = $category->id;
                } else {
                    $this->categoryMap[$catSlug] = 0;
                }

                $this->stats['categories']++;
                $this->line("  ✓ Category: {$catName}");
            } catch (\Exception $e) {
                $this->warn("  ✗ Failed to import category: {$e->getMessage()}");
                $this->stats['errors']++;
            }
        }
    }

    private function importTags($xml, $dryRun)
    {
        $this->info('Importing tags...');

        $tags = $xml->xpath('//wp:tag');

        foreach ($tags as $tag) {
            try {
                $tagSlug = (string)$tag->{'tag_slug'};
                $tagName = (string)$tag->{'tag_name'};

                if (!$tagSlug || !$tagName) {
                    continue;
                }

                if (!$dryRun) {
                    $tagObj = Tag::firstOrCreate(
                        ['slug' => $tagSlug],
                        ['name' => $tagName]
                    );
                    $this->tagMap[$tagSlug] = $tagObj->id;
                } else {
                    $this->tagMap[$tagSlug] = 0;
                }

                $this->stats['tags']++;
                $this->line("  ✓ Tag: {$tagName}");
            } catch (\Exception $e) {
                $this->warn("  ✗ Failed to import tag: {$e->getMessage()}");
                $this->stats['errors']++;
            }
        }
    }

    private function importArticles($xml, $skipImages, $dryRun)
    {
        $this->info('Importing articles...');

        $items = $xml->channel->item;

        foreach ($items as $item) {
            try {
                $postType = (string)$item->children('http://wordpress.org/export/1.2/')->{'post_type'};

                if ($postType !== 'post') {
                    $this->stats['skipped']++;
                    continue;
                }

                $title = (string)$item->title;
                $content = (string)$item->children('http://purl.org/rss/1.0/modules/content/')->{'encoded'};
                $description = (string)$item->description;
                $pubDate = (string)$item->pubDate;

                if (!$title || !$content) {
                    $this->stats['skipped']++;
                    continue;
                }

                $publishDate = $this->parseDate($pubDate);

                $articleData = [
                    'user_id' => $this->author->id,
                    'title' => $title,
                    'excerpt' => $description ?: Str::limit(strip_tags($content), 255),
                    'content' => $this->cleanContent($content),
                    'status' => 'published',
                    'published_at' => $publishDate,
                    'created_at' => $publishDate,
                    'updated_at' => $publishDate,
                    'featured_image' => '/images/placeholder.jpg',
                ];

                $categoryId = $this->getArticleCategory($item) ?: $this->defaultCategoryId;
                $articleData['category_id'] = $categoryId;

                if (!$dryRun) {
                    $article = Article::create($articleData);

                    $this->attachTags($item, $article);
                    $this->downloadFeaturedImage($item, $article, $skipImages);

                    $this->line("  ✓ Article: {$title}");
                } else {
                    $this->line("  ✓ Article (dry-run): {$title}");
                }

                $this->stats['articles']++;
            } catch (\Exception $e) {
                $this->warn("  ✗ Failed to import article: {$e->getMessage()}");
                $this->stats['errors']++;
            }
        }
    }

    private function getArticleCategory($item)
    {
        $categories = $item->xpath('category[@domain="category"]');

        foreach ($categories as $cat) {
            $catSlug = (string)$cat->attributes();
            if (isset($this->categoryMap[$catSlug])) {
                return $this->categoryMap[$catSlug];
            }
        }

        return null;
    }

    private function attachTags($item, $article)
    {
        $tags = $item->xpath('category[@domain="post_tag"]');
        $tagIds = [];

        foreach ($tags as $tag) {
            $tagSlug = (string)$tag->attributes();
            if (isset($this->tagMap[$tagSlug])) {
                $tagIds[] = $this->tagMap[$tagSlug];
            }
        }

        if (!empty($tagIds)) {
            $article->tags()->syncWithoutDetaching($tagIds);
        }
    }

    private function downloadFeaturedImage($item, $article, $skipImages)
    {
        if ($skipImages) {
            return;
        }

        try {
            $attachment = $item->xpath('./wp:attachment_url');
            if (empty($attachment)) {
                return;
            }

            $imageUrl = (string)$attachment[0];
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                return;
            }

            $filename = basename(parse_url($imageUrl, PHP_URL_PATH));
            $filename = sanitize_filename($filename);

            if (empty($filename)) {
                $filename = 'article-' . $article->id . '.jpg';
            }

            $response = Http::timeout(10)->get($imageUrl);

            if ($response->successful()) {
                $path = 'articles/' . date('Y/m') . '/' . $filename;
                storage_path('app/public/' . $path);
                file_put_contents(storage_path('app/public/' . $path), $response->body());

                $article->update(['featured_image' => '/storage/' . $path]);
                $this->stats['images']++;
            }
        } catch (\Exception $e) {
            $this->warn("  ⚠ Could not download image: {$e->getMessage()}");
        }
    }

    private function cleanContent($content)
    {
        $content = trim($content);

        // Remove WordPress block editor comments
        $content = preg_replace('/<!-- wp:[^>]*-->/is', '', $content);

        // Remove script and style tags
        $content = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $content);
        $content = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $content);

        // Remove extra whitespace
        $content = preg_replace('/\n\s*\n/', "\n", $content);

        return trim($content);
    }

    private function parseDate($dateString)
    {
        try {
            return \Carbon\Carbon::parse($dateString);
        } catch (\Exception $e) {
            return now();
        }
    }

    private function displayStats()
    {
        $this->newLine();
        $this->info('📊 Import Statistics:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Categories Imported', $this->stats['categories']],
                ['Tags Imported', $this->stats['tags']],
                ['Articles Imported', $this->stats['articles']],
                ['Images Downloaded', $this->stats['images']],
                ['Items Skipped', $this->stats['skipped']],
                ['Errors', $this->stats['errors']],
            ]
        );
    }
}

function sanitize_filename($filename)
{
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '-', $filename);
    $filename = preg_replace('/-+/', '-', $filename);
    return trim($filename, '-');
}
