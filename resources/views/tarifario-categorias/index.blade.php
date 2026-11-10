@extends('layouts.app')
@section('titulo', 'Gestor de Categorías · Tarifario Maestro')

@section('contenido')
<div class="container-fluid px-3 px-md-4 py-3" style="max-width: 1100px;">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tarifario-maestro.index') }}" class="text-decoration-none">Tarifario Maestro</a></li>
            <li class="breadcrumb-item active" aria-current="page">Gestión de Categorías</li>
        </ol>
    </nav>

    {{-- Encabezado --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.72rem;">
                <i class="fas fa-tags me-1"></i> Clasificación y Filtros
            </span>
            <h1 class="h3 fw-bold text-dark mb-1 mt-1">Categorías del Tarifario Maestro</h1>
            <p class="text-muted small mb-0">
                Administra las pestañas, etiquetas y filtros con los que se organizan los servicios y experiencias del catálogo oficial.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('tarifario-maestro.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm d-flex align-items-center gap-1">
                <i class="fas fa-arrow-left"></i> Volver al Tarifario
            </a>
            @can('proveedores.gestionar')
                <button type="button" class="btn btn-primary btn-sm shadow-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalCrearCategoria">
                    <i class="fas fa-plus"></i> Nueva Categoría
                </button>
            @endcan
        </div>
    </div>

    {{-- Alertas --}}
    @if (session('exito'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle text-success fs-5"></i>
            <div>{{ session('exito') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-triangle text-danger fs-5"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger py-2 mb-4 shadow-sm">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tabla de Categorías --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                    <tr>
                        <th style="width: 70px;" class="ps-3">Orden</th>
                        <th>Nombre de la Categoría</th>
                        <th>Identificador (Slug)</th>
                        <th>Ícono</th>
                        <th class="text-center">Servicios Asociados</th>
                        <th class="text-center">Estado</th>
                        <th class="text-end pe-3" style="width: 140px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categorias as $cat)
                        <tr>
                            <td class="ps-3">
                                <span class="badge bg-light text-dark border font-monospace">#{{ $cat->orden }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-light text-primary border p-2">
                                        <i class="fas {{ $cat->icono ?: 'fa-tag' }}"></i>
                                    </span>
                                    <strong class="text-dark">{{ $cat->nombre }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary font-monospace border px-2 py-1">
                                    {{ $cat->slug }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted font-monospace"><i class="fas {{ $cat->icono ?: 'fa-tag' }} me-1"></i>{{ $cat->icono ?: 'fa-tag' }}</small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('tarifario-maestro.index', ['cat' => $cat->slug]) }}" class="text-decoration-none" title="Ver servicios en esta categoría">
                                    <span class="badge {{ $cat->servicios_count > 0 ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-light text-muted border' }} px-2 py-1">
                                        <i class="fas fa-layer-group me-1"></i>{{ $cat->servicios_count }} servicios
                                    </span>
                                </a>
                            </td>
                            <td class="text-center">
                                @if ($cat->activo)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Activo</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-end pe-3 text-nowrap">
                                @can('proveedores.gestionar')
                                    <button type="button" class="btn btn-outline-secondary btn-sm px-2 py-1 shadow-sm me-1"
                                            data-bs-toggle="modal" data-bs-target="#modalEditarCat-{{ $cat->id }}" title="Editar Categoría">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </button>

                                    @if ($cat->servicios_count === 0)
                                        <form method="POST" action="{{ route('tarifario-categorias.destroy', $cat) }}" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar la categoría [{{ $cat->nombre }}]?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm px-2 py-1 shadow-sm" title="Eliminar Categoría">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-outline-secondary btn-sm px-2 py-1 opacity-50" disabled title="No se puede eliminar: tiene servicios vinculados">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                @endcan
                            </td>
                        </tr>

                        {{-- Modal Editar Categoría --}}
                        <div class="modal fade" id="modalEditarCat-{{ $cat->id }}" tabindex="-1" aria-labelledby="modalEditarCatLabel-{{ $cat->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <form method="POST" action="{{ route('tarifario-categorias.update', $cat) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-light border-bottom">
                                            <h6 class="modal-title fw-bold text-dark" id="modalEditarCatLabel-{{ $cat->id }}">
                                                <i class="fas fa-edit text-primary me-2"></i>Editar Categoría: {{ $cat->nombre }}
                                            </h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-dark">Nombre de la Categoría <span class="text-danger">*</span></label>
                                                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $cat->nombre) }}" required>
                                            </div>

                                            <div class="row g-2 mb-3">
                                                <div class="col-8">
                                                    <label class="form-label small fw-semibold text-dark">Identificador / Slug <span class="text-danger">*</span></label>
                                                    <input type="text" name="slug" class="form-control font-monospace" value="{{ old('slug', $cat->slug) }}" required>
                                                    <small class="text-muted" style="font-size: 0.72rem;">Identificador único interno en la base de datos.</small>
                                                </div>
                                                <div class="col-4">
                                                    <label class="form-label small fw-semibold text-dark">Orden</label>
                                                    <input type="number" min="0" name="orden" class="form-control" value="{{ old('orden', $cat->orden) }}">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-dark">Ícono FontAwesome</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas {{ $cat->icono ?: 'fa-tag' }}"></i></span>
                                                    <input type="text" name="icono" class="form-control font-monospace" value="{{ old('icono', $cat->icono) }}" placeholder="fa-tag, fa-fire, fa-hiking...">
                                                </div>
                                                <small class="text-muted" style="font-size: 0.72rem;">Ejemplos: <code>fa-fire</code>, <code>fa-shuttle-van</code>, <code>fa-hiking</code>, <code>fa-utensils</code>, <code>fa-ticket-alt</code></small>
                                            </div>

                                            <div class="form-check mt-3">
                                                <input class="form-check-input" type="checkbox" name="activo" value="1" id="activo-{{ $cat->id }}" @checked(old('activo', $cat->activo))>
                                                <label class="form-check-label small fw-semibold text-dark" for="activo-{{ $cat->id }}">
                                                    Categoría Activa (visible en filtros y cotizaciones)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light border-top">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary btn-sm fw-semibold">
                                                <i class="fas fa-save me-1"></i> Guardar Cambios
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                No se encontraron categorías registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Modal Crear Categoría --}}
<div class="modal fade" id="modalCrearCategoria" tabindex="-1" aria-labelledby="modalCrearCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('tarifario-categorias.store') }}">
                @csrf
                <div class="modal-header bg-light border-bottom">
                    <h6 class="modal-title fw-bold text-dark" id="modalCrearCategoriaLabel">
                        <i class="fas fa-plus-circle text-primary me-2"></i>Nueva Categoría del Tarifario Maestro
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Nombre de la Categoría <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej. Ceremonias Místicas, Experiencias Niños..." required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-8">
                            <label class="form-label small fw-semibold text-dark">Identificador / Slug (opcional)</label>
                            <input type="text" name="slug" class="form-control font-monospace" placeholder="Se genera automáticamente si se deja vacío">
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-semibold text-dark">Orden</label>
                            <input type="number" min="0" name="orden" class="form-control" value="{{ count($categorias) + 1 }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Ícono FontAwesome (opcional)</label>
                        <input type="text" name="icono" class="form-control font-monospace" placeholder="fa-tag, fa-fire, fa-sun, fa-gem...">
                        <small class="text-muted" style="font-size: 0.72rem;">Clase CSS del ícono. Por defecto: <code>fa-tag</code>.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold">
                        <i class="fas fa-check me-1"></i> Crear Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
