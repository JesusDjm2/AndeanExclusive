{{--
    Formulario único de programas (create y edit), organizado en pestañas.
    Comportamiento: resources/js/programa-form.js · Estilos: resources/sass/admin/_programas-form.scss
    Variables: $programa (nuevo o existente), $agentes, $proveedoresPorCategoria, $hoteles, $estados.
    Itinerario, Pagos y Tareas solo existen al editar. Pagos y Tareas viven fuera del <form> principal porque tienen sus propios formularios.
--}}
@php
    $isEdit = $programa->exists;
    $canEdit = auth()->user()?->can('programas.gestionar');
    $canCobros = auth()->user()?->can('cobros.gestionar');
    $canTareas = auth()->user()?->can('tareas.gestionar');
    $verCotizacion = auth()->user()?->can('cotizacion.ver');
    $verMargen = auth()->user()?->can('margen.ver');
    $idiomas = ['es' => 'Español', 'en' => 'Inglés'];

    // Pasajeros: lo reenviado tras un error de validación o lo guardado.
    $paxRows = old('paxs') ?? $programa->paxs->map(fn ($p) => [
        'id' => $p->id, 'nombre' => $p->nombre, 'fecha_nacimiento' => $p->fecha_nacimiento?->toDateString(),
        'sexo' => $p->sexo, 'pais' => $p->pais, 'pasaporte' => $p->pasaporte,
        'pasaporte_vencimiento' => $p->pasaporte_vencimiento?->toDateString(), 'dieta' => $p->dieta,
        'alergias' => $p->alergias, 'condiciones_medicas' => $p->condiciones_medicas,
        'emergencia_nombre' => $p->emergencia_nombre, 'emergencia_telefono' => $p->emergencia_telefono,
    ])->all();
    $clienteModo = old('cliente_modo', $clientes->isEmpty() ? 'nuevo' : 'existente');
    $clienteNuevo = new \App\Models\Cliente(['tipo' => 'directo', 'idioma' => $programa->lang]);
    $paxModels = $programa->paxs->keyBy('id');

    $agentesSel = collect(old('agentes', $programa->agentes->pluck('id')->all()))->map(fn ($v) => (int) $v);

    // Itinerario: días del programa con sus servicios (lo reenviado tras un error de validación o lo guardado).
    $itinerario = $isEdit ? $programa->itinerario() : ['dias' => collect(), 'fuera' => collect()];
    $servicioRows = old('servicios') ?? $programa->servicios->values()->map(fn ($s) => [
        'id' => $s->id, 'de_estadia' => $s->estadia()->exists(), 'fecha' => $s->fecha->toDateString(), 'hora' => $s->hora_corta, 'tipo' => $s->tipo, 'titulo' => $s->titulo,
        'proveedor_id' => $s->proveedor_id, 'estado' => $s->estado, 'confirmacion' => $s->confirmacion,
        'punto_encuentro' => $s->punto_encuentro, 'descripcion' => $s->descripcion, 'notas' => $s->notas, 'visible_cliente' => $s->visible_cliente,
        'costo' => $s->costo, 'costo_estimado' => $s->costo_estimado, 'moneda' => $s->moneda, 'costo_por' => $s->costo_por, 'pagado_at' => $s->pagado_at?->toDateString(), 'situacion_pago' => $s->situacion_pago, 'saldo' => $s->saldo_proveedor,
    ])->all();
    if (old('servicios') !== null) {
        // Checkbox sin marcar = no llega en el envío.
        $servicioRows = array_map(fn ($row) => $row + ['visible_cliente' => false], $servicioRows);
    }
    $clavesDias = $itinerario['dias']->pluck('clave')->all();
    $serviciosPorDia = collect($servicioRows)->groupBy(fn ($row) => in_array($row['fecha'] ?? '', $clavesDias, true) ? $row['fecha'] : 'fuera', true);
    $operacion = $isEdit ? $programa->operacion() : null;
    $costeo = $isEdit ? $programa->costeo() : null;
    $correosSugeridos = $isEdit ? \App\Support\Recordatorios::sugeridos($programa) : [];
    $estadiaRows = old('estadias') ?? $programa->habitacionesFechas->sortBy(fn ($e) => [$e->fecha_ingreso === null, (string) $e->fecha_ingreso, $e->id])->values()
        ->map(fn ($e) => ['id' => $e->id, 'habitacion_id' => $e->habitacion_id, 'cantidad' => $e->cantidad, 'costo_noche' => $e->costo_noche, 'moneda' => $e->moneda, 'ingreso' => $e->fecha_ingreso, 'salida' => $e->fecha_salida])->all();

    // Pestaña con errores de validación → punto rojo en la pestaña y se abre la primera.
    $tabFields = [
        'datos' => ['nombre', 'codigo', 'estado', 'cliente_modo', 'cliente_id', 'cliente_nuevo.*', 'lang', 'inicio', 'fin', 'presentacion', 'agentes', 'agentes.*'],
        'pasajeros' => ['paxs', 'paxs.*'],
        'alojamiento' => ['estadias', 'estadias.*'],
        'itinerario' => ['dias.*', 'servicios', 'servicios.*'],
        'precios' => ['precioAdulto', 'precioChild', 'porcentaje_ganancia', 'tipo_cambio', 'suplemento_individual', 'habitaciones_individuales'],
    ];
    $tabHasError = collect($tabFields)->map(fn ($fields) => collect($errors->keys())->contains(fn ($key) => collect($fields)->contains(fn ($f) => \Illuminate\Support\Str::is($f, $key))));
    $initialTab = $tabHasError->filter()->keys()->first()
        ?? ($errors->pago->any() ? 'pagos' : null)
        ?? ($errors->tarea->any() ? 'tareas' : null)
        ?? (in_array(request('tab'), ['datos', 'pasajeros', 'alojamiento', 'itinerario', 'precios', 'pagos', 'tareas', 'historial'], true) ? request('tab') : 'datos');
    if (in_array($initialTab, ['itinerario', 'pagos', 'tareas', 'historial'], true) && ! $isEdit) {
        $initialTab = 'datos';
    }

    $tabs = [
        'datos' => ['Datos', 'fa-clipboard-list'],
        'pasajeros' => ['Pasajeros', 'fa-users'],
        'alojamiento' => ['Alojamiento', 'fa-bed'],
    ];
    if ($isEdit) {
        $tabs['itinerario'] = ['Itinerario', 'fa-route'];
    }
    $tabs['precios'] = ['Precios', 'fa-dollar-sign'];
    if ($isEdit) {
        $tabs['pagos'] = ['Pagos', 'fa-wallet'];
        $tabs['tareas'] = ['Tareas', 'fa-tasks'];
        $tabs['historial'] = ['Historial', 'fa-stream'];
        $cobro = $programa->cobranza();
    }
    $money = fn ($n) => '$' . number_format((float) $n, 2);
@endphp

@push('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.9.2/ckeditor.js"></script>
@endpush

<div class="pf" data-programa-form data-initial-tab="{{ $initialTab }}"
    data-edad-adulto="{{ \App\Models\Programa::EDAD_ADULTO }}" data-edad-nino="{{ \App\Models\Programa::EDAD_NINO_MIN }}"
    data-deposito="{{ \App\Models\Programa::PORCENTAJE_DEPOSITO }}">
    {{-- Encabezado --}}
    <div class="pf-header">
        <div class="pf-header__main">
            <a href="{{ route('programas.index') }}" class="pf-header__back"><i class="fas fa-arrow-left" aria-hidden="true"></i> Programas</a>
            <h1 class="pf-header__title">{{ $isEdit ? $programa->nombre : 'Nuevo programa' }}</h1>
            @if ($isEdit)
                <p class="pf-header__meta">
                    <span class="pf-status pf-status--{{ $programa->estado }}">{{ $estados[$programa->estado] ?? $programa->estado }}</span>
                    <span><i class="fas fa-hashtag" aria-hidden="true"></i>{{ $programa->codigo }}</span>
                    @if ($cobro['total'] > 0)
                        <span><i class="fas fa-wallet" aria-hidden="true"></i>Pagado {{ $cobro['porcentaje'] }}%</span>
                    @endif
                    @if ($programa->itinerario_visto_at)
                        <span title="Última vez que el cliente abrió su enlace"><i class="fas fa-eye" aria-hidden="true"></i>Visto {{ $programa->itinerario_visto_at->locale('es')->diffForHumans() }}</span>
                    @endif
                </p>
            @else
                <p class="pf-header__meta">Completa los datos básicos; el resto puedes cargarlo después.</p>
            @endif
        </div>
        @if ($isEdit)
            <div class="pf-header__actions">
                <a href="{{ route('itinerario', $programa) }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-eye" aria-hidden="true"></i> Ver itinerario
                </a>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-copy="{{ route('itinerario', $programa) }}">
                    <i class="fas fa-link" aria-hidden="true"></i> Copiar enlace
                </button>
                <a href="{{ route('programas.pdf', $programa) }}" class="btn btn-outline-secondary btn-sm" data-pdf-preview="{{ route('programas.pdf', [$programa, 'ver' => 1]) }}" data-pdf-title="{{ $programa->nombre }}">
                    <i class="fas fa-file-pdf" aria-hidden="true"></i> PDF
                </a>
            </div>
        @endif
    </div>

    @foreach (['success' => 'success', 'warning' => 'warning', 'error' => 'danger'] as $key => $type)
        @if (session($key))
            <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
    @endforeach
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Revisa los campos marcados</strong> en las pestañas con <span class="pf-tab__dot pf-tab__dot--inline"></span>.
        </div>
    @endif

    @if ($isEdit && ($aceptacion = $programa->aceptaciones()->first()))
        @php $vigente = $aceptacion->huella === \App\Models\Programa::huellaDe($programa->fotoDePropuesta()); @endphp
        <div class="alert alert-{{ $vigente ? 'success' : 'warning' }}" role="status">
            <i class="fas {{ $vigente ? 'fa-check-circle' : 'fa-exclamation-triangle' }}" aria-hidden="true"></i>
            <strong>{{ $aceptacion->nombre }}</strong> aceptó la propuesta el {{ $aceptacion->created_at->locale('es')->isoFormat('D MMM YYYY, HH:mm') }} por {{ $money($aceptacion->total) }}.
            @if (! $vigente) La propuesta cambió después: pídele que vuelva a aceptarla desde su enlace. @elseif ($programa->estado === 'cotizacion') Ya puedes confirmar el programa. @endif
        </div>
    @endif

    @if ($programa->cerrado_at)
        <div class="pf-readonly" role="status">
            <i class="fas fa-lock" aria-hidden="true"></i>
            <span>Programa cerrado el {{ $programa->cerrado_at->locale('es')->isoFormat('D MMM YYYY') }}. Ya no se puede modificar.</span>
            @can('programas.reabrir')
                <form action="{{ route('programas.reabrir', $programa) }}" method="POST" class="d-inline ms-2" data-confirm="El programa se podrá volver a modificar." data-confirm-title="¿Reabrir programa?" data-confirm-button="Sí, reabrir">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Reabrir</button>
                </form>
            @endcan
        </div>
    @elseif ($isEdit && $programa->estado === 'confirmado' && auth()->user()?->can('programas.cerrar') && $programa->fin && \Carbon\Carbon::parse($programa->fin)->lt(today()))
        @php $faltanParaCerrar = $programa->pendientesParaCerrar(); @endphp
        <div class="alert {{ $faltanParaCerrar ? 'alert-light' : 'alert-success' }} d-flex flex-wrap align-items-center justify-content-between gap-2" role="status">
            <span>{{ $faltanParaCerrar ? 'Para cerrar este programa falta: ' . implode(' ', $faltanParaCerrar) : 'El viaje terminó, todo está cobrado y pagado.' }}</span>
            @unless ($faltanParaCerrar)
                <form action="{{ route('programas.cerrar', $programa) }}" method="POST" data-confirm="Después solo Administración podrá reabrirlo." data-confirm-title="¿Cerrar programa?" data-confirm-button="Sí, cerrar">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-lock" aria-hidden="true"></i> Cerrar programa</button>
                </form>
            @endunless
        </div>
    @endif

    @if ($programa->plantilla)
        <div class="pf-readonly" role="status">
            <i class="fas fa-copy" aria-hidden="true"></i>
            <span>Esto es una plantilla: no es un viaje real. Sus fechas son de referencia y no aparece en listados ni reservas. <a href="{{ route('programas.duplicar', $programa) }}">Usarla para una cotización</a>.</span>
        </div>
    @endif

    @if ($isEdit && ! $canEdit)
        <div class="pf-readonly" role="status">
            <i class="fas fa-eye" aria-hidden="true"></i>
            <span>Ficha en modo consulta: los datos del programa los gestiona Operaciones.@if ($canCobros) Aquí registras los cobros en la pestaña Pagos.@endif @can('servicios.gestionar') Los servicios se gestionan desde <a href="{{ route('reservas.index') }}">Reservas</a>.@endcan</span>
        </div>
    @endif

    {{-- Pestañas --}}
    <nav class="pf-tabs" role="tablist" aria-label="Secciones del programa">
        @foreach ($tabs as $key => [$label, $icon])
            <button type="button" class="pf-tab {{ $initialTab === $key ? 'active' : '' }}" id="pf-tab-{{ $key }}"
                data-bs-toggle="tab" data-bs-target="#pf-{{ $key }}" data-tab="{{ $key }}" role="tab"
                aria-controls="pf-{{ $key }}" aria-selected="{{ $initialTab === $key ? 'true' : 'false' }}">
                <i class="fas {{ $icon }}" aria-hidden="true"></i>
                <span>{{ $label }}</span>
                @if ($key === 'pasajeros')
                    <span class="pf-tab__count" data-pax-count>{{ count($paxRows) }}</span>
                @elseif ($key === 'itinerario' && $operacion['abiertos'])
                    <span class="pf-tab__count pf-tab__count--warn" title="Servicios por reservar o solicitados">{{ $operacion['abiertos'] }}</span>
                @elseif ($key === 'tareas' && $operacion['tareasPendientes'])
                    <span class="pf-tab__count {{ $operacion['tareasVencidas'] ? 'pf-tab__count--danger' : '' }}" title="Tareas pendientes">{{ $operacion['tareasPendientes'] }}</span>
                @endif
                @if ($tabHasError[$key] ?? false)
                    <span class="pf-tab__dot" title="Hay errores en esta sección"></span>
                @endif
            </button>
        @endforeach
    </nav>

    <form id="programa-form" action="{{ $isEdit ? route('programas.update', $programa) : route('programas.store') }}"
        method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif
        <input type="hidden" name="_tab" value="{{ $initialTab }}" data-current-tab>
        @if (! $isEdit && request('consulta'))
            <input type="hidden" name="consulta_id" value="{{ request()->integer('consulta') }}">
        @endif
        <fieldset class="pf-fieldset" @disabled(! $canEdit)>

        <div class="tab-content">
            {{-- ── DATOS ── --}}
            <section class="tab-pane fade {{ $initialTab === 'datos' ? 'show active' : '' }}" id="pf-datos" role="tabpanel" aria-labelledby="pf-tab-datos">
                <div class="prog-section">
                    <div class="prog-section__body">
                        <div class="row g-3">
                            <div class="col-lg-8">
                                <label for="pf-nombre" class="prog-label">Nombre del programa <span class="required">*</span></label>
                                <input id="pf-nombre" type="text" name="nombre" value="{{ old('nombre', $programa->nombre) }}"
                                    class="form-control @error('nombre') is-invalid @enderror" required maxlength="255">
                                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <label for="pf-codigo" class="prog-label">Código @if ($isEdit)<span class="required">*</span>@endif</label>
                                <input id="pf-codigo" type="text" name="codigo" value="{{ old('codigo', $programa->codigo) }}"
                                    class="form-control @error('codigo') is-invalid @enderror" @if ($isEdit) required @endif maxlength="50"
                                    @unless ($isEdit) placeholder="Automático: {{ $siguienteCodigo }}" @endunless>
                                @error('codigo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <label for="pf-estado" class="prog-label">Estado</label>
                                <select id="pf-estado" name="estado" class="form-select @error('estado') is-invalid @enderror">
                                    @foreach ($estados as $value => $label)
                                        <option value="{{ $value }}" @selected(old('estado', $programa->estado) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <label for="pf-lang" class="prog-label">Idioma del itinerario</label>
                                <select id="pf-lang" name="lang" class="form-select @error('lang') is-invalid @enderror">
                                    @foreach ($idiomas as $value => $label)
                                        <option value="{{ $value }}" @selected(old('lang', $programa->lang) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('lang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <label for="pf-inicio" class="prog-label">Inicio</label>
                                <input id="pf-inicio" type="date" name="inicio" value="{{ old('inicio', $programa->inicio) }}"
                                    class="form-control @error('inicio') is-invalid @enderror" data-fecha-inicio>
                                @error('inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <label for="pf-fin" class="prog-label">Fin</label>
                                <input id="pf-fin" type="date" name="fin" value="{{ old('fin', $programa->fin) }}"
                                    class="form-control @error('fin') is-invalid @enderror" data-fecha-fin>
                                @error('fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-lg-4 d-flex align-items-end">
                                <p class="prog-input-hint mb-2" data-duracion>
                                    <i class="fas fa-info-circle" aria-hidden="true"></i> El año y mes del programa se asignan solos desde la fecha de inicio.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="prog-section" data-vuelos-section>
                    <div class="prog-section__header">
                        <span class="prog-section__title"><i class="fas fa-plane text-primary me-2"></i> Vuelos del grupo</span>
                        <span class="prog-section__subtitle">Horarios de arribo y partida para coordinar traslados y la hoja de operaciones</span>
                    </div>
                    <div class="prog-section__body">
                        <div class="row g-4">
                            {{-- Tarjeta Vuelo de Llegada --}}
                            <div class="col-12 col-lg-6">
                                <div class="card h-100 border shadow-sm rounded-3">
                                    <div class="card-header bg-light-subtle py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-dark small">
                                            <i class="fas fa-plane-arrival text-success me-1"></i> Vuelo de Llegada (Arribo)
                                        </span>
                                        <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill py-0 px-2"
                                            data-flight-sync="llegada" title="Copiar la fecha de inicio del tour">
                                            <i class="fas fa-calendar-day me-1"></i> Usar día de inicio
                                        </button>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="mb-3">
                                            <label for="pf-llegada-vuelo" class="prog-label small fw-semibold text-muted mb-1">
                                                Aerolínea / N° de Vuelo
                                            </label>
                                            <input id="pf-llegada-vuelo" type="text" name="llegada_vuelo"
                                                value="{{ old('llegada_vuelo', $programa->llegada_vuelo) }}" maxlength="40"
                                                placeholder="Ej. LA 2021 (LATAM)"
                                                class="form-control form-control-sm @error('llegada_vuelo') is-invalid @enderror">
                                            @error('llegada_vuelo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <label class="prog-label small fw-semibold text-muted mb-1">Fecha y hora de arribo</label>
                                        <div class="row g-2 align-items-end mb-2">
                                            <div class="col-7">
                                                <input type="date" class="form-control form-control-sm" id="pf-llegada-fecha"
                                                    data-flight-date="llegada"
                                                    value="{{ old('llegada_at') ? substr(old('llegada_at'), 0, 10) : ($programa->llegada_at ? $programa->llegada_at->format('Y-m-d') : '') }}">
                                            </div>
                                            <div class="col-5">
                                                <input type="time" class="form-control form-control-sm" id="pf-llegada-hora"
                                                    data-flight-time="llegada"
                                                    value="{{ old('llegada_at') ? substr(old('llegada_at'), 11, 5) : ($programa->llegada_at ? $programa->llegada_at->format('H:i') : '') }}">
                                            </div>
                                        </div>

                                        {{-- Botón Aceptar y Acciones --}}
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-2 pt-2 border-top">
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-primary py-1 px-3" data-flight-accept="llegada">
                                                    <i class="fas fa-check me-1"></i> Aceptar fecha y hora
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2" data-flight-clear="llegada" title="Borrar horario de llegada">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            <div class="small text-muted d-flex gap-1 align-items-center">
                                                <span class="text-xs">Sugeridos:</span>
                                                <button type="button" class="badge bg-light text-dark border p-1" data-flight-preset="llegada" data-time="09:00">09:00</button>
                                                <button type="button" class="badge bg-light text-dark border p-1" data-flight-preset="llegada" data-time="11:30">11:30</button>
                                                <button type="button" class="badge bg-light text-dark border p-1" data-flight-preset="llegada" data-time="14:00">14:00</button>
                                            </div>
                                        </div>

                                        {{-- Campo oculto para envío seguro a Laravel (mantiene compatibilidad 100%) --}}
                                        <input id="pf-llegada-at" type="hidden" name="llegada_at"
                                            data-flight-target="llegada"
                                            value="{{ old('llegada_at', $programa->llegada_at?->format('Y-m-d\TH:i')) }}">
                                        @error('llegada_at')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                                        {{-- Tarjeta de estado de confirmación visible --}}
                                        <div class="mt-2" data-flight-status="llegada"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tarjeta Vuelo de Salida --}}
                            <div class="col-12 col-lg-6">
                                <div class="card h-100 border shadow-sm rounded-3">
                                    <div class="card-header bg-light-subtle py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-dark small">
                                            <i class="fas fa-plane-departure text-danger me-1"></i> Vuelo de Salida (Despedida)
                                        </span>
                                        <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill py-0 px-2"
                                            data-flight-sync="salida" title="Copiar la fecha de fin del tour">
                                            <i class="fas fa-calendar-day me-1"></i> Usar día de fin
                                        </button>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="mb-3">
                                            <label for="pf-salida-vuelo" class="prog-label small fw-semibold text-muted mb-1">
                                                Aerolínea / N° de Vuelo
                                            </label>
                                            <input id="pf-salida-vuelo" type="text" name="salida_vuelo"
                                                value="{{ old('salida_vuelo', $programa->salida_vuelo) }}" maxlength="40"
                                                placeholder="Ej. LA 2040 (LATAM)"
                                                class="form-control form-control-sm @error('salida_vuelo') is-invalid @enderror">
                                            @error('salida_vuelo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <label class="prog-label small fw-semibold text-muted mb-1">Fecha y hora de partida</label>
                                        <div class="row g-2 align-items-end mb-2">
                                            <div class="col-7">
                                                <input type="date" class="form-control form-control-sm" id="pf-salida-fecha"
                                                    data-flight-date="salida"
                                                    value="{{ old('salida_at') ? substr(old('salida_at'), 0, 10) : ($programa->salida_at ? $programa->salida_at->format('Y-m-d') : '') }}">
                                            </div>
                                            <div class="col-5">
                                                <input type="time" class="form-control form-control-sm" id="pf-salida-hora"
                                                    data-flight-time="salida"
                                                    value="{{ old('salida_at') ? substr(old('salida_at'), 11, 5) : ($programa->salida_at ? $programa->salida_at->format('H:i') : '') }}">
                                            </div>
                                        </div>

                                        {{-- Botón Aceptar y Acciones --}}
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-2 pt-2 border-top">
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-primary py-1 px-3" data-flight-accept="salida">
                                                    <i class="fas fa-check me-1"></i> Aceptar fecha y hora
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2" data-flight-clear="salida" title="Borrar horario de salida">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            <div class="small text-muted d-flex gap-1 align-items-center">
                                                <span class="text-xs">Sugeridos:</span>
                                                <button type="button" class="badge bg-light text-dark border p-1" data-flight-preset="salida" data-time="11:00">11:00</button>
                                                <button type="button" class="badge bg-light text-dark border p-1" data-flight-preset="salida" data-time="16:30">16:30</button>
                                                <button type="button" class="badge bg-light text-dark border p-1" data-flight-preset="salida" data-time="19:00">19:00</button>
                                            </div>
                                        </div>

                                        {{-- Campo oculto para envío seguro a Laravel --}}
                                        <input id="pf-salida-at" type="hidden" name="salida_at"
                                            data-flight-target="salida"
                                            value="{{ old('salida_at', $programa->salida_at?->format('Y-m-d\TH:i')) }}">
                                        @error('salida_at')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                                        {{-- Tarjeta de estado de confirmación visible --}}
                                        <div class="mt-2" data-flight-status="salida"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="prog-section" data-cliente-picker @if ($programa->plantilla) hidden @endif>
                    <div class="prog-section__header">
                        <span class="prog-section__title">Cliente</span>
                        <span class="prog-section__subtitle">Titular del programa: recibe el itinerario y el PDF por correo</span>
                    </div>
                    <div class="prog-section__body">
                        <div class="pf-segmented" role="radiogroup" aria-label="Cliente">
                            <label><input type="radio" name="cliente_modo" value="existente" @checked($clienteModo === 'existente') @disabled($clientes->isEmpty())> <span>Cliente existente</span></label>
                            <label><input type="radio" name="cliente_modo" value="nuevo" @checked($clienteModo === 'nuevo')> <span><i class="fas fa-plus" aria-hidden="true"></i> Nuevo cliente</span></label>
                        </div>

                        <div class="mt-3" data-cliente-panel="existente" @if ($clienteModo !== 'existente') hidden @endif>
                            <label for="pf-cliente" class="prog-label">Cliente <span class="required">*</span></label>
                            <select id="pf-cliente" name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror">
                                <option value="">— Seleccionar cliente —</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" @selected((int) old('cliente_id', $programa->cliente_id) === $cliente->id)>
                                        {{ $cliente->etiqueta }}{{ $cliente->email ? ' — ' . $cliente->email : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @if ($programa->cliente)
                                <p class="prog-input-hint mt-2 mb-0"><a href="{{ route('clientes.edit', $programa->cliente) }}">Ver ficha de {{ $programa->cliente->nombre }}</a></p>
                            @endif
                        </div>

                        <div class="mt-3" data-cliente-panel="nuevo" @if ($clienteModo !== 'nuevo') hidden @endif>
                            @include('clientes.partials.campos', ['cliente' => $clienteNuevo, 'prefix' => 'cliente_nuevo', 'idPrefix' => 'pf-cn'])
                        </div>
                    </div>
                </div>

                <div class="prog-section">
                    <div class="prog-section__header">
                        <span class="prog-section__title">Agentes asignados</span>
                    </div>
                    <div class="prog-section__body">
                        @error('agentes')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                        @if ($agentes->isEmpty())
                            <p class="text-muted small mb-0">No hay agentes. <a href="{{ route('agentes.index') }}">Crear agentes</a></p>
                        @endif
                        <div class="pf-chips">
                            @foreach ($agentes as $agente)
                                <label class="pf-chip">
                                    <input type="checkbox" name="agentes[]" value="{{ $agente->id }}" @checked($agentesSel->contains($agente->id))>
                                    <span>{{ $agente->nombre }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="prog-section">
                    <div class="prog-section__header">
                        <span class="prog-section__title">Imagen de portada</span>
                        <span class="prog-section__subtitle">Fondo de la primera página del PDF y del itinerario en línea</span>
                    </div>
                    <div class="prog-section__body">
                        @error('portada')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                        @php $portadaSel = old('portada', $programa->portada); @endphp
                        <div class="pf-covers" role="radiogroup" aria-label="Imagen de portada">
                            <label class="pf-cover">
                                <input type="radio" name="portada" value="" @checked(blank($portadaSel))>
                                <span class="pf-cover__media">
                                    <img src="{{ asset(\App\Support\Portadas::DEFECTO) }}" alt="" loading="lazy">
                                    <span class="pf-cover__tag">Por defecto</span>
                                </span>
                                <span class="pf-cover__name">Machu Picchu</span>
                            </label>
                            @foreach ($portadas as $opcion)
                                @continue($opcion['ruta'] === \App\Support\Portadas::DEFECTO)
                                <label class="pf-cover">
                                    <input type="radio" name="portada" value="{{ $opcion['ruta'] }}" @checked($portadaSel === $opcion['ruta'])>
                                    <span class="pf-cover__media">
                                        <img src="{{ asset($opcion['ruta']) }}" alt="" loading="lazy">
                                        @if ($opcion['calidad'] === 'media')
                                            <span class="pf-cover__tag pf-cover__tag--media" title="Resolución {{ $opcion['ancho'] }}×{{ $opcion['alto'] }}: se verá algo menos nítida en el PDF">Calidad media</span>
                                        @endif
                                    </span>
                                    <span class="pf-cover__name" title="{{ $opcion['nombre'] }}">{{ $opcion['nombre'] }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="prog-input-hint mt-2 mb-0">Solo aparecen imágenes de la galería con resolución suficiente para una página completa.</p>
                    </div>
                </div>

                <div class="prog-section">
                    <div class="prog-section__header">
                        <span class="prog-section__title"><i class="fas fa-file-alt text-primary me-2"></i> Presentación general del programa</span>
                        <span class="prog-section__subtitle">Texto de bienvenida y narrativa general que encabeza el viaje en el PDF y en la web del cliente</span>
                    </div>
                    <div class="prog-section__body">
                        <div class="alert alert-light border border-info-subtle bg-info-subtle p-3 rounded-3 small mb-3">
                            <div class="d-flex align-items-start gap-2">
                                <i class="fas fa-info-circle text-primary mt-1"></i>
                                <div>
                                    <strong class="text-dark">¿Qué diferencia tiene esta Presentación frente al Itinerario Día a Día?</strong>
                                    <ul class="mb-0 ps-3 mt-1 text-muted">
                                        <li><strong>Esta Presentación:</strong> Es la carta editorial de bienvenida que introduce el concepto del tour. Resume el espíritu de la experiencia y los atractivos principales (aparece en la portada y primera página del documento).</li>
                                        <li><strong>El Itinerario Día a Día:</strong> Es el desglose cronológico de cada jornada (Día 1, Día 2...), donde se asignan las actividades específicas, traslados, hoteles, guías y entradas. Ese detalle logístico se arma en la pestaña superior <strong>«Itinerario»</strong>.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <label for="presentacion" class="visually-hidden">Presentación general del programa</label>
                        <textarea name="presentacion" id="presentacion" class="ckeditor form-control @error('presentacion') is-invalid @enderror"
                            rows="6" placeholder="Escribe aquí la bienvenida y narrativa general del viaje...">{{ old('presentacion', $programa->presentacion) }}</textarea>
                        @error('presentacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

            {{-- ── PASAJEROS ── --}}
            <section class="tab-pane fade {{ $initialTab === 'pasajeros' ? 'show active' : '' }}" id="pf-pasajeros" role="tabpanel" aria-labelledby="pf-tab-pasajeros">
                <div class="prog-section">
                    <div class="prog-section__body">
                        @error('paxs')<div class="text-danger small mb-3">{{ $message }}</div>@enderror
                        @php
                            $alertas = $programa->paxs->map(fn ($p) => [$p->nombre, $p->alertaPasaporte()])->filter(fn ($a) => $a[1]);
                        @endphp
                        @if ($alertas->isNotEmpty())
                            <div class="alert alert-warning py-2">
                                <strong><i class="fas fa-passport" aria-hidden="true"></i> Pasaportes por revisar:</strong>
                                {{ $alertas->map(fn ($a) => $a[0] . ' (' . ['falta' => 'sin número', 'vencido' => 'vencido', 'por_vencer' => 'vence antes de 6 meses del viaje'][$a[1]] . ')')->implode(' · ') }}
                            </div>
                        @endif
                        @if ($isEdit && $programa->estado !== 'cancelado')
                            <div class="pf-client-link">
                                <span><i class="fas fa-user-edit" aria-hidden="true"></i> El cliente puede completar pasaportes, dietas y contactos de emergencia desde su enlace.</span>
                                <span class="pf-client-link__actions">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-copy="{{ route('itinerario.pasajeros', $programa) }}"><i class="fas fa-link" aria-hidden="true"></i> Copiar enlace</button>
                                    @if ($canEdit && $programa->email)
                                        <button type="submit" form="notif-form" name="tipo" value="datos_pax" class="btn btn-sm btn-outline-primary" data-notif-from="pasajeros">
                                            <i class="fas fa-paper-plane" aria-hidden="true"></i> Pedírselo por correo
                                        </button>
                                    @endif
                                </span>
                            </div>
                        @endif
                        <p class="prog-input-hint mb-3">La edad se calcula a la fecha de inicio del viaje. Tarifas: adulto desde {{ \App\Models\Programa::EDAD_ADULTO }} años · niño de {{ \App\Models\Programa::EDAD_NINO_MIN }} a {{ \App\Models\Programa::EDAD_ADULTO - 1 }} · menores de {{ \App\Models\Programa::EDAD_NINO_MIN }} sin costo.</p>

                        <div id="contenedor-paxs" data-pax-list data-next-index="{{ count($paxRows) ? max(array_keys($paxRows)) + 1 : 0 }}">
                            @foreach ($paxRows as $i => $pax)
                                @php $paxModel = ! empty($pax['id']) ? $paxModels->get((int) $pax['id']) : null; @endphp
                                @include('programas.partials.pax-card', ['i' => $i, 'pax' => $pax, 'paxModel' => $paxModel])
                            @endforeach
                        </div>

                        <p class="pf-empty" data-pax-empty @if (count($paxRows)) hidden @endif>
                            <i class="fas fa-user-plus" aria-hidden="true"></i> Aún no hay pasajeros.
                        </p>

                        <button type="button" class="btn btn-outline-primary" data-pax-add>
                            <i class="fas fa-plus" aria-hidden="true"></i> Agregar pasajero
                        </button>

                        <template data-pax-template>
                            @include('programas.partials.pax-card', ['i' => '__INDEX__', 'pax' => [], 'paxModel' => null])
                        </template>
                    </div>
                </div>
            </section>

            {{-- ── ALOJAMIENTO ── --}}
            <section class="tab-pane fade {{ $initialTab === 'alojamiento' ? 'show active' : '' }}" id="pf-alojamiento" role="tabpanel" aria-labelledby="pf-tab-alojamiento">
                <input type="hidden" name="_estadias" value="1">
                <div class="prog-section" data-estadias data-tarifa-url="{{ route('tarifas.sugerir') }}" data-next-index="{{ count($estadiaRows) ? max(array_keys($estadiaRows)) + 1 : 0 }}">
                    <div class="prog-section__header">
                        <span class="prog-section__title">Habitaciones del grupo</span>
                        <span class="prog-section__subtitle">Una fila por tipo de habitación y estadía. Con fechas, cada fila crea su servicio para reservarlo y pagarlo.</span>
                    </div>
                    <div class="prog-section__body">
                        @if ($hoteles->isEmpty())
                            <p class="text-muted small mb-0">No hay hoteles. <a href="{{ route('hotel.index') }}">Crear hoteles</a></p>
                        @else
                            <p class="estadia-empty" data-estadias-empty @if (count($estadiaRows)) hidden @endif>Aún no hay habitaciones asignadas.</p>
                            <div class="estadia-list" data-estadias-list>
                                @foreach ($estadiaRows as $i => $estadia)
                                    @include('programas.partials.estadia-row', ['i' => $i, 'e' => $estadia])
                                @endforeach
                            </div>
                            <template data-estadias-template>
                                @include('programas.partials.estadia-row', ['i' => '__INDEX__', 'e' => []])
                            </template>
                            <div class="estadia-foot">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-estadias-add><i class="fas fa-plus" aria-hidden="true"></i> Agregar habitación</button>
                                <p class="estadia-resumen" data-estadias-resumen aria-live="polite"></p>
                            </div>
                            @php $hotelesEstadia = $isEdit ? $programa->habitacionesFechas->whereNotNull('fecha_ingreso')->map(fn ($e) => $e->habitacion?->hotel)->filter()->unique('id') : collect(); @endphp
                            @if ($hotelesEstadia->isNotEmpty())
                                <p class="estadia-listas">
                                    <span>Lista de huéspedes para el hotel:</span>
                                    @foreach ($hotelesEstadia as $hotelEstadia)
                                        <a href="{{ route('programas.rooming', [$programa, 'hotel' => $hotelEstadia->id]) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file-pdf" aria-hidden="true"></i> {{ $hotelEstadia->nombre }}</a>
                                    @endforeach
                                </p>
                            @endif
                        @endif
                    </div>
                </div>
            </section>

            {{-- ── ITINERARIO (solo edición): días con su texto para el cliente y los servicios a operar ── --}}
            @if ($isEdit)
                <section class="tab-pane fade {{ $initialTab === 'itinerario' ? 'show active' : '' }}" id="pf-itinerario" role="tabpanel" aria-labelledby="pf-tab-itinerario"
                    data-itinerario data-next-index="{{ count($servicioRows) ? max(array_keys($servicioRows)) + 1 : 0 }}">
                    <input type="hidden" name="_itinerario" value="1">

                    <div class="itin-summary">
                        <div class="itin-summary__stats">
                            <span><strong>{{ $operacion['servicios'] }}</strong> servicios</span>
                            <span class="itin-summary__ok"><i class="fas fa-check-circle" aria-hidden="true"></i> {{ $operacion['confirmados'] }} confirmados</span>
                            @if ($operacion['abiertos'])
                                <span class="itin-summary__warn"><i class="fas fa-hourglass-half" aria-hidden="true"></i> {{ $operacion['abiertos'] }} por confirmar</span>
                            @endif
                        </div>
                        <p class="prog-input-hint mb-0">El cliente ve el título y el texto de cada día y los servicios marcados como visibles. Proveedor, estado, confirmación y notas son internos.</p>
                    </div>

                    @if ($itinerario['dias']->isEmpty())
                        <div class="prog-section">
                            <div class="admin-empty">
                                <i class="fas fa-calendar-plus" aria-hidden="true"></i>
                                Define las fechas de inicio y fin en <strong>Datos</strong> y guarda para armar el itinerario día a día.
                            </div>
                        </div>
                    @endif

                    @foreach ($itinerario['dias'] as $dia)
                        @php $filas = $serviciosPorDia->get($dia['clave'], collect()); @endphp
                        <article class="itin-day">
                            <header class="itin-day__header">
                                <span class="itin-day__num">Día {{ $dia['numero'] }}</span>
                                <span class="itin-day__date">{{ ucfirst($dia['fecha']->locale('es')->isoFormat('dddd D [de] MMMM')) }}</span>
                                @if ($dia['hotel'])
                                    <span class="itin-day__hotel"><i class="fas fa-moon" aria-hidden="true"></i> {{ $dia['hotel'] }}</span>
                                @endif
                            </header>
                            <div class="itin-day__body">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-5">
                                        <label class="prog-label" for="dia-{{ $dia['clave'] }}-titulo">Título del día</label>
                                        <input id="dia-{{ $dia['clave'] }}-titulo" type="text" name="dias[{{ $dia['clave'] }}][titulo]" maxlength="255"
                                            value="{{ old('dias.' . $dia['clave'] . '.titulo', $dia['dia']?->titulo) }}"
                                            class="form-control form-control-sm" placeholder="Ej. Llegada a Cusco y city tour">
                                    </div>
                                    <div class="col-md-7">
                                        <label class="prog-label" for="dia-{{ $dia['clave'] }}-desc">Descripción para el cliente</label>
                                        <textarea id="dia-{{ $dia['clave'] }}-desc" name="dias[{{ $dia['clave'] }}][descripcion]" rows="2" maxlength="3000"
                                            class="form-control form-control-sm">{{ old('dias.' . $dia['clave'] . '.descripcion', $dia['dia']?->descripcion) }}</textarea>
                                    </div>
                                </div>

                                <div class="itin-day__services" data-svc-list>
                                    @foreach ($filas as $i => $fila)
                                        @include('programas.partials.servicio-row', ['i' => $i, 's' => $fila])
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-svc-add data-fecha="{{ $dia['clave'] }}">
                                    <i class="fas fa-plus" aria-hidden="true"></i> Agregar servicio
                                </button>
                            </div>
                        </article>
                    @endforeach

                    @if ($serviciosPorDia->has('fuera'))
                        <article class="itin-day itin-day--outside">
                            <header class="itin-day__header">
                                <span class="itin-day__num"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Fuera de las fechas</span>
                                <span class="itin-day__date">Cambia su fecha en «Detalles» o quítalos</span>
                            </header>
                            <div class="itin-day__body">
                                <div class="itin-day__services" data-svc-list>
                                    @foreach ($serviciosPorDia->get('fuera') as $i => $fila)
                                        @include('programas.partials.servicio-row', ['i' => $i, 's' => $fila])
                                    @endforeach
                                </div>
                            </div>
                        </article>
                    @endif

                    <template data-svc-template>
                        @include('programas.partials.servicio-row', ['i' => '__INDEX__', 's' => ['fecha' => '__FECHA__', 'estado' => 'pendiente', 'visible_cliente' => true]])
                    </template>
                </section>
            @endif

            {{-- ── PRECIOS ── --}}
            <section class="tab-pane fade {{ $initialTab === 'precios' ? 'show active' : '' }}" id="pf-precios" role="tabpanel" aria-labelledby="pf-tab-precios">
                <div class="pf-pricing">
                    <div class="pf-pricing__main">
                    <div class="prog-section">
                        <div class="prog-section__header">
                            <span class="prog-section__title">{{ $verCotizacion ? 'Costo por persona' : 'Precio de venta por persona' }}</span>
                        </div>
                        <div class="prog-section__body">
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <label for="pf-precio-adulto" class="prog-label">Adulto ($)</label>
                                    <input id="pf-precio-adulto" type="number" name="precioAdulto" value="{{ $verCotizacion ? old('precioAdulto', $programa->precioAdulto) : ($programa->exists ? $programa->presupuesto()['ventaAdulto'] : '') }}"
                                        step="0.01" min="0" placeholder="0.00" class="form-control @error('precioAdulto') is-invalid @enderror" data-precio-adulto>
                                    @error('precioAdulto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4">
                                    <label for="pf-precio-nino" class="prog-label">Niño 5–11 ($)</label>
                                    <input id="pf-precio-nino" type="number" name="precioChild" value="{{ $verCotizacion ? old('precioChild', $programa->precioChild) : ($programa->exists ? $programa->presupuesto()['ventaNino'] : '') }}"
                                        step="0.01" min="0" placeholder="0.00" class="form-control @error('precioChild') is-invalid @enderror" data-precio-nino>
                                    @error('precioChild')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                @if ($verCotizacion)
                                <div class="col-sm-4">
                                    <label for="pf-ganancia" class="prog-label">Ganancia (%)</label>
                                    <input id="pf-ganancia" type="number" name="porcentaje_ganancia" value="{{ old('porcentaje_ganancia', $programa->porcentaje_ganancia ?? 0) }}"
                                        step="0.01" min="0" placeholder="15" class="form-control @error('porcentaje_ganancia') is-invalid @enderror" data-ganancia>
                                    @error('porcentaje_ganancia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                @else
                                    <input type="hidden" value="0" data-ganancia>
                                @endif
                            </div>
                            @if ($verCotizacion)
                            <p class="prog-input-hint mt-3 mb-0">
                                El cliente ve el <strong>precio de venta</strong> (costo + ganancia) en el itinerario y el PDF; el desglose interno solo aparece en el panel.
                            </p>
                            @endif
                        </div>
                    </div>

                    <div class="prog-section">
                        <div class="prog-section__header">
                            <span class="prog-section__title">Suplemento individual y tipo de cambio</span>
                        </div>
                        <div class="prog-section__body">
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <label for="pf-sup" class="prog-label">Suplemento por hab. individual ($)</label>
                                    <input id="pf-sup" type="number" name="suplemento_individual" value="{{ old('suplemento_individual', (float) $programa->suplemento_individual ?: '') }}"
                                        step="0.01" min="0" placeholder="0.00" class="form-control @error('suplemento_individual') is-invalid @enderror" data-suplemento>
                                    @error('suplemento_individual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4">
                                    <label for="pf-singles" class="prog-label">Habitaciones individuales</label>
                                    <input id="pf-singles" type="number" name="habitaciones_individuales" value="{{ old('habitaciones_individuales', $programa->habitaciones_individuales ?: '') }}"
                                        step="1" min="0" max="50" placeholder="0" class="form-control @error('habitaciones_individuales') is-invalid @enderror" data-singles>
                                    @error('habitaciones_individuales')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-4">
                                    <label for="pf-tc" class="prog-label">Tipo de cambio (S/ por US$)</label>
                                    <input id="pf-tc" type="number" name="tipo_cambio" value="{{ old('tipo_cambio', $programa->tipo_cambio) }}" step="0.001" min="0.5"
                                        placeholder="{{ number_format(\App\Models\Programa::TIPO_CAMBIO_DEFECTO, 3) }}" class="form-control @error('tipo_cambio') is-invalid @enderror">
                                    @error('tipo_cambio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <p class="prog-input-hint mt-3 mb-0">El suplemento es precio de venta (no se le suma la ganancia). El tipo de cambio convierte a dólares los costos en soles.</p>
                            @if ($isEdit && ($individuales = $programa->individualesSegunAlojamiento()) !== (int) $programa->habitaciones_individuales)
                                <p class="prog-input-hint text-warning mt-1 mb-0"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Según Alojamiento hay {{ $individuales }} {{ $individuales === 1 ? 'habitación individual' : 'habitaciones individuales' }}; aquí figuran {{ (int) $programa->habitaciones_individuales }}.</p>
                            @endif
                        </div>
                    </div>

                    @if ($isEdit && $verMargen)
                        <div class="prog-section pf-costeo">
                            <div class="prog-section__header">
                                <span class="prog-section__title">Costo y margen</span>
                                <span class="prog-section__subtitle">Cotizado (costos estimados) frente a actual (costo real de cada proveedor o, si aún no hay, el estimado)</span>
                            </div>
                            <div class="prog-section__body">
                                @php $bajaMargen = $costeo['margen'] + 0.009 < $costeo['margenCotizado']; @endphp
                                <dl class="pf-costeo__grid">
                                    <div><dt>Venta</dt><dd>{{ $money($costeo['venta']) }}</dd></div>
                                    <div><dt>Costo cotizado</dt><dd>{{ $money($costeo['costoEstimado']) }}</dd></div>
                                    <div class="{{ $bajaMargen ? 'is-warning' : '' }}"><dt>Costo actual</dt><dd>{{ $money($costeo['costo']) }}</dd></div>
                                    <div class="{{ $costeo['margen'] < 0 ? 'is-negative' : ($bajaMargen ? 'is-warning' : 'is-positive') }}">
                                        <dt>Margen actual</dt><dd>{{ $money($costeo['margen']) }} <small>{{ number_format($costeo['margenPct'], 1) }}%</small></dd>
                                        @if ($bajaMargen)<small class="pf-costeo__was">cotizado {{ number_format($costeo['margenCotizadoPct'], 1) }}%</small>@endif
                                    </div>
                                </dl>
                                @if ($costeo['aumentos']->isNotEmpty())
                                    <div class="pf-costeo__alert">
                                        <strong><i class="fas fa-arrow-up" aria-hidden="true"></i> Costos por encima de lo cotizado</strong>
                                        <ul>
                                            @foreach ($costeo['aumentos'] as $servicio)
                                                <li>{{ $servicio->titulo }} ({{ $servicio->fecha->format('d/m') }}) · {{ $servicio->proveedor?->nombre }}:
                                                    <strong>+{{ \App\Models\Servicio::formatoMonto($servicio->desvio_costo, $servicio->moneda) }}</strong></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if ($costeo['sinProveedor'])
                                    <p class="prog-input-hint mb-1"><i class="fas fa-user-plus" aria-hidden="true"></i>
                                        {{ $costeo['sinProveedor'] }} {{ $costeo['sinProveedor'] === 1 ? 'servicio sin proveedor asignado' : 'servicios sin proveedor asignado' }}:
                                        su costo actual es el estimado. <a href="{{ route('reservas.index', ['bandeja' => 'sin_asignar', 'q' => $programa->codigo, 'periodo' => 'todos']) }}">Asignar en Reservas</a></p>
                                @endif
                                @if ($costeo['sinCosto'])
                                    <p class="prog-input-hint text-warning mb-1"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                                        {{ $costeo['sinCosto'] }} {{ $costeo['sinCosto'] === 1 ? 'servicio no tiene' : 'servicios no tienen' }} costo (ni estimado ni real): el margen puede ser menor.</p>
                                @endif
                                @if ($costeo['porPagar'] > 0)
                                    <p class="prog-input-hint mb-0">Pendiente de pago a proveedores: <strong>{{ $money($costeo['porPagar']) }}</strong> ·
                                        <a href="{{ route('cuentas.index') }}">Cuentas por pagar</a></p>
                                @endif
                            </div>
                        </div>
                    @endif

                    </div>

                    {{-- Vista previa en vivo (resources/js/programa-form.js) --}}
                    <aside class="pf-budget" aria-live="polite">
                        <h2 class="pf-budget__title">Presupuesto</h2>
                        <dl class="pf-budget__rows">
                            <dt>Adultos <span data-b="adultos">0</span> × <span data-b="ventaAdulto">$0.00</span></dt><dd data-b="subAdultos">$0.00</dd>
                            <dt>Niños <span data-b="ninos">0</span> × <span data-b="ventaNino">$0.00</span></dt><dd data-b="subNinos">$0.00</dd>
                            <dt>Infantes <span data-b="infantes">0</span></dt><dd>Sin costo</dd>
                            <dt>Suplemento individual × <span data-b="singles">0</span></dt><dd data-b="suplemento">$0.00</dd>
                        </dl>
                        <p class="pf-budget__total"><span>Total al cliente</span><strong data-b="total">$0.00</strong></p>
                        @if ($verCotizacion)
                        <p class="pf-budget__internal">Costo <span data-b="costo">$0.00</span> · Ganancia <span data-b="ganancia">$0.00</span></p>
                        @endif
                        <p class="pf-budget__deposit">Depósito requerido ({{ \App\Models\Programa::PORCENTAJE_DEPOSITO }}%): <strong data-b="deposito">$0.00</strong></p>
                    </aside>
                </div>
            </section>
        </div>
        </fieldset>
    </form>

    {{-- ── PAGOS (solo edición; formularios propios, fuera del principal) ── --}}
    @if ($isEdit)
        <div class="tab-content">
            <section class="tab-pane fade {{ $initialTab === 'pagos' ? 'show active' : '' }}" id="pf-pagos" role="tabpanel" aria-labelledby="pf-tab-pagos">
                <div class="pf-cobro">
                    <div class="pf-cobro__kpi">
                        <span>Total</span><strong>{{ $money($cobro['total']) }}</strong>
                    </div>
                    <div class="pf-cobro__kpi">
                        <span>Pagado</span><strong class="text-success">{{ $money($cobro['pagado']) }}</strong>
                    </div>
                    <div class="pf-cobro__kpi {{ $cobro['saldoVencido'] ? 'is-overdue' : '' }}">
                        <span>Saldo</span><strong>{{ $money($cobro['saldo']) }}</strong>
                        @if ($cobro['vencimientoSaldo'] && $cobro['saldo'] > 0)
                            <small>{{ $cobro['saldoVencido'] ? 'Vencido el' : 'Vence el' }} {{ $cobro['vencimientoSaldo']->locale('es')->isoFormat('D MMM YYYY') }}</small>
                        @endif
                    </div>
                    <div class="pf-cobro__progress" role="progressbar" aria-label="Porcentaje pagado"
                        aria-valuenow="{{ $cobro['porcentaje'] }}" aria-valuemin="0" aria-valuemax="100">
                        <span class="pf-cobro__bar" data-fill="{{ (int) (round($cobro['porcentaje'] / 5) * 5) }}"></span>
                        <small>{{ $cobro['porcentaje'] }}% pagado · depósito {{ $cobro['depositoCubierto'] ? 'cubierto' : 'pendiente (' . $money($cobro['deposito']) . ')' }}
                            @if (! $cobro['depositoCubierto'] && $cobro['vencimientoDeposito'])
                                · <span class="{{ $cobro['depositoVencido'] ? 'text-danger fw-semibold' : '' }}">{{ $cobro['depositoVencido'] ? 'venció' : 'vence' }} el {{ $cobro['vencimientoDeposito']->locale('es')->isoFormat('D MMM') }}</span>
                            @endif
                        </small>
                    </div>
                </div>

                <div class="prog-section">
                    <div class="prog-section__header">
                        <span class="prog-section__title">Pagos registrados</span>
                    </div>
                    @if ($programa->pagos->isEmpty())
                        <div class="admin-empty"><i class="fas fa-receipt" aria-hidden="true"></i> Todavía no hay pagos.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table ae-admin-data-table mb-0">
                                <thead>
                                    <tr><th>Fecha</th><th>Tipo</th><th>Método</th><th>Referencia</th><th class="text-end">Monto</th><th></th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($programa->pagos as $pago)
                                        <tr>
                                            <td>{{ $pago->fecha->format('d/m/Y') }}</td>
                                            <td>{{ \App\Models\Pago::TIPOS[$pago->tipo] ?? $pago->tipo }}</td>
                                            <td>{{ \App\Models\Pago::METODOS[$pago->metodo] ?? '—' }}</td>
                                            <td>{{ $pago->referencia ?: '—' }}@if ($pago->nota)<small class="d-block text-muted">{{ $pago->nota }}</small>@endif</td>
                                            <td class="text-end fw-semibold">{{ $money($pago->monto) }}</td>
                                            <td class="text-end">
                                                @if ($canCobros)
                                                    <form action="{{ route('programas.pagos.destroy', [$programa, $pago]) }}" method="POST"
                                                        data-confirm-motivo data-confirm="Se anulará el pago de {{ $money($pago->monto) }}. Dejará de contar para el saldo y quedará como constancia." data-confirm-title="¿Anular pago?"
                                                        data-confirm-button="Sí, anular" data-confirm-danger>
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Anular pago" title="Anular pago"><i class="fas fa-undo" aria-hidden="true"></i></button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                @php $cobrosAnulados = $programa->pagos()->onlyTrashed()->with('anuladoPor')->latest('deleted_at')->get(); @endphp
                @if ($cobrosAnulados->isNotEmpty())
                    <details class="pagos-anulados mb-3">
                        <summary>Pagos anulados ({{ $cobrosAnulados->count() }})</summary>
                        <ul>
                            @foreach ($cobrosAnulados as $anulado)
                                <li>
                                    <del>{{ $money($anulado->monto) }}</del> · {{ $anulado->fecha->format('d/m/Y') }} · {{ \App\Models\Pago::TIPOS[$anulado->tipo] ?? $anulado->tipo }}
                                    <small>Motivo: {{ $anulado->motivo_anulacion ?: 'no indicado' }}</small>
                                    <small>Anuló {{ $anulado->anuladoPor?->name ?? '—' }} el {{ $anulado->deleted_at->format('d/m/Y H:i') }}</small>
                                </li>
                            @endforeach
                        </ul>
                    </details>
                @endif

                @error('motivo', 'pago')
                    <div class="alert alert-danger" role="alert">{{ $message }}</div>
                @enderror

                @if ($canCobros)
                    <form action="{{ route('programas.pagos.store', $programa) }}" method="POST" class="prog-section">
                        @csrf
                        <div class="prog-section__header">
                            <span class="prog-section__title">Registrar pago</span>
                        </div>
                        <div class="prog-section__body">
                            <div class="row g-3">
                                <div class="col-sm-6 col-lg-2">
                                    <label for="pago-fecha" class="prog-label">Fecha <span class="required">*</span></label>
                                    <input id="pago-fecha" type="date" name="fecha" value="{{ old('fecha', now()->toDateString()) }}" required
                                        class="form-control @error('fecha', 'pago') is-invalid @enderror">
                                    @error('fecha', 'pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-6 col-lg-2">
                                    <label for="pago-monto" class="prog-label">Monto ($) <span class="required">*</span></label>
                                    <input id="pago-monto" type="number" name="monto" step="0.01" min="0.01" required
                                        value="{{ old('monto', $cobro['saldo'] > 0 ? number_format(! $cobro['depositoCubierto'] ? $cobro['deposito'] - $cobro['pagado'] : $cobro['saldo'], 2, '.', '') : '') }}"
                                        class="form-control @error('monto', 'pago') is-invalid @enderror">
                                    @error('monto', 'pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-6 col-lg-2">
                                    <label for="pago-tipo" class="prog-label">Tipo</label>
                                    <select id="pago-tipo" name="tipo" class="form-select">
                                        @foreach (\App\Models\Pago::TIPOS as $value => $label)
                                            <option value="{{ $value }}" @selected(old('tipo', $cobro['depositoCubierto'] ? 'saldo' : 'deposito') === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-lg-2">
                                    <label for="pago-metodo" class="prog-label">Método</label>
                                    <select id="pago-metodo" name="metodo" class="form-select">
                                        <option value="">—</option>
                                        @foreach (\App\Models\Pago::METODOS as $value => $label)
                                            <option value="{{ $value }}" @selected(old('metodo') === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label for="pago-ref" class="prog-label">Referencia / nota</label>
                                    <input id="pago-ref" type="text" name="referencia" value="{{ old('referencia') }}" maxlength="255"
                                        class="form-control" placeholder="N.º de operación, banco…">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-plus" aria-hidden="true"></i> Registrar pago</button>
                        </div>
                    </form>
                @endif

                <div class="prog-section">
                    <div class="prog-section__header">
                        <span class="prog-section__title">Correos al cliente</span>
                        <span class="prog-section__subtitle">Se envían solo desde aquí; el sistema marca el que corresponde según los plazos</span>
                    </div>
                    <div class="prog-section__body">
                        @if ($canEdit)
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach (\App\Models\Notificacion::TIPOS as $tipo => $etiqueta)
                                    @php $sugerido = in_array($tipo, $correosSugeridos, true); @endphp
                                    <button type="submit" form="notif-form" name="tipo" value="{{ $tipo }}" @disabled(! $programa->email)
                                        class="btn btn-sm {{ $sugerido ? 'btn-primary' : 'btn-outline-secondary' }}">
                                        <i class="fas fa-paper-plane" aria-hidden="true"></i> {{ $etiqueta }}
                                        @if ($sugerido)<span class="badge text-bg-light ms-1">Sugerido</span>@endif
                                    </button>
                                @endforeach
                            </div>
                            @unless ($programa->email)
                                <p class="prog-input-hint text-warning">El cliente no tiene correo: agrégalo en su ficha para poder enviar recordatorios.</p>
                            @endunless
                        @endif
                        @if ($programa->notificaciones->isEmpty())
                            <p class="text-muted small mb-0">Todavía no se enviaron correos.</p>
                        @else
                            <ul class="pf-notifs">
                                @foreach ($programa->notificaciones as $notificacion)
                                    <li>
                                        <strong>{{ \App\Models\Notificacion::TIPOS[$notificacion->tipo] ?? $notificacion->tipo }}</strong>
                                        <span>{{ $notificacion->created_at->locale('es')->isoFormat('D MMM YYYY, HH:mm') }} · {{ $notificacion->email }}
                                            · enviado por {{ $notificacion->enviadaPor?->name ?? 'el equipo' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <form id="notif-form" action="{{ route('programas.notificaciones.store', $programa) }}" method="POST" hidden
                    data-confirm="Se enviará un correo a {{ $programa->email }}." data-confirm-title="¿Enviar correo al cliente?" data-confirm-button="Sí, enviar">
                    @csrf
                    <input type="hidden" name="_tab" value="pagos" data-notif-tab>
                </form>
            </section>

            {{-- ── TAREAS (solo edición; formularios propios) ── --}}
            <section class="tab-pane fade {{ $initialTab === 'tareas' ? 'show active' : '' }}" id="pf-tareas" role="tabpanel" aria-labelledby="pf-tab-tareas">
                <div class="prog-section">
                    <div class="prog-section__header">
                        <span class="prog-section__title">Pendientes del viaje</span>
                        <span class="prog-section__subtitle">{{ $operacion['tareasPendientes'] }} pendientes{{ $operacion['tareasVencidas'] ? ' · ' . $operacion['tareasVencidas'] . ' vencidas' : '' }}</span>
                    </div>
                    @if ($programa->tareas->isEmpty())
                        <div class="admin-empty"><i class="fas fa-clipboard-list" aria-hidden="true"></i> Sin tareas. Agrega la lista de control estándar o una tarea propia.</div>
                    @else
                        <ul class="task-list">
                            @foreach ($programa->tareas as $tarea)
                                @php $situacion = $tarea->situacion(); @endphp
                                <li class="task task--{{ $situacion }}">
                                    @if ($canTareas)
                                        <form action="{{ route('programas.tareas.toggle', [$programa, $tarea]) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="task__check" aria-label="{{ $tarea->completada ? 'Reabrir' : 'Marcar como hecha' }}: {{ $tarea->titulo }}">
                                                <i class="{{ $tarea->completada ? 'fas fa-check-square' : 'far fa-square' }}" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="task__check"><i class="{{ $tarea->completada ? 'fas fa-check-square' : 'far fa-square' }}" aria-hidden="true"></i></span>
                                    @endif
                                    <div class="task__body">
                                        <span class="task__title">{{ $tarea->titulo }}</span>
                                        <span class="task__meta">
                                            @if ($tarea->completada)
                                                Hecha {{ $tarea->completada_at->locale('es')->isoFormat('D MMM') }}{{ $tarea->completadaPor ? ' por ' . $tarea->completadaPor->name : '' }}
                                            @elseif ($tarea->fecha_limite)
                                                {{ $situacion === 'vencida' ? 'Venció el' : 'Hasta el' }} {{ $tarea->fecha_limite->locale('es')->isoFormat('D MMM YYYY') }}
                                            @else
                                                Sin fecha límite
                                            @endif
                                            @if ($tarea->notas) · {{ $tarea->notas }}@endif
                                        </span>
                                    </div>
                                    @if ($canTareas)
                                        <form action="{{ route('programas.tareas.destroy', [$programa, $tarea]) }}" method="POST"
                                            data-confirm="Se eliminará «{{ $tarea->titulo }}»." data-confirm-title="¿Eliminar tarea?" data-confirm-button="Sí, eliminar" data-confirm-danger>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link text-danger" aria-label="Eliminar tarea"><i class="fas fa-trash-alt" aria-hidden="true"></i></button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                @if ($canTareas)
                    <div class="row g-3">
                        <div class="col-lg-7">
                            <form action="{{ route('programas.tareas.plantillas', $programa) }}" method="POST" class="prog-section h-100">
                                @csrf
                                <div class="prog-section__header">
                                    <span class="prog-section__title">Lista de control Perú</span>
                                    <span class="prog-section__subtitle">Fecha límite calculada desde el inicio del viaje</span>
                                </div>
                                <div class="prog-section__body">
                                    @error('plantillas')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                                    @php $titulosExistentes = $programa->tareas->pluck('titulo'); @endphp
                                    <div class="task-templates">
                                        @foreach (\App\Models\Tarea::PLANTILLAS as $clave => [$titulo, $diasAntes])
                                            @php $yaEsta = $titulosExistentes->contains($titulo); @endphp
                                            <label class="task-templates__item {{ $yaEsta ? 'is-added' : '' }}">
                                                <input type="checkbox" class="form-check-input" name="plantillas[]" value="{{ $clave }}" @disabled($yaEsta)>
                                                <span>{{ $titulo }}</span>
                                                <small>{{ $diasAntes }} {{ $diasAntes === 1 ? 'día' : 'días' }} antes{{ $yaEsta ? ' · ya agregada' : '' }}</small>
                                            </label>
                                        @endforeach
                                    </div>
                                    @unless ($programa->inicio)
                                        <p class="prog-input-hint mt-2 mb-0"><i class="fas fa-info-circle" aria-hidden="true"></i> Sin fecha de inicio, las tareas se crean sin fecha límite.</p>
                                    @endunless
                                    <button type="submit" class="btn btn-outline-primary mt-3"><i class="fas fa-check-double" aria-hidden="true"></i> Agregar seleccionadas</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-5">
                            <form action="{{ route('programas.tareas.store', $programa) }}" method="POST" class="prog-section h-100">
                                @csrf
                                <div class="prog-section__header">
                                    <span class="prog-section__title">Nueva tarea</span>
                                </div>
                                <div class="prog-section__body">
                                    <label for="tarea-titulo" class="prog-label">Tarea <span class="required">*</span></label>
                                    <input id="tarea-titulo" type="text" name="titulo" value="{{ old('titulo') }}" maxlength="255" required
                                        class="form-control @error('titulo', 'tarea') is-invalid @enderror" placeholder="Ej. Reservar cena de aniversario">
                                    @error('titulo', 'tarea')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div class="row g-2 mt-1">
                                        <div class="col-sm-6">
                                            <label for="tarea-fecha" class="prog-label">Fecha límite</label>
                                            <input id="tarea-fecha" type="date" name="fecha_limite" value="{{ old('fecha_limite') }}" class="form-control">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="tarea-notas" class="prog-label">Nota</label>
                                            <input id="tarea-notas" type="text" name="notas" value="{{ old('notas') }}" maxlength="255" class="form-control">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-plus" aria-hidden="true"></i> Agregar tarea</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </section>

            {{-- ── HISTORIAL ── --}}
            <section class="tab-pane fade {{ $initialTab === 'historial' ? 'show active' : '' }}" id="pf-historial" role="tabpanel" aria-labelledby="pf-tab-historial">
                <div class="prog-section">
                    <div class="prog-section__header">
                        <span class="prog-section__title">Historial del programa</span>
                        <span class="prog-section__subtitle">Quién cambió qué, en todas las áreas.</span>
                    </div>
                    <div class="prog-section__body">
                        @include('movimientos.partials.lista', ['movimientos' => $movimientos, 'conPrograma' => false])
                    </div>
                </div>
            </section>
        </div>
    @endif

    {{-- Barra fija de acciones --}}
    <div class="pf-actionbar" data-actionbar>
        <span class="pf-actionbar__status" data-dirty-indicator hidden><i class="fas fa-circle" aria-hidden="true"></i> Cambios sin guardar</span>
        <div class="pf-actionbar__buttons">
            <a href="{{ route('programas.index') }}" class="btn btn-outline-secondary">{{ $canEdit ? 'Cancelar' : 'Volver' }}</a>
            @if ($canEdit)
                <button type="submit" form="programa-form" class="btn btn-primary" data-save>
                    <i class="fas fa-save" aria-hidden="true"></i> {{ $isEdit ? 'Guardar cambios' : 'Crear programa' }}
                </button>
            @endif
        </div>
    </div>
</div>
