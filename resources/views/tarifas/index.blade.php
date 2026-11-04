{{-- Tarifario (TarifaController@index). --}}
@extends('layouts.app')
@section('titulo', 'Tarifario')

@php $canEdit = auth()->user()->can('proveedores.gestionar'); @endphp

@section('contenido')
    <div class="ae-admin-page-header d-flex flex-wrap align-items-end justify-content-between gap-3">
        <div>
            <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-tags"></i> Tarifario</h1>
            <small class="ae-admin-page-desc">Lo que cobra cada hotel y proveedor por temporada. El sistema lo sugiere al cotizar y al reservar.</small>
        </div>
        @if ($canEdit)
            <a href="{{ route('tarifas.create') }}" class="btn btn-primary"><i class="fas fa-plus" aria-hidden="true"></i> Nueva tarifa</a>
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

    <nav class="prog-tabs" aria-label="Vigencia">
        @foreach (['vigentes' => 'Vigentes y futuras', 'vencidas' => 'Vencidas', 'todas' => 'Todas'] as $clave => $etiqueta)
            <a href="{{ route('tarifas.index', array_filter(['vista' => $clave, 'q' => request('q')])) }}" class="prog-tabs__tab {{ $vista === $clave ? 'is-active' : '' }}" @if ($vista === $clave) aria-current="page" @endif>{{ $etiqueta }}</a>
        @endforeach
    </nav>

    <form method="GET" action="{{ route('tarifas.index') }}" class="res-filters">
        <input type="hidden" name="vista" value="{{ $vista }}">
        <div class="res-filters__search">
            <label for="tar-q" class="visually-hidden">Buscar</label>
            <i class="fas fa-search" aria-hidden="true"></i>
            <input id="tar-q" type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Hotel, proveedor o servicio">
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <div class="card dash-card">
        @if ($tarifas->isEmpty())
            <div class="admin-empty"><i class="fas fa-tags" aria-hidden="true"></i> No hay tarifas en esta vista.</div>
        @else
            <div class="table-responsive">
                <table class="table ae-admin-data-table mb-0">
                    <thead><tr><th>Hotel o proveedor</th><th>Qué cobra</th><th>Temporada</th><th class="text-end">Precio</th><th><span class="visually-hidden">Acciones</span></th></tr></thead>
                    <tbody>
                        @foreach ($tarifas as $tarifa)
                            <tr>
                                <td class="fw-semibold">{{ $tarifa->de_quien }}</td>
                                <td>{{ $tarifa->que }}@if ($tarifa->notas)<small class="d-block text-muted">{{ $tarifa->notas }}</small>@endif</td>
                                <td class="text-nowrap">{{ $tarifa->desde->format('d/m/Y') }} – {{ $tarifa->hasta->format('d/m/Y') }}</td>
                                <td class="text-end text-nowrap"><strong>{{ \App\Models\Servicio::formatoMonto((float) $tarifa->precio, $tarifa->moneda) }}</strong> <small class="text-muted">{{ \App\Models\Tarifa::POR[$tarifa->por] ?? '' }}</small></td>
                                <td class="text-end text-nowrap">
                                    @if ($canEdit)
                                        <a href="{{ route('tarifas.edit', $tarifa) }}" class="btn btn-sm btn-outline-primary" aria-label="Editar tarifa"><i class="fas fa-pen" aria-hidden="true"></i></a>
                                        <form action="{{ route('tarifas.destroy', $tarifa) }}" method="POST" class="d-inline" data-confirm="Se eliminará esta tarifa." data-confirm-title="¿Eliminar tarifa?" data-confirm-button="Sí, eliminar" data-confirm-danger>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Eliminar tarifa"><i class="fas fa-trash-alt" aria-hidden="true"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <div class="prog-pagination">{{ $tarifas->links('pagination::bootstrap-5') }}</div>
@endsection
