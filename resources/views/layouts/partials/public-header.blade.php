{{--
    Cabecera pública unificada ES/EN. Reemplaza el header duplicado inline en
    cada una de las ~20 páginas principales y evoluciona header-public-es.blade.php.

    @param string $locale 'es'|'en'
    @param string|null $altRoute Nombre de ruta (sin parametros) de la pagina equivalente en el otro idioma.
    @param string|null $altUrl URL completa ya resuelta hacia la pagina equivalente (para rutas con parametros,
           p.ej. tours/blogs: route('esblog.show', $esblog->slug)). Tiene prioridad sobre $altRoute.
--}}
@php
    $homeRoute = $locale === 'es' ? 'inicio' : 'index';
    $otherHomeRoute = $locale === 'es' ? 'index' : 'inicio';
    $otherLangUrl = $altUrl ?? ($altRoute ? route($altRoute) : route($otherHomeRoute));
    $otherLangCode = $locale === 'es' ? 'EN' : 'ES';
    $contactPhone = '+51 921 136 755';
@endphp
<header class="site-header" data-header>
    <div class="site-header__inner">
        <a href="{{ route($homeRoute) }}" class="site-header__logo" rel="home">
            <img src="{{ asset('img/Logo-blanco-andean.png') }}" alt="Andean Exclusive Tours" width="180" height="48">
        </a>

        <nav class="site-nav" aria-label="{{ $locale === 'es' ? 'Navegación principal' : 'Main navigation' }}">
            @include('layouts.partials.public-nav-links', ['locale' => $locale])
        </nav>

        <div class="site-header__actions">
            <a href="tel:{{ str_replace(' ', '', $contactPhone) }}" class="site-header__phone">
                <i class="fa fa-phone" aria-hidden="true"></i> {{ $contactPhone }}
            </a>
            <a href="{{ $otherLangUrl }}" class="site-header__lang">{{ $otherLangCode }}</a>
            <button type="button" class="site-header__toggle" data-nav-toggle aria-label="{{ $locale === 'es' ? 'Abrir menú' : 'Open menu' }}">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</header>

<div class="mobile-nav" data-nav-panel aria-hidden="true">
    <div class="mobile-nav__head">
        <a href="{{ route($homeRoute) }}" class="mobile-nav__logo">
            <img src="{{ asset('img/Logo-blanco-andean.png') }}" alt="Andean Exclusive Tours" width="150" height="40">
        </a>
        <button type="button" class="mobile-nav__close" data-nav-close aria-label="{{ $locale === 'es' ? 'Cerrar menú' : 'Close menu' }}">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>

    <span class="mobile-nav__eyebrow font-display">{{ $locale === 'es' ? 'Explora el Perú' : 'Explore Peru' }}</span>

    <nav class="mobile-nav__links" aria-label="{{ $locale === 'es' ? 'Navegación principal' : 'Main navigation' }}">
        @include('layouts.partials.public-nav-links', ['locale' => $locale])
    </nav>

    <div class="mobile-nav__foot">
        <a href="{{ $otherLangUrl }}" class="mobile-nav__lang">{{ $otherLangCode }}</a>
        <a href="https://bit.ly/3kYXpXr" target="_blank" rel="noopener" class="btn-brand btn-brand--primary mobile-nav__whatsapp">
            <i class="fa fa-whatsapp" aria-hidden="true"></i> WhatsApp
        </a>
        <a href="tel:{{ str_replace(' ', '', $contactPhone) }}" class="mobile-nav__phone">
            <i class="fa fa-phone" aria-hidden="true"></i> {{ $contactPhone }}
        </a>
        <div class="mobile-nav__social">
            <a href="https://www.facebook.com/AndeanExclusiveTours" target="_blank" rel="nofollow noopener" aria-label="Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
            <a href="https://twitter.com/AndeanExclusive" target="_blank" rel="nofollow noopener" aria-label="Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
            <a href="https://www.instagram.com/andean.exclusive/" target="_blank" rel="nofollow noopener" aria-label="Instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>
            <a href="https://www.pinterest.com/andeanexclusive/" target="_blank" rel="nofollow noopener" aria-label="Pinterest"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
        </div>
        <p class="mobile-nav__address">Balconcillo Alto C-6, Cusco — Perú</p>
    </div>
</div>
