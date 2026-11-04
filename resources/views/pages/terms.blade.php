{{--
    Términos y condiciones / Terms & Conditions (EnEnlacesController@terms, @terminos).
    Documento: layouts/partials/legal/terms-{en,es} · Índice: App\Support\LegalDocument
--}}
@php
    $isEs = $locale === 'es';
    $altRoute = $isEs ? 'terms' : 'terminos';
    $document = \App\Support\LegalDocument::withToc(view('layouts.partials.legal.terms-' . $locale)->render());
    $t = fn (string $key) => __('pages.terms.' . $key, [], $locale);
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', ['locale' => $locale, 'page' => $isEs ? 'terminos' : 'terms', 'og_image' => '/img/Fondos/Tours-a-Machu-Picchu-Pacha-Mama.webp'])
@endsection

@section('contenido')
    @include('pages.partials.page-hero', [
        'image' => 'img/Fondos/Tours-a-Machu-Picchu-Pacha-Mama.webp',
        'alt' => 'Machu Picchu',
        'title' => $t('title'),
        'eyebrow' => $t('eyebrow'),
        'lead' => $t('lead'),
        'crumbs' => [
            ['label' => __('pages.home', [], $locale), 'url' => route($isEs ? 'inicio' : 'index')],
            ['label' => $t('title')],
        ],
    ])

    <section class="section">
        <div class="site-container legal-layout">
            <nav class="legal-toc" aria-label="{{ $t('toc') }}" data-toc>
                <p class="legal-toc__title">{{ $t('toc') }}</p>
                <ol class="legal-toc__list">
                    @foreach ($document['toc'] as $item)
                        <li><a href="#{{ $item['id'] }}" data-toc-link>{{ $item['label'] }}</a></li>
                    @endforeach
                </ol>
            </nav>

            <article class="legal-prose legal-prose--fluid legal-document">
                {!! $document['html'] !!}
            </article>
        </div>
    </section>
@endsection
