@extends('layouts.auth')
@section('titulo', 'Iniciar sesión')

@section('contenido')
    <h1 class="auth-panel__title">Bienvenido de nuevo</h1>
    <p class="auth-panel__lead">Ingresa a tu panel de gestión.</p>

    @if (session('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}" class="auth-form" novalidate>
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope" aria-hidden="true"></i></span>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="tu@correo.com">
                @error('email')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-baseline">
                <label for="password" class="form-label">Contraseña</label>
                @if (Route::has('password.request'))
                    <a class="small" href="{{ route('password.request') }}">¿La olvidaste?</a>
                @endif
            </div>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock" aria-hidden="true"></i></span>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" @checked(old('remember'))>
            <label class="form-check-label" for="remember">Mantener la sesión iniciada</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">
            Ingresar <i class="fas fa-arrow-right" aria-hidden="true"></i>
        </button>
    </form>
@endsection
