@extends('layouts.app')
@section('titulo', 'Listado de Paxs')
@section('contenido')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h4>Listado de Paxs</h4>
                <a href="{{ route('paxs.create') }}" class="btn btn-primary btn-sm float-right">
                    Crear nuevo Pax
                </a>
                @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table table-striped mt-4">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Programa</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paxs as $pax)
                            <tr>
                                <td>{{ $pax->id }}</td>
                                <td>{{ $pax->nombre }}</td>
                                <td>{{ $pax->programa->nombre }}</td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('paxs.show', $pax) }}">Ver</a>
                                    <a class="btn btn-warning btn-sm" href="{{ route('paxs.edit', $pax) }}">Editar</a>

                                    <form action="{{ route('paxs.destroy', $pax) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
