<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LegalPageController extends Controller
{
    public function index()
    {
        $pages = LegalPage::latest()->paginate(20);
        return view('admin.legal-pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.legal-pages.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:legal_pages',
            'content' => 'required|string',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        LegalPage::create($validated);

        return redirect()->route('admin.legal-pages.index')
            ->with('success', 'Halaman legal berhasil dibuat');
    }

    public function edit(LegalPage $legalPage)
    {
        return view('admin.legal-pages.form', ['page' => $legalPage]);
    }

    public function update(Request $request, LegalPage $legalPage)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:legal_pages,slug,' . $legalPage->id,
            'content' => 'required|string',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

        $legalPage->update($validated);

        return redirect()->route('admin.legal-pages.index')
            ->with('success', 'Halaman legal berhasil diperbarui');
    }

    public function destroy(LegalPage $legalPage)
    {
        $legalPage->delete();

        return redirect()->route('admin.legal-pages.index')
            ->with('success', 'Halaman legal berhasil dihapus');
    }
}
