@php
    $locale = 'es';
    $altRoute = 'adventures';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', ['locale' => 'es', 'page' => 'caminatas', 'og_image' => '/img/tours/rainbow-mountain-vinicunca.jpg'])
@endsection

@section('contenido')
    <section class="page-banner page-banner--tall">
        <picture>
            <source media="(max-width: 768px)" srcset="{{ asset('img/responsive/rainbow-mountain-vinicunca-mobile.webp') }}" type="image/webp">
            <img src="{{ asset('img/tours/rainbow-mountain-vinicunca.jpg') }}" alt="Tours de aventura en Perú" data-parallax="10" loading="eager" fetchpriority="high" decoding="async">
        </picture>
        <div class="page-banner__content">
            <span class="page-banner__eyebrow">Naturaleza, Adrenalina y Confort VIP</span>
            <h1 class="page-banner__title">Tours de Aventura en Perú</h1>
            <div class="page-banner__divider"></div>
            <p class="hero__subtitle">
                Montaña de 7 Colores, Laguna Humantay, caminatas legendarias y paseos en cuatrimotos con equipo de rescate, oxígeno, guías expertos y servicio exclusivo.
            </p>
            <div class="hero__actions mt-md">
                <button type="button" class="btn-brand btn-brand--primary" data-contact-open>
                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                    <span>Planificar mi aventura</span>
                </button>
                <a href="#tours-coleccion" class="btn-brand btn-brand--outline">
                    <span>Ver aventuras</span>
                    <i class="fa fa-arrow-down" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>

    @include('layouts.partials.collection-trust-strip', ['locale' => 'es'])

    <section class="section section--sand" id="tours-coleccion" data-collection-container>
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">Tours Activos y Treks</span>
                <h2 class="section-title">Aventuras Exclusivas en los Andes</h2>
                <p class="section-lead">
                    Supera tus límites con la tranquilidad de contar con guías de montaña certificados, traslados privados y el máximo estándar de seguridad en ruta.
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
        'categoryName' => 'Aventuras en Perú',
    ])
@endsection
