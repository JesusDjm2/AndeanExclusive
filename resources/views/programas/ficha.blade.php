{{--
    Ficha Ejecutiva y de Operaciones del Grupo / Programa (Admin Show).
    Centro de mando de consulta 360° para operaciones, ventas y gerencia sin riesgo de editar datos por error.
--}}
@extends('layouts.app')
@section('titulo', 'Ficha: ' . $programa->nombre)

@php
    $canEdit = auth()->user()?->can('programas.gestionar');
    $verCotizacion = auth()->user()?->can('cotizacion.ver');
    $inicio = $programa->inicio ? \Carbon\Carbon::parse($programa->inicio) : null;
    $fin = $programa->fin ? \Carbon\Carbon::parse($programa->fin) : null;
    $duracion = $programa->duracionDias();
    $diasFaltantes = $programa->diasParaInicio();
    $faseViaje = $programa->faseViaje();
    $p = $presupuesto;
    $c = $cobro;
    $op = $operacion;
    $cost = $costos;
    $simbolo = $programa->simboloMoneda();
    $money = fn ($n) => $simbolo . ' ' . number_format((float) $n, 2);
    $badgeFase = match ($faseViaje) {
        'en_curso' => ['bg-success', 'En curso'],
        'finalizado' => ['bg-secondary', 'Finalizado'],
        'sin_fechas' => ['bg-dark', 'Sin fechas'],
        default => [$diasFaltantes <= 7 ? 'bg-danger' : 'bg-primary', $diasFaltantes === 0 ? 'Inicia hoy' : ($diasFaltantes === 1 ? 'Inicia mañana' : 'Inicia en ' . $diasFaltantes . ' días')],
    };
    $itinerarioData = $programa->itinerario();
    $tc = (float) ($programa->tipoCambio() ?: 3.75);
    $pagantes = (int) ($programa->pagantes() ?: 1);
    $totalPaxs = (int) ($programa->totalPaxs() ?: 1);

    // Cálculos iniciales para la Hoja de Costeo tipo Excel
    $excelSubtotal = 0.0;
    foreach ($itinerarioData['dias'] as $dItem) {
        foreach ($dItem['servicios'] as $serv) {
            $uCost = $serv->costo !== null ? (float)$serv->costo : (float)($serv->costo_estimado ?? 0);
            $uUsd = $serv->moneda === 'PEN' ? ($tc > 0 ? $uCost / $tc : $uCost) : $uCost;
            $qty = $serv->costo_por === 'persona' ? $pagantes : 1;
            $excelSubtotal += ($uUsd * $qty);
        }
    }
    foreach ($itinerarioData['fuera'] as $serv) {
        $uCost = $serv->costo !== null ? (float)$serv->costo : (float)($serv->costo_estimado ?? 0);
        $uUsd = $serv->moneda === 'PEN' ? ($tc > 0 ? $uCost / $tc : $uCost) : $uCost;
        $qty = $serv->costo_por === 'persona' ? $pagantes : 1;
        $excelSubtotal += ($uUsd * $qty);
    }
    $excelComisionPct = (float)($programa->porcentaje_ganancia ?? 11.0);
    $excelIgvPct = (float)($programa->porcentaje_igv ?? 18.0);
    $excelComisionMonto = $excelSubtotal * ($excelComisionPct / 100);
    $excelCostoTotal = $excelSubtotal + $excelComisionMonto;
    $excelIgvMonto = $excelComisionMonto * ($excelIgvPct / 100);
    $excelTotal = $excelCostoTotal + $excelIgvMonto;
    $excelPrecioPax = $pagantes > 0 ? ($excelTotal / $pagantes) : 0;
@endphp

@section('contenido')
    {{-- Encabezado Principal y Barra de Acciones --}}
    <div class="ae-admin-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('programas.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver a Programas
                </a>
                <span class="badge bg-dark font-monospace fs-7"><i class="fas fa-hashtag text-gold me-1"></i>{{ $programa->codigo }}</span>
                <span class="badge {{ $badgeFase[0] }}">{{ $badgeFase[1] }}</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle text-uppercase">{{ $programa->estado }}</span>
                @if ($programa->lang)
                    <span class="badge bg-light text-dark border">{{ strtoupper($programa->lang) }}</span>
                @endif
                @if ($programa->moneda)
                    <span class="badge bg-light text-dark border">{{ $programa->moneda }}</span>
                @endif
            </div>
            <h1 class="ae-admin-page-title mb-1 fw-bold text-dark">{{ $programa->nombre }}</h1>
            <p class="text-muted small mb-0">
                <i class="fas fa-calendar-alt me-1"></i>
                @if ($inicio && $fin)
                    {{ $inicio->locale('es')->isoFormat('dddd D [de] MMMM') }} – {{ $fin->locale('es')->isoFormat('dddd D [de] MMMM YYYY') }}
                    <strong>({{ $duracion }} {{ $duracion === 1 ? 'día' : 'días' }})</strong>
                @else
                    Fechas por definir
                @endif
                · <i class="fas fa-users ms-2 me-1"></i> {{ $programa->paxs->count() }} pasajeros
                @if ($programa->cliente)
                    · <i class="fas fa-user-tie ms-2 me-1"></i> Cliente: <strong>{{ $programa->cliente->nombre }}</strong>
                @endif
            </p>
        </div>

        {{-- Botonera de Acciones Ejecutivas --}}
        <div class="d-flex flex-wrap gap-2">
            @if ($verCotizacion)
                <button type="button" class="btn btn-outline-success" onclick="document.querySelector('#tab-costeo-excel-btn')?.click(); window.scrollTo({top: (document.querySelector('#fichaTab')?.offsetTop || 200) - 80, behavior: 'smooth'});">
                    <i class="fas fa-file-excel me-1 text-success"></i> Hoja de Costeo
                </button>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAjustarPrecios">
                    <i class="fas fa-tags me-1 text-primary"></i> Precios & Márgenes
                </button>
            @endif
            <button type="button" class="btn btn-outline-secondary"
                data-historial-modal
                data-programa-nombre="{{ $programa->nombre }}"
                data-programa-codigo="{{ $programa->codigo }}"
                data-historial-url="{{ route('programas.historial', $programa) }}">
                <i class="fas fa-history me-1 text-primary"></i> Bitácora
            </button>
            <a href="{{ route('programas.pdf', $programa) }}" class="btn btn-outline-danger"
                data-pdf-preview="{{ route('programas.pdf', [$programa, 'ver' => 1]) }}"
                data-pdf-title="Cotización · {{ $programa->nombre }}"
                data-pdf-subtitle="Documento PDF de Cotización / Presentación">
                <i class="fas fa-file-pdf me-1"></i> PDF
            </a>
            <a href="{{ route('itinerario', $programa) }}" class="btn btn-outline-info" target="_blank">
                <i class="fas fa-external-link-alt me-1"></i> Vista Cliente
            </a>
            <button type="button" class="btn btn-outline-dark" data-print>
                <i class="fas fa-print me-1"></i> Imprimir
            </button>
            @if ($canEdit)
                <a href="{{ route('programas.edit', $programa) }}" class="btn btn-primary px-3 shadow-sm">
                    <i class="fas fa-pencil-alt me-1"></i> Editar Programa
                </a>
            @endif
        </div>
    </div>

    {{-- Tarjetas KPI de Estado 360° --}}
    <div class="row g-3 mb-4">
        {{-- KPI 1: Pasajeros & Contacto --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-semibold text-uppercase">Viajeros</span>
                        <span class="badge bg-primary-subtle text-primary rounded-pill"><i class="fas fa-users"></i></span>
                    </div>
                    <div class="h4 fw-bold mb-1">{{ $programa->paxs->count() }} <small class="fs-6 fw-normal text-muted">pax</small></div>
                    <div class="small text-muted mb-2">
                        {{ $p['adultos'] }} adultos
                        @if ($p['ninos']) · {{ $p['ninos'] }} niños @endif
                        @if ($p['infantes']) · {{ $p['infantes'] }} inf. @endif
                    </div>
                    @if ($programa->cliente)
                        <div class="border-top pt-2 small">
                            <div class="fw-semibold text-truncate" title="{{ $programa->cliente->nombre }}">{{ $programa->cliente->nombre }}</div>
                            @if ($programa->cliente->telefono)
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $programa->cliente->telefono) }}" target="_blank" class="text-success text-decoration-none small">
                                    <i class="fab fa-whatsapp me-1"></i>{{ $programa->cliente->telefono }}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KPI 2: Logística de Vuelos --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-semibold text-uppercase">Logística de Vuelos</span>
                        <span class="badge {{ $programa->tieneVuelos() ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} rounded-pill">
                            <i class="fas fa-plane"></i>
                        </span>
                    </div>
                    @if ($programa->tieneVuelos())
                        <div class="small mb-2">
                            <div class="text-truncate" title="Llegada: {{ $programa->llegada_vuelo }}">
                                <i class="fas fa-plane-arrival text-success me-1"></i> <strong>Llegada:</strong> {{ $programa->llegada_vuelo ?: 'Definido' }}
                                @if($programa->llegada_at)<div class="text-muted ps-3">{{ $programa->llegada_at->format('d/m/Y H:i') }}</div>@endif
                            </div>
                            <div class="text-truncate mt-1" title="Salida: {{ $programa->salida_vuelo }}">
                                <i class="fas fa-plane-departure text-primary me-1"></i> <strong>Salida:</strong> {{ $programa->salida_vuelo ?: 'Definido' }}
                                @if($programa->salida_at)<div class="text-muted ps-3">{{ $programa->salida_at->format('d/m/Y H:i') }}</div>@endif
                            </div>
                        </div>
                        <span class="badge bg-success-subtle text-success"><i class="fas fa-check-circle me-1"></i> Vuelos registrados</span>
                    @else
                        <div class="h5 fw-bold text-warning-emphasis mb-1">Pendiente</div>
                        <p class="small text-muted mb-2">Sin vuelos de llegada ni salida registrados.</p>
                        <span class="badge bg-warning-subtle text-warning-emphasis"><i class="fas fa-exclamation-triangle me-1"></i> Coordinar con cliente</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- KPI 3: Cobranza y Pagos --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-semibold text-uppercase">Cobranza Cliente</span>
                        <span class="badge {{ $c['estado'] === 'pagado' ? 'bg-success-subtle text-success' : ($c['saldoVencido'] ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary') }} rounded-pill">
                            <i class="fas fa-wallet"></i>
                        </span>
                    </div>
                    <div class="h4 fw-bold mb-1" id="kpi-total-venta">{{ $money($p['total']) }}</div>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar {{ $c['estado'] === 'pagado' ? 'bg-success' : 'bg-primary' }}" style="width: {{ min(100, $c['porcentaje']) }}%;"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Pagado: <strong>{{ $money($c['pagado']) }}</strong></span>
                        <span class="{{ $c['saldoVencido'] ? 'text-danger fw-bold' : '' }}">Saldo: <strong>{{ $money($c['saldo']) }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- KPI 4: Rentabilidad / Operación --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-semibold text-uppercase">Operación & Tareas</span>
                        <span class="badge bg-info-subtle text-info rounded-pill"><i class="fas fa-tasks"></i></span>
                    </div>
                    <div class="h4 fw-bold mb-1">
                        {{ $op['confirmados'] }} <small class="fs-6 fw-normal text-muted">de {{ $op['servicios'] }} serv. confirmados</small>
                    </div>
                    <div class="d-flex flex-wrap gap-1 mt-2">
                        @if ($op['abiertos'] > 0)
                            <span class="badge bg-warning-subtle text-warning-emphasis">{{ $op['abiertos'] }} serv. por confirmar</span>
                        @else
                            <span class="badge bg-success-subtle text-success"><i class="fas fa-check me-1"></i>Servicios OK</span>
                        @endif
                        @if ($tareasResumen['vencidas'] > 0)
                            <span class="badge bg-danger-subtle text-danger">{{ $tareasResumen['vencidas'] }} tar. vencidas</span>
                        @elseif ($tareasResumen['pendientes'] > 0)
                            <span class="badge bg-secondary-subtle text-secondary">{{ $tareasResumen['pendientes'] }} tar. pendientes</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Navegación por Pestañas Ejecutivas --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-transparent border-bottom p-0">
            <ul class="nav nav-tabs card-header-tabs m-0 px-3 border-0" id="fichaTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active py-3 px-3 fw-semibold" id="tab-itinerario-btn" data-bs-toggle="tab" data-bs-target="#tab-itinerario" type="button" role="tab">
                        <i class="fas fa-route me-2 text-primary"></i> Itinerario & Servicios ({{ $programa->servicios->count() }})
                    </button>
                </li>
                @if ($verCotizacion)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 px-3 fw-semibold text-success" id="tab-costeo-excel-btn" data-bs-toggle="tab" data-bs-target="#tab-costeo-excel" type="button" role="tab">
                            <i class="fas fa-file-excel me-2 text-success"></i> Hoja de Costeo & Cotizador
                        </button>
                    </li>
                @endif
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 px-3 fw-semibold" id="tab-pasajeros-btn" data-bs-toggle="tab" data-bs-target="#tab-pasajeros" type="button" role="tab">
                        <i class="fas fa-users me-2 text-primary"></i> Pasajeros & Rooming ({{ $programa->paxs->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 px-3 fw-semibold" id="tab-logistica-btn" data-bs-toggle="tab" data-bs-target="#tab-logistica" type="button" role="tab">
                        <i class="fas fa-plane-departure me-2 text-primary"></i> Vuelos & Hoteles
                    </button>
                </li>
                @if ($verCotizacion)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 px-3 fw-semibold" id="tab-finanzas-btn" data-bs-toggle="tab" data-bs-target="#tab-finanzas" type="button" role="tab">
                            <i class="fas fa-coins me-2 text-primary"></i> Pagos & Costos
                        </button>
                    </li>
                @endif
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 px-3 fw-semibold" id="tab-tareas-btn" data-bs-toggle="tab" data-bs-target="#tab-tareas" type="button" role="tab">
                        <i class="fas fa-tasks me-2 text-primary"></i> Tareas ({{ $tareasResumen['total'] }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 px-3 fw-semibold" id="tab-bitacora-btn" data-bs-toggle="tab" data-bs-target="#tab-bitacora" type="button" role="tab">
                        <i class="fas fa-stream me-2 text-primary"></i> Bitácora & Historial ({{ $movimientos->count() }})
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="fichaTabContent">
                {{-- TAB 1: Itinerario y Servicios --}}
                <div class="tab-pane fade show active" id="tab-itinerario" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h6 fw-bold text-uppercase text-muted mb-0">Programa Detallado Día por Día</h3>
                        <span class="badge text-bg-light border">{{ $duracion }} días de viaje</span>
                    </div>

                    @forelse ($itinerarioData['dias'] as $itDia)
                        @php $dia = $itDia['dia']; $serviciosDia = $itDia['servicios']; @endphp
                        <div class="card border mb-3 rounded-3 overflow-hidden">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
                                <div>
                                    <strong class="text-primary me-2">Día {{ $itDia['numero'] }}:</strong>
                                    <span class="fw-bold">{{ $dia?->titulo }}</span>
                                    <small class="text-muted ms-2">({{ $itDia['fecha']->locale('es')->isoFormat('dddd D [de] MMMM') }})</small>
                                    @if ($itDia['es_libre'])
                                        <span class="badge bg-success-subtle text-success border border-success-subtle ms-2">
                                            <i class="fas fa-leaf me-1"></i> Día libre
                                        </span>
                                    @endif
                                </div>
                                @if ($itDia['hotel'])
                                    <small class="text-muted"><i class="fas fa-bed me-1"></i>{{ $itDia['hotel'] }}</small>
                                @endif
                            </div>
                            <div class="card-body p-3">
                                @if ($dia?->descripcion)
                                    <div class="text-muted small mb-3">{!! nl2br(e($dia->descripcion)) !!}</div>
                                @endif

                                @if ($itDia['es_libre'])
                                    <div class="p-2 mb-3 bg-success-subtle rounded border border-success-subtle text-success small">
                                        <i class="fas fa-umbrella-beach me-1"></i> <strong>Día Libre / Descanso:</strong> Jornada programada para descanso, compras o aclimatación sin servicios operativos fijos.
                                    </div>
                                @endif

                                @if ($serviciosDia->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover align-middle mb-0">
                                            <thead class="table-light small">
                                                <tr>
                                                    <th>Hora</th>
                                                    <th>Servicio</th>
                                                    <th>Proveedor</th>
                                                    <th>Categoría</th>
                                                    <th>Estado</th>
                                                    <th class="text-end">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody class="small">
                                                @foreach ($serviciosDia as $s)
                                                    <tr>
                                                        <td>{{ $s->hora ? \Carbon\Carbon::parse($s->hora)->format('H:i') : '—' }}</td>
                                                        <td class="fw-semibold">{{ $s->titulo }}</td>
                                                        <td>
                                                            @if ($s->proveedor)
                                                                <span class="text-dark">{{ $s->proveedor->nombre }}</span>
                                                            @else
                                                                <span class="text-muted fst-italic">Sin asignar</span>
                                                            @endif
                                                        </td>
                                                        <td><span class="badge text-bg-light border">{{ $s->tipo_etiqueta }}</span></td>
                                                        <td>
                                                            <span class="badge {{ $s->estado === 'confirmado' ? 'bg-success-subtle text-success' : ($s->estado === 'cancelado' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning') }}">
                                                                {{ ucfirst($s->estado) }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end">
                                                            @if ($s->proveedor_id)
                                                                <a href="{{ route('servicios.voucher', ['servicio' => $s, 'download' => 1]) }}"
                                                                    class="btn btn-xs btn-outline-secondary"
                                                                    data-pdf-preview="{{ route('servicios.voucher', ['servicio' => $s, 'ver' => 1]) }}"
                                                                    data-pdf-title="Voucher #{{ $s->id }} · {{ $s->titulo }}"
                                                                    data-pdf-subtitle="Orden de servicio · Proveedor: {{ $s->proveedor?->nombre ?? 'Sin proveedor' }}"
                                                                    title="Ver orden de servicio (Voucher)">
                                                                    <i class="fas fa-file-invoice me-1"></i> Voucher
                                                                </a>
                                                                <a href="{{ route('servicios.mensaje', $s) }}" class="btn btn-xs btn-outline-secondary ms-1"
                                                                    title="Mensajes con el proveedor (desde reservas@)" aria-label="Mensajes con el proveedor de {{ $s->titulo }}">
                                                                    <i class="fas fa-envelope me-1" aria-hidden="true"></i> Mensajes
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted small fst-italic mb-0">Sin servicios operativos específicos asignados a este día.</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-route fa-2x mb-2 text-secondary opacity-50"></i>
                            <p class="mb-0">No hay días de itinerario cargados.</p>
                        </div>
                    @endforelse
                </div>

                {{-- TAB COSTEO: Hoja de Costeo & Cotizador Excel --}}
                @if ($verCotizacion)
                    <div class="tab-pane fade" id="tab-costeo-excel" role="tabpanel">
                        {{-- Cabecera y Barra de Herramientas de la Hoja de Costeo --}}
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3 p-3 bg-light rounded-3 border">
                            <div>
                                <h3 class="h5 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                    <i class="fas fa-file-excel text-success fs-4"></i>
                                    <span>Planilla de Costeo y Liquidación de Servicios</span>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle fs-7">Estilo Excel</span>
                                </h3>
                                <p class="text-muted small mb-0">
                                    Resumen día a día de costos netos a proveedores, cálculo de margen de ganancia de Andean Exclusive, IGV de intermediación y cotización por pasajero.
                                </p>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-success btn-sm shadow-sm" id="btn-export-excel-csv">
                                    <i class="fas fa-file-csv me-1 text-success"></i> Exportar CSV Excel
                                </button>
                                <button type="button" class="btn btn-outline-dark btn-sm shadow-sm" onclick="window.print();">
                                    <i class="fas fa-print me-1"></i> Imprimir Planilla
                                </button>
                                @if ($canEdit)
                                    <a href="{{ route('programas.edit', $programa) }}#tab-itinerario" class="btn btn-outline-primary btn-sm shadow-sm">
                                        <i class="fas fa-edit me-1"></i> Modificar Servicios
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Ficha Técnica Corporativa (Membrete de Cotización idéntico a Excel) --}}
                        <div class="card border mb-4 rounded-3 shadow-sm bg-white">
                            <div class="card-body p-3 p-md-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <div class="p-2 border rounded bg-light-subtle h-100">
                                            <div class="text-muted small fw-semibold text-uppercase">Programa / File</div>
                                            <div class="fw-bold text-dark fs-6">{{ $programa->nombre }}</div>
                                            <div class="text-primary font-monospace small"><i class="fas fa-hashtag text-gold me-1"></i>{{ $programa->codigo }}</div>
                                            @if ($programa->cliente)
                                                <div class="small mt-1 text-muted">
                                                    <i class="fas fa-user-tie me-1"></i> Cliente: <strong>{{ $programa->cliente->nombre }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="p-2 border rounded bg-light-subtle h-100">
                                            <div class="text-muted small fw-semibold text-uppercase">Grupo & Pasajeros</div>
                                            <div class="fw-bold text-dark fs-6">{{ $totalPaxs }} personas</div>
                                            <div class="small text-muted">
                                                <strong>{{ $pagantes }} pagantes</strong> ({{ $p['adultos'] }} adultos
                                                @if ($p['ninos']) · {{ $p['ninos'] }} niños @endif
                                                @if ($p['infantes']) · {{ $p['infantes'] }} infantes @endif)
                                            </div>
                                            <div class="small mt-1 text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ $inicio ? $inicio->format('d/m/Y') : 'Por definir' }} – {{ $fin ? $fin->format('d/m/Y') : 'Por definir' }}
                                                ({{ $duracion }} días)
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="p-2 border rounded bg-light-subtle h-100">
                                            <div class="text-muted small fw-semibold text-uppercase">Moneda & Conversión</div>
                                            <div class="fw-bold text-dark fs-6">
                                                {{ $programa->moneda ?? 'USD' }} ({{ $simbolo }})
                                                <span class="badge bg-light text-dark border ms-1">T/C: S/ {{ number_format($tc, 2) }}</span>
                                            </div>
                                            <div class="small text-muted mt-1">
                                                Precio adulto en base: <strong>US$ {{ number_format((float)$programa->precioAdulto, 2) }}</strong>
                                                @if ($programa->porcentaje_ganancia)
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1">+{{ number_format($programa->porcentaje_ganancia, 1) }}% margen</span>
                                                @endif
                                            </div>
                                            <div class="small text-success fw-semibold mt-1">
                                                Venta total en sistema: <strong>{{ $money($p['total']) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Hoja de Cálculo Interactiva (Tabla Excel) --}}
                        <div class="card border rounded-3 shadow-sm overflow-hidden mb-4">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered align-middle mb-0 excel-costeo-table" id="tabla-costeo-excel">
                                    <thead class="table-dark text-nowrap small text-uppercase">
                                        <tr>
                                            <th style="width: 100px;">Hora / Ref.</th>
                                            <th>Detalle del Servicio / Concepto</th>
                                            <th style="width: 220px;">Proveedor</th>
                                            <th style="width: 120px;">Modalidad</th>
                                            <th class="text-end" style="width: 130px;">Costo Unit.</th>
                                            <th class="text-center" style="width: 90px;">Cant.</th>
                                            <th class="text-end" style="width: 140px;">Costo Total (US$)</th>
                                            <th style="width: 150px;">Estado / Notas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($itinerarioData['dias'] as $itDia)
                                            @php
                                                $dia = $itDia['dia'];
                                                $serviciosDia = $itDia['servicios'];
                                                $subtotalDiaUsd = 0.0;
                                                foreach ($serviciosDia as $s) {
                                                    $uCost = $s->costo !== null ? (float)$s->costo : (float)($s->costo_estimado ?? 0);
                                                    $uUsd = $s->moneda === 'PEN' ? ($tc > 0 ? $uCost / $tc : $uCost) : $uCost;
                                                    $qty = $s->costo_por === 'persona' ? $pagantes : 1;
                                                    $subtotalDiaUsd += ($uUsd * $qty);
                                                }
                                            @endphp

                                            {{-- Fila de Título del Día --}}
                                            <tr class="excel-row-header-dia">
                                                <td colspan="8" class="py-2 px-3">
                                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                        <div>
                                                            <strong class="excel-dia-num text-uppercase me-2">Día {{ $itDia['numero'] }}:</strong>
                                                            <span class="excel-dia-titulo fw-bold">{{ $dia?->titulo ?: 'Ruta sin definir' }}</span>
                                                            <span class="text-muted small ms-2">({{ $itDia['fecha']->locale('es')->isoFormat('dddd D [de] MMMM YYYY') }})</span>
                                                            @if ($itDia['es_libre'])
                                                                <span class="badge bg-success text-white ms-2 shadow-xs">
                                                                    <i class="fas fa-leaf me-1"></i> Día Libre / Descanso
                                                                </span>
                                                            @endif
                                                        </div>
                                                        @if ($itDia['hotel'])
                                                            <small class="text-dark fw-semibold">
                                                                <i class="fas fa-hotel text-primary me-1"></i> {{ $itDia['hotel'] }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>

                                            {{-- Fila de Alojamiento si está registrado en ese día --}}
                                            @if ($itDia['hotel'])
                                                <tr class="excel-row-hotel bg-light-subtle small">
                                                    <td class="text-center text-muted font-monospace"><i class="fas fa-bed text-primary"></i> Pernocte</td>
                                                    <td colspan="2">
                                                        <span class="fw-semibold text-dark"><i class="fas fa-hotel text-primary me-1"></i> Hotel: {{ $itDia['hotel'] }}</span>
                                                        <small class="text-muted d-block ps-3">Alojamiento programado para este día</small>
                                                    </td>
                                                    <td><span class="badge text-bg-light border">Por grupo / noche</span></td>
                                                    <td class="text-end text-muted font-monospace">—</td>
                                                    <td class="text-center text-muted font-monospace">1 noche</td>
                                                    <td class="text-end text-muted font-monospace">—</td>
                                                    <td><span class="badge text-bg-light border text-muted">Incluido / Coordinado</span></td>
                                                </tr>
                                            @endif

                                            {{-- Servicios del Día --}}
                                            @forelse ($serviciosDia as $s)
                                                @php
                                                    $uCost = $s->costo !== null ? (float)$s->costo : (float)($s->costo_estimado ?? 0);
                                                    $uUsd = $s->moneda === 'PEN' ? ($tc > 0 ? $uCost / $tc : $uCost) : $uCost;
                                                    $qty = $s->costo_por === 'persona' ? $pagantes : 1;
                                                    $totalUsd = $uUsd * $qty;
                                                @endphp
                                                <tr class="excel-row-servicio"
                                                    data-dia="{{ $itDia['numero'] }}"
                                                    data-fecha="{{ $itDia['fecha']->format('d/m/Y') }}"
                                                    data-servicio="{{ $s->titulo }}"
                                                    data-proveedor="{{ $s->proveedor?->nombre ?: 'Sin asignar' }}"
                                                    data-modalidad="{{ $s->costo_por === 'persona' ? 'Por persona' : 'Por grupo' }}"
                                                    data-costo-unit="{{ $uUsd }}"
                                                    data-qty="{{ $qty }}"
                                                    data-total-usd="{{ $totalUsd }}"
                                                    data-notas="{{ $s->notas }}">
                                                    <td class="small text-muted font-monospace text-nowrap">
                                                        {{ $s->hora ? \Carbon\Carbon::parse($s->hora)->format('H:i') : '—' }}
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-dark">{{ $s->titulo }}</div>
                                                        <div class="small text-muted">
                                                            <span class="badge text-bg-light border me-1">{{ $s->tipo_etiqueta }}</span>
                                                            @if ($s->descripcion)
                                                                <span>{{ Str::limit($s->descripcion, 75) }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($s->proveedor)
                                                            <span class="text-dark fw-semibold">{{ $s->proveedor->nombre }}</span>
                                                        @else
                                                            <span class="badge bg-warning-subtle text-warning-emphasis">Sin asignar</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $s->costo_por === 'persona' ? 'bg-info-subtle text-info border' : 'bg-secondary-subtle text-secondary border' }}">
                                                            {{ $s->costo_por === 'persona' ? 'Por persona' : 'Por grupo' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end font-monospace text-nowrap">
                                                        @if ($s->moneda === 'PEN')
                                                            <div class="fw-semibold">S/ {{ number_format($uCost, 2) }}</div>
                                                            <small class="text-muted">(US$ {{ number_format($uUsd, 2) }})</small>
                                                        @else
                                                            <div class="fw-semibold">US$ {{ number_format($uUsd, 2) }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center font-monospace text-nowrap">
                                                        <span class="badge text-bg-light border px-2 py-1">
                                                            {{ $qty }} {{ $s->costo_por === 'persona' ? 'pax' : 'grp' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end font-monospace fw-bold text-dark text-nowrap">
                                                        US$ {{ number_format($totalUsd, 2) }}
                                                    </td>
                                                    <td class="small">
                                                        <span class="badge {{ $s->estado === 'confirmado' ? 'bg-success-subtle text-success' : ($s->estado === 'cancelado' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning') }}">
                                                            {{ ucfirst($s->estado) }}
                                                        </span>
                                                        @if ($s->proveedor_id)
                                                            <a href="{{ route('servicios.voucher', ['servicio' => $s, 'download' => 1]) }}"
                                                                class="btn btn-xs btn-outline-secondary py-0 px-1 ms-1"
                                                                data-pdf-preview="{{ route('servicios.voucher', ['servicio' => $s, 'ver' => 1]) }}"
                                                                data-pdf-title="Voucher #{{ $s->id }} · {{ $s->titulo }}"
                                                                data-pdf-subtitle="Orden de servicio · Proveedor: {{ $s->proveedor?->nombre ?? 'Sin proveedor' }}"
                                                                title="Ver orden de servicio (Voucher)">
                                                                <i class="fas fa-file-invoice"></i>
                                                            </a>
                                                        @endif
                                                        @if ($s->notas)
                                                            <span class="text-muted ms-1 text-truncate d-inline-block" style="max-width: 140px;" title="{{ $s->notas }}">
                                                                {{ $s->notas }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                @if ($itDia['es_libre'])
                                                    <tr class="table-light">
                                                        <td colspan="8" class="text-center py-2 text-muted fst-italic">
                                                            <i class="fas fa-leaf text-success me-1"></i> Día libre sin servicios operativos asignados (Descanso y aclimatación).
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr class="table-light">
                                                        <td colspan="8" class="text-center py-2 text-muted fst-italic">
                                                            Sin servicios operativos específicos asignados a este día.
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforelse

                                            {{-- Subtotal Día --}}
                                            <tr class="excel-subtotal-dia border-bottom-2 border-secondary-subtle bg-light">
                                                <td colspan="6" class="text-end py-1 px-3 text-muted small fw-semibold">
                                                    Sub-total Día {{ $itDia['numero'] }}:
                                                </td>
                                                <td class="text-end py-1 px-3 font-monospace fw-bold text-primary">
                                                    US$ {{ number_format($subtotalDiaUsd, 2) }}
                                                </td>
                                                <td></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    No hay días de itinerario cargados.
                                                </td>
                                            </tr>
                                        @endforelse

                                        {{-- Servicios Fuera de Fechas si los hubiera --}}
                                        @if ($itinerarioData['fuera']->isNotEmpty())
                                            <tr class="table-secondary fw-bold">
                                                <td colspan="8" class="py-2 px-3">
                                                    <i class="fas fa-exclamation-circle text-warning me-1"></i> Servicios Fuera de Rango de Fechas / Traslados Especiales
                                                </td>
                                            </tr>
                                            @foreach ($itinerarioData['fuera'] as $s)
                                                @php
                                                    $uCost = $s->costo !== null ? (float)$s->costo : (float)($s->costo_estimado ?? 0);
                                                    $uUsd = $s->moneda === 'PEN' ? ($tc > 0 ? $uCost / $tc : $uCost) : $uCost;
                                                    $qty = $s->costo_por === 'persona' ? $pagantes : 1;
                                                    $totalUsd = $uUsd * $qty;
                                                @endphp
                                                <tr class="excel-row-servicio"
                                                    data-dia="Ext"
                                                    data-fecha="{{ $s->fecha ? $s->fecha->format('d/m/Y') : 'Ext' }}"
                                                    data-servicio="{{ $s->titulo }}"
                                                    data-proveedor="{{ $s->proveedor?->nombre ?: 'Sin asignar' }}"
                                                    data-modalidad="{{ $s->costo_por === 'persona' ? 'Por persona' : 'Por grupo' }}"
                                                    data-costo-unit="{{ $uUsd }}"
                                                    data-qty="{{ $qty }}"
                                                    data-total-usd="{{ $totalUsd }}"
                                                    data-notas="{{ $s->notas }}">
                                                    <td class="small text-muted font-monospace text-nowrap">
                                                        {{ $s->fecha ? $s->fecha->format('d/m') : '—' }} {{ $s->hora ? \Carbon\Carbon::parse($s->hora)->format('H:i') : '' }}
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-dark">{{ $s->titulo }}</div>
                                                        <small class="text-muted">{{ $s->tipo_etiqueta }}</small>
                                                    </td>
                                                    <td>{{ $s->proveedor?->nombre ?: 'Sin asignar' }}</td>
                                                    <td><span class="badge bg-secondary text-white">{{ $s->costo_por === 'persona' ? 'Por persona' : 'Por grupo' }}</span></td>
                                                    <td class="text-end font-monospace">US$ {{ number_format($uUsd, 2) }}</td>
                                                    <td class="text-center font-monospace">{{ $qty }}</td>
                                                    <td class="text-end font-monospace fw-bold">US$ {{ number_format($totalUsd, 2) }}</td>
                                                    <td><span class="badge bg-light text-dark border">{{ ucfirst($s->estado) }}</span></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Cuadro de Liquidación Financiera y Ganancia (Fórmulas Excel) --}}
                        <div class="card border border-2 border-primary-subtle shadow-sm rounded-3 overflow-hidden">
                            <div class="card-header bg-dark text-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <h5 class="mb-0 fw-bold"><i class="fas fa-calculator text-warning me-2"></i> Liquidación de Costos & Cotización Final</h5>
                                    <small class="text-white-50">Determine en tiempo real el porcentaje de ganancia de Andean Exclusive y el IGV aplicable</small>
                                </div>
                                <span class="badge bg-success fs-7 px-3 py-2"><i class="fas fa-users me-1"></i> Base: {{ $pagantes }} pasajeros pagantes</span>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    {{-- Columna Izquierda: Parámetros y Ajustes --}}
                                    <div class="col-12 col-lg-5 border-end-lg">
                                        <h6 class="fw-bold text-dark text-uppercase small mb-3">
                                            <i class="fas fa-sliders-h text-primary me-1"></i> Parámetros de la Cotización
                                        </h6>

                                        {{-- Comisión Andean Exclusive --}}
                                        <div class="mb-3 p-3 bg-light rounded-3 border">
                                            <label for="costeo-comision-input" class="form-label fw-bold small text-dark d-flex justify-content-between align-items-center mb-1">
                                                <span>% Comisión Andean Exclusive (Margen):</span>
                                                <span class="badge bg-primary fs-7" id="costeo-badge-comision-pct">{{ number_format($excelComisionPct, 1) }}%</span>
                                            </label>
                                            <div class="input-group input-group-lg my-2">
                                                <span class="input-group-text bg-white fw-bold text-primary"><i class="fas fa-percentage"></i></span>
                                                <input type="number" step="0.5" min="0" max="100" class="form-control fw-bold text-primary text-center fs-4" id="costeo-comision-input" value="{{ number_format($excelComisionPct, 1, '.', '') }}">
                                                <span class="input-group-text bg-white fw-bold text-muted">%</span>
                                            </div>
                                            <div class="d-flex flex-wrap gap-1 mt-2">
                                                <span class="small text-muted align-self-center me-1">Atajos:</span>
                                                @foreach ([10, 11, 12, 15, 18, 20, 25, 30] as $presetPct)
                                                    <button type="button" class="btn btn-xs btn-outline-secondary btn-preset-pct" data-pct="{{ $presetPct }}">{{ $presetPct }}%</button>
                                                @endforeach
                                            </div>
                                        </div>

                                        {{-- Input Manual IGV Comisión --}}
                                        <div class="mb-3 p-3 bg-light rounded-3 border">
                                            <label for="costeo-igv-input" class="form-label fw-bold small text-dark d-flex justify-content-between align-items-center mb-1">
                                                <span>% IGV Comisión (Impuesto):</span>
                                                <span class="badge bg-warning text-dark fs-7" id="costeo-badge-igv-pct">{{ number_format($excelIgvPct, 1) }}%</span>
                                            </label>
                                            <div class="input-group input-group-lg my-2">
                                                <span class="input-group-text bg-white fw-bold text-warning-emphasis"><i class="fas fa-receipt"></i></span>
                                                <input type="number" step="0.1" min="0" max="100" class="form-control fw-bold text-warning-emphasis text-center fs-4" id="costeo-igv-input" value="{{ number_format($excelIgvPct, 1, '.', '') }}">
                                                <span class="input-group-text bg-white fw-bold text-muted">%</span>
                                            </div>
                                            <div class="d-flex flex-wrap gap-1 mt-2">
                                                <span class="small text-muted align-self-center me-1">Atajos:</span>
                                                <button type="button" class="btn btn-xs btn-outline-secondary btn-preset-igv" data-pct="18">18% General</button>
                                                <button type="button" class="btn btn-xs btn-outline-secondary btn-preset-igv" data-pct="10">10% Turismo</button>
                                                <button type="button" class="btn btn-xs btn-outline-secondary btn-preset-igv" data-pct="0">0% Exonerado</button>
                                            </div>
                                            <small class="text-muted d-block mt-2">
                                                <i class="fas fa-info-circle me-1"></i> Se aplica sobre la ganancia de la agencia por intermediación conforme a ley.
                                            </small>
                                        </div>

                                        {{-- Divisor de Pasajeros --}}
                                        <div class="p-3 bg-light rounded-3 border">
                                            <label class="form-label fw-bold small text-dark mb-2">
                                                Dividir costo total entre:
                                            </label>
                                            <div class="btn-group w-100" role="group">
                                                <input type="radio" class="btn-check" name="divisor_paxs" id="div-pagantes" value="{{ $pagantes }}" checked>
                                                <label class="btn btn-outline-primary btn-sm py-2" for="div-pagantes">
                                                    <i class="fas fa-user-check me-1"></i> {{ $pagantes }} Pasajeros Pagantes
                                                </label>

                                                <input type="radio" class="btn-check" name="divisor_paxs" id="div-totales" value="{{ $totalPaxs }}">
                                                <label class="btn btn-outline-primary btn-sm py-2" for="div-totales">
                                                    <i class="fas fa-users me-1"></i> {{ $totalPaxs }} Viajeros Totales
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Columna Derecha: Hoja de Liquidación Contable (Excel) --}}
                                    <div class="col-12 col-lg-7">
                                        <h6 class="fw-bold text-dark text-uppercase small mb-3">
                                            <i class="fas fa-file-invoice-dollar text-success me-1"></i> Resumen de Liquidación (Fórmulas Excel)
                                        </h6>

                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle mb-0 shadow-sm rounded-3 overflow-hidden">
                                                <tbody>
                                                    {{-- Fila 1: Sub-Total --}}
                                                    <tr>
                                                        <td class="py-2 px-3 fw-bold text-secondary text-uppercase bg-light">
                                                            <i class="fas fa-coins me-2 text-muted"></i> SUB -TOTAL PROVEEDORES
                                                        </td>
                                                        <td class="py-2 px-3 text-end font-monospace fw-bold fs-6 text-dark" style="width: 45%;">
                                                            US$ <span id="val-subtotal" data-raw-subtotal="{{ $excelSubtotal }}">{{ number_format($excelSubtotal, 2) }}</span>
                                                        </td>
                                                    </tr>

                                                    {{-- Fila 2: Comisión Andean Exclusive --}}
                                                    <tr>
                                                        <td class="py-2 px-3 fw-semibold text-primary">
                                                            <i class="fas fa-hand-holding-usd me-2"></i> Comisión Andean Exclusive (<span id="lbl-comision-pct">{{ number_format($excelComisionPct, 1) }}</span>%)
                                                        </td>
                                                        <td class="py-2 px-3 text-end font-monospace fw-bold text-primary">
                                                            + US$ <span id="val-comision">{{ number_format($excelComisionMonto, 2) }}</span>
                                                        </td>
                                                    </tr>

                                                    {{-- Fila 3: Costo Total --}}
                                                    <tr class="table-light">
                                                        <td class="py-2 px-3 fw-bold text-dark">
                                                            <i class="fas fa-calculator me-2 text-muted"></i> Costo Total (Subtotal + Comisión)
                                                        </td>
                                                        <td class="py-2 px-3 text-end font-monospace fw-bold text-dark">
                                                            US$ <span id="val-costo-total">{{ number_format($excelCostoTotal, 2) }}</span>
                                                        </td>
                                                    </tr>

                                                    {{-- Fila 4: IGV Comisión --}}
                                                    <tr id="row-igv">
                                                        <td class="py-2 px-3 fw-semibold text-warning-emphasis">
                                                            <i class="fas fa-receipt me-2"></i> IGV Comisión (<span id="lbl-igv-pct">{{ number_format($excelIgvPct, 1) }}</span>%)
                                                        </td>
                                                        <td class="py-2 px-3 text-end font-monospace fw-bold text-warning-emphasis">
                                                            + US$ <span id="val-igv">{{ number_format($excelIgvMonto, 2) }}</span>
                                                        </td>
                                                    </tr>

                                                    {{-- Fila 5: TOTAL COTIZACIÓN --}}
                                                    <tr class="bg-dark text-white">
                                                        <td class="py-3 px-3 fs-5 fw-bold text-uppercase text-white">
                                                            <i class="fas fa-gem me-2 text-warning"></i> TOTAL COTIZACIÓN
                                                        </td>
                                                        <td class="py-3 px-3 text-end font-monospace fs-4 fw-bold text-warning">
                                                            US$ <span id="val-total-cotizacion">{{ number_format($excelTotal, 2) }}</span>
                                                        </td>
                                                    </tr>

                                                    {{-- Fila 6: PRECIO POR PASAJERO --}}
                                                    <tr class="table-success border-top-2 border-success">
                                                        <td class="py-3 px-3 fw-bold text-success fs-6">
                                                            <i class="fas fa-user-tag me-2"></i> PRECIO POR PASAJERO (Venta sugerida)
                                                        </td>
                                                        <td class="py-3 px-3 text-end font-monospace fs-3 fw-bold text-success">
                                                            US$ <span id="val-precio-pax">{{ number_format($excelPrecioPax, 2) }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        {{-- Panel de Tarifas Oficiales a Aplicar --}}
                                        <div class="p-3 bg-light rounded-3 border mt-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="fw-bold small text-dark"><i class="fas fa-check-double text-success me-1"></i> Precios que se aplicarán al total del Programa:</span>
                                                <small class="text-muted">Editables directamente aquí</small>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label small text-muted mb-1">Precio Adulto a guardar (US$):</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-white fw-bold text-success">US$</span>
                                                        <input type="number" step="0.01" min="0" class="form-control fw-bold text-success" id="costeo-precio-adulto-input" value="{{ number_format($excelPrecioPax, 2, '.', '') }}">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-revert-calc-price" title="Restablecer al precio calculado">
                                                            <i class="fas fa-sync-alt"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label small text-muted mb-1">Precio Niño a guardar (US$):</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-white fw-bold">US$</span>
                                                        <input type="number" step="0.01" min="0" class="form-control" id="costeo-precio-nino-input" value="{{ $programa->precioChild ? number_format((float)$programa->precioChild, 2, '.', '') : '' }}" placeholder="Opcional">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Acciones y Guardado --}}
                                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3 pt-3 border-top">
                                            <div>
                                                <div class="small text-muted">
                                                    Precio oficial guardado actualmente: <strong class="text-dark">US$ {{ number_format((float)$programa->precioAdulto, 2) }}</strong>
                                                    (Total venta: <strong>{{ $money($p['total']) }}</strong>)
                                                </div>
                                                <div id="costeo-feedback-msg" class="small mt-1" style="display:none;"></div>
                                            </div>
                                            @if ($canEdit)
                                                <button type="button" class="btn btn-success btn-lg px-4 shadow-sm" id="btn-guardar-cotizacion">
                                                    <i class="fas fa-save me-2"></i> Guardar y Aplicar al Total
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- TAB 2: Pasajeros & Rooming List --}}
                <div class="tab-pane fade" id="tab-pasajeros" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h6 fw-bold text-uppercase text-muted mb-0">Manifiesto de Pasajeros</h3>
                        <a href="{{ route('programas.rooming', $programa) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fas fa-bed me-1"></i> Ver Rooming List Oficial
                        </a>
                    </div>

                    @if ($programa->paxs->isNotEmpty())
                        <div class="table-responsive mb-4">
                            <table class="table table-hover align-middle border rounded-3">
                                <thead class="table-light small">
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre Completo</th>
                                        <th>Nacionalidad</th>
                                        <th>Documento / Pasaporte</th>
                                        <th>Edad</th>
                                        <th>Alimentación / Dietas</th>
                                        <th class="text-end">Pasaporte Adjunto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($programa->paxs as $i => $pax)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td class="fw-semibold">{{ $pax->nombre }}</td>
                                            <td>{{ $pax->nacionalidad ?: '—' }}</td>
                                            <td><span class="font-monospace">{{ $pax->documento ?: '—' }}</span></td>
                                            <td>{{ $pax->edad ? $pax->edad . ' años' : '—' }}</td>
                                            <td>
                                                @if ($pax->alimentacion)
                                                    <span class="badge text-bg-warning-subtle text-warning-emphasis"><i class="fas fa-utensils me-1"></i>{{ $pax->alimentacion }}</span>
                                                @else
                                                    <span class="text-muted">Estándar</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($pax->pasaporte_archivo)
                                                    <a href="{{ route('paxs.pasaporte', $pax) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="fas fa-id-card me-1"></i> Ver copia
                                                    </a>
                                                @else
                                                    <span class="text-muted small">Sin adjunto</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted border rounded-3 mb-4">
                            <i class="fas fa-users-slash fa-2x mb-2 text-secondary opacity-50"></i>
                            <p class="mb-0">No se han registrado pasajeros todavía.</p>
                        </div>
                    @endif
                </div>

                {{-- TAB 3: Vuelos & Hoteles --}}
                <div class="tab-pane fade" id="tab-logistica" role="tabpanel">
                    <div class="row g-4">
                        {{-- Vuelos --}}
                        <div class="col-12 col-lg-6">
                            <div class="card border rounded-3 h-100">
                                <div class="card-header bg-light py-2 px-3 fw-bold">
                                    <i class="fas fa-plane text-primary me-2"></i> Vuelos del Grupo
                                </div>
                                <div class="card-body p-3">
                                    <div class="mb-3 p-3 bg-light rounded-3">
                                        <div class="text-muted small fw-semibold mb-1"><i class="fas fa-plane-arrival text-success me-1"></i> VUELO DE LLEGADA</div>
                                        <div class="fw-bold fs-6">{{ $programa->llegada_vuelo ?: 'No registrado' }}</div>
                                        <div class="text-muted small">
                                            Fecha y hora: {{ $programa->llegada_at ? $programa->llegada_at->locale('es')->isoFormat('dddd D [de] MMMM YYYY [a las] HH:mm') : 'Por definir' }}
                                        </div>
                                    </div>
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="text-muted small fw-semibold mb-1"><i class="fas fa-plane-departure text-primary me-1"></i> VUELO DE SALIDA</div>
                                        <div class="fw-bold fs-6">{{ $programa->salida_vuelo ?: 'No registrado' }}</div>
                                        <div class="text-muted small">
                                            Fecha y hora: {{ $programa->salida_at ? $programa->salida_at->locale('es')->isoFormat('dddd D [de] MMMM YYYY [a las] HH:mm') : 'Por definir' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Hoteles y Estadías --}}
                        <div class="col-12 col-lg-6">
                            <div class="card border rounded-3 h-100">
                                <div class="card-header bg-light py-2 px-3 fw-bold">
                                    <i class="fas fa-hotel text-primary me-2"></i> Alojamiento y Noches
                                </div>
                                <div class="card-body p-3">
                                    @forelse ($alojamiento as ['hotel' => $hotel, 'estadias' => $estadias])
                                        <div class="mb-3 pb-3 border-bottom last:border-0">
                                            <div class="fw-bold fs-6 text-dark">{{ $hotel->nombre }}</div>
                                            <div class="text-muted small">{{ $hotel->direccion ?: $hotel->ciudad }}</div>
                                            <div class="mt-2">
                                                @foreach ($estadias as $hf)
                                                    <span class="badge text-bg-light border me-1">
                                                        {{ $hf->etiqueta }} · {{ $hf->cantidad }} hab.
                                                        @if ($hf->fecha_ingreso)
                                                            ({{ \Carbon\Carbon::parse($hf->fecha_ingreso)->format('d/m') }} → {{ \Carbon\Carbon::parse($hf->fecha_salida)->format('d/m/Y') }})
                                                        @endif
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted small fst-italic mb-0">Sin hoteles asignados al programa.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 4: Pagos & Finanzas (si tiene permisos) --}}
                @if ($verCotizacion)
                    <div class="tab-pane fade" id="tab-finanzas" role="tabpanel">
                        <div class="row g-4 mb-4">
                            <div class="col-12 col-md-4">
                                <div class="card bg-light border-0 p-3 rounded-3">
                                    <div class="text-muted small fw-semibold">PRECIO DE VENTA TOTAL</div>
                                    <div class="h3 fw-bold text-dark mt-1">{{ $money($p['total']) }}</div>
                                    <small class="text-muted">Tarifa cobrada al pasajero</small>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="card bg-light border-0 p-3 rounded-3">
                                    <div class="text-muted small fw-semibold">COSTO NETO OPERATIVO</div>
                                    <div class="h3 fw-bold text-dark mt-1">{{ $money($cost['costo']) }}</div>
                                    <small class="text-muted">Servicios y hoteles de proveedores</small>
                                </div>
                            </div>
                            @can('margen.ver')
                            <div class="col-12 col-md-4">
                                <div class="card bg-success-subtle border-0 p-3 rounded-3">
                                    <div class="text-success small fw-semibold">MARGEN DE GANANCIA</div>
                                    <div class="h3 fw-bold text-success mt-1">{{ $money($cost['margen']) }}</div>
                                    <small class="text-success fw-semibold">{{ number_format($cost['margenPct'], 1) }}% de rentabilidad</small>
                                </div>
                            </div>
                            @endcan
                        </div>

                        {{-- Card de Ajuste Directo de Precios y Márgenes --}}
                        @if ($canEdit)
                            <div class="card border mb-4 rounded-3 shadow-sm bg-white">
                                <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-sliders-h text-primary me-2"></i> Ajustar Precios, Márgenes e Impuestos del Programa</h6>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Edición Directa</span>
                                </div>
                                <div class="card-body p-3">
                                    <form id="form-finanzas-precios">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <label class="form-label small fw-semibold text-muted">Tarifa Adulto (<span data-currency-symbol>{{ $simbolo }}</span>):</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white fw-bold text-success">{{ $simbolo }}</span>
                                                    <input type="number" step="0.01" min="0" class="form-control fw-bold" id="fin-precio-adulto" value="{{ number_format((float)$programa->precioAdulto, 2, '.', '') }}">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <label class="form-label small fw-semibold text-muted">Margen Ganancia (%):</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.1" min="0" max="100" class="form-control text-center fw-bold text-primary" id="fin-porcentaje-ganancia" value="{{ number_format((float)($programa->porcentaje_ganancia ?? 20), 1, '.', '') }}">
                                                    <span class="input-group-text bg-white fw-bold">%</span>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <label class="form-label small fw-semibold text-muted">IGV Comisión (%):</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.1" min="0" max="100" class="form-control text-center fw-bold text-warning-emphasis" id="fin-porcentaje-igv" value="{{ number_format((float)($programa->porcentaje_igv ?? 18), 1, '.', '') }}">
                                                    <span class="input-group-text bg-white fw-bold">%</span>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <label class="form-label small fw-semibold text-muted">Tarifa Niño (<span data-currency-symbol>{{ $simbolo }}</span>):</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white fw-bold">{{ $simbolo }}</span>
                                                    <input type="number" step="0.01" min="0" class="form-control" id="fin-precio-nino" value="{{ $programa->precioChild ? number_format((float)$programa->precioChild, 2, '.', '') : '' }}" placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <label class="form-label small fw-semibold text-muted">Tipo de Cambio:</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white small">S/</span>
                                                    <input type="number" step="0.01" min="0.5" max="99" class="form-control text-center font-monospace" id="fin-tipo-cambio" value="{{ number_format((float)$tc, 2, '.', '') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3 pt-3 border-top">
                                            <div class="small text-muted" id="fin-simulacion-resumen">
                                                Venta Adulto sugerida: <strong class="text-success fs-6" id="fin-lbl-venta-adulto">{{ $money($p['ventaAdulto']) }}</strong> / pax
                                                · Total estimado: <strong class="text-dark fs-6" id="fin-lbl-total-programa">{{ $money($p['total']) }}</strong>
                                            </div>
                                            <div class="d-flex gap-2 align-items-center">
                                                <div id="fin-feedback-msg" class="small" style="display:none;"></div>
                                                <button type="button" class="btn btn-primary px-4 shadow-sm" id="btn-guardar-precios-finanzas">
                                                    <i class="fas fa-save me-1"></i> Guardar Precios del Programa
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <h4 class="h6 fw-bold text-muted text-uppercase mb-3">Historial de Cobros al Cliente</h4>
                        @if ($programa->pagos->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover align-middle border rounded-3">
                                    <thead class="table-light small">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Concepto</th>
                                            <th>Método</th>
                                            <th>Monto</th>
                                            <th>Comprobante</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small">
                                        @foreach ($programa->pagos as $pago)
                                            <tr>
                                                <td>{{ $pago->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="fw-semibold">{{ $pago->concepto }}</td>
                                                <td><span class="badge text-bg-light border">{{ $pago->metodo }}</span></td>
                                                <td class="fw-bold text-success">{{ $money($pago->monto) }}</td>
                                                <td>
                                                    @if ($pago->comprobante_archivo)
                                                        <a href="{{ asset('storage/' . $pago->comprobante_archivo) }}" target="_blank" class="btn btn-xs btn-outline-secondary">
                                                            <i class="fas fa-file-invoice"></i> Comprobante
                                                        </a>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted border rounded-3">
                                <i class="fas fa-wallet fa-2x mb-2 text-secondary opacity-50"></i>
                                <p class="mb-0">No se han registrado cobros para este programa aún.</p>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- TAB 5: Tareas Operativas --}}
                <div class="tab-pane fade" id="tab-tareas" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h6 fw-bold text-uppercase text-muted mb-0">Checklist Operativo del Grupo</h3>
                        <span class="badge text-bg-light border">{{ $tareasResumen['pendientes'] }} pendientes de {{ $tareasResumen['total'] }}</span>
                    </div>

                    @if ($programa->tareas->isNotEmpty())
                        <div class="list-group">
                            @foreach ($programa->tareas as $tarea)
                                <div class="list-group-item d-flex align-items-center justify-content-between py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="text-{{ $tarea->completada_at ? 'success' : 'muted' }} fs-5">
                                            <i class="fas fa-{{ $tarea->completada_at ? 'check-circle' : 'circle' }}"></i>
                                        </span>
                                        <div>
                                            <div class="fw-semibold {{ $tarea->completada_at ? 'text-decoration-line-through text-muted' : 'text-dark' }}">
                                                {{ $tarea->titulo }}
                                            </div>
                                            <small class="text-muted">
                                                @if ($tarea->responsable)
                                                    <i class="fas fa-user-check me-1"></i> {{ $tarea->responsable->name }} ·
                                                @endif
                                                @if ($tarea->fecha_limite)
                                                    <i class="fas fa-calendar-day me-1"></i> Límite: {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge {{ $tarea->completada_at ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning-emphasis' }}">
                                            {{ $tarea->completada_at ? 'Completada' : 'Pendiente' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted border rounded-3">
                            <i class="fas fa-tasks fa-2x mb-2 text-secondary opacity-50"></i>
                            <p class="mb-0">No hay tareas operativas asignadas a este programa.</p>
                        </div>
                    @endif
                </div>

                {{-- TAB 6: Bitácora & Historial de Movimientos --}}
                <div class="tab-pane fade" id="tab-bitacora" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h6 fw-bold text-uppercase text-muted mb-0">Línea de Tiempo de Auditoría</h3>
                        <span class="badge text-bg-light border">{{ $movimientos->count() }} eventos registrados</span>
                    </div>

                    <div class="historial-timeline-wrapper">
                        @if ($movimientos->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-history fa-3x mb-3 text-secondary opacity-50"></i>
                                <p class="mb-0">Todavía no hay movimientos registrados para este grupo.</p>
                            </div>
                        @else
                            @include('movimientos.partials.lista', ['movimientos' => $movimientos, 'conPrograma' => false])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Ajuste Rápido de Precios y Márgenes --}}
    @if ($canEdit && $verCotizacion)
        <div class="modal fade" id="modalAjustarPrecios" tabindex="-1" aria-labelledby="modalAjustarPreciosLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-3 shadow">
                    <div class="modal-header bg-dark text-white py-3">
                        <h5 class="modal-title fs-6 fw-bold" id="modalAjustarPreciosLabel">
                            <i class="fas fa-tags text-warning me-2"></i> Ajustar Precios, Márgenes e Impuestos del Programa
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted small mb-3">
                            Modifique directamente las tarifas oficiales, el margen comercial de Andean Exclusive y el porcentaje de IGV sin salir de esta ficha. Los totales del programa se actualizarán automáticamente.
                        </p>
                        <form id="form-modal-precios">
                            <div class="row g-3">
                                <div class="col-12 col-sm-4">
                                    <label class="form-label small fw-semibold text-muted">Tarifa Adulto ({{ $simbolo }}):</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-white fw-bold text-success">{{ $simbolo }}</span>
                                        <input type="number" step="0.01" min="0" class="form-control fw-bold" id="modal-precio-adulto" value="{{ number_format((float)$programa->precioAdulto, 2, '.', '') }}">
                                    </div>
                                    <small class="text-muted">Tarifa por adulto (12+ años).</small>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <label class="form-label small fw-semibold text-muted">Tarifa Niño ({{ $simbolo }}):</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-white fw-bold">{{ $simbolo }}</span>
                                        <input type="number" step="0.01" min="0" class="form-control" id="modal-precio-nino" value="{{ $programa->precioChild ? number_format((float)$programa->precioChild, 2, '.', '') : '' }}" placeholder="0.00">
                                    </div>
                                    <small class="text-muted">Tarifa niños (5-11 años).</small>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <label class="form-label small fw-semibold text-muted">Pasajeros del Grupo:</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-white fw-bold text-primary"><i class="fas fa-users"></i></span>
                                        <input type="number" step="1" min="1" max="999" class="form-control fw-bold text-center" id="modal-cantidad-pax" value="{{ $programa->cantidad_pax ?: $pagantes }}">
                                    </div>
                                    <small class="text-muted">Cantidad total para cotizar.</small>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <label class="form-label small fw-semibold text-muted">Margen Ganancia (%):</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" min="0" max="100" class="form-control text-center fw-bold text-primary" id="modal-porcentaje-ganancia" value="{{ number_format((float)($programa->porcentaje_ganancia ?? 20), 1, '.', '') }}">
                                        <span class="input-group-text bg-white fw-bold">%</span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        @foreach ([10, 11, 12, 15, 18, 20, 25, 30] as $presetPct)
                                            <button type="button" class="btn btn-xs btn-outline-secondary btn-modal-preset-ganancia" data-pct="{{ $presetPct }}">{{ $presetPct }}%</button>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <label class="form-label small fw-semibold text-muted">IGV Comisión (%):</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" min="0" max="100" class="form-control text-center fw-bold text-warning-emphasis" id="modal-porcentaje-igv" value="{{ number_format((float)($programa->porcentaje_igv ?? 18), 1, '.', '') }}">
                                        <span class="input-group-text bg-white fw-bold">%</span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        <button type="button" class="btn btn-xs btn-outline-secondary btn-modal-preset-igv" data-pct="18">18%</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary btn-modal-preset-igv" data-pct="10">10%</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary btn-modal-preset-igv" data-pct="0">0%</button>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <label class="form-label small fw-semibold text-muted">Tipo de Cambio:</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white small">S/</span>
                                        <input type="number" step="0.01" min="0.5" max="99" class="form-control text-center font-monospace" id="modal-tipo-cambio" value="{{ number_format((float)$tc, 2, '.', '') }}">
                                    </div>
                                    <small class="text-muted">Conversión PEN a USD.</small>
                                </div>
                            </div>

                            <div class="p-3 bg-light rounded-3 border mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Total estimado del Programa:</span>
                                    <span class="fs-5 fw-bold text-dark" id="modal-lbl-total-programa">{{ $money($p['total']) }}</span>
                                </div>
                            </div>

                            <div id="modal-feedback-msg" class="small mt-2" style="display:none;"></div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary px-4 shadow-sm" id="btn-guardar-precios-modal">
                            <i class="fas fa-save me-1"></i> Guardar y Aplicar Precios
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal de Historial Reutilizable --}}
    @include('programas.partials.modal-historial')

@push('styles')
<style>
/* Hoja de costeo: los fondos salen de variables para que el texto se lea en tema claro y oscuro. */
.excel-costeo-table {
    --excel-dia-bg: #eaf1fb;
    --excel-dia-acento: #2563eb;
    --excel-subtotal-bg: #f4f6f9;
    --excel-total-bg: #e7ebf0;
    border-color: var(--ae-border, #dee2e6);
    font-size: 0.86rem;
}
[data-bs-theme="dark"] .excel-costeo-table {
    --excel-dia-bg: rgba(96, 165, 250, 0.13);
    --excel-dia-acento: #93c5fd;
    --excel-subtotal-bg: var(--ae-surface-2);
    --excel-total-bg: rgba(148, 163, 184, 0.16);
}
.excel-costeo-table th, .excel-costeo-table td {
    vertical-align: middle;
}
/* Bootstrap pinta cada celda con --bs-table-bg: el color se fija ahí, no en la fila. */
.excel-row-header-dia > td {
    --bs-table-bg: var(--excel-dia-bg);
    --bs-table-color: var(--ae-ink);
    border-left: 3px solid var(--excel-dia-acento);
}
.excel-row-header-dia .excel-dia-num {
    color: var(--excel-dia-acento) !important;
    letter-spacing: 0.04em;
}
.excel-row-header-dia .excel-dia-titulo {
    color: var(--ae-ink) !important;
    font-size: 0.95rem;
}
.excel-subtotal-dia > td {
    --bs-table-bg: var(--excel-subtotal-bg);
    --bs-table-color: var(--ae-ink);
}
.excel-costeo-table .table-secondary > td,
.excel-costeo-table .table-secondary > th {
    --bs-table-bg: var(--excel-total-bg);
    --bs-table-color: var(--ae-ink);
}
@media (min-width: 992px) {
    .border-end-lg {
        border-right: 1px solid var(--ae-border, #dee2e6);
    }
}
@media print {
    body * {
        visibility: hidden !important;
    }
    #tab-costeo-excel, #tab-costeo-excel * {
        visibility: visible !important;
    }
    #tab-costeo-excel {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    .btn, .nav, .ae-admin-page-header, .card-header, #costeo-comision-input, #costeo-igv-input, .btn-preset-pct, .btn-preset-igv, input[name="divisor_paxs"], #btn-guardar-cotizacion, #btn-export-excel-csv {
        display: none !important;
    }
    .excel-costeo-table {
        font-size: 9pt !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rawSubtotalEl = document.getElementById('val-subtotal');
    const rawSubtotal = rawSubtotalEl ? (parseFloat(rawSubtotalEl.dataset.rawSubtotal) || 0) : 0;
    const comisionInput = document.getElementById('costeo-comision-input');
    const badgeComisionPct = document.getElementById('costeo-badge-comision-pct');
    const lblComisionPct = document.getElementById('lbl-comision-pct');
    const valComision = document.getElementById('val-comision');
    const valCostoTotal = document.getElementById('val-costo-total');
    const igvInput = document.getElementById('costeo-igv-input');
    const badgeIgvPct = document.getElementById('costeo-badge-igv-pct');
    const lblIgvPct = document.getElementById('lbl-igv-pct');
    const rowIgv = document.getElementById('row-igv');
    const valIgv = document.getElementById('val-igv');
    const valTotalCotizacion = document.getElementById('val-total-cotizacion');
    const valPrecioPax = document.getElementById('val-precio-pax');
    const costeoPrecioAdultoInput = document.getElementById('costeo-precio-adulto-input');
    const costeoPrecioNinoInput = document.getElementById('costeo-precio-nino-input');
    const btnRevertCalc = document.getElementById('btn-revert-calc-price');
    const btnGuardarCotizacion = document.getElementById('btn-guardar-cotizacion');
    const feedbackMsg = document.getElementById('costeo-feedback-msg');
    const btnExportCsv = document.getElementById('btn-export-excel-csv');

    // Elementos de Tab Finanzas
    const finPrecioAdulto = document.getElementById('fin-precio-adulto');
    const finPorcentajeGanancia = document.getElementById('fin-porcentaje-ganancia');
    const finPorcentajeIgv = document.getElementById('fin-porcentaje-igv');
    const finPrecioNino = document.getElementById('fin-precio-nino');
    const finTipoCambio = document.getElementById('fin-tipo-cambio');
    const btnGuardarFinanzas = document.getElementById('btn-guardar-precios-finanzas');
    const finFeedbackMsg = document.getElementById('fin-feedback-msg');
    const finLblVentaAdulto = document.getElementById('fin-lbl-venta-adulto');
    const finLblTotalPrograma = document.getElementById('fin-lbl-total-programa');

    // Elementos de Modal
    const modalPrecioAdulto = document.getElementById('modal-precio-adulto');
    const modalPrecioNino = document.getElementById('modal-precio-nino');
    const modalPorcentajeGanancia = document.getElementById('modal-porcentaje-ganancia');
    const modalPorcentajeIgv = document.getElementById('modal-porcentaje-igv');
    const modalTipoCambio = document.getElementById('modal-tipo-cambio');
    const modalLblTotalPrograma = document.getElementById('modal-lbl-total-programa');
    const btnGuardarModal = document.getElementById('btn-guardar-precios-modal');
    const modalFeedbackMsg = document.getElementById('modal-feedback-msg');

    const adultosCount = {{ (int) $p['adultos'] }};
    const ninosCount = {{ (int) $p['ninos'] }};
    const suplementoInd = {{ (float) $p['suplemento'] }};
    const monedaSimbolo = "{{ $simbolo }} ";

    function formatNumber(num) {
        return Number(num || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    let userManuallyEditedPrice = false;

    // Recalculo reactivo para la Hoja de Costeo
    function recalcularCotizacion() {
        const pct = Math.max(0, parseFloat(comisionInput ? comisionInput.value : 0) || 0);
        if (badgeComisionPct) badgeComisionPct.textContent = pct.toFixed(1) + '%';
        if (lblComisionPct) lblComisionPct.textContent = pct.toFixed(1);

        const comisionMonto = rawSubtotal * (pct / 100);
        const costoTotal = rawSubtotal + comisionMonto;

        const igvPct = Math.max(0, parseFloat(igvInput ? igvInput.value : 0) || 0);
        if (badgeIgvPct) badgeIgvPct.textContent = igvPct.toFixed(1) + '%';
        if (lblIgvPct) lblIgvPct.textContent = igvPct.toFixed(1);

        const igvMonto = comisionMonto * (igvPct / 100);
        const totalCotizacion = costoTotal + igvMonto;

        const divisorRadio = document.querySelector('input[name="divisor_paxs"]:checked');
        const divisor = divisorRadio ? (parseFloat(divisorRadio.value) || 1) : 1;
        const precioPax = divisor > 0 ? (totalCotizacion / divisor) : 0;

        if (valComision) valComision.textContent = formatNumber(comisionMonto);
        if (valCostoTotal) valCostoTotal.textContent = formatNumber(costoTotal);
        if (valIgv) valIgv.textContent = formatNumber(igvMonto);
        if (rowIgv) rowIgv.style.opacity = igvPct > 0 ? '1' : '0.45';
        if (valTotalCotizacion) valTotalCotizacion.textContent = formatNumber(totalCotizacion);
        if (valPrecioPax) valPrecioPax.textContent = formatNumber(precioPax);

        if (costeoPrecioAdultoInput && !userManuallyEditedPrice) {
            costeoPrecioAdultoInput.value = precioPax.toFixed(2);
        }

        return { pct, igvPct, comisionMonto, costoTotal, igvMonto, totalCotizacion, precioPax, divisor };
    }

    // Inicializar Hoja de Costeo si está presente
    if (comisionInput || igvInput) {
        recalcularCotizacion();
        comisionInput?.addEventListener('input', () => { userManuallyEditedPrice = false; recalcularCotizacion(); });
        igvInput?.addEventListener('input', () => { userManuallyEditedPrice = false; recalcularCotizacion(); });
        document.querySelectorAll('input[name="divisor_paxs"]').forEach(r => {
            r.addEventListener('change', () => { userManuallyEditedPrice = false; recalcularCotizacion(); });
        });

        document.querySelectorAll('.btn-preset-pct').forEach(btn => {
            btn.addEventListener('click', function () {
                if (comisionInput) {
                    comisionInput.value = this.dataset.pct;
                    userManuallyEditedPrice = false;
                    recalcularCotizacion();
                }
            });
        });

        document.querySelectorAll('.btn-preset-igv').forEach(btn => {
            btn.addEventListener('click', function () {
                if (igvInput) {
                    igvInput.value = this.dataset.pct;
                    userManuallyEditedPrice = false;
                    recalcularCotizacion();
                }
            });
        });

        costeoPrecioAdultoInput?.addEventListener('input', () => {
            userManuallyEditedPrice = true;
        });

        btnRevertCalc?.addEventListener('click', () => {
            userManuallyEditedPrice = false;
            recalcularCotizacion();
        });
    }

    // Función universal para guardar precios y sincronizar toda la Ficha
    async function guardarPreciosPrograma(datos, btnEl, feedbackEl) {
        const originalHtml = btnEl ? btnEl.innerHTML : '';
        if (btnEl) {
            btnEl.disabled = true;
            btnEl.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
        }

        try {
            const resp = await fetch("{{ route('programas.actualizar-cotizacion', $programa) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(datos)
            });

            const data = await resp.json();
            if (data.ok) {
                if (btnEl) {
                    btnEl.innerHTML = '<i class="fas fa-check me-2"></i> ¡Guardado!';
                    btnEl.classList.remove('btn-success', 'btn-primary');
                    btnEl.classList.add('btn-dark');
                }

                if (feedbackEl) {
                    feedbackEl.style.display = 'block';
                    feedbackEl.className = 'small mt-1 text-success fw-bold';
                    feedbackEl.innerHTML = '<i class="fas fa-check-circle me-1"></i> Precios guardados exitosamente: ' + (data.simbolo || '$') + ' ' + formatNumber(data.ventaAdulto) + ' / pax (Total: ' + (data.simbolo || '$') + ' ' + formatNumber(data.total) + ')';
                }

                // Sincronizar todos los indicadores y elementos de la página
                const kpiTotal = document.getElementById('kpi-total-venta');
                if (kpiTotal) kpiTotal.textContent = (data.simbolo || '$') + ' ' + formatNumber(data.total);

                const tabFinanzasTotal = document.getElementById('tab-finanzas-total-venta');
                if (tabFinanzasTotal) tabFinanzasTotal.textContent = (data.simbolo || '$') + ' ' + formatNumber(data.total);

                // Sincronizar inputs en Tab Finanzas
                if (finPrecioAdulto) finPrecioAdulto.value = Number(data.precioAdulto || 0).toFixed(2);
                if (finPorcentajeGanancia) finPorcentajeGanancia.value = Number(data.porcentaje_ganancia || 0).toFixed(1);
                if (finPorcentajeIgv) finPorcentajeIgv.value = Number(data.porcentaje_igv || 18).toFixed(1);
                if (finPrecioNino) finPrecioNino.value = data.precioChild ? Number(data.precioChild).toFixed(2) : '';
                if (finTipoCambio && data.tipo_cambio) finTipoCambio.value = Number(data.tipo_cambio).toFixed(2);
                if (finLblVentaAdulto) finLblVentaAdulto.textContent = (data.simbolo || '$') + ' ' + formatNumber(data.ventaAdulto);
                if (finLblTotalPrograma) finLblTotalPrograma.textContent = (data.simbolo || '$') + ' ' + formatNumber(data.total);

                // Sincronizar inputs en Modal
                if (modalPrecioAdulto) modalPrecioAdulto.value = Number(data.precioAdulto || 0).toFixed(2);
                if (modalPorcentajeGanancia) modalPorcentajeGanancia.value = Number(data.porcentaje_ganancia || 0).toFixed(1);
                if (modalPorcentajeIgv) modalPorcentajeIgv.value = Number(data.porcentaje_igv || 18).toFixed(1);
                if (modalPrecioNino) modalPrecioNino.value = data.precioChild ? Number(data.precioChild).toFixed(2) : '';
                if (modalTipoCambio && data.tipo_cambio) modalTipoCambio.value = Number(data.tipo_cambio).toFixed(2);
                if (modalLblTotalPrograma) modalLblTotalPrograma.textContent = (data.simbolo || '$') + ' ' + formatNumber(data.total);

                // Sincronizar inputs en Hoja de Costeo
                if (costeoPrecioAdultoInput) costeoPrecioAdultoInput.value = Number(data.precioAdulto || 0).toFixed(2);
                if (costeoPrecioNinoInput && data.precioChild) costeoPrecioNinoInput.value = Number(data.precioChild).toFixed(2);

                setTimeout(() => {
                    if (btnEl) {
                        btnEl.disabled = false;
                        btnEl.innerHTML = originalHtml;
                        btnEl.classList.remove('btn-dark');
                        btnEl.classList.add(btnEl.id === 'btn-guardar-cotizacion' ? 'btn-success' : 'btn-primary');
                    }
                }, 3000);

                return data;
            } else {
                throw new Error(data.message || 'Error al guardar');
            }
        } catch (err) {
            if (btnEl) {
                btnEl.disabled = false;
                btnEl.innerHTML = originalHtml;
            }
            if (feedbackEl) {
                feedbackEl.style.display = 'block';
                feedbackEl.className = 'small mt-1 text-danger fw-bold';
                feedbackEl.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> No se pudo guardar. Intente nuevamente.';
            }
            throw err;
        }
    }

    // Botón Guardar en Hoja de Costeo
    btnGuardarCotizacion?.addEventListener('click', function () {
        const calc = recalcularCotizacion();
        const precioAdulto = costeoPrecioAdultoInput ? (parseFloat(costeoPrecioAdultoInput.value) || calc.precioPax) : calc.precioPax;
        const precioChild = costeoPrecioNinoInput ? (parseFloat(costeoPrecioNinoInput.value) || null) : null;

        guardarPreciosPrograma({
            precioAdulto: precioAdulto.toFixed(2),
            precioChild: precioChild !== null ? precioChild.toFixed(2) : null,
            porcentaje_ganancia: calc.pct.toFixed(2),
            porcentaje_igv: calc.igvPct.toFixed(2),
            tipo_cambio: {{ $tc }}
        }, btnGuardarCotizacion, feedbackMsg);
    });

    // Simulación en tiempo real dentro de Tab Finanzas
    function recalcularFinanzas() {
        const pAdulto = parseFloat(finPrecioAdulto ? finPrecioAdulto.value : 0) || 0;
        const pChild = parseFloat(finPrecioNino ? finPrecioNino.value : 0) || 0;
        const pGanancia = parseFloat(finPorcentajeGanancia ? finPorcentajeGanancia.value : 0) || 0;
        const factor = 1 + (pGanancia / 100);
        const ventaAdulto = pAdulto * factor;
        const ventaNino = pChild * factor;
        const total = (adultosCount * ventaAdulto) + (ninosCount * ventaNino) + suplementoInd;

        if (finLblVentaAdulto) finLblVentaAdulto.textContent = monedaSimbolo + formatNumber(ventaAdulto);
        if (finLblTotalPrograma) finLblTotalPrograma.textContent = monedaSimbolo + formatNumber(total);
    }

    finPrecioAdulto?.addEventListener('input', recalcularFinanzas);
    finPorcentajeGanancia?.addEventListener('input', recalcularFinanzas);
    finPrecioNino?.addEventListener('input', recalcularFinanzas);

    // Guardar desde Tab Finanzas
    btnGuardarFinanzas?.addEventListener('click', function () {
        const pAdulto = parseFloat(finPrecioAdulto ? finPrecioAdulto.value : 0) || 0;
        const pChild = finPrecioNino && finPrecioNino.value !== '' ? parseFloat(finPrecioNino.value) : null;
        const pGanancia = parseFloat(finPorcentajeGanancia ? finPorcentajeGanancia.value : 0) || 0;
        const pIgv = parseFloat(finPorcentajeIgv ? finPorcentajeIgv.value : 18) || 0;
        const tc = parseFloat(finTipoCambio ? finTipoCambio.value : {{ $tc }}) || {{ $tc }};

        guardarPreciosPrograma({
            precioAdulto: pAdulto.toFixed(2),
            precioChild: pChild !== null ? pChild.toFixed(2) : null,
            porcentaje_ganancia: pGanancia.toFixed(2),
            porcentaje_igv: pIgv.toFixed(2),
            tipo_cambio: tc
        }, btnGuardarFinanzas, finFeedbackMsg);
    });

    // Simulación en tiempo real dentro del Modal
    function recalcularModal() {
        const pAdulto = parseFloat(modalPrecioAdulto ? modalPrecioAdulto.value : 0) || 0;
        const pChild = parseFloat(modalPrecioNino ? modalPrecioNino.value : 0) || 0;
        const pGanancia = parseFloat(modalPorcentajeGanancia ? modalPorcentajeGanancia.value : 0) || 0;
        const factor = 1 + (pGanancia / 100);
        const total = (adultosCount * pAdulto * factor) + (ninosCount * pChild * factor) + suplementoInd;

        if (modalLblTotalPrograma) modalLblTotalPrograma.textContent = monedaSimbolo + formatNumber(total);
    }

    modalPrecioAdulto?.addEventListener('input', recalcularModal);
    modalPorcentajeGanancia?.addEventListener('input', recalcularModal);
    modalPrecioNino?.addEventListener('input', recalcularModal);

    document.querySelectorAll('.btn-modal-preset-ganancia').forEach(btn => {
        btn.addEventListener('click', function () {
            if (modalPorcentajeGanancia) {
                modalPorcentajeGanancia.value = this.dataset.pct;
                recalcularModal();
            }
        });
    });

    document.querySelectorAll('.btn-modal-preset-igv').forEach(btn => {
        btn.addEventListener('click', function () {
            if (modalPorcentajeIgv) {
                modalPorcentajeIgv.value = this.dataset.pct;
            }
        });
    });

    // Guardar desde el Modal
    btnGuardarModal?.addEventListener('click', async function () {
        const pAdulto = parseFloat(modalPrecioAdulto ? modalPrecioAdulto.value : 0) || 0;
        const pChild = modalPrecioNino && modalPrecioNino.value !== '' ? parseFloat(modalPrecioNino.value) : null;
        const pGanancia = parseFloat(modalPorcentajeGanancia ? modalPorcentajeGanancia.value : 0) || 0;
        const pIgv = parseFloat(modalPorcentajeIgv ? modalPorcentajeIgv.value : 18) || 0;
        const cantPax = parseInt(document.getElementById('modal-cantidad-pax')?.value, 10) || {{ $pagantes }};
        const tc = parseFloat(modalTipoCambio ? modalTipoCambio.value : {{ $tc }}) || {{ $tc }};

        try {
            await guardarPreciosPrograma({
                precioAdulto: pAdulto.toFixed(2),
                precioChild: pChild !== null ? pChild.toFixed(2) : null,
                porcentaje_ganancia: pGanancia.toFixed(2),
                porcentaje_igv: pIgv.toFixed(2),
                cantidad_pax: cantPax,
                tipo_cambio: tc
            }, btnGuardarModal, modalFeedbackMsg);

            setTimeout(() => {
                const modalEl = document.getElementById('modalAjustarPrecios');
                if (modalEl && window.bootstrap?.Modal) {
                    const inst = bootstrap.Modal.getInstance(modalEl);
                    if (inst) inst.hide();
                }
                // Si cambió la cantidad de pasajeros, recargamos para refrescar la hoja de liquidación con las cantidades multiplicadas
                if (cantPax !== {{ $pagantes }}) {
                    window.location.reload();
                }
            }, 1200);
        } catch (e) {
            // Error ya mostrado
        }
    });

    // Exportar CSV compatible con Excel
    btnExportCsv?.addEventListener('click', function () {
        const rows = [];
        rows.push(['ANDEAN EXCLUSIVE TOURS - HOJA DE COSTEO & COTIZACION']);
        rows.push(['File / Programa:', "{{ $programa->codigo }} - {{ $programa->nombre }}"]);
        rows.push(['Cliente:', "{{ $programa->cliente?->nombre ?: 'Sin asignar' }}"]);
        rows.push(['Pasajeros:', "{{ $totalPaxs }} personas ({{ $pagantes }} pagantes)"]);
        rows.push(['Fechas:', "{{ $inicio ? $inicio->format('d/m/Y') : '' }} - {{ $fin ? $fin->format('d/m/Y') : '' }}"]);
        rows.push(['Tipo de Cambio:', "S/ {{ number_format($tc, 2) }} por US$ 1.00"]);
        rows.push([]);
        rows.push(['Dia', 'Fecha', 'Hora', 'Detalle del Servicio', 'Proveedor', 'Modalidad', 'Costo Unitario USD', 'Cantidad', 'Costo Total USD', 'Notas']);

        document.querySelectorAll('#tabla-costeo-excel tbody tr.excel-row-servicio').forEach(tr => {
            rows.push([
                tr.dataset.dia || '',
                tr.dataset.fecha || '',
                tr.querySelector('td:nth-child(1)')?.innerText.trim() || '',
                tr.dataset.servicio || '',
                tr.dataset.proveedor || '',
                tr.dataset.modalidad || '',
                parseFloat(tr.dataset.costoUnit || 0).toFixed(2),
                tr.dataset.qty || '1',
                parseFloat(tr.dataset.totalUsd || 0).toFixed(2),
                tr.dataset.notas || ''
            ]);
        });

        const calc = recalcularCotizacion();
        rows.push([]);
        rows.push(['', '', '', '', '', '', 'SUB-TOTAL PROVEEDORES:', '', rawSubtotal.toFixed(2)]);
        rows.push(['', '', '', '', '', '', 'Comision Andean Exclusive (' + calc.pct.toFixed(1) + '%):', '', calc.comisionMonto.toFixed(2)]);
        rows.push(['', '', '', '', '', '', 'Costo Total:', '', calc.costoTotal.toFixed(2)]);
        rows.push(['', '', '', '', '', '', 'IGV Comision (' + calc.igvPct.toFixed(1) + '%):', '', calc.igvMonto.toFixed(2)]);
        rows.push(['', '', '', '', '', '', 'TOTAL COTIZACION:', '', calc.totalCotizacion.toFixed(2)]);
        rows.push(['', '', '', '', '', '', 'PRECIO POR PASAJERO:', '', calc.precioPax.toFixed(2)]);

        const csvContent = "\uFEFF" + rows.map(e => e.map(val => '"' + (val || '').toString().replace(/"/g, '""') + '"').join(',')).join("\r\n");
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement("a");
        const url = URL.createObjectURL(blob);
        link.setAttribute("href", url);
        link.setAttribute("download", "COTIZACION_{{ $programa->codigo }}.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});
</script>
@endpush
@endsection
