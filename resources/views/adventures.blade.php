@php
    $locale = 'en';
    $altRoute = 'caminatas';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', ['locale' => 'en', 'page' => 'adventures', 'og_image' => '/img/tours/rainbow-mountain-vinicunca.jpg'])
@endsection

@section('contenido')
    <section class="page-banner page-banner--tall">
        <picture>
            <source media="(max-width: 768px)" srcset="{{ asset('img/responsive/rainbow-mountain-vinicunca-mobile.webp') }}" type="image/webp">
            <img src="{{ asset('img/tours/rainbow-mountain-vinicunca.jpg') }}" alt="Adventure tours in Peru" data-parallax="10" loading="eager" fetchpriority="high" decoding="async">
        </picture>
        <div class="page-banner__content">
            <span class="page-banner__eyebrow">Raw Nature, Adrenaline & Luxury Comfort</span>
            <h1 class="page-banner__title">Adventure Tours in Perú</h1>
            <div class="page-banner__divider"></div>
            <p class="hero__subtitle">
                Rainbow Mountain, Humantay Lake, ATV trails and high-Andean treks with premium safety protocols, oxygen support, private guides, and gourmet trail meals.
            </p>
            <div class="hero__actions mt-md">
                <button type="button" class="btn-brand btn-brand--primary" data-contact-open>
                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                    <span>Plan my bespoke adventure</span>
                </button>
                <a href="#tours-coleccion" class="btn-brand btn-brand--outline">
                    <span>Explore adventures</span>
                    <i class="fa fa-arrow-down" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>

    @include('layouts.partials.collection-trust-strip', ['locale' => 'en'])

    <section class="section section--sand" id="tours-coleccion" data-collection-container>
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">Active & Treks</span>
                <h2 class="section-title">Exclusive Andean Adventures</h2>
                <p class="section-lead">
                    Push your boundaries with the peace of mind of certified mountain experts, VIP logistics, and unmatched safety on every trail.
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
        'categoryName' => 'Adventures in Peru',
    ])
@endsection
