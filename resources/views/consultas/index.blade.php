{{-- Consultas de posibles clientes (ConsultaController@index). Estilos: resources/sass/admin/_consultas.scss --}}
@extends('layouts.app')
@section('titulo', 'Consultas')

@php $canEdit = auth()->user()->can('consultas.gestionar'); @endphp

@section('contenido')
    <div class="ae-admin-page-header d-flex flex-wrap align-items-end justify-content-between gap-3">
        <div>
            <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-inbox"></i> Consultas</h1>
            <small class="ae-admin-page-desc">Interesados de la web y de otros canales, hasta convertirlos en cotización.</small>
        </div>
        @if ($canEdit)
            <a href="{{ route('consultas.create') }}" class="btn btn-primary"><i class="fas fa-plus" aria-hidden="true"></i> Nueva consulta</a>
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

    <nav class="prog-tabs" aria-label="Estado de la consulta">
        @foreach (\App\Http\Controllers\ConsultaController::BANDEJAS as $clave => [$etiqueta])
            <a href="{{ route('consultas.index', array_filter(['bandeja' => $clave, 'q' => request('q')])) }}" class="prog-tabs__tab {{ $bandeja === $clave ? 'is-active' : '' }}" @if ($bandeja === $clave) aria-current="page" @endif>
                {{ $etiqueta }} <span class="prog-tabs__count">{{ $conteos[$clave] }}</span>
            </a>
        @endforeach
    </nav>

    <form method="GET" action="{{ route('consultas.index') }}" class="res-filters">
        <input type="hidden" name="bandeja" value="{{ $bandeja }}">
        <div class="res-filters__search">
            <label for="con-q" class="visually-hidden">Buscar</label>
            <i class="fas fa-search" aria-hidden="true"></i>
            <input id="con-q" type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Nombre, correo, teléfono o tour">
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    @if ($consultas->isEmpty())
        <div class="card dash-card"><div class="admin-empty"><i class="fas fa-check-circle" aria-hidden="true"></i> No hay consultas en esta pestaña.</div></div>
    @else
        <div class="card dash-card">
            <ul class="consulta-lista">
                @foreach ($consultas as $consulta)
                    <li class="consulta-item">
                        <div class="consulta-item__cuando">
                            <strong>{{ $consulta->created_at->locale('es')->isoFormat('D MMM') }}</strong>
                            <small>{{ $consulta->created_at->locale('es')->diffForHumans() }}</small>
                        </div>
                        <div class="consulta-item__quien">
                            <a href="{{ route('consultas.show', $consulta) }}" class="consulta-item__nombre">{{ $consulta->nombre }}</a>
                            <small>{{ collect([$consulta->email, $consulta->telefono])->filter()->implode(' · ') }}</small>
                        </div>
                        <div class="consulta-item__viaje">
                            {{ $consulta->resumen_viaje ?: 'Sin detalles del viaje' }}
                            @if ($consulta->programa)<small><a href="{{ route('programas.edit', $consulta->programa) }}">{{ $consulta->programa->codigo }}</a></small>@endif
                            @if ($consulta->motivo_perdida)<small>{{ \App\Models\Consulta::MOTIVOS_PERDIDA[$consulta->motivo_perdida] ?? '' }}</small>@endif
                        </div>
                        <div class="consulta-item__a-cargo">
                            <span class="badge text-bg-light">{{ strtoupper($consulta->idioma) }}</span>
                            <small>{{ $consulta->asignado?->name ?? 'Sin asignar' }}</small>
                        </div>
                        <a href="{{ route('consultas.show', $consulta) }}" class="btn btn-sm btn-outline-primary">Abrir</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="prog-pagination">{{ $consultas->links('pagination::bootstrap-5') }}</div>
    @endif
@endsection
