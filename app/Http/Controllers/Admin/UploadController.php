<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    public function uploadSectionImage(Request $request)
    {
        try {
            // Validate upload
            $validated = $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ], [
                'image.required' => 'Please select an image',
                'image.image' => 'File must be an image',
                'image.mimes' => 'Only JPG, PNG, GIF, and WebP are allowed',
                'image.max' => 'File size must not exceed 5MB',
            ]);

            // Check if file exists
            if (!$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image file found in request',
                ], 400);
            }

            $file = $request->file('image');

            // Additional validation
            if (!$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file upload',
                ], 400);
            }

            // Create directory if not exists
            if (!Storage::disk('public')->exists('sections')) {
                Storage::disk('public')->makeDirectory('sections', 0755, true);
            }

            // Store image with unique name
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs('sections', $file, $filename);

            if (!$path) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save image to storage',
                ], 500);
            }

            // Return URL
            return response()->json([
                'success' => true,
                'url' => asset('storage/' . $path),
                'path' => $path,
                'message' => 'Image uploaded successfully',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Image upload error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
