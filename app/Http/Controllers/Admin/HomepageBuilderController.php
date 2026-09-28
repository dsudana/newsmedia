<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use App\Services\HomepageBuilderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HomepageBuilderController extends Controller
{
    /**
     * HomepageBuilderService instance
     */
    protected HomepageBuilderService $service;

    /**
     * Constructor with dependency injection
     */
    public function __construct(HomepageBuilderService $service)
    {
        $this->service = $service;
    }

    /**
     * Display the homepage builder interface
     */
    public function index($pageType = 'homepage')
    {
        $sections = $this->service->getAllSections($pageType);
        $sectionTypes = $this->service->getSectionTypes();

        return view('admin.homepage-builder.index', compact('sections', 'sectionTypes', 'pageType'));
    }

    /**
     * Display the specified section
     */
    public function show(HomepageSection $section): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'section' => $section,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the section: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created section
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'page_type' => 'required|in:homepage,blog_index',
                'section_type' => 'required|string',
                'title' => 'nullable|string|max:255',
                'config' => 'nullable|array',
            ]);

            $section = $this->service->createSection($validated);

            return response()->json([
                'success' => true,
                'section' => $section,
                'message' => 'Section created successfully',
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the section: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified section
     */
    public function update(Request $request, HomepageSection $section): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'subtitle' => 'nullable|string',
                'config' => 'nullable|array',
            ]);

            $updatedSection = $this->service->updateSection($section, $validated);

            return response()->json([
                'success' => true,
                'section' => $updatedSection,
                'message' => 'Section updated successfully',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the section: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete the specified section
     */
    public function destroy(HomepageSection $section): JsonResponse
    {
        try {
            $this->service->deleteSection($section);

            return response()->json([
                'success' => true,
                'message' => 'Section deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the section: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle the status of a section
     */
    public function toggle(HomepageSection $section): JsonResponse
    {
        try {
            $updatedSection = $this->service->toggleStatus($section);

            return response()->json([
                'success' => true,
                'section' => $updatedSection,
                'message' => 'Section status updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while toggling the section status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reorder sections
     */
    public function reorder(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'orders' => 'required|array',
                'orders.*.id' => 'required|integer',
                'orders.*.order' => 'required|integer',
            ]);

            $orders = [];
            foreach ($validated['orders'] as $item) {
                $orders[$item['id']] = $item['order'];
            }

            $this->service->reorderSections($orders);

            return response()->json([
                'success' => true,
                'message' => 'Sections reordered successfully',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while reordering sections: ' . $e->getMessage(),
            ], 500);
        }
    }
}
