@extends('layouts.app')
@section('titulo', 'Categorías Tours (Español)')

@section('contenido')
    <div class="container-fluid">
        {{-- Header Senior --}}
        <div class="ae-admin-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="ae-admin-page-title">
                    <i class="fas fa-tags" aria-hidden="true"></i>
                    Categorías de Tours (Español)
                </h1>
                <small class="ae-admin-page-desc">Taxonomía y colecciones públicas de tours en español.</small>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('estours.index') }}" class="btn btn-outline-secondary" title="Volver al catálogo de tours en español">
                    <i class="fas fa-arrow-left me-1" aria-hidden="true"></i> Tours ES
                </a>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary" title="Gestionar categorías en inglés">
                    <i class="fas fa-language me-1" aria-hidden="true"></i> Categorías EN
                </a>
                <a href="{{ route('categorias.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus-circle me-1" aria-hidden="true"></i> Nueva Categoría ES
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm" role="alert">
                <i class="fas fa-check-circle fs-5" aria-hidden="true"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        {{-- KPI Stats Grid --}}
        <div class="ae-stats-grid mb-4">
            <div class="ae-kpi-card">
                <div class="ae-kpi-icon is-primary">
                    <i class="fas fa-folder" aria-hidden="true"></i>
                </div>
                <div class="ae-kpi-info">
                    <div class="ae-kpi-val">{{ $categorias->count() }}</div>
                    <div class="ae-kpi-label">Categorías Totales</div>
                </div>
            </div>
            <div class="ae-kpi-card">
                <div class="ae-kpi-icon is-success">
                    <i class="fas fa-check-double" aria-hidden="true"></i>
                </div>
                <div class="ae-kpi-info">
                    <div class="ae-kpi-val">{{ $categorias->where('tours_count', '>', 0)->count() }}</div>
                    <div class="ae-kpi-label">Con Tours Activos</div>
                </div>
            </div>
            <div class="ae-kpi-card">
                <div class="ae-kpi-icon is-info">
                    <i class="fas fa-map-marked-alt" aria-hidden="true"></i>
                </div>
                <div class="ae-kpi-info">
                    <div class="ae-kpi-val">{{ $categorias->sum('tours_count') }}</div>
                    <div class="ae-kpi-label">Asignaciones en Tours</div>
                </div>
            </div>
        </div>

        {{-- Main Table Card --}}
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-header bg-surface py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill px-3 py-2 fw-semibold">
                        {{ $categorias->count() }} {{ Str::plural('categoría', $categorias->count()) }}
                    </span>
                </div>
                <div class="position-relative" style="min-width: 260px;">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="search" id="catSearchInput" class="form-control form-control-sm ps-5 rounded-pill"
                        placeholder="Filtrar por nombre o slug...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 ae-content-table" id="catTable">
                    <thead class="table-light text-uppercase fs-xs fw-bold text-muted border-bottom">
                        <tr>
                            <th scope="col" style="width: 60px;" class="text-center">#</th>
                            <th scope="col">Nombre de la Categoría</th>
                            <th scope="col">Slug / Enlace Público</th>
                            <th scope="col" class="text-center">Tours Vinculados</th>
                            <th scope="col" class="text-end pe-3" style="width: 170px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categorias as $cat)
                            <tr class="cat-row" data-search="{{ strtolower($cat->nombre . ' ' . $cat->slug) }}">
                                <td class="text-center text-muted fw-bold">{{ $cat->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                                            <i class="fas fa-tag fa-sm"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('categorias.edit', $cat->id) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ $cat->nombre }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="small px-2 py-1 bg-light border rounded text-secondary">
                                        /categoria/{{ $cat->slug }}
                                    </code>
                                </td>
                                <td class="text-center">
                                    @if ($cat->tours_count > 0)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1">
                                            <i class="fas fa-map-marker-alt me-1"></i> {{ $cat->tours_count }} {{ Str::plural('tour', $cat->tours_count) }}
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted border rounded-pill px-2 py-1">
                                            Sin tours
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('categoria.show', $cat->slug) }}" class="btn btn-outline-secondary" target="_blank" title="Ver en sitio público">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('categorias.edit', $cat->id) }}" class="btn btn-outline-primary" title="Editar categoría">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" title="Eliminar categoría"
                                            onclick="confirmDelete('{{ $cat->id }}', '{{ addslashes($cat->nombre) }}', {{ $cat->tours_count }})">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $cat->id }}" action="{{ route('categorias.destroy', $cat->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-folder-open text-muted fa-3x mb-3 d-block"></i>
                                    <h5 class="text-muted">No hay categorías registradas</h5>
                                    <p class="text-muted small">Comienza creando tu primera categoría para organizar los tours en español.</p>
                                    <a href="{{ route('categorias.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus-circle me-1"></i> Crear Categoría
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('catSearchInput');
        const rows = document.querySelectorAll('.cat-row');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const term = this.value.trim().toLowerCase();
                rows.forEach(r => {
                    const haystack = r.getAttribute('data-search') || '';
                    r.style.display = haystack.includes(term) ? '' : 'none';
                });
            });
        }
    });

    function confirmDelete(id, name, toursCount) {
        let msg = `¿Estás seguro de eliminar la categoría "${name}"?`;
        if (toursCount > 0) {
            msg += `\n\nAtención: Tiene ${toursCount} tour(s) asociado(s). Se desvinculará de ellos pero los tours se conservarán.`;
        }
        if (confirm(msg)) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
