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
            <h1 class="page-banner__title">Tours Alrededor de Perú</h1>
            <div class="page-banner__divider"></div>
            <span class="page-banner__eyebrow">Más allá de lo clásico</span>
        </div>
    </section>

    <section class="section">
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">En demanda</span>
                <h2 class="section-title">Tours en demanda alrededor de Perú</h2>
            </div>
            <div class="grid-auto" data-reveal-stagger>
                @foreach ($tours as $tour)
                    @include('layouts.partials.public-tour-card', [
                        'tour' => $tour,
                        'tourRoute' => 'estour.show',
                        'categoryRoute' => 'categoria.show',
                        'daysLabel' => $tour->dias == 1 ? 'día' : 'días',
                        'moreLabel' => 'Leer más',
                    ])
                @endforeach
            </div>
        </div>
    </section>
@endsection
