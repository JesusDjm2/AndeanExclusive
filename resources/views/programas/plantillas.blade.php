{{-- Plantillas de viaje (CopiaDeProgramaController@plantillas). --}}
@extends('layouts.app')
@section('titulo', 'Plantillas de viaje')

@php $canEdit = auth()->user()->can('programas.gestionar'); @endphp

@section('contenido')
    <div class="ae-admin-page-header d-flex flex-wrap align-items-end justify-content-between gap-3">
        <div>
            <a href="{{ route('programas.index') }}" class="pf-header__back"><i class="fas fa-arrow-left" aria-hidden="true"></i> Programas</a>
            <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-copy"></i> Plantillas de viaje</h1>
            <small class="ae-admin-page-desc">Viajes tipo, listos para usar. Se crean desde el menú ⋮ de cualquier programa, con «Guardar como plantilla».</small>
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

    @if ($plantillas->isEmpty())
        <div class="card"><div class="admin-empty"><i class="fas fa-copy" aria-hidden="true"></i> Todavía no hay plantillas. Abre el menú ⋮ de un programa bien armado y elige «Guardar como plantilla».</div></div>
    @else
        <div class="card dash-card">
            <div class="table-responsive">
                <table class="table ae-admin-data-table mb-0">
                    <thead>
                        <tr><th>Plantilla</th><th>Contenido</th><th><span class="visually-hidden">Acciones</span></th></tr>
                    </thead>
                    <tbody>
                        @foreach ($plantillas as $plantilla)
                            <tr>
                                <td><a href="{{ route('programas.edit', $plantilla) }}" class="fw-semibold">{{ $plantilla->nombre }}</a></td>
                                <td class="text-muted">
                                    {{ $plantilla->duracionDias() ? $plantilla->duracionDias() . ' días · ' : '' }}{{ $plantilla->servicios_count }} servicios · {{ $plantilla->habitaciones_fechas_count }} estadías
                                </td>
                                <td class="text-end text-nowrap">
                                    @if ($canEdit)
                                        <a href="{{ route('programas.duplicar', $plantilla) }}" class="btn btn-sm btn-primary"><i class="fas fa-plus" aria-hidden="true"></i> Usar</a>
                                        <form action="{{ route('programas.plantilla.destroy', $plantilla) }}" method="POST" class="d-inline"
                                            data-confirm="Se eliminará la plantilla «{{ $plantilla->nombre }}». Los viajes creados con ella no cambian." data-confirm-title="¿Eliminar plantilla?" data-confirm-button="Sí, eliminar" data-confirm-danger>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Eliminar {{ $plantilla->nombre }}"><i class="fas fa-trash-alt" aria-hidden="true"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
