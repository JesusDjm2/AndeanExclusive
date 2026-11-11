@extends('layouts.app')
@section('titulo', 'Proveedores')
@section('title', 'Proveedores')

@section('contenido')
    <div class="container-fluid px-0 px-md-3">
        {{-- Migas de pan --}}
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Proveedores</li>
            </ol>
        </nav>

        {{-- Encabezado con acciones inmediatas --}}
        <div class="row align-items-center ae-admin-page-header mb-3">
            <div class="col-12 col-md-7 text-start mb-2 mb-md-0">
                <h1 class="ae-admin-page-title mb-0">
                    <i class="fas fa-fw fa-handshake"></i>
                    Gestión de Proveedores
                </h1>
                <small class="ae-admin-page-desc">Directorio de socios comerciales, contacto directo y categorías operativas.</small>
            </div>
            <div class="col-12 col-md-5 text-md-end d-flex flex-wrap gap-2 justify-content-md-end">
                <a href="{{ route('categoriasproveedor.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-tags me-1"></i>
                    Categorías
                </a>
                @can('proveedores.gestionar')
                    <a href="{{ route('proveedors.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle me-1"></i>
                        Nuevo proveedor
                    </a>
                @endcan
            </div>
        </div>

        {{-- Alerta de éxito --}}
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        {{-- Barra de filtros y búsqueda inmediata (sin badges gigantes que estorben) --}}
        <div class="card shadow-sm border-0 mb-3 ae-admin-filter-card">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('proveedors.index') }}" class="row g-2 align-items-end">
                    <div class="col-12 col-md-5">
                        <label for="buscar-prov-q" class="form-label small text-muted mb-1 fw-semibold">
                            <i class="fas fa-search me-1"></i> Buscar proveedor
                        </label>
                        <input type="search" name="q" id="buscar-prov-q" class="form-control form-control-sm"
                            placeholder="Nombre, RUC, teléfono, correo o ciudad..."
                            value="{{ request('q', $q ?? '') }}" maxlength="100">
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="filtro-categoria" class="form-label small text-muted mb-1 fw-semibold">
                            <i class="fas fa-filter me-1"></i> Categoría / Tipo
                        </label>
                        <select name="categoria_id" id="filtro-categoria" class="form-select form-select-sm" data-autosubmit>
                            <option value="">Todas las categorías ({{ $categorias->sum('proveedors_count') }})</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat->id }}" @selected((string) request('categoria_id', $categoriaId ?? '') === (string) $cat->id)>
                                    {{ $cat->nombre }} ({{ $cat->proveedors_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary flex-fill">
                            <i class="fas fa-filter me-1"></i> Filtrar
                        </button>
                        @if (request()->filled('q') || request()->filled('categoria_id'))
                            <a href="{{ route('proveedors.index') }}" class="btn btn-sm btn-outline-secondary" title="Limpiar filtros">
                                <i class="fas fa-times me-1"></i> Limpiar
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Pestañas rápidas por categoría tipo pills (scroll horizontal en móvil) --}}
        @if ($categorias->isNotEmpty())
            @php
                $catActual = $categorias->firstWhere('id', request('categoria_id'));
                $esVistaHoteles = $catActual && str_contains(mb_strtolower($catActual->nombre), 'hotel');
            @endphp

            <div class="d-flex align-items-center gap-1 overflow-auto pb-2 mb-3 text-nowrap" style="scrollbar-width: thin;">
                <a href="{{ route('proveedors.index', array_filter(['q' => request('q')])) }}"
                    class="btn btn-sm {{ !request('categoria_id') ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill py-1 px-3">
                    Todos <span class="badge {{ !request('categoria_id') ? 'bg-white text-primary' : 'bg-secondary' }} rounded-pill ms-1">{{ $categorias->sum('proveedors_count') }}</span>
                </a>
                @foreach ($categorias as $cat)
                    @if ($cat->proveedors_count > 0 || (string) request('categoria_id') === (string) $cat->id)
                        @php $esCatHotel = str_contains(mb_strtolower($cat->nombre), 'hotel'); @endphp
                        <a href="{{ route('proveedors.index', array_filter(['categoria_id' => $cat->id, 'q' => request('q')])) }}"
                            class="btn btn-sm {{ (string) request('categoria_id') === (string) $cat->id ? 'btn-primary' : ($esCatHotel ? 'btn-outline-info bg-white' : 'btn-outline-secondary bg-white') }} rounded-pill py-1 px-3">
                            @if ($esCatHotel) <i class="fas fa-hotel me-1 text-info"></i> @endif
                            {{ $cat->nombre }}
                            <span class="badge {{ (string) request('categoria_id') === (string) $cat->id ? 'bg-white text-primary' : 'bg-secondary' }} rounded-pill ms-1">
                                {{ $cat->proveedors_count }}
                            </span>
                        </a>
                    @endif
                @endforeach
            </div>

            @if ($esVistaHoteles)
                <div class="card border-0 mb-3 rounded-3 shadow-sm" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 41, 59, 0.98)); color: #f8fafc; border-left: 4px solid #06b6d4 !important;">
                    <div class="card-body p-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(6, 182, 212, 0.18); color: #06b6d4;">
                                <i class="fas fa-hotel fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-white d-flex align-items-center gap-2">
                                    Catálogo de Hoteles & Alojamiento Boutique
                                    <span class="badge rounded-pill bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 font-monospace" style="font-size: 0.72rem;">Centralizado</span>
                                </h6>
                                <small class="text-white-50">Consulta habitaciones, capacidades, tarifas por temporada y contactos directos de reservas sin duplicidad.</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-secondary-subtle text-light border border-secondary px-3 py-2 rounded-pill">
                                <i class="fas fa-bed me-1 text-info"></i> {{ $proveedors->total() }} hoteles aliados
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        {{-- Estado vacío --}}
        @if ($proveedors->isEmpty())
            <div class="card shadow-sm border-0 text-center py-5 my-3">
                <div class="card-body">
                    <i class="fas fa-truck-loading fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="fw-bold text-secondary mb-1">No se encontraron proveedores</h5>
                    <p class="text-muted small mb-3">No hay registros que coincidan con los filtros aplicados.</p>
                    @if (request()->filled('q') || request()->filled('categoria_id'))
                        <a href="{{ route('proveedors.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-undo me-1"></i> Ver todos los proveedores
                        </a>
                    @else
                        @can('proveedores.gestionar')
                            <a href="{{ route('proveedors.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i> Registrar el primer proveedor
                            </a>
                        @endcan
                    @endif
                </div>
            </div>
        @else
            {{-- Vista Móvil: Tarjetas ergonómicas con acciones directas --}}
            <div class="d-md-none">
                <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                    <span class="text-muted small fw-semibold">{{ $proveedors->total() }} proveedores encontrados</span>
                </div>
                @foreach ($proveedors as $proveedor)
                    <div class="card mb-3 shadow-sm border-0 rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <div>
                                    <h5 class="card-title fw-bold text-dark mb-0 fs-6">{{ $proveedor->nombre }}</h5>
                                    @if ($proveedor->ruc)
                                        <small class="text-muted font-monospace d-block">RUC: {{ $proveedor->ruc }}</small>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill">
                                        {{ $proveedor->categoria?->nombre ?? 'Sin categoría' }}
                                    </span>
                                    @if ($proveedor->habitaciones->isNotEmpty())
                                        <div class="mt-1">
                                            <span class="badge rounded-pill" style="background: rgba(6, 182, 212, 0.12); color: #0891b2; border: 1px solid rgba(6, 182, 212, 0.3); font-size: 0.72rem;">
                                                <i class="fas fa-bed me-1"></i> {{ $proveedor->habitaciones->count() }} habs.
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if ($proveedor->detalles)
                                <p class="text-muted small mb-2 fst-italic">{{ \Illuminate\Support\Str::limit($proveedor->detalles, 100) }}</p>
                            @endif

                            @if ($proveedor->direccion)
                                <div class="small text-muted mb-2">
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $proveedor->direccion }}
                                </div>
                            @endif

                            {{-- Botones de contacto rápido móvil --}}
                            <div class="d-flex flex-wrap gap-2 mb-3 pt-1 border-top">
                                @if ($proveedor->telefono)
                                    @php
                                        $numLimpio = preg_replace('/[^0-9]/', '', $proveedor->telefono);
                                    @endphp
                                    <a href="tel:{{ $proveedor->telefono }}" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-2" title="Llamar">
                                        <i class="fas fa-phone-alt text-success me-1"></i> {{ $proveedor->telefono }}
                                    </a>
                                    @if (strlen($numLimpio) >= 9)
                                        <a href="https://wa.me/{{ str_starts_with($numLimpio, '51') ? $numLimpio : '51' . $numLimpio }}"
                                            target="_blank" rel="noopener noreferrer"
                                            class="btn btn-sm btn-outline-success rounded-pill py-1 px-2" title="Enviar WhatsApp">
                                            <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                        </a>
                                    @endif
                                @endif

                                @if ($proveedor->correo)
                                    <a href="mailto:{{ $proveedor->correo }}" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-2 text-truncate" style="max-width: 100%;" title="Escribir correo">
                                        <i class="fas fa-envelope text-primary me-1"></i> {{ $proveedor->correo }}
                                    </a>
                                @endif
                            </div>

                            {{-- Acciones CRUD móvil --}}
                            <div class="d-flex justify-content-end align-items-center gap-1 pt-2 border-top">
                                <a href="{{ route('proveedors.show', $proveedor->id) }}"
                                    class="btn btn-sm btn-outline-primary px-3" title="Ver ficha">
                                    <i class="fas fa-eye me-1"></i> Ver
                                </a>
                                @can('proveedores.gestionar')
                                    <a href="{{ route('proveedors.edit', $proveedor->id) }}"
                                        class="btn btn-sm btn-outline-secondary px-3" title="Editar">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </a>
                                    <form action="{{ route('proveedors.destroy', $proveedor->id) }}" method="POST" class="d-inline"
                                        data-confirm="¿Deseas eliminar al proveedor «{{ $proveedor->nombre }}»? Esta acción no se puede deshacer."
                                        data-confirm-title="Eliminar proveedor"
                                        data-confirm-button="Sí, eliminar"
                                        data-confirm-danger>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-2" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Vista Desktop: Tabla moderna de nivel Senior --}}
            <div class="card shadow-sm border-0 d-none d-md-block">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-secondary">
                            <i class="fas fa-list text-primary me-2"></i>
                            Listado de proveedores
                        </span>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">
                            {{ $proveedors->total() }} registrados
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 ae-admin-data-table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 45px;">#</th>
                                    <th>Proveedor</th>
                                    <th>Categoría</th>
                                    <th>RUC</th>
                                    <th>Contacto</th>
                                    <th>Dirección</th>
                                    <th class="text-center pe-3" style="width: 140px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proveedors as $proveedor)
                                    <tr>
                                        <td class="ps-3 text-muted small">{{ ++$i }}</td>
                                        <td>
                                            <a href="{{ route('proveedors.show', $proveedor->id) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ $proveedor->nombre }}
                                            </a>
                                            @if ($proveedor->detalles)
                                                <small class="d-block text-muted text-truncate" style="max-width: 220px;" title="{{ $proveedor->detalles }}">
                                                    {{ $proveedor->detalles }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill">
                                                {{ $proveedor->categoria?->nombre ?? 'Sin categoría' }}
                                            </span>
                                            @if ($proveedor->habitaciones->isNotEmpty())
                                                <div class="mt-1">
                                                    <span class="badge rounded-pill" style="background: rgba(6, 182, 212, 0.12); color: #0891b2; border: 1px solid rgba(6, 182, 212, 0.3); font-size: 0.72rem;">
                                                        <i class="fas fa-bed me-1"></i> {{ $proveedor->habitaciones->count() }} {{ $proveedor->habitaciones->count() === 1 ? 'hab.' : 'habs.' }}
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="font-monospace small">
                                            {{ $proveedor->ruc ?: '—' }}
                                        </td>
                                        <td>
                                            @if ($proveedor->telefono)
                                                @php
                                                    $numLimpio = preg_replace('/[^0-9]/', '', $proveedor->telefono);
                                                @endphp
                                                <div class="d-flex align-items-center gap-1 small mb-1">
                                                    <a href="tel:{{ $proveedor->telefono }}" class="text-decoration-none text-dark">
                                                        <i class="fas fa-phone-alt text-muted me-1"></i>{{ $proveedor->telefono }}
                                                    </a>
                                                    @if (strlen($numLimpio) >= 9)
                                                        <a href="https://wa.me/{{ str_starts_with($numLimpio, '51') ? $numLimpio : '51' . $numLimpio }}"
                                                            target="_blank" rel="noopener noreferrer" class="text-success ms-1" title="WhatsApp">
                                                            <i class="fab fa-whatsapp"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                            @if ($proveedor->correo)
                                                <div class="small">
                                                    <a href="mailto:{{ $proveedor->correo }}" class="text-decoration-none text-muted" title="{{ $proveedor->correo }}">
                                                        <i class="fas fa-envelope me-1"></i>{{ \Illuminate\Support\Str::limit($proveedor->correo, 24) }}
                                                    </a>
                                                </div>
                                            @endif
                                            @if (! $proveedor->telefono && ! $proveedor->correo)
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted" style="max-width: 200px;">
                                            {{ $proveedor->direccion ?: '—' }}
                                        </td>
                                        <td class="text-center pe-3">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('proveedors.show', $proveedor->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Ver ficha">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @can('proveedores.gestionar')
                                                    <a href="{{ route('proveedors.edit', $proveedor->id) }}"
                                                        class="btn btn-sm btn-outline-secondary" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('proveedors.destroy', $proveedor->id) }}" method="POST" class="d-inline"
                                                        data-confirm="¿Deseas eliminar al proveedor «{{ $proveedor->nombre }}»? Esta acción no se puede deshacer."
                                                        data-confirm-title="Eliminar proveedor"
                                                        data-confirm-button="Sí, eliminar"
                                                        data-confirm-danger>
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($proveedors->hasPages())
                    <div class="card-footer bg-white border-top py-3">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                            <div class="text-muted small">
                                Mostrando {{ $proveedors->firstItem() }} al {{ $proveedors->lastItem() }} de {{ $proveedors->total() }} proveedores
                            </div>
                            <div>
                                {{ $proveedors->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @if ($proveedors->hasPages())
                <div class="d-md-none mt-3">
                    {{ $proveedors->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>
@endsection
