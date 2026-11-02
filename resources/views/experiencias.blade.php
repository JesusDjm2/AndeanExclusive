@php
    $locale = 'es';
    $altRoute = 'experiences';
@endphp
@extends('layouts.public')

@section('metas')
    @include('layouts.seo-head', ['locale' => 'es', 'page' => 'experiencias', 'og_image' => '/img/Machu-Picchu-exclusive.jpg'])
@endsection

@section('contenido')
    <section class="page-banner page-banner--tall">
        <img src="{{ asset('img/hermoso-peru.jpg') }}" alt="Tours exclusivos y privados en Perú" data-parallax="10" loading="eager" fetchpriority="high">
        <div class="page-banner__content">
            <h1 class="page-banner__title">Exclusivos — Tours Privados en Perú</h1>
            <div class="page-banner__divider"></div>
            <span class="page-banner__eyebrow">A tu medida, privado, inolvidable</span>
        </div>
    </section>

    <section class="section">
        <div class="site-container text-center" data-reveal>
            <h2 class="section-title">Experiencias en Perú</h2>
            <p class="section-lead max-w-prose mx-auto">
                Si buscamos un destino con el mayor abanico de experiencias posibles, sin duda más de un viajero
                diría: ¡visita Perú! La riqueza que posee este país sudamericano es difícil de superar, dándonos la
                oportunidad de visitar lugares tan secos como los desiertos costeros del sur del país, caminar por
                glaciares en la cordillera andina, navegar por la profunda selva que rodea el río Amazonas, visitar
                antiguas ciudades donde residieron civilizaciones perdidas, o simplemente degustar una de las
                cocinas mejor valoradas del planeta.
            </p>
            <p class="section-lead max-w-prose mx-auto">
                Los viajeros buscan destinos acorde a sus necesidades: unos buscan experiencias culturales, otros
                gastronómicas y los más osados buscan un contacto con la naturaleza. Perú ofrece esto y mucho más,
                nunca defrauda y siempre obliga a repetir. Perú tiene la experiencia que tanto estabas buscando —
                cada experiencia tiene una riqueza escondida. ¡Encuéntrala!
            </p>
        </div>
    </section>

    <section class="section section--sand">
        <div class="site-container">
            <div class="section-header">
                <span class="section-eyebrow font-display">En demanda</span>
                <h2 class="section-title">Experiencias más solicitadas</h2>
            </div>
            <div class="grid-auto" data-reveal-stagger>
                @foreach ($tours as $tour)
                    @include('layouts.partials.public-tour-card', [
                        'tour' => $tour,
                        'tourRoute' => 'estour.show',
                        'categoryRoute' => 'categoria.show',
                        'daysLabel' => $tour->dias == 1 ? 'día' : 'días',
                        'moreLabel' => 'Leer más',
                    ])
                @endforeach
            </div>
        </div>
    </section>
@endsection
