<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\NewsletterTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribers()
    {
        $subscribers = NewsletterSubscriber::latest()->paginate(20);
        $totalSubscribers = NewsletterSubscriber::count();
        $activeSubscribers = NewsletterSubscriber::active()->count();

        return view('admin.newsletter.subscribers', compact('subscribers', 'totalSubscribers', 'activeSubscribers'));
    }

    public function unsubscribe(NewsletterSubscriber $subscriber)
    {
        $subscriber->update(['is_active' => false, 'unsubscribed_at' => now()]);

        return redirect()->route('admin.newsletter.subscribers')
            ->with('success', 'Subscriber berhasil dinonaktifkan');
    }

    public function deleteSubscriber(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();

        return redirect()->route('admin.newsletter.subscribers')
            ->with('success', 'Subscriber berhasil dihapus');
    }

    public function templates()
    {
        $templates = NewsletterTemplate::withCount('sends')->latest()->paginate(20);

        return view('admin.newsletter.templates', compact('templates'));
    }

    public function createTemplate()
    {
        return view('admin.newsletter.create-template');
    }

    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'html_content' => 'required|string',
            'type' => 'required|in:manual,daily,weekly',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        NewsletterTemplate::create($validated);

        return redirect()->route('admin.newsletter.templates')
            ->with('success', 'Template newsletter berhasil dibuat');
    }

    public function editTemplate(NewsletterTemplate $template)
    {
        return view('admin.newsletter.edit-template', compact('template'));
    }

    public function updateTemplate(Request $request, NewsletterTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'html_content' => 'required|string',
            'type' => 'required|in:manual,daily,weekly',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $template->update($validated);

        return redirect()->route('admin.newsletter.templates')
            ->with('success', 'Template newsletter berhasil diperbarui');
    }

    public function deleteTemplate(NewsletterTemplate $template)
    {
        $template->delete();

        return redirect()->route('admin.newsletter.templates')
            ->with('success', 'Template newsletter berhasil dihapus');
    }
}
