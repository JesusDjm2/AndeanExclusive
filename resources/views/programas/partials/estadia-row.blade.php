{{--
    Una fila de alojamiento en el formulario de programas: tipo de habitación, cantidad y fechas.
    @param int|string $i  Índice en estadias[] ('__INDEX__' en la plantilla).
    @param array      $e  Valores: id, habitacion_id, cantidad, costo_noche, moneda, ingreso, salida.
    @param \Illuminate\Support\Collection $hoteles  Con sus habitaciones.
--}}
@php
    $name = fn (string $campo) => "estadias[$i][$campo]";
    $err = fn (string $campo) => $errors->first("estadias.$i.$campo");
@endphp
<div class="estadia-row" data-estadia-item>
    @if (! empty($e['id']))
        <input type="hidden" name="{{ $name('id') }}" value="{{ $e['id'] }}">
    @endif
    <div class="estadia-row__habitacion">
        <label class="prog-label" for="est-{{ $i }}-hab">Hotel y habitación <span class="required">*</span></label>
        <select id="est-{{ $i }}-hab" name="{{ $name('habitacion_id') }}" class="form-select form-select-sm {{ $err('habitacion_id') ? 'is-invalid' : '' }}" data-estadia-habitacion>
            <option value="">Elige…</option>
            @foreach ($hoteles as $hotel)
                @continue($hotel->habitaciones->isEmpty())
                <optgroup label="{{ $hotel->nombre }}">
                    @foreach ($hotel->habitaciones as $habitacion)
                        <option value="{{ $habitacion->id }}" data-capacidad="{{ (int) $habitacion->capacidad }}" @selected((int) ($e['habitacion_id'] ?? 0) === $habitacion->id)>{{ $hotel->nombre }} · {{ $habitacion->tipo }} ({{ (int) $habitacion->capacidad }} {{ (int) $habitacion->capacidad === 1 ? 'persona' : 'personas' }})</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        @if ($err('habitacion_id'))<div class="invalid-feedback">{{ $err('habitacion_id') }}</div>@endif
    </div>
    <div class="estadia-row__cantidad">
        <label class="prog-label" for="est-{{ $i }}-cant">Cantidad <span class="required">*</span></label>
        <input id="est-{{ $i }}-cant" type="number" name="{{ $name('cantidad') }}" value="{{ $e['cantidad'] ?? 1 }}" min="1" max="50" step="1"
            class="form-control form-control-sm {{ $err('cantidad') ? 'is-invalid' : '' }}" data-estadia-cantidad>
        @if ($err('cantidad'))<div class="invalid-feedback">{{ $err('cantidad') }}</div>@endif
    </div>
    <div class="estadia-row__fecha">
        <label class="prog-label" for="est-{{ $i }}-in">Ingreso</label>
        <input id="est-{{ $i }}-in" type="date" name="{{ $name('ingreso') }}" value="{{ $e['ingreso'] ?? '' }}"
            class="form-control form-control-sm {{ $err('ingreso') ? 'is-invalid' : '' }}" data-estadia-ingreso>
        @if ($err('ingreso'))<div class="invalid-feedback">{{ $err('ingreso') }}</div>@endif
    </div>
    <div class="estadia-row__fecha">
        <label class="prog-label" for="est-{{ $i }}-out">Salida</label>
        <input id="est-{{ $i }}-out" type="date" name="{{ $name('salida') }}" value="{{ $e['salida'] ?? '' }}"
            class="form-control form-control-sm {{ $err('salida') ? 'is-invalid' : '' }}" data-estadia-salida>
        @if ($err('salida'))<div class="invalid-feedback">{{ $err('salida') }}</div>@endif
    </div>
    @can('cotizacion.ver')
    <div class="estadia-row__costo">
        <label class="prog-label" for="est-{{ $i }}-costo">Costo por noche</label>
        <div class="input-group input-group-sm">
            <select name="{{ $name('moneda') }}" class="form-select estadia-row__moneda" aria-label="Moneda" data-estadia-moneda>
                @foreach (\App\Models\Servicio::MONEDAS as $clave => $simbolo)
                    <option value="{{ $clave }}" @selected(($e['moneda'] ?? 'USD') === $clave)>{{ $simbolo }}</option>
                @endforeach
            </select>
            <input id="est-{{ $i }}-costo" type="number" name="{{ $name('costo_noche') }}" value="{{ $e['costo_noche'] ?? '' }}" min="0" step="0.01" placeholder="Por habitación"
                class="form-control {{ $err('costo_noche') ? 'is-invalid' : '' }}" data-estadia-costo>
        </div>
        @if ($err('costo_noche'))<div class="invalid-feedback d-block">{{ $err('costo_noche') }}</div>@endif
    </div>
    @endcan
    <span class="estadia-row__noches" data-estadia-noches></span>
    <button type="button" class="btn btn-sm btn-outline-danger estadia-row__quitar" data-estadia-remove title="Quitar habitación" aria-label="Quitar habitación">
        <i class="fas fa-times" aria-hidden="true"></i>
    </button>
</div>
