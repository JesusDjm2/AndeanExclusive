@extends('layouts.app')
@section('titulo', 'Editar Servicio Maestro: ' . $servicio->codigo)

@section('contenido')
<div class="container-fluid px-3 px-md-4 py-3" style="max-width: 900px;">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tarifario-maestro.index') }}" class="text-decoration-none">Tarifario Maestro</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tarifario-maestro.show', $servicio) }}" class="text-decoration-none">{{ $servicio->codigo }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm rounded-3 bg-white">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="badge bg-dark font-monospace mb-1">{{ $servicio->codigo }}</span>
                    <h5 class="fw-bold mb-0 text-dark">Editar Parámetros del Servicio Maestro</h5>
                </div>
                <span class="badge bg-light text-muted border">{{ $servicio->categoria_nombre }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('tarifario-maestro.update', $servicio) }}">
            @csrf
            @method('PUT')

            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger py-2 mb-3">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-12 col-md-8">
                        <label class="form-label small fw-semibold text-dark">Nombre del Servicio / Experiencia <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $servicio->nombre) }}" required>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold text-dark">Categoría <span class="text-danger">*</span></label>
                        <select name="categoria" class="form-select" required>
                            @foreach (\App\Models\TarifarioServicio::CATEGORIAS as $slug => $nom)
                                <option value="{{ $slug }}" @selected(old('categoria', $servicio->categoria) === $slug)>{{ $nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-8">
                        <label class="form-label small fw-semibold text-dark">Lugares de Visita / Atractivos</label>
                        <input type="text" name="lugares_visita" class="form-control" value="{{ old('lugares_visita', $servicio->lugares_visita) }}" placeholder="Ej. Awana Kancha, Pisac y Ollantaytambo">
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold text-dark">Duración Estimada (Horas)</label>
                        <input type="number" step="0.5" min="0" name="duracion_horas" class="form-control" value="{{ old('duracion_horas', $servicio->duracion_horas) }}" placeholder="8">
                    </div>

                    {{-- Parámetros Financieros --}}
                    <div class="col-12">
                        <h6 class="fw-bold text-dark pt-3 border-top mb-3">Parámetros Financieros & Comisión de Agencia</h6>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-semibold text-dark">Porcentaje de Comisión (%) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" step="0.01" min="0" max="100" name="comision_pct" class="form-control" value="{{ old('comision_pct', $servicio->comision_pct) }}" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;">Por defecto 11% para Aracari (o 13% en rutas específicas). Propinas llevan 0%.</small>
                    </div>

                    <div class="col-12 col-md-6 d-flex align-items-center">
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="aplica_igv_comision" value="1" id="aplica_igv_comision" @checked(old('aplica_igv_comision', $servicio->aplica_igv_comision))>
                            <label class="form-check-label small fw-semibold text-dark" for="aplica_igv_comision">
                                Aplicar IGV (18%) sobre la comisión de agencia
                            </label>
                            <small class="text-muted d-block" style="font-size: 0.72rem;">Cálculo: (Costo + Comisión) + (Comisión * 18%).</small>
                        </div>
                    </div>

                    @if ($servicio->tipo_calculo === 'por_persona')
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold text-dark">Tarifa Adulto General (USD)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" name="precio_adulto" class="form-control" value="{{ old('precio_adulto', $servicio->precio_adulto) }}">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold text-dark">Tarifa Niño / Estudiante (USD)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" name="precio_nino" class="form-control" value="{{ old('precio_nino', $servicio->precio_nino) }}">
                            </div>
                        </div>
                    @endif

                    <div class="col-12">
                        <label class="form-label small fw-semibold text-dark">Descripción y Notas Operativas</label>
                        <textarea name="descripcion" rows="3" class="form-control">{{ old('descripcion', $servicio->descripcion) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light border-top p-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('tarifario-maestro.show', $servicio) }}" class="btn btn-outline-secondary btn-sm">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold">
                    <i class="fas fa-save me-1"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
