@php
    $locale = 'es';
    $tagMap = [
        'Lugares-mágicos' => 'Magic-Places',
        'Tradiciones' => 'Traditions',
        'Historia' => 'History',
        'Tips-de-viaje' => 'Travel-Tips',
        'Gastronomia' => 'Gastronomy',
        'Feriados' => 'Holidays',
        'Actividades' => 'Activities',
        'Costumbres' => 'Traditions',
    ];
    $enSlug = $tagMap[$tag->slug] ?? null;
    $altUrl = $enSlug ? route('entag.show', $enSlug) : route('blog-en');
    $waText = 'Hola Andean Exclusive, estoy explorando sus artículos sobre ' . $tag->nombre . ' y me gustaría diseñar un viaje a medida en Perú.';
    $waUrl = 'https://wa.me/51979721194?text=' . rawurlencode($waText);

    $heroBlog = $blogs->first();
    $heroImage = $heroBlog ? $heroBlog->fullUrl() : asset('img/tours/Hiram-bingham.webp');
    $mobileHero = $heroBlog ? $heroBlog->mobileHeroUrl() : $heroImage;
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'es',
        'page' => null,
        'title' => $tag->nombre . ' - Crónicas y Guías de Viaje | ' . config('seo.brand'),
        'description' => 'Explora nuestros artículos exclusivos de viajes por Perú bajo el tema ' . $tag->nombre . '. Itinerarios privados, hoteles boutique y cultura andina.',
        'canonical' => route('estag.show', $tag->slug, true),
        'og_image' => $heroImage,
    ])
    @if ($enSlug)
        <link rel="alternate" hreflang="es" href="{{ route('estag.show', $tag->slug, true) }}">
        <link rel="alternate" hreflang="en" href="{{ route('entag.show', $enSlug, true) }}">
        <link rel="alternate" hreflang="x-default" href="{{ route('entag.show', $enSlug, true) }}">
    @endif
@endsection

@push('seo-structured-data')
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => $tag->nombre . ' - Diario Andean Exclusive',
    'description' => 'Artículos y guías de viaje seleccionadas sobre ' . $tag->nombre . '.',
    'url' => route('estag.show', $tag->slug, true),
    'inLanguage' => 'es-PE',
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
    @auth
        <a href="{{ route('estags.edit', $tag->id) }}" class="tour-admin-edit" target="_blank" title="Editar este tag en el panel">
            <i class="fa fa-pencil" aria-hidden="true"></i> Editar tag
        </a>
    @endauth

    <section class="page-banner page-banner--tall">
        <picture>
            <source media="(max-width: 768px)" srcset="{{ $mobileHero }}">
            <img src="{{ $heroImage }}" alt="{{ $tag->nombre }}" data-parallax="10" loading="eager" fetchpriority="high" decoding="async">
        </picture>
        <div class="page-banner__content">
            <span class="page-banner__eyebrow font-display">Colección Temática</span>
            <h1 class="page-banner__title">#{{ $tag->nombre }}</h1>
            <div class="page-banner__divider"></div>
            <div class="tag-hero-meta">
                <span class="tag-hero-badge">
                    <i class="fa fa-folder-open-o" aria-hidden="true"></i>
                    {{ $blogs->count() }} {{ $blogs->count() === 1 ? 'artículo seleccionado' : 'artículos seleccionados' }}
                </span>
                <span>
                    <i class="fa fa-compass" aria-hidden="true"></i>
                    Diario Andean Exclusive
                </span>
            </div>
        </div>
    </section>

    <section class="section--tight">
        <div class="site-container">
            @include('layouts.partials.breadcrumb', [
                'ariaLabel' => 'Ruta de navegación',
                'crumbs' => [
                    ['label' => 'Inicio', 'url' => route('inicio')],
                    ['label' => 'Blog', 'url' => route('blog-es')],
                    ['label' => '#' . $tag->nombre],
                ],
            ])

            <div class="article-layout">
                <div>
                    @if ($blogs->count())
                        <div class="tag-stories-grid" data-reveal-stagger aria-label="Artículos sobre {{ $tag->nombre }}">
                            @foreach ($blogs as $blog)
                                <article class="tag-story-card">
                                    <a href="{{ route('esblog.show', $blog->slug) }}" class="tag-story-card__media">
                                        <img src="{{ $blog->thumbUrl() }}" alt="{{ $blog->nombre }}" loading="lazy" decoding="async" class="tag-story-card__img">
                                        <span class="tag-story-card__reading">
                                            <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $blog->readingTime('es') }}
                                        </span>
                                    </a>
                                    <div class="tag-story-card__body">
                                        <div class="tag-story-card__meta">
                                            @if ($blog->created_at)
                                                <span><i class="fa fa-calendar-o" aria-hidden="true"></i> {{ $blog->created_at->format('d/m/Y') }}</span>
                                            @endif
                                        </div>
                                        <h2 class="tag-story-card__title">
                                            <a href="{{ route('esblog.show', $blog->slug) }}">{{ $blog->nombre }}</a>
                                        </h2>
                                        <p class="tag-story-card__excerpt">{{ $blog->descripcionCorta }}</p>
                                        <div class="tag-story-card__footer">
                                            <a href="{{ route('esblog.show', $blog->slug) }}" class="tag-story-card__cta">
                                                <span>Leer artículo</span>
                                                <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state text-center py-2xl">
                            <p class="section-lead">Aún no hay artículos publicados bajo este tema. Te invitamos a explorar las demás historias de nuestro diario.</p>
                            <a href="{{ route('blog-es') }}" class="btn-brand btn-brand--primary mt-md">Volver al Blog</a>
                        </div>
                    @endif
                </div>

                {{-- Barra Lateral Sticky de Alta Gama --}}
                <aside class="article-sidebar">
                    {{-- 1. Nube de Temas --}}
                    @if ($tags->count())
                        <div class="article-sidebar__box">
                            <h3 class="article-sidebar__title">Explorar Temas</h3>
                            <div class="tag-cloud">
                                @foreach ($tags as $t)
                                    @php
                                        $isActive = ($t->id === $tag->id);
                                    @endphp
                                    <a href="{{ route('estag.show', $t->slug) }}" class="tag-pill {{ $isActive ? 'tag-pill--active' : 'tag-pill--muted' }}">
                                        #{{ $t->nombre }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 2. Llamada a la Acción Compacta --}}
                    <div class="article-sidebar__cta-card">
                        <span class="article-sidebar__cta-eyebrow font-display">Viajes a Medida</span>
                        <h4 class="article-sidebar__cta-title">¿Inspirado por {{ $tag->nombre }}?</h4>
                        <p class="article-sidebar__cta-text">Integramos las vivencias culturales y paisajes de esta temática en un itinerario privado hecho exclusivamente para ti.</p>
                        <div class="article-sidebar__cta-actions">
                            <button type="button" class="btn-brand btn-brand--primary btn-brand--sm btn-brand--block" data-contact-open>
                                <i class="fa fa-paper-plane" aria-hidden="true"></i> Planificar Mi Viaje
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
        'locale' => 'es',
        'categoryName' => $tag->nombre,
    ])
@endsection
