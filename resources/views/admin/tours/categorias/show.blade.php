@php
    $locale = 'en';
    $catSlugLower = Str::slug($categoria->nombre ?? $categoria->slug ?? '');
    $categoryHeroMap = [
        'tours-exclusivos' => 'img/Machu-Picchu-exclusive.jpg',
        'exclusive-tours' => 'img/Machu-Picchu-exclusive.jpg',
        'alrededor-de-peru' => 'img/tours/valle-sagrado.jpg',
        'around-peru' => 'img/tours/valle-sagrado.jpg',
        'caminatas-y-aventuras' => 'img/tours/rainbow-mountain-vinicunca.jpg',
        'adventure-tours' => 'img/tours/rainbow-mountain-vinicunca.jpg',
        'hiking' => 'img/tours/Choquequirao-tour-4-days-trekking.jpg',
        'machu-picchu' => 'img/Machu-Picchu-exclusive.jpg',
        'tours-populares' => 'img/chincheros.jpg',
    ];
    $categoryHeroMobileMap = [
        'tours-exclusivos' => 'img/responsive/Machu-Picchu-exclusive-mobile.webp',
        'exclusive-tours' => 'img/responsive/Machu-Picchu-exclusive-mobile.webp',
        'alrededor-de-peru' => 'img/responsive/valle-sagrado-mobile.webp',
        'around-peru' => 'img/responsive/valle-sagrado-mobile.webp',
        'caminatas-y-aventuras' => 'img/responsive/rainbow-mountain-vinicunca-mobile.webp',
        'adventure-tours' => 'img/responsive/rainbow-mountain-vinicunca-mobile.webp',
        'hiking' => 'img/responsive/Choquequirao-tour-4-days-trekking-mobile.webp',
        'machu-picchu' => 'img/responsive/Machu-Picchu-exclusive-mobile.webp',
        'tours-populares' => 'img/responsive/chincheros-mobile.webp',
    ];
    $catHeroImg = $categoryHeroMap[$catSlugLower]
        ?? (!empty($tours->first()?->imgFull) ? $tours->first()->imgFull : 'img/Machu-Picchu-exclusive.jpg');
    $catHeroMobile = $categoryHeroMobileMap[$catSlugLower] ?? null;
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'en',
        'title' => $categoria->nombre . ' Tours | ' . config('seo.brand'),
        'description' => 'Browse ' . $categoria->nombre . ' luxury tours in Peru with Andean Exclusive Tours. Private bespoke itineraries crafted by local specialists.',
        'canonical' => route('category.show', $categoria->slug, true),
        'og_image' => asset($catHeroImg),
    ])
@endsection

@section('contenido')
    <section class="page-banner page-banner--tall">
        <picture>
            @if ($catHeroMobile)
                <source media="(max-width: 768px)" srcset="{{ asset($catHeroMobile) }}" type="image/webp">
            @endif
            <img src="{{ asset($catHeroImg) }}" alt="{{ $categoria->nombre }}" data-parallax="10" loading="eager" fetchpriority="high" decoding="async">
        </picture>
        <div class="page-banner__content">
            <span class="page-banner__eyebrow">Curated Collection</span>
            <h1 class="page-banner__title">{{ $categoria->nombre }}</h1>
            <div class="page-banner__divider"></div>
            <p class="hero__subtitle">
                Private bespoke journeys designed for seamless discovery across Peru with licensed historians, luxury properties, and dedicated care.
            </p>
            <div class="hero__actions mt-md">
                <button type="button" class="btn-brand btn-brand--primary" data-contact-open>
                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                    <span>Design my bespoke trip</span>
                </button>
                <a href="#tours-categoria" class="btn-brand btn-brand--outline">
                    <span>Explore itineraries</span>
                    <i class="fa fa-arrow-down" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>

    @include('layouts.partials.collection-trust-strip', ['locale' => 'en'])

    <section class="section section--sand" id="tours-categoria" data-collection-container>
        <div class="site-container">
            @if (isset($categorias) && $categorias->count() > 1)
                <nav class="collection-nav-pills" aria-label="Other travel categories">
                    @foreach ($categorias as $catItem)
                        <a href="{{ route('category.show', $catItem->slug) }}"
                           class="collection-nav-pill {{ $catItem->id === $categoria->id ? 'is-active' : '' }}">
                            <i class="fa fa-tag" aria-hidden="true"></i>
                            <span>{{ $catItem->nombre }}</span>
                        </a>
                    @endforeach
                </nav>
            @endif

            <div class="section-header">
                <span class="section-eyebrow font-display">Featured Journeys</span>
                <h2 class="section-title">{{ $categoria->nombre }} Journeys</h2>
                <p class="section-lead">
                    Select your preferred route or request a personalized proposal adjusted to your exact dates, group size, and travel pace.
                </p>
            </div>

            @if ($tours->count())
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
            @else
                <div class="collection-empty">
                    <div class="collection-empty__icon" aria-hidden="true">
                        <i class="fa fa-map-o"></i>
                    </div>
                    <h3 class="collection-empty__title">More {{ $categoria->nombre }} journeys coming soon</h3>
                    <p class="collection-empty__desc">
                        We are currently preparing new exclusive experiences for this collection. In the meantime, explore our signature journeys or ask our team for a 100% tailor-made route.
                    </p>
                    <div class="collection-empty__actions">
                        <button type="button" class="btn-brand btn-brand--primary" data-contact-open>
                            <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                            <span>Request tailor-made route</span>
                        </button>
                        <a href="{{ route('experiences') }}" class="btn-brand btn-brand--ghost">
                            <span>Browse all experiences</span>
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @include('layouts.partials.collection-bottom-cta', [
        'locale' => 'en',
        'categoryName' => $categoria->nombre,
    ])
@endsection
