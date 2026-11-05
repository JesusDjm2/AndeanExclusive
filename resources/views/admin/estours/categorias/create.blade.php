@extends('layouts.app')
@section('titulo', 'Nueva Categoría (Español)')

@section('contenido')
    <div class="container-fluid ae-content-workspace">
        {{-- Header Senior --}}
        <div class="ae-admin-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="ae-admin-page-title">
                    <i class="fas fa-folder-plus" aria-hidden="true"></i>
                    Nueva Categoría (Español)
                </h1>
                <small class="ae-admin-page-desc">Crea una nueva taxonomía para agrupar experiencias en el catálogo español.</small>
            </div>
            <div>
                <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1" aria-hidden="true"></i> Volver al listado
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-exclamation-triangle fs-5" aria-hidden="true"></i>
                    <strong>Por favor corrige los siguientes errores:</strong>
                </div>
                <ul class="mb-0 ps-4 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <form action="{{ route('categorias.store') }}" method="POST" id="categoryForm">
            @csrf

            <div class="row g-4 justify-content-center">
                <div class="col-lg-8 col-xl-7">
                    <div class="ae-form-card">
                        <div class="ae-form-card-header">
                            <h2 class="ae-form-card-title">
                                <i class="fas fa-tag" aria-hidden="true"></i> Información de la Categoría
                            </h2>
                            <span class="ae-form-card-subtitle">Tours ES</span>
                        </div>
                        <div class="ae-form-card-body">
                            {{-- Nombre --}}
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-bold mb-0" for="nombre">
                                        Nombre de la Categoría <span class="text-danger">*</span>
                                    </label>
                                    <small class="text-muted" id="nombreCount">0 / 80</small>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">
                                        <i class="fas fa-heading"></i>
                                    </span>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                        id="nombre" name="nombre" value="{{ old('nombre') }}"
                                        placeholder="Ej. Tours Exclusivos, Caminatas y Aventura..." required autofocus maxlength="80">
                                </div>
                                @error('nombre')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Nombre visible en menús, filtros de catálogo y páginas públicas.</small>
                            </div>

                            {{-- Slug --}}
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-bold mb-0" for="slug">
                                        Slug / Enlace Permanente <span class="text-danger">*</span>
                                    </label>
                                    <button type="button" class="btn btn-sm btn-link text-decoration-none p-0 text-muted" id="slugLockBtn">
                                        <i class="fas fa-lock me-1"></i> Desbloquear edición
                                    </button>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">
                                        {{ url('/') }}/categoria/
                                    </span>
                                    <input type="text" class="form-control font-monospace bg-light @error('slug') is-invalid @enderror"
                                        id="slug" name="slug" value="{{ old('slug') }}"
                                        placeholder="tours-exclusivos" required readonly>
                                </div>
                                @error('slug')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Identificador único en la URL. Se genera automáticamente.</small>
                            </div>

                            {{-- Live Preview Box --}}
                            <div class="ae-live-preview-box">
                                <span class="ae-preview-label">
                                    <i class="fas fa-eye me-1"></i> Previsualización del Chip Público
                                </span>
                                <div class="ae-preview-chip" id="chipPreview">
                                    <i class="fas fa-tag"></i> <span>Nueva Categoría</span>
                                </div>
                                <div class="mt-2 text-muted small">
                                    Ruta pública: <code class="small text-primary" id="urlPreview">{{ url('/') }}/categoria/nueva-categoria</code>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="fas fa-save me-1" aria-hidden="true"></i> Guardar Categoría
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nombreInput = document.getElementById('nombre');
        const slugInput = document.getElementById('slug');
        const nombreCount = document.getElementById('nombreCount');
        const slugLockBtn = document.getElementById('slugLockBtn');
        const chipText = document.querySelector('#chipPreview span');
        const urlPreview = document.getElementById('urlPreview');
        const baseUrl = "{{ url('/') }}/categoria/";

        let isSlugLocked = true;

        function stringToSlug(str) {
            return str.toLowerCase()
                .trim()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }

        function updatePreviews() {
            const val = nombreInput.value.trim();
            nombreCount.textContent = `${nombreInput.value.length} / 80`;

            if (isSlugLocked) {
                const autoSlug = stringToSlug(val);
                slugInput.value = autoSlug;
                urlPreview.textContent = baseUrl + (autoSlug || 'nueva-categoria');
            } else {
                urlPreview.textContent = baseUrl + (slugInput.value.trim() || 'nueva-categoria');
            }

            chipText.textContent = val || 'Nueva Categoría';
        }

        nombreInput.addEventListener('input', updatePreviews);

        slugInput.addEventListener('input', function() {
            this.value = stringToSlug(this.value);
            urlPreview.textContent = baseUrl + (this.value || 'nueva-categoria');
        });

        slugLockBtn.addEventListener('click', function() {
            isSlugLocked = !isSlugLocked;
            slugInput.readOnly = isSlugLocked;
            slugInput.classList.toggle('bg-light', isSlugLocked);
            this.innerHTML = isSlugLocked
                ? '<i class="fas fa-lock me-1"></i> Desbloquear edición'
                : '<i class="fas fa-lock-open me-1 text-primary"></i> Bloquear edición';
        });

        updatePreviews();
    });
</script>
@endpush
