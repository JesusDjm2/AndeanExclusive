{{-- Alta y edición de una tarifa (TarifaController). Muestra los campos según el tipo: resources/js/tarifas.js. --}}
@extends('layouts.app')
@section('titulo', $tarifa->exists ? 'Editar tarifa' : 'Nueva tarifa')

@php
    $tipo = old('tipo', $tarifa->proveedor_id ? 'proveedor' : 'habitacion');
    $input = fn (string $campo, $defecto = null) => old($campo, $defecto ?? $tarifa->{$campo});
@endphp

@section('contenido')
    <div class="ae-admin-page-header">
        <a href="{{ route('tarifas.index') }}" class="pf-header__back"><i class="fas fa-arrow-left" aria-hidden="true"></i> Tarifario</a>
        <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-tags"></i> {{ $tarifa->exists ? 'Editar tarifa' : 'Nueva tarifa' }}</h1>
    </div>

    <form action="{{ $tarifa->exists ? route('tarifas.update', $tarifa) : route('tarifas.store') }}" method="POST" class="prog-section" data-tarifa-form>
        @csrf
        @if ($tarifa->exists)
            @method('PUT')
        @endif
        <div class="prog-section__body">
            <div class="pf-segmented mb-3" role="radiogroup" aria-label="Tipo de tarifa">
                <label><input type="radio" name="tipo" value="habitacion" @checked($tipo === 'habitacion') data-tarifa-tipo> <span>Habitación de hotel</span></label>
                <label><input type="radio" name="tipo" value="proveedor" @checked($tipo === 'proveedor') data-tarifa-tipo> <span>Servicio de un proveedor</span></label>
            </div>

            <div class="row g-3">
                <div class="col-md-8" data-tarifa-panel="habitacion" @if ($tipo !== 'habitacion') hidden @endif>
                    <label for="t-hab" class="prog-label">Hotel y habitación <span class="required">*</span></label>
                    <select id="t-hab" name="habitacion_id" class="form-select @error('habitacion_id') is-invalid @enderror">
                        <option value="">Elige…</option>
                        @foreach ($hoteles as $hotel)
                            @continue($hotel->habitaciones->isEmpty())
                            <optgroup label="{{ $hotel->nombre }}">
                                @foreach ($hotel->habitaciones as $habitacion)
                                    <option value="{{ $habitacion->id }}" @selected((int) $input('habitacion_id') === $habitacion->id)>{{ $hotel->nombre }} · {{ $habitacion->tipo }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('habitacion_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-5" data-tarifa-panel="proveedor" @if ($tipo !== 'proveedor') hidden @endif>
                    <label for="t-prov" class="prog-label">Proveedor <span class="required">*</span></label>
                    <select id="t-prov" name="proveedor_id" class="form-select @error('proveedor_id') is-invalid @enderror">
                        <option value="">Elige…</option>
                        @foreach ($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}" @selected((int) $input('proveedor_id') === $proveedor->id)>{{ $proveedor->nombre }}</option>
                        @endforeach
                    </select>
                    @error('proveedor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-7" data-tarifa-panel="proveedor" @if ($tipo !== 'proveedor') hidden @endif>
                    <label for="t-con" class="prog-label">Qué cobra <span class="required">*</span></label>
                    <input id="t-con" type="text" name="concepto" value="{{ $input('concepto') }}" maxlength="255" placeholder="Ej. Traslado aeropuerto → hotel" class="form-control @error('concepto') is-invalid @enderror">
                    @error('concepto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-sm-6 col-md-3">
                    <label for="t-desde" class="prog-label">Desde <span class="required">*</span></label>
                    <input id="t-desde" type="date" name="desde" value="{{ old('desde', $tarifa->desde?->toDateString()) }}" required class="form-control @error('desde') is-invalid @enderror">
                    @error('desde')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-md-3">
                    <label for="t-hasta" class="prog-label">Hasta <span class="required">*</span></label>
                    <input id="t-hasta" type="date" name="hasta" value="{{ old('hasta', $tarifa->hasta?->toDateString()) }}" required class="form-control @error('hasta') is-invalid @enderror">
                    @error('hasta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4 col-md-2">
                    <label for="t-moneda" class="prog-label">Moneda</label>
                    <select id="t-moneda" name="moneda" class="form-select">
                        @foreach (\App\Models\Servicio::MONEDAS as $clave => $simbolo)
                            <option value="{{ $clave }}" @selected($input('moneda') === $clave)>{{ $clave }} ({{ $simbolo }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4 col-md-2">
                    <label for="t-precio" class="prog-label">Precio <span class="required">*</span></label>
                    <input id="t-precio" type="number" name="precio" value="{{ $input('precio') }}" min="0" step="0.01" required class="form-control @error('precio') is-invalid @enderror">
                    @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4 col-md-2" data-tarifa-panel="proveedor" @if ($tipo !== 'proveedor') hidden @endif>
                    <label for="t-por" class="prog-label">Se cobra</label>
                    <select id="t-por" name="por" class="form-select">
                        @foreach (['grupo', 'persona'] as $clave)
                            <option value="{{ $clave }}" @selected($input('por') === $clave)>{{ ucfirst(\App\Models\Tarifa::POR[$clave]) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label for="t-notas" class="prog-label">Notas</label>
                    <input id="t-notas" type="text" name="notas" value="{{ $input('notas') }}" maxlength="255" placeholder="Temporada alta, incluye desayuno…" class="form-control">
                </div>
            </div>

            <p class="prog-input-hint mt-3">Una habitación se cobra por noche. Para temporadas distintas, crea una tarifa por cada temporada.</p>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save" aria-hidden="true"></i> Guardar tarifa</button>
                <a href="{{ route('tarifas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div>
    </form>
@endsection
