@php
    $site = rtrim(config('seo.site_url'), '/');
    $brand = config('seo.brand');
    $addr = config('seo.address', []);
    $geo = config('seo.geo', []);
    $logo = $site . '/img/andean-exclusive-logo.png';
    $graph = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'TravelAgency',
                '@id' => $site . '/#organization',
                'name' => $brand,
                'url' => $site . '/',
                'logo' => ['@type' => 'ImageObject', 'url' => $logo],
                'image' => $logo,
                'telephone' => config('seo.phone'),
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $addr['streetAddress'] ?? '',
                    'addressLocality' => $addr['addressLocality'] ?? 'Cusco',
                    'addressRegion' => $addr['addressRegion'] ?? 'Cusco',
                    'postalCode' => $addr['postalCode'] ?? '',
                    'addressCountry' => $addr['addressCountry'] ?? 'PE',
                ],
                'geo' => [
                    '@type' => 'GeoCoordinates',
                    'latitude' => $geo['latitude'] ?? -13.5319,
                    'longitude' => $geo['longitude'] ?? -71.9675,
                ],
                'sameAs' => array_values(array_filter(config('seo.same_as', []))),
            ],
            [
                '@type' => 'WebSite',
                '@id' => $site . '/#website',
                'url' => $site . '/',
                'name' => $brand,
                'publisher' => ['@id' => $site . '/#organization'],
                'inLanguage' => ['en-US', 'es-PE'],
            ],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
