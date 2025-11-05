@extends('layouts.app')

@section('template_title')
    {{ $categoria->name ?? 'Show Categoria' }}
@endsection

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Categoria</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('categorias.index') }}"> Back</a>
                        </div>
                        <div class="card-body" style="background: #fff; margin:1em">
                            <table class="table table-hover" id="andean">
                                <thead class="thead">
                                    <tr>
                                        <th>Id</th>
                                        <th>Nombre</th>
                                        <th>Categoria</th>
                                        <th>Direccion</th>
                                        <th>Telefono</th>
                                        <th>Correo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categorias as $categoria)
                                        <tr>
                                            <td>{{ $categoria->id }}</td>
                                            <td>{{ $categoria->nombre }}</td>
                                            <td>{{ $categoria->categoria->nombre}}</td>
                                            <td>{{ $categoria->direccion }}</td>
                                            <td>{{ $categoria->telefono }}</td>
                                            <td>{{ $categoria->correo}}</td>
                                        </tr>                                       
                                       
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-body">

                       {{--  <div class="form-group">
                            <strong>Nombre:</strong>
                            @foreach ($categorias as $categoria)
                                @if (Str::contains($categoria->nombre, 'Transporte'))
                                    <tr>
                                        <td>{{ $proveedore->id }}</td>
                                        <td>{{ $proveedore->nombre }}</td>
                                        <td>{{ $proveedore->categoria->nombre }}</td>
                                        <td>{{ $proveedore->direccion }}</td>
                                        <td>{{ $proveedore->telefono }}</td>
                                        <td>{{ $proveedore->correo }}</td>
                                    </tr>
                                @endif
                            @endforeach

                        </div> --}}

                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#andean').DataTable();
        });
    </script>
@endsection
