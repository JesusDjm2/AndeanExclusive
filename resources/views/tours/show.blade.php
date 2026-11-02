{{--
    Detalle de tour, EN y ES (TourController@show / EsTourController@show).
    Textos de interfaz: lang/{en,es}/tour.php · Estilos: resources/sass/components/_tour-detail.scss
    · Comportamiento: resources/js/tour-detail.js
--}}
@php
    $isEs = $locale === 'es';
    $siteBase = rtrim(config('seo.site_url'), '/');
    $canonical = $isEs ? $siteBase . '/' . $tour->slug : $siteBase . '/en/' . $tour->slug;
    $altCanonical = $altTour ? ($isEs ? $siteBase . '/en/' . $altTour->slug : $siteBase . '/' . $altTour->slug) : null;
    $altUrl = $altTour ? route($isEs ? 'tour.show' : 'estour.show', $altTour->slug) : null;

    $routes = $isEs
        ? ['home' => 'inicio', 'listing' => 'experiencias', 'tour' => 'estour.show', 'category' => 'categoria.show', 'edit' => 'estours.edit']
        : ['home' => 'index', 'listing' => 'experiences', 'tour' => 'tour.show', 'category' => 'category.show', 'edit' => 'tours.edit'];

    $t = fn (string $key, array $replace = []) => __('tour.' . $key, $replace, $locale);
    $daysLabel = trans_choice('tour.days', (int) $tour->dias, ['count' => $tour->dias], $locale);
    $price = (float) $tour->precio;
    $category = $tour->categorias->first();

    $itinerary = \App\Support\TourContent::itinerary($tour->itinerario);
    $includes = \App\Support\TourContent::includes($tour->incluye);

    $sections = array_values(array_filter([
        ['id' => 'overview', 'label' => $t('sections.overview')],
        $tour->itinerario ? ['id' => 'itinerary', 'label' => $t('sections.itinerary')] : null,
        $tour->incluye ? ['id' => 'includes', 'label' => $t('sections.includes')] : null,
        $tour->mapa ? ['id' => 'map', 'label' => $t('sections.map')] : null,
        filled(strip_tags((string) $tour->importante)) ? ['id' => 'important', 'label' => $t('sections.important')] : null,
    ]));
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => $locale,
        'title' => $tour->nombre . ' | ' . $daysLabel . ' | ' . config('seo.brand'),
        'description' => \Illuminate\Support\Str::limit(strip_tags($tour->descripcionCorta), 165),
        'canonical' => $canonical,
        'keywords' => $tour->keywords,
        'og_image' => $tour->imgThumb,
        'og_type' => 'article',
    ])
    @if ($altCanonical)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ $canonical }}">
        <link rel="alternate" hreflang="{{ $isEs ? 'en' : 'es' }}" href="{{ $altCanonical }}">
        <link rel="alternate" hreflang="x-default" href="{{ $isEs ? $altCanonical : $canonical }}">
    @endif
    @include('layouts.partials.tour-jsonld', ['tour' => $tour, 'url' => $canonical, 'locale' => $locale])
@endsection

@section('contenido')
    @auth
        <a href="{{ route($routes['edit'], $tour->id) }}" class="tour-admin-edit" target="_blank">{{ $t('edit') }}</a>
    @endauth

    @include('tours.partials.hero', [
        'image' => asset($tour->imgFull),
        'title' => $tour->nombre,
        'eyebrow' => $category?->nombre,
        'lead' => $tour->descripcionCorta,
        'facts' => array_filter([
            ['icon' => 'fa-clock-o', 'label' => $t('duration'), 'value' => $daysLabel],
            $tour->recorrido ? ['icon' => 'fa-map-marker', 'label' => $t('route'), 'value' => $tour->recorrido] : null,
            ['icon' => 'fa-diamond', 'label' => $t('type'), 'value' => $t('type_value')],
        ]),
        'locale' => $locale,
        'tourName' => $tour->nombre . ' (' . $daysLabel . ')',
    ])

    @include('tours.partials.section-nav', [
        'sections' => $sections,
        'price' => $price,
        'locale' => $locale,
    ])

    <section class="tour-body">
        <div class="site-container">
            @include('layouts.partials.breadcrumb', [
                'ariaLabel' => $isEs ? 'Ruta de navegación' : 'Breadcrumb',
                'crumbs' => [
                    ['label' => $t('home'), 'url' => route($routes['home'])],
                    $category
                        ? ['label' => $category->nombre, 'url' => route($routes['category'], $category->slug)]
                        : ['label' => $t('tours'), 'url' => route($routes['listing'])],
                    ['label' => $tour->nombre],
                ],
            ])

            <div class="tour-layout">
                <div class="tour-main">
                    <section id="overview" class="tour-section" data-tour-section>
                        <h2 class="tour-section__title">{{ $t('sections.overview') }}</h2>
                        <div class="legal-prose legal-prose--fluid">
                            {!! \App\Support\TourContent::clean($tour->presentacion) !!}
                        </div>
                    </section>

                    @if ($tour->itinerario)
                        <section id="itinerary" class="tour-section" data-tour-section>
                            @include('tours.partials.itinerary', ['itinerary' => $itinerary, 'locale' => $locale])
                        </section>
                    @endif

                    @if ($tour->incluye)
                        <section id="includes" class="tour-section" data-tour-section>
                            <h2 class="tour-section__title">{{ $t('sections.includes') }}</h2>
                            @include('tours.partials.includes', [
                                'included' => $includes['includes'],
                                'excluded' => $includes['excludes'],
                                'locale' => $locale,
                            ])
                        </section>
                    @endif

                    @if ($tour->mapa)
                        <section id="map" class="tour-section" data-tour-section>
                            <h2 class="tour-section__title">{{ $t('sections.map') }}</h2>
                            <div class="tour-map">
                                {!! \App\Support\TourContent::lazyEmbeds($tour->mapa, $tour->nombre) !!}
                            </div>
                        </section>
                    @endif

                    @if (filled(strip_tags((string) $tour->importante)))
                        <section id="important" class="tour-section" data-tour-section>
                            <h2 class="tour-section__title">{{ $t('sections.important') }}</h2>
                            <div class="tour-note legal-prose legal-prose--fluid">
                                {!! \App\Support\TourContent::clean($tour->importante) !!}
                            </div>
                        </section>
                    @endif

                    @include('tours.partials.book-section', ['tour' => $tour, 'locale' => $locale])

                    @include('layouts.partials.share-links', [
                        'url' => $canonical,
                        'i18n' => $t('share'),
                    ])

                    @if ($categorias->count())
                        <nav class="tour-explore" aria-label="{{ $t('categories') }}">
                            <h2 class="tour-explore__title">{{ $t('categories') }}</h2>
                            <div class="tour-explore__list">
                                @foreach ($categorias as $categoria)
                                    <a href="{{ route($routes['category'], $categoria->slug) }}" class="tag-pill tag-pill--muted">{{ $categoria->nombre }}</a>
                                @endforeach
                            </div>
                        </nav>
                    @endif
                </div>

                <aside class="tour-aside">
                    @include('tours.partials.booking-card', [
                        'price' => $price,
                        'facts' => array_filter([
                            ['icon' => 'fa-clock-o', 'value' => $daysLabel],
                            $tour->recorrido ? ['icon' => 'fa-map-marker', 'value' => $tour->recorrido] : null,
                        ]),
                        'locale' => $locale,
                        'tourName' => $tour->nombre . ' (' . $daysLabel . ')',
                    ])

                </aside>
            </div>
        </div>
    </section>

    @include('layouts.partials.related-tours', [
        'tours' => $tours,
        'locale' => $locale,
        'eyebrow' => $t('related_eyebrow'),
        'title' => $t('related_title'),
        'tourRoute' => $routes['tour'],
        'categoryRoute' => $routes['category'],
        'moreLabel' => $t('read_more'),
    ])

    @include('tours.partials.mobile-bar', ['price' => $price, 'locale' => $locale])
@endsection
