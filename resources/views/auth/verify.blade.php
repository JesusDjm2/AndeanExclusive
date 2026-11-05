@extends('layouts.auth')
@section('titulo', 'Verifica tu correo')

@section('contenido')
    <div class="auth-title-group">
        <h1 class="auth-card__title">Verifica tu correo</h1>
        <p class="auth-card__lead">Antes de continuar, por favor revisa el enlace enviado a tu correo electrónico.</p>
    </div>

    @if (session('resent'))
        <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>Se ha enviado un nuevo enlace de verificación a tu correo.</div>
        </div>
    @endif

    <div class="text-center mb-4">
        <p class="text-muted small">
            ¿No recibiste el correo de verificación? Puedes solicitar otro haciendo clic en el botón siguiente.
        </p>
    </div>

    <form class="auth-form" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="btn btn-auth-submit mb-3">
            <span>Reenviar enlace</span>
            <i class="fas fa-redo ms-2" aria-hidden="true"></i>
        </button>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="auth-link-subtle">
                <i class="fas fa-arrow-left me-1"></i> Volver a iniciar sesión
            </a>
        </div>
    </form>
@endsection
