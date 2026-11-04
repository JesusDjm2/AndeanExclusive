{{-- Ficha de una consulta (ConsultaController@show). Estilos: resources/sass/admin/_consultas.scss --}}
@extends('layouts.app')
@section('titulo', $consulta->nombre)

@php
    $canEdit = auth()->user()->can('consultas.gestionar');
    $estados = \App\Models\Consulta::ESTADOS;
    $whatsapp = $consulta->telefono ? ltrim(preg_replace('/[^0-9+]/', '', $consulta->telefono), '+') : null;
@endphp

@section('contenido')
    <div class="ae-admin-page-header d-flex flex-wrap align-items-end justify-content-between gap-3">
        <div>
            <a href="{{ route('consultas.index') }}" class="pf-header__back"><i class="fas fa-arrow-left" aria-hidden="true"></i> Consultas</a>
            <h1 class="ae-admin-page-title">{{ $consulta->nombre }}</h1>
            <small class="ae-admin-page-desc">
                {{ $estados[$consulta->estado] }} · {{ \App\Models\Cliente::ORIGENES[$consulta->origen] ?? $consulta->origen }} · recibida {{ $consulta->created_at->locale('es')->diffForHumans() }}
            </small>
        </div>
        @if ($canEdit)
            @if ($consulta->programa)
                <a href="{{ route('programas.edit', $consulta->programa) }}" class="btn btn-outline-primary"><i class="fas fa-suitcase-rolling" aria-hidden="true"></i> Ver {{ $consulta->programa->codigo }}</a>
            @elseif ($consulta->estado !== 'perdida')
                <form action="{{ route('consultas.cotizar', $consulta) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary"><i class="fas fa-file-signature" aria-hidden="true"></i> Crear cotización</button>
                </form>
            @endif
        @elseif ($consulta->programa)
            <a href="{{ route('programas.edit', $consulta->programa) }}" class="btn btn-outline-primary">Ver {{ $consulta->programa->codigo }}</a>
        @endif
    </div>

    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
        @if (session($key))
            <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
    @endforeach

    <div class="consulta-ficha">
        <section class="prog-section" aria-labelledby="c-detalle">
            <div class="prog-section__header"><h2 class="prog-section__title" id="c-detalle">Lo que pidió</h2></div>
            <div class="prog-section__body">
                <dl class="consulta-datos">
                    @if ($consulta->email)<div><dt>Correo</dt><dd><a href="mailto:{{ $consulta->email }}">{{ $consulta->email }}</a></dd></div>@endif
                    @if ($consulta->telefono)<div><dt>Teléfono</dt><dd><a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener"><i class="fab fa-whatsapp" aria-hidden="true"></i> {{ $consulta->telefono }}</a></dd></div>@endif
                    @if ($consulta->tour)<div><dt>Viaje</dt><dd>{{ $consulta->tour }}</dd></div>@endif
                    @if ($consulta->fecha_inicio)<div><dt>Fechas</dt><dd>{{ $consulta->fecha_inicio->format('d/m/Y') }}{{ $consulta->fecha_fin ? ' – ' . $consulta->fecha_fin->format('d/m/Y') : '' }}</dd></div>@endif
                    @if ($consulta->adultos || $consulta->ninos)<div><dt>Personas</dt><dd>{{ (int) $consulta->adultos }} adultos · {{ (int) $consulta->ninos }} niños</dd></div>@endif
                    <div><dt>Idioma</dt><dd>{{ $consulta->idioma === 'es' ? 'Español' : 'Inglés' }}</dd></div>
                </dl>
                @if ($consulta->mensaje)
                    <p class="consulta-mensaje">{{ $consulta->mensaje }}</p>
                @else
                    <p class="text-muted mb-0">No dejó un mensaje.</p>
                @endif
            </div>
        </section>

        <form action="{{ route('consultas.update', $consulta) }}" method="POST" class="prog-section" aria-labelledby="c-seguimiento">
            @csrf
            @method('PUT')
            <div class="prog-section__header"><h2 class="prog-section__title" id="c-seguimiento">Seguimiento</h2></div>
            <fieldset class="prog-section__body" @disabled(! $canEdit)>
                @if ($consulta->programa_id)
                    <input type="hidden" name="estado" value="en_gestion">
                    <p class="prog-input-hint">El estado sigue al programa: se gana al confirmarlo y se pierde al cancelarlo.</p>
                @else
                    <label for="c-estado" class="prog-label">Estado</label>
                    <select id="c-estado" name="estado" class="form-select mb-3 @error('estado') is-invalid @enderror">
                        @foreach (['nueva', 'en_gestion', 'perdida'] as $valor)
                            <option value="{{ $valor }}" @selected(old('estado', $consulta->estado) === $valor)>{{ $estados[$valor] }}</option>
                        @endforeach
                    </select>
                    <label for="c-motivo" class="prog-label">Si se perdió, ¿por qué?</label>
                    <select id="c-motivo" name="motivo_perdida" class="form-select mb-3 @error('motivo_perdida') is-invalid @enderror">
                        <option value="">—</option>
                        @foreach (\App\Models\Consulta::MOTIVOS_PERDIDA as $valor => $etiqueta)
                            <option value="{{ $valor }}" @selected(old('motivo_perdida', $consulta->motivo_perdida) === $valor)>{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                    @error('motivo_perdida')<div class="invalid-feedback d-block mb-2">{{ $message }}</div>@enderror
                @endif

                <label for="c-asignado" class="prog-label">A cargo de</label>
                <select id="c-asignado" name="asignado_id" class="form-select mb-3">
                    <option value="">Sin asignar</option>
                    @foreach ($equipo as $persona)
                        <option value="{{ $persona->id }}" @selected((int) old('asignado_id', $consulta->asignado_id) === $persona->id)>{{ $persona->name }}</option>
                    @endforeach
                </select>

                <label for="c-notas" class="prog-label">Notas internas</label>
                <textarea id="c-notas" name="notas" rows="4" maxlength="5000" class="form-control mb-3" placeholder="Qué se le respondió, cuándo volver a escribir…">{{ old('notas', $consulta->notas) }}</textarea>

                @if ($canEdit)
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save" aria-hidden="true"></i> Guardar</button>
                @endif
            </fieldset>
        </form>
    </div>
@endsection
