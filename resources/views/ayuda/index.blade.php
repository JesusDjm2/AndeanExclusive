{{-- Centro de ayuda (AyudaController@index). Contenido: App\Support\Ayuda · JS: resources/js/ayuda.js · Estilos: resources/sass/admin/_ayuda.scss --}}
@extends('layouts.app')
@section('titulo', 'Ayuda')

@section('contenido')
    <div class="ae-admin-page-header d-flex flex-wrap justify-content-between align-items-start gap-2">
        <div>
            <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-question-circle"></i> Ayuda</h1>
            <small class="ae-admin-page-desc">Cómo funciona el sistema y cómo hacer cada cosa. En cualquier pantalla, el botón <strong>?</strong> de arriba explica lo que estás viendo.</small>
        </div>
        @if (\App\Support\Ayuda::guiaDisponible())
            <a href="{{ route('ayuda.guia') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary"><i class="fas fa-file-pdf" aria-hidden="true"></i> Guía completa en PDF</a>
        @endif
    </div>

    <div class="ayuda-buscador">
        <label for="ayuda-q" class="visually-hidden">Buscar en la ayuda</label>
        <i class="fas fa-search" aria-hidden="true"></i>
        <input id="ayuda-q" type="search" class="form-control" placeholder="¿Qué necesitas hacer? Por ejemplo: confirmar, anular, pasaporte…" autocomplete="off" data-ayuda-buscar>
    </div>
    <p class="ayuda-sin-resultados" data-ayuda-vacio role="status" hidden>No encontramos nada con esas palabras. Prueba con otra, como «pago», «hotel» o «cliente».</p>

    <div data-ayuda-guias>
        <div class="ayuda-intro">
            @if ($area)
                <section class="prog-section" aria-labelledby="ayuda-area">
                    <div class="prog-section__header">
                        <h2 class="prog-section__title" id="ayuda-area">Tu trabajo en el sistema</h2>
                        <span class="prog-section__subtitle">{{ \App\Support\Permisos::area(auth()->user()) }}</span>
                    </div>
                    <div class="prog-section__body">
                        <p>{{ $area['trabajo'] }}</p>
                        <p class="ayuda-subtitulo">Para empezar el día</p>
                        <ol class="ayuda-pasos mb-0">
                            @foreach ($area['rutina'] as $paso)
                                <li>{{ $paso }}</li>
                            @endforeach
                        </ol>
                    </div>
                </section>
            @endif

            <section class="prog-section" aria-labelledby="ayuda-viaje">
                <div class="prog-section__header">
                    <h2 class="prog-section__title" id="ayuda-viaje">Cómo avanza un viaje</h2>
                </div>
                <div class="prog-section__body">
                    <ol class="ayuda-viaje">
                        @foreach (\App\Support\Ayuda::VIAJE as [$quien, $que])
                            <li><strong>{{ $quien }}</strong><span>{{ $que }}</span></li>
                        @endforeach
                    </ol>
                </div>
            </section>
        </div>
    </div>

    @foreach ($grupos as $grupo)
        <section class="ayuda-grupo" data-ayuda-grupo>
            <h2 class="ayuda-grupo__titulo">
                {{ $grupo['etiqueta'] }}
                @if ($grupo['propio'])<span class="ayuda-grupo__tuya">Tu área</span>@endif
            </h2>
            <div class="ayuda-grupo__lista">
                @foreach ($grupo['temas'] as $clave => $tema)
                    @include('ayuda.partials.tema', ['clave' => $clave, 'tema' => $tema, 'buscable' => true])
                @endforeach
            </div>
        </section>
    @endforeach

    <section class="ayuda-grupo" data-ayuda-grupo>
        <h2 class="ayuda-grupo__titulo">Si algo no funciona como esperas</h2>
        <div class="ayuda-grupo__lista">
            @foreach (\App\Support\Ayuda::PROBLEMAS as [$problema, $solucion])
                <details class="ayuda-tema" data-ayuda-item>
                    <summary><span>{{ $problema }}</span></summary>
                    <div class="ayuda-tema__cuerpo"><p>{{ $solucion }}</p></div>
                </details>
            @endforeach
        </div>
    </section>

    <section class="ayuda-grupo" data-ayuda-grupo>
        <h2 class="ayuda-grupo__titulo">Palabras que vas a ver</h2>
        <div class="ayuda-grupo__lista">
            @foreach (\App\Support\Ayuda::GLOSARIO as [$palabra, $significado])
                <details class="ayuda-tema" data-ayuda-item>
                    <summary><span>{{ $palabra }}</span></summary>
                    <div class="ayuda-tema__cuerpo"><p>{{ $significado }}</p></div>
                </details>
            @endforeach
        </div>
    </section>
@endsection
