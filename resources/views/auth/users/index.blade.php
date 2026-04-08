@extends('layouts.app')
@section('titulo', 'Usuarios')
@section('contenido')
    <div class="container-fluid">
        <div class="row align-items-center ae-admin-page-header">
            <div class="col-lg-8 text-start mb-2 mb-lg-0">
                <h2 class="ae-admin-page-title">
                    <i class="fas fa-fw fa-user-shield text-primary me-2"></i>
                    {{ __('Usuarios') }}
                </h2>
                <small class="ae-admin-page-desc">Administración de cuentas del panel</small>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> {{ __('Nuevo Usuario') }}
                </a>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header py-3 bg-white border-bottom">
                <span class="fw-bold text-secondary">{{ __('Listado') }}</span>
            </div>
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 ae-admin-data-table">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nombre</th>
                                <th class="text-end" style="min-width: 200px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td class="fw-semibold">{{ $user->name }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            class="d-inline-flex flex-wrap gap-1 justify-content-end">
                                            <a class="btn btn-sm btn-warning" href="{{ route('users.edit', $user->id) }}">
                                                <i class="fas fa-edit me-1"></i> {{ __('Editar') }}
                                            </a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('¿Eliminar usuario?');">
                                                <i class="fas fa-trash-alt me-1"></i> {{ __('Eliminar') }}
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
    </div>
@endsection
