<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @forelse ($categories as $category)
        <url>
            <loc>{{ route('blog.category', $category->slug) }}</loc>
            <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    @empty
        {{-- No active categories --}}
    @endforelse
</urlset>
