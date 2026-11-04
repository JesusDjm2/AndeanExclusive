{{--
    Tarjeta de un pasajero en el formulario de programas.
    @param int|string            $i        Índice en paxs[] ('__INDEX__' en la plantilla para nuevos).
    @param array                 $pax      Valores del pasajero (ver ProgramaController::paxData()).
    @param \App\Models\Pax|null  $paxModel Pasajero guardado (para su pasaporte escaneado y alertas).
    @param bool                  $standalone (opcional) Formulario de un solo pasajero (paxs/form): nombres planos y sin "Quitar".
--}}
@php
    $standalone ??= false;
    $fieldName = fn (string $name) => $standalone ? $name : "paxs[$i][$name]";
    $err = fn (string $name) => $errors->first($standalone ? $name : "paxs.$i.$name");
    $v = fn (string $name) => $pax[$name] ?? '';
    $alerta = $paxModel?->alertaPasaporte();
    $tieneSalud = filled($v('condiciones_medicas')) || filled($v('emergencia_nombre')) || filled($v('emergencia_telefono'))
        || $err('condiciones_medicas') || $err('emergencia_nombre') || $err('emergencia_telefono');
@endphp
<div class="prog-pax-card {{ $standalone ? 'prog-pax-card--standalone' : '' }}" data-pax-item>
    @if (! $standalone && ! empty($pax['id']))
        <input type="hidden" name="{{ $fieldName('id') }}" value="{{ $pax['id'] }}">
    @endif
    <div class="prog-pax-card__top">
        @unless ($standalone)
            <span class="prog-pax-num" data-pax-num></span>
        @endunless
        <h3 class="prog-pax-card__title">{{ $v('nombre') ?: 'Nuevo pasajero' }}</h3>
        @if ($paxModel?->actualizado_por_cliente_at)
            <span class="badge text-bg-info" title="Datos enviados por el cliente desde su enlace">
                <i class="fas fa-user-edit" aria-hidden="true"></i> Cliente · {{ $paxModel->actualizado_por_cliente_at->locale('es')->isoFormat('D MMM') }}
            </span>
        @endif
        @if ($paxModel?->edad !== null)
            <span class="badge text-bg-light">{{ $paxModel->edad }} años al viajar</span>
        @endif
        @unless ($standalone)
            <button type="button" class="prog-pax-remove" data-pax-remove>
                <i class="fas fa-times" aria-hidden="true"></i> Quitar
            </button>
        @endunless
    </div>
    <div class="row g-3">
        <div class="col-md-5">
            <label class="prog-label" for="pax-{{ $i }}-nombre">Nombre completo (como en el pasaporte) <span class="required">*</span></label>
            <input id="pax-{{ $i }}-nombre" type="text" name="{{ $fieldName('nombre') }}" value="{{ $v('nombre') }}"
                class="form-control form-control-sm {{ $err('nombre') ? 'is-invalid' : '' }}" maxlength="255" data-pax-nombre>
            @if ($err('nombre'))<div class="invalid-feedback">{{ $err('nombre') }}</div>@endif
        </div>
        <div class="col-6 col-md-3">
            <label class="prog-label" for="pax-{{ $i }}-nac">Fecha de nacimiento <span class="required">*</span></label>
            <input id="pax-{{ $i }}-nac" type="date" name="{{ $fieldName('fecha_nacimiento') }}" value="{{ $v('fecha_nacimiento') }}"
                class="form-control form-control-sm {{ $err('fecha_nacimiento') ? 'is-invalid' : '' }}" max="{{ now()->toDateString() }}" data-pax-nacimiento>
            @if ($err('fecha_nacimiento'))<div class="invalid-feedback">{{ $err('fecha_nacimiento') }}</div>@endif
        </div>
        <div class="col-6 col-md-2">
            <label class="prog-label" for="pax-{{ $i }}-sexo">Sexo</label>
            <select id="pax-{{ $i }}-sexo" name="{{ $fieldName('sexo') }}" class="form-select form-select-sm">
                <option value="">—</option>
                @foreach (\App\Models\Pax::SEXOS as $value => $label)
                    <option value="{{ $value }}" @selected($v('sexo') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="prog-label" for="pax-{{ $i }}-pais">País <span class="required">*</span></label>
            @include('admin.partials.select-pais', [
                'name' => $fieldName('pais'), 'id' => "pax-$i-pais", 'value' => $v('pais'),
                'class' => 'form-select-sm' . ($err('pais') ? ' is-invalid' : ''),
            ])
            @if ($err('pais'))<div class="invalid-feedback">{{ $err('pais') }}</div>@endif
        </div>

        <div class="col-6 col-md-3">
            <label class="prog-label" for="pax-{{ $i }}-pasaporte">N.º de pasaporte</label>
            <input id="pax-{{ $i }}-pasaporte" type="text" name="{{ $fieldName('pasaporte') }}" value="{{ auth()->user()?->can('pasaportes.ver') ? $v('pasaporte') : $paxModel?->pasaporte_oculto }}"
                class="form-control form-control-sm {{ $err('pasaporte') || $alerta === 'falta' ? 'is-invalid' : '' }}" maxlength="50" autocomplete="off">
            @if ($err('pasaporte'))<div class="invalid-feedback">{{ $err('pasaporte') }}</div>@endif
        </div>
        <div class="col-6 col-md-3">
            <label class="prog-label" for="pax-{{ $i }}-venc">Vence</label>
            <input id="pax-{{ $i }}-venc" type="date" name="{{ $fieldName('pasaporte_vencimiento') }}" value="{{ $v('pasaporte_vencimiento') }}"
                class="form-control form-control-sm {{ $err('pasaporte_vencimiento') || in_array($alerta, ['vencido', 'por_vencer'], true) ? 'is-invalid' : '' }}">
            @if (in_array($alerta, ['vencido', 'por_vencer'], true))
                <div class="invalid-feedback">{{ $alerta === 'vencido' ? 'Vencido para la fecha del viaje.' : 'Necesita 6 meses de vigencia al ingresar a Perú.' }}</div>
            @endif
        </div>
        <div class="col-md-6">
            <label class="prog-label" for="pax-{{ $i }}-archivo">Pasaporte escaneado <span class="text-muted fw-normal">(PDF o imagen, privado)</span></label>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <input id="pax-{{ $i }}-archivo" type="file" name="{{ $fieldName('pasaporte_archivo') }}" accept=".pdf,.jpg,.jpeg,.png,.webp"
                    class="form-control form-control-sm flex-grow-1 w-auto {{ $err('pasaporte_archivo') ? 'is-invalid' : '' }}">
                @if ($paxModel?->pasaporte_archivo && auth()->user()?->can('pasaportes.ver'))
                    <a href="{{ route('paxs.pasaporte', $paxModel) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-file-alt" aria-hidden="true"></i> Ver actual
                    </a>
                @endif
            </div>
            @if ($err('pasaporte_archivo'))<div class="invalid-feedback d-block">{{ $err('pasaporte_archivo') }}</div>@endif
        </div>

        <div class="col-md-4">
            <label class="prog-label" for="pax-{{ $i }}-dieta">Dieta</label>
            <select id="pax-{{ $i }}-dieta" name="{{ $fieldName('dieta') }}" class="form-select form-select-sm">
                <option value="">—</option>
                @foreach (\App\Models\Pax::DIETAS as $value => $label)
                    <option value="{{ $value }}" @selected($v('dieta') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-8">
            <label class="prog-label" for="pax-{{ $i }}-alergias">Alergias</label>
            <input id="pax-{{ $i }}-alergias" type="text" name="{{ $fieldName('alergias') }}" value="{{ $v('alergias') }}" maxlength="255"
                class="form-control form-control-sm" placeholder="Ej. maní, mariscos, penicilina">
        </div>

        <div class="col-12">
            <details class="pf-more" @if ($tieneSalud) open @endif>
                <summary>Salud y contacto de emergencia</summary>
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="prog-label" for="pax-{{ $i }}-medico">Condiciones médicas</label>
                        <textarea id="pax-{{ $i }}-medico" name="{{ $fieldName('condiciones_medicas') }}" rows="2" maxlength="1000"
                            class="form-control form-control-sm" placeholder="Hipertensión, movilidad reducida, medicación habitual…">{{ $v('condiciones_medicas') }}</textarea>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="prog-label" for="pax-{{ $i }}-em-nombre">Contacto de emergencia</label>
                        <input id="pax-{{ $i }}-em-nombre" type="text" name="{{ $fieldName('emergencia_nombre') }}" value="{{ $v('emergencia_nombre') }}"
                            maxlength="255" class="form-control form-control-sm" placeholder="Nombre y parentesco">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="prog-label" for="pax-{{ $i }}-em-tel">Teléfono</label>
                        <input id="pax-{{ $i }}-em-tel" type="tel" name="{{ $fieldName('emergencia_telefono') }}" value="{{ $v('emergencia_telefono') }}"
                            maxlength="40" class="form-control form-control-sm">
                    </div>
                </div>
            </details>
        </div>
    </div>
</div>
