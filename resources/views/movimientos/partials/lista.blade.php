{{--
    Lista de movimientos agrupada por día. Estilos: resources/sass/admin/_movimientos.scss
    @param \Illuminate\Support\Collection $movimientos
    @param bool $conPrograma  Muestra el código del programa (no hace falta dentro de la ficha del programa).
    @param bool $compacto     Sin el detalle de los cambios (panel de inicio).
--}}
@php
    $conPrograma ??= true;
    $compacto ??= false;
@endphp
@if ($movimientos->isEmpty())
    <p class="mov-empty">Todavía no hay movimientos registrados.</p>
@else
    @foreach ($movimientos->groupBy(fn ($m) => $m->created_at->toDateString()) as $dia => $delDia)
        <h3 class="mov-day">{{ ucfirst(\Carbon\Carbon::parse($dia)->locale('es')->isoFormat('dddd, D [de] MMMM')) }}</h3>
        <ul class="mov-list">
            @foreach ($delDia as $mov)
                @php
                    $cambios = $mov->cambiosVisibles();
                    // Verbos como "solicitó el pago de" ya dicen de qué se trata.
                    $objeto = str_ends_with($mov->verbo, ' de') ? '' : mb_strtolower($mov->modulo);
                @endphp
                <li class="mov">
                    <time class="mov__time" datetime="{{ $mov->created_at->toIso8601String() }}">{{ $mov->created_at->format('H:i') }}</time>
                    <div class="mov__body">
                        <p class="mov__text">
                            <strong>{{ $mov->usuario }}</strong>
                            @if ($mov->area)<span class="mov__area mov__area--{{ \Illuminate\Support\Str::slug($mov->area) }}">{{ $mov->area }}</span>@endif
                            {{ $mov->verbo }} {{ $objeto }} @if ($mov->titulo)<span class="mov__title">{{ $mov->titulo }}</span>@endif
                            @if ($conPrograma && $mov->programa_codigo)
                                ·
                                <x-programa-codigo :programa="$mov->programa" :codigo="$mov->programa_codigo" tab="historial" />
                            @endif
                        </p>
                        @if (! $compacto && $cambios)
                            <ul class="mov__changes">
                                @foreach ($cambios as $cambio)
                                    <li>
                                        <span>{{ $cambio['campo'] }}:</span>
                                        @if (isset($cambio['de']) || isset($cambio['a']))
                                            @isset($cambio['de'])<del>{{ $cambio['de'] }}</del> <i class="fas fa-arrow-right" aria-label="pasó a"></i>@endisset
                                            {{ $cambio['a'] ?? 'vacío' }}
                                        @else
                                            modificado
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @endforeach
@endif
