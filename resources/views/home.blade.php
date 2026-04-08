@extends('layouts.app')
@section('titulo', 'Inicio')

@section('contenido')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">
                <div class="card shadow border-0">
                    <div class="card-header py-3 bg-white border-bottom text-center">
                        <h1 class="h4 mb-0 fw-bold text-secondary">Dashboard de Andean Exclusive Tours</h1>
                    </div>
                    <div class="card-body bg-light text-center">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show text-start" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <p class="mb-4 text-muted">Tradition of excellence.</p>

                        <div class="card shadow mb-4 text-start mx-auto ae-admin-filter-card" style="max-width: 720px;">
                            <div class="card-header py-3 bg-white border-bottom">
                                <h2 class="h6 m-0 fw-bold text-primary">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Filtrar programas por año y mes
                                </h2>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('programas.index') }}"
                                    class="row g-2 align-items-end justify-content-center">
                                    <div class="col-sm-5 col-md-4">
                                        <label class="form-label small text-muted mb-1">Año</label>
                                        <select name="anio_id" class="form-select form-select-sm">
                                            <option value="">Todos los años</option>
                                            @foreach ($anios as $anio)
                                                <option value="{{ $anio->id }}"
                                                    {{ (string) request('anio_id') === (string) $anio->id ? 'selected' : '' }}>
                                                    {{ $anio->anio }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-5 col-md-4">
                                        <label class="form-label small text-muted mb-1">Mes</label>
                                        <select name="mes_id" class="form-select form-select-sm">
                                            <option value="">Todos los meses</option>
                                            @foreach ($meses as $mes)
                                                <option value="{{ $mes->id }}"
                                                    {{ (string) request('mes_id') === (string) $mes->id ? 'selected' : '' }}>
                                                    {{ $mes->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-10 col-md-4 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                            <i class="fas fa-search me-1"></i> Ver programas
                                        </button>
                                        <a href="{{ route('programas.index') }}"
                                            class="btn btn-outline-secondary btn-sm">Limpiar</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Sección de botones principales --}}
                        <div class="row row-cols-1 row-cols-md-3 g-4 text-start">
                            {{-- Inglés --}}
                            <div class="col">
                                <div class="card h-100 border shadow-sm">
                                    <div class="card-body">
                                        <i class="fas fa-language fa-3x text-primary mb-3"></i>
                                        <h5 class="card-title fw-bold">Inglés</h5>
                                        <p class="text-muted">Gestión de tours y blogs en inglés.</p>
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('tours.index') }}" class="btn btn-primary btn-sm">Tours EN</a>
                                            <a href="{{ route('categories.index') }}"
                                                class="btn btn-primary btn-sm">Categorías EN</a>
                                            <a href="{{ route('entags.index') }}" class="btn btn-primary btn-sm">Tags EN</a>
                                            <a href="{{ route('enblogs.index') }}" class="btn btn-primary btn-sm">Blogs
                                                EN</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Español --}}
                            <div class="col">
                                <div class="card h-100 border shadow-sm">
                                    <div class="card-body">
                                        <i class="fas fa-globe-americas fa-3x text-success mb-3"></i>
                                        <h5 class="card-title fw-bold">Español</h5>
                                        <p class="text-muted">Gestión de tours y blogs en español.</p>
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('estours.index') }}" class="btn btn-success btn-sm">Tours
                                                ES</a>
                                            <a href="{{ route('categorias.index') }}"
                                                class="btn btn-success btn-sm">Categorías ES</a>
                                            <a href="{{ route('estags.index') }}" class="btn btn-success btn-sm">Tags ES</a>
                                            <a href="{{ route('esblogs.index') }}" class="btn btn-success btn-sm">Blogs
                                                ES</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Otros --}}
                            <div class="col">
                                <div class="card h-100 border shadow-sm">
                                    <div class="card-body">
                                        <i class="fas fa-cogs fa-3x text-info mb-3"></i>
                                        <h5 class="card-title fw-bold">Administración</h5>
                                        <p class="text-muted">Gestión general del sitio.</p>
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('imagenes.index') }}" class="btn btn-info btn-sm">Imágenes</a>
                                            <a href="{{ route('users.index') }}" class="btn btn-info btn-sm">Usuarios</a>
                                            <a href="{{ route('proveedors.index') }}"
                                                class="btn btn-info btn-sm">Proveedores</a>
                                                <a href="{{ route('programas.index') }}"
                                                class="btn btn-info btn-sm">Programas</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> {{-- row --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
