@extends('layouts.app')
@section('titulo', $programa->nombre)

@section('contenido')
    @include('programas.form')
@endsection
