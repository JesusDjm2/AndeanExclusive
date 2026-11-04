@php
    $locale = 'en';
    $altRoute = 'blog-es';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'en',
        'page' => 'blog',
        'title' => 'Peru Travel Blog | ' . config('seo.brand'),
        'description' => 'Explore insider stories, cultural heritage guides, and expert travel insights from Cusco, Machu Picchu and beyond with Andean Exclusive Tours.',
        'canonical' => route('blog-en', [], true),
        'og_image' => $lastBlog ? $lastBlog->thumbUrl() : '/img/blog-hiking-peru.jpg',
    ])
@endsection

@section('contenido')
    @if ($lastBlog)
        <section class="page-banner page-banner--tall">
            <picture>
                <source media="(max-width: 768px)" srcset="{{ $lastBlog->mobileHeroUrl() }}">
                <img src="{{ $lastBlog->fullUrl() }}" alt="{{ $lastBlog->nombre }}" data-parallax="10" loading="eager" fetchpriority="high" decoding="async">
            </picture>
            <div class="page-banner__content">
                <h1 class="page-banner__title">Peru Travel Blog</h1>
                <div class="page-banner__divider"></div>
                <span class="page-banner__eyebrow">The Andean Exclusive Journal</span>
                <p class="mt-sm">
                    <a href="{{ route('enblog.show', $lastBlog->slug) }}" class="btn-brand btn-brand--outline btn-brand--sm">
                        <span>Read: {{ Str::limit($lastBlog->nombre, 38) }}</span>
                    </a>
                </p>
            </div>
        </section>
    @else
        <section class="section section--dark">
            <div class="site-container text-center">
                <h1 class="section-title">Peru Travel Blog</h1>
                <p class="section-lead">
                    Articles coming soon. Check back shortly.
                </p>
            </div>
        </section>
    @endif

    @if ($blogs->count())
        <section class="photo-wall" data-reveal-stagger aria-label="Peru travel articles">
            @foreach ($blogs as $blog)
                <a href="{{ route('enblog.show', $blog->slug) }}" class="photo-tile">
                    <img src="{{ $blog->thumbUrl() }}" alt="{{ $blog->nombre }}" loading="lazy" decoding="async" class="photo-tile__img">
                    <div class="photo-tile__body">
                        <span class="photo-tile__meta">
                            @if ($blog->created_at)
                                {{ $blog->created_at->format('M d, Y') }} &bull;
                            @endif
                            {{ $blog->readingTime('en') }}
                        </span>
                        <h2 class="photo-tile__title">{{ $blog->nombre }}</h2>
                        <div class="photo-tile__reveal">
                            <p class="photo-tile__excerpt">{{ $blog->descripcionCorta }}</p>
                            <span class="photo-tile__cta">Read article <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </a>
            @endforeach
        </section>
    @endif
@endsection
