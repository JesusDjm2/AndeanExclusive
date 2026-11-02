@php
    $locale = 'es';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'es',
        'title' => 'Tours de ' . $categoria->nombre . ' | ' . config('seo.brand'),
        'description' => 'Descubre tours de ' . $categoria->nombre . ' en Perú con Andean Exclusive Tours.',
        'canonical' => route('categoria.show', $categoria->slug, true),
        'og_image' => $tours->first()->imgThumb ?? null,
    ])
@endsection

@section('contenido')
    <section class="page-banner">
        <img src="{{ asset($tours->first()->imgFull ?? 'img/full/Huayna-Picchu-Machu-Picchu.webp') }}" alt="{{ $categoria->nombre }}" data-parallax="10" loading="eager" fetchpriority="high">
        <div class="page-banner__content">
            <h1 class="page-banner__title">{{ $categoria->nombre }}</h1>
            <div class="page-banner__divider"></div>
            <span class="page-banner__eyebrow">Categoría de tour</span>
        </div>
    </section>

    <section class="section">
        <div class="site-container">
            @if ($tours->count())
                <div class="grid-auto" data-reveal-stagger>
                    @foreach ($tours as $tour)
                        @include('layouts.partials.public-tour-card', [
                            'tour' => $tour,
                            'tourRoute' => 'estour.show',
                            'categoryRoute' => 'categoria.show',
                            'daysLabel' => $tour->dias == 1 ? 'día' : 'días',
                            'moreLabel' => 'Ver más',
                        ])
                    @endforeach
                </div>
            @else
                <p class="section-lead text-center" data-reveal>
                    Aún no hay tours en esta categoría — <a href="{{ route('experiencias') }}">explora todas las experiencias</a>.
                </p>
            @endif
        </div>
    </section>
@endsection
