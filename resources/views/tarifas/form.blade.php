{{-- Alta y edición de una tarifa (TarifaController). Muestra los campos según el tipo: resources/js/tarifas.js. --}}
@extends('layouts.app')
@section('titulo', $tarifa->exists ? 'Editar tarifa' : 'Nueva tarifa')

@php
    $tipo = old('tipo', $tarifa->proveedor_id ? 'proveedor' : 'habitacion');
    $input = fn (string $campo, $defecto = null) => old($campo, $defecto ?? $tarifa->{$campo});
    $esEspecial = old('es_especial', $tarifa->exists ? (bool)$tarifa->es_especial : false);
    $esFija = old('es_fija', $tarifa->exists ? (bool)$tarifa->es_fija : (! $esEspecial && ! $tarifa->desde && ! $tarifa->hasta));
    $urlVolver = ($retorno === 'proveedor' && $tarifa->proveedor_id) ? route('proveedors.show', $tarifa->proveedor_id) : route('tarifas.index');
@endphp

@section('contenido')
    <div class="ae-admin-page-header">
        <a href="{{ $urlVolver }}" class="pf-header__back"><i class="fas fa-arrow-left" aria-hidden="true"></i> {{ ($retorno === 'proveedor' && $tarifa->proveedor_id) ? 'Volver al proveedor' : 'Tarifario' }}</a>
        <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-tags"></i> {{ $tarifa->exists ? 'Editar tarifa' : 'Nueva tarifa' }}</h1>
    </div>

    <form action="{{ $tarifa->exists ? route('tarifas.update', $tarifa) : route('tarifas.store') }}" method="POST" class="prog-section" data-tarifa-form>
        @csrf
        @if ($tarifa->exists)
            @method('PUT')
        @endif
        @if ($retorno)
            <input type="hidden" name="retorno" value="{{ $retorno }}">
        @endif

        <div class="prog-section__body">
            <div class="pf-segmented mb-3" role="radiogroup" aria-label="Tipo de tarifa">
                <label><input type="radio" name="tipo" value="habitacion" @checked($tipo === 'habitacion') data-tarifa-tipo> <span>Habitación de hotel</span></label>
                <label><input type="radio" name="tipo" value="proveedor" @checked($tipo === 'proveedor') data-tarifa-tipo> <span>Servicio de un proveedor</span></label>
            </div>

            <div class="row g-3">
                <div class="col-md-8" data-tarifa-panel="habitacion" @if ($tipo !== 'habitacion') hidden @endif>
                    <label for="t-hab" class="prog-label">Hotel y habitación <span class="required">*</span></label>
                    <select id="t-hab" name="habitacion_id" class="form-select @error('habitacion_id') is-invalid @enderror">
                        <option value="">Elige…</option>
                        @foreach ($hoteles as $hotel)
                            @continue($hotel->habitaciones->isEmpty())
                            <optgroup label="{{ $hotel->nombre }}">
                                @foreach ($hotel->habitaciones as $habitacion)
                                    <option value="{{ $habitacion->id }}" @selected((int) $input('habitacion_id') === $habitacion->id)>{{ $hotel->nombre }} · {{ $habitacion->tipo }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('habitacion_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-5" data-tarifa-panel="proveedor" @if ($tipo !== 'proveedor') hidden @endif>
                    <label for="t-prov" class="prog-label">Proveedor <span class="required">*</span></label>
                    <select id="t-prov" name="proveedor_id" class="form-select @error('proveedor_id') is-invalid @enderror">
                        <option value="">Elige…</option>
                        @foreach ($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}" @selected((int) $input('proveedor_id') === $proveedor->id)>{{ $proveedor->nombre }}</option>
                        @endforeach
                    </select>
                    @error('proveedor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-7" data-tarifa-panel="proveedor" @if ($tipo !== 'proveedor') hidden @endif>
                    <label for="t-con" class="prog-label">Servicio / Concepto <span class="required">*</span></label>
                    <input id="t-con" type="text" name="concepto" value="{{ $input('concepto') }}" maxlength="255" placeholder="Ej. Traslado aeropuerto → hotel, Día completo, Almuerzo buffet…" class="form-control @error('concepto') is-invalid @enderror">
                    @error('concepto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Vigencia: Fija vs Temporada vs Fecha Especial --}}
                <div class="col-12">
                    <div class="p-3 bg-light rounded border">
                        <label class="prog-label mb-2"><i class="fas fa-calendar-alt text-primary me-1"></i> Vigencia y Temporalidad de la Tarifa</label>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="form-check p-2 bg-white rounded border h-100">
                                    <input class="form-check-input ms-1" type="radio" name="modalidad_vigencia" id="vigencia-fija" value="fija" @checked($esFija && ! $esEspecial) data-vigencia-radio>
                                    <label class="form-check-label fw-bold text-dark cursor-pointer ms-2" for="vigencia-fija">
                                        <i class="fas fa-infinity text-primary me-1"></i> Tarifa Fija Estándar
                                        <small class="d-block text-muted fw-normal">Aplica todo el año sin restricción de fechas.</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check p-2 bg-white rounded border h-100">
                                    <input class="form-check-input ms-1" type="radio" name="modalidad_vigencia" id="vigencia-temporada" value="temporada" @checked(! $esFija && ! $esEspecial) data-vigencia-radio>
                                    <label class="form-check-label fw-bold text-dark cursor-pointer ms-2" for="vigencia-temporada">
                                        <i class="fas fa-calendar-week text-info me-1"></i> Tarifa de Temporada
                                        <small class="d-block text-muted fw-normal">Rango regular de fechas (alta/baja).</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check p-2 bg-white rounded border border-warning h-100">
                                    <input class="form-check-input ms-1" type="radio" name="modalidad_vigencia" id="vigencia-especial" value="especial" @checked($esEspecial) data-vigencia-radio>
                                    <label class="form-check-label fw-bold text-dark cursor-pointer ms-2" for="vigencia-especial">
                                        <i class="fas fa-star text-warning me-1"></i> Fecha Especial / Festividad
                                        <small class="d-block text-muted fw-normal">Hoteles y trenes en fechas pico (Inti Raymi, etc.).</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="es_fija" id="input-es-fija" value="{{ $esFija && ! $esEspecial ? '1' : '0' }}">
                        <input type="hidden" name="es_especial" id="input-es-especial" value="{{ $esEspecial ? '1' : '0' }}">

                        <div class="row g-2 mt-2" data-tarifa-especial-box @unless ($esEspecial) style="display:none;" @endunless>
                            <div class="col-md-7">
                                <label for="t-nombre-especial" class="prog-label">Nombre del Evento / Festividad / Temporada Especial <span class="required">*</span></label>
                                <input id="t-nombre-especial" type="text" name="nombre_especial" value="{{ $input('nombre_especial') }}"
                                    list="lista-festividades" placeholder="Ej. Inti Raymi, Semana Santa, Año Nuevo..." class="form-control form-control-sm @error('nombre_especial') is-invalid @enderror">
                                <datalist id="lista-festividades">
                                    <option value="Inti Raymi">
                                    <option value="Semana Santa">
                                    <option value="Fiestas Patrias">
                                    <option value="Navidad y Año Nuevo">
                                    <option value="Virgen de la Candelaria">
                                    <option value="Feriado Nacional / Puente Festivo">
                                </datalist>
                                @error('nombre_especial')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-5 d-flex align-items-center">
                                <div class="alert alert-warning py-1 px-2 mb-0 small text-dark border-0">
                                    <i class="fas fa-star text-warning me-1"></i> Esta tarifa tendrá <strong>prioridad 1</strong> al cotizar en esas fechas.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3" data-tarifa-fechas @if ($esFija && ! $esEspecial) style="display:none;" @endif>
                    <label for="t-desde" class="prog-label">Fecha Desde</label>
                    <input id="t-desde" type="date" name="desde" value="{{ old('desde', $tarifa->desde?->toDateString()) }}" class="form-control @error('desde') is-invalid @enderror" data-tarifa-desde>
                    @error('desde')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-md-3" data-tarifa-fechas @if ($esFija && ! $esEspecial) style="display:none;" @endif>
                    <label for="t-hasta" class="prog-label">Fecha Hasta</label>
                    <input id="t-hasta" type="date" name="hasta" value="{{ old('hasta', $tarifa->hasta?->toDateString()) }}" class="form-control @error('hasta') is-invalid @enderror" data-tarifa-hasta>
                    @error('hasta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-sm-4 col-md-2">
                    <label for="t-moneda" class="prog-label">Moneda</label>
                    <select id="t-moneda" name="moneda" class="form-select">
                        @foreach (\App\Models\Servicio::MONEDAS as $clave => $simbolo)
                            <option value="{{ $clave }}" @selected($input('moneda') === $clave)>{{ $clave }} ({{ $simbolo }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4 col-md-2">
                    <label for="t-precio" class="prog-label">Precio <span class="required">*</span></label>
                    <input id="t-precio" type="number" name="precio" value="{{ $input('precio') }}" min="0" step="0.01" required class="form-control @error('precio') is-invalid @enderror">
                    @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4 col-md-2" data-tarifa-panel="proveedor" @if ($tipo !== 'proveedor') hidden @endif>
                    <label for="t-por" class="prog-label">Modalidad de cobro</label>
                    <select id="t-por" name="por" class="form-select">
                        @foreach (['grupo', 'persona'] as $clave)
                            <option value="{{ $clave }}" @selected($input('por') === $clave)>{{ ucfirst(\App\Models\Tarifa::POR[$clave]) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Con el tipo, el itinerario aplica sola la tarifa correcta cuando el proveedor tiene varias. --}}
                <div class="col-md-4" data-tarifa-panel="proveedor" @if ($tipo !== 'proveedor') hidden @endif>
                    <label for="t-tipo-servicio" class="prog-label">Tipo de servicio</label>
                    <select id="t-tipo-servicio" name="tipo_servicio" class="form-select @error('tipo_servicio') is-invalid @enderror">
                        <option value="">Sin especificar</option>
                        @foreach (\App\Models\Servicio::TIPOS as $clave => [$etiqueta])
                            @continue($clave === 'alojamiento')
                            <option value="{{ $clave }}" @selected($input('tipo_servicio') === $clave)>{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                    @error('tipo_servicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8" data-tarifa-panel="proveedor" @if ($tipo !== 'proveedor') hidden @endif>
                    <label for="t-unidad" class="prog-label">Detalle de unidad / servicio (opcional)</label>
                    <input id="t-unidad" type="text" name="unidad_detalle" value="{{ $input('unidad_detalle') }}" maxlength="100" placeholder="Ej. Van H1 (1-6 pax), Bilingüe, Solo ida…" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="t-notas" class="prog-label">Notas internas</label>
                    <input id="t-notas" type="text" name="notas" value="{{ $input('notas') }}" maxlength="255" placeholder="Incluye refrigerio, aplica con 48h de anticipación…" class="form-control">
                </div>
            </div>

            <p class="prog-input-hint mt-3">Las tarifas fijas se usan por defecto todo el año al cotizar. Si defines temporadas, el sistema priorizará la tarifa específica para la fecha del viaje.</p>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save" aria-hidden="true"></i> Guardar tarifa</button>
                <a href="{{ $urlVolver }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div>
    </form>
@endsection
