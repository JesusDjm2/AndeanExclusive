@extends('layouts.app')
@section('title', 'Editar Agente')
@section('contenido')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2">
                <h1>Editar agente</h1>

                <form action="{{ route('agentes.update', $agente) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('programas.agentes.form', ['modo' => 'editar'])
                </form>
            </div>
        </div>
    </div>
@endsection
