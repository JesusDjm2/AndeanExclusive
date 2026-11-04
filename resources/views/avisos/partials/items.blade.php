{{-- Lista de la campana: lo pendiente de ver, lo más reciente primero. --}}
@forelse ($avisos as $aviso)
    <a href="{{ route('avisos.abrir', $aviso->id) }}" class="aviso-mini {{ $aviso->requiere_accion ? 'aviso-mini--accion' : '' }}">
        <span class="aviso-mini__dot" aria-hidden="true"></span>
        <span class="aviso-mini__body">
            <strong>{{ $aviso->titulo }}@if ($aviso->veces > 1) <span class="aviso-veces">×{{ $aviso->veces }}</span>@endif</strong>
            @if ($aviso->detalle)<small>{{ \Illuminate\Support\Str::limit($aviso->detalle, 80) }}</small>@endif
            <small class="aviso-mini__meta">{{ $aviso->updated_at->locale('es')->diffForHumans() }} · <span class="aviso-mini__cta">{{ $aviso->accion }}</span></small>
        </span>
    </a>
@empty
    <p class="aviso-empty"><i class="fas fa-check-circle" aria-hidden="true"></i> Estás al día.</p>
@endforelse
