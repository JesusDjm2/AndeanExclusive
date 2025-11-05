@extends('layouts.app')
@section('titulo', 'Listado de Tags')
@section('contenido')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6">
                                <h3>Lista de Tags en Inglés</h3>
                            </div>
                            <div class="col-lg-6">
                                <a href="{{ route('entags.create') }}" class="btn btn-primary btn-sm float-right">
                                    Crear nuevo tag
                                </a>
                            </div>
                            <div class="col-lg-12">
                                @if ($message = Session::get('success'))
                                    @if ($message = Session::get('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <p>{{ $message }}</p>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div class="col-lg-12">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="thead">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nombre</th>
                                                    <th>Slug</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($tags as $cat)
                                                    <tr>
                                                        <td>{{ $cat->id }}</td>
                                                        <td>{{ $cat->nombre }}</td>
                                                        <td>{{ $cat->slug }}</td>
                                                        <td>
                                                            <form action="{{ route('entags.destroy', $cat->id) }}"
                                                                method="POST">
                                                                <a class="btn btn-sm btn-primary "
                                                                    href="{{ route('entag.show', $cat->slug) }}"><i
                                                                        class="fa fa-fw fa-eye"></i> Show</a>
                                                                <a class="btn btn-sm btn-success"
                                                                    href="{{ route('entags.edit', $cat->id) }}"><i
                                                                        class="fa fa-fw fa-edit"></i> Edit</a>
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"><i
                                                                        class="fa fa-fw fa-trash"></i> Delete</button>
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
                    </div>
                </div>
            </div>
    </section>
@endsection