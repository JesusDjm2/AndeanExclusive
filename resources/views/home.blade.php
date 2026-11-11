{{-- Dashboard del panel (HomeController@index). Estilos: resources/sass/admin/_dashboard.scss --}}
@extends('layouts.app')
@section('titulo', 'Inicio')

@php
    $user = auth()->user();
    $canEdit = $user->can('programas.gestionar');
    $isAdmin = $user->hasRole('administrador');
    $isBranding = $user->hasRole('branding');
    $maxMonth = max(1, $months->max('count') ?? 1);
    $range = function ($programa) {
        $inicio = \Carbon\Carbon::parse($programa->inicio);
        $fin = \Carbon\Carbon::parse($programa->fin);

        return $inicio->isSameMonth($fin)
            ? $inicio->format('d') . '–' . $fin->locale('es')->isoFormat('D MMM YYYY')
            : $inicio->locale('es')->isoFormat('D MMM') . ' – ' . $fin->locale('es')->isoFormat('D MMM YYYY');
    };
@endphp

@section('contenido')
    {{-- Bienvenida + acciones rápidas --}}
    <div class="dash-hero">
        <div>
            <p class="dash-hero__date">{{ ucfirst($today->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY')) }}</p>
            <h1 class="dash-hero__title">Hola, {{ $firstName ?? $user->name }}</h1>
            @if ($isBranding)
                <p class="dash-hero__lead">Panel de gestión de marca, contenidos web, tours, blogs y galería de medios.</p>
            @else
                <p class="dash-hero__lead">Este es el resumen de la operación y del sitio web.</p>
            @endif
        </div>
        @if ($isBranding)
            <div class="dash-hero__actions">
                <a href="{{ route('tours.create') }}" class="btn btn-primary"><i class="fas fa-plus" aria-hidden="true"></i> Nuevo Tour EN</a>
                <a href="{{ route('estours.create') }}" class="btn btn-outline-primary"><i class="fas fa-plus" aria-hidden="true"></i> Nuevo Tour ES</a>
                <a href="{{ route('enblogs.create') }}" class="btn btn-outline-secondary"><i class="fas fa-pen-nib" aria-hidden="true"></i> Nuevo Blog</a>
                <a href="{{ route('imagenes.create') }}" class="btn btn-outline-secondary"><i class="fas fa-image" aria-hidden="true"></i> Subir Imagen</a>
            </div>
        @elseif ($canEdit)
            <div class="dash-hero__actions">
                <a href="{{ route('programas.create') }}" class="btn btn-primary"><i class="fas fa-plus" aria-hidden="true"></i> Nuevo programa</a>
                <a href="{{ route('paxs.create') }}" class="btn btn-outline-primary"><i class="fas fa-user-plus" aria-hidden="true"></i> Nuevo pasajero</a>
            </div>
        @endif
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <div class="ayuda-bienvenida" data-ayuda-bienvenida hidden>
        <p><strong>¿Primera vez aquí?</strong> En cada pantalla, el botón <strong>?</strong> de arriba explica lo que estás viendo y cómo hacerlo.</p>
        <div class="ayuda-bienvenida__acciones">
            <a href="{{ route('ayuda.index') }}" class="btn btn-sm btn-primary">Ver la ayuda</a>
            <button type="button" class="btn btn-sm btn-link" data-ayuda-bienvenida-cerrar>Ya entendí</button>
        </div>
    </div>

    {{-- Indicadores principales --}}
    @if ($isBranding)
        <div class="dash-kpis">
            <article class="kpi">
                <span class="kpi__icon kpi__icon--live"><i class="fas fa-map-marked-alt" aria-hidden="true"></i></span>
                <div>
                    <p class="kpi__label">Tours Publicados</p>
                    <p class="kpi__value">{{ $content['toursEn'] + $content['toursEs'] }}</p>
                    <p class="kpi__meta">{{ $content['toursEn'] }} EN · {{ $content['toursEs'] }} ES</p>
                </div>
            </article>
            <article class="kpi">
                <span class="kpi__icon"><i class="fas fa-newspaper" aria-hidden="true"></i></span>
                <div>
                    <p class="kpi__label">Artículos de Blog</p>
                    <p class="kpi__value">{{ $content['blogsEn'] + $content['blogsEs'] }}</p>
                    <p class="kpi__meta">{{ $content['blogsEn'] }} EN · {{ $content['blogsEs'] }} ES</p>
                </div>
            </article>
            <article class="kpi">
                <span class="kpi__icon"><i class="fas fa-images" aria-hidden="true"></i></span>
                <div>
                    <p class="kpi__label">Galería de Medios</p>
                    <p class="kpi__value">{{ $content['imagenes'] }}</p>
                    <p class="kpi__meta">imágenes optimizadas</p>
                </div>
            </article>
            <article class="kpi">
                <span class="kpi__icon {{ ($content['toursMissingEs'] + $content['blogsMissingEs'] > 0) ? 'text-warning' : 'text-success' }}"><i class="fas fa-language" aria-hidden="true"></i></span>
                <div>
                    <p class="kpi__label">Traducciones Pendientes</p>
                    <p class="kpi__value">{{ $content['toursMissingEs'] + $content['blogsMissingEs'] }}</p>
                    <p class="kpi__meta">{{ $content['toursMissingEs'] }} tours · {{ $content['blogsMissingEs'] }} blogs</p>
                </div>
            </article>
        </div>
    @else
        <div class="dash-kpis">
            <article class="kpi">
                <span class="kpi__icon kpi__icon--live"><i class="fas fa-plane-departure" aria-hidden="true"></i></span>
                <div>
                    <p class="kpi__label">En viaje ahora</p>
                    <p class="kpi__value">{{ $stats['ongoing'] }}</p>
                    <p class="kpi__meta">{{ $stats['travelersOnTrip'] }} {{ $stats['travelersOnTrip'] === 1 ? 'viajero' : 'viajeros' }} en ruta</p>
                </div>
            </article>
            <article class="kpi">
                <span class="kpi__icon"><i class="fas fa-calendar-check" aria-hidden="true"></i></span>
                <div>
                    <p class="kpi__label">Salen en 30 días</p>
                    <p class="kpi__value">{{ $stats['next30'] }}</p>
                    <p class="kpi__meta">programas por preparar</p>
                </div>
            </article>
            <article class="kpi">
                <span class="kpi__icon"><i class="fas fa-calendar-alt" aria-hidden="true"></i></span>
                <div>
                    <p class="kpi__label">Inician este mes</p>
                    <p class="kpi__value">{{ $stats['thisMonth'] }}</p>
                    <p class="kpi__meta">{{ ucfirst($today->locale('es')->isoFormat('MMMM')) }}</p>
                </div>
            </article>
            <article class="kpi">
                <span class="kpi__icon"><i class="fas fa-suitcase-rolling" aria-hidden="true"></i></span>
                <div>
                    <p class="kpi__label">Programas {{ $today->year }}</p>
                    <p class="kpi__value">{{ $stats['year'] }}</p>
                    <p class="kpi__meta">{{ $stats['paxYear'] }} pasajeros en el año</p>
                </div>
            </article>
        </div>
    @endif

    @if ($isBranding)
        {{-- Panel exclusivo de Branding y Contenidos Públicos --}}
        <div class="row g-4 mb-4">
            {{-- Tours & Categorías --}}
            <div class="col-lg-6">
                <section class="card dash-card h-100" aria-labelledby="branding-tours">
                    <div class="card-header dash-card__header">
                        <h2 id="branding-tours" class="dash-card__title">
                            <i class="fas fa-map-marked-alt text-primary me-2"></i> Gestión de Tours & Itinerarios
                        </h2>
                        <a href="{{ route('tours.index') }}" class="btn btn-sm btn-link">Ver catálogo <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Administra paquetes, itinerarios detallados, precios referenciales y versiones multilingües (Inglés y Español).</p>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <a href="{{ route('tours.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-globe me-1"></i> Tours en Inglés ({{ $content['toursEn'] }})
                            </a>
                            <a href="{{ route('estours.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-language me-1"></i> Tours en Español ({{ $content['toursEs'] }})
                            </a>
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-tags me-1"></i> Categorías EN ({{ $content['categoriasEn'] }})
                            </a>
                            <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-tags me-1"></i> Categorías ES ({{ $content['categoriasEs'] }})
                            </a>
                        </div>
                        @if ($content['toursMissingEs'])
                            <div class="alert alert-warning py-2 px-3 small d-flex align-items-center gap-2 mb-0">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div>Hay <strong>{{ $content['toursMissingEs'] }}</strong> tours en inglés sin versión en español vinculada.</div>
                            </div>
                        @else
                            <div class="alert alert-success py-2 px-3 small d-flex align-items-center gap-2 mb-0">
                                <i class="fas fa-check-circle"></i>
                                <div>Todos los tours en inglés cuentan con su versión en español correspondiente.</div>
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            {{-- Blog & Artículos --}}
            <div class="col-lg-6">
                <section class="card dash-card h-100" aria-labelledby="branding-blogs">
                    <div class="card-header dash-card__header">
                        <h2 id="branding-blogs" class="dash-card__title">
                            <i class="fas fa-newspaper text-info me-2"></i> Blog & Artículos de Marca
                        </h2>
                        <a href="{{ route('enblogs.index') }}" class="btn btn-sm btn-link">Ver artículos <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Redacta historias, guías de viaje de lujo, gastronomía andina y novedades para posicionamiento orgánico (SEO).</p>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <a href="{{ route('enblogs.index') }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-globe me-1"></i> Artículos en Inglés ({{ $content['blogsEn'] }})
                            </a>
                            <a href="{{ route('esblogs.index') }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-language me-1"></i> Artículos en Español ({{ $content['blogsEs'] }})
                            </a>
                            <a href="{{ route('entags.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-hashtag me-1"></i> Tags EN ({{ $content['tagsEn'] }})
                            </a>
                            <a href="{{ route('estags.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-hashtag me-1"></i> Tags ES ({{ $content['tagsEs'] }})
                            </a>
                        </div>
                        @if ($content['blogsMissingEs'])
                            <div class="alert alert-warning py-2 px-3 small d-flex align-items-center gap-2 mb-0">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div>Hay <strong>{{ $content['blogsMissingEs'] }}</strong> artículos en inglés sin traducción al español.</div>
                            </div>
                        @else
                            <div class="alert alert-success py-2 px-3 small d-flex align-items-center gap-2 mb-0">
                                <i class="fas fa-check-circle"></i>
                                <div>Todos los artículos de blog están completamente traducidos.</div>
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            {{-- Galería de Medios --}}
            <div class="col-lg-6">
                <section class="card dash-card h-100" aria-labelledby="branding-media">
                    <div class="card-header dash-card__header">
                        <h2 id="branding-media" class="dash-card__title">
                            <i class="fas fa-images text-warning me-2"></i> Galería de Medios & Fotografía
                        </h2>
                        <a href="{{ route('imagenes.index') }}" class="btn btn-sm btn-link">Biblioteca <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Banco fotográfico de alta resolución para portadas de tours, banners de destino y entradas de blog.</p>
                        <div class="d-flex align-items-center justify-content-between p-3 rounded bg-body-tertiary mb-3">
                            <div>
                                <div class="h4 mb-0 fw-bold">{{ $content['imagenes'] }}</div>
                                <small class="text-muted">Imágenes registradas en el repositorio</small>
                            </div>
                            <a href="{{ route('imagenes.create') }}" class="btn btn-warning btn-sm shadow-sm">
                                <i class="fas fa-cloud-upload-alt me-1"></i> Subir imagen
                            </a>
                        </div>
                        @if ($content['lastImagen'])
                            <div class="small text-muted">
                                <i class="far fa-clock me-1"></i> Última imagen: <strong>{{ $content['lastImagen']->nombre }}</strong> ({{ $content['lastImagen']->updated_at?->locale('es')->diffForHumans() }})
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            {{-- Estado y Acceso Público --}}
            <div class="col-lg-6">
                <section class="card dash-card h-100" aria-labelledby="branding-status">
                    <div class="card-header dash-card__header">
                        <h2 id="branding-status" class="dash-card__title">
                            <i class="fas fa-satellite-dish text-success me-2"></i> Estado del Portal Web
                        </h2>
                        <a href="{{ route('index') }}" class="btn btn-sm btn-link" target="_blank" rel="noopener">Ir a la web <i class="fas fa-external-link-alt" aria-hidden="true"></i></a>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Visualiza el sitio web público tal como lo experimentan los viajeros internacionales.</p>
                        <div class="list-group list-group-flush small">
                            <a href="{{ route('index') }}" target="_blank" rel="noopener" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2">
                                <span><i class="fas fa-home text-muted me-2"></i> Portada Principal (Home)</span>
                                <i class="fas fa-external-link-alt text-muted"></i>
                            </a>
                            <a href="{{ route('tours.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2">
                                <span><i class="fas fa-compass text-muted me-2"></i> Catálogo Completo de Tours</span>
                                <i class="fas fa-arrow-right text-muted"></i>
                            </a>
                            <a href="{{ route('enblogs.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2">
                                <span><i class="fas fa-book-open text-muted me-2"></i> Andean Blog & Historias</span>
                                <i class="fas fa-arrow-right text-muted"></i>
                            </a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    @else
        <div class="dash-grid">
            {{-- Viajes en curso y próximos --}}
            <section class="card dash-card dash-grid__main" aria-labelledby="dash-upcoming">
                <div class="card-header dash-card__header">
                    <h2 id="dash-upcoming" class="dash-card__title">Próximos viajes</h2>
                    <a href="{{ route('programas.index') }}" class="btn btn-sm btn-link">Ver todos <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                </div>

                @if ($ongoing->isNotEmpty())
                    <div class="dash-ongoing">
                        <p class="dash-ongoing__label"><span class="dash-dot" aria-hidden="true"></span> En curso</p>
                        @foreach ($ongoing as $programa)
                            <a href="{{ route('itinerario', $programa) }}" class="dash-ongoing__item" target="_blank" rel="noopener">
                                <strong>{{ $programa->nombre }}</strong>
                                <span>Regresa {{ \Carbon\Carbon::parse($programa->fin)->locale('es')->isoFormat('D MMM') }} · {{ $programa->paxs_count }} pax</span>
                            </a>
                        @endforeach
                    </div>
                @endif

                @if ($upcoming->isEmpty())
                    <div class="admin-empty">
                        <i class="fas fa-route" aria-hidden="true"></i>
                        No hay programas próximos registrados.
                    </div>
                @else
                    <ul class="trip-list">
                        @foreach ($upcoming as $programa)
                            @php
                                $daysLeft = (int) $today->diffInDays(\Carbon\Carbon::parse($programa->inicio));
                                $urgency = $daysLeft <= 7 ? 'soon' : ($daysLeft <= 30 ? 'near' : 'later');
                            @endphp
                            <li class="trip">
                                <div class="trip__when trip__when--{{ $urgency }}">
                                    <span class="trip__day">{{ \Carbon\Carbon::parse($programa->inicio)->format('d') }}</span>
                                    <span class="trip__month">{{ \Carbon\Carbon::parse($programa->inicio)->locale('es')->isoFormat('MMM') }}</span>
                                </div>
                                <div class="trip__body">
                                    <p class="trip__name">{{ $programa->nombre }}</p>
                                    <p class="trip__meta">
                                        <span><i class="far fa-calendar" aria-hidden="true"></i> {{ $range($programa) }}</span>
                                        <span><i class="fas fa-users" aria-hidden="true"></i> {{ $programa->paxs_count }} pax</span>
                                        @if ($programa->agentes->isNotEmpty())
                                            <span class="d-none d-md-inline"><i class="fas fa-user-tie" aria-hidden="true"></i> {{ $programa->agentes->pluck('nombre')->implode(', ') }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="trip__side">
                                    <span class="badge trip__badge trip__badge--{{ $urgency }}">
                                        {{ $daysLeft === 0 ? 'Hoy' : ($daysLeft === 1 ? 'Mañana' : 'En ' . $daysLeft . ' días') }}
                                    </span>
                                    <span class="badge text-bg-light">{{ strtoupper($programa->lang ?: 'es') }}</span>
                                    <div class="trip__actions">
                                        <a href="{{ route('itinerario', $programa) }}" class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener" title="Ver itinerario del cliente" aria-label="Ver itinerario de {{ $programa->nombre }}"><i class="fas fa-eye" aria-hidden="true"></i></a>
                                        <a href="{{ route('programas.edit', $programa) }}" class="btn btn-sm btn-outline-primary" title="Editar" aria-label="Editar {{ $programa->nombre }}"><i class="fas fa-pen" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <div class="dash-grid__side">
                {{-- Operación: servicios del día y pendientes con fecha límite --}}
                <section class="card dash-card" aria-labelledby="dash-operacion">
                    <div class="card-header dash-card__header">
                        <h2 id="dash-operacion" class="dash-card__title">Operación</h2>
                        <a href="{{ route('operaciones.index') }}" class="btn btn-sm btn-link">Hoja del día <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                    <div class="dash-ops">
                        <a href="{{ route('operaciones.index') }}" class="dash-ops__stat">
                            <strong>{{ $operacion['hoy'] }}</strong><span>servicios hoy</span>
                        </a>
                        <a href="{{ route('operaciones.index', ['fecha' => $today->copy()->addDay()->toDateString()]) }}" class="dash-ops__stat">
                            <strong>{{ $operacion['manana'] }}</strong><span>mañana</span>
                        </a>
                        <a href="{{ route('operaciones.index') }}#ops-pendientes" class="dash-ops__stat {{ $operacion['porConfirmar'] ? 'is-warn' : '' }}">
                            <strong>{{ $operacion['porConfirmar'] }}</strong><span>por confirmar ({{ \App\Http\Controllers\OperacionesController::DIAS_POR_CONFIRMAR }} días)</span>
                        </a>
                    </div>
                    @if ($consultasNuevas)
                        <a href="{{ route('consultas.index') }}" class="dash-ops__alert">
                            <i class="fas fa-inbox" aria-hidden="true"></i>
                            {{ $consultasNuevas }} {{ $consultasNuevas === 1 ? 'consulta nueva por atender' : 'consultas nuevas por atender' }}
                            <i class="fas fa-arrow-right ms-auto" aria-hidden="true"></i>
                        </a>
                    @endif
                    @can('pagos.registrar')
                        @if ($operacion['pagosSolicitados'])
                            <a href="{{ route('cuentas.index', ['filtro' => 'solicitadas']) }}" class="dash-ops__alert">
                                <i class="fas fa-paper-plane" aria-hidden="true"></i>
                                {{ $operacion['pagosSolicitados'] }} {{ $operacion['pagosSolicitados'] === 1 ? 'pago solicitado por Reservas' : 'pagos solicitados por Reservas' }}
                                <i class="fas fa-arrow-right ms-auto" aria-hidden="true"></i>
                            </a>
                        @endif
                    @endcan
                    @if ($operacion['pagosVencidos'])
                        <a href="{{ route('cuentas.index', ['filtro' => 'vencidas']) }}" class="dash-ops__alert">
                            <i class="fas fa-file-invoice-dollar" aria-hidden="true"></i>
                            {{ $operacion['pagosVencidos'] }} {{ $operacion['pagosVencidos'] === 1 ? 'servicio prestado sin pagar' : 'servicios prestados sin pagar' }} al proveedor
                            <i class="fas fa-arrow-right ms-auto" aria-hidden="true"></i>
                        </a>
                    @endif
                    @if ($tareas->isEmpty())
                        <div class="admin-empty py-3"><i class="fas fa-check-circle" aria-hidden="true"></i> Sin tareas vencidas ni por vencer esta semana.</div>
                    @else
                        <ul class="task-list">
                            @foreach ($tareas->take(6) as $tarea)
                                @php $situacion = $tarea->situacion(); @endphp
                                <li class="task task--{{ $situacion }}">
                                    @can('tareas.gestionar')
                                        <form action="{{ route('programas.tareas.toggle', [$tarea->programa, $tarea]) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="volver" value="home">
                                            <button type="submit" class="task__check" aria-label="Marcar como hecha: {{ $tarea->titulo }}"><i class="far fa-square" aria-hidden="true"></i></button>
                                        </form>
                                    @endcan
                                    <div class="task__body">
                                        <a href="{{ route('programas.edit', [$tarea->programa, 'tab' => 'tareas']) }}" class="task__title">{{ $tarea->titulo }}</a>
                                        <span class="task__meta">{{ $tarea->programa->codigo }} · {{ $situacion === 'vencida' ? 'venció' : 'vence' }} {{ $tarea->fecha_limite->locale('es')->isoFormat('D MMM') }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        @if ($tareas->count() > 6)
                            <p class="cobro-list__total">y {{ $tareas->count() - 6 }} tareas más</p>
                        @endif
                    @endif
                </section>

                {{-- Correos sugeridos al cliente (se envían a mano) --}}
                @if ($avisosCliente->isNotEmpty())
                    <section class="card dash-card" aria-labelledby="dash-avisos">
                        <div class="card-header dash-card__header">
                            <h2 id="dash-avisos" class="dash-card__title">Avisar al cliente</h2>
                            <span class="badge text-bg-light">{{ $avisosCliente->count() }}</span>
                        </div>
                        <ul class="ops-side-list">
                            @foreach ($avisosCliente->take(6) as ['programa' => $programa, 'tipos' => $tipos])
                                <li>
                                    <span>
                                        <a href="{{ route('programas.edit', [$programa, 'tab' => 'pagos']) }}">{{ $programa->codigo }} · {{ $programa->cliente?->nombre ?? $programa->nombre }}</a>
                                        <small>{{ collect($tipos)->map(fn ($t) => \App\Models\Notificacion::TIPOS[$t])->implode(' · ') }}</small>
                                    </span>
                                    <i class="fas fa-envelope text-muted" aria-hidden="true"></i>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                {{-- Cobros por gestionar --}}
                <section class="card dash-card" aria-labelledby="dash-cobros">
                    <div class="card-header dash-card__header">
                        <h2 id="dash-cobros" class="dash-card__title">Cobros por gestionar</h2>
                        <span class="badge text-bg-light">{{ $stats['cotizaciones'] }} {{ $stats['cotizaciones'] === 1 ? 'cotización abierta' : 'cotizaciones abiertas' }}</span>
                    </div>
                    @if ($cobros->isEmpty())
                        <div class="admin-empty py-4"><i class="fas fa-check-circle" aria-hidden="true"></i> Sin saldos por vencer en 30 días.</div>
                    @else
                        <ul class="cobro-list">
                            @foreach ($cobros->take(6) as ['programa' => $programa, 'cobro' => $cobro])
                                <li class="cobro-list__item">
                                    <a href="{{ route('programas.edit', [$programa, 'tab' => 'pagos']) }}" class="cobro-list__link">
                                        <span class="cobro-list__name">{{ $programa->nombre }}</span>
                                        <span class="cobro-list__due {{ $cobro['saldoVencido'] ? 'is-overdue' : '' }}">
                                            {{ $cobro['saldoVencido'] ? 'Vencido' : 'Vence' }} {{ $cobro['vencimientoSaldo']->locale('es')->isoFormat('D MMM') }}
                                        </span>
                                    </a>
                                    <span class="cobro-list__amount">${{ number_format($cobro['saldo'], 0) }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <p class="cobro-list__total">Saldo pendiente: <strong>${{ number_format($stats['saldoPendiente'], 2) }}</strong></p>
                    @endif
                </section>

                {{-- Últimos movimientos de todas las áreas --}}
                <section class="card dash-card" aria-labelledby="dash-movimientos">
                    <div class="card-header dash-card__header">
                        <h2 id="dash-movimientos" class="dash-card__title">Últimos movimientos</h2>
                    </div>
                    <div class="dash-mov">
                        @include('movimientos.partials.lista', ['movimientos' => $ultimosMovimientos, 'compacto' => true])
                        <a href="{{ route('movimientos.index') }}" class="dash-mov__all">Ver todos los movimientos</a>
                    </div>
                </section>

                {{-- Actividad por mes --}}
                <section class="card dash-card" aria-labelledby="dash-months">
                    <div class="card-header dash-card__header">
                        <h2 id="dash-months" class="dash-card__title">Programas por mes · {{ $today->year }}</h2>
                    </div>
                    <div class="card-body">
                        <ol class="month-bars" aria-label="Programas que inician cada mes">
                            @foreach ($months as $month)
                                <li class="month-bars__item {{ $month['current'] ? 'is-current' : '' }}">
                                    <span class="month-bars__value">{{ $month['count'] }}</span>
                                    <span class="month-bars__bar"><span class="month-bars__fill" data-fill="{{ (int) (round($month['count'] / $maxMonth * 20) * 5) }}"></span></span>
                                    <span class="month-bars__label">{{ $month['label'] }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </section>

                {{-- Búsqueda por periodo --}}
                <section class="card dash-card" aria-labelledby="dash-filter">
                    <div class="card-header dash-card__header">
                        <h2 id="dash-filter" class="dash-card__title">Buscar programas</h2>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('programas.index') }}" class="row g-2">
                            <div class="col-6">
                                <label for="dash-anio" class="form-label small">Año</label>
                                <select id="dash-anio" name="anio_id" class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    @foreach ($anios as $anio)
                                        <option value="{{ $anio->id }}" @selected((int) $anio->anio === $today->year)>{{ $anio->anio }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="dash-mes" class="form-label small">Mes</label>
                                <select id="dash-mes" name="mes_id" class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    @foreach ($meses as $mes)
                                        <option value="{{ $mes->id }}">{{ $mes->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search" aria-hidden="true"></i> Ver programas</button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    @endif

    {{-- Estado del sitio web (para administradores) --}}
    @if ($isAdmin)
        <section class="card dash-card mt-4" aria-labelledby="dash-content">
            <div class="card-header dash-card__header">
                <h2 id="dash-content" class="dash-card__title">Contenido del sitio web</h2>
                <a href="{{ route('index') }}" class="btn btn-sm btn-link" target="_blank" rel="noopener">Ver sitio <i class="fas fa-external-link-alt" aria-hidden="true"></i></a>
            </div>
            <div class="card-body">
                <div class="content-health">
                    <div class="content-health__item">
                        <p class="content-health__label">Tours</p>
                        <p class="content-health__value">{{ $content['toursEn'] }} <small>EN</small> · {{ $content['toursEs'] }} <small>ES</small></p>
                        @if ($content['toursMissingEs'])
                            <a href="{{ route('tours.index') }}" class="content-health__warn"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> {{ $content['toursMissingEs'] }} sin versión en español</a>
                        @else
                            <span class="content-health__ok"><i class="fas fa-check-circle" aria-hidden="true"></i> Todos traducidos</span>
                        @endif
                        <div class="content-health__links">
                            <a href="{{ route('tours.create') }}">+ Tour EN</a>
                            <a href="{{ route('estours.create') }}">+ Tour ES</a>
                        </div>
                    </div>
                    <div class="content-health__item">
                        <p class="content-health__label">Blog</p>
                        <p class="content-health__value">{{ $content['blogsEn'] }} <small>EN</small> · {{ $content['blogsEs'] }} <small>ES</small></p>
                        @if ($content['blogsMissingEs'])
                            <a href="{{ route('esblogs.index') }}" class="content-health__warn"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> {{ $content['blogsMissingEs'] }} artículos EN sin versión ES vinculada</a>
                        @else
                            <span class="content-health__ok"><i class="fas fa-check-circle" aria-hidden="true"></i> Todos traducidos</span>
                        @endif
                        <div class="content-health__links">
                            <a href="{{ route('enblogs.create') }}">+ Artículo EN</a>
                            <a href="{{ route('esblogs.create') }}">+ Artículo ES</a>
                        </div>
                    </div>
                    <div class="content-health__item">
                        <p class="content-health__label">Imágenes y Medios</p>
                        <p class="content-health__value">{{ $content['imagenes'] }} <small>imágenes</small></p>
                        <div class="content-health__links">
                            <a href="{{ route('imagenes.index') }}">Ver galería</a>
                            <a href="{{ route('imagenes.create') }}">+ Subir imagen</a>
                        </div>
                    </div>
                    <div class="content-health__item">
                        <p class="content-health__label">Última actualización</p>
                        @if ($content['lastTour'])
                            <p class="content-health__recent"><i class="fas fa-map-marked-alt" aria-hidden="true"></i> {{ $content['lastTour']->nombre }} <small>{{ $content['lastTour']->updated_at?->locale('es')->diffForHumans() }}</small></p>
                        @endif
                        @if ($content['lastBlog'])
                            <p class="content-health__recent"><i class="fas fa-pen-nib" aria-hidden="true"></i> {{ $content['lastBlog']->nombre }} <small>{{ $content['lastBlog']->updated_at?->locale('es')->diffForHumans() }}</small></p>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
