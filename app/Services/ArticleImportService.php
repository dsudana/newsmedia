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
            if (stripos($xmlContent, '<!DOCTYPE') !== false) {
                throw new Exception('DOCTYPE declarations are not allowed for security reasons');
            }

            $prev = libxml_use_internal_errors(true);
            $xml = simplexml_load_string(
                $xmlContent,
                'SimpleXMLElement',
                LIBXML_NONET
            );
            libxml_use_internal_errors($prev);

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
        $attachments = $this->extractAttachments($xml);

        if (!isset($xml->channel->item)) {
            return $articles;
        }

        foreach ($xml->channel->item as $item) {
            $attachmentId = $this->extractFeaturedImageId($item);
            $featuredImageUrl = null;

            // If attachment ID found, look up the URL from attachments
            if ($attachmentId && isset($attachments[$attachmentId])) {
                $featuredImageUrl = $attachments[$attachmentId];
            }

            $articles[] = [
                'title' => (string)$item->title,
                'slug' => (string)$item->children('wp', true)->post_name ?? Str::slug((string)$item->title),
                'excerpt' => (string)($item->description ?? ''),
                'content' => (string)$item->children('content', true)->encoded ?? '',
                'meta_description' => $this->extractMetaDescription($item),
                'meta_title' => (string)($item->title ?? ''),
                'status' => $this->mapStatus((string)$item->children('wp', true)->status ?? 'draft'),
                'published_at' => $this->parsePubDate((string)$item->pubDate ?? null),
                'featured_image' => $featuredImageUrl,
            ];
        }

        return $articles;
    }

    /**
     * Extract all attachments from WordPress XML
     * Returns array of attachment_id => image_url
     */
    protected function extractAttachments($xml)
    {
        $attachments = [];

        if (!isset($xml->channel->item)) {
            return $attachments;
        }

        foreach ($xml->channel->item as $item) {
            $wp = $item->children('wp', true);
            $type = (string)($wp->post_type ?? '');

            // Only process attachment items
            if ($type !== 'attachment') {
                continue;
            }

            $attachmentId = (string)($wp->post_id ?? '');
            $attachmentUrl = (string)($item->children('wp', true)->attachment_url ?? '');

            if ($attachmentId && $attachmentUrl) {
                $attachments[$attachmentId] = $attachmentUrl;
            }
        }

        return $attachments;
    }

    /**
     * Extract featured image ID from WordPress post meta
     */
    protected function extractFeaturedImageId($item)
    {
        $wp = $item->children('wp', true);

        if (isset($wp->post_meta)) {
            foreach ($wp->post_meta as $postMeta) {
                $metaKey = (string)$postMeta->children('wp', true)->meta_key;
                if ($metaKey === '_thumbnail_id') {
                    return (string)$postMeta->children('wp', true)->meta_value;
                }
            }
        }

        return null;
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

            // Download featured image if URL provided and enabled
            $featuredImagePath = null;
            if ($downloadImages && !empty($articleData['featured_image'])) {
                $featuredImagePath = $this->downloadFeaturedImage(
                    $articleData['featured_image'],
                    $articleData['slug']
                );
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
                'featured_image' => $featuredImagePath,
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
     * Download and save featured image with SSRF/DNS-pinning protection
     */
    public function downloadFeaturedImage($imageUrl, $articleSlug)
    {
        if (empty($imageUrl)) {
            return null;
        }

        try {
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                return null;
            }

            $url = parse_url($imageUrl);
            if (!isset($url['scheme']) || !in_array($url['scheme'], ['http', 'https'])) {
                Log::warning('Invalid URL scheme: ' . $imageUrl);
                return null;
            }

            if (!isset($url['host'])) {
                Log::warning('Invalid URL host: ' . $imageUrl);
                return null;
            }

            // Resolve hostname once to prevent TOCTOU
            $resolvedIp = gethostbyname($url['host']);
            if ($resolvedIp === $url['host']) {
                Log::warning('Failed to resolve hostname: ' . $url['host']);
                return null;
            }

            if ($this->isPrivateIP($resolvedIp)) {
                Log::warning('Blocked private/internal IP: ' . $resolvedIp);
                return null;
            }

            // Use cURL with DNS pinning to prevent TOCTOU
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $imageUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                CURLOPT_RESOLVE => [$url['host'] . ':' . ($url['scheme'] === 'https' ? 443 : 80) . ':' . $resolvedIp],
            ]);

            $imageContent = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (!$imageContent || $httpCode !== 200) {
                Log::warning('Failed to download image: ' . $imageUrl);
                return null;
            }

            // Get image info
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $imageContent);
            finfo_close($finfo);

            // Determine extension (no SVG allowed)
            $ext = $this->getMimeExtension($mimeType);
            if (!$ext) {
                Log::warning('Unknown or unsupported image type: ' . $mimeType);
                return null;
            }

            // Sanitize filename to prevent path traversal
            $safeSlug = Str::slug($articleSlug) ?: Str::random(16);
            $filename = 'articles/' . $safeSlug . '-' . time() . '.' . $ext;
            $path = Storage::disk('public')->put($filename, $imageContent);

            Log::info('Image downloaded: ' . $path);
            return $path;
        } catch (Exception $e) {
            Log::warning('Failed to download featured image: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if IP is private/internal (SSRF prevention)
     */
    protected function isPrivateIP($ip)
    {
        $privateRanges = [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16',
            '127.0.0.0/8',
            '169.254.0.0/16',
            '::1/128',
            'fc00::/7',
        ];

        foreach ($privateRanges as $range) {
            if ($this->ipInRange($ip, $range)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if IP is in CIDR range
     */
    protected function ipInRange($ip, $range)
    {
        if (strpos($range, ':') !== false) {
            return $this->ipv6InRange($ip, $range);
        }

        [$subnet, $bits] = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask;

        return ($ip & $mask) === $subnet;
    }

    /**
     * Check if IPv6 is in CIDR range
     */
    protected function ipv6InRange($ip, $range)
    {
        [$subnet, $bits] = explode('/', $range);

        if (inet_pton($ip) === false || inet_pton($subnet) === false) {
            return false;
        }

        $ipBin = inet_pton($ip);
        $subnetBin = inet_pton($subnet);
        $maskBin = '';

        for ($i = 0; $i < $bits; $i++) {
            $maskBin .= (int)($i / 8) === ($i / 8) ? '1' : '0';
        }

        while (strlen($maskBin) < 128) {
            $maskBin .= '0';
        }

        $maskBin = hex2bin(base_convert($maskBin, 2, 16));

        return ($ipBin & $maskBin) === ($subnetBin & $maskBin);
    }

    /**
     * Get file extension from MIME type (no SVG for security)
     */
    protected function getMimeExtension($mimeType)
    {
        $mapping = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
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
