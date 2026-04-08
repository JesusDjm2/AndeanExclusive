@extends('layouts.app')
@section('titulo', 'Categorías tours ES')
@section('contenido')
    <div class="container-fluid">
        <div class="row align-items-center ae-admin-page-header">
            <div class="col-lg-8 text-start mb-2 mb-lg-0">
                <h2 class="ae-admin-page-title">
                    <i class="fas fa-fw fa-folder text-primary me-2"></i>
                    Categorías en español
                </h2>
                <small class="ae-admin-page-desc">Categorías para tours ES</small>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('categorias.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Nueva categoría
                </a>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 ae-admin-data-table">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nombre</th>
                                <th>Slug</th>
                                <th class="text-center" style="width: 220px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categorias as $cat)
                                <tr>
                                    <td>{{ $cat->id }}</td>
                                    <td class="fw-semibold">{{ $cat->nombre }}</td>
                                    <td><code class="small">{{ $cat->slug }}</code></td>
                                    <td class="text-center">
                                        <form action="{{ route('categorias.destroy', $cat->id) }}" method="POST"
                                            class="d-inline-flex flex-wrap gap-1 justify-content-center">
                                            <a class="btn btn-sm btn-success"
                                                href="{{ route('categoria.show', $cat->slug) }}" target="_blank"
                                                title="Ver"><i class="fas fa-eye"></i></a>
                                            <a class="btn btn-sm btn-info" href="{{ route('categorias.edit', $cat->id) }}"
                                                title="Editar"><i class="fas fa-edit"></i></a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('¿Eliminar categoría?');" title="Eliminar"><i
                                                    class="fas fa-trash-alt"></i></button>
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
