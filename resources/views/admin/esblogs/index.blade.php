@extends('layouts.app')
@section('titulo', 'Listado de blog en español')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
@endpush
@section('contenido')
    <div class="container-fluid">
        <div class="row align-items-center ae-admin-page-header">
            <div class="col-lg-8 text-start mb-2 mb-lg-0">
                <h2 class="ae-admin-page-title">
                    <i class="fas fa-fw fa-pen text-primary me-2"></i>
                    Blogs en español
                </h2>
                <small class="ae-admin-page-desc">Listado de entradas ES</small>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('esblogs.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Crear nuevo Blog
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body p-0">
                <div class="table-responsive p-2 p-md-3">
                    <table id="tabladatos" class="table table-hover table-bordered w-100 ae-admin-data-table">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción corta</th>
                                <th>Tags</th>
                                <th>Relación</th>
                                <th>Img Thumb</th>
                                <th>Img Full</th>
                                <th>Slug</th>
                                <th class="text-center" style="width: 140px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($blogs as $registro)
                                <tr>
                                    <td>{{ $registro->id }}</td>
                                    <td class="fw-semibold">{{ $registro->nombre }}</td>
                                    <td>{{ Str::limit($registro->descripcionCorta ?? '', 80) }}</td>
                                    <td>
                                        @foreach ($registro->tags as $tag)
                                            <span class="badge bg-info text-dark me-1">{{ $tag->nombre }}</span>
                                        @endforeach
                                    </td>
                                    <td class="small">
                                        @if ($registro->blog)
                                            <span class="badge bg-success">{{ $registro->blog->nombre }}</span>
                                            <div class="text-muted mt-1"><code>{{ $registro->blog->slug }}</code></div>
                                        @else
                                            <span class="badge bg-secondary">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <img src="{{ asset($registro->imgThumb) }}" width="90" height="50"
                                            class="rounded" style="object-fit: cover" alt="">
                                    </td>
                                    <td>
                                        <img src="{{ asset($registro->imgFull) }}" width="90" height="50" class="rounded"
                                            style="object-fit: cover" alt="">
                                    </td>
                                    <td><code class="small">{{ $registro->slug }}</code></td>
                                    <td class="text-center">
                                        <div class="d-inline-flex flex-wrap gap-1 justify-content-center">
                                            <a href="{{ route('esblog.show', $registro->slug) }}"
                                                class="btn btn-success btn-sm" target="_blank" title="Ver"><i
                                                    class="fas fa-eye"></i></a>
                                            <a href="{{ route('esblogs.edit', $registro->id) }}"
                                                class="btn btn-info btn-sm" title="Editar"><i
                                                    class="fas fa-edit"></i></a>
                                            <form action="{{ route('esblogs.destroy', $registro->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('¿Eliminar este blog?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar"><i
                                                        class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </div>
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
