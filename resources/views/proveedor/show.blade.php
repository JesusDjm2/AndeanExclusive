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
                            <a href="{{ route('tarifas.create', ['proveedor_id' => $proveedor->id, 'retorno' => 'proveedor']) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus me-1"></i> Agregar Tarifa / Servicio
                            </a>
                        @endcan
                    </div>
                    <div class="card-body p-0">
                        @if ($proveedor->tarifas->isEmpty())
                            <div class="p-4 text-center text-muted">
                                <i class="fas fa-hand-holding-usd fa-3x mb-3 text-secondary opacity-50"></i>
                                <h5 class="fw-normal">No hay tarifas registradas para este proveedor</h5>
                                <p class="small text-muted mb-3">Registra los precios que cobra por sus servicios (ej. medio día, día completo, traslados, tipo de vehículo o menús) para que aparezcan automáticamente al armar cotizaciones.</p>
                                @can('proveedores.gestionar')
                                    <a href="{{ route('tarifas.create', ['proveedor_id' => $proveedor->id, 'retorno' => 'proveedor']) }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-plus me-1"></i> Cargar primera tarifa fija
                                    </a>
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
                                                </td>
                                                <td>
                                                    @if ($tarifa->unidad_detalle)
                                                        <span class="badge bg-secondary-subtle text-secondary border">{{ $tarifa->unidad_detalle }}</span>
                                                    @else
                                                        <span class="text-muted small">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($tarifa->es_fija || (! $tarifa->desde && ! $tarifa->hasta))
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

            </div>
        </div>
    </section>
@endsection
