{{--
    Bandeja de mensajes de Reservas con los proveedores (MensajeProveedorController@index):
    solicitudes esperando respuesta y el historial de todo lo enviado y respondido.
    Estilos: resources/sass/admin/_mensajes.scss
--}}
@extends('layouts.app')
@section('titulo', 'Mensajes a proveedores')

@section('contenido')
    <div class="ae-admin-page-header">
        <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-envelope-open-text"></i> Mensajes a proveedores</h1>
        <small class="ae-admin-page-desc">Lo que Reservas envía desde {{ config('mail.reservas.address') }} y las respuestas registradas. Para escribir, abre el servicio desde la Bandeja de Reservas.</small>
    </div>

    @if ($esperando->isNotEmpty())
        <section class="card dash-card msg-waiting" aria-labelledby="msg-waiting-title">
            <h2 class="msg-card__title" id="msg-waiting-title"><i class="fas fa-hourglass-half" aria-hidden="true"></i> Esperando respuesta <span class="msg-thread__count">{{ $esperando->count() }}</span></h2>
            <ul class="msg-waiting__list">
                @foreach ($esperando as $servicio)
                    @php $ultimo = $servicio->mensajes->first(); $dias = (int) $ultimo->created_at->diffInDays(now()); @endphp
                    <li class="msg-waiting__item {{ $dias >= 2 ? 'is-late' : '' }}">
                        <div>
                            <strong>{{ $servicio->titulo }}</strong>
                            <span>{{ $servicio->proveedor?->nombre }} · {{ $servicio->programa?->codigo }} · servicio el {{ $servicio->fecha->format('d/m') }}</span>
                        </div>
                        <span class="msg-waiting__since" title="{{ $ultimo->created_at->format('d/m/Y H:i') }}">Enviado {{ $ultimo->created_at->locale('es')->diffForHumans() }}</span>
                        <a href="{{ route('servicios.mensaje', $servicio) }}" class="btn btn-sm btn-outline-primary">Abrir</a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    <form method="GET" action="{{ route('mensajes.index') }}" class="res-filters">
        <div class="res-filters__search">
            <label for="msg-q" class="visually-hidden">Buscar</label>
            <i class="fas fa-search" aria-hidden="true"></i>
            <input id="msg-q" type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Asunto o programa">
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
            @foreach ($proveedores as $proveedor)
                <option value="{{ $proveedor->id }}" @selected((string) request('proveedor_id') === (string) $proveedor->id)>{{ $proveedor->nombre }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    @if ($mensajes->isEmpty())
        <div class="card dash-card"><div class="admin-empty"><i class="fas fa-inbox" aria-hidden="true"></i> Todavía no hay mensajes con esos filtros. Escribe a un proveedor desde un servicio de la Bandeja de Reservas.</div></div>
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
                                @if ($mensaje->con_orden) · <i class="fas fa-paperclip" aria-hidden="true"></i> orden @endif
                                @if ($mensaje->fallido) · <span class="text-danger"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> No se envió</span> @endif
                            </span>
                        </div>
                        <time class="msg-list__when" datetime="{{ $mensaje->created_at->toIso8601String() }}" title="{{ $mensaje->created_at->format('d/m/Y H:i') }}">{{ $mensaje->created_at->locale('es')->isoFormat('D MMM, HH:mm') }}</time>
                        @if ($mensaje->servicio)
                            <a href="{{ route('servicios.mensaje', $mensaje->servicio) }}" class="btn btn-sm btn-light" title="Abrir el hilo del servicio" aria-label="Abrir el hilo de {{ $mensaje->servicio->titulo }}"><i class="fas fa-chevron-right" aria-hidden="true"></i></a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="prog-pagination">{{ $mensajes->links('pagination::bootstrap-5') }}</div>
    @endif
@endsection
