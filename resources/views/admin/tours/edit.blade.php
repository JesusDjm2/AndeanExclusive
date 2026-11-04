@extends('layouts.app')
@section('titulo', 'Editar Tour: ' . $tour->nombre)

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
                        <li class="breadcrumb-item"><a href="{{ route('tours.index') }}" class="text-decoration-none">Tours</a></li>
                        <li class="breadcrumb-item active text-truncate" style="max-width: 280px;" aria-current="page">{{ $tour->nombre }}</li>
                    </ol>
                </nav>
                <h1 class="ae-admin-page-title">
                    <i class="fas fa-edit" aria-hidden="true"></i>
                    Editar Tour: {{ $tour->nombre }}
                </h1>
                <small class="ae-admin-page-desc">Actualiza el itinerario, precios, imágenes y configuración SEO del tour en inglés.</small>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ url($tour->slug) }}" target="_blank" rel="noopener" class="btn btn-outline-info">
                    <i class="fas fa-external-link-alt me-1"></i> Ver en sitio web
                </a>
                <a href="{{ route('tours.index') }}" class="btn btn-outline-secondary">
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

        <form action="{{ route('tours.update', $tour->id) }}" method="POST" enctype="multipart/form-data" id="tourForm">
            @csrf
            @method('PUT')
            <div class="row g-4">
                {{-- Columna Principal: Contenido Extenso (8 cols) --}}
                <div class="col-lg-8">
                    {{-- 1. Información Principal --}}
                    <div class="ae-form-card mb-4">
                        <div class="ae-form-card-header">
                            <h2 class="ae-form-card-title">
                                <i class="fas fa-info-circle"></i> Información Principal
                            </h2>
                            <span class="ae-form-card-subtitle">Título y recorrido del paquete</span>
                        </div>
                        <div class="ae-form-card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="nombre" class="form-label">
                                        Nombre del Tour <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="nombre" name="nombre"
                                        class="form-control form-control-lg @error('nombre') is-invalid @enderror"
                                        value="{{ old('nombre', $tour->nombre) }}" required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="recorrido" class="form-label">
                                        Recorrido / Ruta <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="recorrido" name="recorrido"
                                        class="form-control @error('recorrido') is-invalid @enderror"
                                        value="{{ old('recorrido', $tour->recorrido) }}" required>
                                    @error('recorrido')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Contenido Editorial con Pestañas --}}
                    <div class="ae-form-card mb-4">
                        <div class="ae-form-card-header">
                            <h2 class="ae-form-card-title">
                                <i class="fas fa-file-alt"></i> Contenido y Experiencia
                            </h2>
                            <span class="ae-form-card-subtitle">Itinerario y detalles para el viajero</span>
                        </div>
                        <div class="ae-form-card-body">
                            <ul class="nav nav-tabs ae-content-tabs" id="tourContentTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="tab-presentacion-btn" data-bs-toggle="tab"
                                        data-bs-target="#tab-presentacion" type="button" role="tab">
                                        <i class="fas fa-align-left"></i> Presentación
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-itinerario-btn" data-bs-toggle="tab"
                                        data-bs-target="#tab-itinerario" type="button" role="tab">
                                        <i class="fas fa-route"></i> Itinerario Detallado
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-incluye-btn" data-bs-toggle="tab"
                                        data-bs-target="#tab-incluye" type="button" role="tab">
                                        <i class="fas fa-check-circle"></i> Qué Incluye
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-importante-btn" data-bs-toggle="tab"
                                        data-bs-target="#tab-importante" type="button" role="tab">
                                        <i class="fas fa-clipboard-list"></i> Importante / Notas
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content pt-2" id="tourContentTabsContent">
                                {{-- Presentación --}}
                                <div class="tab-pane fade show active" id="tab-presentacion" role="tabpanel">
                                    <label class="form-label small text-muted mb-2">Introducción y experiencia que vivirá el pasajero:</label>
                                    <textarea class="ckeditor form-control" name="presentacion" id="presentacion" rows="6">{{ old('presentacion', $tour->presentacion) }}</textarea>
                                    @error('presentacion')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Itinerario --}}
                                <div class="tab-pane fade" id="tab-itinerario" role="tabpanel">
                                    <label class="form-label small text-muted mb-2">Plan día por día (Día 1, Día 2, etc.):</label>
                                    <textarea class="ckeditor form-control" name="itinerario" id="itinerario" rows="8">{{ old('itinerario', $tour->itinerario) }}</textarea>
                                    @error('itinerario')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Incluye --}}
                                <div class="tab-pane fade" id="tab-incluye" role="tabpanel">
                                    <label class="form-label small text-muted mb-2">Servicios, traslados, guiados y alojamientos incluidos:</label>
                                    <textarea class="ckeditor form-control" name="incluye" id="incluye" rows="6">{{ old('incluye', $tour->incluye) }}</textarea>
                                    @error('incluye')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Importante --}}
                                <div class="tab-pane fade" id="tab-importante" role="tabpanel">
                                    <label class="form-label small text-muted mb-2">Recomendaciones, altitud, qué llevar y advertencias:</label>
                                    <textarea class="ckeditor form-control" name="importante" id="importante" rows="6">{{ old('importante', $tour->importante) }}</textarea>
                                    @error('importante')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Mapa Interactivo --}}
                    <div class="ae-form-card mb-4">
                        <div class="ae-form-card-header">
                            <h2 class="ae-form-card-title">
                                <i class="fas fa-map"></i> Mapa o Código Embed
                            </h2>
                            <span class="ae-form-card-subtitle">Iframe de Google MyMaps o mapa interactivo (opcional)</span>
                        </div>
                        <div class="ae-form-card-body">
                            <textarea name="mapa" id="mapa" class="form-control font-monospace small" rows="3"
                                placeholder='<iframe src="https://www.google.com/maps/d/embed?mid=..." width="100%" height="450"></iframe>'>{{ old('mapa', $tour->mapa) }}</textarea>
                            <small class="text-muted d-block mt-1">Pega el código HTML del iframe o la URL del mapa compartido.</small>
                            @error('mapa')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Columna Lateral: Logística, Imágenes, Categorías y SEO (4 cols) --}}
                <div class="col-lg-4">
                    <div class="ae-sticky-sidebar">
                        {{-- 1. Tarjeta de Publicación --}}
                        <div class="ae-form-card mb-3">
                            <div class="ae-form-card-header bg-primary-subtle">
                                <h2 class="ae-form-card-title text-primary">
                                    <i class="fas fa-paper-plane"></i> Actualización
                                </h2>
                                <span class="badge bg-primary text-white">ID #{{ $tour->id }}</span>
                            </div>
                            <div class="ae-form-card-body">
                                <button type="submit" class="btn btn-primary w-100 py-2 mb-2 shadow-sm">
                                    <i class="fas fa-save me-1"></i> Guardar Cambios
                                </button>
                                <a href="{{ route('tours.index') }}" class="btn btn-outline-secondary w-100">
                                    Cancelar
                                </a>
                            </div>
                        </div>

                        {{-- 2. Duración y Precio --}}
                        <div class="ae-form-card mb-3">
                            <div class="ae-form-card-header">
                                <h2 class="ae-form-card-title">
                                    <i class="fas fa-coins"></i> Tarifas & Duración
                                </h2>
                            </div>
                            <div class="ae-form-card-body">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label for="dias" class="form-label">Días <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            <input type="number" id="dias" name="dias" min="1" max="99"
                                                class="form-control @error('dias') is-invalid @enderror"
                                                value="{{ old('dias', $tour->dias) }}" required>
                                        </div>
                                        @error('dias')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="precio" class="form-label">Precio Base <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" id="precio" name="precio" min="0"
                                                class="form-control @error('precio') is-invalid @enderror"
                                                value="{{ old('precio', $tour->precio) }}" required>
                                        </div>
                                        @error('precio')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Categorías --}}
                        <div class="ae-form-card mb-3">
                            <div class="ae-form-card-header">
                                <h2 class="ae-form-card-title">
                                    <i class="fas fa-tags"></i> Categorías
                                </h2>
                                <span class="ae-form-card-subtitle">Selecciona una o más</span>
                            </div>
                            <div class="ae-form-card-body">
                                @php
                                    $selectedCategories = old('categorias', $tour->categorias->pluck('id')->all());
                                @endphp
                                <div class="ae-chips-selector">
                                    @foreach ($categorias as $id => $nombre)
                                        <div class="ae-chip-checkbox">
                                            <input type="checkbox" id="cat_{{ $id }}" name="categorias[]"
                                                value="{{ $id }}" @checked(is_array($selectedCategories) && in_array($id, $selectedCategories))>
                                            <label for="cat_{{ $id }}">{{ $nombre }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('categorias')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- 4. Imágenes Multimedia --}}
                        <div class="ae-form-card mb-3">
                            <div class="ae-form-card-header">
                                <h2 class="ae-form-card-title">
                                    <i class="fas fa-images"></i> Imágenes del Tour
                                </h2>
                            </div>
                            <div class="ae-form-card-body">
                                {{-- Thumbnail --}}
                                <div class="mb-3">
                                    <label class="form-label d-flex justify-content-between align-items-center">
                                        <span>Miniatura / Tarjeta</span>
                                        <small class="badge bg-light text-muted border">420 x 280 px</small>
                                    </label>
                                    <div class="ae-image-uploader {{ $tour->imgThumb ? 'has-image' : '' }}" id="uploaderThumb">
                                        <input type="file" accept=".jpg,.jpeg,.png,.webp,.gif" id="imgThumb" name="imgThumb">
                                        <div class="ae-upload-placeholder {{ $tour->imgThumb ? 'd-none' : '' }}" id="placeholderThumb">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Subir miniatura</span>
                                            <small>Clic para seleccionar archivo</small>
                                        </div>
                                        <div class="ae-image-preview-wrapper {{ $tour->imgThumb ? '' : 'd-none' }}" id="previewThumbWrapper">
                                            <img src="{{ $tour->imgThumb ? asset($tour->imgThumb) : '#' }}" id="imgThumbPreview" alt="Miniatura actual">
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
                                        <span>Portada / Cabecera</span>
                                        <small class="badge bg-light text-muted border">1920 x 1080 px</small>
                                    </label>
                                    <div class="ae-image-uploader {{ $tour->imgFull ? 'has-image' : '' }}" id="uploaderFull">
                                        <input type="file" accept=".jpg,.jpeg,.png,.webp,.gif" id="imgFull" name="imgFull">
                                        <div class="ae-upload-placeholder {{ $tour->imgFull ? 'd-none' : '' }}" id="placeholderFull">
                                            <i class="fas fa-image"></i>
                                            <span>Subir portada</span>
                                            <small>Clic para seleccionar archivo</small>
                                        </div>
                                        <div class="ae-image-preview-wrapper {{ $tour->imgFull ? '' : 'd-none' }}" id="previewFullWrapper">
                                            <img src="{{ $tour->imgFull ? asset($tour->imgFull) : '#' }}" id="imgFullPreview" alt="Portada actual">
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

                        {{-- 5. SEO & Metadatos --}}
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
                                            value="{{ old('slug', $tour->slug) }}" required>
                                    </div>
                                    @error('slug')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Descripción Corta --}}
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label for="descripcionCorta" class="form-label mb-0">Descripción Corta</label>
                                        <small class="text-muted"><span id="charCountDesc">0</span> / 255 car.</small>
                                    </div>
                                    <textarea id="descripcionCorta" name="descripcionCorta" maxlength="255" rows="3"
                                        class="form-control @error('descripcionCorta') is-invalid @enderror"
                                        required>{{ old('descripcionCorta', $tour->descripcionCorta) }}</textarea>
                                    @error('descripcionCorta')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Keywords --}}
                                <div>
                                    <label for="keywords" class="form-label">Palabras Clave (SEO)</label>
                                    <input type="text" id="keywords" name="keywords"
                                        class="form-control @error('keywords') is-invalid @enderror"
                                        value="{{ old('keywords', $tour->keywords) }}" required>
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
            // Inicializar CKEditor con toolbar completo
            if (typeof CKEDITOR !== 'undefined') {
                const editorOptions = {
                    height: 240,
                    toolbar: [
                        { name: 'document', items: ['Source'] },
                        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
                        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Blockquote'] },
                        { name: 'links', items: ['Link', 'Unlink'] },
                        { name: 'insert', items: ['Table', 'HorizontalRule'] },
                        { name: 'styles', items: ['Format', 'FontSize'] },
                        { name: 'colors', items: ['TextColor', 'BGColor'] },
                        { name: 'tools', items: ['Maximize'] }
                    ]
                };

                ['presentacion', 'itinerario', 'incluye', 'importante'].forEach(id => {
                    if (document.getElementById(id)) {
                        CKEDITOR.replace(id, editorOptions);
                    }
                });
            }

            // Contador de caracteres para descripción corta
            const descInput = document.getElementById('descripcionCorta');
            const charCount = document.getElementById('charCountDesc');
            if (descInput && charCount) {
                const updateCount = () => { charCount.textContent = descInput.value.length; };
                descInput.addEventListener('input', updateCount);
                updateCount();
            }

            // Previews interactivos de imágenes
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
