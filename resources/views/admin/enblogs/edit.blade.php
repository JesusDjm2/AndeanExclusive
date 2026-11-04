@extends('layouts.app')
@section('titulo', 'Editar Artículo: ' . $blog->nombre)

@push('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.9.2/ckeditor.js"></script>
@endpush

@section('contenido')
    <div class="container-fluid ae-content-workspace">
        {{-- Header Senior --}}
        <div class="ae-admin-page-header d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="{{ route('enblogs.index') }}" class="text-decoration-none">Blogs en Inglés</a></li>
                        <li class="breadcrumb-item active text-truncate" style="max-width: 280px;" aria-current="page">{{ $blog->nombre }}</li>
                    </ol>
                </nav>
                <h1 class="ae-admin-page-title">
                    <i class="fas fa-edit" aria-hidden="true"></i>
                    Editar Artículo: {{ $blog->nombre }}
                </h1>
                <small class="ae-admin-page-desc">Actualiza el contenido, imágenes, etiquetas y optimización SEO del post en inglés.</small>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('enblog.show', $blog->slug) }}" target="_blank" rel="noopener" class="btn btn-outline-info">
                    <i class="fas fa-external-link-alt me-1"></i> Ver en sitio web
                </a>
                <a href="{{ route('enblogs.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver al listado
                </a>
            </div>
        </div>

        {{-- Errores de Validación Generales --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-lg me-3"></i>
                    <div>
                        <strong class="d-block">Por favor corrige los errores señalados en el formulario:</strong>
                        <ul class="mb-0 small ps-3 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <form action="{{ route('enblogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data" id="blogForm">
            @csrf
            @method('PUT')
            <div class="row g-4">
                {{-- Columna Principal: Contenido del Artículo (8 cols) --}}
                <div class="col-lg-8">
                    {{-- 1. Título y Resumen --}}
                    <div class="ae-form-card mb-4">
                        <div class="ae-form-card-header">
                            <h2 class="ae-form-card-title">
                                <i class="fas fa-heading"></i> Título y Resumen
                            </h2>
                            <span class="ae-form-card-subtitle">Encabezado y extracto del artículo</span>
                        </div>
                        <div class="ae-form-card-body">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">
                                    Título del Artículo <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="nombre" name="nombre"
                                    class="form-control form-control-lg @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre', $blog->nombre) }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="descripcionCorta" class="form-label mb-0">
                                        Descripción Corta / Extracto <span class="text-danger">*</span>
                                    </label>
                                    <small class="text-muted"><span id="charCountDesc">0</span> / 255 car.</small>
                                </div>
                                <textarea id="descripcionCorta" name="descripcionCorta" maxlength="255" rows="3"
                                    class="form-control @error('descripcionCorta') is-invalid @enderror"
                                    required>{{ old('descripcionCorta', $blog->descripcionCorta) }}</textarea>
                                @error('descripcionCorta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- 2. Cuerpo Completo del Artículo --}}
                    <div class="ae-form-card mb-4">
                        <div class="ae-form-card-header">
                            <h2 class="ae-form-card-title">
                                <i class="fas fa-paragraph"></i> Cuerpo del Artículo
                            </h2>
                            <span class="ae-form-card-subtitle">Editor de contenido enriquecido</span>
                        </div>
                        <div class="ae-form-card-body">
                            <textarea class="ckeditor form-control" name="descripcion" id="descripcion" rows="14" required>{{ old('descripcion', $blog->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Columna Lateral: Publicación, Imágenes, Tags y SEO (4 cols) --}}
                <div class="col-lg-4">
                    <div class="ae-sticky-sidebar">
                        {{-- 1. Tarjeta de Publicación --}}
                        <div class="ae-form-card mb-3">
                            <div class="ae-form-card-header bg-primary-subtle">
                                <h2 class="ae-form-card-title text-primary">
                                    <i class="fas fa-paper-plane"></i> Actualización
                                </h2>
                                <span class="badge bg-primary text-white">ID #{{ $blog->id }}</span>
                            </div>
                            <div class="ae-form-card-body">
                                <button type="submit" class="btn btn-primary w-100 py-2 mb-2 shadow-sm">
                                    <i class="fas fa-save me-1"></i> Guardar Cambios
                                </button>
                                <a href="{{ route('enblogs.index') }}" class="btn btn-outline-secondary w-100">
                                    Cancelar
                                </a>
                            </div>
                        </div>

                        {{-- 2. Etiquetas (Tags) --}}
                        <div class="ae-form-card mb-3">
                            <div class="ae-form-card-header">
                                <h2 class="ae-form-card-title">
                                    <i class="fas fa-tags"></i> Etiquetas (Tags)
                                </h2>
                                <span class="ae-form-card-subtitle">Temáticas del post</span>
                            </div>
                            <div class="ae-form-card-body">
                                @php
                                    $selectedTags = old('tags', $blog->tags->pluck('id')->all());
                                @endphp
                                <div class="ae-chips-selector">
                                    @foreach ($tags as $id => $nombre)
                                        <div class="ae-chip-checkbox">
                                            <input type="checkbox" id="tag_{{ $id }}" name="tags[]"
                                                value="{{ $id }}" @checked(is_array($selectedTags) && in_array($id, $selectedTags))>
                                            <label for="tag_{{ $id }}">{{ $nombre }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('tags')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- 3. Imágenes Multimedia --}}
                        <div class="ae-form-card mb-3">
                            <div class="ae-form-card-header">
                                <h2 class="ae-form-card-title">
                                    <i class="fas fa-images"></i> Imágenes del Artículo
                                </h2>
                            </div>
                            <div class="ae-form-card-body">
                                {{-- Thumbnail --}}
                                <div class="mb-3">
                                    <label class="form-label d-flex justify-content-between align-items-center">
                                        <span>Miniatura de Tarjeta</span>
                                        <small class="badge bg-light text-muted border">420 x 280 px</small>
                                    </label>
                                    <div class="ae-image-uploader {{ $blog->imgThumb ? 'has-image' : '' }}" id="uploaderThumb">
                                        <input type="file" accept=".jpg,.jpeg,.png,.webp,.gif" id="imgThumb" name="imgThumb">
                                        <div class="ae-upload-placeholder {{ $blog->imgThumb ? 'd-none' : '' }}" id="placeholderThumb">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Subir miniatura</span>
                                            <small>Clic para seleccionar archivo</small>
                                        </div>
                                        <div class="ae-image-preview-wrapper {{ $blog->imgThumb ? '' : 'd-none' }}" id="previewThumbWrapper">
                                            <img src="{{ $blog->imgThumb ? asset('img/thumb/' . $blog->imgThumb) : '#' }}" id="imgThumbPreview" alt="Miniatura actual">
                                            <div class="ae-image-overlay">
                                                <i class="fas fa-sync-alt"></i> Reemplazar imagen
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1">Deja vacío para conservar la imagen actual.</small>
                                    @error('imgThumb')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Cover Full --}}
                                <div>
                                    <label class="form-label d-flex justify-content-between align-items-center">
                                        <span>Portada Principal</span>
                                        <small class="badge bg-light text-muted border">1200 x 630 px</small>
                                    </label>
                                    <div class="ae-image-uploader {{ $blog->imgFull ? 'has-image' : '' }}" id="uploaderFull">
                                        <input type="file" accept=".jpg,.jpeg,.png,.webp,.gif" id="imgFull" name="imgFull">
                                        <div class="ae-upload-placeholder {{ $blog->imgFull ? 'd-none' : '' }}" id="placeholderFull">
                                            <i class="fas fa-image"></i>
                                            <span>Subir portada</span>
                                            <small>Clic para seleccionar archivo</small>
                                        </div>
                                        <div class="ae-image-preview-wrapper {{ $blog->imgFull ? '' : 'd-none' }}" id="previewFullWrapper">
                                            <img src="{{ $blog->imgFull ? asset('img/full/' . $blog->imgFull) : '#' }}" id="imgFullPreview" alt="Portada actual">
                                            <div class="ae-image-overlay">
                                                <i class="fas fa-sync-alt"></i> Reemplazar imagen
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1">Deja vacío para conservar la imagen actual.</small>
                                    @error('imgFull')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- 4. SEO & Metadatos --}}
                        <div class="ae-form-card mb-3">
                            <div class="ae-form-card-header">
                                <h2 class="ae-form-card-title">
                                    <i class="fas fa-search-dollar"></i> SEO & Enlace
                                </h2>
                            </div>
                            <div class="ae-form-card-body">
                                {{-- Slug con bloqueo --}}
                                <div class="mb-3">
                                    <label for="slug" class="form-label d-flex justify-content-between">
                                        <span>URL Amigable (Slug) <span class="text-danger">*</span></span>
                                        <small class="text-muted">Ruta única</small>
                                    </label>
                                    <div class="input-group ae-slug-group">
                                        <span class="input-group-text">/</span>
                                        <input type="text" id="slug" name="slug"
                                            class="form-control font-monospace small @error('slug') is-invalid @enderror"
                                            value="{{ old('slug', $blog->slug) }}" required>
                                    </div>
                                    @error('slug')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Keywords --}}
                                <div>
                                    <label for="keywords" class="form-label">Palabras Clave (SEO)</label>
                                    <input type="text" id="keywords" name="keywords"
                                        class="form-control @error('keywords') is-invalid @enderror"
                                        value="{{ old('keywords', $blog->keywords) }}">
                                    <small class="text-muted">Separadas por comas (,)</small>
                                    @error('keywords')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
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
            if (typeof CKEDITOR !== 'undefined') {
                CKEDITOR.replace('descripcion', {
                    height: 380,
                    toolbar: [
                        { name: 'document', items: ['Source'] },
                        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
                        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Blockquote'] },
                        { name: 'links', items: ['Link', 'Unlink'] },
                        { name: 'insert', items: ['Image', 'Table', 'HorizontalRule'] },
                        { name: 'styles', items: ['Format', 'FontSize'] },
                        { name: 'colors', items: ['TextColor', 'BGColor'] },
                        { name: 'tools', items: ['Maximize'] }
                    ]
                });
            }

            const descInput = document.getElementById('descripcionCorta');
            const charCount = document.getElementById('charCountDesc');
            if (descInput && charCount) {
                const updateCount = () => { charCount.textContent = descInput.value.length; };
                descInput.addEventListener('input', updateCount);
                updateCount();
            }

            function setupPreview(inputId, wrapperId, imgId, placeholderId, uploaderId) {
                const input = document.getElementById(inputId);
                const wrapper = document.getElementById(wrapperId);
                const img = document.getElementById(imgId);
                const placeholder = document.getElementById(placeholderId);
                const uploader = document.getElementById(uploaderId);

                if (!input) return;

                input.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            img.src = e.target.result;
                            wrapper.classList.remove('d-none');
                            if (placeholder) placeholder.classList.add('d-none');
                            uploader.classList.add('has-image');
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }

            setupPreview('imgThumb', 'previewThumbWrapper', 'imgThumbPreview', 'placeholderThumb', 'uploaderThumb');
            setupPreview('imgFull', 'previewFullWrapper', 'imgFullPreview', 'placeholderFull', 'uploaderFull');
        });
    </script>
@endpush
