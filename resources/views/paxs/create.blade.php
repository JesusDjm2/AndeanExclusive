@extends('layouts.app')
@section('titulo', 'Crear Pax')

@section('contenido')
    <div class="container">
        <h4>Crear Pax</h4>

        <form action="{{ route('paxs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}">
            @error('nombre')
                <small class="text-danger">{{ $message }}</small>
            @enderror

            <label>Edad</label>
            <input type="number" name="edad" class="form-control" value="{{ old('edad') }}">
            @error('edad')
                <small class="text-danger">{{ $message }}</small>
            @enderror

            <label>Pasaporte</label>
            <input type="file" name="pasaporte" class="form-control">
            @error('pasaporte')
                <small class="text-danger">{{ $message }}</small>
            @enderror

            <label>Nacionalidad</label>
            <input type="text" name="nacionalidad" class="form-control" value="{{ old('nacionalidad') }}">
            @error('nacionalidad')
                <small class="text-danger">{{ $message }}</small>
            @enderror

            <label>Alimentación</label>
            <input type="text" name="alimentacion" class="form-control" value="{{ old('alimentacion') }}">
            @error('alimentacion')
                <small class="text-danger">{{ $message }}</small>
            @enderror

            <label>Programa</label>
            <select name="programa_id" class="form-control">
                @foreach ($programas as $p)
                    <option value="{{ $p->id }}" @selected(old('programa_id') == $p->id)>
                        {{ $p->nombre }}
                    </option>
                @endforeach
            </select>
            @error('programa_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror

            <label>Agentes</label>
            <select multiple name="agentes[]" class="form-control">
                @foreach ($agentes as $a)
                    <option value="{{ $a->id }}" @selected(collect(old('agentes'))->contains($a->id))>
                        {{ $a->nombre }}
                    </option>
                @endforeach
            </select>
            @error('agentes')
                <small class="text-danger">{{ $message }}</small>
            @enderror

            <br>
            <button class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection
