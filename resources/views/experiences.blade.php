@php
    $locale = 'en';
    $altRoute = 'experiencias';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', ['locale' => 'en', 'page' => 'experiences', 'og_image' => '/img/Machu-Picchu-exclusive.jpg'])
@endsection

@section('contenido')
    <section class="page-banner page-banner--tall">
        <img src="{{ asset('img/hermoso-peru.jpg') }}" alt="Exclusive private tours in Peru" data-parallax="10" loading="eager" fetchpriority="high">
        <div class="page-banner__content">
            <span class="page-banner__eyebrow">Signature Luxury & Private Journeys</span>
            <h1 class="page-banner__title">Exclusive Tours in Perú</h1>
            <div class="page-banner__divider"></div>
            <p class="hero__subtitle">
                Handcrafted itineraries for discerning travellers seeking absolute privacy, authentic cultural depth, and world-class comfort across Cusco, Machu Picchu and beyond.
            </p>
            <div class="hero__actions mt-md">
                <button type="button" class="btn-brand btn-brand--primary" data-contact-open>
                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                    <span>Design my bespoke trip</span>
                </button>
                <a href="#tours-coleccion" class="btn-brand btn-brand--outline">
                    <span>Explore journeys</span>
                    <i class="fa fa-arrow-down" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>

    @include('layouts.partials.collection-trust-strip', ['locale' => 'en'])

    <section class="section section--sand" id="tours-coleccion" data-collection-container>
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">Curated Collection</span>
                <h2 class="section-title">Most Requested Exclusive Experiences</h2>
                <p class="section-lead">
                    Every itinerary features VIP access, licensed private historians, premium transport, and handpicked luxury boutique hotels.
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
        'categoryName' => 'Exclusive Tours',
    ])
@endsection
