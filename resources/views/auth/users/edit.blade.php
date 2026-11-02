@extends('layouts.app')
@section('title', 'Editar usuario')
@section('contenido')
@if (session('status'))
<div class="text-success">
    <h4>{{ session('status') }}</h4>
</div>
@endif
<div class="row">
    <div class="col-lg-12">
        <span>Actualizar usuario <strong>{{$user->name}}</strong></span>
        <a href="{{ route('users.index') }}" class="btn btn-danger mt-4 float-end">Cancelar</a>
    </div>
    <div class="col-12">        
        <form action="{{route('users.update', $user->id)}}" method="post" class="bg-light">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" id="name" name="name" class="form-control" required value="{{ $user->name }}">
                </div>
                <div class="col-lg-12">
                    <label for="descripcion" class="form-label">Email:</label>
                    <input type="text" id="email" name="email" class="form-control" required value="{{ $user->email }}">
                </div>
                <div class="col-lg-12 mt-3">
                    <label for="role" class="form-label">Rol:</label>
                    @php
                        $currentRole = old('role', $user->roles->first()?->name ?? 'contabilidad');
                    @endphp
                    <select id="role" name="role" class="form-control" required>
                        <option value="administrador" {{ $currentRole === 'administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="operaciones" {{ $currentRole === 'operaciones' ? 'selected' : '' }}>Operaciones y ventas</option>
                        <option value="contabilidad" {{ $currentRole === 'contabilidad' ? 'selected' : '' }}>Contabilidad</option>
                        <option value="reservas" {{ $currentRole === 'reservas' ? 'selected' : '' }}>Reservas</option>
                    </select>
                </div>
            </div>            
            <button class="btn btn-primary mt-4" type="submit">Actualizar</button>            
            <span class="float-end">
                @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Olvidaste tu contraseña?') }}
                </a>
                @endif
            </span>
        </form>
    </div>
</div>
@endsection