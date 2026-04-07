@extends('layouts.app')

@section('contenido')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-secondary text-white text-center fs-4 fw-bold">
                        Dashboard de Andean Exclusive Tours
                    </div>
                    <div class="card-body bg-secondary-subtle text-center">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <p class="mb-4 text-muted fs-5">Tradition of excellence.</p>
                        {{-- Sección de botones principales --}}
                        <div class="row row-cols-1 row-cols-md-3 g-4">
                            {{-- Inglés --}}
                            <div class="col">
                                <div class="card h-100 border-0 shadow-sm">
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
                                <div class="card h-100 border-0 shadow-sm">
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
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <i class="fas fa-cogs fa-3x text-info mb-3"></i>
                                        <h5 class="card-title fw-bold">Administración</h5>
                                        <p class="text-muted">Gestión general del sitio.</p>
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('imagenes.index') }}" class="btn btn-info btn-sm">Imágenes</a>
                                            <a href="{{ route('users.index') }}" class="btn btn-info btn-sm">Usuarios</a>
                                            <a href="{{ route('proveedors.index') }}"
                                                class="btn btn-info btn-sm">Proveedores</a>
                                                <a href=""
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
