@php
    $locale = 'en';
    $altRoute = 'alrededor-de-peru';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', ['locale' => 'en', 'page' => 'around', 'og_image' => '/img/Machu-Picchu-exclusive.jpg'])
@endsection

@section('contenido')
    <section class="page-banner page-banner--tall">
        <img src="{{ asset('img/andean_exclusive_tours_slide2.jpg') }}" alt="Tours around Peru" data-parallax="10" loading="eager" fetchpriority="high">
        <div class="page-banner__content">
            <h1 class="page-banner__title">Tours Around Perú</h1>
            <div class="page-banner__divider"></div>
            <span class="page-banner__eyebrow">Beyond the classics</span>
        </div>
    </section>

    <section class="section">
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">In high demand</span>
                <h2 class="section-title">Tours in demand around Perú</h2>
            </div>
            <div class="grid-auto" data-reveal-stagger>
                @foreach ($tours as $tour)
                    @include('layouts.partials.public-tour-card', [
                        'tour' => $tour,
                        'tourRoute' => 'tour.show',
                        'categoryRoute' => 'category.show',
                        'daysLabel' => 'days',
                        'moreLabel' => 'Read more',
                    ])
                @endforeach
            </div>
        </div>
    </section>
@endsection
