{{--
    Un tema de ayuda desplegable. Quien no lo gestiona ve el resumen y a quién acudir, sin los pasos.
    @param string $clave
    @param array  $tema   Ver App\Support\Ayuda::TEMAS.
    @param bool   $abierto
    @param bool   $buscable  Lo filtra el buscador del centro de ayuda.
--}}
@php
    $puede = \App\Support\Ayuda::puede($tema, auth()->user());
    $consulta = $tema['consulta'] ?? \App\Support\Ayuda::GRUPOS[$tema['grupo']]['consulta'];
@endphp
<details class="ayuda-tema" id="tema-{{ $clave }}" @if ($tema['tab'] ?? false) data-ayuda-tab="{{ $tema['tab'] }}" @endif @if ($puede) data-ayuda-propio @endif @if ($buscable ?? false) data-ayuda-item data-ayuda-texto="{{ implode(' ', [...$tema['pasos'], ...($tema['notas'] ?? [])]) }}" @endif @if ($abierto ?? false) open @endif>
    <summary>
        <span>{{ $tema['titulo'] }}</span>
        @unless ($puede)<span class="ayuda-tema__consulta">Solo consulta</span>@endunless
    </summary>
    <div class="ayuda-tema__cuerpo">
        <p>{{ $tema['resumen'] }}</p>
        @if ($puede)
            <ol class="ayuda-pasos">
                @foreach ($tema['pasos'] as $paso)
                    <li>{{ $paso }}</li>
                @endforeach
            </ol>
            @foreach ($tema['notas'] ?? [] as $nota)
                <p class="ayuda-nota"><i class="fas fa-lightbulb" aria-hidden="true"></i><span>{{ $nota }}</span></p>
            @endforeach
        @else
            <p class="ayuda-nota"><i class="fas fa-eye" aria-hidden="true"></i><span>{{ $consulta }}</span></p>
        @endif
    </div>
</details>
