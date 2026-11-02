@php
    $locale = 'en';
    $altRoute = 'caminatas';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', ['locale' => 'en', 'page' => 'adventures', 'og_image' => '/img/Machu-Picchu-exclusive.jpg'])
@endsection

@section('contenido')
    <section class="page-banner page-banner--tall">
        <img src="{{ asset('img/climbing-peru-adventures-andean.jpg') }}" alt="Adventure tours in Peru" data-parallax="10" loading="eager" fetchpriority="high">
        <div class="page-banner__content">
            <h1 class="page-banner__title">Adventure Tours in Perú</h1>
            <div class="page-banner__divider"></div>
            <span class="page-banner__eyebrow">Get your adrenaline going</span>
        </div>
    </section>

    <section class="section">
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">In high demand</span>
                <h2 class="section-title">Adventure Tours in demand in Perú</h2>
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
