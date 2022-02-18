<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($categories as $category)
    <url>
        <loc>{{ URL::route("categoryDetail", [$category->slug]) }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime($category->updated_at)) }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
@endforeach

{{--@foreach($vendors as $vendor)
    <url>
        <loc>{{ URL::route("patients.show", [$vendor->id]) }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime($vendor->updated_at)) }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
@endforeach

@foreach($products as $product)
    <url>
        <loc>{{ URL::route("patients.show", [$product->id]) }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime($vendor->updated_at)) }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
@endforeach--}}
</urlset>