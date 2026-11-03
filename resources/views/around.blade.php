@php
    $locale = 'en';
    $altRoute = 'alrededor-de-peru';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', ['locale' => 'en', 'page' => 'around', 'og_image' => '/img/tours/valle-sagrado.jpg'])
@endsection

@section('contenido')
    <section class="page-banner page-banner--tall">
        <picture>
            <source media="(max-width: 768px)" srcset="{{ asset('img/responsive/valle-sagrado-mobile.webp') }}" type="image/webp">
            <img src="{{ asset('img/tours/valle-sagrado.jpg') }}" alt="Tours around Peru" data-parallax="10" loading="eager" fetchpriority="high" decoding="async">
        </picture>
        <div class="page-banner__content">
            <span class="page-banner__eyebrow">Multi-Destination Grand Journeys</span>
            <h1 class="page-banner__title">Tours Around Perú</h1>
            <div class="page-banner__divider"></div>
            <p class="hero__subtitle">
                From the coastal desert of Paracas and Nazca to the heights of the Andes and the Amazon rainforest. Seamless logistics, VIP transport and pure comfort.
            </p>
            <div class="hero__actions mt-md">
                <button type="button" class="btn-brand btn-brand--primary" data-contact-open>
                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                    <span>Customize my Peru route</span>
                </button>
                <a href="#tours-coleccion" class="btn-brand btn-brand--outline">
                    <span>Explore circuits</span>
                    <i class="fa fa-arrow-down" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>

    @include('layouts.partials.collection-trust-strip', ['locale' => 'en'])

    <section class="section section--sand" id="tours-coleccion" data-collection-container>
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">In High Demand</span>
                <h2 class="section-title">Grand Circuits Around Perú</h2>
                <p class="section-lead">
                    Connect Lima, Arequipa, Colca Canyon, Lake Titicaca, Cusco and the Amazon with bespoke private arrangements and expert guidance.
                </p>
            </div>

            @include('layouts.partials.collection-controls', [
                'locale' => 'en',
                'totalTours' => $tours->count(),
            ])

            <div class="grid-auto" data-reveal-stagger>
                @foreach ($tours as $tour)
                    @include('layouts.partials.public-tour-card', [
                        'tour' => $tour,
                        'tourRoute' => 'tour.show',
                        'categoryRoute' => 'category.show',
                        'locale' => 'en',
                    ])
                @endforeach
            </div>
        </div>
    </section>

    @include('layouts.partials.collection-bottom-cta', [
        'locale' => 'en',
        'categoryName' => 'Around Peru',
    ])
@endsection
