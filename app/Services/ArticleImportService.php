<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Keyword;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class ArticleImportService
{
    protected $importResults = [
        'total' => 0,
        'imported' => 0,
        'failed' => 0,
        'errors' => [],
        'articles' => [],
    ];

    /**
     * Parse WordPress XML export file
     */
    public function parseXML($xmlContent)
    {
        try {
            $xml = simplexml_load_string($xmlContent);
            if (!$xml) {
                throw new Exception('Invalid XML format');
            }
            return $xml;
        } catch (Exception $e) {
            throw new Exception('Failed to parse XML: ' . $e->getMessage());
        }
    }

    /**
     * Extract articles from WordPress XML
     */
    public function extractArticles($xml)
    {
        $articles = [];

        if (!isset($xml->channel->item)) {
            return $articles;
        }

        foreach ($xml->channel->item as $item) {
            $articles[] = [
                'title' => (string)$item->title,
                'slug' => (string)$item->children('wp', true)->post_name ?? Str::slug((string)$item->title),
                'excerpt' => (string)($item->description ?? ''),
                'content' => (string)$item->children('content', true)->encoded ?? '',
                'meta_description' => $this->extractMetaDescription($item),
                'meta_title' => (string)($item->title ?? ''),
                'status' => $this->mapStatus((string)$item->children('wp', true)->status ?? 'draft'),
                'published_at' => $this->parsePubDate((string)$item->pubDate ?? null),
                'featured_image' => null, // Will be set if image exists
            ];
        }

        return $articles;
    }

    /**
     * Extract meta description from WordPress meta
     */
    protected function extractMetaDescription($item)
    {
        $meta = $item->children('wp', true);
        if (isset($meta->post_meta)) {
            foreach ($meta->post_meta as $postMeta) {
                $metaKey = (string)$postMeta->children('wp', true)->meta_key;
                if ($metaKey === '_yoast_wpseo_metadesc' || $metaKey === '_metabox_meta_description') {
                    return (string)$postMeta->children('wp', true)->meta_value;
                }
            }
        }
        return '';
    }

    /**
     * Map WordPress status to newsmedia status
     */
    protected function mapStatus($wpStatus)
    {
        $mapping = [
            'publish' => 'published',
            'draft' => 'draft',
            'pending' => 'draft',
            'private' => 'draft',
        ];

        return $mapping[$wpStatus] ?? 'draft';
    }

    /**
     * Parse WordPress publish date
     */
    protected function parsePubDate($pubDate)
    {
        if (empty($pubDate)) {
            return now();
        }

        try {
            return new \DateTime($pubDate);
        } catch (Exception $e) {
            Log::warning('Failed to parse date: ' . $pubDate);
            return now();
        }
    }

    /**
     * Import article with validation
     */
    public function importArticle($articleData, $userId, $categoryMapping = [], $keywordMapping = [], $downloadImages = true)
    {
        try {
            $this->importResults['total']++;

            // Validate required fields
            if (empty($articleData['title']) || empty($articleData['content'])) {
                throw new Exception('Title and content are required');
            }

            // Check if article already exists by slug
            $existing = Article::where('slug', $articleData['slug'])->first();
            if ($existing) {
                throw new Exception('Article with slug "' . $articleData['slug'] . '" already exists');
            }

            // Get or create category
            $categoryId = $this->resolveCategoryId($categoryMapping);
            if (!$categoryId) {
                throw new Exception('Default category not found');
            }

            // Calculate word count
            $wordCount = str_word_count(strip_tags($articleData['content']));

            // Create article
            $article = Article::create([
                'user_id' => $userId,
                'category_id' => $categoryId,
                'title' => $articleData['title'],
                'slug' => $articleData['slug'],
                'excerpt' => $articleData['excerpt'],
                'content' => $articleData['content'],
                'meta_title' => $articleData['meta_title'],
                'meta_description' => $articleData['meta_description'],
                'status' => $articleData['status'],
                'published_at' => $articleData['published_at'],
                'featured_image' => $articleData['featured_image'],
                'word_count' => $wordCount,
                'views_count' => 0,
                'is_featured' => false,
            ]);

            $this->importResults['imported']++;
            $this->importResults['articles'][] = [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'status' => 'success',
            ];

            return $article;
        } catch (Exception $e) {
            $this->importResults['failed']++;
            $this->importResults['errors'][] = [
                'title' => $articleData['title'] ?? 'Unknown',
                'error' => $e->getMessage(),
            ];

            Log::error('Article import failed: ' . $e->getMessage(), [
                'article_title' => $articleData['title'] ?? 'Unknown',
            ]);

            return null;
        }
    }

    /**
     * Resolve category ID from mapping or use default
     */
    protected function resolveCategoryId($categoryMapping = [])
    {
        // If mapping provided, use first mapped category
        if (!empty($categoryMapping)) {
            $categoryId = array_values($categoryMapping)[0];
            if (Category::find($categoryId)) {
                return $categoryId;
            }
        }

        // Otherwise use first active category
        $category = Category::where('is_active', true)->first();
        return $category?->id;
    }

    /**
     * Import batch of articles
     */
    public function importBatch($articles, $userId, $categoryMapping = [], $keywordMapping = [], $downloadImages = true)
    {
        $this->importResults = [
            'total' => 0,
            'imported' => 0,
            'failed' => 0,
            'errors' => [],
            'articles' => [],
        ];

        foreach ($articles as $articleData) {
            $this->importArticle($articleData, $userId, $categoryMapping, $keywordMapping, $downloadImages);
        }

        return $this->importResults;
    }

    /**
     * Download and save featured image
     */
    public function downloadFeaturedImage($imageUrl, $articleSlug)
    {
        if (empty($imageUrl)) {
            return null;
        }

        try {
            // Skip if already local path
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                return null;
            }

            // Download image
            $imageContent = file_get_contents($imageUrl, false, stream_context_create([
                'http' => [
                    'timeout' => 10,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ]
            ]));

            if (!$imageContent) {
                Log::warning('Failed to download image: ' . $imageUrl);
                return null;
            }

            // Get image info
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $imageContent);
            finfo_close($finfo);

            // Determine extension
            $ext = $this->getMimeExtension($mimeType);
            if (!$ext) {
                Log::warning('Unknown image type: ' . $mimeType);
                return null;
            }

            // Save to storage
            $filename = 'articles/' . $articleSlug . '-' . time() . '.' . $ext;
            $path = Storage::disk('public')->put($filename, $imageContent);

            Log::info('Image downloaded: ' . $path);
            return $path;
        } catch (Exception $e) {
            Log::warning('Failed to download featured image: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get file extension from MIME type
     */
    protected function getMimeExtension($mimeType)
    {
        $mapping = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
        ];

        return $mapping[$mimeType] ?? null;
    }

    /**
     * Get import results
     */
    public function getResults()
    {
        return $this->importResults;
    }
}
