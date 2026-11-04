@extends('layouts.app')
@section('titulo', 'Tours en Inglés')

@section('contenido')
    <div class="container-fluid">
        {{-- Header Senior --}}
        <div class="ae-admin-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="ae-admin-page-title">
                    <i class="fas fa-map-marked-alt" aria-hidden="true"></i>
                    Tours en Inglés
                </h1>
                <small class="ae-admin-page-desc">Catálogo público EN: itinerarios, tarifas, categorías e imágenes.</small>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('estours.index') }}" class="btn btn-outline-secondary" title="Gestionar tours en español">
                    <i class="fas fa-language me-1" aria-hidden="true"></i> Ver Tours en Español
                </a>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary" title="Gestionar categorías">
                    <i class="fas fa-tags me-1" aria-hidden="true"></i> Categorías
                </a>
                <a href="{{ route('tours.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus-circle me-1" aria-hidden="true"></i> Nuevo Tour EN
                </a>
            </div>
        </div>

        {{-- Alertas --}}
        @foreach (['success' => 'success', 'error' => 'danger', 'status' => 'info'] as $key => $type)
            @if (session($key))
                <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                    <i class="fas {{ $type === 'success' ? 'fa-check-circle' : 'fa-info-circle' }} me-2"></i>
                    {{ session($key) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif
        @endforeach

        {{-- Barra de Filtros y Estadísticas --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body p-3">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-muted">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" id="buscadorTours" class="form-control border-start-0 ps-0"
                                placeholder="Buscar por título, recorrido o slug..." autocomplete="off">
                            <button class="btn btn-outline-secondary border-start-0" type="button" id="btnLimpiarFiltro" title="Limpiar búsqueda">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select id="filtroCategoria" class="form-select">
                            <option value="">Todas las categorías ({{ $categorias->count() }})</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ strtolower($cat->nombre) }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">
                            Mostrando <span id="contadorVisible" class="text-primary fw-bold">{{ $tours->count() }}</span> de {{ $totalTours }} tours
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de Tours --}}
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 ae-admin-data-table ae-content-table" id="tablaTours">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 48px;" class="text-center">#</th>
                                <th style="width: 90px;">Portada</th>
                                <th>Tour & Recorrido</th>
                                <th style="width: 140px;">Duración / Precio</th>
                                <th>Categorías</th>
                                <th style="width: 180px;">Versión Español</th>
                                <th style="width: 130px;" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyTours">
                            @forelse ($tours as $index => $tour)
                                @php
                                    $categoriasNombres = $tour->categorias->pluck('nombre')->implode(', ');
                                    $hasEsRelation = $tour->estour !== null;
                                @endphp
                                <tr class="tour-row"
                                    data-nombre="{{ strtolower($tour->nombre . ' ' . $tour->recorrido . ' ' . $tour->slug) }}"
                                    data-categorias="{{ strtolower($categoriasNombres) }}">
                                    <td class="text-center text-muted small fw-semibold">{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ asset($tour->imgFull ?: $tour->imgThumb) }}" target="_blank" title="Ver imagen completa">
                                            <img src="{{ asset($tour->imgThumb ?: 'img/default-avatar.png') }}"
                                                alt="{{ $tour->nombre }}" class="ae-table-thumb"
                                                onerror="this.src='{{ asset('img/no-image.jpg') }}'">
                                        </a>
                                    </td>
                                    <td class="ae-title-cell">
                                        <a href="{{ route('tours.edit', $tour->id) }}" class="ae-title-link">
                                            {{ $tour->nombre }}
                                        </a>
                                        <div class="ae-title-meta">
                                            @if ($tour->recorrido)
                                                <span><i class="fas fa-map-marker-alt text-danger"></i> {{ $tour->recorrido }}</span>
                                            @endif
                                            <span><i class="fas fa-link text-muted"></i> <code>/{{ $tour->slug }}</code></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge bg-light text-dark border">
                                                <i class="far fa-clock text-primary me-1"></i> {{ $tour->dias }} {{ $tour->dias == 1 ? 'Día' : 'Días' }}
                                            </span>
                                            <span class="badge bg-light text-success border">
                                                <i class="fas fa-dollar-sign me-1"></i> {{ number_format((float) $tour->precio, 2) }} USD
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @forelse ($tour->categorias as $categoria)
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                                    {{ $categoria->nombre }}
                                                </span>
                                            @empty
                                                <span class="text-muted small">Sin categoría</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>
                                        @if ($hasEsRelation)
                                            <a href="{{ route('estours.edit', $tour->estour->id) }}"
                                                class="ae-relation-badge is-linked text-decoration-none"
                                                title="Editar versión en español">
                                                <i class="fas fa-check-circle"></i>
                                                <span class="text-truncate" style="max-width: 140px;">{{ $tour->estour->nombre }}</span>
                                            </a>
                                        @else
                                            <span class="ae-relation-badge is-unlinked" title="Aún no tiene tour en español vinculado">
                                                <i class="fas fa-exclamation-circle"></i> Sin vincular
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ url($tour->slug) }}" target="_blank" rel="noopener"
                                                class="btn btn-outline-secondary" title="Ver en sitio web">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            <a href="{{ route('tours.edit', $tour->id) }}"
                                                class="btn btn-outline-primary" title="Editar tour">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger"
                                                title="Eliminar tour"
                                                onclick="confirmarEliminar('{{ $tour->id }}', '{{ addslashes($tour->nombre) }}')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $tour->id }}"
                                            action="{{ route('tours.destroy', $tour->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="admin-empty">
                                            <i class="fas fa-map-marked-alt mb-3"></i>
                                            <h5>No hay tours registrados</h5>
                                            <p class="text-muted">Comienza agregando el primer tour en inglés para el catálogo.</p>
                                            <a href="{{ route('tours.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus-circle me-1"></i> Crear Tour
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Fila sin resultados de búsqueda --}}
                <div id="noResultados" class="text-center py-5 d-none">
                    <div class="admin-empty">
                        <i class="fas fa-search mb-2"></i>
                        <h5>No se encontraron tours</h5>
                        <p class="text-muted">Ningún tour coincide con el filtro de búsqueda aplicado.</p>
                        <button class="btn btn-sm btn-outline-primary" onclick="resetFiltros()">Limpiar filtros</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de confirmación de eliminación --}}
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalEliminarLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i> Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <p class="mb-2">¿Estás seguro de que deseas eliminar permanentemente este tour?</p>
                    <h6 class="fw-bold text-danger mb-3" id="nombreTourEliminar"></h6>
                    <small class="text-muted">Esta acción no se puede deshacer y eliminará las imágenes asociadas.</small>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar Tour</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buscador = document.getElementById('buscadorTours');
            const filtroCat = document.getElementById('filtroCategoria');
            const btnLimpiar = document.getElementById('btnLimpiarFiltro');
            const rows = document.querySelectorAll('.tour-row');
            const noResultados = document.getElementById('noResultados');
            const contadorVisible = document.getElementById('contadorVisible');

            function filtrar() {
                const q = buscador.value.trim().toLowerCase();
                const cat = filtroCat.value.trim().toLowerCase();
                let visibles = 0;

                rows.forEach(row => {
                    const nombre = row.getAttribute('data-nombre') || '';
                    const categorias = row.getAttribute('data-categorias') || '';

                    const matchQ = !q || nombre.includes(q);
                    const matchCat = !cat || categorias.includes(cat);

                    if (matchQ && matchCat) {
                        row.classList.remove('d-none');
                        visibles++;
                    } else {
                        row.classList.add('d-none');
                    }
                });

                contadorVisible.textContent = visibles;
                if (visibles === 0 && rows.length > 0) {
                    noResultados.classList.remove('d-none');
                } else {
                    noResultados.classList.add('d-none');
                }
            }

            buscador.addEventListener('input', filtrar);
            filtroCat.addEventListener('change', filtrar);
            btnLimpiar.addEventListener('click', function() {
                buscador.value = '';
                filtroCat.value = '';
                filtrar();
            });

            window.resetFiltros = function() {
                buscador.value = '';
                filtroCat.value = '';
                filtrar();
            };

            // Modal de eliminación
            let tourIdToDelete = null;
            const modalEl = document.getElementById('modalEliminar');
            const modalInstance = new bootstrap.Modal(modalEl);

            window.confirmarEliminar = function(id, nombre) {
                tourIdToDelete = id;
                document.getElementById('nombreTourEliminar').textContent = nombre;
                modalInstance.show();
            };

            document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
                if (tourIdToDelete) {
                    const form = document.getElementById('delete-form-{{ "" }}' + tourIdToDelete);
                    if (form) form.submit();
                }
            });
        });
    </script>
@endpush
