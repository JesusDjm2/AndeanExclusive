@extends('layouts.auth')
@section('titulo', 'Nueva contraseña')

@section('contenido')
    <h1 class="auth-panel__title">Crea una nueva contraseña</h1>
    <p class="auth-panel__lead">Usa al menos 8 caracteres.</p>

    <form method="POST" action="{{ route('password.update') }}" class="auth-form">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Nueva contraseña</label>
            <div class="input-group has-validation">
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
            <label for="password-confirm" class="form-label">Confirmar contraseña</label>
            <div class="input-group">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                <button type="button" class="btn btn-outline-secondary btn-toggle-password"
                    data-toggle-password="#password-confirm" aria-label="Mostrar contraseña" title="Mostrar contraseña"
                    aria-pressed="false">
                    <i class="fas fa-eye" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">Guardar contraseña</button>
    </form>
@endsection
