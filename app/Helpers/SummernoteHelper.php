<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DOMDocument;
use DOMXPath;

class SummernoteHelper
{
    private const IMAGE_PATH = 'articles';

    /**
     * Extract and save base64 images from Summernote content
     * Returns cleaned HTML with file paths instead of base64
     */
    public static function extractAndSaveImages($htmlContent): string
    {
        if (empty($htmlContent)) {
            return $htmlContent;
        }

        return self::processImages($htmlContent);
    }

    /**
     * Get first image from content for featured image
     * Returns relative path or null
     */
    public static function getFirstImage($htmlContent): ?string
    {
        if (empty($htmlContent)) {
            return null;
        }

        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $htmlContent);

        $xpath = new DOMXPath($dom);
        $images = $xpath->query('//img');

        if ($images->length > 0) {
            $src = $images->item(0)->getAttribute('src');
            return self::isBase64($src) ? null : self::cleanImagePath($src);
        }

        return null;
    }

    /**
     * Delete images associated with content
     * Pass old content to extract image paths before deletion
     */
    public static function deleteImages($htmlContent): void
    {
        if (empty($htmlContent)) {
            return;
        }

        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $htmlContent);

        $xpath = new DOMXPath($dom);
        $images = $xpath->query('//img');

        foreach ($images as $img) {
            $src = $img->getAttribute('src');
            if (!self::isBase64($src)) {
                $path = self::cleanImagePath($src);
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
    }

    /**
     * Process and convert base64 images to file storage
     */
    private static function processImages($htmlContent): string
    {
        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $htmlContent);

        $xpath = new DOMXPath($dom);
        $images = $xpath->query('//img');

        foreach ($images as $img) {
            $src = $img->getAttribute('src');

            if (self::isBase64($src)) {
                $newPath = self::saveBase64Image($src);
                if ($newPath) {
                    $img->setAttribute('src', $newPath);
                }
            }
        }

        $html = $dom->saveHTML();

        // Remove XML declaration and html/body tags added by DOMDocument
        $html = preg_replace('/<\?xml.*?\?>/', '', $html);
        $html = preg_replace('/<(!DOCTYPE|html|head|body).*?>/i', '', $html);
        $html = str_replace(['</html>', '</head>', '</body>'], '', $html);

        return trim($html);
    }

    /**
     * Save base64 image to storage
     * Returns relative path or null on failure
     */
    private static function saveBase64Image($base64String): ?string
    {
        try {
            // Extract base64 data
            if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches)) {
                $ext = $matches[1];
                $data = substr($base64String, strpos($base64String, ',') + 1);
                $data = base64_decode($data);

                if ($data === false) {
                    return null;
                }

                // Generate filename
                $filename = Str::random(20) . '.' . $ext;
                $path = self::IMAGE_PATH . '/' . $filename;

                // Save to public disk
                Storage::disk('public')->put($path, $data);

                return $path;
            }
        } catch (\Exception $e) {
            \Log::error('Summernote image save failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Check if string is base64 encoded image
     */
    private static function isBase64($string): bool
    {
        return str_starts_with($string, 'data:image/');
    }

    /**
     * Clean image path (remove storage/ prefix if present)
     */
    private static function cleanImagePath($path): string
    {
        return ltrim(str_replace('storage/', '', $path), '/');
    }
}
