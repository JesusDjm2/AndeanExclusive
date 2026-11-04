{{--
    Nosotros / About (EnEnlacesController@about, @nosotros).
    Contenido: resources/data/about.php · Textos de interfaz: lang/{en,es}/pages.php
--}}
@php
    $isEs = $locale === 'es';
    $altRoute = $isEs ? 'about' : 'nosotros';
    $content = (require resource_path('data/about.php'))[$locale];
    $t = fn (string $key) => __('pages.about.' . $key, [], $locale);
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', ['locale' => $locale, 'page' => $isEs ? 'nosotros' : 'about', 'og_image' => '/img/Fondos/cusco-ciudad-imperial.webp', 'og_type' => 'article'])
@endsection

@section('contenido')
    @include('pages.partials.page-hero', [
        'image' => 'img/Fondos/cusco-ciudad-imperial.webp',
        'alt' => $isEs ? 'Vista de la ciudad del Cusco' : 'View over the city of Cusco',
        'title' => $t('title'),
        'eyebrow' => $t('eyebrow'),
        'lead' => $t('lead'),
        'crumbs' => [
            ['label' => __('pages.home', [], $locale), 'url' => route($isEs ? 'inicio' : 'index')],
            ['label' => $t('title')],
        ],
    ])

    {{-- Intro --}}
    <section class="section">
        <div class="site-container about-intro" data-reveal>
            <div>
                <span class="section-eyebrow">{{ $t('intro_eyebrow') }}</span>
                <h2 class="section-title">{{ $t('intro_title') }}</h2>
            </div>
            <div>
                <p class="about-intro__text">{{ $content['intro'] }}</p>
                <ul class="about-highlights">
                    @foreach ($t('highlights') as $highlight)
                        <li><i class="fa fa-check" aria-hidden="true"></i> {{ $highlight }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    {{-- Historia: ciudad y forma de trabajo --}}
    <section class="section section--sand">
        <div class="site-container story">
            <div class="story-row" data-reveal>
                <figure class="story-row__media">
                    <img src="{{ asset('img/cusco-de-noche.jpg') }}" alt="{{ $t('img_city') }}" loading="lazy" decoding="async">
                </figure>
                <div class="story-row__body">
                    <span class="section-eyebrow">{{ $t('city_eyebrow') }}</span>
                    <h2 class="section-title section-title--sm">{{ $t('city_title') }}</h2>
                    <p>{{ $content['city'] }}</p>
                </div>
            </div>
            <div class="story-row story-row--reverse" data-reveal>
                <figure class="story-row__media">
                    <img src="{{ asset('img/Fondos/Luxury-picnic-lunch-in-Cusco-Andean-Exclusive-Tours-Travel-Agency.jpg') }}" alt="{{ $t('img_work') }}" loading="lazy" decoding="async">
                </figure>
                <div class="story-row__body">
                    <span class="section-eyebrow">{{ $t('work_eyebrow') }}</span>
                    <h2 class="section-title section-title--sm">{{ $t('work_title') }}</h2>
                    <p>{{ $content['work'] }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Misión, visión y valores --}}
    <section class="section">
        <div class="site-container">
            <div class="pillars" data-reveal-stagger>
                <article class="pillar">
                    <span class="pillar__icon"><i class="fa fa-compass" aria-hidden="true"></i></span>
                    <h2 class="pillar__title">{{ $t('mission') }}</h2>
                    <p>{{ $content['mission'] }}</p>
                </article>
                <article class="pillar">
                    <span class="pillar__icon"><i class="fa fa-eye" aria-hidden="true"></i></span>
                    <h2 class="pillar__title">{{ $t('vision') }}</h2>
                    <p>{{ $content['vision'] }}</p>
                </article>
            </div>

            <div class="values-figure" data-reveal>
                <h2 class="section-title section-title--sm">{{ $t('values') }}</h2>
                <img src="{{ asset('img/valores AET.png') }}" alt="{{ $t('values') }}" class="img-fluid" loading="lazy" decoding="async">
            </div>
        </div>
    </section>

    {{-- Equipo --}}
    <section class="section section--sand">
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow">{{ $t('team_eyebrow') }}</span>
                <h2 class="section-title">{{ $t('team_title') }}</h2>
            </div>
            <div class="team-grid" data-reveal-stagger>
                @foreach ($content['team'] as $member)
                    <article class="team-card">
                        <img src="{{ asset('img/staff/' . $member['img']) }}" alt="{{ $member['name'] }}" class="team-card__photo" width="108" height="108" loading="lazy" decoding="async">
                        <h3 class="team-card__name">{{ $member['name'] }}</h3>
                        <p class="team-card__role">{{ $member['role'] }}</p>
                        <p class="team-card__bio">{{ $member['bio'] }}</p>
                        <a href="mailto:{{ $member['email'] }}" class="team-card__email"><i class="fa fa-envelope-o" aria-hidden="true"></i> {{ $member['email'] }}</a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    @include('pages.partials.cta-band', ['locale' => $locale])
@endsection
