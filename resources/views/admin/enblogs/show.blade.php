@php
    $locale = 'en';
    $altUrl = $esblog ? route('esblog.show', $esblog->slug) : null;
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
    <section class="page-banner page-banner--tall">
        <img src="{{ $blog->fullUrl() }}" alt="{{ $blog->nombre }}" data-parallax="10" loading="eager" fetchpriority="high">
        <div class="page-banner__content">
            <h1 class="page-banner__title">{{ $blog->nombre }}</h1>
            <div class="page-banner__divider"></div>
            @if ($blog->created_at)
                <span class="page-banner__eyebrow">{{ $blog->created_at->format('M d, Y') }}</span>
            @endif
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
                    ['label' => 'Blog', 'url' => route('blog-en')],
                    ['label' => $blog->nombre],
                ],
            ])

            <div class="article-layout">
                <article>
                    <div class="article-figure" data-reveal>
                        <img src="{{ $blog->fullUrl() }}" alt="{{ $blog->nombre }}" loading="lazy">
                    </div>
                    <div class="legal-prose legal-prose--fluid">
                        {!! $blog->descripcion !!}
                    </div>
                    @include('layouts.partials.share-links', [
                        'url' => route('enblog.show', $blog->slug, true),
                        'i18n' => [
                            'share' => 'Share:',
                            'on' => 'Share on',
                            'email' => 'Share by email',
                            'subject' => 'Andean Exclusive',
                            'body' => 'I recommend this article: ',
                        ],
                    ])
                </article>

                <aside data-reveal>
                    @if ($blogs->count())
                        <h3 class="article-sidebar__title">Similar Blogs</h3>
                        @foreach ($blogs as $item)
                            <a href="{{ route('enblog.show', $item->slug) }}" class="article-side-card">
                                <img src="{{ $item->thumbUrl() }}" alt="{{ $item->nombre }}" class="article-side-card__img" loading="lazy">
                                <div>
                                    <h4 class="article-side-card__title">{{ $item->nombre }}</h4>
                                    @if ($item->created_at)
                                        <span class="article-side-card__date"><i class="fa fa-calendar" aria-hidden="true"></i> {{ $item->created_at->format('d M, Y') }}</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    @endif
                    @if ($blog->tags->count())
                        <h3 class="article-sidebar__title mt-lg">Tags</h3>
                        <div class="article-tags">
                            @foreach ($blog->tags as $tag)
                                <a href="{{ route('entag.show', $tag->slug) }}" class="tag-pill tag-pill--muted">#{{ $tag->nombre }}</a>
                            @endforeach
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>

    @include('layouts.partials.related-tours', [
        'tours' => $tours,
        'locale' => 'en',
        'eyebrow' => 'You may also like',
        'title' => 'Exclusive Tours for Perú',
        'tourRoute' => 'tour.show',
        'categoryRoute' => 'category.show',
        'moreLabel' => 'Read more',
    ])
@endsection
