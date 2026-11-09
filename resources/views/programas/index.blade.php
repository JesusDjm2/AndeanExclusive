{{--
    Listado de programas (ProgramaController@index).
    Fila compacta + detalle desplegable + menú de acciones (⋮). Estilos: resources/sass/admin/_programas-list.scss
--}}
@extends('layouts.app')
@section('titulo', 'Programas')

@php
    $canEdit = auth()->user()?->can('programas.gestionar');
    $verCotizacion = auth()->user()?->can('cotizacion.ver');
    $idiomas = ['es' => 'Español', 'en' => 'Inglés', 'pt' => 'Portugués', 'fr' => 'Francés'];
    $filtrosActivos = collect(request()->only(['q', 'anio_id', 'mes_id', 'comercial']))->filter()->isNotEmpty();
    $money = fn ($n) => '$' . number_format($n, 2);
@endphp

@section('contenido')
    <div class="ae-admin-page-header d-flex flex-wrap align-items-end justify-content-between gap-3">
        <div>
            <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-suitcase-rolling"></i> Programas</h1>
            <small class="ae-admin-page-desc">Itinerarios de clientes: fechas, pasajeros, hoteles, servicios y presupuesto.</small>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('programas.por-periodo') }}" class="btn btn-outline-primary" title="Ver viajes segmentados por calendario (año y mes)">
                <i class="fas fa-calendar-alt me-1" aria-hidden="true"></i> Vista por Año y Mes
            </a>
            <a href="{{ route('programas.plantillas') }}" class="btn btn-outline-secondary" title="Plantillas maestras reutilizables">
                <i class="fas fa-copy me-1" aria-hidden="true"></i> Plantillas
            </a>
            @if ($canEdit)
                <a href="{{ route('programas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1" aria-hidden="true"></i> Nuevo programa
                </a>
            @endif
        </div>
    </div>

    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
        @if (session($key))
            <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
    @endforeach

    {{-- Pestañas por estado + búsqueda y filtros --}}
    <div class="card prog-toolbar">
        <nav class="prog-tabs" aria-label="Filtrar por estado">
            @foreach ($estados as $key => $label)
                <a href="{{ route('programas.index', array_merge(request()->only(['q', 'anio_id', 'mes_id', 'comercial']), ['estado' => $key])) }}"
                    class="prog-tabs__tab {{ $estado === $key ? 'is-active' : '' }}" @if ($estado === $key) aria-current="page" @endif>
                    {{ $label }} <span class="prog-tabs__count">{{ $counts[$key] }}</span>
                </a>
            @endforeach
        </nav>
        <form method="GET" action="{{ route('programas.index') }}" class="prog-filters">
            <input type="hidden" name="estado" value="{{ $estado }}">
            <div class="prog-filters__search">
                <label for="prog-q" class="visually-hidden">Buscar</label>
                <i class="fas fa-search" aria-hidden="true"></i>
                <input id="prog-q" type="search" name="q" value="{{ request('q') }}" class="form-control"
                    placeholder="Buscar por programa, código, correo o pasajero">
            </div>
            <div>
                <label for="prog-anio" class="visually-hidden">Año</label>
                <select id="prog-anio" name="anio_id" class="form-select">
                    <option value="">Todos los años</option>
                    @foreach ($anios as $anio)
                        <option value="{{ $anio->id }}" @selected((string) request('anio_id') === (string) $anio->id)>{{ $anio->anio }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="prog-mes" class="visually-hidden">Mes</label>
                <select id="prog-mes" name="mes_id" class="form-select">
                    <option value="">Todos los meses</option>
                    @foreach ($meses as $mes)
                        <option value="{{ $mes->id }}" @selected((string) request('mes_id') === (string) $mes->id)>{{ $mes->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="prog-comercial" class="visually-hidden">Estado comercial</label>
                <select id="prog-comercial" name="comercial" class="form-select">
                    <option value="">Cualquier estado</option>
                    @foreach ($comerciales as $value => $label)
                        <option value="{{ $value }}" @selected(request('comercial') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="prog-filters__actions">
                <button type="submit" class="btn btn-primary">Buscar</button>
                @if ($filtrosActivos)
                    <a href="{{ route('programas.index', ['estado' => $estado]) }}" class="btn btn-link">Limpiar</a>
                @endif
            </div>
        </form>
    </div>

    @if ($programas->isEmpty())
        <div class="card">
            <div class="admin-empty">
                <i class="fas fa-suitcase" aria-hidden="true"></i>
                @if ($filtrosActivos)
                    No hay programas que coincidan con la búsqueda.
                    <div class="mt-2"><a href="{{ route('programas.index', ['estado' => $estado]) }}">Limpiar filtros</a></div>
                @else
                    No hay programas en «{{ $estados[$estado] }}».
                @endif
            </div>
        </div>
    @else
        <p class="prog-results">
            Mostrando {{ $programas->firstItem() }}–{{ $programas->lastItem() }} de {{ $programas->total() }} programas
        </p>

        <ul class="prog-list">
            @foreach ($programas as $programa)
                @php
                    $estadoViaje = $programa->faseViaje();
                    $dias = $programa->diasParaInicio();
                    $duracion = $programa->duracionDias();
                    $p = $programa->presupuesto();
                    $cobro = $programa->cobranza();
                    $inicio = $programa->inicio ? \Carbon\Carbon::parse($programa->inicio) : null;
                    $fin = $programa->fin ? \Carbon\Carbon::parse($programa->fin) : null;
                    $badge = match ($estadoViaje) {
                        'en_curso' => ['live', 'En curso'],
                        'finalizado' => ['done', 'Finalizado'],
                        'sin_fechas' => ['none', 'Sin fechas'],
                        default => [$dias <= 7 ? 'soon' : 'next', $dias === 0 ? 'Hoy' : ($dias === 1 ? 'Mañana' : 'En ' . $dias . ' días')],
                    };
                    $clientUrl = route('itinerario', $programa);
                    $emailValido = $programa->email && filter_var(trim($programa->email), FILTER_VALIDATE_EMAIL);
                    $hoteles = $programa->alojamiento();
                @endphp
                <li class="prog-item">
                    <div class="prog-row">
                        <div class="prog-row__date prog-row__date--{{ $badge[0] }}" aria-hidden="true">
                            @if ($inicio)
                                <span class="prog-row__day">{{ $inicio->format('d') }}</span>
                                <span class="prog-row__month">{{ $inicio->locale('es')->isoFormat('MMM YY') }}</span>
                            @else
                                <i class="far fa-calendar-times"></i>
                            @endif
                        </div>

                        <div class="prog-row__main">
                            <h2 class="prog-row__name">
                                <a href="{{ route('programas.ficha', $programa) }}" title="Ver ficha operativa de {{ $programa->nombre }}">{{ $programa->nombre }}</a>
                            </h2>
                            <p class="prog-row__meta">
                                <span title="Código"><i class="fas fa-hashtag" aria-hidden="true"></i>{{ $programa->codigo }}</span>
                                @if ($programa->lang)
                                    <span class="badge text-bg-light">{{ strtoupper($programa->lang) }}</span>
                                @endif
                                @if ($programa->agentes->isNotEmpty())
                                    <span title="Agente"><i class="fas fa-user-tie" aria-hidden="true"></i>{{ $programa->agentes->pluck('nombre')->implode(', ') }}</span>
                                @endif
                                @if ($programa->tieneVuelos())
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" title="Llegada: {{ $programa->llegada_vuelo ?: 'Sí' }} · Salida: {{ $programa->salida_vuelo ?: 'Sí' }}"><i class="fas fa-plane me-1"></i>Vuelos OK</span>
                                @elseif ($inicio && $inicio->isFuture() && $dias <= 30)
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" title="Sin vuelos registrados"><i class="fas fa-plane-slash me-1"></i>Sin vuelos</span>
                                @endif
                                @php $tRes = $programa->tareasResumen(); @endphp
                                @if ($tRes['vencidas'] > 0)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle" title="{{ $tRes['vencidas'] }} tareas vencidas"><i class="fas fa-exclamation-triangle me-1"></i>{{ $tRes['vencidas'] }} tar. vencidas</span>
                                @elseif ($tRes['pendientes'] > 0)
                                    <span class="badge bg-info-subtle text-info border border-info-subtle" title="{{ $tRes['pendientes'] }} tareas pendientes"><i class="fas fa-tasks me-1"></i>{{ $tRes['pendientes'] }} tareas</span>
                                @endif
                            </p>
                        </div>

                        <div class="prog-row__cells">
                            <div class="prog-row__cell prog-row__dates">
                                <span class="prog-row__label">Fechas</span>
                                @if ($inicio && $fin)
                                    <span class="prog-row__value">{{ $inicio->locale('es')->isoFormat('D MMM') }} – {{ $fin->locale('es')->isoFormat('D MMM YYYY') }}</span>
                                    <span class="prog-row__sub">{{ $duracion }} {{ $duracion === 1 ? 'día' : 'días' }}</span>
                                @else
                                    <span class="prog-row__value text-muted">Por definir</span>
                                @endif
                            </div>

                            <div class="prog-row__cell">
                                <span class="prog-row__label">Estado</span>
                                <span class="prog-badge prog-badge--{{ $badge[0] }}">{{ $badge[1] }}</span>
                                <span class="prog-row__sub pf-status-text pf-status-text--{{ $programa->estado }}">{{ $comerciales[$programa->estado] ?? $programa->estado }}</span>
                            </div>

                            <div class="prog-row__cell">
                                <span class="prog-row__label">Pasajeros</span>
                                <span class="prog-row__value">{{ $programa->paxs->count() }} pax</span>
                                @if ($programa->paxs->isNotEmpty())
                                    <span class="prog-row__sub">{{ $p['adultos'] }} ad.{{ $p['ninos'] ? ' · ' . $p['ninos'] . ' niñ.' : '' }}{{ $p['infantes'] ? ' · ' . $p['infantes'] . ' inf.' : '' }}</span>
                                @endif
                            </div>

                            <div class="prog-row__cell prog-row__total">
                                <span class="prog-row__label">Total</span>
                                @if ($p['total'] > 0)
                                    <span class="prog-row__value">{{ $money($p['total']) }}</span>
                                    @if ($cobro['estado'] === 'pagado')
                                        <span class="prog-row__sub text-success"><i class="fas fa-check-circle" aria-hidden="true"></i> Pagado</span>
                                    @elseif ($cobro['saldoVencido'])
                                        <span class="prog-row__sub text-danger"><i class="fas fa-exclamation-circle" aria-hidden="true"></i> Saldo vencido</span>
                                    @else
                                        <span class="prog-row__sub">Pagado {{ $cobro['porcentaje'] }}%</span>
                                    @endif
                                @else
                                    <span class="prog-row__value text-muted">Sin tarifa</span>
                                @endif
                            </div>
                        </div>

                        <div class="prog-row__actions">
                            <button class="btn btn-sm btn-outline-secondary prog-row__toggle collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#prog-detail-{{ $programa->id }}"
                                aria-expanded="false" aria-controls="prog-detail-{{ $programa->id }}">
                                <span class="d-none d-xl-inline">Detalle</span>
                                <i class="fas fa-chevron-down" aria-hidden="true"></i>
                                <span class="visually-hidden">Ver detalle de {{ $programa->nombre }}</span>
                            </button>

                            <div class="dropdown">
                                <button class="btn btn-sm btn-light prog-row__kebab" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false" aria-label="Acciones de {{ $programa->nombre }}">
                                    <i class="fas fa-ellipsis-v" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li><h3 class="dropdown-header">Cliente</h3></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ $clientUrl }}" target="_blank" rel="noopener">
                                            <i class="fas fa-fw fa-eye" aria-hidden="true"></i> Ver itinerario
                                        </a>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item" data-copy="{{ $clientUrl }}">
                                            <i class="fas fa-fw fa-link" aria-hidden="true"></i> Copiar enlace
                                        </button>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('programas.pdf', $programa) }}" data-pdf-preview="{{ route('programas.pdf', [$programa, 'ver' => 1]) }}" data-pdf-title="{{ $programa->nombre }}">
                                            <i class="fas fa-fw fa-file-pdf" aria-hidden="true"></i> Ver PDF
                                        </a>
                                    </li>
                                    @if ($canEdit)
                                        <li>
                                            @if ($emailValido)
                                                <form action="{{ route('programas.enviar-correo', $programa) }}" method="POST"
                                                    data-confirm="Se enviará el programa en PDF a {{ $programa->email }}."
                                                    data-confirm-title="¿Enviar por correo?" data-confirm-button="Sí, enviar">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-fw fa-paper-plane" aria-hidden="true"></i> Enviar por correo
                                                    </button>
                                                </form>
                                            @else
                                                <span class="dropdown-item disabled" aria-disabled="true">
                                                    <i class="fas fa-fw fa-paper-plane" aria-hidden="true"></i> Enviar por correo
                                                    <small class="d-block text-muted">Falta un correo válido</small>
                                                </span>
                                            @endif
                                        </li>
                                    @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li><h3 class="dropdown-header">Gestión Operativa</h3></li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('programas.ficha', $programa) }}">
                                                <i class="fas fa-fw fa-id-card text-primary" aria-hidden="true"></i> Ficha operativa
                                            </a>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item"
                                                data-historial-modal
                                                data-programa-nombre="{{ $programa->nombre }}"
                                                data-programa-codigo="{{ $programa->codigo }}"
                                                data-historial-url="{{ route('programas.historial', $programa) }}">
                                                <i class="fas fa-fw fa-history text-secondary" aria-hidden="true"></i> Historial de movimientos
                                            </button>
                                        </li>
                                        @if ($canEdit)
                                            <li>
                                                <a class="dropdown-item" href="{{ route('programas.edit', $programa) }}">
                                                    <i class="fas fa-fw fa-pencil-alt text-muted" aria-hidden="true"></i> Editar datos
                                                </a>
                                            </li>
                                        @endif
                                        @if ($canEdit)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('programas.duplicar', $programa) }}">
                                                <i class="fas fa-fw fa-copy" aria-hidden="true"></i> Duplicar
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('programas.plantilla.store', $programa) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item"><i class="fas fa-fw fa-bookmark" aria-hidden="true"></i> Guardar como plantilla</button>
                                            </form>
                                        </li>
                                        @endif
                                        @can('cobros.gestionar')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('programas.edit', [$programa, 'tab' => 'pagos']) }}">
                                                <i class="fas fa-fw fa-wallet" aria-hidden="true"></i> Registrar pago
                                            </a>
                                        </li>
                                        @endcan
                                        @can('programas.eliminar')
                                        <li>
                                            <form action="{{ route('programas.destroy', $programa) }}" method="POST"
                                                data-confirm="Se eliminará «{{ $programa->nombre }}» con sus pasajeros. Esta acción no se puede deshacer."
                                                data-confirm-title="¿Eliminar programa?" data-confirm-button="Sí, eliminar" data-confirm-danger>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-fw fa-trash-alt" aria-hidden="true"></i> Eliminar
                                                </button>
                                            </form>
                                        </li>
                                        @endcan
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Detalle desplegable: lo que falta para que el viaje salga bien y el viaje de un vistazo.
                         El desglose de precios y los datos de cada pasajero están en la ficha operativa. --}}
                    @php
                        $op = $programa->operacion();
                        $porConfirmar = $programa->servicios->filter(fn ($s) => in_array($s->estado, \App\Models\Servicio::ABIERTOS, true))->sortBy('fecha')->values();
                        $pctServicios = $op['servicios'] ? (int) round($op['confirmados'] / $op['servicios'] * 100) : 0;
                        $estadoServicios = ! $op['servicios'] ? 'muted' : ($op['abiertos'] ? 'warn' : 'ok');
                        $estadoCobro = $cobro['total'] <= 0 ? 'muted' : ($cobro['estado'] === 'pagado' ? 'ok' : ($cobro['saldoVencido'] ? 'danger' : 'warn'));
                        $estadoTareas = $op['tareasVencidas'] ? 'danger' : ($op['tareasPendientes'] ? 'warn' : 'ok');
                        $iconos = ['ok' => 'fa-check-circle', 'warn' => 'fa-clock', 'danger' => 'fa-exclamation-circle', 'muted' => 'fa-minus-circle'];
                        $conDieta = $programa->paxs->filter(fn ($pax) => filled($pax->alimentacion))->count();
                    @endphp
                    <div class="collapse" id="prog-detail-{{ $programa->id }}">
                        <div class="prog-detail">
                            <section class="prog-detail__pendientes" aria-labelledby="prog-pend-{{ $programa->id }}">
                                <h3 class="prog-detail__title" id="prog-pend-{{ $programa->id }}"><i class="fas fa-clipboard-check" aria-hidden="true"></i> Pendientes</h3>
                                <ul class="prog-check">
                                    <li class="prog-check__item prog-check__item--{{ $estadoServicios }}">
                                        <i class="prog-check__icon fas {{ $iconos[$estadoServicios] }}" aria-hidden="true"></i>
                                        <div class="prog-check__body">
                                            <div class="prog-check__head">
                                                <strong>Servicios</strong>
                                                <span>{{ $op['servicios'] ? $op['confirmados'] . ' de ' . $op['servicios'] . ' confirmados' : 'Sin servicios cargados' }}</span>
                                            </div>
                                            @if ($op['servicios'])
                                                <div class="prog-meter" role="progressbar" aria-label="Servicios confirmados" aria-valuenow="{{ $pctServicios }}" aria-valuemin="0" aria-valuemax="100"><span style="width: {{ $pctServicios }}%"></span></div>
                                            @endif
                                            @if ($porConfirmar->isNotEmpty())
                                                <p class="prog-check__detail">
                                                    Por confirmar:
                                                    {{ $porConfirmar->take(3)->map(fn ($s) => ($s->titulo ?: $s->tipo_etiqueta) . ' (' . ($s->proveedor?->nombre ?? 'sin proveedor') . ')')->implode(' · ') }}@if ($porConfirmar->count() > 3) y {{ $porConfirmar->count() - 3 }} más @endif
                                                </p>
                                            @endif
                                        </div>
                                        @if ($op['abiertos'])
                                            <a class="prog-check__action" href="{{ route('reservas.index', ['q' => $programa->codigo, 'periodo' => 'todos']) }}">Ir a Reservas</a>
                                        @endif
                                    </li>

                                    <li class="prog-check__item prog-check__item--{{ $estadoCobro }}">
                                        <i class="prog-check__icon fas {{ $iconos[$estadoCobro] }}" aria-hidden="true"></i>
                                        <div class="prog-check__body">
                                            <div class="prog-check__head">
                                                <strong>Cobro</strong>
                                                <span>
                                                    @if ($cobro['total'] <= 0)
                                                        Sin precio de venta
                                                    @elseif ($cobro['estado'] === 'pagado')
                                                        Pagado completo
                                                    @else
                                                        {{ $cobro['porcentaje'] }}% pagado
                                                    @endif
                                                </span>
                                            </div>
                                            @if ($cobro['total'] > 0)
                                                <div class="prog-meter" role="progressbar" aria-label="Porcentaje cobrado" aria-valuenow="{{ $cobro['porcentaje'] }}" aria-valuemin="0" aria-valuemax="100"><span style="width: {{ min(100, $cobro['porcentaje']) }}%"></span></div>
                                                @if ($cobro['saldo'] > 0)
                                                    <p class="prog-check__detail">
                                                        Saldo <strong>{{ $money($cobro['saldo']) }}</strong>
                                                        @if ($cobro['vencimientoSaldo'])· {{ $cobro['saldoVencido'] ? 'venció' : 'vence' }} el {{ $cobro['vencimientoSaldo']->locale('es')->isoFormat('D [de] MMMM') }}@endif
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                        @can('cobros.gestionar')
                                            @if ($cobro['saldo'] > 0)
                                                <a class="prog-check__action" href="{{ route('programas.edit', [$programa, 'tab' => 'pagos']) }}">Registrar pago</a>
                                            @endif
                                        @endcan
                                    </li>

                                    @php $estadoVuelos = $programa->tieneVuelos() ? 'ok' : 'warn'; @endphp
                                    <li class="prog-check__item prog-check__item--{{ $estadoVuelos }}">
                                        <i class="prog-check__icon fas {{ $programa->tieneVuelos() ? 'fa-plane' : 'fa-plane-slash' }}" aria-hidden="true"></i>
                                        <div class="prog-check__body">
                                            <div class="prog-check__head">
                                                <strong>Vuelos</strong>
                                                <span>
                                                    @if ($programa->tieneVuelos())
                                                        Llegada {{ $programa->llegada_vuelo ?: 'registrada' }} · Salida {{ $programa->salida_vuelo ?: 'registrada' }}
                                                    @else
                                                        Faltan los vuelos de llegada y salida
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        @if ($canEdit && ! $programa->tieneVuelos())
                                            <a class="prog-check__action" href="{{ route('programas.edit', [$programa, 'tab' => 'datos']) }}">Cargar vuelos</a>
                                        @endif
                                    </li>

                                    <li class="prog-check__item prog-check__item--{{ $estadoTareas }}">
                                        <i class="prog-check__icon fas {{ $iconos[$estadoTareas] }}" aria-hidden="true"></i>
                                        <div class="prog-check__body">
                                            <div class="prog-check__head">
                                                <strong>Tareas</strong>
                                                <span>
                                                    @if ($op['tareasVencidas'])
                                                        {{ $op['tareasVencidas'] }} {{ $op['tareasVencidas'] === 1 ? 'vencida' : 'vencidas' }} de {{ $op['tareasPendientes'] }} pendientes
                                                    @elseif ($op['tareasPendientes'])
                                                        {{ $op['tareasPendientes'] }} {{ $op['tareasPendientes'] === 1 ? 'pendiente' : 'pendientes' }}, ninguna vencida
                                                    @else
                                                        Al día
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        @if ($op['tareasPendientes'])
                                            <a class="prog-check__action" href="{{ route('programas.edit', [$programa, 'tab' => 'tareas']) }}">Ver tareas</a>
                                        @endif
                                    </li>
                                </ul>
                            </section>

                            <section class="prog-detail__viaje" aria-labelledby="prog-viaje-{{ $programa->id }}">
                                <h3 class="prog-detail__title" id="prog-viaje-{{ $programa->id }}"><i class="fas fa-suitcase-rolling" aria-hidden="true"></i> El viaje</h3>

                                <span class="prog-trip__label">Hoteles</span>
                                @if ($hoteles->isNotEmpty())
                                    <ol class="prog-stays">
                                        @foreach ($hoteles as ['hotel' => $hotel, 'estadias' => $estadias])
                                            @foreach ($estadias as $hf)
                                                @php
                                                    $entrada = $hf->fecha_ingreso ? \Carbon\Carbon::parse($hf->fecha_ingreso) : null;
                                                    $salida = $hf->fecha_salida ? \Carbon\Carbon::parse($hf->fecha_salida) : null;
                                                    $noches = $entrada && $salida ? (int) $entrada->diffInDays($salida) : null;
                                                @endphp
                                                <li>
                                                    <strong>{{ $hotel->nombre }}</strong>
                                                    <span>
                                                        @if ($noches)
                                                            {{ $noches }} {{ $noches === 1 ? 'noche' : 'noches' }} · {{ $entrada->format('d/m') }} → {{ $salida->format('d/m') }}
                                                        @else
                                                            <span class="text-warning">Sin fechas</span>
                                                        @endif
                                                    </span>
                                                </li>
                                            @endforeach
                                        @endforeach
                                    </ol>
                                @else
                                    <p class="prog-detail__empty">Sin hoteles asignados.</p>
                                @endif

                                <span class="prog-trip__label">Pasajeros</span>
                                @if ($programa->paxs->isNotEmpty())
                                    <p class="prog-trip__text">
                                        {{ $programa->paxs->pluck('nombre')->implode(', ') }}
                                        @if ($conDieta)<span class="prog-trip__note"><i class="fas fa-utensils" aria-hidden="true"></i> {{ $conDieta }} con dieta especial</span>@endif
                                    </p>
                                @else
                                    <p class="prog-detail__empty">Sin pasajeros registrados.</p>
                                @endif

                                @if ($programa->email)
                                    <span class="prog-trip__label">Contacto</span>
                                    <p class="prog-trip__text"><a href="mailto:{{ $programa->email }}">{{ $programa->email }}</a></p>
                                @endif
                            </section>

                            <div class="prog-detail__footer">
                                <button type="button" class="btn btn-sm btn-link prog-detail__link"
                                    data-historial-modal
                                    data-programa-nombre="{{ $programa->nombre }}"
                                    data-programa-codigo="{{ $programa->codigo }}"
                                    data-historial-url="{{ route('programas.historial', $programa) }}">
                                    <i class="fas fa-history me-1" aria-hidden="true"></i> Historial
                                </button>
                                <div class="d-flex gap-2">
                                    @if ($canEdit)
                                        <a href="{{ route('programas.edit', $programa) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-pencil-alt me-1" aria-hidden="true"></i> Editar
                                        </a>
                                    @endif
                                    <a href="{{ route('programas.ficha', $programa) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-id-card me-1" aria-hidden="true"></i> Abrir ficha operativa
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="prog-pagination">
            {{ $programas->links('pagination::bootstrap-5') }}
        </div>
    @endif

    {{-- Modal Reutilizable de Historial de Movimientos --}}
    @include('programas.partials.modal-historial')
@endsection
