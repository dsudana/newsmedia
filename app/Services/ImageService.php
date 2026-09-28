<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageService
{
    /**
     * Upload dan optimize image, dengan auto-generate WebP
     */
    public function uploadAndOptimize(UploadedFile $file, string $format = 'jpg', string $path = 'articles'): array
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $uniqueName = preg_replace('/[^a-z0-9]/i', '_', $originalName) . '_' . time();

        try {
            // Create directory if not exists
            $directory = storage_path("app/{$path}");
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $results = [];
            $originalSize = $file->getSize();

            // Load original image
            $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
            if (!$image) {
                return [
                    'success' => false,
                    'message' => 'Format image tidak didukung',
                ];
            }

            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);

            // Optimize image size (max width 1200px)
            if ($originalWidth > 1200) {
                $ratio = 1200 / $originalWidth;
                $newHeight = (int)($originalHeight * $ratio);
                $resized = imagecreatetruecolor(1200, $newHeight);
                imagecopyresampled($resized, $image, 0, 0, 0, 0, 1200, $newHeight, $originalWidth, $originalHeight);
                $image = $resized;
            }

            // Save as JPG
            $jpgFileName = $uniqueName . '.jpg';
            $jpgPath = "{$path}/{$jpgFileName}";
            imageinterlace($image, true);
            imagejpeg($image, storage_path("app/{$jpgPath}"), 85);

            $results['jpg'] = [
                'path' => $jpgPath,
                'url' => "/storage/{$jpgPath}",
                'size' => filesize(storage_path("app/{$jpgPath}")),
                'format' => 'jpg',
            ];

            // Save as WebP (jika PHP GD mendukung)
            if (function_exists('imagewebp')) {
                $webpFileName = $uniqueName . '.webp';
                $webpPath = "{$path}/{$webpFileName}";
                imagewebp($image, storage_path("app/{$webpPath}"), 80);

                $results['webp'] = [
                    'path' => $webpPath,
                    'url' => "/storage/{$webpPath}",
                    'size' => filesize(storage_path("app/{$webpPath}")),
                    'format' => 'webp',
                ];
            }

            imagedestroy($image);

            // Calculate file size reduction
            $optimizedSize = array_sum(array_map(fn($f) => $f['size'], $results));
            $savedSize = $originalSize - $optimizedSize;

            return [
                'success' => true,
                'files' => $results,
                'original_size' => $originalSize,
                'optimized_size' => $optimizedSize,
                'reduction_percent' => round(($savedSize / $originalSize) * 100, 1),
                'message' => 'Image berhasil dioptimasi',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error mengoptimasi image: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Convert image ke WebP format
     */
    public function convertToWebP(string $imagePath): array
    {
        if (!function_exists('imagewebp')) {
            return [
                'success' => false,
                'message' => 'Server tidak support WebP (PHP GD WebP extension tidak tersedia)',
            ];
        }

        try {
            $fullPath = storage_path("app/{$imagePath}");

            if (!file_exists($fullPath)) {
                return [
                    'success' => false,
                    'message' => 'Image tidak ditemukan',
                ];
            }

            $image = imagecreatefromstring(file_get_contents($fullPath));
            if (!$image) {
                return [
                    'success' => false,
                    'message' => 'Tidak bisa membaca file image',
                ];
            }

            $pathInfo = pathinfo($imagePath);
            $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
            $webpFullPath = storage_path("app/{$webpPath}");

            // Optimize dimension jika perlu
            $width = imagesx($image);
            if ($width > 1200) {
                $height = imagesy($image);
                $ratio = 1200 / $width;
                $newHeight = (int)($height * $ratio);
                $resized = imagecreatetruecolor(1200, $newHeight);
                imagecopyresampled($resized, $image, 0, 0, 0, 0, 1200, $newHeight, $width, $height);
                $image = $resized;
            }

            imagewebp($image, $webpFullPath, 80);
            imagedestroy($image);

            $originalSize = filesize($fullPath);
            $webpSize = filesize($webpFullPath);
            $savings = $originalSize - $webpSize;
            $savingsPercent = round(($savings / $originalSize) * 100, 1);

            return [
                'success' => true,
                'webp_path' => $webpPath,
                'webp_url' => "/storage/{$webpPath}",
                'webp_size' => $webpSize,
                'original_size' => $originalSize,
                'savings_bytes' => $savings,
                'savings_percent' => $savingsPercent,
                'message' => "WebP berhasil dibuat. Penghematan: {$savingsPercent}%",
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error konversi WebP: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get image optimization stats
     */
    public function getImageStats(string $imagePath): array
    {
        try {
            $fullPath = storage_path("app/{$imagePath}");

            if (!file_exists($fullPath)) {
                return ['success' => false];
            }

            $image = imagecreatefromstring(file_get_contents($fullPath));
            if (!$image) {
                return ['success' => false];
            }

            $width = imagesx($image);
            $height = imagesy($image);
            imagedestroy($image);

            return [
                'success' => true,
                'width' => $width,
                'height' => $height,
                'size' => filesize($fullPath),
                'size_kb' => round(filesize($fullPath) / 1024, 2),
                'mime' => mime_content_type($fullPath),
                'format' => strtoupper(pathinfo($fullPath, PATHINFO_EXTENSION)),
            ];

        } catch (\Exception $e) {
            return ['success' => false];
        }
    }
}
