@extends('layouts.app')
@section('title', 'Editar Hotel')

@section('contenido')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <h2 class="text-dark"><small>Editar Hotel:</small> {{ $hotel->nombre }}</h2>
            </div>
        </div>

        <form action="{{ route('hotel.update', $hotel) }}" method="POST" enctype="multipart/form-data" class="margin-bottom:2em">
            @csrf
            @method('PUT')
            @include('programas.hoteles.form')
            <button class="btn btn-primary btn-sm">
                <i class="fas fa-save"></i> Actualizar
            </button>
            <a href="{{ route('hotel.index') }}" class="btn btn-secondary btn-sm">
                Cancelar
            </a>
        </form>
    </div>
@endsection
