{{-- Segundo paso del inicio de sesión (UserController@verificacion). --}}
@extends('layouts.auth')
@section('titulo', 'Código de acceso')

@section('contenido')
    <h1 class="auth-panel__title">Revisa tu correo</h1>
    <p class="auth-panel__lead">Te enviamos un código de 6 dígitos. Vence en 10 minutos.</p>

    <form method="POST" action="{{ route('verificacion.comprobar') }}" class="auth-form" novalidate>
        @csrf
        <div class="mb-3">
            <label for="codigo" class="form-label">Código</label>
            <input id="codigo" type="text" name="codigo" inputmode="numeric" autocomplete="one-time-code" maxlength="6" autofocus required
                class="form-control @error('codigo') is-invalid @enderror">
            @error('codigo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
    <p class="mt-3 small"><a href="{{ route('login') }}">Volver a iniciar sesión</a></p>
@endsection
