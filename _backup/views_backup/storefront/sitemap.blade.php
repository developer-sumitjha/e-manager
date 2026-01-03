<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('storefront.preview', $tenant->subdomain) }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @foreach($categories as $cat)
    <url>
        <loc>{{ route('storefront.category', [$tenant->subdomain, $cat->slug]) }}</loc>
        <lastmod>{{ $cat->updated_at?->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
    @foreach($products as $p)
    <url>
        <loc>{{ route('storefront.product', [$tenant->subdomain, $p->slug]) }}</loc>
        <lastmod>{{ $p->updated_at?->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
</urlset>




