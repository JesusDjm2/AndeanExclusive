@extends('layouts.app')

@section('title')
    Proveedores
@endsection

@section('contenido')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Proveedor') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('proveedors.create') }}" class="btn btn-primary btn-sm"
                                    data-placement="left">
                                    {{ __('Nuevo registro') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="andean">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        <th>Nombre:</th>
                                        <th>Categoria:</th>
                                        <th>RUC:</th>
                                        <th>Direccion:</th>
                                        <th>Telefono:</th>
                                        <th>Correo:</th>
                                        <th>Detalles:</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($proveedors as $proveedor)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            <td>{{ $proveedor->nombre }}</td>
                                            <td>{{ $proveedor->categoria->nombre }}</td>
                                            <td>{{ $proveedor->ruc }}</td>
                                            <td>{{ $proveedor->direccion }}</td>
                                            <td>{{ $proveedor->telefono }}</td>
                                            <td>{{ $proveedor->correo }}</td>
                                            <td>{!! $proveedor->detalles !!}</td>

                                            <td style="width: 140px">
                                                <form action="{{ route('proveedores.destroy', $proveedor->id) }}"
                                                    method="POST">
                                                    <a class="btn btn-sm btn-primary "
                                                        href="{{ route('proveedores.show', $proveedor->id) }}"
                                                        title="Mostrar"><i class="fa fa-fw fa-eye"></i></a>
                                                    <a class="btn btn-sm btn-success"
                                                        href="{{ route('proveedores.edit', $proveedor->id) }}"
                                                        title="Editar"><i class="fa fa-fw fa-edit"></i></a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                                            class="fa fa-fw fa-trash" title="Borrar"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $proveedors->links() !!}
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#andean').DataTable();
        });
    </script>
@endsection
