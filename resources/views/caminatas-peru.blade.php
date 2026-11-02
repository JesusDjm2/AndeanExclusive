@php
    $locale = 'es';
    $altRoute = 'adventures';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', ['locale' => 'es', 'page' => 'caminatas', 'og_image' => '/img/Machu-Picchu-exclusive.jpg'])
@endsection

@section('contenido')
    <section class="page-banner page-banner--tall">
        <img src="{{ asset('img/climbing-peru-adventures-andean.jpg') }}" alt="Tours de aventura en Perú" data-parallax="10" loading="eager" fetchpriority="high">
        <div class="page-banner__content">
            <h1 class="page-banner__title">Tours de Aventura en Perú</h1>
            <div class="page-banner__divider"></div>
            <span class="page-banner__eyebrow">Que la adrenalina fluya</span>
        </div>
    </section>

    <section class="section">
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">En demanda</span>
                <h2 class="section-title">Tours de aventura más solicitados</h2>
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
