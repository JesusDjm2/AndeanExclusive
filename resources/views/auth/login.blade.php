@extends('layouts.auth')
@section('titulo', 'Iniciar sesión')

@section('contenido')
    <div class="auth-title-group">
        <h1 class="auth-card__title">Bienvenido de nuevo</h1>
        <p class="auth-card__lead">Ingresa a tu panel de gestión.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}" class="auth-form" novalidate>
        @csrf
        <div class="mb-3">
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

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-baseline mb-1">
                <label for="password" class="form-label auth-label mb-0">Contraseña</label>
                @if (Route::has('password.request'))
                    <a class="auth-link-subtle" href="{{ route('password.request') }}">¿La olvidaste?</a>
                @endif
            </div>
            <div class="input-group auth-input-group has-validation">
                <span class="input-group-text"><i class="fas fa-lock" aria-hidden="true"></i></span>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="current-password" placeholder="••••••••">
                <button type="button" class="btn btn-outline-secondary btn-toggle-password" data-toggle-password="#password"
                    aria-label="Mostrar contraseña" title="Mostrar contraseña" aria-pressed="false">
                    <i class="fas fa-eye" aria-hidden="true"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check auth-checkbox mb-0">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" @checked(old('remember'))>
                <label class="form-check-label text-muted small ms-1" for="remember">Mantener sesión iniciada</label>
            </div>
        </div>

        <button type="submit" class="btn btn-auth-submit">
            <span>Ingresar al Panel</span>
            <i class="fas fa-arrow-right ms-2" aria-hidden="true"></i>
        </button>
    </form>
@endsection
