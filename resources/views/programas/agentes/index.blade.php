@extends('layouts.app')
@section('title', 'Lista de Agentes')
@section('contenido')
    <div class="container-fluid">
        <div class="row align-items-center mb-4 position-relative">
            <!-- Izquierda: Título -->
            <div class="col text-start">
                <h2 class="mb-0 fw-bold">
                    <i class="fas fa-fw fa-users text-primary me-2"></i>
                    Agentes
                </h2>
                <small class="text-muted">
                    Gestión y visualización de Agentes 
                </small>
            </div>
            <!-- Centro: Botón principal -->
            <div class="col text-center">
                <a href="{{ route('agentes.create') }}" class="btn btn-primary btn-sm px-4">
                    <i class="fas fa-plus-circle"></i> Nuevo Agente
                </a>
            </div>

            <!-- Derecha: Volver atrás -->
            <div class="col text-end">
                <a href="#" class="btn btn-sm btn-danger">
                    <i class="fas fa-arrow-left"></i> Programas
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button btn btn-sm" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <table class="table table-bordered">
            <thead class="bg-secondary text-white">
                <tr>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Foto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($agentes as $agente)
                    <tr>
                        <td>{{ $agente->nombre }}</td>
                        <td>{{ $agente->telefono }}</td>
                        <td>{{ $agente->email }}</td>
                        <td class="text-center">
                            @if ($agente->foto && file_exists(public_path($agente->foto)))
                                <img src="{{ asset($agente->foto) }}" alt="Foto de {{ $agente->nombre }}"
                                    class="rounded-circle" width="70" height="70"
                                    style="object-fit: cover; border: 2px solid #ddd;">
                            @else
                                <img src="{{ asset('img/default-avatar.png') }}" alt="Sin foto" class="rounded-circle"
                                    width="70" height="70" style="object-fit: cover; opacity: 0.6;">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('agentes.edit', $agente) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('agentes.destroy', $agente) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('¿Seguro de eliminar este agente?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $agentes->links() }}
    </div>
    </div>
    </div>
@endsection
