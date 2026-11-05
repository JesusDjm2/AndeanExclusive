@extends('layouts.app')
@section('titulo', 'Tags de Blogs (Español)')

@section('contenido')
    <div class="container-fluid">
        {{-- Header Senior --}}
        <div class="ae-admin-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="ae-admin-page-title">
                    <i class="fas fa-hashtag" aria-hidden="true"></i>
                    Tags de Blogs (Español)
                </h1>
                <small class="ae-admin-page-desc">Etiquetas temáticas para categorizar y relacionar artículos en el blog en español.</small>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('esblogs.index') }}" class="btn btn-outline-secondary" title="Volver al catálogo de blogs">
                    <i class="fas fa-arrow-left me-1" aria-hidden="true"></i> Blogs ES
                </a>
                <a href="{{ route('entags.index') }}" class="btn btn-outline-secondary" title="Gestionar tags en inglés">
                    <i class="fas fa-language me-1" aria-hidden="true"></i> Tags EN
                </a>
                <a href="{{ route('estags.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus-circle me-1" aria-hidden="true"></i> Nuevo Tag ES
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
                    <i class="fas fa-tags" aria-hidden="true"></i>
                </div>
                <div class="ae-kpi-info">
                    <div class="ae-kpi-val">{{ $tags->count() }}</div>
                    <div class="ae-kpi-label">Tags Totales</div>
                </div>
            </div>
            <div class="ae-kpi-card">
                <div class="ae-kpi-icon is-success">
                    <i class="fas fa-file-alt" aria-hidden="true"></i>
                </div>
                <div class="ae-kpi-info">
                    <div class="ae-kpi-val">{{ $tags->where('blogs_count', '>', 0)->count() }}</div>
                    <div class="ae-kpi-label">Con Artículos</div>
                </div>
            </div>
            <div class="ae-kpi-card">
                <div class="ae-kpi-icon is-info">
                    <i class="fas fa-link" aria-hidden="true"></i>
                </div>
                <div class="ae-kpi-info">
                    <div class="ae-kpi-val">{{ $tags->sum('blogs_count') }}</div>
                    <div class="ae-kpi-label">Asignaciones en Posts</div>
                </div>
            </div>
        </div>

        {{-- Main Table Card --}}
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-header bg-surface py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill px-3 py-2 fw-semibold">
                        {{ $tags->count() }} {{ Str::plural('tag', $tags->count()) }}
                    </span>
                </div>
                <div class="position-relative" style="min-width: 260px;">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="search" id="tagSearchInput" class="form-control form-control-sm ps-5 rounded-pill"
                        placeholder="Filtrar tag o slug...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 ae-content-table" id="tagTable">
                    <thead class="table-light text-uppercase fs-xs fw-bold text-muted border-bottom">
                        <tr>
                            <th scope="col" style="width: 60px;" class="text-center">#</th>
                            <th scope="col">Etiqueta (Tag)</th>
                            <th scope="col">Slug / Enlace Público</th>
                            <th scope="col" class="text-center">Artículos Vinculados</th>
                            <th scope="col" class="text-end pe-3" style="width: 170px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tags as $tag)
                            <tr class="tag-row" data-search="{{ strtolower($tag->nombre . ' ' . $tag->slug) }}">
                                <td class="text-center text-muted fw-bold">{{ $tag->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge rounded-pill bg-light text-primary border border-primary-subtle px-3 py-1.5 fw-bold font-monospace">
                                            #{{ $tag->nombre }}
                                        </span>
                                        <a href="{{ route('estags.edit', $tag->id) }}" class="fw-bold text-dark text-decoration-none ms-1">
                                            {{ $tag->nombre }}
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <code class="small px-2 py-1 bg-light border rounded text-secondary">
                                        /es/tag/{{ $tag->slug }}
                                    </code>
                                </td>
                                <td class="text-center">
                                    @if ($tag->blogs_count > 0)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1">
                                            <i class="fas fa-newspaper me-1"></i> {{ $tag->blogs_count }} {{ Str::plural('artículo', $tag->blogs_count) }}
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted border rounded-pill px-2 py-1">
                                            Sin artículos
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('estag.show', $tag->slug) }}" class="btn btn-outline-secondary" target="_blank" title="Ver en sitio público">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('estags.edit', $tag->id) }}" class="btn btn-outline-primary" title="Editar tag">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" title="Eliminar tag"
                                            onclick="confirmDelete('{{ $tag->id }}', '{{ addslashes($tag->nombre) }}', {{ $tag->blogs_count }})">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $tag->id }}" action="{{ route('estags.destroy', $tag->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-hashtag text-muted fa-3x mb-3 d-block"></i>
                                    <h5 class="text-muted">No hay tags registrados</h5>
                                    <p class="text-muted small">Crea etiquetas para organizar los artículos del blog en español.</p>
                                    <a href="{{ route('estags.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus-circle me-1"></i> Crear Tag
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
        const searchInput = document.getElementById('tagSearchInput');
        const rows = document.querySelectorAll('.tag-row');
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

    function confirmDelete(id, name, count) {
        let msg = `¿Estás seguro de eliminar el tag "#${name}"?`;
        if (count > 0) {
            msg += `\n\nAtención: Está vinculado a ${count} artículo(s). Se desvinculará de ellos pero los artículos no se borrarán.`;
        }
        if (confirm(msg)) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
