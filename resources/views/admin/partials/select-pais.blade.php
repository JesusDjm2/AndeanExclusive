{{--
    Selector de país (ISO alfa-2) con los países frecuentes del negocio primero.
    @param string      $name
    @param string      $id
    @param string|null $value  Código ISO seleccionado.
    @param string      $class  Clases extra (p. ej. form-select-sm, is-invalid).
--}}
@php
    $paises = \App\Support\Paises::all();
    $frecuentes = collect(\App\Support\Paises::FRECUENTES)->mapWithKeys(fn ($c) => [$c => $paises[$c]])->sortBy(fn ($n) => $n);
@endphp
<select name="{{ $name }}" id="{{ $id }}" class="form-select {{ $class ?? '' }}">
    <option value="">— Seleccionar país —</option>
    <optgroup label="Frecuentes">
        @foreach ($frecuentes as $codigo => $nombre)
            <option value="{{ $codigo }}" @selected($value === $codigo)>{{ $nombre }}</option>
        @endforeach
    </optgroup>
    <optgroup label="Todos los países">
        @foreach ($paises as $codigo => $nombre)
            <option value="{{ $codigo }}" @selected($value === $codigo && ! $frecuentes->has($codigo))>{{ $nombre }}</option>
        @endforeach
    </optgroup>
</select>
