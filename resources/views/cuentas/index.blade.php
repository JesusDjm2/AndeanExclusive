{{-- Cuentas por pagar a proveedores (CuentasPorPagarController@index). Estilos: resources/sass/admin/_operaciones.scss --}}
@extends('layouts.app')
@section('titulo', 'Cuentas por pagar')

@php
    // Reservas solicita el pago; lo registra Contabilidad.
    $canEdit = auth()->user()->can('pagos.registrar');
    $hoy = \Carbon\Carbon::today();
    $fmt = fn ($monto, $moneda) => \App\Models\Servicio::formatoMonto((float) $monto, $moneda);
    $totalesTexto = fn ($totales) => $totales->map(fn ($monto, $moneda) => $fmt($monto, $moneda))->implode(' + ') ?: '—';
@endphp

@section('contenido')
    <div class="ae-admin-page-header">
        <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-file-invoice-dollar"></i> Cuentas por pagar</h1>
        <small class="ae-admin-page-desc">Servicios confirmados con el proveedor, de programas confirmados. Cada uno vence, a más tardar, el día en que se presta.</small>
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

    <nav class="prog-tabs" aria-label="Filtro">
        @foreach (\App\Http\Controllers\CuentasPorPagarController::FILTROS as $clave => $etiqueta)
            <a href="{{ route('cuentas.index', array_filter(['filtro' => $clave, 'proveedor_id' => request('proveedor_id'), 'categoria' => $categoria])) }}"
                class="prog-tabs__tab {{ $filtro === $clave ? 'is-active' : '' }}" @if ($filtro === $clave) aria-current="page" @endif>
                {{ $etiqueta }} <span class="prog-tabs__count">{{ $conteos[$clave] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="ops-toolbar mt-3">
        <form method="GET" action="{{ route('cuentas.index') }}">
            <input type="hidden" name="filtro" value="{{ $filtro }}">
            <input type="hidden" name="categoria" value="{{ $categoria }}">
            <label for="cxp-prov" class="visually-hidden">Proveedor</label>
            <select id="cxp-prov" name="proveedor_id" class="form-select form-select-sm" data-autosubmit>
                <option value="">Todos los proveedores</option>
                @foreach ($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}" @selected((int) request('proveedor_id') === $proveedor->id)>{{ $proveedor->nombre }}</option>
                @endforeach
            </select>
            <noscript><button type="submit" class="btn btn-sm btn-outline-primary">Filtrar</button></noscript>
        </form>
        <p class="cxp-total mb-0">Total {{ $filtro === 'pagadas' ? 'pagado' : 'por pagar' }}: <strong>{{ $totalesTexto($totales) }}</strong></p>
    </div>

    @if ($categorias->isNotEmpty())
        <nav class="cxp-cats" aria-label="Tipo de proveedor">
            @foreach ($categorias as $cat)
                <a href="{{ route('cuentas.index', array_filter(['filtro' => $filtro, 'proveedor_id' => request('proveedor_id'), 'categoria' => $cat['clave']])) }}"
                    class="cxp-cats__tab {{ $categoria === $cat['clave'] ? 'active' : '' }}" @if ($categoria === $cat['clave']) aria-current="page" @endif>
                    <span class="cxp-cats__name">{{ $cat['nombre'] }} <span class="cxp-cats__count">{{ $cat['cantidad'] }}</span></span>
                    <span class="cxp-cats__total">{{ $totalesTexto($cat['totales']) }}</span>
                </a>
            @endforeach
        </nav>
    @endif

    @if ($grupos->isEmpty())
        <div class="card dash-card"><div class="admin-empty"><i class="fas fa-check-circle" aria-hidden="true"></i> No hay servicios en esta vista.</div></div>
    @endif

    @foreach ($grupos as $grupo)
        @php $formId = 'cxp-pago-' . ($grupo['proveedor']?->id ?? 0); @endphp
        <section class="card dash-card mb-3 cxp-group" aria-label="{{ $grupo['proveedor']?->nombre ?? 'Sin proveedor' }}">
            <div class="card-header dash-card__header">
                <div>
                    <h2 class="dash-card__title">{{ $grupo['proveedor']?->nombre ?? 'Sin proveedor asignado' }}</h2>
                    @if ($grupo['proveedor']?->telefono || $grupo['proveedor']?->ruc)
                        <small class="text-muted">{{ collect([$grupo['proveedor']->ruc ? 'RUC ' . $grupo['proveedor']->ruc : null, $grupo['proveedor']->telefono])->filter()->implode(' · ') }}</small>
                    @endif
                </div>
                <strong class="cxp-group__total">{{ $totalesTexto($grupo['totales']) }}</strong>
            </div>
            <div class="table-responsive">
                <table class="table ae-admin-data-table mb-0">
                    <thead>
                        <tr>
                            @if ($canEdit && $filtro !== 'pagadas')<th class="cxp-check"><span class="visually-hidden">Elegir</span></th>@endif
                            <th>Fecha</th><th>Servicio</th><th>Programa</th><th class="text-end">Importe</th>
                            <th>{{ $filtro === 'pagadas' ? 'Pagado' : 'Situación' }}</th><th><span class="visually-hidden">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grupo['servicios'] as $servicio)
                            @php
                                $vencido = ! $servicio->pagado_at && $servicio->fecha->lt($hoy);
                                $dias = (int) $hoy->diffInDays($servicio->fecha);
                            @endphp
                            <tr id="servicio-{{ $servicio->id }}">
                                @if ($canEdit && $filtro !== 'pagadas')
                                    <td class="cxp-check">
                                        <input type="checkbox" class="form-check-input" name="servicios[]" value="{{ $servicio->id }}" form="{{ $formId }}"
                                            aria-label="Pagar {{ $servicio->titulo }}">
                                    </td>
                                @endif
                                <td class="text-nowrap">{{ $servicio->fecha->format('d/m/Y') }}</td>
                                <td>
                                    {{ $servicio->titulo }}
                                    <small class="d-block text-muted">{{ $servicio->tipo_etiqueta }} · {{ $fmt($servicio->costo, $servicio->moneda) }} {{ \App\Models\Servicio::COSTO_POR[$servicio->costo_por] ?? '' }}{{ $servicio->costo_por === 'persona' ? ' × ' . $servicio->programa->pagantes() : '' }}</small>
                                </td>
                                <td>
                                    <x-programa-codigo :programa="$servicio->programa" :codigo="$servicio->programa->codigo" tab="itinerario" />
                                    <small class="d-block text-muted">{{ \Illuminate\Support\Str::limit($servicio->programa->nombre, 32) }}</small>
                                </td>
                                <td class="text-end text-nowrap">
                                    <span class="fw-semibold">{{ $fmt($servicio->importe, $servicio->moneda) }}</span>
                                    @if ($servicio->situacion_pago === 'parcial')
                                        <small class="d-block text-muted">Pagado {{ $fmt($servicio->pagado_proveedor, $servicio->moneda) }}</small>
                                        <small class="d-block fw-semibold" style="color: var(--ae-warn)">Saldo {{ $fmt($servicio->saldo_proveedor, $servicio->moneda) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if ($servicio->pagado_at)
                                        <span class="svc-badge svc-badge--confirmado">{{ $servicio->pagado_at->format('d/m/Y') }}</span>
                                        @if ($servicio->pagos->last()?->referencia)<small class="d-block text-muted">{{ $servicio->pagos->last()->referencia }}</small>@endif
                                        @if ($canEdit)
                                            <form action="{{ route('cuentas.anular', $servicio) }}" method="POST" class="d-inline"
                                                data-confirm="Se anularán los pagos registrados de «{{ $servicio->titulo }}». Quedarán en su historial." data-confirm-motivo data-confirm-title="¿Anular pago?" data-confirm-button="Sí, anular" data-confirm-danger>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link p-0 small">Anular</button>
                                            </form>
                                        @endif
                                    @elseif ($vencido)
                                        <span class="svc-badge svc-badge--pendiente">Vencido</span>
                                    @else
                                        <span class="svc-badge svc-badge--solicitado">{{ $dias === 0 ? 'Hoy' : ($dias === 1 ? 'Mañana' : 'En ' . $dias . ' días') }}</span>
                                    @endif
                                    @if ($servicio->situacion_pago === 'parcial')
                                        <span class="svc-badge svc-badge--solicitado mt-1">Pago parcial</span>
                                    @endif
                                </td>
                                <td class="text-end">@include('servicios.partials.acciones', ['servicio' => $servicio])</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($canEdit && $filtro !== 'pagadas')
                <form id="{{ $formId }}" action="{{ route('cuentas.pagar') }}" method="POST" class="cxp-pay">
                    @csrf
                    <span class="cxp-pay__label">Pagar el saldo de los marcados:</span>
                    <label class="visually-hidden" for="{{ $formId }}-fecha">Fecha de pago</label>
                    <input id="{{ $formId }}-fecha" type="date" name="fecha" value="{{ $hoy->toDateString() }}" max="{{ $hoy->toDateString() }}" class="form-control form-control-sm" required>
                    <label class="visually-hidden" for="{{ $formId }}-ref">Referencia</label>
                    <label class="visually-hidden" for="{{ $formId }}-metodo">Método</label>
                    <select id="{{ $formId }}-metodo" name="metodo" class="form-select form-select-sm">
                        <option value="">Método…</option>
                        @foreach (\App\Models\ProveedorPago::METODOS as $clave => $etiqueta)
                            <option value="{{ $clave }}">{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                    <input id="{{ $formId }}-ref" type="text" name="referencia" maxlength="255" class="form-control form-control-sm" placeholder="N.º de operación / factura">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-check" aria-hidden="true"></i> Marcar como pagados</button>
                </form>
            @endif
        </section>
    @endforeach
    @include('servicios.partials.modales')
@endsection
