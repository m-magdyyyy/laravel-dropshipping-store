<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
    <channel>
        <title>{{ config('app.name', 'فكره استور') }}</title>
        <link>{{ url('/') }}</link>
        <description>منتجات {{ config('app.name', 'فكره استور') }}</description>
@foreach($products as $product)
        <item>
            <g:id>{{ $product->sku ?? 'PROD-' . $product->id }}</g:id>
            <g:title><![CDATA[{{ Str::limit($product->name, 150) }}]]></g:title>
            <g:description><![CDATA[{{ Str::limit(strip_tags($product->description), 5000) }}]]></g:description>
            <g:link>{{ route('product.show', $product->slug) }}</g:link>
            <g:image_link>{{ $product->image_url ? (Str::startsWith($product->image_url, 'http') ? $product->image_url : url($product->image_url)) : url(Storage::url($product->image ?? 'default.jpg')) }}</g:image_link>
            <g:availability>{{ ($product->quantity ?? 1) > 0 ? 'in stock' : 'out of stock' }}</g:availability>
            <g:price>{{ number_format($product->price, 2, '.', '') }} EGP</g:price>
            <g:brand>فكره استور</g:brand>
            <g:condition>new</g:condition>
@if(!empty($product->gtin))
            <g:gtin>{{ $product->gtin }}</g:gtin>
@endif
        </item>
@endforeach
    </channel>
</rss>
