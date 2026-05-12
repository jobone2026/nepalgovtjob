<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($states as $state)
    <url>
        <loc>{{ url("/state/{$state->slug}") }}</loc>
        <lastmod>{{ $state->updated_at->toIso8601String() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.7</priority>
    </url>
@endforeach
</urlset>
