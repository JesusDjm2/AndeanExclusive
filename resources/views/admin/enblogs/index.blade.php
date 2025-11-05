@extends('layouts.app')
@section('titulo', 'Listado de blog en inglés')
@section('contenido')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h4>Listado de Blogs</h4>
                <a href="{{ route('enblogs.create') }}" class="btn btn-primary btn-sm float-right">
                    Crear nuevo Blog
                </a>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
            <div class="table-responsive">
                <table id="tabladatos" class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción Corta</th>
                            <th>Tags</th>
                            <th>Img Thumb</th>
                            <th>Img Full</th>
                            <th>Slug</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($blogs as $registro)
                            <tr>
                                <td>{{ $registro->id }}</td>
                                <td>{{ $registro->nombre }}</td>
                                <td>{{ $registro->descripcionCorta }}</td>
                                <td>
                                    @foreach ($registro->tags as $tag)
                                        <span class="badge badge-info">{{ $tag->nombre }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <img src="{{ asset('img/thumb/' . $registro->imgThumb) }}" width="90px" style="height: 50px; object-fit: cover">
                                </td>
                                <td>
                                    <img src="{{ asset('img/full/' . $registro->imgFull) }}" width="90px" style="height: 50px; object-fit: cover">
                                </td>
                                <td>{{ $registro->slug }}</td>
                                <td style="width: 120px">
                                    <a href="{{ route('enblog.show', $registro->slug) }}"
                                        class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('enblogs.edit', $registro->id) }}"
                                        class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('enblogs.destroy', $registro->id) }}" method="POST"
                                        style="display: inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        var j = jQuery.noConflict();
        j(document).ready(function() {
            j('#tabladatos').DataTable();
        });
    </script>
@endsection
