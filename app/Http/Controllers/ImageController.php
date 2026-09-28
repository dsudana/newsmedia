<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
        $this->middleware('auth');
    }

    /**
     * Convert image ke WebP via AJAX
     */
    public function convertToWebP(Request $request)
    {
        $request->validate([
            'image_path' => 'required|string',
        ]);

        $result = $this->imageService->convertToWebP($request->image_path);

        return response()->json($result);
    }

    /**
     * Get image stats
     */
    public function getImageStats(Request $request)
    {
        $request->validate([
            'image_path' => 'required|string',
        ]);

        $stats = $this->imageService->getImageStats($request->image_path);

        return response()->json($stats);
    }

    /**
     * Upload image dengan optimization
     */
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240', // max 10MB
        ]);

        if (!$request->hasFile('image')) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada file image',
            ]);
        }

        $result = $this->imageService->uploadAndOptimize(
            $request->file('image'),
            'jpg',
            'articles'
        );

        return response()->json($result);
    }

    /**
     * Preview image dengan WebP option
     */
    public function preview(Request $request)
    {
        $request->validate([
            'image_path' => 'required|string',
        ]);

        $stats = $this->imageService->getImageStats($request->image_path);

        if (!$stats['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Image tidak ditemukan',
            ]);
        }

        // Check jika WebP sudah ada
        $pathInfo = pathinfo($request->image_path);
        $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
        $webpFullPath = storage_path("app/{$webpPath}");
        $webpExists = file_exists($webpFullPath);

        return response()->json([
            'success' => true,
            'image_stats' => $stats,
            'webp_exists' => $webpExists,
            'webp_url' => $webpExists ? "/storage/{$webpPath}" : null,
            'original_url' => "/storage/{$request->image_path}",
        ]);
    }
}
