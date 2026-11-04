{{-- Notificaciones entre áreas (AvisoController@index). Estilos: resources/sass/admin/_avisos.scss --}}
@extends('layouts.app')
@section('titulo', 'Notificaciones')

@php
    $esAdmin = auth()->user()->hasRole(\App\Support\Permisos::ADMIN);
    $pestanas = ['no_vistas' => 'No vistas', 'vistas' => 'Vistas', 'todas' => 'Todas'];
@endphp

@section('contenido')
    <div class="ae-admin-page-header d-flex flex-wrap justify-content-between align-items-start gap-2">
        <div>
            <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-bell"></i> Notificaciones</h1>
            <small class="ae-admin-page-desc">Lo que tu área debe saber o hacer. Cada aviso lleva directo a donde se resuelve.</small>
        </div>
        @if ($conteos['no_vistas'])
            <form action="{{ route('avisos.marcar-todas') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="fas fa-check-double" aria-hidden="true"></i> Marcar todas como vistas</button>
            </form>
        @endif
    </div>

    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
        @if (session($key))
            <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
    @endforeach

    <nav class="prog-tabs" aria-label="Filtro">
        @foreach ($pestanas as $clave => $etiqueta)
            <a href="{{ route('avisos.index', array_filter(['filtro' => $clave, 'area' => request('area')])) }}"
                class="prog-tabs__tab {{ $filtro === $clave ? 'is-active' : '' }}" @if ($filtro === $clave) aria-current="page" @endif>
                {{ $etiqueta }} <span class="prog-tabs__count">{{ $conteos[$clave] }}</span>
            </a>
        @endforeach
    </nav>

    @if ($esAdmin)
        <form method="GET" action="{{ route('avisos.index') }}" class="avisos-filter">
            <input type="hidden" name="filtro" value="{{ $filtro }}">
            <label for="avisos-area" class="prog-label mb-0">Área</label>
            <select id="avisos-area" name="area" class="form-select form-select-sm" data-autosubmit>
                <option value="">Todas</option>
                @foreach (array_diff_key(\App\Support\Permisos::AREAS, [\App\Support\Permisos::ADMIN => 1]) as $rol => $area)
                    <option value="{{ $rol }}" @selected(request('area') === $rol)>{{ $area }}</option>
                @endforeach
            </select>
            <noscript><button type="submit" class="btn btn-sm btn-outline-primary">Filtrar</button></noscript>
        </form>
    @endif

    @if ($avisos->isEmpty())
        <section class="prog-section mt-3">
            <div class="prog-section__body aviso-empty">
                <i class="fas fa-check-circle" aria-hidden="true"></i>
                {{ $filtro === 'no_vistas' ? 'Estás al día: no hay notificaciones sin ver.' : 'No hay notificaciones aquí.' }}
            </div>
        </section>
    @else
        <ul class="aviso-list">
            @foreach ($avisos as $aviso)
                @php $pendiente = ! $aviso->visto && ! $aviso->resuelto_at; @endphp
                <li class="aviso {{ $pendiente ? 'is-unread' : '' }}">
                    <span class="aviso__dot" aria-hidden="true"></span>
                    <div class="aviso__body">
                        <p class="aviso__title">
                            @if ($pendiente)<span class="visually-hidden">Sin ver: </span>@endif
                            {{ $aviso->titulo }}
                            @if ($aviso->veces > 1)<span class="aviso-veces" title="Se actualizó {{ $aviso->veces }} veces">×{{ $aviso->veces }}</span>@endif
                            @if ($aviso->resuelto_at)
                                <span class="svc-badge svc-badge--confirmado"><i class="fas fa-check" aria-hidden="true"></i> Resuelto</span>
                            @elseif ($aviso->requiere_accion)
                                <span class="svc-badge svc-badge--pendiente">Requiere acción</span>
                            @endif
                        </p>
                        @if ($aviso->detalle)<p class="aviso__detail">{{ $aviso->detalle }}</p>@endif
                        <p class="aviso__meta">
                            {{ $aviso->updated_at->locale('es')->diffForHumans() }}
                            @if ($aviso->actor_nombre) · {{ $aviso->actor_nombre }}@endif
                            @if ($esAdmin) · para {{ \App\Support\Permisos::AREAS[$aviso->area] ?? $aviso->area }}@endif
                        </p>
                    </div>
                    <div class="aviso__actions">
                        <a href="{{ route('avisos.abrir', $aviso->id) }}" class="btn btn-sm {{ $pendiente && $aviso->requiere_accion ? 'btn-primary' : 'btn-outline-primary' }}">
                            {{ $aviso->accion }} <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                        @unless ($aviso->resuelto_at)
                            <form action="{{ route('avisos.marcar', $aviso->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="visto" value="{{ $aviso->visto ? 0 : 1 }}">
                                <button type="submit" class="btn btn-sm btn-link" title="{{ $aviso->visto ? 'Marcar como no vista' : 'Marcar como vista' }}"
                                    aria-label="{{ $aviso->visto ? 'Marcar como no vista' : 'Marcar como vista' }}: {{ $aviso->titulo }}">
                                    <i class="fas {{ $aviso->visto ? 'fa-eye-slash' : 'fa-eye' }}" aria-hidden="true"></i>
                                </button>
                            </form>
                        @endunless
                    </div>
                </li>
            @endforeach
        </ul>
        @if ($avisos->hasPages())
            <div class="mt-3">{{ $avisos->links('pagination::bootstrap-5') }}</div>
        @endif
    @endif
@endsection
