{{--
    Tarjeta de un viajero en el formulario del cliente (programas/pasajeros-cliente). Usa $t, $sexos, $dietas, $paises,
    $frecuentes y $mascara del padre.
    @param int|string $i  Índice ('__INDEX__' en la plantilla).
    @param array $fila     Valores.
    @param \App\Models\Pax|null $paxModel
--}}
@php
    $n = fn ($campo) => "paxs[$i][$campo]";
    $e = fn ($campo) => $errors->first("paxs.$i.$campo");
    $v = fn ($campo) => $fila[$campo] ?? '';
    $id = fn ($campo) => "pc-$i-$campo";
@endphp
<fieldset class="pax-form__card" data-pax-item>
    <legend class="pax-form__legend">
        <span class="pax-form__num" data-pax-num></span>
        <span>{{ $v('nombre') ?: $t['new'] }}</span>
        @if (! $paxModel)
            <button type="button" class="pax-form__remove" data-pax-remove><i class="fa fa-times" aria-hidden="true"></i> {{ $t['remove'] }}</button>
        @endif
    </legend>
    @if ($paxModel)
        <input type="hidden" name="{{ $n('id') }}" value="{{ $paxModel->id }}">
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label class="pax-form__label" for="{{ $id('nombre') }}">{{ $t['name'] }} *</label>
            <input id="{{ $id('nombre') }}" type="text" name="{{ $n('nombre') }}" value="{{ $v('nombre') }}" maxlength="255" autocomplete="name"
                class="form-control {{ $e('nombre') ? 'is-invalid' : '' }}" required>
            @if ($e('nombre'))<div class="invalid-feedback">{{ $e('nombre') }}</div>@endif
        </div>
        <div class="col-6 col-md-3">
            <label class="pax-form__label" for="{{ $id('nac') }}">{{ $t['birth'] }} *</label>
            <input id="{{ $id('nac') }}" type="date" name="{{ $n('fecha_nacimiento') }}" value="{{ $v('fecha_nacimiento') }}" max="{{ now()->toDateString() }}"
                class="form-control {{ $e('fecha_nacimiento') ? 'is-invalid' : '' }}" required>
            @if ($e('fecha_nacimiento'))<div class="invalid-feedback">{{ $e('fecha_nacimiento') }}</div>@endif
        </div>
        <div class="col-6 col-md-3">
            <label class="pax-form__label" for="{{ $id('sexo') }}">{{ $t['sex'] }}</label>
            <select id="{{ $id('sexo') }}" name="{{ $n('sexo') }}" class="form-select">
                <option value="">—</option>
                @foreach ($sexos as $clave => $etiqueta)
                    <option value="{{ $clave }}" @selected($v('sexo') === $clave)>{{ $etiqueta }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="pax-form__label" for="{{ $id('pais') }}">{{ $t['country'] }} *</label>
            <select id="{{ $id('pais') }}" name="{{ $n('pais') }}" class="form-select {{ $e('pais') ? 'is-invalid' : '' }}" required>
                <option value="">{{ $t['select'] }}</option>
                <optgroup label="{{ $t['frequent'] }}">
                    @foreach ($frecuentes as $codigo => $nombre)
                        <option value="{{ $codigo }}" @selected($v('pais') === $codigo)>{{ $nombre }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="{{ $t['all'] }}">
                    @foreach ($paises as $codigo => $nombre)
                        <option value="{{ $codigo }}" @selected($v('pais') === $codigo && ! $frecuentes->has($codigo))>{{ $nombre }}</option>
                    @endforeach
                </optgroup>
            </select>
            @if ($e('pais'))<div class="invalid-feedback">{{ $e('pais') }}</div>@endif
        </div>
        <div class="col-6 col-md-3">
            <label class="pax-form__label" for="{{ $id('pas') }}">{{ $t['passport'] }}</label>
            <input id="{{ $id('pas') }}" type="text" name="{{ $n('pasaporte') }}" value="{{ $v('pasaporte') }}" maxlength="50" autocomplete="off"
                class="form-control {{ $e('pasaporte') ? 'is-invalid' : '' }}">
            @if ($paxModel?->pasaporte)
                <small class="pax-form__hint">{{ str_replace(':mask', $mascara($paxModel->pasaporte), $t['passport_kept']) }}</small>
            @endif
        </div>
        <div class="col-6 col-md-3">
            <label class="pax-form__label" for="{{ $id('venc') }}">{{ $t['expiry'] }}</label>
            <input id="{{ $id('venc') }}" type="date" name="{{ $n('pasaporte_vencimiento') }}" value="{{ $v('pasaporte_vencimiento') }}"
                class="form-control {{ $e('pasaporte_vencimiento') ? 'is-invalid' : '' }}">
            <small class="pax-form__hint">{{ $t['expiry_hint'] }}</small>
        </div>

        <div class="col-md-6">
            <label class="pax-form__label" for="{{ $id('scan') }}">{{ $t['scan'] }}</label>
            <input id="{{ $id('scan') }}" type="file" name="{{ $n('pasaporte_archivo') }}" accept=".pdf,.jpg,.jpeg,.png,.webp"
                class="form-control {{ $e('pasaporte_archivo') ? 'is-invalid' : '' }}">
            @if ($e('pasaporte_archivo'))<div class="invalid-feedback">{{ $e('pasaporte_archivo') }}</div>@endif
            @if ($paxModel?->pasaporte_archivo)
                <small class="pax-form__hint pax-form__hint--ok"><i class="fa fa-check" aria-hidden="true"></i> {{ $t['scan_ok'] }}</small>
            @endif
        </div>
        <div class="col-6 col-md-3">
            <label class="pax-form__label" for="{{ $id('dieta') }}">{{ $t['diet'] }}</label>
            <select id="{{ $id('dieta') }}" name="{{ $n('dieta') }}" class="form-select">
                <option value="">—</option>
                @foreach ($dietas as $clave => $etiqueta)
                    <option value="{{ $clave }}" @selected($v('dieta') === $clave)>{{ $etiqueta }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3">
            <label class="pax-form__label" for="{{ $id('alergias') }}">{{ $t['allergies'] }}</label>
            <input id="{{ $id('alergias') }}" type="text" name="{{ $n('alergias') }}" value="{{ $v('alergias') }}" maxlength="255" class="form-control">
        </div>

        <div class="col-12">
            <label class="pax-form__label" for="{{ $id('med') }}">{{ $t['medical'] }}</label>
            <textarea id="{{ $id('med') }}" name="{{ $n('condiciones_medicas') }}" rows="2" maxlength="1000" class="form-control">{{ $v('condiciones_medicas') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="pax-form__label" for="{{ $id('em') }}">{{ $t['emergency'] }}</label>
            <input id="{{ $id('em') }}" type="text" name="{{ $n('emergencia_nombre') }}" value="{{ $v('emergencia_nombre') }}" maxlength="255" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="pax-form__label" for="{{ $id('emtel') }}">{{ $t['emergency_phone'] }}</label>
            <input id="{{ $id('emtel') }}" type="tel" name="{{ $n('emergencia_telefono') }}" value="{{ $v('emergencia_telefono') }}" maxlength="40" autocomplete="tel" class="form-control">
        </div>
    </div>
</fieldset>
