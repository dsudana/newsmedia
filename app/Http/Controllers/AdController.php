<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ads = Ad::latest()->paginate(10);
        return view('admin.ads.index', compact('ads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ads.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:banner,adsense,script',
            'placement' => 'required|in:header,sidebar,in_article,footer',
            'image' => 'nullable|image|max:2048|required_if:type,banner', // Required if type is banner
            'url' => 'nullable|url|max:255|required_if:type,banner',
            'script' => 'nullable|string|required_if:type,script|required_if:type,adsense', // Script or adsense
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('ads', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        Ad::create($validated);

        return redirect()->route('admin.ads.index')->with('success', 'Ad created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ad $ad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ad $ad)
    {
        return view('admin.ads.edit', compact('ad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ad $ad)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:banner,adsense,script',
            'placement' => 'required|in:header,sidebar,in_article,footer',
            'image' => 'nullable|image|max:2048',
            'url' => 'nullable|url|max:255|required_if:type,banner',
            'script' => 'nullable|string|required_if:type,script|required_if:type,adsense',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($ad->image) {
                Storage::disk('public')->delete($ad->image);
            }
            $validated['image'] = $request->file('image')->store('ads', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $ad->update($validated);

        return redirect()->route('admin.ads.index')->with('success', 'Ad updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ad $ad)
    {
        if ($ad->image) {
            Storage::disk('public')->delete($ad->image);
        }
        $ad->delete();
        return redirect()->route('admin.ads.index')->with('success', 'Ad deleted successfully.');
    }
}
