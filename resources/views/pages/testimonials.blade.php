{{--
    Testimonios / Testimonials (EnEnlacesController@testimonials, @testimonios).
    Testimonios: resources/data/testimonials.php (en su idioma original en ambas versiones).
--}}
@php
    $isEs = $locale === 'es';
    $altRoute = $isEs ? 'testimonials' : 'testimonios';
    $testimonials = require resource_path('data/testimonials.php');
    $featured = array_shift($testimonials);
    $t = fn (string $key) => __('pages.testimonials.' . $key, [], $locale);
    // "The Anderson Family" -> "TA"; skips lowercase joiners so initials stay meaningful.
    $initials = fn (string $name) => collect(preg_split('/[\s,]+/', $name))
        ->filter(fn ($word) => $word !== '' && ctype_upper(mb_substr($word, 0, 1)))
        ->take(2)
        ->map(fn ($word) => mb_substr($word, 0, 1))
        ->implode('');
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', ['locale' => $locale, 'page' => $isEs ? 'testimonios' : 'testimonials', 'og_image' => '/img/Fondos/paquetes-peru.webp'])
@endsection

@section('contenido')
    @include('pages.partials.page-hero', [
        'image' => 'img/Fondos/paquetes-peru.webp',
        'alt' => $isEs ? 'Viajeros felices en Machu Picchu' : 'Happy travellers at Machu Picchu',
        'title' => $t('title'),
        'eyebrow' => $t('eyebrow'),
        'lead' => $t('lead'),
        'crumbs' => [
            ['label' => __('pages.home', [], $locale), 'url' => route($isEs ? 'inicio' : 'index')],
            ['label' => $t('eyebrow')],
        ],
    ])

    <section class="section">
        <div class="site-container">
            <figure class="review-featured" data-reveal>
                <span class="review-featured__label">{{ $t('featured') }}</span>
                <blockquote class="review-featured__quote">{{ $featured['quote'] }}</blockquote>
                <figcaption class="review-author">
                    <span class="review-author__avatar" aria-hidden="true">{{ $initials($featured['author']) }}</span>
                    <span>
                        <strong>{{ $featured['author'] }}</strong>
                        @if ($featured['title'])
                            <small>{{ $featured['title'] }}</small>
                        @endif
                    </span>
                </figcaption>
            </figure>

            <h2 class="visually-hidden">{{ $t('more') }}</h2>
            <div class="review-masonry">
                @foreach ($testimonials as $review)
                    <figure class="review-card" data-reveal>
                        <div class="review-card__stars" aria-hidden="true">
                            @for ($i = 0; $i < 5; $i++)<i class="fa fa-star"></i>@endfor
                        </div>
                        <blockquote class="review-card__quote">{{ $review['quote'] }}</blockquote>
                        <figcaption class="review-author">
                            <span class="review-author__avatar" aria-hidden="true">{{ $initials($review['author']) }}</span>
                            <span>
                                <strong>{{ $review['author'] }}</strong>
                                @if ($review['title'])
                                    <small>{{ $review['title'] }}</small>
                                @endif
                            </span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>

            <p class="review-more">
                <a href="https://n9.cl/cjx4" class="btn-brand btn-brand--ghost" target="_blank" rel="noopener nofollow">
                    <i class="fa fa-tripadvisor" aria-hidden="true"></i> {{ $t('tripadvisor') }}
                </a>
            </p>
        </div>
    </section>

    @include('pages.partials.cta-band', ['locale' => $locale, 'image' => 'img/Fondos/los-mejores-destinos-para-peru.webp'])
@endsection
