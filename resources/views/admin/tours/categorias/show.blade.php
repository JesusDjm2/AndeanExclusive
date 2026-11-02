@php
    $locale = 'en';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'en',
        'title' => $categoria->nombre . ' Tours | ' . config('seo.brand'),
        'description' => 'Browse ' . $categoria->nombre . ' tours in Peru with Andean Exclusive Tours.',
        'canonical' => route('category.show', $categoria->slug, true),
        'og_image' => $tours->first()->imgThumb ?? null,
    ])
@endsection

@section('contenido')
    <section class="page-banner">
        <img src="{{ asset($tours->first()->imgFull ?? 'img/full/Huayna-Picchu-Machu-Picchu.webp') }}" alt="{{ $categoria->nombre }}" data-parallax="10" loading="eager" fetchpriority="high">
        <div class="page-banner__content">
            <h1 class="page-banner__title">{{ $categoria->nombre }}</h1>
            <div class="page-banner__divider"></div>
            <span class="page-banner__eyebrow">Tour category</span>
        </div>
    </section>

    <section class="section">
        <div class="site-container">
            @if ($tours->count())
                <div class="grid-auto" data-reveal-stagger>
                    @foreach ($tours as $tour)
                        @include('layouts.partials.public-tour-card', [
                            'tour' => $tour,
                            'tourRoute' => 'tour.show',
                            'categoryRoute' => 'category.show',
                            'daysLabel' => 'days',
                            'moreLabel' => 'Read more',
                        ])
                    @endforeach
                </div>
            @else
                <p class="section-lead text-center" data-reveal>
                    No tours in this category yet — <a href="{{ route('experiences') }}">explore all experiences</a>.
                </p>
            @endif
        </div>
    </section>
@endsection
