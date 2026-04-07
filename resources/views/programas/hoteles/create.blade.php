@extends('layouts.app')
@section('title', 'Nuevo Hotel')

@section('contenido')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="text-dark">Nuevo Hotel</h2>
        </div>
    </div>

    <form action="{{ route('hotel.store') }}" method="POST" enctype="multipart/form-data" class="margin-bottom:2em">
        @csrf
        @include('programas.hoteles.form')
        <button class="btn btn-primary btn-sm">
            <i class="fas fa-save"></i> Guardar
        </button>
        <a href="{{ route('hotel.index') }}" class="btn btn-secondary btn-sm">
            Cancelar
        </a>
    </form>
</div>
@endsection
