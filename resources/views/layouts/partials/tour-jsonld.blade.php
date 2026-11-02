@php
    // schema.org TouristTrip for a tour detail page. $tour (Tour|Estour), $url (canonical), $locale ('en'|'es').
    $siteBase = rtrim(config('seo.site_url'), '/');
    $image = str_starts_with((string) $tour->imgFull, 'http') ? $tour->imgFull : $siteBase . '/' . ltrim((string) $tour->imgFull, '/');
    $tripLd = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'TouristTrip',
        'name' => $tour->nombre,
        'description' => \Illuminate\Support\Str::limit(strip_tags((string) $tour->descripcionCorta), 300),
        'url' => $url,
        'image' => $image,
        'inLanguage' => $locale === 'es' ? 'es-PE' : 'en-US',
        'itinerary' => $tour->recorrido ? ['@type' => 'Place', 'name' => $tour->recorrido] : null,
        'provider' => ['@id' => $siteBase . '/#organization'],
        'offers' => (float) $tour->precio > 0 ? [
            '@type' => 'Offer',
            'price' => number_format((float) $tour->precio, 2, '.', ''),
            'priceCurrency' => 'USD',
            'url' => $url,
            'availability' => 'https://schema.org/InStock',
        ] : null,
    ]);
@endphp
@push('seo-structured-data')
<script type="application/ld+json">{!! json_encode($tripLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush
