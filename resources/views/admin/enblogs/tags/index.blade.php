@extends('layouts.app')
@section('titulo', 'Listado de Tags EN')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
@endpush
@section('contenido')
    <div class="container-fluid">
        <div class="row align-items-center ae-admin-page-header">
            <div class="col-lg-8 text-start mb-2 mb-lg-0">
                <h2 class="ae-admin-page-title">
                    <i class="fas fa-fw fa-tags text-primary me-2"></i>
                    Tags en inglés
                </h2>
                <small class="ae-admin-page-desc">Etiquetas para blogs EN</small>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('entags.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Crear nuevo tag
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
                <div class="table-responsive p-2 p-md-3">
                    <table id="tabladatos" class="table table-hover table-bordered w-100 ae-admin-data-table">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nombre</th>
                                <th>Slug</th>
                                <th class="text-center" style="width: 220px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tags as $cat)
                                <tr>
                                    <td>{{ $cat->id }}</td>
                                    <td class="fw-semibold">{{ $cat->nombre }}</td>
                                    <td><code class="small">{{ $cat->slug }}</code></td>
                                    <td class="text-center">
                                        <form action="{{ route('entags.destroy', $cat->id) }}" method="POST"
                                            class="d-inline-flex flex-wrap gap-1 justify-content-center">
                                            <a class="btn btn-sm btn-success" href="{{ route('entag.show', $cat->slug) }}"
                                                target="_blank"><i class="fas fa-eye"></i></a>
                                            <a class="btn btn-sm btn-info" href="{{ route('entags.edit', $cat->id) }}"><i
                                                    class="fas fa-edit"></i></a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('¿Eliminar?');"><i
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
@push('scripts')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.jQuery && jQuery.fn.DataTable) {
                jQuery('#tabladatos').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json'
                    },
                    pageLength: 25,
                    order: [
                        [0, 'desc']
                    ]
                });
            }
        });
    </script>
@endpush
