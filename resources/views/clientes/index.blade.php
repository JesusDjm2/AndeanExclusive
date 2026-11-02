{{-- Listado de clientes (ClienteController@index). --}}
@extends('layouts.app')
@section('titulo', 'Clientes')

@php $canEdit = auth()->user()?->can('programas.gestionar'); @endphp

@section('contenido')
    <div class="ae-admin-page-header d-flex flex-wrap align-items-end justify-content-between gap-3">
        <div>
            <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-address-book"></i> Clientes</h1>
            <small class="ae-admin-page-desc">Titulares de los programas: viajeros directos y agencias.</small>
        </div>
        @if ($canEdit)
            <a href="{{ route('clientes.create') }}" class="btn btn-primary"><i class="fas fa-plus" aria-hidden="true"></i> Nuevo cliente</a>
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

    <div class="card prog-toolbar">
        <form method="GET" action="{{ route('clientes.index') }}" class="prog-filters prog-filters--simple">
            <div class="prog-filters__search">
                <label for="cli-q" class="visually-hidden">Buscar</label>
                <i class="fas fa-search" aria-hidden="true"></i>
                <input id="cli-q" type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por nombre, agencia, correo o teléfono">
            </div>
            <div>
                <label for="cli-tipo" class="visually-hidden">Tipo</label>
                <select id="cli-tipo" name="tipo" class="form-select">
                    <option value="">Todos los tipos</option>
                    @foreach ($tipos as $value => $label)
                        <option value="{{ $value }}" @selected(request('tipo') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="prog-filters__actions">
                <button type="submit" class="btn btn-primary">Buscar</button>
                @if (request()->filled('q') || request()->filled('tipo'))
                    <a href="{{ route('clientes.index') }}" class="btn btn-link">Limpiar</a>
                @endif
            </div>
        </form>
    </div>

    <div class="card">
        @if ($clientes->isEmpty())
            <div class="admin-empty"><i class="fas fa-address-book" aria-hidden="true"></i> No hay clientes que coincidan.</div>
        @else
            <div class="table-responsive">
                <table class="table ae-admin-data-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Contacto</th>
                            <th>País</th>
                            <th>Origen</th>
                            <th class="text-center">Programas</th>
                            <th>Último viaje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td>
                                    <a href="{{ route('clientes.edit', $cliente) }}" class="fw-semibold text-decoration-none">{{ $cliente->nombre }}</a>
                                    @if ($cliente->tipo === 'agencia')
                                        <span class="badge text-bg-light ms-1">Agencia</span>
                                        <small class="d-block text-muted">{{ $cliente->empresa }}</small>
                                    @endif
                                </td>
                                <td class="small">
                                    @if ($cliente->email)<a href="mailto:{{ $cliente->email }}" class="d-block">{{ $cliente->email }}</a>@endif
                                    {{ $cliente->telefono }}
                                </td>
                                <td>{{ $cliente->pais_nombre ?? '—' }}</td>
                                <td class="small">{{ \App\Models\Cliente::ORIGENES[$cliente->origen] ?? '—' }}</td>
                                <td class="text-center">{{ $cliente->programas_count }}</td>
                                <td class="small">{{ $cliente->programas_max_inicio ? \Carbon\Carbon::parse($cliente->programas_max_inicio)->locale('es')->isoFormat('D MMM YYYY') : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="prog-pagination">{{ $clientes->links('pagination::bootstrap-5') }}</div>
@endsection
