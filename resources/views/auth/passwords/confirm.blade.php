@extends('layouts.auth')
@section('titulo', 'Confirmar contraseña')

@section('contenido')
    <div class="auth-title-group">
        <h1 class="auth-card__title">Confirmar contraseña</h1>
        <p class="auth-card__lead">Por seguridad, confirma tu contraseña antes de continuar.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="auth-form" novalidate>
        @csrf

        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-baseline mb-1">
                <label for="password" class="form-label auth-label mb-0">Contraseña</label>
                @if (Route::has('password.request'))
                    <a class="auth-link-subtle" href="{{ route('password.request') }}">¿La olvidaste?</a>
                @endif
            </div>
            <div class="input-group auth-input-group has-validation">
                <span class="input-group-text"><i class="fas fa-lock" aria-hidden="true"></i></span>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="current-password" placeholder="••••••••" autofocus>
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

        <button type="submit" class="btn btn-auth-submit mb-3">
            <span>Confirmar acceso</span>
            <i class="fas fa-shield-alt ms-2" aria-hidden="true"></i>
        </button>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="auth-link-subtle">
                <i class="fas fa-arrow-left me-1"></i> Volver a iniciar sesión
            </a>
        </div>
    </form>
@endsection
