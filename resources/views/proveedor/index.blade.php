@extends('layouts.app')
@section('titulo', 'Proveedores')
@section('title', 'Proveedores')
@section('contenido')
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Proveedores</li>
            </ol>
        </nav>

        <div class="row align-items-center ae-admin-page-header">
            <div class="col-lg-8 text-start mb-2 mb-lg-0">
                <h2 class="ae-admin-page-title">
                    <i class="fas fa-fw fa-truck text-primary me-2"></i>
                    Gestión de proveedores
                </h2>
                <small class="ae-admin-page-desc">Proveedores, contacto y categorías</small>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('proveedors.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus-circle me-1"></i>
                    Nuevo proveedor
                </a>
            </div>
        </div>

                    <!-- Tarjetas de resumen -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white-50 mb-1">Total Proveedores</h6>
                                            <h3 class="mb-0">{{ $proveedors->total() }}</h3>
                                        </div>
                                        <i class="fas fa-truck fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white-50 mb-1">Categorías</h6>
                                            <h3 class="mb-0">{{ $proveedors->groupBy('categoria_id')->count() }}</h3>
                                        </div>
                                        <i class="fas fa-tags fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card bg-info text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white-50 mb-1">Con Correo</h6>
                                            <h3 class="mb-0">{{ $proveedors->whereNotNull('correo')->count() }}</h3>
                                        </div>
                                        <i class="fas fa-envelope fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white-50 mb-1">Con Teléfono</h6>
                                            <h3 class="mb-0">{{ $proveedors->whereNotNull('telefono')->count() }}</h3>
                                        </div>
                                        <i class="fas fa-phone fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alerta de éxito -->
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Buscador -->
                    <div class="card shadow mb-3 ae-admin-filter-card">
                        <div class="card-body">
                            <label class="form-label small text-muted mb-1">Buscar</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="buscarProveedor"
                                    placeholder="Nombre, RUC, teléfono...">
                            </div>
                        </div>
                    </div>

                    <!-- Vista móvil: Tarjetas -->
                    <div class="d-md-none">
                        @forelse ($proveedors as $proveedor)
                            <div class="card mb-3 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $proveedor->nombre }}</h5>
                                        <span class="badge bg-info">{{ $proveedor->categoria->nombre }}</span>
                                    </div>

                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <small class="text-muted d-block">RUC</small>
                                            <span>{{ $proveedor->ruc }}</span>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Teléfono</small>
                                            <span>{{ $proveedor->telefono ?: '—' }}</span>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <small class="text-muted d-block">Dirección</small>
                                        <span>{{ $proveedor->direccion ?: '—' }}</span>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted d-block">Correo</small>
                                        <span>{{ $proveedor->correo ?: '—' }}</span>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('proveedors.show', $proveedor->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('proveedors.edit', $proveedor->id) }}"
                                            class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('proveedors.destroy', $proveedor->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('¿Eliminar este proveedor?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                No hay proveedores registrados
                            </div>
                        @endforelse
                    </div>

                    <!-- Vista desktop: Tabla -->
                    <div class="card shadow d-none d-md-block">
                        <div class="card-header py-3 bg-white border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-secondary">
                                    <i class="fas fa-list text-primary me-2"></i>
                                    Listado de proveedores
                                </span>
                                <span class="badge bg-primary rounded-pill">{{ $proveedors->total() }} registros</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 ae-admin-data-table" id="tablaProveedores">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Categoría</th>
                                            <th>RUC</th>
                                            <th>Dirección</th>
                                            <th>Teléfono</th>
                                            <th>Correo</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($proveedors as $proveedor)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ $proveedor->nombre }}</td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        {{ $proveedor->categoria->nombre }}
                                                    </span>
                                                </td>
                                                <td>{{ $proveedor->ruc }}</td>
                                                <td>{{ $proveedor->direccion ?: '—' }}</td>
                                                <td>
                                                    @if ($proveedor->telefono)
                                                        <a href="tel:{{ $proveedor->telefono }}"
                                                            class="text-decoration-none">
                                                            {{ $proveedor->telefono }}
                                                        </a>
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($proveedor->correo)
                                                        <a href="mailto:{{ $proveedor->correo }}"
                                                            class="text-decoration-none">
                                                            {{ $proveedor->correo }}
                                                        </a>
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td class="text-center" style="width: 120px">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('proveedors.show', $proveedor->id) }}"
                                                            class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                            <i class="fa fa-fw fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('proveedors.edit', $proveedor->id) }}"
                                                            class="btn btn-sm btn-outline-success" title="Editar">
                                                            <i class="fa fa-fw fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('proveedors.destroy', $proveedor->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm btn-eliminar">
                                                                <i class="fa fa-fw fa-trash" title="Borrar"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($proveedors->hasPages())
                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        Mostrando {{ $proveedors->firstItem() }} - {{ $proveedors->lastItem() }}
                                        de {{ $proveedors->total() }}
                                    </div>
                                    <div>
                                        {{ $proveedors->links() }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
    </div>
@endsection
@push('scripts')
    <script>
        jQuery(function($) {
            function sinTildes(texto) {
                return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            }

            $("#buscarProveedor").on("keyup", function() {
                var busqueda = sinTildes($(this).val());

                $("#tablaProveedores tbody tr").each(function() {
                    var texto = sinTildes($(this).text());
                    $(this).toggle(texto.indexOf(busqueda) > -1);
                });

                $(".d-md-none .card").each(function() {
                    var texto = sinTildes($(this).text());
                    $(this).toggle(texto.indexOf(busqueda) > -1);
                });
            });

            $(".btn-eliminar").on("click", function(e) {
                e.preventDefault();
                var form = $(this).closest("form");

                Swal.fire({
                    title: "¿Eliminar proveedor?",
                    text: "No podrás revertir esto",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        @if (session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: "success",
                    title: @json(session('success')),
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        @endif
    </script>
@endpush
