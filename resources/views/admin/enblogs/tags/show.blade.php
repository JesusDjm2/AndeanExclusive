@php
    $locale = 'en';
    $tagMap = [
        'Magic-Places' => 'Lugares-mágicos',
        'Traditions' => 'Tradiciones',
        'History' => 'Historia',
        'Travel-Tips' => 'Tips-de-viaje',
        'Gastronomy' => 'Gastronomia',
        'Holidays' => 'Feriados',
        'Activities' => 'Actividades',
    ];
    $esSlug = $tagMap[$tag->slug] ?? null;
    $altUrl = $esSlug ? route('estag.show', $esSlug) : route('blog-es');
    $waText = 'Hello Andean Exclusive, I am exploring your articles about ' . $tag->nombre . ' and would like to design a bespoke journey in Peru.';
    $waUrl = 'https://wa.me/51979721194?text=' . rawurlencode($waText);

    $heroBlog = $blogs->first();
    $heroImage = $heroBlog ? $heroBlog->fullUrl() : asset('img/tours/Hiram-bingham.webp');
    $mobileHero = $heroBlog ? $heroBlog->mobileHeroUrl() : $heroImage;
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'en',
        'page' => null,
        'title' => $tag->nombre . ' - Stories & Travel Guides | ' . config('seo.brand'),
        'description' => 'Explore our curated Peru luxury travel stories and insights tagged under ' . $tag->nombre . '. Private tours, boutique stays, and cultural heritage.',
        'canonical' => route('entag.show', $tag->slug, true),
        'og_image' => $heroImage,
    ])
    @if ($esSlug)
        <link rel="alternate" hreflang="es" href="{{ route('estag.show', $esSlug, true) }}">
        <link rel="alternate" hreflang="en" href="{{ route('entag.show', $tag->slug, true) }}">
        <link rel="alternate" hreflang="x-default" href="{{ route('entag.show', $tag->slug, true) }}">
    @endif
@endsection

@push('seo-structured-data')
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => $tag->nombre . ' - Andean Exclusive Journal',
    'description' => 'Curated travel articles and guides tagged under ' . $tag->nombre . '.',
    'url' => route('entag.show', $tag->slug, true),
    'publisher' => [
        '@type' => 'Organization',
        'name' => config('seo.brand'),
        'logo' => [
            '@type' => 'ImageObject',
            'url' => rtrim(config('seo.site_url'), '/') . '/img/andean-exclusive-logo.png'
        ]
    ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('contenido')
    <section class="page-banner page-banner--tall">
        <picture>
            <source media="(max-width: 768px)" srcset="{{ $mobileHero }}">
            <img src="{{ $heroImage }}" alt="{{ $tag->nombre }}" data-parallax="10" loading="eager" fetchpriority="high" decoding="async">
        </picture>
        <div class="page-banner__content">
            <span class="page-banner__eyebrow font-display">Topic Collection</span>
            <h1 class="page-banner__title">#{{ $tag->nombre }}</h1>
            <div class="page-banner__divider"></div>
            <div class="tag-hero-meta">
                <span class="tag-hero-badge">
                    <i class="fa fa-folder-open-o" aria-hidden="true"></i>
                    {{ $blogs->count() }} {{ $blogs->count() === 1 ? 'Curated Story' : 'Curated Stories' }}
                </span>
                <span>
                    <i class="fa fa-compass" aria-hidden="true"></i>
                    Andean Exclusive Journal
                </span>
            </div>
        </div>
    </section>

    <section class="section--tight">
        <div class="site-container">
            @include('layouts.partials.breadcrumb', [
                'ariaLabel' => 'Breadcrumb',
                'crumbs' => [
                    ['label' => 'Home', 'url' => route('index')],
                    ['label' => 'Journal', 'url' => route('blog-en')],
                    ['label' => '#' . $tag->nombre],
                ],
            ])

            <div class="article-layout">
                <div>
                    @if ($blogs->count())
                        <div class="tag-stories-grid" data-reveal-stagger aria-label="{{ $tag->nombre }} articles">
                            @foreach ($blogs as $blog)
                                <article class="tag-story-card">
                                    <a href="{{ route('enblog.show', $blog->slug) }}" class="tag-story-card__media">
                                        <img src="{{ $blog->thumbUrl() }}" alt="{{ $blog->nombre }}" loading="lazy" decoding="async" class="tag-story-card__img">
                                        <span class="tag-story-card__reading">
                                            <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $blog->readingTime('en') }}
                                        </span>
                                    </a>
                                    <div class="tag-story-card__body">
                                        <div class="tag-story-card__meta">
                                            @if ($blog->created_at)
                                                <span><i class="fa fa-calendar-o" aria-hidden="true"></i> {{ $blog->created_at->format('M d, Y') }}</span>
                                            @endif
                                        </div>
                                        <h2 class="tag-story-card__title">
                                            <a href="{{ route('enblog.show', $blog->slug) }}">{{ $blog->nombre }}</a>
                                        </h2>
                                        <p class="tag-story-card__excerpt">{{ $blog->descripcionCorta }}</p>
                                        <div class="tag-story-card__footer">
                                            <a href="{{ route('enblog.show', $blog->slug) }}" class="tag-story-card__cta">
                                                <span>Read story</span>
                                                <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state text-center py-2xl">
                            <p class="section-lead">No stories published under this topic yet. Explore our other journal dispatches below.</p>
                            <a href="{{ route('blog-en') }}" class="btn-brand btn-brand--primary mt-md">Back to Journal</a>
                        </div>
                    @endif
                </div>

                {{-- Senior Sticky Sidebar Rail --}}
                <aside class="article-sidebar">
                    {{-- 1. Topic Cloud --}}
                    @if ($tags->count())
                        <div class="article-sidebar__box">
                            <h3 class="article-sidebar__title">Explore Topics</h3>
                            <div class="tag-cloud">
                                @foreach ($tags as $t)
                                    @php
                                        $isActive = ($t->id === $tag->id);
                                    @endphp
                                    <a href="{{ route('entag.show', $t->slug) }}" class="tag-pill {{ $isActive ? 'tag-pill--active' : 'tag-pill--muted' }}">
                                        #{{ $t->nombre }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 2. Compact Bespoke Journey CTA --}}
                    <div class="article-sidebar__cta-card">
                        <span class="article-sidebar__cta-eyebrow font-display">Bespoke Journeys</span>
                        <h4 class="article-sidebar__cta-title">Inspired by {{ $tag->nombre }}?</h4>
                        <p class="article-sidebar__cta-text">We weave every cultural theme and landscape into custom-tailored Peru itineraries with private guides and boutique luxury lodges.</p>
                        <div class="article-sidebar__cta-actions">
                            <button type="button" class="btn-brand btn-brand--primary btn-brand--sm btn-brand--block" data-contact-open>
                                <i class="fa fa-paper-plane" aria-hidden="true"></i> Plan Custom Trip
                            </button>
                            <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="btn-brand btn-brand--whatsapp btn-brand--sm btn-brand--block">
                                <i class="fa fa-whatsapp" aria-hidden="true"></i> WhatsApp Concierge
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @include('layouts.partials.collection-bottom-cta', [
        'locale' => 'en',
        'categoryName' => $tag->nombre,
    ])
@endsection
