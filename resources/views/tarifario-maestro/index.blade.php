@extends('layouts.app')
@section('titulo', 'Tarifario Maestro Oficial 2027')

@section('contenido')
<div class="container-fluid px-3 px-md-4 py-3">

    {{-- Encabezado Principal --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.72rem;">
                    <i class="fas fa-crown me-1"></i> Catálogo Oficial Andean Aracari
                </span>
                <button type="button" class="btn btn-sm btn-light border px-2 py-1 text-muted shadow-sm d-inline-flex align-items-center gap-1"
                        data-bs-toggle="modal" data-bs-target="#modalTipoCambio" title="Clic para consultar o editar el Tipo de Cambio de referencia">
                    <span class="fw-semibold text-dark"><i class="fas fa-exchange-alt text-primary me-1"></i>Tipo de Cambio: S/ {{ number_format($tipoCambio, 2) }}</span>
                    <span class="text-muted small">· Vigencia 2027</span>
                    @can('proveedores.gestionar')
                        <i class="fas fa-pen text-primary ms-1" style="font-size: 0.7rem;"></i>
                    @endcan
                </button>
            </div>
            <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="fas fa-calculator text-primary"></i> Tarifario Maestro de Servicios
            </h1>
            <p class="text-muted small mb-0">
                Catálogo base para cotizar itinerarios con matrices escalonadas de 1 a 20 pasajeros, boletos oficiales, traslados y tours exclusivos.
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            @can('proveedores.gestionar')
                <a href="{{ route('tarifario-categorias.index') }}" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 shadow-sm">
                    <i class="fas fa-tags"></i> Gestionar Categorías
                </a>
            @endcan
            <a href="{{ route('programas.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1 shadow-sm">
                <i class="fas fa-suitcase-rolling"></i> Programas y Cotizaciones
            </a>
            <a href="{{ route('tarifas.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1 shadow-sm">
                <i class="fas fa-search-dollar"></i> Comparador de Proveedores
            </a>
        </div>
    </div>

    {{-- Alertas de Éxito / Error --}}
    @if (session('exito'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle text-success fs-5"></i>
            <div>{{ session('exito') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- Tarjetas KPI / Métricas Rápidas --}}
    <div class="row g-2 g-md-3 mb-4">
        <div class="col-6 col-md-3 col-xl">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3 bg-white border-start border-4 border-primary">
                <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.7rem;">Total Servicios</span>
                <div class="d-flex align-items-baseline justify-content-between mt-1">
                    <span class="fs-4 fw-bold text-dark">{{ $stats['total'] }}</span>
                    <i class="fas fa-layer-group text-primary opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3 bg-white border-start border-4 border-info">
                <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.7rem;">Tours Exclusivos</span>
                <div class="d-flex align-items-baseline justify-content-between mt-1">
                    <span class="fs-4 fw-bold text-dark">{{ $stats['tours'] }}</span>
                    <i class="fas fa-map-marked-alt text-info opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3 bg-white border-start border-4 border-success">
                <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.7rem;">Traslados</span>
                <div class="d-flex align-items-baseline justify-content-between mt-1">
                    <span class="fs-4 fw-bold text-dark">{{ $stats['traslados'] }}</span>
                    <i class="fas fa-shuttle-van text-success opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3 bg-white border-start border-4 border-warning">
                <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.7rem;">Boletos Oficiales</span>
                <div class="d-flex align-items-baseline justify-content-between mt-1">
                    <span class="fs-4 fw-bold text-dark">{{ $stats['boletos'] }}</span>
                    <i class="fas fa-ticket-alt text-warning opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3 bg-white border-start border-4 border-danger">
                <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.7rem;">Treks de Altura</span>
                <div class="d-flex align-items-baseline justify-content-between mt-1">
                    <span class="fs-4 fw-bold text-dark">{{ $stats['treks'] }}</span>
                    <i class="fas fa-hiking text-danger opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3 bg-white border-start border-4" style="border-color: #6f42c1 !important;">
                <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.7rem;">Ceremonias</span>
                <div class="d-flex align-items-baseline justify-content-between mt-1">
                    <span class="fs-4 fw-bold text-dark">{{ $stats['ceremonias'] ?? 0 }}</span>
                    <i class="fas fa-fire opacity-50" style="color: #6f42c1;"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3 bg-white border-start border-4 border-secondary">
                <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.7rem;">Alimentación</span>
                <div class="d-flex align-items-baseline justify-content-between mt-1">
                    <span class="fs-4 fw-bold text-dark">{{ $stats['alimentacion'] }}</span>
                    <i class="fas fa-utensils text-secondary opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Barra de Filtros y Búsqueda --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('tarifario-maestro.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-6 col-lg-7">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" name="q" value="{{ $q }}" class="form-control border-start-0 bg-light"
                               placeholder="Buscar por código (ej. FDAWA, BTG, APTHTL), nombre o lugares de visita...">
                        @if ($q !== '' || $cat !== '')
                            <a href="{{ route('tarifario-maestro.index') }}" class="btn btn-outline-secondary" title="Limpiar filtros">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <select name="cat" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Todas las categorías ({{ $stats['total'] }})</option>
                        @foreach ($categoriasLista as $c)
                            <option value="{{ $c->slug }}" @selected($cat === $c->slug)>{{ $c->nombre }} ({{ $c->servicios_count }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2 col-lg-2 text-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                </div>
            </form>

            {{-- Píldoras de Filtro Rápido --}}
            <div class="d-flex flex-wrap gap-1 mt-3 pt-2 border-top align-items-center">
                <span class="small text-muted me-2 fw-semibold" style="font-size: 0.75rem;"><i class="fas fa-tags me-1"></i>Filtro:</span>
                <a href="{{ route('tarifario-maestro.index', array_filter(['q' => $q])) }}"
                   class="tm-filter-pill {{ $cat === '' ? 'is-active' : '' }}">
                    Todos
                </a>
                @foreach ($categoriasLista as $c)
                    <a href="{{ route('tarifario-maestro.index', array_filter(['cat' => $c->slug, 'q' => $q])) }}"
                       class="tm-filter-pill {{ $cat === $c->slug ? 'is-active' : '' }}">
                        <i class="fas {{ $c->icono ?: 'fa-tag' }} me-1 opacity-75"></i>{{ $c->nombre }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Tabla de Servicios del Catálogo --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        @if ($servicios->isEmpty())
            <div class="p-5 text-center text-muted">
                <i class="fas fa-search-dollar fa-3x mb-3 text-secondary opacity-50"></i>
                <h5 class="fw-bold">No se encontraron servicios maestros con los filtros indicados.</h5>
                <p class="small text-muted mb-3">Intenta buscar con otra palabra clave o restablece los filtros para ver todo el tarifario.</p>
                <a href="{{ route('tarifario-maestro.index') }}" class="btn btn-primary btn-sm">Ver Todos los Servicios</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                    <thead class="table-light text-muted small text-uppercase" style="letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-3" style="width: 110px;">Código</th>
                            <th style="width: 130px;">Categoría</th>
                            <th>Servicio / Experiencia</th>
                            <th style="width: 140px;">Modalidad Cálculo</th>
                            <th class="text-end" style="width: 130px;">Costo Ref. (2 pax / Adulto)</th>
                            <th class="text-end" style="width: 120px;">Comisión</th>
                            <th class="text-end pe-3" style="width: 120px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($servicios as $s)
                            @php
                                $badgeCat = match($s->categoria) {
                                    'tour' => 'bg-info-subtle text-info border border-info-subtle',
                                    'traslado' => 'bg-success-subtle text-success border border-success-subtle',
                                    'trek' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                    'boleto' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                                    'alimentacion' => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                                    'viatico' => 'bg-dark-subtle text-dark border border-dark-subtle',
                                    'propina' => 'bg-light text-muted border',
                                    default => 'bg-light text-dark border',
                                };

                                $refCosto = match($s->tipo_calculo) {
                                    'escala_pax' => $s->calcularCosto(2),
                                    'por_persona' => $s->precio_adulto ?? 0,
                                    'por_grupo' => $s->costo_grupo ?? 0,
                                    default => 0,
                                };
                            @endphp
                            <tr>
                                <td class="ps-3">
                                    <span class="badge bg-dark-subtle text-dark border font-monospace fw-bold px-2 py-1" style="font-size: 0.8rem;">
                                        {{ $s->codigo }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $badgeCat }} rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                        {{ $s->categoria_nombre }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">
                                        <a href="{{ route('tarifario-maestro.show', $s) }}" class="text-dark text-decoration-none hover-primary">
                                            {{ $s->nombre }}
                                        </a>
                                    </div>
                                    @if ($s->lugares_visita)
                                        <small class="text-muted d-block text-truncate" style="max-width: 450px;" title="{{ $s->lugares_visita }}">
                                            <i class="fas fa-map-pin text-primary me-1" style="font-size: 0.7rem;"></i>{{ $s->lugares_visita }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if ($s->tipo_calculo === 'escala_pax')
                                        <span class="badge bg-light text-primary border border-primary-subtle px-2 py-1" title="Matriz escalonada de 1 a 20 pasajeros">
                                            <i class="fas fa-chart-line me-1"></i> 1 a 20 Pax
                                        </span>
                                    @elseif ($s->tipo_calculo === 'por_persona')
                                        <span class="badge bg-light text-success border border-success-subtle px-2 py-1">
                                            <i class="fas fa-user me-1"></i> Por persona
                                        </span>
                                    @else
                                        <span class="badge bg-light text-secondary border px-2 py-1">
                                            <i class="fas fa-users me-1"></i> Por grupo
                                        </span>
                                    @endif
                                    @if ($s->duracion_horas)
                                        <small class="text-muted ms-1"><i class="far fa-clock"></i> {{ (float) $s->duracion_horas }}h</small>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold text-dark">
                                        {{ $s->moneda }} {{ number_format($refCosto, 2) }}
                                    </div>
                                    <small class="text-muted" style="font-size: 0.72rem;">
                                        @if ($s->tipo_calculo === 'escala_pax')
                                            (base 2 pax)
                                        @elseif ($s->tipo_calculo === 'por_persona')
                                            (por pax)
                                        @else
                                            (total grupo)
                                        @endif
                                    </small>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                        {{ (float) $s->comision_pct }}%
                                    </span>
                                    @if ($s->aplica_igv_comision)
                                        <small class="d-block text-muted" style="font-size: 0.7rem;">+ IGV fee</small>
                                    @endif
                                </td>
                                <td class="text-end pe-3 text-nowrap">
                                    <a href="{{ route('tarifario-maestro.show', $s) }}" class="btn btn-outline-primary btn-sm px-2 py-1 shadow-sm" title="Ver matriz detallada y simulador de cotización">
                                        <i class="fas fa-calculator me-1"></i> Matriz
                                    </a>
                                    @can('proveedores.gestionar')
                                        <a href="{{ route('tarifario-maestro.edit', $s) }}" class="btn btn-outline-secondary btn-sm px-2 py-1 shadow-sm ms-1" title="Editar parámetros y categoría">
                                            <i class="fas fa-edit me-1"></i> Editar
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if ($servicios->hasPages())
                <div class="card-footer bg-light border-top p-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <small class="text-muted">
                        Mostrando del {{ $servicios->firstItem() }} al {{ $servicios->lastItem() }} de {{ $servicios->total() }} servicios maestros.
                    </small>
                    <div class="mb-0">
                        {{ $servicios->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        @endif
    </div>

</div>

{{-- Modal Tipo de Cambio --}}
<div class="modal fade" id="modalTipoCambio" tabindex="-1" aria-labelledby="modalTipoCambioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('tarifario-maestro.tipo-cambio') }}">
                @csrf
                <div class="modal-header bg-light border-bottom">
                    <h6 class="modal-title fw-bold text-dark" id="modalTipoCambioLabel">
                        <i class="fas fa-exchange-alt text-primary me-2"></i>Tipo de Cambio Oficial (Tarifario Maestro)
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">
                        Tasa de conversión contable utilizada para convertir a Dólares Americanos (USD) los servicios y boletos oficiales fijados en Soles peruanos (PEN).
                    </p>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Tipo de Cambio Oficial (S/ por cada US$ 1.00) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text fw-bold text-secondary">S/</span>
                            <input type="number" step="0.01" min="0.10" max="100.00" name="tipo_cambio" class="form-control form-control-lg fw-bold text-dark font-monospace" value="{{ old('tipo_cambio', number_format($tipoCambio, 2, '.', '')) }}" required>
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;">Referencia original de la hoja oficial Aracari: S/ 3.30.</small>
                    </div>

                    <div class="p-3 bg-light rounded-3 border mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recalcular_boletos" value="1" id="recalcular_boletos" checked>
                            <label class="form-check-label small fw-semibold text-dark" for="recalcular_boletos">
                                Recalcular automáticamente tarifas de boletos oficiales en Soles
                            </label>
                        </div>
                        <small class="text-muted d-block mt-1 ps-4" style="font-size: 0.72rem;">
                            Actualiza el costo en USD de los boletos del Estado (BTG: S/ 130, Maras: S/ 20, Catedral: S/ 40, Qorikancha: S/ 20, Machu Picchu: S/ 152) dividiendo su valor en Soles entre este nuevo tipo de cambio.
                        </small>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    @can('proveedores.gestionar')
                        <button type="submit" class="btn btn-primary btn-sm fw-semibold">
                            <i class="fas fa-save me-1"></i> Guardar Tipo de Cambio
                        </button>
                    @else
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Solo lectura</button>
                    @endcan
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.hover-primary:hover {
    color: var(--bs-primary) !important;
}
</style>
@endsection
