@extends('layouts.app')
@section('titulo', $formHeading ?? 'Nuevo usuario')

@section('contenido')
    <div class="container-fluid">
        <div class="row align-items-center ae-admin-page-header">
            <div class="col-lg-8 text-start mb-2 mb-lg-0">
                <h2 class="ae-admin-page-title">
                    <i class="fas fa-fw fa-user-plus text-primary me-2"></i>
                    {{ $formHeading ?? 'Nuevo Usuario' }}
                </h2>
                <small class="ae-admin-page-desc">Crea una cuenta para dar acceso al panel administrativo</small>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver a usuarios
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-3 bg-white border-bottom">
                        <span class="fw-bold text-secondary">
                            <i class="fas fa-id-card me-1 text-primary"></i> Datos de la cuenta
                        </span>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ $formAction ?? route('users.store') }}" novalidate>
                            @csrf
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }} <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                        placeholder="Ej. Carlos Mendoza">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autocomplete="email"
                                        placeholder="ejemplo@andeanexclusive.com">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }} <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <div class="input-group has-validation">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres">
                                        <button type="button" class="btn btn-outline-secondary btn-toggle-password"
                                            data-toggle-password="#password" aria-label="Mostrar contraseña" title="Mostrar contraseña"
                                            aria-pressed="false">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </button>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" required autocomplete="new-password" placeholder="Repite la contraseña">
                                        <button type="button" class="btn btn-outline-secondary btn-toggle-password"
                                            data-toggle-password="#password-confirm" aria-label="Mostrar contraseña" title="Mostrar contraseña"
                                            aria-pressed="false">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label for="role" class="col-md-4 col-form-label text-md-end">Rol en el panel <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                        <option value="administrador" {{ old('role') === 'administrador' ? 'selected' : '' }}>Administrador (incluye usuarios)</option>
                                        <option value="operaciones" {{ old('role') === 'operaciones' ? 'selected' : '' }}>Operaciones y ventas</option>
                                        <option value="contabilidad" {{ old('role', 'contabilidad') === 'contabilidad' ? 'selected' : '' }}>Contabilidad</option>
                                        <option value="reservas" {{ old('role') === 'reservas' ? 'selected' : '' }}>Reservas</option>
                                    </select>
                                    @error('role')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-7 offset-md-4 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-1"></i> {{ $submitLabel ?? 'Crear usuario' }}
                                    </button>
                                    <a href="{{ route('users.index') }}" class="btn btn-light border px-3">
                                        Cancelar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
