@extends('layouts.app')
@section('title', 'Detalle del Agente')
@section('contenido')
    <div class="container-fluid">
        <div class="row align-items-center mb-3">
            <div class="col">
                <h2 class="fw-bold mb-4">Agente: {{ $agente->nombre }}</h2>
            </div>
            <div class="col-auto">
                <a href="{{ url()->previous() }}" class="btn btn-danger btn-sm">
                    <i class="bi bi-arrow-left-circle"></i> Volver atrás
                </a>
            </div>
            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Teléfono:</strong> {{ $agente->telefono }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $agente->email }}</li>
            </ul>
            @if ($agente->foto)
                <img src="{{ asset($agente->foto) }}" class="rounded shadow-sm" alt="{{ $agente->nombre }}"
                    style="width: 400px">
            @endif
        </div>
    </div>
@endsection
