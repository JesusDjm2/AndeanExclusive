@php
    $locale = 'es';
    $altRoute = 'blog-en';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'es',
        'page' => 'blog',
        'title' => 'Blog de Turismo y Relatos en el Perú | ' . config('seo.brand'),
        'description' => 'Descubre historias exclusivas, guías de viaje y secretos culturales del Cusco, Machu Picchu y todo el Perú con Andean Exclusive Tours.',
        'canonical' => route('blog-es', [], true),
        'og_image' => $lastBlog ? $lastBlog->thumbUrl() : '/img/blog-hiking-peru.jpg',
    ])
@endsection

@section('contenido')
    <section class="page-banner page-banner--tall">
        @if ($lastBlog)
            <picture>
                <source media="(max-width: 768px)" srcset="{{ $lastBlog->mobileHeroUrl() }}">
                <img src="{{ $lastBlog->fullUrl() }}" alt="{{ $lastBlog->nombre }}" data-parallax="10" loading="eager" fetchpriority="high" decoding="async">
            </picture>
        @else
            <img src="{{ asset('img/blog-hiking-peru.jpg') }}" alt="Blog de Turismo en el Perú" data-parallax="10" loading="eager" fetchpriority="high" decoding="async">
        @endif
        <div class="page-banner__content">
            <span class="page-banner__eyebrow">El Diario de Andean Exclusive</span>
            <h1 class="page-banner__title">Blog de Turismo en el Perú</h1>
            <div class="page-banner__divider"></div>
            <p class="hero__subtitle">
                Patrimonio cultural auténtico, paisajes andinos recónditos y consejos expertos de viaje seleccionados por nuestros especialistas locales en Cusco.
            </p>
            <div class="hero__actions mt-md">
                <a href="#articulos-recientes" class="btn-brand btn-brand--primary">
                    <i class="fa fa-book" aria-hidden="true"></i>
                    <span>Explorar historias</span>
                </a>
                <button type="button" class="btn-brand btn-brand--outline" data-contact-open>
                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                    <span>Diseñar viaje a medida</span>
                </button>
            </div>
        </div>
    </section>

    <section class="journal-section" id="articulos-recientes">
        <div class="site-container">
            @if ($lastBlog)
                <div class="section-header text-center">
                    <span class="section-eyebrow font-display">Selección del Editor</span>
                    <h2 class="section-title">Historia Destacada</h2>
                </div>

                {{-- Featured Story Spread --}}
                <article class="journal-featured" data-reveal>
                    <div class="journal-featured__media">
                        <span class="journal-featured__badge"><i class="fa fa-star" aria-hidden="true"></i> Historia Destacada</span>
                        <picture>
                            <source media="(max-width: 768px)" srcset="{{ $lastBlog->mobileHeroUrl() }}">
                            <img src="{{ $lastBlog->fullUrl() }}" alt="{{ $lastBlog->nombre }}" class="journal-featured__img" loading="eager" decoding="async">
                        </picture>
                    </div>
                    <div class="journal-featured__body">
                        <div class="journal-featured__meta">
                            @if ($lastBlog->tags->count())
                                <span class="journal-meta-tag">#{{ $lastBlog->tags->first()->nombre }}</span>
                            @endif
                            @if ($lastBlog->created_at)
                                <span><i class="fa fa-calendar-o" aria-hidden="true"></i> {{ $lastBlog->created_at->format('d/m/Y') }}</span>
                            @endif
                            <span><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $lastBlog->readingTime('es') }}</span>
                        </div>
                        <h2 class="journal-featured__title">
                            <a href="{{ route('esblog.show', $lastBlog->slug) }}">
                                {{ $lastBlog->nombre }}
                            </a>
                        </h2>
                        <p class="journal-featured__excerpt">
                            {{ $lastBlog->descripcionCorta }}
                        </p>
                        <div>
                            <a href="{{ route('esblog.show', $lastBlog->slug) }}" class="btn-brand btn-brand--primary btn-brand--sm">
                                <span>Leer historia completa</span>
                                <i class="fa fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @endif

            @if ($blogs->count())
                <div class="section-header text-center mt-2xl">
                    <span class="section-eyebrow font-display">Archivo Exclusivo</span>
                    <h2 class="section-title">Artículos y Guías Recientes</h2>
                    <p class="section-lead">
                        Publicaciones a fondo sobre gastronomía peruana, monumentos incas, festividades andinas y consejos clave para tu próximo viaje.
                    </p>
                </div>

                <div class="journal-grid" data-reveal-stagger aria-label="Archivo de artículos de viaje por Perú">
                    @foreach ($blogs as $blog)
                        <a href="{{ route('esblog.show', $blog->slug) }}" class="journal-card">
                            <div class="journal-card__media">
                                <img src="{{ $blog->thumbUrl() }}" alt="{{ $blog->nombre }}" loading="lazy" decoding="async" class="journal-card__img">
                            </div>
                            <div class="journal-card__body">
                                <div class="journal-card__meta">
                                    <span class="journal-card__date">
                                        @if ($blog->created_at)
                                            <i class="fa fa-calendar-o" aria-hidden="true"></i> {{ $blog->created_at->format('d/m/Y') }}
                                        @endif
                                    </span>
                                    <span class="journal-card__readtime">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $blog->readingTime('es') }}
                                    </span>
                                </div>
                                <h3 class="journal-card__title">{{ $blog->nombre }}</h3>
                                <p class="journal-card__excerpt">{{ $blog->descripcionCorta }}</p>
                                <span class="journal-card__cta">
                                    <span>Leer artículo</span>
                                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @include('layouts.partials.collection-bottom-cta', [
        'locale' => 'es',
        'categoryName' => 'Relatos de Viaje en Perú',
    ])
@endsection
