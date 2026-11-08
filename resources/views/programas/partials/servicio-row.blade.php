{{--
    Un servicio del itinerario en el formulario de programas (pestaña Itinerario).
    @param int|string $i       Índice en servicios[] ('__INDEX__' en la plantilla).
    @param array      $s       Valores (ver $servicioRows en programas/form).
    @param \Illuminate\Support\Collection $proveedoresPorCategoria
--}}
@php
    $name = fn (string $campo) => "servicios[$i][$campo]";
    $err = fn (string $campo) => $errors->first("servicios.$i.$campo");
    $v = fn (string $campo) => $s[$campo] ?? '';
    $estado = $v('estado') ?: 'pendiente';
    // Proveedor, estado, confirmación y costo real los gestiona Reservas (el servidor también lo impone).
    $bloqueado = ! auth()->user()?->can('servicios.gestionar');
    $bloqueadoMoneda = $bloqueado && filled($v('costo'));
    // Cerrado por defecto (el itinerario se vuelve largo); se abre si hay errores dentro.
    $abierto = (bool) ($err('fecha') || $err('punto_encuentro') || $err('descripcion'));
    $esEstadia = ! empty($s['de_estadia']);
    $resumen = collect([
        filled($v('punto_encuentro')) ? 'punto: ' . \Illuminate\Support\Str::limit($v('punto_encuentro'), 25) : null,
        filled($v('descripcion')) ? 'descripción cliente' : null,
        filled($v('notas')) ? 'notas proveedor' : null,
        empty($s['visible_cliente'] ?? true) ? 'oculto al cliente' : null,
    ])->filter();
@endphp
<div class="svc-row" data-svc-item @if (! empty($s['id'])) id="servicio-{{ $s['id'] }}" @endif>
    @if (! empty($s['id']))
        <input type="hidden" name="{{ $name('id') }}" value="{{ $s['id'] }}">
    @endif

    {{-- Línea 1: Identificación y Proveedor --}}
    <div class="svc-row__grid">
        <div class="svc-row__hora">
            <label class="prog-label" for="svc-{{ $i }}-hora">Hora</label>
            <input id="svc-{{ $i }}-hora" type="time" name="{{ $name('hora') }}" value="{{ $v('hora') }}"
                class="form-control form-control-sm {{ $err('hora') ? 'is-invalid' : '' }}">
        </div>
        <div class="svc-row__tipo">
            <label class="prog-label" for="svc-{{ $i }}-tipo">Tipo</label>
            <select id="svc-{{ $i }}-tipo" name="{{ $name('tipo') }}" class="form-select form-select-sm" data-svc-tipo>
                @foreach (\App\Models\Servicio::TIPOS as $clave => [$etiqueta])
                    <option value="{{ $clave }}" @selected(($v('tipo') ?: 'traslado') === $clave)>{{ $etiqueta }}</option>
                @endforeach
            </select>
        </div>
        <div class="svc-row__proveedor">
            <label class="prog-label" for="svc-{{ $i }}-prov">Proveedor</label>
            @if ($bloqueado)<input type="hidden" name="{{ $name('proveedor_id') }}" value="{{ $v('proveedor_id') }}">@endif
            <select id="svc-{{ $i }}-prov" @disabled($bloqueado) name="{{ $name('proveedor_id') }}" class="form-select form-select-sm" data-svc-proveedor-select>
                <option value="">— Sin asignar —</option>
                @foreach ($proveedoresPorCategoria as $categoria => $proveedores)
                    <optgroup label="{{ $categoria }}" data-tipos="{{ implode(' ', \App\Models\Servicio::tiposDeRubro($categoria)) }}">
                        @foreach ($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}" @selected((int) $v('proveedor_id') === $proveedor->id)>{{ $proveedor->nombre }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            <div class="svc-quick-rates mt-1" data-svc-quick-rates="true"></div>
        </div>
        <div class="svc-row__titulo">
            <label class="prog-label" for="svc-{{ $i }}-titulo">Servicio / Experiencia</label>
            <input id="svc-{{ $i }}-titulo" type="text" name="{{ $name('titulo') }}" value="{{ $v('titulo') }}" maxlength="255"
                class="form-control form-control-sm {{ $err('titulo') ? 'is-invalid' : '' }}" placeholder="Ej. Traslado aeropuerto → hotel (opcional)" data-svc-titulo>
            @if ($err('titulo'))<div class="invalid-feedback">{{ $err('titulo') }}</div>@endif
        </div>
        <div class="svc-row__actions">
            @if (! empty($s['id']))
                <a href="{{ route('servicios.voucher', $s['id']) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary"
                    title="Orden de servicio (PDF)" aria-label="Orden de servicio en PDF"><i class="fas fa-file-invoice" aria-hidden="true"></i></a>
            @endif
            @if ($esEstadia)
                <span class="svc-row__de-estadia" title="Lo generó el alojamiento: su fecha y su costo se cambian en la pestaña Alojamiento" aria-label="Generado por el alojamiento" role="img"><i class="fas fa-bed" aria-hidden="true"></i></span>
            @else
                <button type="button" class="btn btn-sm btn-outline-danger" data-svc-remove title="Quitar servicio" aria-label="Quitar servicio">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            @endif
        </div>
    </div>

    {{-- Línea 2: Precios de Operaciones & Cotización (Visible directamente para cotizar sin desplegar) --}}
    @if ($esEstadia)
        @php
            $esReservadoCliente = empty($v('costo')) && empty($v('costo_estimado'));
        @endphp
        <div class="svc-row__estadia-bar p-2 rounded border bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                @if ($esReservadoCliente)
                    <span class="badge bg-info-subtle text-info border">
                        <i class="fas fa-user-check me-1"></i> Hotel reservado por el cliente
                    </span>
                    <strong class="text-dark">{{ $v('titulo') ?: 'Estadía de Hotel' }}</strong>
                    <span class="small text-muted">· Costo para la agencia: <strong>US$ 0.00</strong> (lo abona el pasajero directamente)</span>
                @else
                    <span class="badge bg-success-subtle text-success border">
                        <i class="fas fa-bed me-1"></i> Hotel reservado por la agencia
                    </span>
                    <strong class="text-dark">{{ $v('titulo') ?: 'Estadía de Hotel' }}</strong>
                    <span class="small text-muted">· Costo noche: <strong class="text-success">{{ \App\Models\Servicio::formatoMonto((float) $v('costo'), $v('moneda')) }}</strong> (definido en Alojamiento)</span>
                @endif
            </div>
            <div>
                <button type="button" class="btn btn-xs btn-outline-secondary" onclick="document.querySelector('#pf-tab-alojamiento')?.click();">
                    <i class="fas fa-bed me-1"></i> Modificar en Alojamiento
                </button>
            </div>
        </div>
    @else
        <div class="svc-row__finance-grid">
            {{-- La moneda va pegada al precio: por defecto la del programa, o la de la tarifa del proveedor si se aplica una. --}}
            @php $monedaFila = $v('moneda') ?: ($programa->moneda ?? 'USD'); @endphp
            <div class="svc-fin__col svc-fin__col--estimado">
                <label class="prog-label" for="svc-{{ $i }}-estimado">
                    <i class="fas fa-tag text-warning me-1" aria-hidden="true"></i>Precio cotizado
                </label>
                <div class="input-group input-group-sm">
                    {{-- No se pregunta: es la del programa o la de la tarifa aplicada. Un clic la alterna solo en casos excepcionales. --}}
                    <input type="hidden" id="svc-{{ $i }}-moneda" name="{{ $name('moneda') }}" value="{{ $monedaFila }}"
                        data-svc-moneda data-moneda-auto="{{ filled($v('moneda')) ? '0' : '1' }}">
                    <button type="button" class="input-group-text svc-fin__moneda" @disabled($bloqueadoMoneda) data-svc-moneda-toggle
                        title="Moneda del precio (la del programa o la de la tarifa). Clic para cambiarla" aria-label="Moneda del precio: {{ $monedaFila }}. Cambiar">{{ \App\Models\Servicio::MONEDAS[$monedaFila] ?? 'US$' }}</button>
                    <input id="svc-{{ $i }}-estimado" type="number" name="{{ $name('costo_estimado') }}" value="{{ $v('costo_estimado') }}" step="0.01" min="0"
                        class="form-control {{ $err('costo_estimado') ? 'is-invalid' : '' }}" placeholder="0.00" data-svc-estimado>
                </div>
                <small class="svc-fin__origen" data-svc-origen hidden></small>
            </div>
            <div class="svc-fin__col svc-fin__col--por">
                <label class="prog-label" for="svc-{{ $i }}-por">Se cobra</label>
                @if ($bloqueadoMoneda)<input type="hidden" name="{{ $name('costo_por') }}" value="{{ $v('costo_por') ?: 'grupo' }}">@endif
                <select id="svc-{{ $i }}-por" @disabled($bloqueadoMoneda) name="{{ $name('costo_por') }}" class="form-select form-select-sm" data-svc-por>
                    @foreach (\App\Models\Servicio::COSTO_POR as $clave => $etiqueta)
                        <option value="{{ $clave }}" @selected(($v('costo_por') ?: 'grupo') === $clave)>{{ ucfirst($etiqueta) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="svc-fin__col svc-fin__col--estado">
                <label class="prog-label" for="svc-{{ $i }}-estado">Estado reserva</label>
                @if ($bloqueado)<input type="hidden" name="{{ $name('estado') }}" value="{{ $estado }}">@endif
                <select id="svc-{{ $i }}-estado" @disabled($bloqueado) name="{{ $name('estado') }}" class="form-select form-select-sm svc-estado svc-estado--{{ $estado }}" data-svc-estado>
                    @foreach (\App\Models\Servicio::ESTADOS as $clave => $etiqueta)
                        <option value="{{ $clave }}" @selected($estado === $clave)>{{ $etiqueta }}</option>
                    @endforeach
                </select>
            </div>
            <div class="svc-fin__col svc-fin__col--costo">
                <label class="prog-label" for="svc-{{ $i }}-costo">Costo real prov.</label>
                @if ($bloqueado)<input type="hidden" name="{{ $name('costo') }}" value="{{ $v('costo') }}">@endif
                <input id="svc-{{ $i }}-costo" type="number" @disabled($bloqueado) name="{{ $name('costo') }}" value="{{ $v('costo') }}" step="0.01" min="0"
                    class="form-control form-control-sm {{ $err('costo') ? 'is-invalid' : '' }}" placeholder="0.00" data-svc-costo>
            </div>
            <div class="svc-fin__col svc-fin__col--conf">
                <label class="prog-label" for="svc-{{ $i }}-conf">N.º confirmación</label>
                @if ($bloqueado)<input type="hidden" name="{{ $name('confirmacion') }}" value="{{ $v('confirmacion') }}">@endif
                <input id="svc-{{ $i }}-conf" type="text" @disabled($bloqueado) name="{{ $name('confirmacion') }}" value="{{ $v('confirmacion') }}" maxlength="80"
                    class="form-control form-control-sm" autocomplete="off" placeholder="Ej. CONF-102">
            </div>
        </div>

        @if (filled($v('costo')) && filled($v('costo_estimado')) && (float) $v('costo') > (float) $v('costo_estimado') + 0.009)
            <div class="mt-1">
                <span class="svc-badge svc-badge--pendiente">
                    <i class="fas fa-arrow-up" aria-hidden="true"></i> Costo real {{ \App\Models\Servicio::formatoMonto((float) $v('costo') - (float) $v('costo_estimado'), $v('moneda') ?: 'USD') }} sobre lo cotizado
                </span>
            </div>
        @endif
        @if (! empty($s['pagado_at']))
            <div class="mt-1">
                <span class="svc-badge svc-badge--confirmado">
                    <i class="fas fa-check" aria-hidden="true"></i> Pagado al proveedor el {{ \Carbon\Carbon::parse($s['pagado_at'])->format('d/m/Y') }}
                </span>
            </div>
        @elseif (($s['situacion_pago'] ?? null) === 'parcial')
            <div class="mt-1">
                <span class="svc-badge svc-badge--solicitado">
                    Pago parcial · saldo {{ \App\Models\Servicio::formatoMonto((float) $s['saldo'], $v('moneda') ?: 'USD') }}
                </span>
            </div>
        @endif
    @endif

    {{-- Línea 3: Detalles secundarios (punto de encuentro, textos para el cliente y notas internas) --}}
    <details class="pf-more svc-row__more" @if ($abierto) open @endif>
        <summary>Detalles @if ($resumen->isNotEmpty())<span class="svc-row__summary">· {{ $resumen->implode(', ') }}</span>@else<span class="svc-row__summary">· punto de encuentro, texto para el cliente y notas</span>@endif</summary>
        <div class="row g-2 mt-2">
            <div class="col-6 col-md-3">
                <label class="prog-label" for="svc-{{ $i }}-fecha">Fecha</label>
                <input id="svc-{{ $i }}-fecha" type="date" name="{{ $name('fecha') }}" value="{{ $v('fecha') }}"
                    class="form-control form-control-sm {{ $err('fecha') ? 'is-invalid' : '' }}" data-svc-fecha>
                @if ($err('fecha'))<div class="invalid-feedback">{{ $err('fecha') }}</div>@endif
            </div>
            <div class="col-6 col-md-5">
                <label class="prog-label" for="svc-{{ $i }}-punto">Punto de encuentro / recojo</label>
                <input id="svc-{{ $i }}-punto" type="text" name="{{ $name('punto_encuentro') }}" value="{{ $v('punto_encuentro') }}" maxlength="255"
                    class="form-control form-control-sm" placeholder="Lobby del hotel, puerta 3 del aeropuerto…">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <label class="form-check mb-1">
                    <input type="checkbox" class="form-check-input" name="{{ $name('visible_cliente') }}" value="1" @checked($s['visible_cliente'] ?? true)>
                    <span class="form-check-label small">Mostrar en el itinerario del cliente</span>
                </label>
            </div>
            <div class="col-md-6">
                <label class="prog-label" for="svc-{{ $i }}-desc">Descripción para el cliente (aparece en web y PDF)</label>
                <textarea id="svc-{{ $i }}-desc" name="{{ $name('descripcion') }}" rows="2" maxlength="2000"
                    class="form-control form-control-sm" placeholder="Detalles de la experiencia para el viajero">{{ $v('descripcion') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="prog-label" for="svc-{{ $i }}-notas">Notas internas / para el proveedor</label>
                <textarea id="svc-{{ $i }}-notas" name="{{ $name('notas') }}" rows="2" maxlength="2000"
                    class="form-control form-control-sm" placeholder="Salen en la orden de servicio / voucher, no en el itinerario del cliente">{{ $v('notas') }}</textarea>
            </div>
        </div>
    </details>
</div>
