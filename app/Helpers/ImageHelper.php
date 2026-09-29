<?php

namespace App\Helpers;

class ImageHelper
{
    public static function getImageUrl($imagePath)
    {
        if (!$imagePath) {
            return null;
        }

        // If it's already a URL, return it as-is
        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            return $imagePath;
        }

        // Otherwise, treat it as a file path and use asset()
        return asset('storage/' . $imagePath);
    }
}
