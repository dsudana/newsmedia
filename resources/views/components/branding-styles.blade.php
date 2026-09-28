@php
    use App\Models\SiteSetting;

    // Color Settings (hanya warna yang berubah dari setting)
    $primaryColor = SiteSetting::get('primary_color', '#dc2626');
    $secondaryColor = SiteSetting::get('secondary_color', '#1f2937');
    $footerBgColor = SiteSetting::get('footer_bg_color', '#0f172a');
    $footerTextColor = SiteSetting::get('footer_text_color', '#f3f4f6');

    // Font Settings (opsional, untuk font custom)
    $bodyFont = SiteSetting::get('body_font', "'Inter', -apple-system, BlinkMacSystemFont, sans-serif");
    $headingFont = SiteSetting::get(
        'heading_font',
        "'Inter', -apple-system, BlinkMacSystemFont, sans-serif",
    );

    // Logo & Favicon
    $logoUrl = SiteSetting::get('logo_url');
    $faviconUrl = SiteSetting::get('favicon_url');
@endphp

<style>
    /* Color Variables - HANYA WARNA YANG BERUBAH */
    :root {
        --primary-color: {{ $primaryColor }};
        --secondary-color: {{ $secondaryColor }};
        --footer-bg-color: {{ $footerBgColor }};
        --footer-text-color: {{ $footerTextColor }};
    }

    * {
        --primary-color: {{ $primaryColor }};
        --secondary-color: {{ $secondaryColor }};
        --footer-bg-color: {{ $footerBgColor }};
        --footer-text-color: {{ $footerTextColor }};
    }

    /* Font Default - TIDAK BERUBAH DARI SETTING */
    body {
        font-family: {{ $bodyFont }};
        /* Warna teks default tetap gelap agar terlihat */
        color: #1f2937;
    }

    h1 {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        color: #1f2937;
    }

    h2,
    h3,
    h4,
    h5,
    h6,
    .heading {
        font-family: {{ $headingFont }};
        /* Warna heading default tetap gelap */
        color: #1f2937;
    }

    p,
    span,
    li,
    a {
        /* Pastikan teks default tetap terbaca */
        color: inherit;
    }

    /* Link Styles - Hanya warna primary yang berubah */
    a,
    .link {
        color: var(--primary-color);
    }

    a:hover,
    .link:hover {
        color: #111827;
        text-decoration: underline;
    }

    /* Button Styles */
    .btn-primary,
    .bg-primary,
    button[type="submit"],
    .badge-primary {
        background-color: var(--primary-color) !important;
        color: white !important;
    }

    .btn-primary:hover,
    button[type="submit"]:hover {
        filter: brightness(0.9);
    }

    /* Text Color Classes */
    .text-primary {
        color: var(--primary-color) !important;
    }

    .border-primary {
        border-color: var(--primary-color) !important;
    }

    .text-secondary {
        color: var(--secondary-color) !important;
    }

    .border-secondary {
        border-color: var(--secondary-color) !important;
    }

    .bg-secondary {
        background-color: var(--secondary-color) !important;
        color: white !important;
    }

    /* Footer Dark Styles - Warna footer sesuai setting */
    footer {
        background-color: var(--footer-bg-color);
        color: var(--footer-text-color);
    }

    footer p,
    footer span,
    footer li {
        color: var(--footer-text-color);
    }

    footer a {
        color: var(--footer-text-color);
    }

    footer a:hover {
        color: var(--primary-color);
    }

    footer h3 {
        color: var(--footer-text-color);
    }

    footer .text-white\/70 {
        color: rgba(243, 244, 246, 0.7);
    }

    footer input,
    footer textarea {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.2);
        color: var(--footer-text-color);
    }

    footer input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    /* Article Body Styling */
    .article-body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        font-size: 20px;
        line-height: 1.7;
        color: #1f2937;
    }

    .article-body p {
        margin-bottom: 1rem;
        font-size: 18px;
    }

    .article-body h2 {
        font-size: 24px;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }

    .article-body h3 {
        font-size: 20px;
        margin-top: 1.25rem;
        margin-bottom: 0.75rem;
    }

    .article-body ul,
    .article-body ol {
        margin-bottom: 1rem;
        padding-left: 2rem;
    }

    .article-body li {
        margin-bottom: 0.5rem;
        font-size: 18px;
    }

    .article-body blockquote {
        border-left: 4px solid var(--primary-color);
        padding-left: 1rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #4b5563;
    }

    .article-body a {
        color: var(--primary-color);
        text-decoration: underline;
    }

    .article-body a:hover {
        color: #111827;
    }

    .article-body img {
        max-width: 100%;
        height: auto;
        margin: 1.5rem 0;
        border-radius: 0.5rem;
    }

    .article-body code {
        background-color: #f3f4f6;
        padding: 0.2rem 0.4rem;
        border-radius: 0.25rem;
        font-family: 'Courier New', monospace;
        font-size: 16px;
    }

    .article-body pre {
        background-color: #1f2937;
        color: #f3f4f6;
        padding: 1rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        margin: 1.5rem 0;
    }

    .article-body pre code {
        background-color: transparent;
        color: #f3f4f6;
        padding: 0;
    }
</style>

@if ($faviconUrl ?? false)
    <link rel="icon" href="{{ asset('storage/' . $faviconUrl) }}" type="image/x-icon">
@endif
