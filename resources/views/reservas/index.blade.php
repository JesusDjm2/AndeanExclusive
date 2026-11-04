{{--
    Bandeja del área de reservas (ReservasController@index): servicios por estado con su proveedor y su situación de pago.
    Acciones: solicitar/confirmar, pagar, historial, orden de servicio; en lote: marcar solicitados o cancelar.
    Estilos: resources/sass/admin/_reservas.scss · JS: resources/js/servicio-acciones.js
--}}
@extends('layouts.app')
@section('titulo', 'Reservas')

@php
    $hoy = \Carbon\Carbon::today();
    $pagoEtiqueta = ['pendiente' => ['Por pagar', 'pendiente'], 'parcial' => ['Pago parcial', 'solicitado'], 'pagado' => ['Pagado', 'confirmado'], 'sin_costo' => ['Sin costo', 'cancelado']];
    $lote = in_array($bandeja, ['sin_asignar', 'por_reservar', 'solicitados'], true) && auth()->user()->can('servicios.gestionar');
    $filtros = fn (array $cambios) => route('reservas.index', array_filter(array_merge(request()->only('bandeja', 'periodo', 'proveedor_id', 'q'), $cambios)));
@endphp

@section('contenido')
    <div class="ae-admin-page-header">
        <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-concierge-bell"></i> Reservas</h1>
        <small class="ae-admin-page-desc">Servicios a gestionar con los proveedores: solicitar, confirmar con su n.º de reserva y pagar.</small>
    </div>

    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
        @if (session($key))
            <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
    @endforeach
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    @if ($urgentes)
        <div class="res-urgent" role="status">
            <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
            <span><strong>{{ $urgentes }} {{ $urgentes === 1 ? 'servicio' : 'servicios' }}</strong> sin confirmar en los próximos 3 días.</span>
            <a href="{{ route('reservas.index', ['bandeja' => $bandejaUrgentes, 'periodo' => '7']) }}">Ver</a>
        </div>
    @endif

    <nav class="prog-tabs" aria-label="Estado de la reserva">
        @foreach (\App\Http\Controllers\ReservasController::BANDEJAS as $clave => [$etiqueta])
            <a href="{{ $filtros(['bandeja' => $clave, 'page' => null]) }}" class="prog-tabs__tab {{ $bandeja === $clave ? 'is-active' : '' }}" @if ($bandeja === $clave) aria-current="page" @endif>
                {{ $etiqueta }} <span class="prog-tabs__count">{{ $conteos[$clave] }}</span>
            </a>
        @endforeach
    </nav>

    <form method="GET" action="{{ route('reservas.index') }}" class="res-filters">
        <input type="hidden" name="bandeja" value="{{ $bandeja }}">
        <div class="res-filters__search">
            <label for="res-q" class="visually-hidden">Buscar</label>
            <i class="fas fa-search" aria-hidden="true"></i>
            <input id="res-q" type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Servicio, programa o n.º de confirmación">
        </div>
        <label for="res-periodo" class="visually-hidden">Periodo</label>
        <select id="res-periodo" name="periodo" class="form-select" data-autosubmit>
            @foreach (\App\Http\Controllers\ReservasController::PERIODOS as $clave => [$etiqueta])
                <option value="{{ $clave }}" @selected((string) $periodo === (string) $clave)>{{ $etiqueta }}</option>
            @endforeach
        </select>
        <label for="res-prov" class="visually-hidden">Proveedor</label>
        <select id="res-prov" name="proveedor_id" class="form-select" data-autosubmit>
            <option value="">Todos los proveedores</option>
            <option value="sin" @selected(request('proveedor_id') === 'sin')>— Sin proveedor asignado —</option>
            @foreach ($proveedores as $proveedor)
                <option value="{{ $proveedor->id }}" @selected((string) request('proveedor_id') === (string) $proveedor->id)>{{ $proveedor->nombre }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    @if ($servicios->isEmpty())
        <div class="card dash-card"><div class="admin-empty"><i class="fas fa-check-circle" aria-hidden="true"></i> No hay servicios en esta bandeja con esos filtros.</div></div>
    @else
        @if ($lote)
            <form id="res-lote" action="{{ route('reservas.lote') }}" method="POST" class="res-bulk" data-res-bulk>
                @csrf
                <label class="res-bulk__all"><input type="checkbox" class="form-check-input" data-res-all> <span>Seleccionar todos</span></label>
                <span class="res-bulk__count" data-res-count>0 seleccionados</span>
                <label for="res-accion" class="visually-hidden">Acción</label>
                <select id="res-accion" name="accion" class="form-select form-select-sm">
                    @if ($bandeja === 'por_reservar')
                        <option value="solicitado">Marcar como solicitados</option>
                    @elseif ($bandeja === 'sin_asignar')
                    @else
                        <option value="pendiente">Volver a «Por reservar»</option>
                    @endif
                    <option value="cancelado">Cancelar</option>
                </select>
                <button type="submit" class="btn btn-sm btn-outline-primary" data-res-apply disabled>Aplicar</button>
            </form>
        @endif

        <div class="card dash-card">
            <ul class="res-list">
                @foreach ($servicios as $servicio)
                    @php
                        $dias = (int) $hoy->diffInDays($servicio->fecha, false);
                        $urgente = in_array($servicio->estado, \App\Models\Servicio::ABIERTOS, true) && $dias >= 0 && $dias <= 3;
                        $cuando = match (true) { $dias === 0 => 'Hoy', $dias === 1 => 'Mañana', $dias > 1 => 'En ' . $dias . ' días', default => 'Hace ' . abs($dias) . ' días' };
                        [$pagoTexto, $pagoClase] = $pagoEtiqueta[$servicio->situacion_pago];
                    @endphp
                    <li id="servicio-{{ $servicio->id }}" class="res-item {{ $urgente ? 'is-urgent' : '' }}">
                        @if ($lote)
                            <input type="checkbox" class="form-check-input res-item__check" name="servicios[]" value="{{ $servicio->id }}" form="res-lote"
                                aria-label="Seleccionar {{ $servicio->titulo }}" data-res-check>
                        @endif
                        <div class="res-item__when">
                            <strong>{{ $servicio->fecha->locale('es')->isoFormat('ddd D MMM') }}</strong>
                            <span>{{ $servicio->hora_corta ?? 'Sin hora' }}</span>
                            <small class="{{ $urgente ? 'text-danger fw-semibold' : '' }}">{{ $cuando }}</small>
                        </div>
                        <div class="res-item__main">
                            <p class="res-item__title"><i class="fas {{ $servicio->icono }}" aria-hidden="true"></i> {{ $servicio->titulo }}</p>
                            <p class="res-item__meta">
                                <a href="{{ route('programas.edit', [$servicio->programa, 'tab' => 'itinerario']) }}">{{ $servicio->programa->codigo }}</a>
                                · {{ \Illuminate\Support\Str::limit($servicio->programa->cliente?->etiqueta ?? $servicio->programa->nombre, 40) }}
                                · {{ $servicio->programa->paxs->count() }} pax
                            </p>
                        </div>
                        <div class="res-item__provider">
                            @if ($servicio->proveedor)
                                <strong>{{ $servicio->proveedor->nombre }}</strong>
                                @if ($servicio->proveedor->telefono)
                                    <a href="https://wa.me/{{ ltrim(preg_replace('/[^0-9+]/', '', $servicio->proveedor->telefono), '+') }}" target="_blank" rel="noopener"><i class="fab fa-whatsapp" aria-hidden="true"></i> {{ $servicio->proveedor->telefono }}</a>
                                @endif
                            @else
                                <span class="res-item__noprov"><i class="fas fa-user-plus" aria-hidden="true"></i> Proveedor por asignar</span>
                            @endif
                        </div>
                        <div class="res-item__status">
                            <span class="svc-badge svc-badge--{{ $servicio->estado }}">{{ \App\Models\Servicio::ESTADOS[$servicio->estado] }}</span>
                            @if ($servicio->confirmacion)<small>Conf. <strong>{{ $servicio->confirmacion }}</strong></small>@endif
                        </div>
                        <div class="res-item__pay">
                            @if ($servicio->estado === 'sin_asignar')
                                <span class="svc-badge svc-badge--sin_asignar">Cotizado</span>
                                <small>{{ $servicio->costo_estimado !== null ? \App\Models\Servicio::formatoMonto($servicio->importe_estimado, $servicio->moneda) : 'Sin costo estimado' }}</small>
                            @else
                            <span class="svc-badge svc-badge--{{ $pagoClase }}">{{ $pagoTexto }}</span>
                            @if ($servicio->situacion_pago !== 'sin_costo')
                                <small>{{ $servicio->saldo_proveedor > 0 ? 'Saldo ' . \App\Models\Servicio::formatoMonto($servicio->saldo_proveedor, $servicio->moneda) : \App\Models\Servicio::formatoMonto($servicio->importe, $servicio->moneda) }}</small>
                            @endif
                            @if (($servicio->desvio_costo ?? 0) > 0.009)
                                <small class="res-item__over"><i class="fas fa-arrow-up" aria-hidden="true"></i> {{ \App\Models\Servicio::formatoMonto($servicio->desvio_costo, $servicio->moneda) }} sobre lo cotizado</small>
                            @endif
                            @endif
                        </div>
                        <div class="res-item__actions">
                            @include('servicios.partials.acciones', ['servicio' => $servicio])
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="prog-pagination">{{ $servicios->links('pagination::bootstrap-5') }}</div>
    @endif

    @include('servicios.partials.modales')
@endsection
