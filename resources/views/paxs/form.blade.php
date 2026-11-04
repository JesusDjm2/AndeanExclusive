{{--
    Alta / edición de un pasajero (PaxController@create, @edit).
    Reutiliza la tarjeta del formulario de programas (programas/partials/pax-card) con nombres de campo planos.
--}}
@extends('layouts.app')
@section('titulo', $pax->exists ? $pax->nombre : 'Nuevo pasajero')

@php
    $isEdit = $pax->exists;
    $valores = [
        'nombre' => old('nombre', $pax->nombre),
        'fecha_nacimiento' => old('fecha_nacimiento', $pax->fecha_nacimiento?->toDateString()),
        'sexo' => old('sexo', $pax->sexo),
        'pais' => old('pais', $pax->pais),
        'pasaporte' => old('pasaporte', $pax->pasaporte),
        'pasaporte_vencimiento' => old('pasaporte_vencimiento', $pax->pasaporte_vencimiento?->toDateString()),
        'dieta' => old('dieta', $pax->dieta),
        'alergias' => old('alergias', $pax->alergias),
        'condiciones_medicas' => old('condiciones_medicas', $pax->condiciones_medicas),
        'emergencia_nombre' => old('emergencia_nombre', $pax->emergencia_nombre),
        'emergencia_telefono' => old('emergencia_telefono', $pax->emergencia_telefono),
    ];
    $agentesSel = collect(old('agentes', $pax->agentes->pluck('id')->all()))->map(fn ($v) => (int) $v);
@endphp

@section('contenido')
    <div class="pf">
        <div class="pf-header">
            <div class="pf-header__main">
                <a href="{{ route('paxs.index') }}" class="pf-header__back"><i class="fas fa-arrow-left" aria-hidden="true"></i> Pasajeros</a>
                <h1 class="pf-header__title">{{ $isEdit ? $pax->nombre : 'Nuevo pasajero' }}</h1>
                @if ($isEdit && $pax->programa)
                    <p class="pf-header__meta">
                        <span><i class="fas fa-suitcase-rolling" aria-hidden="true"></i><a href="{{ route('programas.edit', [$pax->programa, 'tab' => 'pasajeros']) }}">{{ $pax->programa->nombre }}</a></span>
                    </p>
                @endif
            </div>
        </div>

        @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
            @if (session($key))
                <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                    {{ session($key) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif
        @endforeach

        <form id="pax-form" method="POST" action="{{ $isEdit ? route('paxs.update', $pax) : route('paxs.store') }}" enctype="multipart/form-data" novalidate>
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="prog-section">
                <div class="prog-section__body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="pax-programa" class="prog-label">Programa <span class="required">*</span></label>
                            <select id="pax-programa" name="programa_id" class="form-select @error('programa_id') is-invalid @enderror">
                                <option value="">— Seleccionar programa —</option>
                                @foreach ($programas as $p)
                                    <option value="{{ $p->id }}" @selected((int) old('programa_id', $pax->programa_id ?? request('programa_id')) === $p->id)>
                                        {{ $p->codigo }} · {{ $p->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('programa_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <span class="prog-label d-block">Agentes</span>
                            <div class="pf-chips">
                                @foreach ($agentes as $agente)
                                    <label class="pf-chip">
                                        <input type="checkbox" name="agentes[]" value="{{ $agente->id }}" @checked($agentesSel->contains($agente->id))>
                                        <span>{{ $agente->nombre }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('programas.partials.pax-card', ['i' => 0, 'pax' => $valores, 'paxModel' => $isEdit ? $pax : null, 'standalone' => true])
        </form>

        <div class="pf-actionbar">
            <div class="pf-actionbar__buttons">
                <a href="{{ route('paxs.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" form="pax-form" class="btn btn-primary"><i class="fas fa-save" aria-hidden="true"></i> {{ $isEdit ? 'Guardar cambios' : 'Crear pasajero' }}</button>
            </div>
        </div>
    </div>
@endsection
