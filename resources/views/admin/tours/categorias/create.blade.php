@extends('layouts.app')
@section('titulo', 'Nueva categoría EN')
@section('contenido')
    <div class="container-fluid">
        <div class="row align-items-center ae-admin-page-header">
            <div class="col text-start">
                <h2 class="ae-admin-page-title">
                    <i class="fas fa-fw fa-folder-plus text-primary me-2"></i>
                    Nueva categoría (inglés)
                </h2>
                <small class="ae-admin-page-desc">Tours EN</small>
            </div>
            <div class="col-auto">
                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-danger">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    @include('admin.tours.categorias.form')
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nombreInput = document.getElementById('nombre');
            const slugInput = document.getElementById('slug');
            if (!nombreInput || !slugInput) return;
            nombreInput.addEventListener('input', function() {
                const nombre = nombreInput.value.trim();
                slugInput.value = nombre.replace(/\s+/g, '-');
            });
        });
    </script>
@endpush
