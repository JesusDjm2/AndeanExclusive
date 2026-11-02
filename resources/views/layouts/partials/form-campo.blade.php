{{--
    Un campo de los formularios públicos: etiqueta, icono, ayuda y error accesibles.
    @param string $form        Formulario al que pertenece: solo muestra errores y valores previos de ese formulario.
    @param string $name
    @param string $label
    @param string $type        text, email, tel, date, textarea o stepper.
    @param bool   $required    Lo opcional se señala; lo obligatorio es lo normal.
    @param string $opcional    Texto de la marca de opcional.
    @param string $icon        Icono de Font Awesome 4, sin el prefijo fa-.
    @param string $autocomplete
    @param string $placeholder
    @param string $hint
    @param bool   $full        Ocupa todo el ancho.
    @param array  $attrs       Otros atributos del campo.
--}}
@php
    $id = "{$form}-{$name}";
    $activo = session('form_activo') === $form;
    $error = $activo ? $errors->first($name) : null;
    $valor = $activo ? old($name) : ($valor ?? null);
    $type = $type ?? 'text';
    $required = $required ?? false;
    $hint = $hint ?? null;
    $describe = collect([$hint ? "{$id}-ayuda" : null, $error ? "{$id}-error" : null])->filter()->implode(' ');
    $atributos = collect($attrs ?? [])->map(fn ($v, $k) => $k . '="' . e($v) . '"')->implode(' ');
@endphp
<div class="aet-field {{ ($full ?? false) ? 'aet-field--full' : '' }} {{ $error ? 'is-invalid' : '' }}" data-aet-field>
    <label class="aet-label" for="{{ $id }}">
        {{ $label }}
        @unless ($required)<span class="aet-label__opcional">{{ $opcional }}</span>@endunless
    </label>

    @if ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" class="aet-input aet-input--area" rows="4" maxlength="5000" placeholder="{{ $placeholder ?? '' }}"
            @if ($required) required @endif @if ($describe) aria-describedby="{{ $describe }}" @endif @if ($error) aria-invalid="true" @endif>{{ $valor }}</textarea>
    @elseif ($type === 'stepper')
        <div class="aet-stepper" data-aet-stepper>
            <button type="button" class="aet-stepper__btn" data-aet-menos aria-label="{{ $restar }}" hidden><i class="fa fa-minus" aria-hidden="true"></i></button>
            <input id="{{ $id }}" type="number" name="{{ $name }}" value="{{ $valor ?? ($inicial ?? '') }}" inputmode="numeric" class="aet-input aet-stepper__input"
                @if ($required) required @endif @if ($describe) aria-describedby="{{ $describe }}" @endif @if ($error) aria-invalid="true" @endif {!! $atributos !!}>
            <button type="button" class="aet-stepper__btn" data-aet-mas aria-label="{{ $sumar }}" hidden><i class="fa fa-plus" aria-hidden="true"></i></button>
        </div>
    @else
        <div class="aet-input-wrap">
            @isset($icon)<i class="fa fa-{{ $icon }} aet-input-wrap__icon" aria-hidden="true"></i>@endisset
            <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" value="{{ $valor }}" class="aet-input {{ isset($icon) ? 'aet-input--icon' : '' }}" placeholder="{{ $placeholder ?? '' }}"
                @isset($autocomplete) autocomplete="{{ $autocomplete }}" @endisset
                @if ($required) required @endif @if ($describe) aria-describedby="{{ $describe }}" @endif @if ($error) aria-invalid="true" @endif {!! $atributos !!}>
        </div>
    @endif

    @if ($hint)<p class="aet-hint" id="{{ $id }}-ayuda">{{ $hint }}</p>@endif
    <p class="aet-error" id="{{ $id }}-error" data-aet-error @unless ($error) hidden @endunless>
        <i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span data-aet-error-texto>{{ $error }}</span>
    </p>
</div>
