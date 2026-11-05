@extends('layouts.auth')
@section('titulo', 'Recuperar contraseña')

@section('contenido')
    <div class="auth-title-group">
        <h1 class="auth-card__title">Recuperar contraseña</h1>
        <p class="auth-card__lead">Te enviaremos un enlace a tu correo para restablecer tu contraseña.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="auth-form" novalidate>
        @csrf
        <div class="mb-4">
            <label for="email" class="form-label auth-label">Correo electrónico</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="fas fa-envelope" aria-hidden="true"></i></span>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="tu@correo.com">
                @error('email')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-auth-submit mb-3">
            <span>Enviar enlace</span>
            <i class="fas fa-paper-plane ms-2" aria-hidden="true"></i>
        </button>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="auth-link-subtle">
                <i class="fas fa-arrow-left me-1"></i> Volver a iniciar sesión
            </a>
        </div>
    </form>
@endsection
