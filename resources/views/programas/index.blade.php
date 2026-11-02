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
            <a href="{{ route('programas.plantillas') }}" class="btn btn-outline-secondary">
                <i class="fas fa-copy" aria-hidden="true"></i> Plantillas
            </a>
            <a href="{{ route('programas.por-periodo') }}" class="btn btn-outline-secondary">
                <i class="fas fa-calendar-alt" aria-hidden="true"></i> Por año y mes
            </a>
            @if ($canEdit)
                <a href="{{ route('programas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus" aria-hidden="true"></i> Nuevo programa
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
                                                                    <a href="{{ route('programas.edit', $programa) }}">{{ $programa->nombre }}</a>
                            </h2>
                            <p class="prog-row__meta">
                                <span title="Código"><i class="fas fa-hashtag" aria-hidden="true"></i>{{ $programa->codigo }}</span>
                                @if ($programa->lang)
                                    <span class="badge text-bg-light">{{ strtoupper($programa->lang) }}</span>
                                @endif
                                @if ($programa->agentes->isNotEmpty())
                                    <span title="Agente"><i class="fas fa-user-tie" aria-hidden="true"></i>{{ $programa->agentes->pluck('nombre')->implode(', ') }}</span>
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
                                        <li><h3 class="dropdown-header">Gestión</h3></li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('programas.edit', $programa) }}">
                                                <i class="fas fa-fw {{ $canEdit ? 'fa-pen' : 'fa-eye' }}" aria-hidden="true"></i> {{ $canEdit ? 'Editar' : 'Ver ficha' }}
                                            </a>
                                        </li>
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

                    {{-- Detalle desplegable --}}
                    <div class="collapse" id="prog-detail-{{ $programa->id }}">
                        <div class="prog-detail">
                            <section class="prog-detail__col">
                                <h3 class="prog-detail__title"><i class="fas fa-users" aria-hidden="true"></i> Pasajeros</h3>
                                @forelse ($programa->paxs as $pax)
                                    <div class="prog-detail__line">
                                        <strong>{{ $pax->nombre }}</strong>
                                        <span>{{ $pax->edad }} años · {{ $pax->nacionalidad }}@if ($pax->alimentacion) · <i class="fas fa-utensils" aria-hidden="true"></i> {{ $pax->alimentacion }}@endif</span>
                                    </div>
                                @empty
                                    <p class="prog-detail__empty">Sin pasajeros registrados.</p>
                                @endforelse
                            </section>

                            <section class="prog-detail__col">
                                <h3 class="prog-detail__title"><i class="fas fa-hotel" aria-hidden="true"></i> Hoteles</h3>
                                @forelse ($hoteles as ['hotel' => $hotel, 'estadias' => $estadias])
                                    <div class="prog-detail__line">
                                        <strong>{{ $hotel->nombre }}</strong>
                                        @foreach ($estadias as $hf)
                                            <span>
                                                {{ $hf->etiqueta }} ·
                                                @if ($hf->fecha_ingreso)
                                                    {{ \Carbon\Carbon::parse($hf->fecha_ingreso)->format('d/m') }} → {{ \Carbon\Carbon::parse($hf->fecha_salida)->format('d/m/Y') }}
                                                @else
                                                    <span class="text-warning">sin fechas</span>
                                                @endif
                                            </span>
                                        @endforeach
                                    </div>
                                @empty
                                    <p class="prog-detail__empty">Sin hoteles asignados.</p>
                                @endforelse

                                @php $op = $programa->operacion(); @endphp
                                <h3 class="prog-detail__title mt-3"><i class="fas fa-route" aria-hidden="true"></i> Operación</h3>
                                @if ($op['servicios'] || $op['tareasPendientes'])
                                    <div class="prog-detail__line">
                                        <strong>Servicios</strong>
                                        <span>{{ $op['confirmados'] }} de {{ $op['servicios'] }} confirmados
                                            @if ($op['abiertos'])<span class="svc-badge svc-badge--pendiente ms-1">{{ $op['abiertos'] }} por confirmar</span>@endif
                                        </span>
                                        <span>{{ $programa->servicios->pluck('proveedor.nombre')->filter()->unique()->implode(' · ') ?: 'Sin proveedores asignados' }}</span>
                                    </div>
                                    <div class="prog-detail__line">
                                        <strong>Tareas</strong>
                                        <span>{{ $op['tareasPendientes'] }} pendientes
                                            @if ($op['tareasVencidas'])<span class="svc-badge svc-badge--pendiente ms-1">{{ $op['tareasVencidas'] }} vencidas</span>@endif
                                        </span>
                                    </div>
                                @else
                                    <p class="prog-detail__empty">Sin servicios ni tareas cargados.</p>
                                @endif
                            </section>

                            <section class="prog-detail__col">
                                <h3 class="prog-detail__title"><i class="fas fa-coins" aria-hidden="true"></i> Presupuesto</h3>
                                @if ($p['subtotal'] > 0)
                                    <dl class="prog-budget">
                                        @if ($p['adultos'])
                                            <dt>Adultos ({{ $p['adultos'] }} × {{ $money($verCotizacion ? $programa->precioAdulto : $p['ventaAdulto']) }})</dt>
                                            <dd>{{ $money($verCotizacion ? $p['subtotalAdultos'] : $p['ventaAdulto'] * $p['adultos']) }}</dd>
                                        @endif
                                        @if ($p['ninos'])
                                            <dt>Niños 5-11 ({{ $p['ninos'] }} × {{ $money($verCotizacion ? $programa->precioChild : $p['ventaNino']) }})</dt>
                                            <dd>{{ $money($verCotizacion ? $p['subtotalNinos'] : $p['ventaNino'] * $p['ninos']) }}</dd>
                                        @endif
                                        @if ($p['infantes'])
                                            <dt>Infantes ({{ $p['infantes'] }})</dt>
                                            <dd>Gratis</dd>
                                        @endif
                                        @if ($verCotizacion && $p['pctGanancia'] > 0)
                                            <dt class="prog-budget__sep">Subtotal</dt>
                                            <dd class="prog-budget__sep">{{ $money($p['subtotal']) }}</dd>
                                            <dt>Ganancia ({{ rtrim(rtrim(number_format($p['pctGanancia'], 2), '0'), '.') }}%)</dt>
                                            <dd class="text-success">+{{ $money($p['ganancia']) }}</dd>
                                        @endif
                                        <dt class="prog-budget__total">Total</dt>
                                        <dd class="prog-budget__total">{{ $money($p['total']) }}</dd>
                                    </dl>
                                @else
                                    <p class="prog-detail__empty">Sin tarifas cargadas.</p>
                                @endif
                                @if ($cobro['total'] > 0)
                                    <dl class="prog-budget mt-3">
                                        <dt>Pagado ({{ $cobro['porcentaje'] }}%)</dt>
                                        <dd class="text-success">{{ $money($cobro['pagado']) }}</dd>
                                        <dt>Saldo{{ $cobro['vencimientoSaldo'] && $cobro['saldo'] > 0 ? ' · vence ' . $cobro['vencimientoSaldo']->locale('es')->isoFormat('D MMM') : '' }}</dt>
                                        <dd class="{{ $cobro['saldoVencido'] ? 'text-danger' : '' }}">{{ $money($cobro['saldo']) }}</dd>
                                    </dl>
                                @endif
                                @if ($programa->email)
                                    <p class="prog-detail__contact"><i class="fas fa-envelope" aria-hidden="true"></i> {{ $programa->email }}</p>
                                @endif
                            </section>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="prog-pagination">
            {{ $programas->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endsection
