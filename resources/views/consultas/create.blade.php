{{-- Consulta recibida por WhatsApp, teléfono, correo u otro canal (ConsultaController@create). --}}
@extends('layouts.app')
@section('titulo', 'Nueva consulta')

@section('contenido')
    <div class="ae-admin-page-header">
        <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-inbox"></i> Nueva consulta</h1>
        <small class="ae-admin-page-desc">Para lo que llega fuera de la web. Las de los formularios entran solas.</small>
    </div>

    <form action="{{ route('consultas.store') }}" method="POST" class="prog-section">
        @csrf
        <div class="prog-section__body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="c-nombre" class="prog-label">Nombre <span class="required">*</span></label>
                    <input id="c-nombre" type="text" name="nombre" value="{{ old('nombre') }}" maxlength="255" required class="form-control @error('nombre') is-invalid @enderror">
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-md-3">
                    <label for="c-origen" class="prog-label">¿Por dónde llegó? <span class="required">*</span></label>
                    <select id="c-origen" name="origen" class="form-select @error('origen') is-invalid @enderror">
                        @foreach ($origenes as $valor => $etiqueta)
                            <option value="{{ $valor }}" @selected(old('origen', 'whatsapp') === $valor)>{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                    @error('origen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-md-3">
                    <label for="c-idioma" class="prog-label">Idioma <span class="required">*</span></label>
                    <select id="c-idioma" name="idioma" class="form-select">
                        <option value="en" @selected(old('idioma', 'en') === 'en')>Inglés</option>
                        <option value="es" @selected(old('idioma') === 'es')>Español</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label for="c-email" class="prog-label">Correo</label>
                    <input id="c-email" type="email" name="email" value="{{ old('email') }}" maxlength="255" class="form-control @error('email') is-invalid @enderror">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label for="c-tel" class="prog-label">Teléfono / WhatsApp</label>
                    <input id="c-tel" type="tel" name="telefono" value="{{ old('telefono') }}" maxlength="40" class="form-control @error('telefono') is-invalid @enderror">
                    @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="c-tour" class="prog-label">Viaje que le interesa</label>
                    <input id="c-tour" type="text" name="tour" value="{{ old('tour') }}" maxlength="255" class="form-control @error('tour') is-invalid @enderror">
                    @error('tour')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-md-3">
                    <label for="c-inicio" class="prog-label">Desde</label>
                    <input id="c-inicio" type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" class="form-control @error('fecha_inicio') is-invalid @enderror">
                    @error('fecha_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-md-3">
                    <label for="c-fin" class="prog-label">Hasta</label>
                    <input id="c-fin" type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" class="form-control @error('fecha_fin') is-invalid @enderror">
                    @error('fecha_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-6 col-md-3">
                    <label for="c-ad" class="prog-label">Adultos</label>
                    <input id="c-ad" type="number" name="adultos" value="{{ old('adultos') }}" min="0" max="999" class="form-control @error('adultos') is-invalid @enderror">
                </div>
                <div class="col-6 col-md-3">
                    <label for="c-ni" class="prog-label">Niños</label>
                    <input id="c-ni" type="number" name="ninos" value="{{ old('ninos') }}" min="0" max="999" class="form-control @error('ninos') is-invalid @enderror">
                </div>
                <div class="col-12">
                    <label for="c-msg" class="prog-label">Qué pidió</label>
                    <textarea id="c-msg" name="mensaje" rows="4" maxlength="5000" class="form-control @error('mensaje') is-invalid @enderror">{{ old('mensaje') }}</textarea>
                    @error('mensaje')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save" aria-hidden="true"></i> Guardar consulta</button>
                <a href="{{ route('consultas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div>
    </form>
@endsection
