@php
    $locale = 'es';
    $altRoute = 'experiences';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', ['locale' => 'es', 'page' => 'experiencias', 'og_image' => '/img/Machu-Picchu-exclusive.jpg'])
@endsection

@section('contenido')
    <section class="page-banner page-banner--tall">
        <img src="{{ asset('img/hermoso-peru.jpg') }}" alt="Tours exclusivos y privados en Perú" data-parallax="10" loading="eager" fetchpriority="high">
        <div class="page-banner__content">
            <span class="page-banner__eyebrow">Experiencias Privadas de Alta Gama</span>
            <h1 class="page-banner__title">Tours Exclusivos en Perú</h1>
            <div class="page-banner__divider"></div>
            <p class="hero__subtitle">
                Itinerarios hechos a mano para viajeros que valoran la privacidad, el confort de clase mundial y la autenticidad cultural en Machu Picchu, Cusco y todo el Perú.
            </p>
            <div class="hero__actions mt-md">
                <button type="button" class="btn-brand btn-brand--primary" data-contact-open>
                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                    <span>Diseñar mi viaje exclusivo</span>
                </button>
                <a href="#tours-coleccion" class="btn-brand btn-brand--outline">
                    <span>Explorar itinerarios</span>
                    <i class="fa fa-arrow-down" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>

    @include('layouts.partials.collection-trust-strip', ['locale' => 'es'])

    <section class="section section--sand" id="tours-coleccion" data-collection-container>
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">Colección de Autor</span>
                <h2 class="section-title">Experiencias Exclusivas en Perú</h2>
                <p class="section-lead">
                    Cada viaje incluye accesos preferenciales, guías privados certificados, movilidad ejecutiva y hoteles boutique cuidadosamente seleccionados.
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
        'categoryName' => 'Tours Exclusivos',
    ])
@endsection
