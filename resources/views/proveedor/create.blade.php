@extends('layouts.app')
@section('titulo', 'Crear nuevo Proveedor')
@section('contenido')
    <section class="content row">
        <div class="container">
            <div class="col-md-12">
                @includeif('partials.errors')
                <div class="card card-default">
                    <div class="card-header bg-secondary text-white">
                        <span class="card-title">Crear Proveedor</span>
                    </div>
                    @include('proveedor.form')
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.ckeditor').ckeditor();
        });
    </script>
    {{-- <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.9.2/ckeditor.js"></script>

    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#detalles'))
            .catch(error => console.error(error));
    </script> --}}



@endsection
