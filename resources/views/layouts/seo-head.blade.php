{{-- Uso: @include('layouts.seo-head', ['locale' => 'en', 'page' => 'home']) o pasar title, description, canonical, og_image, og_type. --}}
@php
    $siteUrl = rtrim(config('seo.site_url'), '/');
    $cfg = ($page ?? null) ? (config('seo.pages.' . $locale . '.' . $page) ?? []) : [];
    $brand = config('seo.brand', 'Andean Exclusive Tours');
    $defaultOg = $siteUrl . (config('seo.default_og_image') ?? '/img/machupicchu.png');
    $ogImagePath = $og_image ?? ($cfg['og_image'] ?? null);
    if ($ogImagePath && ! str_starts_with((string) $ogImagePath, 'http')) {
        $fullOg = str_starts_with((string) $ogImagePath, '/') ? $siteUrl . $ogImagePath : $siteUrl . '/' . ltrim((string) $ogImagePath, '/');
    } else {
        $fullOg = $ogImagePath ?: $defaultOg;
    }
    $metaTitle = trim($title ?? $cfg['title'] ?? $brand);
    $metaDesc = trim($description ?? $cfg['description'] ?? '');
    if ($metaDesc === '') {
        $metaDesc = $brand . ' — ' . ($locale === 'es' ? 'Agencia de viajes de lujo en Perú y Cusco.' : 'Luxury travel agency in Peru & Cusco.');
    }
    if (isset($cfg['route'])) {
        $canonicalDefault = route($cfg['route'], $route_params ?? [], true);
    } else {
        $canonicalDefault = url()->current();
    }
    $canonical = $canonical ?? $canonicalDefault;
    $ogType = $og_type ?? ($cfg['og_type'] ?? 'website');
    $metaKeywords = $keywords ?? ($cfg['keywords'] ?? null);
    $robots = $robots ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
    $twitter = $twitter_handle ?? '@AndeanExclusive';
@endphp
<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ Str::limit(strip_tags($metaDesc), 320, '') }}">
@if (! empty($metaKeywords))
    <meta name="keywords" content="{{ Str::limit($metaKeywords, 255, '') }}">
@endif
<meta name="robots" content="{{ $robots }}">
<meta name="author" content="{{ $author ?? $brand }}">
<link rel="canonical" href="{{ $canonical }}">
<meta property="og:site_name" content="{{ $brand }}">
<meta property="og:locale" content="{{ $locale === 'es' ? 'es_PE' : 'en_US' }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($metaDesc), 300, '') }}">
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:image" content="{{ $fullOg }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ Str::limit(strip_tags($metaDesc), 200, '') }}">
<meta name="twitter:image" content="{{ $fullOg }}">
@if (! empty($twitter) && str_starts_with((string) $twitter, '@'))
    <meta name="twitter:site" content="{{ $twitter }}">
@endif
@if (! empty($cfg['hreflang_es_route'] ?? null))
    <link rel="alternate" hreflang="es" href="{{ route($cfg['hreflang_es_route'], [], true) }}">
    <link rel="alternate" hreflang="en" href="{{ $canonical }}">
    <link rel="alternate" hreflang="x-default" href="{{ $canonical }}">
@endif
@if (! empty($cfg['hreflang_en_route'] ?? null))
    <link rel="alternate" hreflang="en" href="{{ route($cfg['hreflang_en_route'], [], true) }}">
    <link rel="alternate" hreflang="es" href="{{ $canonical }}">
    <link rel="alternate" hreflang="x-default" href="{{ route($cfg['hreflang_en_route'], [], true) }}">
@endif
