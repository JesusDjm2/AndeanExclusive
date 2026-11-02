{{-- Campana de notificaciones del topbar. Se actualiza sola: resources/js/avisos.js · Estilos: resources/sass/admin/_avisos.scss --}}
@php
    $avisosCampana = \App\Http\Controllers\AvisoController::recientes(request());
    $noVistas = \App\Models\Aviso::query()->paraUsuario(auth()->user())->noVistosPor(auth()->user())->count();
@endphp
<div class="dropdown avisos-bell" data-avisos data-url="{{ route('avisos.resumen') }}">
    <button class="avisos-bell__btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"
        aria-label="Notificaciones: {{ $noVistas }} sin ver" data-avisos-btn>
        <i class="fas fa-bell" aria-hidden="true"></i>
        <span class="avisos-bell__count" data-avisos-count @if (! $noVistas) hidden @endif>{{ $noVistas > 99 ? '99+' : $noVistas }}</span>
    </button>
    <div class="dropdown-menu dropdown-menu-end shadow avisos-bell__menu">
        <div class="avisos-bell__head">
            <strong>Notificaciones</strong>
            <form action="{{ route('avisos.marcar-todas') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link btn-sm p-0">Marcar todas como vistas</button>
            </form>
        </div>
        <div class="avisos-bell__list" data-avisos-list>
            @include('avisos.partials.items', ['avisos' => $avisosCampana])
        </div>
        <a href="{{ route('avisos.index', ['filtro' => 'todas']) }}" class="avisos-bell__all">Ver todas las notificaciones</a>
    </div>
</div>
