@php
    $locale = 'en';
    $altRoute = 'blog-es';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'en',
        'page' => 'blog',
        'canonical' => route('blog-en', [], true),
        'og_image' => '/img/blog-hiking-peru.jpg',
    ])
@endsection

@section('contenido')
    <section class="page-banner page-banner--tall">
        <img src="{{ $lastBlog->fullUrl() }}" alt="{{ $lastBlog->nombre }}" data-parallax="10" loading="eager" fetchpriority="high">
        <div class="page-banner__content">
            <h1 class="page-banner__title">Peru Travel Blog</h1>
            <div class="page-banner__divider"></div>
            <span class="page-banner__eyebrow">The Andean Exclusive Journal</span>
            <p class="mt-sm">
                <a href="{{ route('enblog.show', $lastBlog->slug) }}" class="btn-brand btn-brand--outline btn-brand--sm">
                    Read: {{ $lastBlog->nombre }}
                </a>
            </p>
        </div>
    </section>

    <section class="photo-wall" data-reveal-stagger aria-label="Peru travel articles">
        @foreach ($blogs as $blog)
            <a href="{{ route('enblog.show', $blog->slug) }}" class="photo-tile">
                <img src="{{ $blog->thumbUrl() }}" alt="{{ $blog->nombre }}" loading="lazy" class="photo-tile__img">
                <div class="photo-tile__body">
                    @if ($blog->created_at)
                        <span class="photo-tile__meta">{{ $blog->created_at->format('M d, Y') }}</span>
                    @endif
                    <h2 class="photo-tile__title">{{ $blog->nombre }}</h2>
                    <div class="photo-tile__reveal">
                        <p class="photo-tile__excerpt">{{ $blog->descripcionCorta }}</p>
                        <span class="photo-tile__cta">Read article <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                    </div>
                </div>
            </a>
        @endforeach
    </section>
@endsection
