{{-- Listado de clientes (ClienteController@index). --}}
@extends('layouts.app')
@section('titulo', 'Clientes')

@php $canEdit = auth()->user()?->can('programas.gestionar'); @endphp

@section('contenido')
    <div class="ae-admin-page-header d-flex flex-wrap align-items-end justify-content-between gap-3">
        <div>
            <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-address-book" aria-hidden="true"></i> Clientes</h1>
            <small class="ae-admin-page-desc">Titulares de los programas: viajeros directos y agencias aliadas.</small>
        </div>
        @if ($canEdit)
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus" aria-hidden="true"></i> Nuevo cliente
            </a>
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

    {{-- Indicadores Clave de Clientes (KPIs) --}}
    @isset($stats)
        <div class="ae-stats-row">
            <div class="ae-stat-card">
                <div class="ae-stat-card__icon ae-stat-card__icon--dark">
                    <i class="fas fa-users" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="ae-stat-card__val">{{ number_format($stats['total']) }}</div>
                    <div class="ae-stat-card__lbl">Total Clientes</div>
                </div>
            </div>
            <div class="ae-stat-card">
                <div class="ae-stat-card__icon ae-stat-card__icon--plomo">
                    <i class="fas fa-user" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="ae-stat-card__val">{{ number_format($stats['directos']) }}</div>
                    <div class="ae-stat-card__lbl">Viajeros Directos</div>
                </div>
            </div>
            <div class="ae-stat-card">
                <div class="ae-stat-card__icon ae-stat-card__icon--gold">
                    <i class="fas fa-briefcase" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="ae-stat-card__val">{{ number_format($stats['agencias']) }}</div>
                    <div class="ae-stat-card__lbl">Agencias Aliadas</div>
                </div>
            </div>
            <div class="ae-stat-card">
                <div class="ae-stat-card__icon ae-stat-card__icon--gold">
                    <i class="fas fa-suitcase-rolling" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="ae-stat-card__val">{{ number_format($stats['con_viajes']) }}</div>
                    <div class="ae-stat-card__lbl">Con Programas</div>
                </div>
            </div>
        </div>
    @endisset

    {{-- Barra de Filtros y Búsqueda --}}
    <div class="card prog-toolbar mb-3">
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

    {{-- Tabla de Clientes --}}
    <div class="card">
        @if ($clientes->isEmpty())
            <div class="admin-empty">
                <i class="fas fa-address-book" aria-hidden="true"></i>
                No hay clientes que coincidan con los criterios de búsqueda.
            </div>
        @else
            <div class="table-responsive">
                <table class="table ae-admin-data-table align-middle">
                    <thead>
                        <tr>
                            <th>Cliente / Titular</th>
                            <th>Tipo</th>
                            <th>Contacto</th>
                            <th>País</th>
                            <th>Origen</th>
                            <th class="text-center">Programas</th>
                            <th>Último viaje</th>
                            <th class="text-end pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            @php
                                $isAgencia = $cliente->tipo === 'agencia';
                                $initials = collect(explode(' ', trim($cliente->nombre)))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="ae-avatar-circle {{ $isAgencia ? 'ae-avatar-circle--gold' : 'ae-avatar-circle--plomo' }}">
                                            {{ $initials ?: ($isAgencia ? 'AG' : 'CL') }}
                                        </span>
                                        <div>
                                            <a href="{{ route('clientes.edit', $cliente) }}" class="fw-bold text-decoration-none d-block text-truncate" style="max-width: 250px;">
                                                {{ $cliente->nombre }}
                                            </a>
                                            @if ($isAgencia && $cliente->empresa)
                                                <small class="text-muted d-flex align-items-center gap-1">
                                                    <i class="fas fa-building fa-xs" aria-hidden="true"></i> {{ $cliente->empresa }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($isAgencia)
                                        <span class="ae-badge-gold"><i class="fas fa-briefcase"></i> Agencia</span>
                                    @else
                                        <span class="ae-badge-plomo"><i class="fas fa-user"></i> Directo</span>
                                    @endif
                                </td>
                                <td class="small">
                                    @if ($cliente->email)
                                        <div class="text-truncate mb-1" style="max-width: 220px;">
                                            <a href="mailto:{{ $cliente->email }}" class="text-decoration-none text-muted">
                                                <i class="far fa-envelope text-primary me-1" aria-hidden="true"></i>{{ $cliente->email }}
                                            </a>
                                        </div>
                                    @endif
                                    @if ($cliente->telefono)
                                        <div class="text-nowrap text-muted">
                                            <i class="fas fa-phone-alt me-1 fa-xs text-muted" aria-hidden="true"></i>{{ $cliente->telefono }}
                                        </div>
                                    @endif
                                    @if (!$cliente->email && !$cliente->telefono)
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($cliente->pais_nombre)
                                        <span class="ae-badge-plomo">
                                            <i class="fas fa-globe-americas me-1 text-muted" aria-hidden="true"></i>{{ $cliente->pais_nombre }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($cliente->origen)
                                        <span class="small text-muted">{{ \App\Models\Cliente::ORIGENES[$cliente->origen] ?? $cliente->origen }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($cliente->programas_count > 0)
                                        <a href="{{ route('programas.index', ['q' => $cliente->nombre]) }}" class="text-decoration-none" title="Ver programas de {{ $cliente->nombre }}">
                                            <span class="ae-badge-count">
                                                <i class="fas fa-route" aria-hidden="true"></i> {{ $cliente->programas_count }} {{ $cliente->programas_count === 1 ? 'viaje' : 'viajes' }}
                                            </span>
                                        </a>
                                    @else
                                        <span class="badge text-bg-light text-muted">0 viajes</span>
                                    @endif
                                </td>
                                <td class="small text-muted text-nowrap">
                                    @if ($cliente->programas_max_inicio)
                                        <i class="far fa-calendar-alt me-1 text-muted" aria-hidden="true"></i>
                                        {{ \Carbon\Carbon::parse($cliente->programas_max_inicio)->locale('es')->isoFormat('D MMM YYYY') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="text-end pe-3 text-nowrap">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-outline-secondary" title="Editar cliente">
                                            <i class="fas fa-pencil-alt" aria-hidden="true"></i>
                                        </a>
                                        @if ($cliente->programas_count > 0)
                                            <a href="{{ route('programas.index', ['q' => $cliente->nombre]) }}" class="btn btn-outline-secondary" title="Ver programas de este cliente">
                                                <i class="fas fa-suitcase-rolling" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="prog-pagination">{{ $clientes->links('pagination::bootstrap-5') }}</div>
@endsection
