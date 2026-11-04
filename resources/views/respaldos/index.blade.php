{{-- Copias de seguridad de la base de datos (RespaldoController@index). --}}
@extends('layouts.app')
@section('titulo', 'Copias de seguridad')

@section('contenido')
    <div class="ae-admin-page-header d-flex flex-wrap align-items-end justify-content-between gap-3">
        <div>
            <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-database"></i> Copias de seguridad</h1>
            <small class="ae-admin-page-desc">Se crea una cada día y se conservan las últimas {{ config('seguridad.respaldos_a_conservar') }}. Descarga una de vez en cuando: si el servidor falla, estas copias también se pierden.</small>
        </div>
        <form action="{{ route('respaldos.store') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus" aria-hidden="true"></i> Crear copia ahora</button>
        </form>
    </div>

    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
        @if (session($key))
            <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
    @endforeach

    <div class="card dash-card">
        @if ($respaldos->isEmpty())
            <div class="admin-empty"><i class="fas fa-database" aria-hidden="true"></i> Todavía no hay copias.</div>
        @else
            <div class="table-responsive">
                <table class="table ae-admin-data-table mb-0">
                    <thead><tr><th>Fecha</th><th>Tamaño</th><th><span class="visually-hidden">Acciones</span></th></tr></thead>
                    <tbody>
                        @foreach ($respaldos as $respaldo)
                            <tr>
                                <td>{{ \Carbon\Carbon::createFromTimestamp($respaldo['fecha'], config('app.timezone'))->locale('es')->isoFormat('D MMM YYYY, HH:mm') }}</td>
                                <td>{{ number_format($respaldo['bytes'] / 1048576, 1) }} MB</td>
                                <td class="text-end"><a href="{{ route('respaldos.descargar', $respaldo['nombre']) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-download" aria-hidden="true"></i> Descargar</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
