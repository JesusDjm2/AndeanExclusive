{{--
    Campos del cliente. Se usan en la ficha (clientes.form) y en el alta rápida dentro del formulario de programas.
    @param \App\Models\Cliente $cliente
    @param string $prefix    Prefijo de los nombres ('' o 'cliente_nuevo').
    @param string $idPrefix  Prefijo de los id HTML.
--}}
@php
    $n = fn (string $field) => $prefix ? "{$prefix}[{$field}]" : $field;
    $key = fn (string $field) => $prefix ? "{$prefix}.{$field}" : $field;
    $val = fn (string $field) => old($key($field), $cliente->{$field});
    $tipos = \App\Models\Cliente::TIPOS;
    $origenes = \App\Models\Cliente::ORIGENES;
@endphp
<div class="row g-3" data-cliente-campos>
    <div class="col-sm-6 col-lg-4">
        <label class="prog-label" for="{{ $idPrefix }}-tipo">Tipo</label>
        <select id="{{ $idPrefix }}-tipo" name="{{ $n('tipo') }}" class="form-select @error($key('tipo')) is-invalid @enderror" data-cliente-tipo>
            @foreach ($tipos as $value => $label)
                <option value="{{ $value }}" @selected($val('tipo') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-6 col-lg-4">
        <label class="prog-label" for="{{ $idPrefix }}-nombre">Nombre del contacto <span class="required">*</span></label>
        <input id="{{ $idPrefix }}-nombre" type="text" name="{{ $n('nombre') }}" value="{{ $val('nombre') }}" maxlength="255"
            class="form-control @error($key('nombre')) is-invalid @enderror">
        @error($key('nombre'))<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-6 col-lg-4" data-cliente-empresa @if ($val('tipo') !== 'agencia') hidden @endif>
        <label class="prog-label" for="{{ $idPrefix }}-empresa">Agencia / empresa <span class="required">*</span></label>
        <input id="{{ $idPrefix }}-empresa" type="text" name="{{ $n('empresa') }}" value="{{ $val('empresa') }}" maxlength="255"
            class="form-control @error($key('empresa')) is-invalid @enderror">
        @error($key('empresa'))<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-6 col-lg-4">
        <label class="prog-label" for="{{ $idPrefix }}-email">Correo</label>
        <input id="{{ $idPrefix }}-email" type="email" name="{{ $n('email') }}" value="{{ $val('email') }}" maxlength="255"
            class="form-control @error($key('email')) is-invalid @enderror" placeholder="cliente@correo.com">
        @error($key('email'))<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-6 col-lg-4">
        <label class="prog-label" for="{{ $idPrefix }}-telefono">Teléfono / WhatsApp</label>
        <input id="{{ $idPrefix }}-telefono" type="tel" name="{{ $n('telefono') }}" value="{{ $val('telefono') }}" maxlength="40"
            class="form-control @error($key('telefono')) is-invalid @enderror" placeholder="+1 555 123 4567">
        @error($key('telefono'))<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-6 col-lg-4">
        <label class="prog-label" for="{{ $idPrefix }}-pais">País</label>
        @include('admin.partials.select-pais', ['name' => $n('pais'), 'id' => $idPrefix . '-pais', 'value' => $val('pais'), 'class' => $errors->has($key('pais')) ? 'is-invalid' : ''])
    </div>
    <div class="col-sm-6 col-lg-4">
        <label class="prog-label" for="{{ $idPrefix }}-idioma">Idioma de atención</label>
        <select id="{{ $idPrefix }}-idioma" name="{{ $n('idioma') }}" class="form-select">
            <option value="">—</option>
            <option value="en" @selected($val('idioma') === 'en')>Inglés</option>
            <option value="es" @selected($val('idioma') === 'es')>Español</option>
        </select>
    </div>
    <div class="col-sm-6 col-lg-4">
        <label class="prog-label" for="{{ $idPrefix }}-origen">¿Cómo nos conoció?</label>
        <select id="{{ $idPrefix }}-origen" name="{{ $n('origen') }}" class="form-select">
            <option value="">—</option>
            @foreach ($origenes as $value => $label)
                <option value="{{ $value }}" @selected($val('origen') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <label class="prog-label" for="{{ $idPrefix }}-notas">Notas</label>
        <textarea id="{{ $idPrefix }}-notas" name="{{ $n('notas') }}" rows="2" maxlength="2000" class="form-control"
            placeholder="Preferencias, ocasión especial (luna de miel, aniversario), detalles de atención…">{{ $val('notas') }}</textarea>
    </div>
</div>
