@extends('layouts.app')
@section('titulo', 'Lista de Tours en español')
@section('contenido')

    <div class="row">
        <div class="col-lg-4 col-md-6">
            <h2 class="text-primary">Lista de tours en Español:</h2>
        </div>
        <div class="col-lg-4 col-md-3 text-center mt-2">
            <p>Tours: <strong>{{ $totalTours }}</strong></p>
        </div>
        <div class="col-lg-4 col-md-3 text-end">
            <a href="{{ route('estours.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nuevo Tour
            </a>
        </div>
        @if (session('status'))
            <div class="alert alert-success mt-2" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div class="col-lg-12 mt-3">
            <input type="text" id="buscadorTours" class="form-control form-control-sm"
                placeholder="Buscar por nombre, categoría o días...">
        </div>
        <div class="col-lg-12">
            <div class="table-responsive">
                <table id="tabladatos" class="table mt-4 table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th> Imagenes</th>
                            <th scope="col">Categorias</th>
                            <th>Relación Inglés</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $count = 1;
                        @endphp
                        @foreach ($tours as $tour)
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td><strong>{{ $tour->nombre }}</strong><br>
                                    <ul>
                                        <li>Recorrido:{{ $tour->recorrido }}</li>
                                        <li>Precio: ${{ $tour->precio }}.00</li>
                                        <li>Días: {{ $tour->dias }}</li>
                                        <li>Keywords:
                                            @foreach (explode(',', $tour->keywords) as $keyword)
                                                <ul style="list-style: disc">{{ trim($keyword) }}</ul>
                                            @endforeach
                                        </li>
                                    </ul>

                                </td>
                                <td> <strong>Img de fondo:</strong> <br>
                                    <img src="{{ asset($tour->imgFull) }}" width="90px"><br>
                                    <strong>Img min:</strong><br>
                                    <img src="{{ asset($tour->imgThumb) }}" width="90px">
                                </td>
                                <td>
                                    <ul>
                                        @foreach ($tour->categorias as $categoria)
                                            <li>{{ $categoria->nombre }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    @if ($tour->tour)
                                        <span class="badge bg-success">
                                            {{ $tour->tour->nombre ?? 'Sin nombre' }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">No relacionado</span>
                                    @endif
                                </td>
                                <td style="width: 140px">
                                    <form action="{{ route('estours.destroy', $tour->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <a href="{{ route('estours.edit', $tour->id) }}" class="btn btn-info btn-sm"
                                            title="Editar">
                                            <i class="fa fa-edit"></i> </a>
                                        <a href="{{ route('estour.show', $tour->slug) }}" class="btn btn-success btn-sm"
                                            title="Ver tour" target="_blank"><i class="fa fa-eye"></i></a>
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar"
                                            onclick="alerta();"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function alerta() {
            alert('Desea aliminar?');
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('buscadorTours');
            const filas = document.querySelectorAll('#tabladatos tbody tr');

            input.addEventListener('keyup', () => {
                const texto = input.value.toLowerCase();

                filas.forEach(fila => {
                    const nombre = fila.querySelector('td:nth-child(2)')?.innerText.toLowerCase() ||
                        '';
                    const categorias = fila.querySelector('td:nth-child(4)')?.innerText
                        .toLowerCase() || '';
                    const dias = nombre.match(/días:\s*(\d+)/i)?.[1] ||
                        ''; // extrae el número de días si aparece en el texto

                    // Mostrar u ocultar según coincidencia
                    if (nombre.includes(texto) || categorias.includes(texto) || dias.includes(
                            texto)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>


@endsection
