@extends('layouts.app')

@section('contenido')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">
                @includeif('partials.errors')
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Actualizar Categoria</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('categoriasproveedor.update', $categoria->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf
                            @include('proveedor.categoria.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
