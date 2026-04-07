@extends('layouts.app')
@section('title', 'Nuevo Agente')
@section('contenido')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2">
                <h1>Registrar nuevo agente</h1>

                <form action="{{ route('agentes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('programas.agentes.form', ['modo' => 'crear'])
                </form>
            </div>
        </div>
    </div>
@endsection
