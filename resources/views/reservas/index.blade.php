{{--
    Bandeja del área de reservas (ReservasController@index): servicios por estado con su proveedor y su situación de pago,
    más bandeja integrada de mensajería y seguimiento de respuestas con los proveedores.
    Acciones: solicitar/confirmar, pagar, historial, orden de servicio, redactar y consultar mensajes.
    Estilos: resources/sass/admin/_reservas.scss, _mensajes.scss · JS: resources/js/servicio-acciones.js
--}}
@extends('layouts.app')
@section('titulo', 'Reservas')

@php
    $hoy = \Carbon\Carbon::today();
    $pagoEtiqueta = ['pendiente' => ['Por pagar', 'pendiente'], 'parcial' => ['Pago parcial', 'solicitado'], 'pagado' => ['Pagado', 'confirmado'], 'sin_costo' => ['Sin costo', 'cancelado']];
    $lote = in_array($bandeja, ['sin_asignar', 'por_reservar', 'solicitados'], true) && auth()->user()->can('servicios.gestionar');
    $filtros = fn (array $cambios) => route('reservas.index', array_filter(array_merge(request()->only('bandeja', 'periodo', 'proveedor_id', 'tipo', 'q'), $cambios)));
@endphp

@section('contenido')
    <div class="ae-admin-page-header">
        <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-concierge-bell"></i> Reservas</h1>
        <small class="ae-admin-page-desc">Centro de control de reservas: gestión de servicios, asignación de proveedores, pagos y mensajería integrada.</small>
    </div>

    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
        @if (session($key))
            <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
    @endforeach
    @if (isset($errors) && $errors->any())
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
                @if ($clave === 'mensajes')
                    <i class="fas fa-envelope-open-text me-1" aria-hidden="true"></i>
                @endif
                {{ $etiqueta }}
                <span class="prog-tabs__count {{ $clave === 'mensajes' && ($conteos[$clave] ?? 0) > 0 ? 'bg-warning text-dark' : '' }}">{{ $conteos[$clave] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- ========================================================================= --}}
    {{-- PESTAÑA: MENSAJES Y COMUNICACIONES CON PROVEEDORES                      --}}
    {{-- ========================================================================= --}}
    @if ($bandeja === 'mensajes')
        {{-- Servicios solicitados esperando respuesta --}}
        @if ($esperando->isNotEmpty())
            <section class="card dash-card msg-waiting mb-4" aria-labelledby="msg-waiting-title">
                <h2 class="msg-card__title" id="msg-waiting-title">
                    <i class="fas fa-hourglass-half text-warning" aria-hidden="true"></i> Esperando respuesta del proveedor 
                    <span class="msg-thread__count">{{ $esperando->count() }}</span>
                </h2>
                <ul class="msg-waiting__list">
                    @foreach ($esperando as $itemServicio)
                        @php 
                            $ultimo = $itemServicio->mensajes->first(); 
                            $dias = (int) $ultimo->created_at->diffInDays(now()); 
                        @endphp
                        <li class="msg-waiting__item {{ $dias >= 2 ? 'is-late' : '' }}">
                            <div>
                                <strong>{{ $itemServicio->titulo }}</strong>
                                <span>{{ $itemServicio->proveedor?->nombre }} · {{ $itemServicio->programa?->codigo }} · servicio el {{ $itemServicio->fecha->format('d/m') }}</span>
                            </div>
                            <span class="msg-waiting__since" title="{{ $ultimo->created_at->format('d/m/Y H:i') }}">
                                <i class="far fa-clock me-1"></i> Enviado {{ $ultimo->created_at->locale('es')->diffForHumans() }}
                            </span>
                            <a href="{{ route('servicios.mensaje', $itemServicio) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-envelope-open-text me-1"></i> Abrir hilo
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        {{-- Filtros de mensajería --}}
        <form method="GET" action="{{ route('reservas.index') }}" class="res-filters">
            <input type="hidden" name="bandeja" value="mensajes">
            <div class="res-filters__search">
                <label for="msg-q" class="visually-hidden">Buscar</label>
                <i class="fas fa-search" aria-hidden="true"></i>
                <input id="msg-q" type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por asunto, programa o servicio...">
            </div>
            <label for="msg-tipo" class="visually-hidden">Tipo</label>
            <select id="msg-tipo" name="tipo" class="form-select" data-autosubmit>
                <option value="">Todos los tipos</option>
                @foreach (\App\Models\MensajeProveedor::TIPOS as $clave => $etiqueta)
                    <option value="{{ $clave }}" @selected(request('tipo') === $clave)>{{ $etiqueta }}</option>
                @endforeach
            </select>
            <label for="msg-prov" class="visually-hidden">Proveedor</label>
            <select id="msg-prov" name="proveedor_id" class="form-select" data-autosubmit>
                <option value="">Todos los proveedores</option>
                @foreach ($proveedoresMensajes as $prov)
                    <option value="{{ $prov->id }}" @selected((string) request('proveedor_id') === (string) $prov->id)>{{ $prov->nombre }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Buscar</button>
            @if (request()->filled('q') || request()->filled('tipo') || request()->filled('proveedor_id'))
                <a href="{{ route('reservas.index', ['bandeja' => 'mensajes']) }}" class="btn btn-outline-secondary">Limpiar</a>
            @endif
        </form>

        {{-- Listado de mensajes --}}
        @if ($mensajes->isEmpty())
            <div class="card dash-card">
                <div class="admin-empty">
                    <i class="fas fa-inbox" aria-hidden="true"></i> Todavía no hay mensajes registrados con esos filtros.
                    Para escribir a un proveedor, abre cualquier servicio desde las demás pestañas de Reservas.
                </div>
            </div>
        @else
            <div class="card dash-card">
                <ul class="msg-list">
                    @foreach ($mensajes as $mensaje)
                        <li class="msg-list__item msg-list__item--{{ $mensaje->direccion }} {{ $mensaje->fallido ? 'is-failed' : '' }}">
                            <i class="msg-list__icon fas {{ $mensaje->direccion === 'recibido' ? 'fa-reply' : 'fa-paper-plane' }}" aria-hidden="true"></i>
                            <div class="msg-list__main">
                                <div class="msg-list__head">
                                    <span class="msg-list__type">{{ \App\Models\MensajeProveedor::TIPOS[$mensaje->tipo] ?? $mensaje->tipo }}</span>
                                    <strong>{{ $mensaje->proveedor?->nombre }}</strong>
                                </div>
                                <p class="msg-list__subject">
                                    {{ $mensaje->direccion === 'recibido' ? (\App\Models\MensajeProveedor::RESULTADOS[$mensaje->resultado] ?? '') . ($mensaje->cuerpo !== (\App\Models\MensajeProveedor::RESULTADOS[$mensaje->resultado] ?? null) ? ' · ' . $mensaje->cuerpo : '') : $mensaje->asunto }}
                                </p>
                                <span class="msg-list__meta">
                                    {{ $mensaje->programa?->codigo }}@if ($mensaje->servicio) · {{ $mensaje->servicio->titulo }}@endif
                                    · {{ $mensaje->user?->name ?? 'Sistema' }}
                                    @if ($mensaje->con_orden) · <i class="fas fa-paperclip" aria-hidden="true"></i> orden adjunta @endif
                                    @if ($mensaje->fallido) · <span class="text-danger"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> No se envió</span> @endif
                                </span>
                            </div>
                            <time class="msg-list__when" datetime="{{ $mensaje->created_at->toIso8601String() }}" title="{{ $mensaje->created_at->format('d/m/Y H:i') }}">
                                {{ $mensaje->created_at->locale('es')->isoFormat('D MMM, HH:mm') }}
                            </time>
                            @if ($mensaje->servicio)
                                <a href="{{ route('servicios.mensaje', $mensaje->servicio) }}" class="btn btn-sm btn-light" title="Abrir el hilo del servicio" aria-label="Abrir el hilo de {{ $mensaje->servicio->titulo }}">
                                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="prog-pagination">{{ $mensajes->links('pagination::bootstrap-5') }}</div>
        @endif

    {{-- ========================================================================= --}}
    {{-- PESTAÑAS OPERATIVAS: SERVICIOS POR ESTADO                               --}}
    {{-- ========================================================================= --}}
    @else
        {{-- Aviso contextual en "Solicitados" si hay respuestas pendientes --}}
        @if ($bandeja === 'solicitados' && $esperando->isNotEmpty())
            <div class="alert alert-warning py-2 px-3 small d-flex align-items-center justify-content-between mb-3 shadow-sm">
                <div>
                    <i class="fas fa-hourglass-half me-1"></i>
                    Tienes <strong>{{ $esperando->count() }}</strong> {{ $esperando->count() === 1 ? 'servicio solicitado esperando' : 'servicios solicitados esperando' }} respuesta del proveedor.
                </div>
                <a href="{{ $filtros(['bandeja' => 'mensajes']) }}" class="btn btn-sm btn-outline-dark">Ver seguimiento en Mensajes &rarr;</a>
            </div>
        @endif

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
    @endif
@endsection
