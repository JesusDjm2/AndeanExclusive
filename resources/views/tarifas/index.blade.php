{{-- Tarifario / Buscador y Comparador de Tarifas (TarifaController@index). --}}
@extends('layouts.app')
@section('titulo', 'Buscador y Comparador de Tarifas')

@php $canEdit = auth()->user()->can('proveedores.gestionar'); @endphp

@section('contenido')
    <div class="ae-admin-page-header d-flex flex-wrap align-items-end justify-content-between gap-3">
        <div>
            <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-search-dollar"></i> Buscador y Comparador de Tarifas</h1>
            <small class="ae-admin-page-desc">Consulta y compara rápidamente precios de transporte, guías, restaurantes, entradas y hoteles para cotizaciones.</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('proveedors.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-handshake me-1" aria-hidden="true"></i> Ir a Proveedores
            </a>
            @if ($canEdit)
                <a href="{{ route('tarifas.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1" aria-hidden="true"></i> Nueva tarifa global</a>
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

    {{-- Nota Operativa --}}
    <div class="alert alert-light border d-flex align-items-center gap-2 py-2 px-3 mb-3 small text-muted">
        <i class="fas fa-info-circle text-primary fs-5" aria-hidden="true"></i>
        <div>
            <strong>Consejo para Operaciones:</strong> Los precios se gestionan directamente en la ficha de cada proveedor en <a href="{{ route('proveedors.index') }}" class="fw-bold text-decoration-none">Proveedores</a>. Usa este buscador para comparar quién ofrece la mejor tarifa por ruta, vehículo o servicio al cotizar.
        </div>
    </div>

    {{-- Filtro de Categoría / Rubro --}}
    <div class="d-flex flex-wrap gap-1 mb-2 align-items-center">
        <span class="small text-muted fw-bold me-2"><i class="fas fa-filter text-muted me-1"></i>Rubro:</span>
        <a href="{{ route('tarifas.index', array_filter(['vista' => $vista, 'q' => request('q')])) }}"
           class="badge text-decoration-none {{ empty($categoriaFiltro) ? 'bg-dark text-white' : 'bg-light text-dark border' }} py-2 px-3">
            Todos
        </a>
        <a href="{{ route('tarifas.index', array_filter(['vista' => $vista, 'categoria' => 'hotel', 'q' => request('q')])) }}"
           class="badge text-decoration-none {{ $categoriaFiltro === 'hotel' ? 'bg-dark text-white' : 'bg-light text-dark border' }} py-2 px-3">
            <i class="fas fa-bed me-1"></i> Hoteles
        </a>
        @foreach ($categorias as $cat)
            <a href="{{ route('tarifas.index', array_filter(['vista' => $vista, 'categoria' => $cat->id, 'q' => request('q')])) }}"
               class="badge text-decoration-none {{ (string) $categoriaFiltro === (string) $cat->id ? 'bg-dark text-white' : 'bg-light text-dark border' }} py-2 px-3">
                {{ $cat->nombre }}
            </a>
        @endforeach
    </div>

    <nav class="prog-tabs mb-3" aria-label="Vigencia">
        @foreach (['vigentes' => 'Vigentes y fijas', 'fijas' => 'Tarifas fijas (Todo el año)', 'vencidas' => 'Temporadas vencidas', 'todas' => 'Todas'] as $clave => $etiqueta)
            <a href="{{ route('tarifas.index', array_filter(['vista' => $clave, 'categoria' => $categoriaFiltro, 'q' => request('q')])) }}" class="prog-tabs__tab {{ $vista === $clave ? 'is-active' : '' }}" @if ($vista === $clave) aria-current="page" @endif>{{ $etiqueta }}</a>
        @endforeach
    </nav>

    <form method="GET" action="{{ route('tarifas.index') }}" class="res-filters mb-3">
        <input type="hidden" name="vista" value="{{ $vista }}">
        @if ($categoriaFiltro)
            <input type="hidden" name="categoria" value="{{ $categoriaFiltro }}">
        @endif
        <div class="res-filters__search flex-grow-1">
            <label for="tar-q" class="visually-hidden">Buscar</label>
            <i class="fas fa-search" aria-hidden="true"></i>
            <input id="tar-q" type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por transporte, guía, ruta, vehículo, restaurante u hotel...">
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i> Buscar</button>
        @if (request()->hasAny(['q', 'categoria']))
            <a href="{{ route('tarifas.index', ['vista' => $vista]) }}" class="btn btn-outline-secondary">Limpiar filtros</a>
        @endif
    </form>

    <div class="card dash-card">
        @if ($tarifas->isEmpty())
            <div class="admin-empty p-5 text-center text-muted">
                <i class="fas fa-tags fa-3x mb-3 text-secondary opacity-50" aria-hidden="true"></i>
                <h5>No se encontraron tarifas con los filtros seleccionados.</h5>
                <p class="small text-muted mb-3">Prueba con otra palabra clave o agrega una nueva tarifa directamente en la ficha del proveedor.</p>
                <a href="{{ route('proveedors.index') }}" class="btn btn-outline-primary btn-sm">Ver Proveedores</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Proveedor u Hotel</th>
                            <th>Rubro / Categoría</th>
                            <th>Servicio / Concepto / Ruta</th>
                            <th>Vigencia / Temporada</th>
                            <th>Modalidad</th>
                            <th class="text-end">Costo Neto</th>
                            <th class="text-end" style="width: 110px;"><span class="visually-hidden">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tarifas as $tarifa)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $tarifa->de_quien }}
                                    @if ($tarifa->proveedor_id)
                                        <a href="{{ route('proveedors.show', $tarifa->proveedor_id) }}" class="ms-1 text-primary small" title="Ver ficha del proveedor"><i class="fas fa-external-link-alt"></i></a>
                                    @elseif ($tarifa->habitacion?->hotel_id)
                                        <a href="{{ route('hotel.show', $tarifa->habitacion->hotel_id) }}" class="ms-1 text-primary small" title="Ver ficha del hotel"><i class="fas fa-external-link-alt"></i></a>
                                    @endif
                                </td>
                                <td>
                                    @if ($tarifa->proveedor)
                                        <span class="badge bg-secondary-subtle text-secondary border">
                                            {{ $tarifa->proveedor->categoria->nombre ?? 'Proveedor' }}
                                        </span>
                                    @elseif ($tarifa->habitacion)
                                        <span class="badge bg-info-subtle text-info border">Hotel</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-medium text-dark">{{ $tarifa->que }}</span>
                                    @if ($tarifa->unidad_detalle)
                                        <span class="badge bg-light text-dark border ms-1">{{ $tarifa->unidad_detalle }}</span>
                                    @endif
                                    @if ($tarifa->notas)<small class="d-block text-muted">{{ $tarifa->notas }}</small>@endif
                                </td>
                                <td class="text-nowrap">
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
                                    <span class="badge bg-light text-secondary border">
                                        {{ ucfirst(\App\Models\Tarifa::POR[$tarifa->por] ?? $tarifa->por) }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap fw-bold fs-6">
                                    {{ \App\Models\Servicio::formatoMonto((float) $tarifa->precio, $tarifa->moneda) }}
                                </td>
                                <td class="text-end text-nowrap">
                                    @if ($canEdit)
                                        <a href="{{ route('tarifas.edit', $tarifa) }}" class="btn btn-sm btn-outline-primary" aria-label="Editar tarifa" title="Editar tarifa"><i class="fas fa-pen" aria-hidden="true"></i></a>
                                        <form action="{{ route('tarifas.destroy', $tarifa) }}" method="POST" class="d-inline" data-confirm="Se eliminará esta tarifa." data-confirm-title="¿Eliminar tarifa?" data-confirm-button="Sí, eliminar" data-confirm-danger>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Eliminar tarifa" title="Eliminar tarifa"><i class="fas fa-trash-alt" aria-hidden="true"></i></button>
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
    <div class="prog-pagination mt-3">{{ $tarifas->links('pagination::bootstrap-5') }}</div>
@endsection
