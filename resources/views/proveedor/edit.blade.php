@extends('layouts.app')

@section('titulo', 'Editar Proveedor')

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                @includeif('partials.errors')
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Editar Proveedor</span>
                    </div>
                    @include('proveedor.form')
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
@endsection
