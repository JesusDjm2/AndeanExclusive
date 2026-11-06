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
            <button type="button" class="btn btn-outline-secondary"
                data-historial-modal
                data-programa-nombre="{{ $programa->nombre }}"
                data-programa-codigo="{{ $programa->codigo }}"
                data-historial-url="{{ route('programas.historial', $programa) }}">
                <i class="fas fa-history me-1 text-primary"></i> Bitácora
            </button>
            <a href="{{ route('programas.pdf', [$programa, 'ver' => 1]) }}" class="btn btn-outline-danger" target="_blank">
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
                    <div class="h4 fw-bold mb-1">{{ $money($p['total']) }}</div>
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

                    @forelse ($programa->dias as $dia)
                        <div class="card border mb-3 rounded-3 overflow-hidden">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
                                <div>
                                    <strong class="text-primary me-2">Día {{ $dia->dia }}:</strong>
                                    <span class="fw-bold">{{ $dia->titulo }}</span>
                                    @if ($dia->fecha)
                                        <small class="text-muted ms-2">({{ \Carbon\Carbon::parse($dia->fecha)->locale('es')->isoFormat('dddd D [de] MMMM') }})</small>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-3">
                                @if ($dia->descripcion)
                                    <div class="text-muted small mb-3">{!! nl2br(e($dia->descripcion)) !!}</div>
                                @endif

                                @php $serviciosDia = $programa->servicios->where('fecha', (string) $dia->fecha); @endphp
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
                                                        <td><span class="badge text-bg-light border">{{ $s->categoria }}</span></td>
                                                        <td>
                                                            <span class="badge {{ $s->estado === 'confirmado' ? 'bg-success-subtle text-success' : ($s->estado === 'cancelado' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning') }}">
                                                                {{ ucfirst($s->estado) }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end">
                                                            @if ($s->proveedor_id)
                                                                <a href="{{ route('servicios.voucher', $s) }}" class="btn btn-xs btn-outline-secondary" target="_blank" title="Ver orden de servicio">
                                                                    <i class="fas fa-file-invoice"></i> Voucher
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
                            <div class="col-12 col-md-4">
                                <div class="card bg-success-subtle border-0 p-3 rounded-3">
                                    <div class="text-success small fw-semibold">MARGEN DE GANANCIA</div>
                                    <div class="h3 fw-bold text-success mt-1">{{ $money($cost['margen']) }}</div>
                                    <small class="text-success fw-semibold">{{ number_format($cost['margenPct'], 1) }}% de rentabilidad</small>
                                </div>
                            </div>
                        </div>

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

    {{-- Modal de Historial Reutilizable --}}
    @include('programas.partials.modal-historial')
@endsection
