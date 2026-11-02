@php
    $locale = 'en';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'en',
        'title' => 'Blog: ' . $tag->nombre . ' | ' . config('seo.brand'),
        'description' => 'Peru travel blog posts tagged ' . $tag->nombre . '.',
        'canonical' => route('entag.show', $tag->slug, true),
        'og_image' => $blogs->first()?->thumbUrl(),
    ])
@endsection

@section('contenido')
    <section class="page-banner">
        <img src="{{ asset('img/tours/Hiram-bingham.webp') }}" alt="{{ $tag->nombre }}" data-parallax="10" loading="eager" fetchpriority="high">
        <div class="page-banner__content">
            <h1 class="page-banner__title">Tagged: {{ $tag->nombre }}</h1>
            <div class="page-banner__divider"></div>
            <span class="page-banner__eyebrow">Andean Exclusive Journal</span>
        </div>
    </section>

    <section class="section">
        <div class="site-container">
            @include('layouts.partials.breadcrumb', [
                'crumbs' => [
                    ['label' => 'Home', 'url' => route('index')],
                    ['label' => 'Blog', 'url' => route('blog-en')],
                    ['label' => $tag->nombre],
                ],
            ])

            <div class="article-layout">
                <div>
                    <div class="grid-auto" data-reveal-stagger>
                        @foreach ($blogs as $blog)
                            <a href="{{ route('enblog.show', $blog->slug) }}" class="card-blog">
                                <img src="{{ $blog->thumbUrl() }}" alt="{{ $blog->nombre }}" loading="lazy">
                                <div class="card-blog__body">
                                    @if ($blog->created_at)
                                        <span class="card-blog__date">{{ $blog->created_at->format('M d, Y') }}</span>
                                    @endif
                                    <h2 class="card-blog__title">{{ $blog->nombre }}</h2>
                                    <p class="card-blog__excerpt">{{ $blog->descripcionCorta }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <aside>
                    <h3 class="article-sidebar__title">Tags</h3>
                    <div class="tour-categories">
                        @foreach ($tags as $t)
                            <a href="{{ route('entag.show', $t->slug) }}" class="tag-pill tag-pill--muted">#{{ $t->nombre }}</a>
                        @endforeach
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
