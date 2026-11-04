@extends('layouts.app')
@section('titulo', 'Editar Tag: #' . $tag->nombre)

@section('contenido')
    <div class="container-fluid ae-content-workspace">
        {{-- Header Senior --}}
        <div class="ae-admin-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="ae-admin-page-title">
                    <i class="fas fa-edit" aria-hidden="true"></i>
                    Editar Tag: #{{ $tag->nombre }}
                </h1>
                <small class="ae-admin-page-desc">ID #{{ $tag->id }} · Modificación de etiqueta temática para el blog en inglés.</small>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('entag.show', $tag->slug) }}" class="btn btn-outline-secondary" target="_blank" title="Ver en sitio web público">
                    <i class="fas fa-external-link-alt me-1" aria-hidden="true"></i> Ver en web
                </a>
                <a href="{{ route('entags.index') }}" class="btn btn-outline-secondary">
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

        <form action="{{ route('entags.update', $tag->id) }}" method="POST" id="tagForm">
            @csrf
            @method('PUT')

            <div class="row g-4 justify-content-center">
                <div class="col-lg-8 col-xl-7">
                    <div class="ae-form-card">
                        <div class="ae-form-card-header">
                            <h2 class="ae-form-card-title">
                                <i class="fas fa-hashtag" aria-hidden="true"></i> Información del Tag
                            </h2>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1">
                                <i class="fas fa-newspaper me-1"></i> {{ $tag->blogs_count }} {{ Str::plural('artículo', $tag->blogs_count) }} vinculados
                            </span>
                        </div>
                        <div class="ae-form-card-body">
                            {{-- Nombre --}}
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-bold mb-0" for="nombre">
                                        Nombre del Tag <span class="text-danger">*</span>
                                    </label>
                                    <small class="text-muted" id="nombreCount">0 / 60</small>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">
                                        <i class="fas fa-hashtag"></i>
                                    </span>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                        id="nombre" name="nombre" value="{{ old('nombre', $tag->nombre) }}"
                                        placeholder="Ej. Magic Places..." required autofocus maxlength="60">
                                </div>
                                @error('nombre')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Nombre de la etiqueta visible en los artículos y filtros temáticos.</small>
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
                                        {{ url('/') }}/en/tag/
                                    </span>
                                    <input type="text" class="form-control font-monospace bg-light @error('slug') is-invalid @enderror"
                                        id="slug" name="slug" value="{{ old('slug', $tag->slug) }}"
                                        placeholder="magic-places" required readonly>
                                </div>
                                @error('slug')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Cambiar el slug modificará la URL pública canónica.</small>
                            </div>

                            {{-- Live Preview Box --}}
                            <div class="ae-live-preview-box">
                                <span class="ae-preview-label">
                                    <i class="fas fa-eye me-1"></i> Previsualización del Tag
                                </span>
                                <div class="ae-preview-chip" id="chipPreview">
                                    <i class="fas fa-hashtag"></i> <span>{{ $tag->nombre }}</span>
                                </div>
                                <div class="mt-2 text-muted small">
                                    Ruta pública: <code class="small text-primary" id="urlPreview">{{ url('/') }}/en/tag/{{ $tag->slug }}</code>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <a href="{{ route('entags.index') }}" class="btn btn-outline-secondary">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="fas fa-save me-1" aria-hidden="true"></i> Actualizar Tag
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
        const baseUrl = "{{ url('/') }}/en/tag/";

        let isSlugLocked = true;

        function stringToSlug(str) {
            return str.toLowerCase()
                .trim()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }

        nombreInput.addEventListener('input', function() {
            nombreCount.textContent = `${this.value.length} / 60`;
            chipText.textContent = this.value.trim() || 'Tag';
        });

        slugInput.addEventListener('input', function() {
            this.value = stringToSlug(this.value);
            urlPreview.textContent = baseUrl + (this.value || 'tag');
        });

        slugLockBtn.addEventListener('click', function() {
            isSlugLocked = !isSlugLocked;
            slugInput.readOnly = isSlugLocked;
            slugInput.classList.toggle('bg-light', isSlugLocked);
            this.innerHTML = isSlugLocked
                ? '<i class="fas fa-lock me-1"></i> Desbloquear edición'
                : '<i class="fas fa-lock-open me-1 text-primary"></i> Bloquear edición';
        });

        nombreCount.textContent = `${nombreInput.value.length} / 60`;
    });
</script>
@endpush
