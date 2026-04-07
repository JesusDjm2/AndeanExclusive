@extends('layouts.app')
@section('titulo', 'Crear Nuevo Proveedor')
@section('contenido')
    <div class="container-fluid py-4">
        <!-- Migas de pan -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('proveedors.index') }}"
                        class="text-decoration-none">Proveedores</a></li>
                <li class="breadcrumb-item active" aria-current="page">Nuevo Proveedor</li>
            </ol>
        </nav>

        <!-- Título de la página -->
        <div class="d-flex align-items-center mb-4">
            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                <i class="fas fa-truck fa-2x text-white"></i>
            </div>
            <div>
                <h2 class="h3 mb-1">Crear Nuevo Proveedor</h2>
                <p class="text-muted mb-0">Completa los datos para registrar un nuevo proveedor en el sistema</p>
            </div>
        </div>

        <!-- Formulario principal -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            <h5 class="card-title mb-0">Información del Proveedor</h5>
                        </div>
                    </div>

                    <div class="card-body">
                        @includeif('partials.errors')

                        <form action="{{ route('proveedors.store') }}" method="POST">
                            @csrf

                            <!-- Nombre del Proveedor -->
                            <div class="mb-3">
                                <label for="nombre" class="form-label fw-semibold">
                                    <i class="fas fa-building me-1 text-primary"></i>
                                    Nombre del Proveedor <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    id="nombre" name="nombre" value="{{ old('nombre') }}"
                                    placeholder="Ej: Transportes Andean, Hoteles Perú, etc." autofocus>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <!-- Categoría -->
                                <div class="col-md-6 mb-3">
                                    <label for="categoria_id" class="form-label fw-semibold">
                                        <i class="fas fa-tag me-1 text-primary"></i>
                                        Categoría <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('categoria_id') is-invalid @enderror"
                                        id="categoria_id" name="categoria_id">
                                        <option value="">Seleccionar categoría...</option>
                                        @foreach ($categorias as $id => $nombre)
                                            <option value="{{ $id }}"
                                                {{ old('categoria_id') == $id ? 'selected' : '' }}>
                                                {{ $nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categoria_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- RUC -->
                                <div class="col-md-6 mb-3">
                                    <label for="ruc" class="form-label fw-semibold">
                                        <i class="fas fa-id-card me-1 text-primary"></i>
                                        RUC
                                    </label>
                                    <input type="text" class="form-control @error('ruc') is-invalid @enderror"
                                        id="ruc" name="ruc" value="{{ old('ruc') }}" placeholder="20XXXXXXXXX"
                                        maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    @error('ruc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Solo números, máximo 11 dígitos</div>
                                </div>
                            </div>

                            <!-- Dirección -->
                            <div class="mb-3">
                                <label for="direccion" class="form-label fw-semibold">
                                    <i class="fas fa-map-marker-alt me-1 text-primary"></i>
                                    Dirección
                                </label>
                                <input type="text" class="form-control @error('direccion') is-invalid @enderror"
                                    id="direccion" name="direccion" value="{{ old('direccion') }}"
                                    placeholder="Ej: Av. Principal 123, Cercado">
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <!-- Teléfono -->
                                <div class="col-md-6 mb-3">
                                    <label for="telefono" class="form-label fw-semibold">
                                        <i class="fas fa-phone me-1 text-primary"></i>
                                        Teléfono
                                    </label>
                                    <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                        id="telefono" name="telefono" value="{{ old('telefono') }}"
                                        placeholder="Ej: 987654321">
                                    @error('telefono')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Correo -->
                                <div class="col-md-6 mb-3">
                                    <label for="correo" class="form-label fw-semibold">
                                        <i class="fas fa-envelope me-1 text-primary"></i>
                                        Correo Electrónico
                                    </label>
                                    <input type="email" class="form-control @error('correo') is-invalid @enderror"
                                        id="correo" name="correo" value="{{ old('correo') }}"
                                        placeholder="Ej: contacto@empresa.com">
                                    @error('correo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Descripción con CKEditor -->
                            <div class="mb-3">
                                <label for="descripcion" class="form-label fw-semibold">
                                    <i class="fas fa-align-left me-1 text-primary"></i>
                                    Descripción / Notas
                                </label>
                                <textarea class="form-control ckeditor" id="descripcion" name="descripcion" rows="4">{{ old('descripcion') }}</textarea>
                                <div class="form-text">Información adicional del proveedor (opcional)</div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('proveedors.index') }}" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-times me-2"></i>
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="fas fa-save me-2"></i>
                                    Guardar Proveedor
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            // Inicializar CKEditor
            if (typeof CKEDITOR !== 'undefined') {
                $('.ckeditor').ckeditor();
            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.9.2/ckeditor.js"></script>

    <style>
        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }

        .form-text {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        .card {
            border-radius: 0.75rem;
        }

        .btn {
            border-radius: 0.5rem;
        }
    </style>
@endsection
