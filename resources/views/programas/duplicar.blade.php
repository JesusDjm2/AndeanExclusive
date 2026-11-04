{{-- Crear una cotización a partir de un viaje o una plantilla (CopiaDeProgramaController@create). --}}
@extends('layouts.app')
@section('titulo', 'Nueva cotización')

@section('contenido')
    <div class="ae-admin-page-header">
        <a href="{{ $programa->plantilla ? route('programas.plantillas') : route('programas.index') }}" class="pf-header__back"><i class="fas fa-arrow-left" aria-hidden="true"></i> Volver</a>
        <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-copy"></i> Nueva cotización a partir de «{{ $programa->nombre }}»</h1>
        <small class="ae-admin-page-desc">Se copian los días, hoteles, servicios y precios, con las fechas corridas. No se copian pasajeros, pagos ni confirmaciones.</small>
    </div>

    <form action="{{ route('programas.duplicar.store', $programa) }}" method="POST" class="prog-section">
        @csrf
        <div class="prog-section__body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="d-nombre" class="prog-label">Nombre del programa <span class="required">*</span></label>
                    <input id="d-nombre" type="text" name="nombre" value="{{ old('nombre', $programa->nombre) }}" maxlength="255" required class="form-control @error('nombre') is-invalid @enderror">
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-md-3">
                    <label for="d-inicio" class="prog-label">Inicio @if ($programa->inicio)<span class="required">*</span>@endif</label>
                    <input id="d-inicio" type="date" name="inicio" value="{{ old('inicio') }}" @if ($programa->inicio) required @endif class="form-control @error('inicio') is-invalid @enderror">
                    @error('inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="d-cliente" class="prog-label">Cliente <span class="required">*</span></label>
                    <select id="d-cliente" name="cliente_id" required class="form-select @error('cliente_id') is-invalid @enderror">
                        <option value="">— Seleccionar cliente —</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}" @selected((int) old('cliente_id') === $cliente->id)>{{ $cliente->etiqueta }}{{ $cliente->email ? ' — ' . $cliente->email : '' }}</option>
                        @endforeach
                    </select>
                    @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <p class="prog-input-hint mt-2 mb-0">¿Cliente nuevo? <a href="{{ route('clientes.create') }}">Regístralo primero</a>.</p>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-copy" aria-hidden="true"></i> Crear cotización</button>
            </div>
        </div>
    </form>
@endsection
