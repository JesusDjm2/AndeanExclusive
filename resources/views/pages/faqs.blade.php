{{--
    Preguntas frecuentes / FAQs (EnEnlacesController@faqs, @preguntas).
    Preguntas: resources/data/faqs-{en,es}.php
--}}
@php
    $isEs = $locale === 'es';
    $altRoute = $isEs ? 'faqs' : 'preguntas-frecuentes';
    $faqs = require resource_path('data/faqs-' . $locale . '.php');
    $t = fn (string $key, array $replace = []) => __('pages.faqs.' . $key, $replace, $locale);
    $whatsappUrl = 'https://wa.me/' . preg_replace('/\D/', '', config('seo.phone'))
        . '?text=' . rawurlencode(__('pages.cta.whatsapp_text', [], $locale));
    $faqLd = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'inLanguage' => $isEs ? 'es-PE' : 'en-US',
        'mainEntity' => array_map(fn ($faq) => [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(preg_replace('/\s+/', ' ', strip_tags($faq['answer'])))],
        ], $faqs),
    ];
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', ['locale' => $locale, 'page' => $isEs ? 'preguntas' : 'faqs', 'og_image' => '/img/hermoso-peru.jpg', 'og_type' => 'article'])
@endsection

@push('seo-structured-data')
<script type="application/ld+json">{!! json_encode($faqLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush

@section('contenido')
    @include('pages.partials.page-hero', [
        'image' => 'img/hermoso-peru.jpg',
        'alt' => $isEs ? 'Viajero junto a una llama frente a Machu Picchu' : 'Traveller next to a llama facing Machu Picchu',
        'title' => $t('title'),
        'eyebrow' => $t('eyebrow'),
        'lead' => $t('lead'),
        'crumbs' => [
            ['label' => __('pages.home', [], $locale), 'url' => route($isEs ? 'inicio' : 'index')],
            ['label' => $t('title')],
        ],
    ])

    <section class="section">
        <div class="site-container faq-layout">
            <div>
                <p class="faq-layout__count">{{ $t('count', ['count' => count($faqs)]) }}</p>
                <div class="faq-accordion">
                    @foreach ($faqs as $faq)
                        <details class="faq-item" @if ($loop->first) open @endif>
                            <summary class="faq-question">
                                <span><span class="faq-question__num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $faq['question'] }}</span>
                                <span class="faq-question__icon" aria-hidden="true"><i class="fa fa-plus"></i></span>
                            </summary>
                            <div class="faq-answer">{!! $faq['answer'] !!}</div>
                        </details>
                    @endforeach
                </div>
            </div>

            <aside class="faq-layout__aside">
                <div class="help-card" data-reveal>
                    <span class="help-card__icon"><i class="fa fa-comments-o" aria-hidden="true"></i></span>
                    <h2 class="help-card__title">{{ $t('help_title') }}</h2>
                    <p>{{ $t('help_text') }}</p>
                    <div class="help-card__actions">
                        <button type="button" class="btn-brand btn-brand--primary btn-brand--block" data-contact-open>{{ $t('help_contact') }}</button>
                        <a href="{{ $whatsappUrl }}" class="btn-brand btn-brand--whatsapp btn-brand--block" target="_blank" rel="noopener nofollow">
                            <i class="fa fa-whatsapp" aria-hidden="true"></i> {{ $t('help_whatsapp') }}
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    @include('pages.partials.cta-band', ['locale' => $locale, 'image' => 'img/Fondos/llama-Peru-Machu-Picchu.webp'])
@endsection
