@extends('layouts.app')
@section('titulo', 'Programas por año y mes')

@php
    $mesCorto = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    $money = fn ($n) => '$' . number_format((float) $n, 2);
    $authUser = auth()->user();
    $canEdit = $authUser?->can('programas.gestionar');
    $verCotizacion = $authUser?->can('cotizacion.ver');
    $rol = $authUser?->roles->first()?->name;
@endphp

@section('contenido')
    <div class="container-fluid">
        {{-- Encabezado con atajos fluidos entre vistas de Programas --}}
        <div class="ae-admin-page-header d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
            <div>
                <a href="{{ route('programas.index') }}" class="pf-header__back mb-1 d-inline-block text-decoration-none">
                    <i class="fas fa-arrow-left me-1" aria-hidden="true"></i> Volver al listado general
                </a>
                <h1 class="ae-admin-page-title mb-1">
                    <i class="fas fa-fw fa-calendar-alt text-primary me-2"></i>
                    Programas por Año y Mes
                </h1>
                <small class="ae-admin-page-desc">Calendario y segmentación de salidas por temporada para control comercial y logístico.</small>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('programas.index') }}" class="btn btn-outline-secondary" title="Ver listado general con filtros avanzados">
                    <i class="fas fa-list me-1" aria-hidden="true"></i> Listado general
                </a>
                <a href="{{ route('programas.plantillas') }}" class="btn btn-outline-secondary" title="Plantillas maestras">
                    <i class="fas fa-copy me-1" aria-hidden="true"></i> Plantillas
                </a>
                @if ($canEdit)
                    <a href="{{ route('programas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1" aria-hidden="true"></i> Nuevo programa
                    </a>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($sinAsignarCount > 0)
            <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>{{ $sinAsignarCount }}</strong> programa(s) sin año o mes asignado.
                        Asígnalos al crear o editar para que figuren en esta vista de calendario.
                    </div>
                    <a href="{{ route('programas.index') }}" class="btn btn-sm btn-outline-dark">Ver programas sin asignar</a>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($anios->isEmpty())
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center text-muted py-5">
                    <i class="fas fa-calendar-times fa-3x mb-3 d-block text-secondary"></i>
                    <p class="mb-0">No hay años configurados en el sistema.</p>
                </div>
            </div>
        @else
            {{-- Selector de Año --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header py-3 bg-white border-bottom">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-calendar-day me-2"></i>1. Selecciona el Año
                    </h6>
                </div>
                <div class="card-body py-3">
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        @foreach ($anios as $anio)
                            @php
                                $cnt = (int) ($countsByAnio[$anio->id] ?? 0);
                                $isActive = (int) $anioId === (int) $anio->id;
                            @endphp
                            <a href="{{ route('programas.por-periodo', ['anio_id' => $anio->id]) }}"
                                class="btn px-4 {{ $isActive ? 'btn-primary shadow-sm' : 'btn-outline-secondary' }}">
                                <span class="fw-bold">{{ $anio->anio }}</span>
                                <span class="badge {{ $isActive ? 'bg-light text-primary' : 'bg-secondary' }} ms-2">{{ $cnt }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Selector de Mes (Cuadrícula interactiva) --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header py-3 bg-white border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-th-large me-2"></i>2. Selecciona el Mes
                        @if ($anioActivo)
                            <span class="text-secondary fw-normal">({{ $anioActivo->anio }})</span>
                        @endif
                    </h6>
                    @if ($mesActivo)
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                            Mes activo: <strong>{{ $mesActivo->nombre }}</strong>
                        </span>
                    @endif
                </div>
                <div class="card-body p-3">
                    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-2">
                        @foreach ($meses as $mes)
                            @php
                                $c = (int) ($monthCounts[$mes->id] ?? 0);
                                $isActive = (int) $mesId === (int) $mes->id && $anioId;
                                $abbr = $mesCorto[$mes->numero - 1] ?? $mes->nombre;
                                $href = $anioId
                                    ? route('programas.por-periodo', ['anio_id' => $anioId, 'mes_id' => $mes->id])
                                    : '#';
                            @endphp
                            <div class="col">
                                @if ($anioId)
                                    <a href="{{ $href }}"
                                        class="card h-100 text-decoration-none border transition-all {{ $isActive ? 'border-primary shadow-sm bg-primary text-white' : 'border-secondary-subtle bg-white text-dark hover-elevate' }}">
                                        <div class="card-body p-2 p-md-3 text-center position-relative">
                                            <span class="position-absolute top-0 end-0 badge {{ $isActive ? 'bg-white text-primary' : ($c > 0 ? 'bg-success' : 'bg-secondary-subtle text-muted') }} rounded-pill m-1">
                                                {{ $c }}
                                            </span>
                                            <div class="fw-bold fs-5 pt-1 {{ $isActive ? 'text-white' : 'text-primary' }}">{{ $abbr }}</div>
                                            <small class="d-block text-capitalize {{ $isActive ? 'text-white-50' : 'text-muted' }}">{{ $mes->nombre }}</small>
                                        </div>
                                    </a>
                                @else
                                    <div class="card h-100 border border-secondary-subtle opacity-50 bg-light">
                                        <div class="card-body p-2 p-md-3 text-center position-relative">
                                            <span class="position-absolute top-0 end-0 badge bg-secondary rounded-pill m-1">—</span>
                                            <div class="fw-bold text-muted fs-5 pt-1">{{ $abbr }}</div>
                                            <small class="text-muted d-block text-capitalize">{{ $mes->nombre }}</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Listado de Programas del Mes Seleccionado con Tarjetas y Acciones Senior --}}
            @if ($anioId && $mesId)
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <h3 class="h5 mb-0 fw-bold text-secondary">
                        <i class="fas fa-plane-departure text-primary me-2"></i>
                        Viajes programados para {{ optional($mesActivo)->nombre }} de {{ optional($anioActivo)->anio }}
                    </h3>
                    <span class="badge bg-primary px-3 py-2 fs-6 rounded-pill">
                        {{ $programas->count() }} {{ $programas->count() === 1 ? 'viaje' : 'viajes' }}
                    </span>
                </div>

                @if ($programas->isEmpty())
                    <div class="card shadow-sm border-0 mb-5">
                        <div class="card-body text-center text-muted py-5">
                            <i class="far fa-calendar-times fa-3x mb-3 d-block text-secondary opacity-50"></i>
                            <h5 class="fw-bold text-dark">No hay programas asignados en este mes</h5>
                            <p class="mb-3 text-muted">Puedes crear una nueva cotización para este periodo o asignarle año y mes a programas existentes.</p>
                            @if ($canEdit)
                                <a href="{{ route('programas.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Crear programa para este mes
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="row g-3 mb-5">
                        @foreach ($programas as $programa)
                            @php
                                $fase = $programa->faseViaje(); // 'proximo', 'en_curso', 'finalizado', 'sin_fechas'
                                $estado = $programa->estado; // 'cotizacion', 'confirmado', 'cancelado'
                                $cobranza = $programa->cobranza();
                                $presupuesto = $programa->presupuesto();
                                $diasParaInicio = $programa->diasParaInicio();
                                $serviciosTotal = $programa->servicios->count();
                                $serviciosConfirmados = $programa->servicios->where('estado', 'confirmado')->count();
                                $tareasPendientes = $programa->tareas->where('completada', false)->count();
                            @endphp
                            <div class="col-md-6 col-xl-4">
                                <div class="card shadow-sm border h-100 prog-periodo-card d-flex flex-column">
                                    {{-- Encabezado con Código y Badges de Fase/Estado --}}
                                    <div class="card-header bg-white border-bottom py-2 px-3 d-flex justify-content-between align-items-center">
                                        <span class="badge bg-light text-dark border font-monospace">
                                            {{ $programa->codigo }}
                                        </span>
                                        <div class="d-flex align-items-center gap-1">
                                            {{-- Badge de Fase de Viaje --}}
                                            @if ($fase === 'finalizado')
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-flag-checkered me-1"></i> Finalizado
                                                </span>
                                            @elseif ($fase === 'en_curso')
                                                <span class="badge text-white" style="background-color: #0f766e;">
                                                    <i class="fas fa-plane-departure me-1"></i> En Servicio
                                                </span>
                                            @elseif ($fase === 'proximo')
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-clock me-1"></i> Próximo {{ $diasParaInicio !== null ? "({$diasParaInicio}d)" : '' }}
                                                </span>
                                            @endif

                                            {{-- Badge de Estado Comercial --}}
                                            @if ($estado === 'confirmado')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">Confirmado</span>
                                            @elseif ($estado === 'cotizacion')
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Cotización</span>
                                            @elseif ($estado === 'cancelado')
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Cancelado</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="card-body p-3 d-flex flex-column">
                                        {{-- Título y Titular --}}
                                        <h4 class="h6 fw-bold mb-1">
                                            @if ($canEdit)
                                                <a href="{{ route('programas.edit', $programa) }}" class="text-decoration-none text-dark hover-primary">
                                                    {{ $programa->nombre }}
                                                </a>
                                            @else
                                                {{ $programa->nombre }}
                                            @endif
                                        </h4>

                                        <p class="small text-muted mb-2">
                                            <i class="far fa-user me-1 text-primary"></i>
                                            <strong>{{ $programa->cliente?->nombre ?? 'Sin cliente asignado' }}</strong>
                                            <span class="mx-1">·</span>
                                            <i class="fas fa-users me-1"></i>{{ $programa->paxs->count() }} pax(s)
                                            @if ($programa->agentes->isNotEmpty())
                                                <span class="mx-1">·</span>
                                                <i class="fas fa-user-tie me-1"></i>{{ $programa->agentes->pluck('nombre')->join(', ') }}
                                            @endif
                                        </p>

                                        {{-- Fechas y Duración --}}
                                        <div class="small text-secondary mb-3">
                                            <i class="far fa-calendar-alt me-1 text-primary"></i>
                                            @if ($programa->inicio && $programa->fin)
                                                {{ \Carbon\Carbon::parse($programa->inicio)->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($programa->fin)->format('d/m/Y') }}
                                                <span class="text-muted">({{ $programa->duracionDias() }} días)</span>
                                            @else
                                                <span class="text-muted fst-italic">Fechas por definir</span>
                                            @endif
                                        </div>

                                        {{-- Resumen contextual: Métricas si está Finalizado vs Operaciones si está Activo/Próximo --}}
                                        @if ($fase === 'finalizado')
                                            {{-- BLOQUE FINALIZADO: Métricas, liquidación y auditoría --}}
                                            <div class="prog-periodo-metricas p-2 mb-3 rounded bg-light border">
                                                <div class="d-flex justify-content-between align-items-center small mb-1">
                                                    <span class="text-muted">Total facturado:</span>
                                                    <strong class="text-dark">{{ $money($cobranza['total']) }}</strong>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center small mb-1">
                                                    <span class="text-muted">Estado de cobranza:</span>
                                                    @if ($cobranza['saldo'] <= 0.01)
                                                        <span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i>Liquidado</span>
                                                    @else
                                                        <span class="text-danger fw-bold"><i class="fas fa-exclamation-circle me-1"></i>Saldo: {{ $money($cobranza['saldo']) }}</span>
                                                    @endif
                                                </div>
                                                @if ($verCotizacion)
                                                    <div class="d-flex justify-content-between align-items-center small pt-1 border-top">
                                                        <span class="text-muted">Margen / Rentabilidad:</span>
                                                        <span class="text-success fw-semibold">{{ $presupuesto['pctGanancia'] }}% ({{ $money($presupuesto['ganancia']) }})</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            {{-- BLOQUE EN SERVICIO / PRÓXIMO: Logística, cobranzas y reservas --}}
                                            <div class="prog-periodo-status p-2 mb-3 rounded bg-light border">
                                                <div class="d-flex justify-content-between align-items-center small mb-1">
                                                    <span class="text-muted">Cobranza del viaje:</span>
                                                    <strong class="{{ $cobranza['porcentaje'] >= 100 ? 'text-success' : 'text-primary' }}">
                                                        {{ $cobranza['porcentaje'] }}% cobrado ({{ $money($cobranza['pagado']) }})
                                                    </strong>
                                                </div>
                                                <div class="progress mb-2" style="height: 5px;">
                                                    <div class="progress-bar {{ $cobranza['porcentaje'] >= 100 ? 'bg-success' : 'bg-primary' }}" style="width: {{ min(100, $cobranza['porcentaje']) }}%"></div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center small">
                                                    <span>
                                                        <i class="fas fa-clipboard-check text-muted me-1"></i>
                                                        Servicios: <strong>{{ $serviciosConfirmados }}/{{ $serviciosTotal }}</strong>
                                                    </span>
                                                    @if ($tareasPendientes > 0)
                                                        <span class="text-warning-emphasis">
                                                            <i class="fas fa-exclamation-triangle me-1"></i><strong>{{ $tareasPendientes }}</strong> tareas
                                                        </span>
                                                    @else
                                                        <span class="text-success">
                                                            <i class="fas fa-check me-1"></i>Al día
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Barra de Acciones Inteligentes (Roles y Estado) --}}
                                        <div class="mt-auto pt-2 border-top d-flex flex-wrap align-items-center justify-content-between gap-1">
                                            <div class="d-flex flex-wrap gap-1">
                                                @if ($fase === 'finalizado')
                                                    {{-- ACCIONES PARA PROGRAMA FINALIZADO --}}
                                                    @if ($verCotizacion)
                                                        <a href="{{ route('programas.edit', [$programa, 'tab' => 'precios']) }}"
                                                            class="btn btn-sm btn-outline-success" title="Ver métricas de rentabilidad y costos">
                                                            <i class="fas fa-chart-line me-1"></i> Métricas
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('programas.edit', [$programa, 'tab' => 'pagos']) }}"
                                                        class="btn btn-sm btn-outline-info" title="Auditoría de pagos y cuentas">
                                                        <i class="fas fa-wallet me-1"></i> Pagos
                                                    </a>
                                                    <a href="{{ route('programas.edit', [$programa, 'tab' => 'historial']) }}"
                                                        class="btn btn-sm btn-outline-secondary" title="Bitácora e historial de eventos">
                                                        <i class="fas fa-history me-1"></i> Historial
                                                    </a>
                                                @else
                                                    {{-- ACCIONES PARA PROGRAMA EN SERVICIO O PRÓXIMO --}}
                                                    @if ($rol === 'contabilidad')
                                                        {{-- Rol Contabilidad --}}
                                                        <a href="{{ route('programas.edit', [$programa, 'tab' => 'pagos']) }}"
                                                            class="btn btn-sm btn-success" title="Registrar cobro al cliente o pago a proveedor">
                                                            <i class="fas fa-dollar-sign me-1"></i> Pagos
                                                        </a>
                                                        @if ($verCotizacion)
                                                            <a href="{{ route('programas.edit', [$programa, 'tab' => 'precios']) }}"
                                                                class="btn btn-sm btn-outline-info" title="Costos y margen financiero">
                                                                <i class="fas fa-chart-line me-1"></i> Costos
                                                            </a>
                                                        @endif
                                                        <a href="{{ route('programas.edit', $programa) }}"
                                                            class="btn btn-sm btn-outline-secondary" title="Ver ficha del programa">
                                                            <i class="fas fa-eye me-1"></i> Ficha
                                                        </a>
                                                    @elseif ($rol === 'reservas')
                                                        {{-- Rol Reservas --}}
                                                        <a href="{{ route('programas.rooming', $programa) }}"
                                                            class="btn btn-sm btn-primary" title="Rooming list y reservas de hotel">
                                                            <i class="fas fa-bed me-1"></i> Rooming
                                                        </a>
                                                        <a href="{{ route('programas.edit', [$programa, 'tab' => 'tareas']) }}"
                                                            class="btn btn-sm btn-outline-warning" title="Plazos y tareas pendientes">
                                                            <i class="fas fa-tasks me-1"></i> Tareas
                                                        </a>
                                                        <a href="{{ route('programas.edit', [$programa, 'tab' => 'alojamiento']) }}"
                                                            class="btn btn-sm btn-outline-info" title="Alojamiento y hoteles">
                                                            <i class="fas fa-hotel me-1"></i> Hoteles
                                                        </a>
                                                    @else
                                                        {{-- Rol Operaciones y Administrador --}}
                                                        <a href="{{ route('operaciones.index', ['q' => $programa->codigo]) }}"
                                                            class="btn btn-sm text-white" style="background-color: #0f766e;" title="Hoja de operaciones y logística">
                                                            <i class="fas fa-clipboard-check me-1"></i> Operación
                                                        </a>
                                                        <a href="{{ route('programas.rooming', $programa) }}"
                                                            class="btn btn-sm btn-outline-primary" title="Rooming list y pasaportes">
                                                            <i class="fas fa-bed me-1"></i> Rooming
                                                        </a>
                                                        <a href="{{ route('programas.edit', [$programa, 'tab' => 'tareas']) }}"
                                                            class="btn btn-sm btn-outline-warning" title="Tareas pendientes de reserva">
                                                            <i class="fas fa-tasks me-1"></i> Tareas
                                                        </a>
                                                        @if ($canEdit)
                                                            <a href="{{ route('programas.edit', $programa) }}"
                                                                class="btn btn-sm btn-primary" title="Editar ficha completa">
                                                                <i class="fas fa-edit me-1"></i> Editar
                                                            </a>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>

                                            {{-- Menú de Más Opciones (⋮) --}}
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Más opciones">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('itinerario', $programa) }}" target="_blank">
                                                            <i class="fas fa-external-link-alt me-2 text-primary"></i> Itinerario web
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('programas.pdf', $programa) }}"
                                                            data-pdf-preview="{{ route('programas.pdf', [$programa, 'ver' => 1]) }}"
                                                            data-pdf-title="{{ $programa->nombre }}">
                                                            <i class="fas fa-file-pdf me-2 text-danger"></i> Ver PDF de lujo
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    @if ($canEdit)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('programas.duplicar', $programa) }}">
                                                                <i class="fas fa-copy me-2 text-secondary"></i> Duplicar viaje
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('programas.plantilla.store', $programa) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="fas fa-bookmark me-2 text-info"></i> Guardar como plantilla
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if ($fase !== 'finalizado' && $canEdit)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('programas.edit', [$programa, 'tab' => 'pagos']) }}">
                                                                <i class="fas fa-wallet me-2 text-success"></i> Registrar cobro / pago
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @elseif($anioId && !$mesId)
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-body text-center text-muted py-5">
                        <i class="fas fa-hand-pointer fa-3x mb-3 d-block text-primary opacity-50"></i>
                        <h5 class="fw-bold text-dark">Selecciona un mes en la cuadrícula superior</h5>
                        <p class="mb-0 text-muted">Haz clic sobre cualquier mes para consultar los viajes confirmados, en servicio o finalizados de esa temporada.</p>
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection
