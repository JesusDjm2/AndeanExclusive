@extends('layouts.app')

@section('body-class', 'view-proveedor-show')
@section('template_title')
    Proveedor: {{ $proveedor->nombre ?? 'Detalles del Proveedor' }}
@endsection

@section('contenido')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-truck me-2 text-primary"></i>
                                    Proveedor: <strong>{{ $proveedor->nombre }}</strong>
                                </h3>
                                <p class="text-muted mb-0 mt-1">
                                    <small>ID: {{ $proveedor->id }} | Registrado: {{ $proveedor->created_at->format('d/m/Y') }}</small>
                                </p>
                            </div>
                            <div class="btn-group">
                                @can('proveedores.gestionar')
                                    <a href="{{ route('proveedors.edit', $proveedor->id) }}" class="btn btn-warning me-2">
                                        <i class="fas fa-edit"></i> Editar Proveedor
                                    </a>
                                @endcan
                                <a href="{{ route('proveedors.index') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="bg-light" width="25%"><i class="fas fa-user-tag me-2 text-muted"></i>Nombre</th>
                                                <td><span class="fw-bold">{{ $proveedor->nombre }}</span></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light"><i class="fas fa-tags me-2 text-muted"></i>Categoría</th>
                                                <td><span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">{{ $proveedor->categoria->nombre ?? 'Sin categoría' }}</span></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light"><i class="fas fa-id-card me-2 text-muted"></i>RUC</th>
                                                <td><span>{{ $proveedor->ruc ?: 'No registrado' }}</span></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light"><i class="fas fa-map-marker-alt me-2 text-muted"></i>Dirección</th>
                                                <td>{{ $proveedor->direccion ?? 'No especificada' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light"><i class="fas fa-phone me-2 text-muted"></i>Teléfono</th>
                                                <td>{{ $proveedor->telefono ?? 'No especificado' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light"><i class="fas fa-envelope me-2 text-muted"></i>Correo</th>
                                                <td>
                                                    @if ($proveedor->correo)
                                                        <a href="mailto:{{ $proveedor->correo }}" class="text-decoration-none">{{ $proveedor->correo }}</a>
                                                    @else
                                                        <span class="text-muted">No especificado</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light"><i class="fas fa-info-circle me-2 text-muted"></i>Detalles</th>
                                                <td>
                                                    @if ($proveedor->detalles)
                                                        {!! nl2br(e($proveedor->detalles)) !!}
                                                    @else
                                                        <span class="text-muted">Sin observaciones adicionales.</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4 p-3 border-start bg-light">
                                <div class="p-3 bg-white rounded border mb-3">
                                    <h6 class="text-muted text-uppercase small fw-bold mb-2"><i class="fas fa-coins me-1 text-warning"></i> Resumen de Tarifas</h6>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Tarifas registradas:</span>
                                        <strong>{{ $proveedor->tarifas->count() }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Tarifas fijas (todo el año):</span>
                                        <span class="text-success fw-medium">{{ $proveedor->tarifas->filter(fn($t) => $t->es_fija || (!$t->desde && !$t->hasta))->count() }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Tarifas por temporada:</span>
                                        <span class="text-primary fw-medium">{{ $proveedor->tarifas->filter(fn($t) => !$t->es_fija && $t->desde)->count() }}</span>
                                    </div>
                                </div>
                                <div class="small text-muted">
                                    <p class="mb-1"><i class="fas fa-clock me-1"></i> <strong>Creado:</strong> {{ $proveedor->created_at->format('d/m/Y H:i') }}</p>
                                    <p class="mb-0"><i class="fas fa-history me-1"></i> <strong>Último cambio:</strong> {{ $proveedor->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Si es un Hotel: Sección Exclusiva de Alojamiento y Habitaciones Boutique --}}
                @if ($proveedor->es_hotel)
                    <div class="card card-outline card-info shadow-sm mb-4 border-0 rounded-3 overflow-hidden">
                        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h4 class="card-title mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                    <i class="fas fa-hotel text-info"></i>
                                    Alojamiento & Habitaciones Boutique
                                </h4>
                                <small class="text-muted">Tipos de habitación para este hotel, sus capacidades y las tarifas asignadas.</small>
                            </div>
                            @can('proveedores.gestionar')
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalFotoHotel">
                                        <i class="fas fa-camera me-1"></i> Foto de portada
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalNuevaHabitacion">
                                        <i class="fas fa-plus me-1"></i> Nueva Habitación
                                    </button>
                                </div>
                            @endcan
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                @if ($proveedor->hotel?->img)
                                    <div class="col-md-3">
                                        <div class="position-relative rounded overflow-hidden shadow-sm border" style="height: 160px;">
                                            <img src="{{ asset($proveedor->hotel->img) }}" alt="{{ $proveedor->nombre }}"
                                                class="w-100 h-100" style="object-fit: cover; cursor: pointer;"
                                                data-bs-toggle="modal" data-bs-target="#modalFotoPreview">
                                            <span class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 text-white small px-2 py-1 text-center">
                                                <i class="fas fa-expand me-1"></i> Ver foto
                                            </span>
                                        </div>
                                    </div>
                                @endif
                                <div class="{{ $proveedor->hotel?->img ? 'col-md-9' : 'col-12' }}">
                                    @php
                                        $habitaciones = $proveedor->hotel?->habitaciones ?? collect();
                                    @endphp
                                    @if ($habitaciones->isEmpty())
                                        <div class="p-4 text-center text-muted bg-light rounded border">
                                            <i class="fas fa-bed fa-2x text-secondary opacity-50 mb-2"></i>
                                            <p class="mb-2 fw-medium">No hay tipos de habitación registrados todavía para este hotel.</p>
                                            <small class="text-muted d-block mb-3">Registra habitaciones (ej. Standard, Suite, Matrimonial) para que Operaciones pueda elegirlas al armar un viaje.</small>
                                            @can('proveedores.gestionar')
                                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalNuevaHabitacion">
                                                    <i class="fas fa-plus me-1"></i> Agregar primera habitación
                                                </button>
                                            @endcan
                                        </div>
                                    @else
                                        <div class="row g-2">
                                            @foreach ($habitaciones as $hab)
                                                @php
                                                    $tarifasHab = $proveedor->tarifas->where('habitacion_id', $hab->id);
                                                @endphp
                                                <div class="col-sm-6 col-lg-4">
                                                    <div class="p-3 bg-light rounded-3 border h-100 d-flex flex-column justify-content-between">
                                                        <div>
                                                            <div class="d-flex justify-content-between align-items-start gap-1 mb-1">
                                                                <h6 class="fw-bold text-dark mb-0 fs-6">{{ $hab->tipo }}</h6>
                                                                @can('proveedores.gestionar')
                                                                    <form action="{{ route('proveedors.habitaciones.destroy', [$proveedor->id, $hab->id]) }}" method="POST"
                                                                        onsubmit="return confirm('¿Seguro que deseas eliminar la habitación «{{ $hab->tipo }}»?');" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-link text-danger p-0 border-0" title="Eliminar habitación">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                    </form>
                                                                @endcan
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                                <span class="badge bg-secondary-subtle text-secondary border">
                                                                    <i class="fas {{ $hab->capacidad > 1 ? 'fa-user-friends' : 'fa-user' }} me-1"></i>
                                                                    {{ $hab->capacidad }} {{ $hab->capacidad === 1 ? 'persona' : 'personas' }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="pt-2 border-top mt-2">
                                                            @if ($tarifasHab->isNotEmpty())
                                                                @foreach ($tarifasHab as $tarifaHab)
                                                                    <div class="d-flex justify-content-between align-items-center small mb-1">
                                                                        <span class="text-muted text-truncate" style="max-width: 140px;" title="{{ $tarifaHab->temporada_texto }}">
                                                                            {{ $tarifaHab->es_fija ? 'Fija' : ($tarifaHab->nombre_especial ?: 'Temporada') }}:
                                                                        </span>
                                                                        <strong class="text-success font-monospace">
                                                                            {{ \App\Models\Servicio::formatoMonto((float) $tarifaHab->precio, $tarifaHab->moneda) }}
                                                                        </strong>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <small class="text-muted fst-italic">Sin tarifa asignada</small>
                                                                    @can('proveedores.gestionar')
                                                                        <button type="button" class="btn btn-xs btn-outline-success py-0 px-2 btn-tarifa-rapida-hab"
                                                                            data-hab-id="{{ $hab->id }}" data-hab-tipo="{{ $hab->tipo }}">
                                                                            + Tarifa
                                                                        </button>
                                                                    @endcan
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Tarifario y Servicios Propios de este Proveedor --}}
                <div class="card card-outline card-success shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                        <div>
                            <h4 class="card-title mb-0 fw-bold text-dark">
                                <i class="fas fa-tags text-success me-2"></i>Servicios y Tarifario de {{ $proveedor->nombre }}
                            </h4>
                            <small class="text-muted">Lista de precios fijos por servicio (medio día, día completo, ruta, etc.) y temporadas para cotizaciones automáticas.</small>
                        </div>
                        @can('proveedores.gestionar')
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaTarifa">
                                    <i class="fas fa-plus me-1"></i> Agregar Tarifa / Servicio
                                </button>
                                <a href="{{ route('tarifas.create', ['proveedor_id' => $proveedor->id, 'retorno' => 'proveedor']) }}" class="btn btn-outline-secondary btn-sm" title="Formulario completo">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        @endcan
                    </div>
                    <div class="card-body p-0">
                        @if ($proveedor->tarifas->isEmpty())
                            <div class="p-4 text-center text-muted">
                                <i class="fas fa-hand-holding-usd fa-3x mb-3 text-secondary opacity-50"></i>
                                <h5 class="fw-normal">No hay tarifas registradas para este proveedor</h5>
                                <p class="small text-muted mb-3">Registra los precios que cobra por sus servicios (ej. medio día, día completo, traslados, tipo de vehículo o menús) para que aparezcan automáticamente al armar cotizaciones.</p>
                                @can('proveedores.gestionar')
                                    <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaTarifa">
                                        <i class="fas fa-plus me-1"></i> Cargar primera tarifa fija
                                    </button>
                                @endcan
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Servicio / Concepto</th>
                                            <th>Detalle / Unidad</th>
                                            <th>Vigencia / Tipo</th>
                                            <th>Modalidad</th>
                                            <th class="text-end">Costo / Precio</th>
                                            <th>Notas</th>
                                            @can('proveedores.gestionar')
                                                <th class="text-end" style="width: 110px;">Acciones</th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($proveedor->tarifas as $tarifa)
                                            <tr>
                                                <td class="fw-semibold text-dark">
                                                    {{ $tarifa->concepto }}
                                                    @if ($tarifa->habitacion)
                                                        <div class="mt-1">
                                                            <span class="badge rounded-pill" style="background: rgba(6, 182, 212, 0.12); color: #0891b2; border: 1px solid rgba(6, 182, 212, 0.3); font-size: 0.72rem;">
                                                                <i class="fas fa-bed me-1"></i> Habitación: {{ $tarifa->habitacion->tipo }} ({{ $tarifa->habitacion->capacidad }} pax)
                                                            </span>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($tarifa->unidad_detalle)
                                                        <span class="badge bg-secondary-subtle text-secondary border">{{ $tarifa->unidad_detalle }}</span>
                                                    @else
                                                        <span class="text-muted small">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($tarifa->es_especial)
                                                        <span class="badge bg-warning-subtle text-dark border border-warning" title="{{ $tarifa->nombre_especial ?: 'Fecha especial' }}">
                                                            <i class="fas fa-star text-warning me-1"></i> {{ $tarifa->nombre_especial ?: 'Especial' }}: {{ $tarifa->desde?->format('d/m/Y') }} – {{ $tarifa->hasta?->format('d/m/Y') }}
                                                        </span>
                                                    @elseif ($tarifa->es_fija || (! $tarifa->desde && ! $tarifa->hasta))
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                            <i class="fas fa-infinity me-1"></i> Fija todo el año
                                                        </span>
                                                    @else
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                                            <i class="fas fa-calendar-alt me-1"></i> {{ $tarifa->desde?->format('d/m/Y') }} – {{ $tarifa->hasta?->format('d/m/Y') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="text-muted small">{{ ucfirst(\App\Models\Tarifa::POR[$tarifa->por] ?? $tarifa->por) }}</span>
                                                </td>
                                                <td class="text-end fw-bold">
                                                    {{ \App\Models\Servicio::formatoMonto((float) $tarifa->precio, $tarifa->moneda) }}
                                                </td>
                                                <td class="small text-muted">
                                                    {{ $tarifa->notas ?: '—' }}
                                                </td>
                                                @can('proveedores.gestionar')
                                                    <td class="text-end text-nowrap">
                                                        <a href="{{ route('tarifas.edit', [$tarifa->id, 'retorno' => 'proveedor']) }}" class="btn btn-sm btn-outline-primary" title="Editar tarifa">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <form action="{{ route('tarifas.destroy', [$tarifa->id, 'retorno' => 'proveedor']) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta tarifa?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar tarifa">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                @endcan
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Mensajería con el proveedor: lo último enviado desde reservas@ y sus respuestas. --}}
                @php $ultimosMensajes = $proveedor->mensajes()->with(['servicio:id,titulo,fecha', 'programa:id,codigo', 'user:id,name'])->limit(8)->get(); @endphp
                <div class="card dash-card msg-thread mt-4">
                    <h2 class="msg-card__title">
                        <i class="fas fa-envelope-open-text" aria-hidden="true"></i> Mensajes con el proveedor
                        @if ($ultimosMensajes->isNotEmpty())
                            <a href="{{ route('mensajes.index', ['proveedor_id' => $proveedor->id]) }}" class="msg-card__link">Ver todos</a>
                        @endif
                    </h2>
                    @forelse ($ultimosMensajes as $mensaje)
                        <article class="msg-bubble msg-bubble--{{ $mensaje->direccion }} {{ $mensaje->fallido ? 'is-failed' : '' }}">
                            <header class="msg-bubble__head">
                                <strong>{{ \App\Models\MensajeProveedor::TIPOS[$mensaje->tipo] ?? $mensaje->tipo }}</strong>
                                <time datetime="{{ $mensaje->created_at->toIso8601String() }}" title="{{ $mensaje->created_at->format('d/m/Y H:i') }}">{{ $mensaje->created_at->locale('es')->diffForHumans() }}</time>
                            </header>
                            <p class="msg-bubble__text">
                                {{ $mensaje->direccion === 'recibido' ? (\App\Models\MensajeProveedor::RESULTADOS[$mensaje->resultado] ?? $mensaje->cuerpo) : $mensaje->asunto }}
                            </p>
                            <footer class="msg-bubble__foot">
                                {{ $mensaje->programa?->codigo }}
                                @if ($mensaje->servicio) · <a href="{{ route('servicios.mensaje', $mensaje->servicio) }}">{{ $mensaje->servicio->titulo }}</a>@endif
                                · {{ $mensaje->user?->name ?? 'Sistema' }}
                                @if ($mensaje->fallido)<span class="msg-bubble__error"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> No se envió</span>@endif
                            </footer>
                        </article>
                    @empty
                        <p class="msg-thread__empty">Todavía no hay mensajes. Se escribe al proveedor desde cada servicio en la Bandeja de Reservas.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </section>

    {{-- Modal Carga Rápida de Tarifa / Servicio para este Proveedor --}}
    @can('proveedores.gestionar')
        @php
            $cat = \Illuminate\Support\Str::lower($proveedor->categoria->nombre ?? '');
            $esHotel = $proveedor->es_hotel;
            $esTransporte = str_contains($cat, 'transporte');
            $esGuia = str_contains($cat, 'guia') || str_contains($cat, 'guía');
            $esRestaurante = str_contains($cat, 'restaurante') || str_contains($cat, 'gastr');
            $esEntradasTren = str_contains($cat, 'tren') || str_contains($cat, 'bus') || str_contains($cat, 'entrada') || str_contains($cat, 'boleto');
            $modoDefecto = $esHotel ? 'noche' : (($esRestaurante || $esEntradasTren) ? 'persona' : 'grupo');
            $tipoPorRubro = match (true) {
                $esHotel => 'alojamiento',
                $esTransporte => 'traslado',
                $esGuia => 'guia',
                $esRestaurante => 'comida',
                $esEntradasTren => 'entrada',
                default => ''
            };
        @endphp
        <div class="modal fade" id="modalNuevaTarifa" tabindex="-1" aria-labelledby="modalNuevaTarifaLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <form action="{{ route('tarifas.store') }}" method="POST" id="formNuevaTarifaProveedor">
                        @csrf
                        <input type="hidden" name="tipo" value="proveedor">
                        <input type="hidden" name="proveedor_id" value="{{ $proveedor->id }}">
                        <input type="hidden" name="habitacion_id" id="modal-tar-habitacion-id" value="">
                        <input type="hidden" name="retorno" value="proveedor">

                        <div class="modal-header bg-dark text-white py-3">
                            <div>
                                <h5 class="modal-title fw-bold text-white mb-0" id="modalNuevaTarifaLabel">
                                    <i class="fas fa-tags text-warning me-2"></i>Nueva Tarifa para {{ $proveedor->nombre }}
                                </h5>
                                <small class="text-light opacity-75">
                                    Rubro: <span class="badge bg-secondary">{{ $proveedor->categoria->nombre ?? 'General' }}</span>
                                </small>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>

                        <div class="modal-body p-4">
                            {{-- Chips de Sugerencia Inteligente según Rubro --}}
                            <div class="p-3 bg-light rounded border mb-3">
                                <span class="d-block text-muted small fw-bold text-uppercase mb-2">
                                    <i class="fas fa-magic text-warning me-1"></i> Sugerencias con 1 clic para {{ $proveedor->categoria->nombre ?? 'este proveedor' }}:
                                </span>

                                @if ($esHotel)
                                    @php
                                        $habsHotel = $proveedor->hotel?->habitaciones ?? collect();
                                    @endphp
                                    @if ($habsHotel->isNotEmpty())
                                        <div class="mb-2">
                                            <span class="small text-muted me-1">Vincular a habitación existente:</span>
                                            @foreach ($habsHotel as $habH)
                                                <button type="button" class="btn btn-xs btn-outline-info me-1 mb-1 btn-chip-hotel"
                                                    data-hab-id="{{ $habH->id }}"
                                                    data-hab-tipo="{{ $habH->tipo }}"
                                                    data-concepto="Alojamiento: {{ $proveedor->nombre }} · {{ $habH->tipo }}">
                                                    <i class="fas fa-bed me-1"></i> {{ $habH->tipo }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div>
                                        <span class="small text-muted me-1">Conceptos sugeridos:</span>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="noche" data-tipo="alojamiento" data-val="Alojamiento: {{ $proveedor->nombre }} · Habitación Standard">Standard</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="noche" data-tipo="alojamiento" data-val="Alojamiento: {{ $proveedor->nombre }} · Suite Deluxe">Suite Deluxe</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="noche" data-tipo="alojamiento" data-val="Alojamiento: {{ $proveedor->nombre }} · Junior Suite">Junior Suite</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="noche" data-tipo="alojamiento" data-val="Alojamiento: {{ $proveedor->nombre }} · Suite Presidencial">Presidencial</button>
                                    </div>
                                @elseif ($esTransporte)
                                    <div class="mb-2">
                                        <span class="small text-muted me-1">Tipo de Vehículo:</span>
                                        <button type="button" class="btn btn-xs btn-outline-secondary me-1 mb-1 btn-chip" data-target="unidad" data-val="Van H1 (1-4 pax)">Van H1 (1-4 pax)</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary me-1 mb-1 btn-chip" data-target="unidad" data-val="Sprinter (5-15 pax)">Sprinter (5-15 pax)</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary me-1 mb-1 btn-chip" data-target="unidad" data-val="Renault Master">Renault Master</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary me-1 mb-1 btn-chip" data-target="unidad" data-val="Suburban VIP">Suburban VIP</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary me-1 mb-1 btn-chip" data-target="unidad" data-val="Sedán Privado">Sedán Privado</button>
                                    </div>
                                    <div>
                                        <span class="small text-muted me-1">Rutas frecuentes:</span>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="grupo" data-val="Traslado Aeropuerto ⇄ Hotel Cusco">Aeropuerto ⇄ Hotel</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="grupo" data-val="Valle Sagrado de los Incas Full Day">Valle Sagrado Full Day</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="grupo" data-val="Cusco → Estación Ollantaytambo">Cusco → Ollanta</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="grupo" data-val="City Tour Cusco & 4 Ruinas">City Tour & 4 Ruinas</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="grupo" data-val="Maras, Moray & Salineras">Maras & Moray</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="grupo" data-val="Disposición Vehicular (8 hrs)">Disposición 8 hrs</button>
                                    </div>
                                @elseif ($esGuia)
                                    <div class="mb-2">
                                        <span class="small text-muted me-1">Idiomas:</span>
                                        <button type="button" class="btn btn-xs btn-outline-secondary me-1 mb-1 btn-chip" data-target="unidad" data-val="Español">Español</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary me-1 mb-1 btn-chip" data-target="unidad" data-val="Inglés">Inglés</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary me-1 mb-1 btn-chip" data-target="unidad" data-val="Francés">Francés</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary me-1 mb-1 btn-chip" data-target="unidad" data-val="Alemán">Alemán</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary me-1 mb-1 btn-chip" data-target="unidad" data-val="Portugués">Portugués</button>
                                    </div>
                                    <div>
                                        <span class="small text-muted me-1">Servicios de Guía:</span>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="grupo" data-val="Guía Machu Picchu Full Day">Machu Picchu Full Day</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="grupo" data-val="Guía Valle Sagrado Full Day">Valle Sagrado Full Day</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="grupo" data-val="Guía City Tour Cusco (Medio Día)">City Tour Medio Día</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="grupo" data-val="Guía Maras, Moray & Salineras">Maras & Moray</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="grupo" data-val="Guía Montaña 7 Colores">Montaña 7 Colores</button>
                                    </div>
                                @elseif ($esRestaurante)
                                    <div>
                                        <span class="small text-muted me-1">Experiencias gastronómicas:</span>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="persona" data-val="Almuerzo Buffet">Almuerzo Buffet</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="persona" data-val="Menú Degustación (3 Tiempos)">Menú 3 Tiempos</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="persona" data-val="Menú Degustación (5 Tiempos)">Menú 5 Tiempos</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="persona" data-val="Box Lunch Gourmet">Box Lunch Gourmet</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="persona" data-val="Picnic Lunch de Lujo al Aire Libre">Picnic de Lujo</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="persona" data-val="Cooking Class & Pisco Sour Demonstration">Cooking Class & Pisco</button>
                                    </div>
                                @elseif ($esEntradasTren)
                                    <div>
                                        <span class="small text-muted me-1">Boletos y Trenes:</span>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="persona" data-val="Boleto Turístico del Cusco (BTC Integral)">BTC Integral</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="persona" data-val="Boleto Llaqta Machupicchu (Circuito Clásico)">Llaqta Machupicchu</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="persona" data-val="Bus Consettur Machupicchu (Ida y Vuelta)">Bus Consettur (RT)</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="persona" data-val="Tren Vistadome / 360°">Tren Vistadome</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-mode="persona" data-val="Tren Hiram Bingham de Lujo">Tren Hiram Bingham</button>
                                    </div>
                                @else
                                    <div>
                                        <span class="small text-muted me-1">Conceptos sugeridos:</span>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-val="Servicio Estándar">Servicio Estándar</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-val="Día Completo">Día Completo</button>
                                        <button type="button" class="btn btn-xs btn-outline-primary me-1 mb-1 btn-chip" data-target="concepto" data-val="Medio Día">Medio Día</button>
                                    </div>
                                @endif
                            </div>

                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label for="modal-tar-concepto" class="form-label fw-semibold small">Servicio / Ruta / Habitación <span class="text-danger">*</span></label>
                                    <input type="text" id="modal-tar-concepto" name="concepto" required maxlength="255" class="form-control" placeholder="Ej. Traslado Aeropuerto ⇄ Hotel, Alojamiento Suite Presidencial, etc.">
                                </div>
                                <div class="col-md-3">
                                    <label for="modal-tar-tipo" class="form-label fw-semibold small">Tipo de servicio</label>
                                    <select id="modal-tar-tipo" name="tipo_servicio" class="form-select">
                                        <option value="">Sin especificar</option>
                                        @foreach (\App\Models\Servicio::TIPOS as $clave => [$etiqueta])
                                            @continue(!$esHotel && $clave === 'alojamiento')
                                            <option value="{{ $clave }}" @selected($tipoPorRubro === $clave)>{{ $etiqueta }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="modal-tar-unidad" class="form-label fw-semibold small">Detalle / Unidad (opcional)</label>
                                    <input type="text" id="modal-tar-unidad" name="unidad_detalle" maxlength="100" class="form-control" placeholder="Ej. Van H1, King Bed con Balcón, Desayuno buffet...">
                                </div>

                                {{-- Switch Tarifa Fija vs Temporada --}}
                                <div class="col-12">
                                    <div class="form-check form-switch p-2 bg-light rounded border">
                                        <input class="form-check-input ms-0 me-2" type="checkbox" role="switch" id="modal-tar-es-fija" name="es_fija" value="1" checked>
                                        <label class="form-check-label fw-bold text-dark cursor-pointer" for="modal-tar-es-fija">
                                            <i class="fas fa-infinity text-success me-1"></i> Tarifa fija estándar (Aplica todo el año / Sin fechas de corte)
                                        </label>
                                        <small class="d-block text-muted ms-4">Desmárcalo si solo aplica en ciertas fechas del año (ej. Temporada Alta Inti Raymi o Fiestas Patrias).</small>
                                    </div>
                                </div>

                                <div class="col-sm-6" id="modal-tar-fechas-desde" style="display: none;">
                                    <label for="modal-tar-desde" class="form-label fw-semibold small">Vigencia Desde</label>
                                    <input type="date" id="modal-tar-desde" name="desde" class="form-control">
                                </div>
                                <div class="col-sm-6" id="modal-tar-fechas-hasta" style="display: none;">
                                    <label for="modal-tar-hasta" class="form-label fw-semibold small">Vigencia Hasta</label>
                                    <input type="date" id="modal-tar-hasta" name="hasta" class="form-control">
                                </div>

                                <div class="col-sm-4">
                                    <label for="modal-tar-moneda" class="form-label fw-semibold small">Moneda</label>
                                    <select id="modal-tar-moneda" name="moneda" class="form-select">
                                        @foreach (\App\Models\Servicio::MONEDAS as $clave => $simbolo)
                                            <option value="{{ $clave }}" @selected($clave === 'USD')>{{ $clave }} ({{ $simbolo }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="modal-tar-precio" class="form-label fw-semibold small">Precio / Costo Neto <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" id="modal-tar-precio" name="precio" min="0" step="0.01" required class="form-control fw-bold" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="modal-tar-por" class="form-label fw-semibold small">Modalidad de Cobro</label>
                                    <select id="modal-tar-por" name="por" class="form-select">
                                        <option value="noche" @selected($modoDefecto === 'noche')>Por Noche (Habitación / Alojamiento)</option>
                                        <option value="grupo" @selected($modoDefecto === 'grupo')>Por Grupo (Vehículo / Guía)</option>
                                        <option value="persona" @selected($modoDefecto === 'persona')>Por Persona (Almuerzo / Entrada)</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="modal-tar-notas" class="form-label fw-semibold small">Notas internas / Condiciones (opcional)</label>
                                    <input type="text" id="modal-tar-notas" name="notas" maxlength="255" class="form-control form-control-sm" placeholder="Ej. Mínimo 2 noches, no incluye propinas, incluye desayuno buffet...">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Guardar Tarifa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Nueva Habitación para Hotel --}}
        @if ($proveedor->es_hotel)
            <div class="modal fade" id="modalNuevaHabitacion" tabindex="-1" aria-labelledby="modalNuevaHabitacionLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <form action="{{ route('proveedors.habitaciones.store', $proveedor->id) }}" method="POST">
                            @csrf
                            <div class="modal-header bg-dark text-white py-3">
                                <h5 class="modal-title fw-bold text-white mb-0" id="modalNuevaHabitacionLabel">
                                    <i class="fas fa-bed text-info me-2"></i>Nueva Habitación para {{ $proveedor->nombre }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label for="hab-tipo" class="form-label fw-semibold small">Tipo / Nombre de la Habitación <span class="text-danger">*</span></label>
                                    <input type="text" id="hab-tipo" name="tipo" required maxlength="100" class="form-control" placeholder="Ej. Deluxe King con Balcón, Casita Suite, etc.">
                                </div>
                                <div class="mb-3">
                                    <label for="hab-capacidad" class="form-label fw-semibold small">Capacidad Máxima de Pasajeros <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-users text-muted"></i></span>
                                        <input type="number" id="hab-capacidad" name="capacidad" min="1" max="20" value="2" required class="form-control">
                                        <span class="input-group-text">personas</span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-info text-white">
                                    <i class="fas fa-save me-1"></i> Guardar Habitación
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal Subir / Cambiar Foto de Portada del Hotel --}}
            <div class="modal fade" id="modalFotoHotel" tabindex="-1" aria-labelledby="modalFotoHotelLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <form action="{{ route('proveedors.hotel.foto', $proveedor->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header bg-dark text-white py-3">
                                <h5 class="modal-title fw-bold text-white mb-0" id="modalFotoHotelLabel">
                                    <i class="fas fa-camera text-info me-2"></i>Foto de Portada del Hotel
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label for="hotel-foto-file" class="form-label fw-semibold small">Selecciona una imagen (JPG, PNG o WebP, máx 5MB):</label>
                                    <input type="file" id="hotel-foto-file" name="img" accept=".jpg,.jpeg,.png,.webp" required class="form-control">
                                </div>
                                @if ($proveedor->hotel?->img)
                                    <div class="text-center p-2 bg-light rounded border">
                                        <small class="text-muted d-block mb-2">Foto actual:</small>
                                        <img src="{{ asset($proveedor->hotel->img) }}" alt="Foto actual" class="img-thumbnail" style="max-height: 120px;">
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload me-1"></i> Subir Foto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if ($proveedor->hotel?->img)
                {{-- Modal Preview de la Foto --}}
                <div class="modal fade" id="modalFotoPreview" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow-lg bg-dark">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title text-white fw-bold">{{ $proveedor->nombre }}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body text-center p-3">
                                <img src="{{ asset($proveedor->hotel->img) }}" alt="{{ $proveedor->nombre }}" class="img-fluid rounded">
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = document.getElementById('modalNuevaTarifa');
                if (!modal) return;

                const switchFija = modal.querySelector('#modal-tar-es-fija');
                const desdeBox = modal.querySelector('#modal-tar-fechas-desde');
                const hastaBox = modal.querySelector('#modal-tar-fechas-hasta');
                const inputDesde = modal.querySelector('#modal-tar-desde');
                const inputHasta = modal.querySelector('#modal-tar-hasta');

                const syncFija = () => {
                    const esFija = switchFija.checked;
                    desdeBox.style.display = esFija ? 'none' : 'block';
                    hastaBox.style.display = esFija ? 'none' : 'block';
                    inputDesde.required = !esFija;
                    inputHasta.required = !esFija;
                    if (esFija) {
                        inputDesde.value = '';
                        inputHasta.value = '';
                    }
                };

                switchFija.addEventListener('change', syncFija);

                // Chips rápidos generales
                modal.querySelectorAll('.btn-chip').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const target = this.dataset.target;
                        const val = this.dataset.val;
                        const mode = this.dataset.mode;
                        const tipo = this.dataset.tipo;

                        if (target === 'concepto') {
                            const input = modal.querySelector('#modal-tar-concepto');
                            if (input) input.value = val;
                        } else if (target === 'unidad') {
                            const input = modal.querySelector('#modal-tar-unidad');
                            if (input) input.value = val;
                        }

                        if (mode) {
                            const selectPor = modal.querySelector('#modal-tar-por');
                            if (selectPor) selectPor.value = mode;
                        }

                        if (tipo) {
                            const selectTipo = modal.querySelector('#modal-tar-tipo');
                            if (selectTipo) selectTipo.value = tipo;
                        }
                    });
                });

                // Chips para habitaciones de hotel
                modal.querySelectorAll('.btn-chip-hotel').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const habId = this.dataset.habId;
                        const concepto = this.dataset.concepto;

                        const inputHabId = modal.querySelector('#modal-tar-habitacion-id');
                        if (inputHabId) inputHabId.value = habId;

                        const inputConcepto = modal.querySelector('#modal-tar-concepto');
                        if (inputConcepto) inputConcepto.value = concepto;

                        const selectPor = modal.querySelector('#modal-tar-por');
                        if (selectPor) selectPor.value = 'noche';

                        const selectTipo = modal.querySelector('#modal-tar-tipo');
                        if (selectTipo) selectTipo.value = 'alojamiento';
                    });
                });

                // Botones rápidos "+ Tarifa" dentro de cada tarjeta de habitación
                document.querySelectorAll('.btn-tarifa-rapida-hab').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const habId = this.dataset.habId;
                        const habTipo = this.dataset.habTipo;

                        const inputHabId = modal.querySelector('#modal-tar-habitacion-id');
                        if (inputHabId) inputHabId.value = habId;

                        const inputConcepto = modal.querySelector('#modal-tar-concepto');
                        if (inputConcepto) inputConcepto.value = 'Alojamiento: {{ $proveedor->nombre }} · ' + habTipo;

                        const selectPor = modal.querySelector('#modal-tar-por');
                        if (selectPor) selectPor.value = 'noche';

                        const selectTipo = modal.querySelector('#modal-tar-tipo');
                        if (selectTipo) selectTipo.value = 'alojamiento';

                        const bsModal = new bootstrap.Modal(modal);
                        bsModal.show();
                    });
                });
            });
        </script>
    @endcan
@endsection
