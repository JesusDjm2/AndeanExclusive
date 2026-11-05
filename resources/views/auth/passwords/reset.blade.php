@extends('layouts.auth')
@section('titulo', 'Nueva contraseña')

@section('contenido')
    <div class="auth-title-group">
        <h1 class="auth-card__title">Nueva contraseña</h1>
        <p class="auth-card__lead">Crea una nueva contraseña segura para tu cuenta.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="auth-form" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label for="email" class="form-label auth-label">Correo electrónico</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="fas fa-envelope" aria-hidden="true"></i></span>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="tu@correo.com">
                @error('email')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label auth-label">Nueva contraseña</label>
            <div class="input-group auth-input-group has-validation">
                <span class="input-group-text"><i class="fas fa-lock" aria-hidden="true"></i></span>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="new-password" placeholder="••••••••">
                <button type="button" class="btn btn-outline-secondary btn-toggle-password"
                    data-toggle-password="#password" aria-label="Mostrar contraseña" title="Mostrar contraseña"
                    aria-pressed="false">
                    <i class="fas fa-eye" aria-hidden="true"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="password-confirm" class="form-label auth-label">Confirmar contraseña</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="fas fa-check-double" aria-hidden="true"></i></span>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                    required autocomplete="new-password" placeholder="••••••••">
                <button type="button" class="btn btn-outline-secondary btn-toggle-password"
                    data-toggle-password="#password-confirm" aria-label="Mostrar contraseña" title="Mostrar contraseña"
                    aria-pressed="false">
                    <i class="fas fa-eye" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-auth-submit mb-3">
            <span>Guardar contraseña</span>
            <i class="fas fa-check ms-2" aria-hidden="true"></i>
        </button>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="auth-link-subtle">
                <i class="fas fa-arrow-left me-1"></i> Volver a iniciar sesión
            </a>
        </div>
    </form>
@endsection
