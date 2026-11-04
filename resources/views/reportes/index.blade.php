{{-- Reportes de gestión (ReporteController@index). Costo y margen solo con el permiso margen.ver. --}}
@extends('layouts.app')
@section('titulo', 'Reportes')

@php
    $verMargen = auth()->user()->can('margen.ver');
    $usd = fn ($n) => '$' . number_format((float) $n, 2);
    $periodo = ['desde' => $desde->toDateString(), 'hasta' => $hasta->toDateString()];
    $grupos = ['mes' => ['Por mes de inicio', $porMes, 'Mes'], 'agente' => ['Por agente', $porAgente, 'Agente'], 'origen' => ['Por origen del cliente', $porOrigen, 'Origen']];
@endphp

@section('contenido')
    <div class="ae-admin-page-header">
        <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-chart-bar"></i> Reportes</h1>
        <small class="ae-admin-page-desc">Programas confirmados que inician en el periodo elegido. Montos en dólares.</small>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="GET" action="{{ route('reportes.index') }}" class="mov-filters">
        <div>
            <label for="rep-desde" class="prog-label">Desde</label>
            <input id="rep-desde" type="date" name="desde" value="{{ $periodo['desde'] }}" class="form-control form-control-sm">
        </div>
        <div>
            <label for="rep-hasta" class="prog-label">Hasta</label>
            <input id="rep-hasta" type="date" name="hasta" value="{{ $periodo['hasta'] }}" class="form-control form-control-sm">
        </div>
        <div class="mov-filters__actions"><button type="submit" class="btn btn-sm btn-primary">Ver</button></div>
    </form>

    <div class="dash-kpis">
        <article class="kpi"><div><p class="kpi__label">Programas</p><p class="kpi__value">{{ $totales['programas'] }}</p><p class="kpi__meta">{{ $totales['pasajeros'] }} pasajeros</p></div></article>
        <article class="kpi"><div><p class="kpi__label">Ventas</p><p class="kpi__value">{{ $usd($totales['venta']) }}</p></div></article>
        <article class="kpi"><div><p class="kpi__label">Cobrado</p><p class="kpi__value">{{ $usd($totales['cobrado']) }}</p><p class="kpi__meta">{{ $usd($totales['por_cobrar']) }} por cobrar</p></div></article>
        @if ($verMargen)
            <article class="kpi"><div><p class="kpi__label">Margen</p><p class="kpi__value">{{ $usd($totales['margen']) }}</p><p class="kpi__meta">{{ number_format($totales['margen_pct'], 1) }} % · costo {{ $usd($totales['costo']) }}</p></div></article>
        @endif
    </div>

    @foreach ($grupos as $clave => [$titulo, $filas, $columna])
        <section class="prog-section" aria-labelledby="rep-{{ $clave }}">
            <div class="prog-section__header">
                <h2 class="prog-section__title" id="rep-{{ $clave }}">{{ $titulo }}</h2>
                <a href="{{ route('reportes.exportar', ['tabla' => $clave] + $periodo) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file-excel" aria-hidden="true"></i> Excel</a>
            </div>
            @if ($filas->isEmpty())
                <div class="admin-empty py-4">Sin programas confirmados en este periodo.</div>
            @else
                <div class="table-responsive">
                    <table class="table ae-admin-data-table mb-0">
                        <thead>
                            <tr>
                                <th>{{ $columna }}</th><th class="text-end">Programas</th><th class="text-end">Pasajeros</th><th class="text-end">Venta</th><th class="text-end">Cobrado</th><th class="text-end">Por cobrar</th>
                                @if ($verMargen)<th class="text-end">Costo</th><th class="text-end">Margen</th><th class="text-end">%</th>@endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filas as $fila)
                                <tr>
                                    <td class="fw-semibold">{{ $fila['etiqueta'] }}</td>
                                    <td class="text-end">{{ $fila['programas'] }}</td>
                                    <td class="text-end">{{ $fila['pasajeros'] }}</td>
                                    <td class="text-end">{{ $usd($fila['venta']) }}</td>
                                    <td class="text-end">{{ $usd($fila['cobrado']) }}</td>
                                    <td class="text-end">{{ $usd($fila['por_cobrar']) }}</td>
                                    @if ($verMargen)
                                        <td class="text-end">{{ $usd($fila['costo']) }}</td>
                                        <td class="text-end {{ $fila['margen'] < 0 ? 'text-danger' : '' }}">{{ $usd($fila['margen']) }}</td>
                                        <td class="text-end">{{ number_format($fila['margen_pct'], 1) }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($clave === 'agente')
                    <p class="prog-input-hint px-3 py-2 mb-0">Un programa con dos agentes cuenta para los dos, así que esta tabla no suma el total.</p>
                @endif
            @endif
        </section>
    @endforeach

    <section class="prog-section" aria-labelledby="rep-consultas">
        <div class="prog-section__header">
            <h2 class="prog-section__title" id="rep-consultas">Consultas recibidas en el periodo</h2>
            <span class="prog-section__subtitle">{{ $consultas['recibidas'] }} recibidas · {{ number_format($consultas['conversion'], 1) }} % ganadas</span>
        </div>
        <div class="prog-section__body">
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="ayuda-subtitulo">Cómo van</p>
                    <ul class="list-unstyled mb-0">
                        @foreach ($consultas['estados'] as $estado)
                            <li class="d-flex justify-content-between border-bottom py-1"><span>{{ $estado['etiqueta'] }}</span><strong>{{ $estado['cantidad'] }}</strong></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-6">
                    <p class="ayuda-subtitulo">Por qué se perdieron</p>
                    @forelse ($consultas['motivos'] as $motivo)
                        <div class="d-flex justify-content-between border-bottom py-1"><span>{{ $motivo['etiqueta'] }}</span><strong>{{ $motivo['cantidad'] }}</strong></div>
                    @empty
                        <p class="text-muted mb-0">Sin datos todavía.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    @if ($verMargen)
        <section class="prog-section" aria-labelledby="rep-proveedor">
            <div class="prog-section__header">
                <h2 class="prog-section__title" id="rep-proveedor">Por proveedor</h2>
                <a href="{{ route('reportes.exportar', ['tabla' => 'proveedor'] + $periodo) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file-excel" aria-hidden="true"></i> Excel</a>
            </div>
            @if ($porProveedor->isEmpty())
                <div class="admin-empty py-4">Sin servicios con proveedor en este periodo.</div>
            @else
                <div class="table-responsive">
                    <table class="table ae-admin-data-table mb-0">
                        <thead><tr><th>Proveedor</th><th class="text-end">Servicios</th><th class="text-end">Costo</th><th class="text-end">Pagado</th><th class="text-end">Por pagar</th></tr></thead>
                        <tbody>
                            @foreach ($porProveedor as $fila)
                                <tr>
                                    <td class="fw-semibold">{{ $fila['etiqueta'] }}</td>
                                    <td class="text-end">{{ $fila['servicios'] }}</td>
                                    <td class="text-end">{{ $usd($fila['costo']) }}</td>
                                    <td class="text-end">{{ $usd($fila['pagado']) }}</td>
                                    <td class="text-end">{{ $usd($fila['por_pagar']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    @endif
@endsection
