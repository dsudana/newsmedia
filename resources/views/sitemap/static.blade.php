<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @forelse ($pages as $page)
        <url>
            <loc>{{ $page['url'] }}</loc>
            <changefreq>{{ $page['changefreq'] ?? 'monthly' }}</changefreq>
            <priority>{{ $page['priority'] ?? 0.5 }}</priority>
        </url>
    @empty
        {{-- No static pages --}}
    @endforelse
</urlset>
