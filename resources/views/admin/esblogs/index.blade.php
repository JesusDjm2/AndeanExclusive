@extends('layouts.app')
@section('titulo', 'Blogs en Español')

@section('contenido')
    <div class="container-fluid">
        {{-- Header Senior --}}
        <div class="ae-admin-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="ae-admin-page-title">
                    <i class="fas fa-newspaper" aria-hidden="true"></i>
                    Blogs en Español
                </h1>
                <small class="ae-admin-page-desc">Artículos, guías turísticas y contenidos editoriales en español.</small>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('enblogs.index') }}" class="btn btn-outline-secondary" title="Gestionar blogs en inglés">
                    <i class="fas fa-globe-americas me-1" aria-hidden="true"></i> Ver Blogs en Inglés
                </a>
                <a href="{{ route('estags.index') }}" class="btn btn-outline-secondary" title="Gestionar etiquetas">
                    <i class="fas fa-tags me-1" aria-hidden="true"></i> Etiquetas (Tags)
                </a>
                <a href="{{ route('esblogs.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus-circle me-1" aria-hidden="true"></i> Nuevo Artículo ES
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
                            <input type="text" id="buscadorBlogs" class="form-control border-start-0 ps-0"
                                placeholder="Buscar artículo por título o slug..." autocomplete="off">
                            <button class="btn btn-outline-secondary border-start-0" type="button" id="btnLimpiarFiltro" title="Limpiar búsqueda">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select id="filtroTag" class="form-select">
                            <option value="">Todas las etiquetas ({{ $tags->count() }})</option>
                            @foreach ($tags as $tag)
                                <option value="{{ strtolower($tag->nombre) }}">{{ $tag->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">
                            Mostrando <span id="contadorVisible" class="text-primary fw-bold">{{ $blogs->count() }}</span> artículos
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de Blogs --}}
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 ae-admin-data-table ae-content-table" id="tablaBlogs">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 48px;" class="text-center">#</th>
                                <th style="width: 90px;">Portada</th>
                                <th>Título & Resumen</th>
                                <th>Etiquetas (Tags)</th>
                                <th style="width: 180px;">Versión Inglés</th>
                                <th style="width: 130px;" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyBlogs">
                            @forelse ($blogs as $index => $blog)
                                @php
                                    $tagsNombres = $blog->tags->pluck('nombre')->implode(', ');
                                    $hasEnRelation = $blog->blog !== null;
                                @endphp
                                <tr class="blog-row"
                                    data-nombre="{{ strtolower($blog->nombre . ' ' . $blog->slug . ' ' . $blog->descripcionCorta) }}"
                                    data-tags="{{ strtolower($tagsNombres) }}">
                                    <td class="text-center text-muted small fw-semibold">{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ asset('img/full/' . $blog->imgFull) }}" target="_blank" title="Ver imagen completa">
                                            <img src="{{ asset('img/thumb/' . $blog->imgThumb) }}"
                                                alt="{{ $blog->nombre }}" class="ae-table-thumb"
                                                onerror="this.src='{{ asset('img/no-image.jpg') }}'">
                                        </a>
                                    </td>
                                    <td class="ae-title-cell">
                                        <a href="{{ route('esblogs.edit', $blog->id) }}" class="ae-title-link">
                                            {{ $blog->nombre }}
                                        </a>
                                        <p class="text-muted small mb-1 line-clamp-2" style="max-width: 450px;">
                                            {{ Str::limit($blog->descripcionCorta, 100) }}
                                        </p>
                                        <div class="ae-title-meta">
                                            <span><i class="fas fa-link text-muted"></i> <code>/blog-peru/{{ $blog->slug }}</code></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @forelse ($blog->tags as $tag)
                                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                                    {{ $tag->nombre }}
                                                </span>
                                            @empty
                                                <span class="text-muted small">Sin tags</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>
                                        @if ($hasEnRelation)
                                            <a href="{{ route('enblogs.edit', $blog->blog->id) }}"
                                                class="ae-relation-badge is-linked text-decoration-none"
                                                title="Editar versión en inglés">
                                                <i class="fas fa-check-circle"></i>
                                                <span class="text-truncate" style="max-width: 140px;">{{ $blog->blog->nombre }}</span>
                                            </a>
                                        @else
                                            <span class="ae-relation-badge is-unlinked" title="Aún no tiene blog en inglés vinculado">
                                                <i class="fas fa-exclamation-circle"></i> Sin vincular
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('esblog.show', $blog->slug) }}" target="_blank" rel="noopener"
                                                class="btn btn-outline-secondary" title="Ver artículo en sitio web">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            <a href="{{ route('esblogs.edit', $blog->id) }}"
                                                class="btn btn-outline-primary" title="Editar artículo">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger"
                                                title="Eliminar artículo"
                                                onclick="confirmarEliminar('{{ $blog->id }}', '{{ addslashes($blog->nombre) }}')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $blog->id }}"
                                            action="{{ route('esblogs.destroy', $blog->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="admin-empty">
                                            <i class="fas fa-newspaper mb-3"></i>
                                            <h5>No hay artículos en español registrados</h5>
                                            <p class="text-muted">Comienza redactando el primer artículo para el blog en español.</p>
                                            <a href="{{ route('esblogs.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus-circle me-1"></i> Crear Artículo
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
                        <h5>No se encontraron artículos</h5>
                        <p class="text-muted">Ningún post coincide con el filtro de búsqueda aplicado.</p>
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
                    <p class="mb-2">¿Estás seguro de que deseas eliminar permanentemente este artículo de blog?</p>
                    <h6 class="fw-bold text-danger mb-3" id="nombreBlogEliminar"></h6>
                    <small class="text-muted">Esta acción no se puede deshacer y retirará el contenido del sitio público.</small>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar Artículo</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buscador = document.getElementById('buscadorBlogs');
            const filtroTag = document.getElementById('filtroTag');
            const btnLimpiar = document.getElementById('btnLimpiarFiltro');
            const rows = document.querySelectorAll('.blog-row');
            const noResultados = document.getElementById('noResultados');
            const contadorVisible = document.getElementById('contadorVisible');

            function filtrar() {
                const q = buscador.value.trim().toLowerCase();
                const tag = filtroTag.value.trim().toLowerCase();
                let visibles = 0;

                rows.forEach(row => {
                    const nombre = row.getAttribute('data-nombre') || '';
                    const tags = row.getAttribute('data-tags') || '';

                    const matchQ = !q || nombre.includes(q);
                    const matchTag = !tag || tags.includes(tag);

                    if (matchQ && matchTag) {
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
            filtroTag.addEventListener('change', filtrar);
            btnLimpiar.addEventListener('click', function() {
                buscador.value = '';
                filtroTag.value = '';
                filtrar();
            });

            window.resetFiltros = function() {
                buscador.value = '';
                filtroTag.value = '';
                filtrar();
            };

            let blogIdToDelete = null;
            const modalEl = document.getElementById('modalEliminar');
            const modalInstance = new bootstrap.Modal(modalEl);

            window.confirmarEliminar = function(id, nombre) {
                blogIdToDelete = id;
                document.getElementById('nombreBlogEliminar').textContent = nombre;
                modalInstance.show();
            };

            document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
                if (blogIdToDelete) {
                    const form = document.getElementById('delete-form-' + blogIdToDelete);
                    if (form) form.submit();
                }
            });
        });
    </script>
@endpush
