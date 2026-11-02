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
            <h1 class="page-banner__title">Exclusive — Private tours in Perú</h1>
            <div class="page-banner__divider"></div>
            <span class="page-banner__eyebrow">Tailored, private, unforgettable</span>
        </div>
    </section>

    <section class="section">
        <div class="site-container text-center" data-reveal>
            <h2 class="section-title">Exclusive tours in Perú</h2>
            <p class="section-lead max-w-prose mx-auto">
                If we are looking for a destination with the widest range of experiences possible, without a doubt
                more than one traveler would say: visit Peru! The wealth that this South American country possesses
                is difficult to surpass, giving us the opportunity to visit places as dry as the desert coasts of
                the south of the country, walk through glaciers in the Andean mountain range, navigate through the
                deep jungle that surrounds the Amazon River, visit ancient cities where lost civilizations resided,
                or simply taste one of the best valued cuisines on the planet.
            </p>
            <p class="section-lead max-w-prose mx-auto">
                Travelers look for destinations according to their needs — some seek cultural experiences, others
                gastronomic, and the most daring seek contact with nature. Peru offers this and much more, never
                disappoints and always forces you to return. Peru has the experience you were looking for. Every
                experience has a hidden wealth — find it!
            </p>
        </div>
    </section>

    <section class="section section--sand">
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">In high demand</span>
                <h2 class="section-title">Most Requested Experiences</h2>
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
