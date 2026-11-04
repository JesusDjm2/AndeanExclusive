@php
    $locale = 'en';
    $altUrl = $esblog ? route('esblog.show', $esblog->slug) : null;
    $waText = 'Hello Andean Exclusive, I am reading "' . $blog->nombre . '" and would like to design a bespoke journey in Peru.';
    $waUrl = 'https://wa.me/51979721194?text=' . rawurlencode($waText);
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'en',
        'page' => null,
        'title' => $blog->nombre . ' | ' . config('seo.brand'),
        'description' => Str::limit(strip_tags($blog->descripcionCorta ?: $blog->descripcion), 165),
        'canonical' => route('enblog.show', $blog->slug, true),
        'og_image' => $blog->thumbUrl(),
        'og_type' => 'article',
        'keywords' => $blog->keywords,
    ])
    @if ($esblog)
        <link rel="alternate" hreflang="es" href="{{ route('esblog.show', $esblog->slug, true) }}">
        <link rel="alternate" hreflang="en" href="{{ route('enblog.show', $blog->slug, true) }}">
        <link rel="alternate" hreflang="x-default" href="{{ route('enblog.show', $blog->slug, true) }}">
    @endif
@endsection

@push('seo-structured-data')
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $blog->nombre,
    'description' => Str::limit(strip_tags($blog->descripcionCorta ?: $blog->descripcion), 300),
    'url' => route('enblog.show', $blog->slug, true),
    'image' => [$blog->thumbUrl()],
    'datePublished' => $blog->created_at?->toIso8601String(),
    'dateModified' => $blog->updated_at?->toIso8601String(),
    'author' => ['@type' => 'Organization', 'name' => config('seo.brand')],
    'publisher' => ['@type' => 'Organization', 'name' => config('seo.brand'), 'logo' => ['@type' => 'ImageObject', 'url' => rtrim(config('seo.site_url'), '/') . '/img/andean-exclusive-logo.png']],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('contenido')
    @auth
        <a href="{{ route('enblogs.edit', $blog->id) }}" class="tour-admin-edit" target="_blank" title="Edit this post in admin panel">
            <i class="fa fa-pencil" aria-hidden="true"></i> Edit post
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
                    <span><i class="fa fa-calendar-o" aria-hidden="true"></i> {{ $blog->created_at->format('M d, Y') }}</span>
                @endif
                <span class="article-reading-meta__badge"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $blog->readingTime('en') }}</span>
                <span><i class="fa fa-bookmark-o" aria-hidden="true"></i> Andean Exclusive Journal</span>
            </div>
            @if ($blog->tags->count())
                <div class="article-tags">
                    @foreach ($blog->tags as $tag)
                        <a href="{{ route('entag.show', $tag->slug) }}" class="tag-pill">#{{ $tag->nombre }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="section--tight">
        <div class="site-container">
            @include('layouts.partials.breadcrumb', [
                'ariaLabel' => 'Breadcrumb',
                'crumbs' => [
                    ['label' => 'Home', 'url' => route('index')],
                    ['label' => 'Journal', 'url' => route('blog-en')],
                    ['label' => $blog->nombre],
                ],
            ])

            <div class="article-layout">
                <article>
                    @if (!empty(trim(strip_tags($blog->descripcionCorta))))
                        <div class="article-highlights" data-reveal>
                            <span class="article-highlights__eyebrow">Editorial Overview</span>
                            <p class="article-highlights__lead">{{ strip_tags($blog->descripcionCorta) }}</p>
                        </div>
                    @endif

                    <div class="legal-prose legal-prose--fluid">
                        {!! $blog->renderedContent() !!}
                    </div>

                    {{-- Commercial In-Article Conversion Hook --}}
                    <div class="article-cta-box" data-reveal>
                        <span class="article-cta-box__eyebrow">Bespoke Travel Planning</span>
                        <h3 class="article-cta-box__title">Experience This Journey in Complete Privacy</h3>
                        <p class="article-cta-box__text">Every destination featured in our journal can be seamlessly woven into your private Peru itinerary. Tell our Cusco-based luxury specialists what inspires you, and we will handle every detail.</p>
                        <div class="article-cta-box__actions">
                            <button type="button" class="btn-brand btn-brand--primary" data-contact-open>
                                <i class="fa fa-envelope-o" aria-hidden="true"></i> Design My Journey
                            </button>
                            <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="btn-brand btn-brand--whatsapp">
                                <i class="fa fa-whatsapp" aria-hidden="true"></i> Chat on WhatsApp
                            </a>
                        </div>
                    </div>

                    {{-- Author / Editorial Signature --}}
                    <div class="article-author-card" data-reveal>
                        <div class="article-author-card__avatar">
                            <i class="fa fa-compass" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h4 class="article-author-card__name">Andean Exclusive Editorial Team</h4>
                            <p class="article-author-card__bio">Curated by our native travel specialists and private guides based in Cusco and Lima, dedicated to genuine Peruvian luxury and authentic cultural immersion.</p>
                        </div>
                    </div>

                    @include('layouts.partials.share-links', [
                        'url' => route('enblog.show', $blog->slug, true),
                        'i18n' => [
                            'share' => 'Share:',
                            'on' => 'Share on',
                            'email' => 'Share by email',
                            'subject' => 'Andean Exclusive - ' . $blog->nombre,
                            'body' => 'I recommend reading this article from Andean Exclusive: ',
                        ],
                    ])
                </article>

                {{-- Senior Sticky Sidebar Rail --}}
                <aside class="article-sidebar">
                    {{-- 1. Similar Stories --}}
                    @if ($blogs->count())
                        <div class="article-sidebar__box">
                            <h3 class="article-sidebar__title">Similar Stories</h3>
                            <div class="article-sidebar__stories">
                                @foreach ($blogs as $item)
                                    <a href="{{ route('enblog.show', $item->slug) }}" class="article-side-card">
                                        <div class="article-side-card__media">
                                            <img src="{{ $item->thumbUrl() }}" alt="{{ $item->nombre }}" class="article-side-card__img" loading="lazy" decoding="async">
                                        </div>
                                        <div class="article-side-card__info">
                                            <h4 class="article-side-card__title">{{ $item->nombre }}</h4>
                                            @if ($item->created_at)
                                                <span class="article-side-card__date">
                                                    <i class="fa fa-calendar-o" aria-hidden="true"></i> {{ $item->created_at->format('M d, Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 2. Topic Tags --}}
                    @if ($blog->tags->count())
                        <div class="article-sidebar__box">
                            <h3 class="article-sidebar__title">Explore Topics</h3>
                            <div class="article-tags">
                                @foreach ($blog->tags as $tag)
                                    <a href="{{ route('entag.show', $tag->slug) }}" class="tag-pill tag-pill--muted">#{{ $tag->nombre }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 3. Compact Bespoke Journey CTA --}}
                    <div class="article-sidebar__cta-card">
                        <span class="article-sidebar__cta-eyebrow font-display">Bespoke Journeys</span>
                        <h4 class="article-sidebar__cta-title">Craft Your Peru Itinerary</h4>
                        <p class="article-sidebar__cta-text">Tailored 100% to your rhythm with private guides and handpicked boutique stays.</p>
                        <div class="article-sidebar__cta-actions">
                            <button type="button" class="btn-brand btn-brand--primary btn-brand--sm btn-brand--block" data-contact-open>
                                <i class="fa fa-paper-plane" aria-hidden="true"></i> Plan Custom Trip
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
        'locale' => 'en',
        'eyebrow' => 'Handcrafted Journeys',
        'title' => 'Featured Private Tours in Perú',
        'tourRoute' => 'tour.show',
        'categoryRoute' => 'category.show',
        'moreLabel' => 'View itinerary',
    ])
@endsection
