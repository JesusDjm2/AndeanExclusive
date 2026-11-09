{{--
    Botones de gestión de un servicio con su proveedor: asignar proveedor, estado/confirmación, pago, historial y orden de servicio.
    Abren los modales de servicios/partials/modales (resources/js/servicio-acciones.js).
    @param \App\Models\Servicio $servicio  Con programa.paxs, proveedor y pagos cargados.
--}}
@php
    $saldo = $servicio->saldo_proveedor;
    $puedePagar = $servicio->estado !== 'cancelado' && (float) $servicio->costo > 0 && $saldo > 0;
    $nombre = $servicio->titulo . ' · ' . $servicio->programa->codigo . ' · ' . $servicio->fecha->format('d/m');
    $simbolo = \App\Models\Servicio::MONEDAS[$servicio->moneda] ?? '$';
    $porPersona = $servicio->costo_por === 'persona' ? ' por persona' : '';
    $sinProveedor = ! $servicio->proveedor_id;
@endphp
<div class="svc-actions">
    @if ($servicio->pago_solicitado_at && ! $servicio->pagado_at && $servicio->estado !== 'cancelado')
        <span class="svc-badge svc-badge--pago-solicitado" title="Solicitado el {{ $servicio->pago_solicitado_at->format('d/m/Y H:i') }}"><i class="fas fa-paper-plane" aria-hidden="true"></i> Pago solicitado</span>
    @endif
    @can('servicios.gestionar')
    @if ($servicio->estado !== 'cancelado')
        <button type="button" class="btn btn-sm {{ $sinProveedor ? 'btn-primary' : 'btn-light' }}"
            data-svc-accion="asignar" data-url="{{ route('servicios.asignar', $servicio) }}" data-nombre="{{ $nombre }}" data-fecha="{{ $servicio->fecha->toDateString() }}"
            data-proveedor="{{ $servicio->proveedor_id }}" data-estado="{{ $servicio->estado }}" data-simbolo="{{ $simbolo }}" data-por="{{ $porPersona }}"
            data-costo="{{ $servicio->costo !== null ? number_format((float) $servicio->costo, 2, '.', '') : '' }}"
            data-estimado="{{ $servicio->costo_estimado !== null ? number_format((float) $servicio->costo_estimado, 2, '.', '') : '' }}"
            @unless ($sinProveedor) title="Cambiar proveedor" aria-label="Cambiar proveedor de {{ $servicio->titulo }}" @endunless>
            <i class="fas {{ $sinProveedor ? 'fa-user-plus' : 'fa-people-arrows' }}" aria-hidden="true"></i>
            @if ($sinProveedor)<span>Asignar proveedor</span>@endif
        </button>
    @endif
    @unless ($sinProveedor)
        <button type="button" class="btn btn-sm {{ in_array($servicio->estado, \App\Models\Servicio::ABIERTOS, true) ? 'btn-primary' : 'btn-outline-secondary' }}"
            data-svc-accion="estado" data-url="{{ route('servicios.estado', $servicio) }}" data-nombre="{{ $nombre }}"
            data-estado="{{ $servicio->estado }}" data-confirmacion="{{ $servicio->confirmacion }}">
            <i class="fas {{ $servicio->estado === 'confirmado' ? 'fa-exchange-alt' : 'fa-check' }}" aria-hidden="true"></i>
            <span>{{ $servicio->estado === 'pendiente' ? 'Solicitar / confirmar' : ($servicio->estado === 'solicitado' ? 'Confirmar' : 'Estado') }}</span>
        </button>
    @endunless
    @endcan
    @if ($puedePagar)
    @can('pagos.registrar')
        <button type="button" class="btn btn-sm btn-outline-primary" data-svc-accion="pago" data-url="{{ route('servicios.pagar', $servicio) }}"
            data-nombre="{{ $nombre }}" data-moneda="{{ $servicio->moneda }}" data-simbolo="{{ $simbolo }}"
            data-importe="{{ number_format($servicio->importe, 2, '.', '') }}" data-pagado="{{ number_format($servicio->pagado_proveedor, 2, '.', '') }}"
            data-saldo="{{ number_format($saldo, 2, '.', '') }}">
            <i class="fas fa-hand-holding-usd" aria-hidden="true"></i> <span>Pagar</span>
        </button>
    @elsecan('pagos.solicitar')
        @unless ($sinProveedor)
            <form action="{{ route('servicios.solicitar-pago', $servicio) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm {{ $servicio->pago_solicitado_at ? 'btn-link' : 'btn-outline-primary' }}">
                    <i class="fas {{ $servicio->pago_solicitado_at ? 'fa-undo' : 'fa-paper-plane' }}" aria-hidden="true"></i>
                    <span>{{ $servicio->pago_solicitado_at ? 'Retirar solicitud' : 'Solicitar pago' }}</span>
                </button>
            </form>
        @endunless
    @endcan
    @endif
    <button type="button" class="btn btn-sm btn-light" data-svc-accion="historial" data-url="{{ route('servicios.historial', $servicio) }}"
        data-nombre="{{ $nombre }}" title="Historial y pagos" aria-label="Historial y pagos de {{ $servicio->titulo }}">
        <i class="fas fa-history" aria-hidden="true"></i>
    </button>
    {{-- Mensajería con el proveedor desde reservas@: solicitar, confirmar con orden, modificar, cancelar y su hilo. --}}
    @unless ($sinProveedor)
        @php $nMensajes = $servicio->mensajes_count ?? null; @endphp
        <a href="{{ route('servicios.mensaje', $servicio) }}" class="btn btn-sm btn-light svc-msg-btn"
            title="{{ $nMensajes ? $nMensajes . ' ' . ($nMensajes === 1 ? 'mensaje' : 'mensajes') . ' con el proveedor' : 'Escribir al proveedor' }}{{ $servicio->orden_enviada_at ? ' · orden enviada el ' . $servicio->orden_enviada_at->format('d/m/Y') : '' }}"
            aria-label="Mensajes con el proveedor de {{ $servicio->titulo }}">
            <i class="fas {{ $servicio->orden_enviada_at ? 'fa-envelope-open-text' : 'fa-envelope' }}" aria-hidden="true"></i>
            @if ($nMensajes)<span class="svc-msg-btn__count">{{ $nMensajes }}</span>@endif
        </a>
    @endunless
    @unless ($sinProveedor)
        <a href="{{ route('servicios.voucher', $servicio) }}" target="_blank" rel="noopener" class="btn btn-sm btn-light"
            title="Orden de servicio (PDF)" aria-label="Orden de servicio de {{ $servicio->titulo }}">
            <i class="fas fa-file-invoice" aria-hidden="true"></i>
        </a>
    @endunless
</div>
