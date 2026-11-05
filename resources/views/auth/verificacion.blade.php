{{-- Segundo paso del inicio de sesión (UserController@verificacion). --}}
@extends('layouts.auth')
@section('titulo', 'Código de acceso')

@section('contenido')
    <div class="auth-title-group">
        <h1 class="auth-card__title">Revisa tu correo</h1>
        <p class="auth-card__lead">Te enviamos un código de 6 dígitos. Vence en 10 minutos.</p>
    </div>

    <form method="POST" action="{{ route('verificacion.comprobar') }}" class="auth-form" novalidate>
        @csrf
        <div class="mb-4">
            <label for="codigo" class="form-label auth-label">Código de verificación</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="fas fa-shield-alt" aria-hidden="true"></i></span>
                <input id="codigo" type="text" name="codigo" inputmode="numeric" autocomplete="one-time-code" maxlength="6" autofocus required
                    placeholder="123456" class="form-control text-center fs-4 fw-bold @error('codigo') is-invalid @enderror" style="letter-spacing: 0.35em;">
                @error('codigo')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-auth-submit mb-3">
            <span>Entrar al Panel</span>
            <i class="fas fa-arrow-right ms-2" aria-hidden="true"></i>
        </button>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="auth-link-subtle">
                <i class="fas fa-arrow-left me-1"></i> Volver a iniciar sesión
            </a>
        </div>
    </form>
@endsection
