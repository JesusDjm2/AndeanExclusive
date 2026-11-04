{{--
    Plantilla de las aventuras de salida fija (resources/views/adventures/*).
    Mismo diseño y partials que el detalle de tour (tours/show), con contenido estático.

    La vista hija define, antes de @extends:
      $locale    'es'|'en'
      $adventure [
          'route'      => nombre de ruta (canonical / compartir / excluir de relacionados),
          'title'      => H1,
          'crumb'      => etiqueta corta para el breadcrumb,
          'eyebrow'    => etiqueta sobre el título,
          'lead'       => bajada del hero,
          'image'      => ruta relativa a public/ de la imagen principal,
          'image_alt'  => texto alternativo,
          'days'       => número de días (formulario de consulta),
          'facts'      => [['icon', 'label', 'value'], ...],
          'includes'   => HTML, 'excludes' => HTML|null, 'bring' => HTML|null (qué llevar),
      ]
    y las secciones: metas, overview (HTML del resumen) e itinerary (opcional).
--}}
@php
    $t = fn (string $key) => __('tour.' . $key, [], $locale);
    $hasItinerary = trim($__env->yieldContent('itinerary')) !== '';
    $bookingTour = (object) ['nombre' => $adventure['title'], 'dias' => $adventure['days'] ?? 1];

    $sections = array_values(array_filter([
        ['id' => 'overview', 'label' => $t('sections.overview')],
        $hasItinerary ? ['id' => 'itinerary', 'label' => $t('sections.itinerary')] : null,
        ['id' => 'includes', 'label' => $t('sections.includes')],
        ! empty($adventure['bring']) ? ['id' => 'bring', 'label' => $t('sections.bring')] : null,
    ]));
@endphp
@extends('layouts.public')

@section('contenido')
    @include('tours.partials.hero', [
        'image' => asset($adventure['image']),
        'alt' => $adventure['image_alt'] ?? null,
        'title' => $adventure['title'],
        'eyebrow' => $adventure['eyebrow'] ?? null,
        'lead' => $adventure['lead'] ?? null,
        'facts' => $adventure['facts'],
        'locale' => $locale,
        'tourName' => $adventure['title'],
    ])

    @include('tours.partials.section-nav', ['sections' => $sections, 'price' => null, 'locale' => $locale])

    <section class="tour-body">
        <div class="site-container">
            @include('layouts.partials.breadcrumb', [
                'ariaLabel' => $locale === 'es' ? 'Ruta de navegación' : 'Breadcrumb',
                'crumbs' => [
                    ['label' => $t('home'), 'url' => route($locale === 'es' ? 'inicio' : 'index')],
                    ['label' => $t('adventures'), 'url' => route($locale === 'es' ? 'caminatas' : 'adventures')],
                    ['label' => $adventure['crumb']],
                ],
            ])

            <div class="tour-layout">
                <div class="tour-main">
                    <section id="overview" class="tour-section" data-tour-section>
                        <h2 class="tour-section__title">{{ $t('sections.overview') }}</h2>
                        <div class="legal-prose legal-prose--fluid">
                            @yield('overview')
                        </div>
                    </section>

                    @if ($hasItinerary)
                        <section id="itinerary" class="tour-section" data-tour-section>
                            <h2 class="tour-section__title">{{ $t('sections.itinerary') }}</h2>
                            <div class="legal-prose legal-prose--fluid">
                                @yield('itinerary')
                            </div>
                        </section>
                    @endif

                    <section id="includes" class="tour-section" data-tour-section>
                        <h2 class="tour-section__title">{{ $t('sections.includes') }}</h2>
                        @include('tours.partials.includes', [
                            'included' => $adventure['includes'],
                            'excluded' => $adventure['excludes'] ?? null,
                            'locale' => $locale,
                        ])
                    </section>

                    @if (! empty($adventure['bring']))
                        <section id="bring" class="tour-section" data-tour-section>
                            <h2 class="tour-section__title">{{ $t('sections.bring') }}</h2>
                            <div class="tour-checklist">{!! $adventure['bring'] !!}</div>
                        </section>
                    @endif

                    @include('tours.partials.book-section', ['tour' => $bookingTour, 'locale' => $locale])

                    @include('layouts.partials.share-links', [
                        'url' => route($adventure['route'], [], true),
                        'i18n' => $t('share'),
                    ])
                </div>

                <aside class="tour-aside">
                    @include('tours.partials.booking-card', [
                        'price' => null,
                        'facts' => array_map(fn ($fact) => ['icon' => $fact['icon'], 'value' => $fact['value']], $adventure['facts']),
                        'locale' => $locale,
                        'tourName' => $adventure['title'],
                    ])
                </aside>
            </div>
        </div>
    </section>

    @include('layouts.partials.public-adventure-links', ['currentRoute' => $adventure['route']])

    @include('tours.partials.mobile-bar', ['price' => null, 'locale' => $locale])
@endsection
