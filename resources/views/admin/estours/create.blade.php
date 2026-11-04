@extends('layouts.app')
@section('titulo', 'Crear Nuevo Tour ES')

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
                        <li class="breadcrumb-item"><a href="{{ route('estours.index') }}" class="text-decoration-none">Tours en Español</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nuevo Tour ES</li>
                    </ol>
                </nav>
                <h1 class="ae-admin-page-title">
                    <i class="fas fa-plus-circle" aria-hidden="true"></i>
                    Crear Nuevo Tour en Español
                </h1>
                <small class="ae-admin-page-desc">Define el itinerario, categorías, tarifas y contenido multimedia para el catálogo en español.</small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('estours.index') }}" class="btn btn-outline-secondary">
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

        <form action="{{ route('estours.store') }}" method="POST" enctype="multipart/form-data" id="tourForm">
            @csrf
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
                                        value="{{ old('nombre') }}" placeholder="Ej. Experiencia de Lujo en Machu Picchu 4 Días" required>
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
                                        value="{{ old('recorrido') }}" placeholder="Ej. Lima - Cusco - Valle Sagrado - Machu Picchu" required>
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
                                    <textarea class="ckeditor form-control" name="presentacion" id="presentacion" rows="6">{{ old('presentacion') }}</textarea>
                                    @error('presentacion')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Itinerario --}}
                                <div class="tab-pane fade" id="tab-itinerario" role="tabpanel">
                                    <label class="form-label small text-muted mb-2">Plan día por día (Día 1, Día 2, etc.):</label>
                                    <textarea class="ckeditor form-control" name="itinerario" id="itinerario" rows="8">{{ old('itinerario') }}</textarea>
                                    @error('itinerario')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Incluye --}}
                                <div class="tab-pane fade" id="tab-incluye" role="tabpanel">
                                    <label class="form-label small text-muted mb-2">Servicios, traslados, guiados y alojamientos incluidos:</label>
                                    <textarea class="ckeditor form-control" name="incluye" id="incluye" rows="6">{{ old('incluye') }}</textarea>
                                    @error('incluye')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Importante --}}
                                <div class="tab-pane fade" id="tab-importante" role="tabpanel">
                                    <label class="form-label small text-muted mb-2">Recomendaciones, altitud, qué llevar y advertencias:</label>
                                    <textarea class="ckeditor form-control" name="importante" id="importante" rows="6">{{ old('importante') }}</textarea>
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
                                placeholder='<iframe src="https://www.google.com/maps/d/embed?mid=..." width="100%" height="450"></iframe>'>{{ old('mapa') }}</textarea>
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
                            <div class="ae-form-card-header bg-success-subtle">
                                <h2 class="ae-form-card-title text-success">
                                    <i class="fas fa-paper-plane"></i> Publicación
                                </h2>
                                <span class="badge bg-success text-white">Español</span>
                            </div>
                            <div class="ae-form-card-body">
                                <button type="submit" class="btn btn-success text-white w-100 py-2 mb-2 shadow-sm">
                                    <i class="fas fa-save me-1"></i> Guardar y Publicar Tour ES
                                </button>
                                <a href="{{ route('estours.index') }}" class="btn btn-outline-secondary w-100">
                                    Cancelar
                                </a>
                            </div>
                        </div>

                        {{-- 2. Vinculación con Tour en Inglés (Hreflang) --}}
                        <div class="ae-form-card mb-3">
                            <div class="ae-form-card-header">
                                <h2 class="ae-form-card-title">
                                    <i class="fas fa-globe-americas"></i> Tour en Inglés
                                </h2>
                                <span class="ae-form-card-subtitle">Homólogo EN</span>
                            </div>
                            <div class="ae-form-card-body">
                                <label for="relacionado_id" class="form-label small">
                                    Selecciona el tour en inglés equivalente <span class="text-danger">*</span>
                                </label>
                                <select id="relacionado_id" name="relacionado_id"
                                    class="form-select @error('relacionado_id') is-invalid @enderror" required>
                                    <option value="">-- Seleccionar Tour EN --</option>
                                    @foreach ($entours as $entour)
                                        <option value="{{ $entour->id }}" @selected(old('relacionado_id') == $entour->id)>
                                            {{ $entour->nombre }} ({{ $entour->dias }}d)
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-1">Permite a los usuarios cambiar de idioma en el sitio web automáticamente.</small>
                                @error('relacionado_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- 3. Duración y Precio --}}
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
                                                value="{{ old('dias', 1) }}" required>
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
                                                value="{{ old('precio') }}" placeholder="0.00" required>
                                        </div>
                                        @error('precio')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 4. Categorías --}}
                        <div class="ae-form-card mb-3">
                            <div class="ae-form-card-header">
                                <h2 class="ae-form-card-title">
                                    <i class="fas fa-tags"></i> Categorías
                                </h2>
                                <span class="ae-form-card-subtitle">Selecciona una o más</span>
                            </div>
                            <div class="ae-form-card-body">
                                <div class="ae-chips-selector">
                                    @foreach ($categorias as $id => $nombre)
                                        <div class="ae-chip-checkbox">
                                            <input type="checkbox" id="cat_{{ $id }}" name="categorias[]"
                                                value="{{ $id }}" @checked(is_array(old('categorias')) && in_array($id, old('categorias')))>
                                            <label for="cat_{{ $id }}">{{ $nombre }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('categorias')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- 5. Imágenes Multimedia --}}
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
                                        <span>Miniatura / Tarjeta <span class="text-danger">*</span></span>
                                        <small class="badge bg-light text-muted border">420 x 280 px</small>
                                    </label>
                                    <div class="ae-image-uploader" id="uploaderThumb">
                                        <input type="file" accept=".jpg,.jpeg,.png,.webp,.gif" id="imgThumb" name="imgThumb" required>
                                        <div class="ae-upload-placeholder" id="placeholderThumb">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Subir miniatura</span>
                                            <small>Clic para seleccionar archivo</small>
                                        </div>
                                        <div class="ae-image-preview-wrapper d-none" id="previewThumbWrapper">
                                            <img src="#" id="imgThumbPreview" alt="Vista previa">
                                            <div class="ae-image-overlay">
                                                <i class="fas fa-sync-alt"></i> Cambiar imagen
                                            </div>
                                        </div>
                                    </div>
                                    @error('imgThumb')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Cover Full --}}
                                <div>
                                    <label class="form-label d-flex justify-content-between align-items-center">
                                        <span>Portada / Cabecera <span class="text-danger">*</span></span>
                                        <small class="badge bg-light text-muted border">1920 x 1080 px</small>
                                    </label>
                                    <div class="ae-image-uploader" id="uploaderFull">
                                        <input type="file" accept=".jpg,.jpeg,.png,.webp,.gif" id="imgFull" name="imgFull" required>
                                        <div class="ae-upload-placeholder" id="placeholderFull">
                                            <i class="fas fa-image"></i>
                                            <span>Subir portada</span>
                                            <small>Clic para seleccionar archivo</small>
                                        </div>
                                        <div class="ae-image-preview-wrapper d-none" id="previewFullWrapper">
                                            <img src="#" id="imgFullPreview" alt="Vista previa">
                                            <div class="ae-image-overlay">
                                                <i class="fas fa-sync-alt"></i> Cambiar imagen
                                            </div>
                                        </div>
                                    </div>
                                    @error('imgFull')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- 6. SEO & Metadatos --}}
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
                                            value="{{ old('slug') }}" placeholder="machu-picchu-tour-es" required>
                                        <button type="button" class="btn btn-outline-secondary btn-slug-lock" id="btnLockSlug" title="Alternar auto-generación">
                                            <i class="fas fa-lock-open" id="iconLockSlug"></i>
                                        </button>
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
                                        placeholder="Breve resumen que se mostrará en los resultados de Google y tarjetas..." required>{{ old('descripcionCorta') }}</textarea>
                                    @error('descripcionCorta')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Keywords --}}
                                <div>
                                    <label for="keywords" class="form-label">Palabras Clave (SEO)</label>
                                    <input type="text" id="keywords" name="keywords"
                                        class="form-control @error('keywords') is-invalid @enderror"
                                        value="{{ old('keywords') }}" placeholder="Tours en Cusco, Machu Picchu lujo, viajar a Peru" required>
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

            const inputNombre = document.getElementById('nombre');
            const inputSlug = document.getElementById('slug');
            const btnLockSlug = document.getElementById('btnLockSlug');
            const iconLockSlug = document.getElementById('iconLockSlug');
            let isAutoSlug = true;

            btnLockSlug.addEventListener('click', function() {
                isAutoSlug = !isAutoSlug;
                if (isAutoSlug) {
                    iconLockSlug.className = 'fas fa-lock-open text-success';
                    btnLockSlug.title = 'Slug automático activo';
                } else {
                    iconLockSlug.className = 'fas fa-lock text-danger';
                    btnLockSlug.title = 'Slug manual bloqueado';
                }
            });

            function slugify(text) {
                return text.toString().toLowerCase().trim()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9 -]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }

            inputNombre.addEventListener('input', function() {
                if (isAutoSlug && !inputSlug.dataset.manual) {
                    inputSlug.value = slugify(this.value);
                }
            });

            inputSlug.addEventListener('input', function() {
                inputSlug.dataset.manual = 'true';
                inputSlug.value = slugify(this.value);
            });

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
                            placeholder.classList.add('d-none');
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
