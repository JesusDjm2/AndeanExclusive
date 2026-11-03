@php
    $locale = 'es';
    $altRoute = 'around';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', ['locale' => 'es', 'page' => 'alrededor', 'og_image' => '/img/Machu-Picchu-exclusive.jpg'])
@endsection

@section('contenido')
    <section class="page-banner page-banner--tall">
        <img src="{{ asset('img/andean_exclusive_tours_slide2.jpg') }}" alt="Tours alrededor de Perú" data-parallax="10" loading="eager" fetchpriority="high">
        <div class="page-banner__content">
            <span class="page-banner__eyebrow">Circuitos Multi-Destino Inolvidables</span>
            <h1 class="page-banner__title">Tours Alrededor de Perú</h1>
            <div class="page-banner__divider"></div>
            <p class="hero__subtitle">
                Desde los desiertos costeros de Paracas y Nazca hasta la majestuosidad de los Andes y la selva amazónica. Rutas integrales con logística impecable y confort absoluto.
            </p>
            <div class="hero__actions mt-md">
                <button type="button" class="btn-brand btn-brand--primary" data-contact-open>
                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                    <span>Personalizar mi circuito</span>
                </button>
                <a href="#tours-coleccion" class="btn-brand btn-brand--outline">
                    <span>Ver circuitos</span>
                    <i class="fa fa-arrow-down" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>

    @include('layouts.partials.collection-trust-strip', ['locale' => 'es'])

    <section class="section section--sand" id="tours-coleccion" data-collection-container>
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">Rutas Completas</span>
                <h2 class="section-title">Grandes Viajes por el Perú</h2>
                <p class="section-lead">
                    Conecta Lima, Arequipa, el Cañón del Colca, Lago Titicaca, Cusco y la Amazonía con traslados privados y asesoría personalizada de principio a fin.
                </p>
            </div>

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
        </div>
    </section>

    @include('layouts.partials.collection-bottom-cta', [
        'locale' => 'es',
        'categoryName' => 'Tours en Perú',
    ])
@endsection
