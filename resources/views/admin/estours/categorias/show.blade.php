@php
    $locale = 'es';
    $catSlugLower = Str::slug($categoria->nombre ?? $categoria->slug ?? '');
    $categoryHeroMap = [
        'tours-exclusivos' => 'img/Machu-Picchu-exclusive.jpg',
        'exclusive-tours' => 'img/Machu-Picchu-exclusive.jpg',
        'alrededor-de-peru' => 'img/tours/valle-sagrado.jpg',
        'around-peru' => 'img/tours/valle-sagrado.jpg',
        'caminatas-y-aventuras' => 'img/tours/rainbow-mountain-vinicunca.jpg',
        'adventure-tours' => 'img/tours/rainbow-mountain-vinicunca.jpg',
        'hiking' => 'img/tours/Choquequirao-tour-4-days-trekking.jpg',
        'machu-picchu' => 'img/Machu-Picchu-exclusive.jpg',
        'tours-populares' => 'img/chincheros.jpg',
    ];
    $categoryHeroMobileMap = [
        'tours-exclusivos' => 'img/responsive/Machu-Picchu-exclusive-mobile.webp',
        'exclusive-tours' => 'img/responsive/Machu-Picchu-exclusive-mobile.webp',
        'alrededor-de-peru' => 'img/responsive/valle-sagrado-mobile.webp',
        'around-peru' => 'img/responsive/valle-sagrado-mobile.webp',
        'caminatas-y-aventuras' => 'img/responsive/rainbow-mountain-vinicunca-mobile.webp',
        'adventure-tours' => 'img/responsive/rainbow-mountain-vinicunca-mobile.webp',
        'hiking' => 'img/responsive/Choquequirao-tour-4-days-trekking-mobile.webp',
        'machu-picchu' => 'img/responsive/Machu-Picchu-exclusive-mobile.webp',
        'tours-populares' => 'img/responsive/chincheros-mobile.webp',
    ];
    $catHeroImg = $categoryHeroMap[$catSlugLower]
        ?? (!empty($tours->first()?->imgFull) ? $tours->first()->imgFull : 'img/Machu-Picchu-exclusive.jpg');
    $catHeroMobile = $categoryHeroMobileMap[$catSlugLower] ?? null;
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'es',
        'title' => 'Tours de ' . $categoria->nombre . ' | ' . config('seo.brand'),
        'description' => 'Descubre tours de ' . $categoria->nombre . ' en Perú con Andean Exclusive Tours. Experiencias privadas y de lujo hechas a medida.',
        'canonical' => route('categoria.show', $categoria->slug, true),
        'og_image' => asset($catHeroImg),
    ])
@endsection

@section('contenido')
    <section class="page-banner page-banner--tall">
        <picture>
            @if ($catHeroMobile)
                <source media="(max-width: 768px)" srcset="{{ asset($catHeroMobile) }}" type="image/webp">
            @endif
            <img src="{{ asset($catHeroImg) }}" alt="{{ $categoria->nombre }}" data-parallax="10" loading="eager" fetchpriority="high" decoding="async">
        </picture>
        <div class="page-banner__content">
            <span class="page-banner__eyebrow">Colección Especializada</span>
            <h1 class="page-banner__title">{{ $categoria->nombre }}</h1>
            <div class="page-banner__divider"></div>
            <p class="hero__subtitle">
                Itinerarios privados y de lujo creados para que descubras lo mejor de Perú con total comodidad, guías certificados y atención exclusiva.
            </p>
            <div class="hero__actions mt-md">
                <button type="button" class="btn-brand btn-brand--primary" data-contact-open>
                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                    <span>Diseñar viaje a medida</span>
                </button>
                <a href="#tours-categoria" class="btn-brand btn-brand--outline">
                    <span>Ver itinerarios</span>
                    <i class="fa fa-arrow-down" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>

    @include('layouts.partials.collection-trust-strip', ['locale' => 'es'])

    <section class="section section--sand" id="tours-categoria" data-collection-container>
        <div class="site-container">
            @if (isset($categorias) && $categorias->count() > 1)
                <nav class="collection-nav-pills" aria-label="Otras categorías de viaje">
                    @foreach ($categorias as $catItem)
                        <a href="{{ route('categoria.show', $catItem->slug) }}"
                           class="collection-nav-pill {{ $catItem->id === $categoria->id ? 'is-active' : '' }}">
                            <i class="fa fa-tag" aria-hidden="true"></i>
                            <span>{{ $catItem->nombre }}</span>
                        </a>
                    @endforeach
                </nav>
            @endif

            <div class="section-header">
                <span class="section-eyebrow font-display">Tours Seleccionados</span>
                <h2 class="section-title">Experiencias en {{ $categoria->nombre }}</h2>
                <p class="section-lead">
                    Selecciona tu itinerario favorito o solicita una adaptación personalizada a tus fechas y ritmo preferido.
                </p>
            </div>

            @if ($tours->count())
                @include('layouts.partials.collection-controls', [
                    'locale' => 'es',
                    'totalTours' => $tours->count(),
                ])

                <div class="grid-auto" data-reveal-stagger>
                    @foreach ($tours as $tour)
                        @include('layouts.partials.public-tour-card', [
                            'tour' => $tour,
                            'tourRoute' => 'estour.show',
                            'categoryRoute' => 'categoria.show',
                            'locale' => 'es',
                        ])
                    @endforeach
                </div>
            @else
                <div class="collection-empty">
                    <div class="collection-empty__icon" aria-hidden="true">
                        <i class="fa fa-map-o"></i>
                    </div>
                    <h3 class="collection-empty__title">Próximamente más experiencias en {{ $categoria->nombre }}</h3>
                    <p class="collection-empty__desc">
                        Estamos preparando nuevas propuestas exclusivas para esta categoría. Mientras tanto, puedes explorar nuestras experiencias más solicitadas o pedir un viaje 100% a medida.
                    </p>
                    <div class="collection-empty__actions">
                        <button type="button" class="btn-brand btn-brand--primary" data-contact-open>
                            <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                            <span>Diseñar viaje a medida</span>
                        </button>
                        <a href="{{ route('experiencias') }}" class="btn-brand btn-brand--ghost">
                            <span>Ver todas las experiencias</span>
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @include('layouts.partials.collection-bottom-cta', [
        'locale' => 'es',
        'categoryName' => $categoria->nombre,
    ])
@endsection
