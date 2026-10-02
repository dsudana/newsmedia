<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Get featured image URL - handles both local paths and external URLs
     */
    public static function featuredImageUrl($image)
    {
        if (empty($image)) {
            return null;
        }

        // If it's already a full URL (http/https), return as-is
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        // Otherwise it's a local path, prepend storage
        return asset('storage/' . $image);
    }
}
