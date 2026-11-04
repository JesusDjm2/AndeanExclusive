{{-- Movimientos de la agencia (MovimientoController@index). Estilos: resources/sass/admin/_movimientos.scss --}}
@extends('layouts.app')
@section('titulo', 'Movimientos')

@section('contenido')
    <div class="ae-admin-page-header">
        <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-stream"></i> Movimientos de la agencia</h1>
        <small class="ae-admin-page-desc">Quién hizo qué y cuándo, en todas las áreas. Solo consulta: nadie puede modificar este registro.</small>
    </div>

    <form method="GET" action="{{ route('movimientos.index') }}" class="mov-filters">
        <div>
            <label for="mov-q" class="prog-label">Buscar</label>
            <input id="mov-q" type="search" name="q" value="{{ request('q') }}" maxlength="100" class="form-control form-control-sm" placeholder="Persona, programa, servicio…">
        </div>
        <div>
            <label for="mov-area" class="prog-label">Área</label>
            <select id="mov-area" name="area" class="form-select form-select-sm">
                <option value="">Todas</option>
                @foreach (\App\Support\Permisos::AREAS as $area)
                    <option value="{{ $area }}" @selected(request('area') === $area)>{{ $area }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="mov-modulo" class="prog-label">Tipo</label>
            <select id="mov-modulo" name="modulo" class="form-select form-select-sm">
                <option value="">Todos</option>
                @foreach (\App\Support\Bitacora::modulos() as $modulo)
                    <option value="{{ $modulo }}" @selected(request('modulo') === $modulo)>{{ $modulo }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="mov-desde" class="prog-label">Desde</label>
            <input id="mov-desde" type="date" name="desde" value="{{ request('desde') }}" class="form-control form-control-sm">
        </div>
        <div>
            <label for="mov-hasta" class="prog-label">Hasta</label>
            <input id="mov-hasta" type="date" name="hasta" value="{{ request('hasta') }}" class="form-control form-control-sm">
        </div>
        <div class="mov-filters__actions">
            <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
            @if (request()->hasAny(['q', 'area', 'modulo', 'desde', 'hasta']))
                <a href="{{ route('movimientos.index') }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>
            @endif
        </div>
    </form>

    <section class="prog-section">
        <div class="prog-section__body">
            @include('movimientos.partials.lista', ['movimientos' => $movimientos->getCollection()])
        </div>
    </section>

    @if ($movimientos->hasPages())
        <div class="mt-3">{{ $movimientos->links('pagination::bootstrap-5') }}</div>
    @endif
@endsection
