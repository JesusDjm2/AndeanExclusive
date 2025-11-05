@extends('layouts.app')
@section('titulo', 'Crear nueva Categoria español')
@section('contenido')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6">
                                <h3>Crear nueva Categorias en Español</h3>
                            </div>
                            <div class="col-lg-6">
                                <a href="{{ route('categorias.index') }}" class="btn btn-danger btn-sm float-right">
                                    Volver
                                </a>
                            </div>
                            <div class="col-lg-12">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-12">
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <p>{{ $message }}</p>
                                        <strong>{{ $message }}</strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-12">
                                <div class="card-body">
                                    <form action="{{ route('categorias.store') }}" method="POST">
                                        @csrf
                                        @include('admin.tours.categorias.form')
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nombreInput = document.getElementById('nombre');
            const slugInput = document.getElementById('slug');

            nombreInput.addEventListener('input', function() {
                const nombre = nombreInput.value.trim();
                const slug = nombre.replace(/\s+/g, '-');
                slugInput.value = slug;
            });
        });
    </script>

@endsection
