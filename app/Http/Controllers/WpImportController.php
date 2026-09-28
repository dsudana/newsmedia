<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WpImportController extends Controller
{
    public function show()
    {
        return view('admin.wp-import.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'xml_file' => 'required|file|mimes:xml|max:50000',
        ]);

        try {
            $file = $request->file('xml_file');
            $xmlContent = $file->get();

            return $this->processXmlImport($xmlContent);
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    private function processXmlImport($xmlContent)
    {
        // Disable external entities before parsing to prevent XXE attacks
        $xml = simplexml_load_string(
            $xmlContent,
            'SimpleXMLElement',
            LIBXML_NONET | LIBXML_DTDLOAD
        );

        if ($xml === false) {
            return back()->with('error', 'Invalid XML');
        }

        $xml->registerXPathNamespace('wp', 'http://wordpress.org/export/1.2/');
        $xml->registerXPathNamespace('content', 'http://purl.org/rss/1.0/modules/content/');

        // Extract attachments
        $attachmentUrls = [];
        foreach ($xml->channel->item as $item) {
            $postType = (string)$item->children('http://wordpress.org/export/1.2/')->post_type;
            if ($postType === 'attachment') {
                $postId = (int)$item->children('http://wordpress.org/export/1.2/')->post_id;
                $attachmentUrl = (string)$item->children('http://wordpress.org/export/1.2/')->attachment_url;
                $attachmentUrls[$postId] = $attachmentUrl;
            }
        }

        // Delete existing articles
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Article::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Extract categories and tags
        $categoryData = [];
        $tagData = [];
        foreach ($xml->channel->item as $item) {
            foreach ($item->category as $cat) {
                $domain = (string)$cat['domain'];
                $niceName = (string)$cat['nicename'];
                $name = (string)$cat;

                if ($domain === 'category' && !isset($categoryData[$niceName])) {
                    $categoryData[$niceName] = ['name' => $name, 'slug' => $niceName];
                }
                if ($domain === 'post_tag' && !isset($tagData[$niceName])) {
                    $tagData[$niceName] = ['name' => $name, 'slug' => $niceName];
                }
            }
        }

        // Create categories
        $categoryMap = [];
        foreach ($categoryData as $slug => $data) {
            $category = Category::firstOrCreate(['slug' => $slug], ['name' => $data['name']]);
            $categoryMap[$slug] = $category->id;
        }

        // Create tags
        $tagMap = [];
        foreach ($tagData as $slug => $data) {
            $tag = Tag::firstOrCreate(['slug' => $slug], ['name' => $data['name']]);
            $tagMap[$slug] = $tag->id;
        }

        // Import posts
        $imported = 0;
        $failed = 0;

        foreach ($xml->channel->item as $item) {
            $postType = (string)$item->children('http://wordpress.org/export/1.2/')->post_type;
            $postStatus = (string)$item->children('http://wordpress.org/export/1.2/')->status;

            if ($postType === 'post' && $postStatus === 'publish') {
                try {
                    $title = (string)$item->title;
                    $content = (string)$item->children('http://purl.org/rss/1.0/modules/content/')->encoded;
                    $pubDate = (string)$item->pubDate;
                    $creator = (string)$item->children('http://purl.org/dc/elements/1.1/')->creator;

                    // Get thumbnail ID
                    $thumbnailId = null;
                    $thumbMetas = $item->xpath('.//wp:postmeta[wp:meta_key = "_thumbnail_id"]/wp:meta_value');
                    if (!empty($thumbMetas)) {
                        $thumbnailId = (int)$thumbMetas[0];
                    }

                    // Get categories and tags
                    $categories = [];
                    $tags = [];
                    foreach ($item->category as $cat) {
                        $domain = (string)$cat['domain'];
                        $niceName = (string)$cat['nicename'];
                        if ($domain === 'category') $categories[] = $niceName;
                        elseif ($domain === 'post_tag') $tags[] = $niceName;
                    }

                    // Get image URL
                    $imageUrl = null;
                    if ($thumbnailId && isset($attachmentUrls[$thumbnailId])) {
                        $imageUrl = $attachmentUrls[$thumbnailId];
                    }

                    // Create article
                    $article = new Article();
                    $article->title = $title;
                    $article->slug = Str::slug($title);
                    $article->content = $this->cleanContent($content);
                    $article->excerpt = Str::limit(strip_tags($article->content), 150);
                    $article->published_at = $pubDate ? Carbon::parse($pubDate) : now();
                    $article->status = 'published';
                    $article->user_id = 1;

                    // Download and assign image
                    if ($imageUrl) {
                        $imagePath = $this->downloadImage($imageUrl);
                        if ($imagePath) {
                            $article->featured_image = $imagePath;
                        }
                    }

                    // Assign first category if available
                    if (!empty($categories) && isset($categoryMap[$categories[0]])) {
                        $article->category_id = $categoryMap[$categories[0]];
                    } else {
                        $defaultCategory = Category::first();
                        $article->category_id = $defaultCategory ? $defaultCategory->id : null;
                    }

                    $article->save();

                    // Assign tags
                    if (!empty($tags)) {
                        $tagIds = [];
                        foreach ($tags as $tagSlug) {
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
                    $failed++;
                }
            }
        }

        // Extract images from content for articles without featured images
        $this->extractImagesFromContent();

        return back()->with('success', "Import successful! $imported articles imported, $failed failed.");
    }

    private function cleanContent($content)
    {
        // Remove WordPress block comments
        $content = preg_replace('/<!-- \/?wp:[^>]*-->/is', '', $content);

        // Remove figure and img tags
        $content = preg_replace('/<figure[^>]*>|<\/figure>/i', '', $content);
        $content = preg_replace('/<img[^>]*>/i', '', $content);

        // Remove hr tags
        $content = preg_replace('/<hr[^>]*>/i', '', $content);

        // Remove formatting tags
        $content = preg_replace('/<\/?em>/i', '', $content);
        $content = preg_replace('/<\/?strong>/i', '', $content);
        $content = preg_replace('/<\/?i>/i', '', $content);
        $content = preg_replace('/<\/?b>/i', '', $content);

        // Clean up paragraphs
        $paragraphs = explode('</p>', $content);
        $cleanParagraphs = [];

        foreach ($paragraphs as $para) {
            $para = trim($para);
            $para = str_replace('<p>', '', $para);
            $para = trim($para);

            if (!empty($para)) {
                $cleanParagraphs[] = $para;
            }
        }

        return implode("\n\n", $cleanParagraphs);
    }

    private function downloadImage($imageUrl)
    {
        try {
            if (empty($imageUrl)) {
                return null;
            }

            // Validate URL to prevent SSRF
            $parsed = parse_url($imageUrl);
            if (!$parsed || !in_array($parsed['scheme'] ?? '', ['http', 'https'])) {
                return null; // Skip non-http(s) URLs
            }

            $host = $parsed['host'] ?? '';
            if ($host === '') {
                return null;
            }

            // Resolve and validate EVERY DNS record (A + AAAA) to prevent
            // DNS-rebinding / multi-record SSRF bypasses.
            $records = @dns_get_record($host, DNS_A + DNS_AAAA);

            if ($records === false || empty($records)) {
                // dns_get_record failed or returned nothing. Fall back to
                // gethostbyname so we can still validate the resolved address.
                $ip = gethostbyname($host);
                if ($ip === $host) {
                    // DNS resolution failed - hostname returned unchanged.
                    return null;
                }
                if ($this->isPrivateIp($ip)) {
                    return null;
                }
            } else {
                foreach ($records as $record) {
                    $ip = $record['ip'] ?? $record['ipv6'] ?? null;
                    if (!$ip) {
                        continue;
                    }
                    if ($this->isPrivateIp($ip)) {
                        // At least one record resolves to a private/reserved IP.
                        return null;
                    }
                }
            }

            // Automatic redirects are DISABLED (max_redirects => 0). A redirect
            // target is never re-validated, so following one would allow an
            // attacker to bounce us to a private address after validation.
            $context = stream_context_create([
                'http' => [
                    'timeout' => 15,
                    'user_agent' => 'Mozilla/5.0',
                    'follow_location' => 0,
                    'max_redirects' => 0,
                ],
                'ssl' => [
                    'verify_peer' => true,
                    'verify_peer_name' => true,
                ],
            ]);

            // Safe to fetch
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

    /**
     * Determine whether an IP (IPv4 or IPv6) is private, reserved, loopback
     * or otherwise unsafe to fetch (SSRF protection).
     */
    private function isPrivateIp($ip)
    {
        // Not a valid IP at all -> treat as unsafe.
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            return true;
        }

        // filter_var rejects private (RFC1918 / fc00::/7) and reserved
        // (loopback, link-local, ::1, 169.254.0.0/16, etc.) ranges for both
        // IPv4 and IPv6. A public IP is returned unchanged; anything unsafe
        // returns false.
        $isPublic = filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );

        if ($isPublic === false) {
            return true;
        }

        // Explicit IPv4 range check as defence-in-depth.
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $privateRanges = [
                '127.0.0.0/8',
                '10.0.0.0/8',
                '172.16.0.0/12',
                '192.168.0.0/16',
                '169.254.0.0/16',
                '0.0.0.0/8',
            ];
            foreach ($privateRanges as $range) {
                if ($this->ipInRange($ip, $range)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function ipInRange($ip, $range)
    {
        if (strpos($range, '/') === false) {
            return $ip === $range;
        }

        [$subnet, $bits] = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        if ($ip === false || $subnet === false) {
            return false;
        }
        $mask = -1 << (32 - $bits);
        $subnet &= $mask;
        return ($ip & $mask) === $subnet;
    }

    private function extractImagesFromContent()
    {
        $articles = Article::where(function($q) {
            $q->whereNull('featured_image')->orWhere('featured_image', '=', '');
        })->get();

        foreach ($articles as $article) {
            $imageUrl = $this->extractImageFromContent($article->content);
            if ($imageUrl) {
                $imagePath = $this->downloadImage($imageUrl);
                if ($imagePath) {
                    $article->update(['featured_image' => $imagePath]);
                }
            }
        }
    }

    private function extractImageFromContent($content)
    {
        if (preg_match('/<figure[^>]*>.*?<img[^>]*src=["\']([^"\']+)["\'].*?<\/figure>/is', $content, $matches)) {
            return $matches[1];
        }

        if (preg_match('/<img[^>]*src=["\']([^"\']+)["\'].*?>/is', $content, $matches)) {
            $src = $matches[1];
            if (filter_var($src, FILTER_VALIDATE_URL) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $src)) {
                return $src;
            }
        }

        return null;
    }
}
