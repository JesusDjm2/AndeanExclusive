{{--
    Layout público unificado ES/EN. Cada página define $locale ('es' o 'en')
    antes de extender este layout; ver resources/views/about.blade.php.
    Todo el CSS/JS del sitio vive en resources/sass y resources/js (Vite):
    las vistas no llevan <style> ni style="" en línea.
--}}
@php
    $locale = $locale ?? 'es';
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="theme-color" content="#09ccbd">
    <link rel="icon" type="image/png" href="{{ asset('img/logoico.png') }}">

    @yield('metas')
    @include('layouts.partials.seo-jsonld')
    @stack('seo-structured-data')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&family=Dancing+Script:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TCY2R1G6QT"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-TCY2R1G6QT');
    </script>
</head>
<body>
    @if(!empty($hasPreloader))
        @include('layouts.partials.preloader', ['locale' => $locale])
    @endif

    @include('layouts.partials.public-header', ['locale' => $locale, 'altRoute' => $altRoute ?? null, 'altUrl' => $altUrl ?? null])

    <main>
        @yield('contenido')
    </main>

    @include('layouts.partials.public-footer', ['locale' => $locale])
    @include('layouts.partials.public-wasa')
    @include('layouts.partials.back-to-top', ['locale' => $locale])
    @include('layouts.partials.contact-modal', ['locale' => $locale])

    @stack('scripts')
</body>
</html>
