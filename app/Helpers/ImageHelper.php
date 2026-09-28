<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Get article image URL with fallback to placeholder
     */
    public static function articleImage($article, $placeholder = true)
    {
        if (empty($article->featured_image)) {
            return $placeholder ? asset('images/placeholder.jpg') : null;
        }

        $imagePath = $article->featured_image;

        // Remove leading slashes
        $imagePath = ltrim($imagePath, '/');

        // If path doesn't start with 'storage/', add it
        if (!str_starts_with($imagePath, 'storage/')) {
            $imagePath = 'storage/' . $imagePath;
        }

        return asset($imagePath);
    }

    /**
     * Get image URL safely
     */
    public static function image($path, $placeholder = true)
    {
        if (empty($path)) {
            return $placeholder ? asset('images/placeholder.jpg') : null;
        }

        $path = ltrim($path, '/');

        if (!str_starts_with($path, 'storage/')) {
            $path = 'storage/' . $path;
        }

        return asset($path);
    }
}
