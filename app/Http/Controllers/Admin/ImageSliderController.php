<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImageSlider;
use Illuminate\Http\Request;

class ImageSliderController extends Controller
{
    public function index()
    {
        $sliders = ImageSlider::orderBy('order')->paginate(20);
        return view('admin.image-sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.image-sliders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image_url' => 'required|url',
            'caption' => 'nullable|string|max:255',
            'cta_text' => 'nullable|string|max:50',
            'cta_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $validated['order'] = ImageSlider::max('order') + 1 ?? 1;

        ImageSlider::create($validated);

        return redirect()->route('admin.image-sliders.index')
            ->with('success', 'Slider created successfully');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(ImageSlider $imageSlider)
    {
        return view('admin.image-sliders.edit', compact('imageSlider'));
    }

    public function update(Request $request, ImageSlider $imageSlider)
    {
        $validated = $request->validate([
            'image_url' => 'required|url',
            'caption' => 'nullable|string|max:255',
            'cta_text' => 'nullable|string|max:50',
            'cta_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $imageSlider->update($validated);

        return redirect()->route('admin.image-sliders.index')
            ->with('success', 'Slider updated successfully');
    }

    public function destroy(ImageSlider $imageSlider)
    {
        $imageSlider->delete();

        return redirect()->route('admin.image-sliders.index')
            ->with('success', 'Slider deleted successfully');
    }

    public function updateOrder(Request $request)
    {
        $order = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer',
        ])['order'];

        foreach ($order as $position => $sliderId) {
            ImageSlider::where('id', $sliderId)->update(['order' => $position]);
        }

        return response()->json(['success' => true]);
    }
}
