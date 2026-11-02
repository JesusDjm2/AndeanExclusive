@extends('layouts.app')
@section('body-class', 'view-hoteles')
@section('title', 'Hoteles')
@section('contenido')
    <div class="container-fluid py-4">
        <!-- Migas de pan -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('proveedors.index') }}">Proveedores</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categoriasproveedor.index') }}">Categorías</a></li>
                <li class="breadcrumb-item active" aria-current="page">Hoteles</li>
            </ol>
        </nav>

        <!-- Cabecera con estadísticas rápidas -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-hotel fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h2 class="h3 mb-1 fw-bold">Hoteles</h2>
                        <p class="text-muted mb-0">Gestión y visualización de hoteles registrados en el sistema</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                @can('proveedores.gestionar')
<a href="{{ route('hotel.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>
                    Nuevo Hotel
                </a>
@endcan
            </div>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card bg-primary text-white h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Total Hoteles</h6>
                                <h3 class="mb-0">{{ $hoteles->count() }}</h3>
                            </div>
                            <i class="fas fa-hotel fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card bg-success text-white h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Con Habitaciones</h6>
                                <h3 class="mb-0">{{ $hoteles->filter(fn($h) => $h->habitaciones->count() > 0)->count() }}
                                </h3>
                            </div>
                            <i class="fas fa-bed fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card bg-info text-white h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Total Habitaciones</h6>
                                <h3 class="mb-0">{{ $hoteles->sum(fn($h) => $h->habitaciones->count()) }}</h3>
                            </div>
                            <i class="fas fa-door-open fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card bg-warning text-white h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Con Imagen</h6>
                                <h3 class="mb-0">{{ $hoteles->filter(fn($h) => $h->img)->count() }}</h3>
                            </div>
                            <i class="fas fa-image fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros y búsqueda -->
        <div class="row mb-4">

            <div class="col-md-12 mt-3 mt-md-0">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" id="buscarHotel"
                        placeholder="Buscar por nombre, teléfono, dirección...">
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Vista móvil: Tarjetas -->
        <div class="d-md-none">
            @forelse ($hoteles as $hotel)
                <div class="card shadow-sm mb-3 hotel-card" data-habitaciones="{{ $hotel->habitaciones->count() }}"
                    data-imagen="{{ $hotel->img ? 'si' : 'no' }}">
                    <div class="card-body">
                        <!-- Cabecera del hotel -->
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0 fw-bold">
                                <span class="hotel-underline">{{ $hotel->nombre }}</span>
                            </h5>
                            @if ($hotel->img)
                                <span class="badge bg-info">
                                    <i class="fas fa-image me-1"></i>Foto
                                </span>
                            @endif
                        </div>

                        <!-- Información principal -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">
                                    <i class="fas fa-phone me-1"></i>Teléfono:
                                </small>
                                <span>{{ $hotel->telefono ?? '—' }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">
                                    <i class="fas fa-envelope me-1"></i>Email:
                                </small>
                                <span>{{ $hotel->correo ?? '—' }}</span>
                            </div>
                            <div class="col-12">
                                <small class="text-muted d-block">
                                    <i class="fas fa-map-marker-alt me-1"></i>Dirección:
                                </small>
                                <span>{{ $hotel->direccion ?? '—' }}</span>
                            </div>
                            <div class="col-12">
                                <small class="text-muted d-block">
                                    <i class="fas fa-id-card me-1"></i>RUC:
                                </small>
                                <span>{{ $hotel->ruc ?? '—' }}</span>
                            </div>
                        </div>

                        <!-- Descripción -->
                        @if ($hotel->detalles)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">
                                    <i class="fas fa-align-left me-1"></i>Descripción:
                                </small>
                                <p class="small mb-0">{{ Str::limit($hotel->detalles, 100) }}</p>
                            </div>
                        @endif

                        <!-- Habitaciones -->
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-bed me-1"></i>Habitaciones:
                            </small>
                            @if ($hotel->habitaciones->count())
                                @foreach ($hotel->habitaciones as $habitacion)
                                    <div class="bg-light p-2 rounded mb-1">
                                        <span class="fw-medium">{{ $habitacion->tipo }}</span>
                                        <span class="badge bg-secondary ms-2">
                                            <i
                                                class="fas {{ $habitacion->capacidad > 1 ? 'fa-users' : 'fa-user' }} me-1"></i>
                                            Cap. {{ $habitacion->capacidad }}
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <em class="text-muted small">Sin habitaciones registradas</em>
                            @endif
                        </div>

                        <!-- Imagen si existe -->
                        @if ($hotel->img)
                            <div class="mb-3 text-center">
                                <img src="{{ asset($hotel->img) }}" alt="Hotel {{ $hotel->nombre }}"
                                    class="img-fluid rounded" style="max-height: 150px; cursor: pointer;"
                                    data-bs-toggle="modal" data-bs-target="#imgHotel{{ $hotel->id }}">
                            </div>
                        @endif

                        <!-- Acciones -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('hotel.show', $hotel) }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            @can('proveedores.gestionar')
<a href="{{ route('hotel.edit', $hotel) }}" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit"></i>
                            </a>
@endcan
                            @can('proveedores.gestionar')
<form action="{{ route('hotel.destroy', $hotel) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger btn-eliminar-hotel" type="button">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
@endcan
                        </div>
                    </div>
                </div>

                <!-- Modal para imagen -->
                <div class="modal fade" id="imgHotel{{ $hotel->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $hotel->nombre }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="{{ asset($hotel->img) }}" alt="Hotel {{ $hotel->nombre }}" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    No hay hoteles registrados
                </div>
            @endforelse
        </div>

        <!-- Vista desktop: Tabla mejorada -->
        <div class="card shadow-sm d-none d-md-block">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2 text-primary"></i>
                        Listado de Hoteles
                    </h5>
                    <span class="badge bg-primary">{{ $hoteles->count() }} registros</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tablaHoteles">
                        <thead class="table-light">
                            <tr>
                                <th>Hotel</th>
                                <th>Descripción</th>
                                <th>Habitaciones</th>
                                <th>Imagen</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($hoteles as $hotel)
                                <tr class="hotel-row" data-habitaciones="{{ $hotel->habitaciones->count() }}"
                                    data-imagen="{{ $hotel->img ? 'si' : 'no' }}">
                                    <td>
                                        <div class="fw-bold">
                                            <span class="hotel-underline">{{ $hotel->nombre }}</span>
                                        </div>
                                        <ul class="list-unstyled mt-2 small">
                                            <li><i class="fas fa-phone text-muted me-1"></i> {{ $hotel->telefono ?? '—' }}
                                            </li>
                                            <li><i class="fas fa-envelope text-muted me-1"></i>
                                                {{ $hotel->correo ?? '—' }}</li>
                                            <li><i class="fas fa-map-marker-alt text-muted me-1"></i>
                                                {{ $hotel->direccion ?? '—' }}</li>
                                            <li><i class="fas fa-id-card text-muted me-1"></i> {{ $hotel->ruc ?? '—' }}
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        <small>{{ $hotel->detalles ?? '—' }}</small>
                                    </td>
                                    <td>
                                        @if ($hotel->habitaciones->count())
                                            @foreach ($hotel->habitaciones as $habitacion)
                                                <div class="mb-1">
                                                    <span class="badge bg-info bg-opacity-10 text-info">
                                                        {{ $habitacion->tipo }}
                                                    </span>
                                                    <span class="badge bg-secondary ms-1">
                                                        <i
                                                            class="fas {{ $habitacion->capacidad > 1 ? 'fa-users' : 'fa-user' }} me-1"></i>
                                                        {{ $habitacion->capacidad }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        @else
                                            <em class="text-muted">—</em>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($hotel->img)
                                            <img src="{{ asset($hotel->img) }}" alt="Hotel {{ $hotel->nombre }}"
                                                class="img-thumbnail rounded" style="max-height: 50px; cursor: pointer;"
                                                data-bs-toggle="modal" data-bs-target="#imgHotel{{ $hotel->id }}">
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('hotel.show', $hotel) }}"
                                                class="btn btn-sm btn-outline-info" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('proveedores.gestionar')
<a href="{{ route('hotel.edit', $hotel) }}"
                                                class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
@endcan
                                            @can('proveedores.gestionar')
<form action="{{ route('hotel.destroy', $hotel) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger btn-eliminar-hotel"
                                                    type="button" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
@endcan
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal para imagen (reutilizado) -->
                                <div class="modal fade" id="imgHotel{{ $hotel->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ $hotel->nombre }}</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset($hotel->img) }}" alt="Hotel {{ $hotel->nombre }}"
                                                    class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-hotel fa-2x mb-2"></i>
                                        <p>No hay hoteles registrados</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if (method_exists($hoteles, 'links'))
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Mostrando {{ $hoteles->firstItem() ?? 0 }} - {{ $hoteles->lastItem() ?? 0 }}
                            de {{ $hoteles->total() ?? $hoteles->count() }}
                        </div>
                        <div>
                            {{ $hoteles->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
   <script>
    $(document).ready(function() {
        // Función para quitar tildes (EXACTA)
        function sinTildes(texto) {
            return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
        }

        // Búsqueda simple (EXACTA)
        $("#buscarHotel").on("keyup", function() {
            var busqueda = sinTildes($(this).val());

            $("#tablaHoteles tbody tr").each(function() {
                var texto = sinTildes($(this).text());
                $(this).toggle(texto.indexOf(busqueda) > -1);
            });

            $(".hotel-card").each(function() {
                var texto = sinTildes($(this).text());
                $(this).toggle(texto.indexOf(busqueda) > -1);
            });
        });

        // SweetAlert para eliminar (EXACTO)
        $(".btn-eliminar-hotel").on("click", function(e) {
            e.preventDefault();
            var form = $(this).closest("form");

            Swal.fire({
                title: "¿Eliminar hotel?",
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

    // Notificación de éxito
    @if (session('success'))
        Swal.fire({
            icon: "success",
            title: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 3000
        });
    @endif
</script>

@endsection
