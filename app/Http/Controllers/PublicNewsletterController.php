<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class PublicNewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email']);

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $validated['email']],
            ['is_active' => true]
        );

        if ($subscriber->wasRecentlyCreated) {
            return back()->with('newsletter_success', 'Terima kasih telah berlangganan newsletter kami!');
        }

        if (!$subscriber->is_active) {
            $subscriber->update(['is_active' => true, 'unsubscribed_at' => null]);
            return back()->with('newsletter_success', 'Anda berhasil berlangganan kembali!');
        }

        return back()->with('newsletter_success', 'Email Anda sudah terdaftar dalam newsletter kami');
    }

    public function unsubscribe($email)
    {
        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if ($subscriber) {
            $subscriber->update(['is_active' => false, 'unsubscribed_at' => now()]);
            return view('newsletter-unsubscribe', ['success' => true]);
        }

        return view('newsletter-unsubscribe', ['success' => false]);
    }
}
