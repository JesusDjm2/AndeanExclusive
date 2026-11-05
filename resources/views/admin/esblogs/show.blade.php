@php
    $locale = 'es';
    $altUrl = $enblog ? route('enblog.show', $enblog->slug) : null;
    $waText = 'Hola Andean Exclusive, estoy leyendo "' . $blog->nombre . '" y me gustaría diseñar un viaje a medida en Perú.';
    $waUrl = 'https://wa.me/51979721194?text=' . rawurlencode($waText);
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'es',
        'page' => null,
        'title' => $blog->nombre . ' | ' . config('seo.brand'),
        'description' => Str::limit(strip_tags($blog->descripcionCorta ?: $blog->descripcion), 165),
        'canonical' => route('esblog.show', $blog->slug, true),
        'og_image' => $blog->thumbUrl(),
        'og_type' => 'article',
        'keywords' => $blog->keywords,
    ])
    @if ($enblog)
        <link rel="alternate" hreflang="en" href="{{ route('enblog.show', $enblog->slug, true) }}">
        <link rel="alternate" hreflang="es" href="{{ route('esblog.show', $blog->slug, true) }}">
        <link rel="alternate" hreflang="x-default" href="{{ route('enblog.show', $enblog->slug, true) }}">
    @endif
@endsection

@push('seo-structured-data')
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $blog->nombre,
    'description' => Str::limit(strip_tags($blog->descripcionCorta ?: $blog->descripcion), 300),
    'url' => route('esblog.show', $blog->slug, true),
    'image' => [$blog->thumbUrl()],
    'datePublished' => $blog->created_at?->toIso8601String(),
    'dateModified' => $blog->updated_at?->toIso8601String(),
    'author' => ['@type' => 'Organization', 'name' => config('seo.brand')],
    'publisher' => ['@type' => 'Organization', 'name' => config('seo.brand'), 'logo' => ['@type' => 'ImageObject', 'url' => rtrim(config('seo.site_url'), '/') . '/img/andean-exclusive-logo.png']],
    'inLanguage' => 'es-PE',
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('contenido')
    @auth
        <a href="{{ route('esblogs.edit', $blog->id) }}" class="tour-admin-edit" target="_blank" title="Editar este artículo en el panel">
            <i class="fa fa-pencil" aria-hidden="true"></i> Editar artículo
        </a>
    @endauth

    <section class="page-banner page-banner--tall">
        <picture>
            <source media="(max-width: 768px)" srcset="{{ $blog->mobileHeroUrl() }}">
            <img src="{{ $blog->fullUrl() }}" alt="{{ $blog->nombre }}" data-parallax="10" loading="eager" fetchpriority="high" decoding="async">
        </picture>
        <div class="page-banner__content">
            <h1 class="page-banner__title">{{ $blog->nombre }}</h1>
            <div class="page-banner__divider"></div>
            <div class="article-reading-meta">
                @if ($blog->created_at)
                    <span><i class="fa fa-calendar-o" aria-hidden="true"></i> {{ $blog->created_at->format('d/m/Y') }}</span>
                @endif
                <span class="article-reading-meta__badge"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $blog->readingTime('es') }}</span>
                <span><i class="fa fa-bookmark-o" aria-hidden="true"></i> Diario Andean Exclusive</span>
            </div>
            @if ($blog->tags->count())
                <div class="article-tags">
                    @foreach ($blog->tags as $tag)
                        <a href="{{ route('estag.show', $tag->slug) }}" class="tag-pill">{{ $tag->nombre }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="section--tight">
        <div class="site-container">
            @include('layouts.partials.breadcrumb', [
                'ariaLabel' => 'Ruta de navegación',
                'crumbs' => [
                    ['label' => 'Inicio', 'url' => route('inicio')],
                    ['label' => 'Blog', 'url' => route('blog-es')],
                    ['label' => $blog->nombre],
                ],
            ])

            <div class="article-layout">
                <article>
                    @if (!empty(trim(strip_tags($blog->descripcionCorta))))
                        <div class="article-highlights" data-reveal>
                            <span class="article-highlights__eyebrow">Resumen Editorial</span>
                            <p class="article-highlights__lead">{{ strip_tags($blog->descripcionCorta) }}</p>
                        </div>
                    @endif

                    <div class="legal-prose legal-prose--fluid">
                        {!! $blog->renderedContent() !!}
                    </div>

                    {{-- Conversión comercial dentro del artículo --}}
                    <div class="article-cta-box" data-reveal>
                        <span class="article-cta-box__eyebrow">Viajes a Medida</span>
                        <h3 class="article-cta-box__title">Vive Esta Experiencia en Total Privacidad</h3>
                        <p class="article-cta-box__text">Cada destino y experiencia descritos en nuestros artículos pueden formar parte de tu itinerario privado por Perú. Conversa con nuestros diseñadores de viajes en Cusco y crearemos un plan exclusivo para ti.</p>
                        <div class="article-cta-box__actions">
                            <button type="button" class="btn-brand btn-brand--primary" data-contact-open>
                                <i class="fa fa-envelope-o" aria-hidden="true"></i> Diseñar Mi Viaje
                            </button>
                            <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="btn-brand btn-brand--whatsapp">
                                <i class="fa fa-whatsapp" aria-hidden="true"></i> Hablar por WhatsApp
                            </a>
                        </div>
                    </div>

                    {{-- Autor / Firma editorial --}}
                    <div class="article-author-card" data-reveal>
                        <div class="article-author-card__avatar">
                            <i class="fa fa-compass" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h4 class="article-author-card__name">Equipo Editorial Andean Exclusive</h4>
                            <p class="article-author-card__bio">Redactado por nuestros especialistas locales y guías privados residentes en Cusco y Lima, dedicados a ofrecer vivencias de alta gama con autenticidad cultural y respeto patrimonial.</p>
                        </div>
                    </div>

                    @include('layouts.partials.share-links', [
                        'url' => route('esblog.show', $blog->slug, true),
                        'i18n' => [
                            'share' => 'Compartir:',
                            'on' => 'Compartir en',
                            'email' => 'Compartir por correo',
                            'subject' => 'Andean Exclusive - ' . $blog->nombre,
                            'body' => 'Te recomiendo leer este artículo de Andean Exclusive: ',
                        ],
                    ])
                </article>

                {{-- Barra Lateral Sticky de Alta Gama --}}
                <aside class="article-sidebar">
                    {{-- 1. Historias Similares --}}
                    @if ($blogs->count())
                        <div class="article-sidebar__box">
                            <h3 class="article-sidebar__title">Historias Similares</h3>
                            <div class="article-sidebar__stories">
                                @foreach ($blogs as $item)
                                    <a href="{{ route('esblog.show', $item->slug) }}" class="article-side-card">
                                        <div class="article-side-card__media">
                                            <img src="{{ $item->thumbUrl() }}" alt="{{ $item->nombre }}" class="article-side-card__img" loading="lazy" decoding="async">
                                        </div>
                                        <div class="article-side-card__info">
                                            <h4 class="article-side-card__title">{{ $item->nombre }}</h4>
                                            @if ($item->created_at)
                                                <span class="article-side-card__date">
                                                    <i class="fa fa-calendar-o" aria-hidden="true"></i> {{ $item->created_at->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 2. Temas / Etiquetas --}}
                    @if ($blog->tags->count())
                        <div class="article-sidebar__box">
                            <h3 class="article-sidebar__title">Temas Relacionados</h3>
                            <div class="article-tags">
                                @foreach ($blog->tags as $tag)
                                    <a href="{{ route('estag.show', $tag->slug) }}" class="tag-pill tag-pill--muted">#{{ $tag->nombre }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 3. Llamada a la Acción Compacta --}}
                    <div class="article-sidebar__cta-card">
                        <span class="article-sidebar__cta-eyebrow font-display">Viajes a Medida</span>
                        <h4 class="article-sidebar__cta-title">Diseña tu Itinerario en Perú</h4>
                        <p class="article-sidebar__cta-text">100% personalizado a tu ritmo con guías privados y hoteles boutique seleccionados.</p>
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

    @include('layouts.partials.related-tours', [
        'tours' => $tours,
        'locale' => 'es',
        'eyebrow' => 'Viajes a Medida',
        'title' => 'Tours Privados en Perú',
        'tourRoute' => 'estour.show',
        'categoryRoute' => 'categoria.show',
        'moreLabel' => 'Ver itinerario',
    ])
@endsection
