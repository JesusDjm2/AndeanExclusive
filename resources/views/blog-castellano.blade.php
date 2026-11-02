@php
    $locale = 'es';
    $altRoute = 'blog-en';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'es',
        'page' => 'blog',
        'canonical' => route('blog-es', [], true),
        'og_image' => '/img/blog-hiking-peru.jpg',
    ])
@endsection

@section('contenido')
    @if ($lastBlog)
        <section class="page-banner page-banner--tall">
            <img src="{{ $lastBlog->fullUrl() }}" alt="{{ $lastBlog->nombre }}" data-parallax="10" loading="eager" fetchpriority="high">
            <div class="page-banner__content">
                <h1 class="page-banner__title">Blog de Turismo en el Perú</h1>
                <div class="page-banner__divider"></div>
                <span class="page-banner__eyebrow">El diario de Andean Exclusive</span>
                <p class="mt-sm">
                    <a href="{{ route('esblog.show', $lastBlog->slug) }}" class="btn-brand btn-brand--outline btn-brand--sm">
                        Destacado: {{ $lastBlog->nombre }}
                    </a>
                </p>
            </div>
        </section>
    @else
        <section class="section section--dark">
            <div class="site-container text-center">
                <h1 class="section-title">Blog de Turismo en el Perú</h1>
                <p class="section-lead">
                    Próximamente publicaremos artículos. Vuelva pronto o visite la versión en
                    <a href="{{ route('blog-en') }}" class="text-accent">inglés</a>.
                </p>
            </div>
        </section>
    @endif

    @if ($blogs->count())
        <section class="photo-wall" data-reveal-stagger aria-label="Artículos de viaje en el Perú">
            @foreach ($blogs as $blog)
                <a href="{{ route('esblog.show', $blog->slug) }}" class="photo-tile">
                    <img src="{{ $blog->thumbUrl() }}" alt="{{ $blog->nombre }}" loading="lazy" class="photo-tile__img">
                    <div class="photo-tile__body">
                        @if ($blog->created_at)
                            <span class="photo-tile__meta">{{ $blog->created_at->format('d/m/Y') }}</span>
                        @endif
                        <h2 class="photo-tile__title">{{ $blog->nombre }}</h2>
                        <div class="photo-tile__reveal">
                            <p class="photo-tile__excerpt">{{ $blog->descripcionCorta }}</p>
                            <span class="photo-tile__cta">Leer artículo <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </a>
            @endforeach
        </section>
    @endif
@endsection
