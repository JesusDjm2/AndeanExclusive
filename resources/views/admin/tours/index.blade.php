@extends('layouts.app')
@section('titulo', 'Lista de Tours en inglés')
@section('contenido')
    <div class="container-fluid">
        <div class="row align-items-center ae-admin-page-header">
            <div class="col-lg-4 text-start mb-2 mb-lg-0">
                <h2 class="ae-admin-page-title">
                    <i class="fas fa-fw fa-map-marked-alt text-primary me-2"></i>
                    Tours en inglés
                </h2>
                <small class="ae-admin-page-desc">Listado y edición de tours EN</small>
            </div>
            <div class="col-lg-4 text-center mb-2 mb-lg-0">
                <span class="badge bg-primary rounded-pill px-3 py-2">{{ $totalTours }} tours</span>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('tours.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Nuevo Tour
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow mb-3 ae-admin-filter-card">
            <div class="card-body">
                <label class="form-label small text-muted mb-1">Buscar</label>
                <input type="text" id="buscadorTours" class="form-control form-control-sm"
                    placeholder="Nombre, categoría o días...">
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tabladatos" class="table table-hover table-bordered mb-0 ae-admin-data-table">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th>Imágenes</th>
                                <th scope="col">Categorías</th>
                                <th>Relación Español</th>
                                <th scope="col" class="text-center" style="width: 150px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $count = 1; @endphp
                            @foreach ($tours as $tour)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td><strong>{{ $tour->nombre }}</strong><br>
                                        <ul class="small mb-0 ps-3">
                                            <li>Recorrido: {{ $tour->recorrido }}</li>
                                            <li>Precio: ${{ $tour->precio }}.00</li>
                                            <li>Días: {{ $tour->dias }}</li>
                                            <li>Keywords:
                                                @foreach (explode(',', $tour->keywords) as $keyword)
                                                    <span class="d-block">{{ trim($keyword) }}</span>
                                                @endforeach
                                            </li>
                                        </ul>
                                    </td>
                                    <td class="small">
                                        <strong>Img de fondo:</strong><br>
                                        <img src="{{ asset($tour->imgFull) }}" width="90" alt="" class="mb-1"><br>
                                        <strong>Img min:</strong><br>
                                        <img src="{{ asset($tour->imgThumb) }}" width="90" alt="">
                                    </td>
                                    <td>
                                        <ul class="small mb-0 ps-3">
                                            @foreach ($tour->categorias as $categoria)
                                                <li>{{ $categoria->nombre }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        @if ($tour->estour)
                                            <span class="badge bg-success">{{ $tour->estour->nombre ?? 'Sin nombre' }}</span>
                                        @else
                                            <span class="badge bg-secondary">No relacionado</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('tours.destroy', $tour->id) }}" method="POST"
                                            class="d-inline-flex flex-wrap gap-1 justify-content-center">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{ route('tours.edit', $tour->id) }}" class="btn btn-info btn-sm"
                                                title="Editar"><i class="fas fa-edit"></i></a>
                                            <a href="{{ route('tour.show', $tour->slug) }}" class="btn btn-success btn-sm"
                                                title="Ver tour" target="_blank"><i class="fas fa-eye"></i></a>
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar"
                                                onclick="return confirm('¿Desea eliminar este tour?');"><i
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('buscadorTours');
            const filas = document.querySelectorAll('#tabladatos tbody tr');
            if (!input) return;
            input.addEventListener('keyup', () => {
                const texto = input.value.toLowerCase();
                filas.forEach(fila => {
                    const nombre = fila.querySelector('td:nth-child(2)')?.innerText.toLowerCase() || '';
                    const categorias = fila.querySelector('td:nth-child(4)')?.innerText.toLowerCase() || '';
                    const dias = nombre.match(/días:\s*(\d+)/i)?.[1] || '';
                    if (nombre.includes(texto) || categorias.includes(texto) || dias.includes(texto)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
