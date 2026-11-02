@extends('layouts.app')
@section('titulo', 'Detalle del Pax')
@section('contenido')
    <div class="container">
        <h4>Detalle del Pax</h4>
        <p><strong>Nombre:</strong> {{ $pax->nombre }}</p>
        <p><strong>Edad:</strong> {{ $pax->edad }}</p>
        <p><strong>Nacionalidad:</strong> {{ $pax->nacionalidad }}</p>
        <p><strong>Alimentación:</strong> {{ $pax->alimentacion }}</p>
        <p><strong>Programa:</strong> {{ $pax->programa->nombre }}</p>
        <p><strong>Agentes:</strong>
            @foreach ($pax->agentes as $a)
                {{ $a->nombre }}{{ !$loop->last ? ',' : '' }}
            @endforeach
        </p>

        @if ($pax->pasaporte)
            <p><strong>N.º de pasaporte:</strong> {{ $pax->pasaportePara(auth()->user()) }}</p>
        @endif
        @if ($pax->pasaporte_archivo && auth()->user()->can('pasaportes.ver'))
            <a href="{{ route('paxs.pasaporte', $pax) }}" target="_blank" rel="noopener">Ver pasaporte escaneado</a>
        @endif
    </div>
@endsection
