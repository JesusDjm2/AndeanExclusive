@extends('layouts.auth')
@section('titulo', 'Recuperar contraseña')

@section('contenido')
    <h1 class="auth-panel__title">Recuperar contraseña</h1>
    <p class="auth-panel__lead">Te enviaremos un enlace para crear una nueva contraseña.</p>

    @if (session('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf
        <div class="mb-4">
            <label for="email" class="form-label">Correo electrónico</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2">Enviar enlace</button>
        <p class="text-center mt-3 mb-0"><a href="{{ route('login') }}" class="small">Volver a iniciar sesión</a></p>
    </form>
@endsection
