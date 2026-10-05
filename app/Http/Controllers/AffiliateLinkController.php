<?php

namespace App\Http\Controllers;

use App\Models\AffiliateLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AffiliateLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $links = AffiliateLink::latest()->get();
        return view('admin.affiliates.index', compact('links'));
    }

    /**
     * Display performance dashboard
     */
    public function performance()
    {
        $totalClicks = AffiliateLink::sum('clicks_count') ?? 0;
        $activeLinks = AffiliateLink::where('is_active', true)->count();
        $topLinks = AffiliateLink::orderByDesc('clicks_count')->limit(10)->get();
        $linksByArticle = \App\Models\Article::withCount('affiliateLinks')
            ->having('affiliate_links_count', '>', 0)
            ->orderByDesc('affiliate_links_count')
            ->limit(10)
            ->get();

        return view('admin.affiliates.performance', compact('totalClicks', 'activeLinks', 'topLinks', 'linksByArticle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.affiliates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'slug' => 'nullable|string|max:255|unique:affiliate_links,slug',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');

        AffiliateLink::create($validated);

        return redirect()->route('admin.affiliates.index')->with('success', 'Affiliate link created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AffiliateLink $affiliateLink)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AffiliateLink $affiliate)
    {
        // Explicit binding or just accept id
        // In route resource, usually uses {affiliate} which matches model binding if wired correctly
        // But here let's assume standard binding
        return view('admin.affiliates.edit', ['link' => $affiliate]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AffiliateLink $affiliate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'destination_url' => 'required|url',
            'slug' => 'nullable|string|max:255|unique:affiliate_links,slug,' . $affiliate->id,
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $affiliate->update($validated);

        return redirect()->route('admin.affiliates.index')->with('success', 'Affiliate link updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AffiliateLink $affiliate)
    {
        $affiliate->delete();
        return redirect()->route('admin.affiliates.index')->with('success', 'Affiliate link deleted successfully.');
    }
}
