@extends('layouts.app')

@section('template_title')
    {{ $proveedor->name ?? 'Show Proveedor' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Mostrar Proveedor: <strong> {{$proveedor->nombre}}</strong></span>
                            <a class="btn btn-primary float-end" href="{{ route('proveedores.index') }}"> Volver</a>
                        </div>   
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Nombre:</strong>
                            {{ $proveedor->nombre }}
                        </div>
                        <div class="form-group">
                            <strong>Categoria:</strong>
                            {{ $proveedor->categoria_id }}
                        </div>
                        <div class="form-group">
                            <strong>RUC:</strong>
                            {{ $proveedor->ruc }}
                        </div>
                        <div class="form-group">
                            <strong>Direccion:</strong>
                            {{ $proveedor->direccion }}
                        </div>
                        <div class="form-group">
                            <strong>Telefono:</strong>
                            {{ $proveedor->telefono }}
                        </div>
                        <div class="form-group">
                            <strong>Correo:</strong>
                            {{ $proveedor->correo }}
                        </div>
                        <div class="form-group">
                            <strong>Detalles:</strong>
                            {!! $proveedor->detalles !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
