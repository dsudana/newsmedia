<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ route('sitemap.static') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ route('sitemap.articles') }}</loc>
        <lastmod>{{ $sitemaps[1]['lastmod']?->toAtomString() ?? now()->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ route('sitemap.categories') }}</loc>
        <lastmod>{{ $sitemaps[2]['lastmod']?->toAtomString() ?? now()->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ route('sitemap.tags') }}</loc>
        <lastmod>{{ $sitemaps[3]['lastmod']?->toAtomString() ?? now()->toAtomString() }}</lastmod>
    </sitemap>
</sitemapindex>
