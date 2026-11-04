{{--
    Hoja de operaciones diaria (OperacionesController@index): servicios del día por hora, ingresos y salidas de hotel
    y servicios sin confirmar de los próximos días. Se imprime limpia (resources/sass/admin/_operaciones.scss).
--}}
@extends('layouts.app')
@section('titulo', 'Operaciones · ' . $fecha->format('d/m/Y'))

@php
    $hoy = \Carbon\Carbon::today();
    $etiquetaDia = $fecha->isToday() ? 'Hoy' : ($fecha->isTomorrow() ? 'Mañana' : ($fecha->isYesterday() ? 'Ayer' : null));
    // Pasajeros con dieta o alergias: lo que debe saber el restaurante o el guía.
    $avisos = fn ($programa) => $programa->paxs
        ->filter(fn ($pax) => ($pax->dieta && $pax->dieta !== 'ninguna') || $pax->alergias)
        ->map(fn ($pax) => $pax->nombre . ': ' . $pax->alimentacion);
@endphp

@section('contenido')
    <div class="ae-admin-page-header">
        <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-clipboard-check"></i> Hoja de operaciones</h1>
        <small class="ae-admin-page-desc">Servicios, traslados y movimientos de hotel del día. Se puede imprimir para el equipo de campo.</small>
    </div>

    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
        @if (session($key))
            <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
    @endforeach

    <div class="ops-toolbar">
        <a href="{{ route('operaciones.index', ['fecha' => $fecha->copy()->subDay()->toDateString()]) }}" class="btn btn-outline-secondary btn-sm" aria-label="Día anterior"><i class="fas fa-chevron-left" aria-hidden="true"></i></a>
        <a href="{{ route('operaciones.index') }}" class="btn btn-sm {{ $fecha->isToday() ? 'btn-primary' : 'btn-outline-secondary' }}">Hoy</a>
        <a href="{{ route('operaciones.index', ['fecha' => $hoy->copy()->addDay()->toDateString()]) }}" class="btn btn-sm {{ $fecha->isTomorrow() ? 'btn-primary' : 'btn-outline-secondary' }}">Mañana</a>
        <a href="{{ route('operaciones.index', ['fecha' => $fecha->copy()->addDay()->toDateString()]) }}" class="btn btn-outline-secondary btn-sm" aria-label="Día siguiente"><i class="fas fa-chevron-right" aria-hidden="true"></i></a>
        <form method="GET" action="{{ route('operaciones.index') }}">
            <label for="ops-fecha" class="visually-hidden">Fecha</label>
            <input id="ops-fecha" type="date" name="fecha" value="{{ $fecha->toDateString() }}" class="form-control form-control-sm">
            <button type="submit" class="btn btn-sm btn-outline-primary">Ir</button>
        </form>
        <button type="button" class="btn btn-sm btn-outline-secondary ops-toolbar__print" data-print><i class="fas fa-print" aria-hidden="true"></i> Imprimir</button>
    </div>

    <p class="ops-date">{{ $etiquetaDia ? $etiquetaDia . ' · ' : '' }}{{ ucfirst($fecha->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY')) }}</p>
    <div class="ops-stats">
        <span><strong>{{ $resumen['servicios'] }}</strong> {{ $resumen['servicios'] === 1 ? 'servicio' : 'servicios' }}</span>
        <span><strong>{{ $resumen['programas'] }}</strong> {{ $resumen['programas'] === 1 ? 'programa' : 'programas' }}</span>
        <span><strong>{{ $resumen['pax'] }}</strong> pasajeros</span>
        @if ($resumen['abiertos'])
            <span class="is-warn"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> <strong>{{ $resumen['abiertos'] }}</strong> sin confirmar</span>
        @endif
    </div>

    <div class="ops-layout">
        <div>
            <section class="card dash-card mb-4" aria-labelledby="ops-servicios">
                <div class="card-header dash-card__header">
                    <h2 id="ops-servicios" class="dash-card__title">Servicios del día</h2>
                </div>
                @if ($servicios->isEmpty())
                    <div class="admin-empty"><i class="fas fa-mug-hot" aria-hidden="true"></i> No hay servicios programados para este día.</div>
                @else
                    <ul class="ops-list">
                        @foreach ($servicios as $servicio)
                            @php
                                $programa = $servicio->programa;
                                $avisosPax = $avisos($programa);
                            @endphp
                            <li class="ops-item">
                                <div class="ops-item__time">
                                    {{ $servicio->hora_corta ?? '—' }}
                                    <small>{{ $servicio->tipo_etiqueta }}</small>
                                </div>
                                <div>
                                    <h3 class="ops-item__title"><i class="fas {{ $servicio->icono }}" aria-hidden="true"></i>{{ $servicio->titulo }}</h3>
                                    <p class="ops-item__meta">
                                        <span><i class="fas fa-suitcase-rolling" aria-hidden="true"></i>
                                                                                            <a href="{{ route('programas.edit', [$programa, 'tab' => 'itinerario']) }}">{{ $programa->codigo }}</a>
                                            · {{ $programa->cliente?->etiqueta ?? $programa->nombre }}
                                        </span>
                                        <span><i class="fas fa-users" aria-hidden="true"></i>{{ $programa->paxs->count() }} pax</span>
                                        @if ($servicio->punto_encuentro)
                                            <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i>{{ $servicio->punto_encuentro }}</span>
                                        @endif
                                        <span><i class="fas fa-handshake" aria-hidden="true"></i>
                                            @if ($servicio->proveedor)
                                                {{ $servicio->proveedor->nombre }}@if ($servicio->proveedor->telefono) · <a href="tel:{{ preg_replace('/[^0-9+]/', '', $servicio->proveedor->telefono) }}">{{ $servicio->proveedor->telefono }}</a>@endif
                                            @else
                                                <span class="text-danger">Sin proveedor</span>
                                            @endif
                                        </span>
                                    </p>
                                    @if ($programa->paxs->isNotEmpty())
                                        <p class="ops-item__meta mb-1"><span>{{ $programa->paxs->pluck('nombre')->implode(', ') }}</span></p>
                                    @endif
                                    @if ($avisosPax->isNotEmpty())
                                        <p class="ops-item__flags mb-1"><i class="fas fa-utensils" aria-hidden="true"></i> {{ $avisosPax->implode(' · ') }}</p>
                                    @endif
                                    @if ($servicio->notas)
                                        <p class="ops-item__meta mb-0"><span><i class="fas fa-sticky-note" aria-hidden="true"></i>{{ $servicio->notas }}</span></p>
                                    @endif

                                    <div class="ops-item__footer">
                                        <span class="svc-badge svc-badge--{{ $servicio->estado }}">{{ \App\Models\Servicio::ESTADOS[$servicio->estado] }}</span>
                                        @if ($servicio->confirmacion)
                                            <span class="small text-muted">Conf. <strong>{{ $servicio->confirmacion }}</strong></span>
                                        @endif
                                        @include('servicios.partials.acciones', ['servicio' => $servicio])
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            @if ($vuelos->isNotEmpty())
                <section class="card dash-card mb-4" aria-labelledby="ops-vuelos">
                    <div class="card-header dash-card__header">
                        <h2 id="ops-vuelos" class="dash-card__title">Vuelos del día</h2>
                    </div>
                    <ul class="ops-side-list">
                        @foreach ($vuelos as $vuelo)
                            <li>
                                <span>
                                    <strong>{{ $vuelo['hora'] }} · {{ $vuelo['vuelo'] ?: 'Vuelo sin número' }}</strong>
                                    <small>{{ $vuelo['programa']->codigo }} · {{ $vuelo['programa']->nombre }} · {{ $vuelo['programa']->paxs->count() }} pax</small>
                                </span>
                                <span class="svc-badge {{ $vuelo['tipo'] === 'Llegada' ? 'svc-badge--confirmado' : 'svc-badge--solicitado' }}">{{ $vuelo['tipo'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <section class="card dash-card" aria-labelledby="ops-hoteles">
                <div class="card-header dash-card__header">
                    <h2 id="ops-hoteles" class="dash-card__title">Ingresos y salidas de hotel</h2>
                </div>
                @if ($estadias->isEmpty())
                    <div class="admin-empty py-4"><i class="fas fa-hotel" aria-hidden="true"></i> Sin movimientos de hotel este día.</div>
                @else
                    <ul class="ops-side-list">
                        @foreach ($estadias as $estadia)
                            @php $ingresa = $estadia->fecha_ingreso === $fecha->toDateString(); @endphp
                            <li>
                                <span>
                                    <strong>{{ $estadia->habitacion?->hotel?->nombre ?? 'Hotel' }}</strong> · {{ $estadia->etiqueta }}
                                    <small>{{ $estadia->programa->codigo }} · {{ $estadia->programa->nombre }} · {{ $estadia->programa->paxs->count() }} pax</small>
                                </span>
                                <span class="svc-badge {{ $ingresa ? 'svc-badge--confirmado' : 'svc-badge--solicitado' }}">{{ $ingresa ? 'Check-in' : 'Check-out' }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>

        <aside class="card dash-card ops-no-print" aria-labelledby="ops-pendientes">
            <div class="card-header dash-card__header">
                <h2 id="ops-pendientes" class="dash-card__title">Por confirmar · {{ \App\Http\Controllers\OperacionesController::DIAS_POR_CONFIRMAR }} días</h2>
                <span class="badge text-bg-light">{{ $porConfirmar->count() }}</span>
            </div>
            @if ($porConfirmar->isEmpty())
                <div class="admin-empty py-4"><i class="fas fa-check-circle" aria-hidden="true"></i> Todo confirmado.</div>
            @else
                <ul class="ops-side-list">
                    @foreach ($porConfirmar as $servicio)
                        <li>
                            <span>
                                <a href="{{ route('operaciones.index', ['fecha' => $servicio->fecha->toDateString()]) }}">{{ $servicio->titulo }}</a>
                                <small>{{ $servicio->fecha->locale('es')->isoFormat('ddd D MMM') }}{{ $servicio->hora_corta ? ' · ' . $servicio->hora_corta : '' }} · {{ $servicio->programa->codigo }}{{ $servicio->proveedor ? ' · ' . $servicio->proveedor->nombre : ' · sin proveedor' }}</small>
                            </span>
                            <span class="svc-badge svc-badge--{{ $servicio->estado }}">{{ \App\Models\Servicio::ESTADOS[$servicio->estado] }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </aside>
    </div>
    @include('servicios.partials.modales')
@endsection
