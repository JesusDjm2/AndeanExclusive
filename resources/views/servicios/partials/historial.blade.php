{{-- Historial de un servicio (ServicioController@historial, se carga dentro del modal). --}}
@php
    $fmt = fn ($n) => \App\Models\Servicio::formatoMonto((float) $n, $servicio->moneda);
    $situacion = $servicio->situacion_pago;
@endphp
<div class="svc-history">
    <dl class="svc-amounts">
        <div><dt>Estado</dt><dd><span class="svc-badge svc-badge--{{ $servicio->estado }}">{{ \App\Models\Servicio::ESTADOS[$servicio->estado] }}</span></dd></div>
        <div><dt>Confirmación</dt><dd>{{ $servicio->confirmacion ?: '—' }}</dd></div>
        <div><dt>Proveedor</dt><dd>{{ $servicio->proveedor?->nombre ?? 'Sin proveedor' }}</dd></div>
        @if ($situacion !== 'sin_costo')
            <div><dt>Importe</dt><dd>{{ $fmt($servicio->importe) }}</dd></div>
            <div><dt>Pagado</dt><dd>{{ $fmt($servicio->pagado_proveedor) }}</dd></div>
            <div class="{{ $servicio->saldo_proveedor > 0 ? 'is-due' : 'is-ok' }}"><dt>Saldo</dt><dd>{{ $fmt($servicio->saldo_proveedor) }}</dd></div>
        @endif
    </dl>

    <h3 class="svc-history__title">Pagos al proveedor</h3>
    @if ($servicio->pagos->isEmpty())
        <p class="text-muted small">{{ $situacion === 'sin_costo' ? 'El servicio no tiene costo cargado.' : 'Todavía no hay pagos.' }}</p>
    @else
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-3">
                <thead><tr><th>Fecha</th><th class="text-end">Monto</th><th>Método</th><th>Referencia</th><th>Registró</th><th></th></tr></thead>
                <tbody>
                    @foreach ($servicio->pagos as $pago)
                        <tr>
                            <td class="text-nowrap">{{ $pago->fecha->format('d/m/Y') }}</td>
                            <td class="text-end fw-semibold text-nowrap">{{ $fmt($pago->monto) }}</td>
                            <td>{{ \App\Models\ProveedorPago::METODOS[$pago->metodo] ?? '—' }}</td>
                            <td>
                                {{ $pago->referencia ?: '—' }}
                                @if ($pago->comprobante)
                                    <a href="{{ route('servicios.pagos.comprobante', [$servicio, $pago]) }}" target="_blank" rel="noopener" class="ms-1" title="Ver comprobante"><i class="fas fa-paperclip" aria-hidden="true"></i></a>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $pago->usuario?->name ?? '—' }}</td>
                            <td class="text-end">
                                @can('pagos.registrar')
                                <form action="{{ route('servicios.pagos.anular', [$servicio, $pago]) }}" method="POST"
                                    data-confirm-motivo data-confirm="Se anulará el pago de {{ $fmt($pago->monto) }}. Quedará registrado en el historial." data-confirm-title="¿Anular pago?" data-confirm-button="Sí, anular" data-confirm-danger>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" aria-label="Anular pago"><i class="fas fa-undo" aria-hidden="true"></i></button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if ($anulados->isNotEmpty())
        <details class="pagos-anulados mb-3">
            <summary>Pagos anulados ({{ $anulados->count() }})</summary>
            <ul>
                @foreach ($anulados as $pago)
                    <li>
                        <del>{{ $fmt($pago->monto) }}</del> · {{ $pago->fecha->format('d/m/Y') }}
                        @if ($pago->comprobante)
                            · <a href="{{ route('servicios.pagos.comprobante', [$servicio, $pago]) }}" target="_blank" rel="noopener">comprobante</a>
                        @endif
                        <small>Motivo: {{ $pago->motivo_anulacion ?: 'no indicado' }}</small>
                        <small>Anuló {{ $pago->anuladoPor?->name ?? '—' }} el {{ $pago->deleted_at->format('d/m/Y H:i') }}</small>
                    </li>
                @endforeach
            </ul>
        </details>
    @endif

    <h3 class="svc-history__title">Historial</h3>
    <ol class="svc-timeline">
        @forelse ($servicio->eventos as $evento)
            <li class="svc-timeline__item svc-timeline__item--{{ $evento->tipo === 'estado' ? $evento->a : $evento->tipo }}">
                <span class="svc-timeline__what">{{ $evento->descripcion }}</span>
                @if ($evento->detalle)<span class="svc-timeline__detail">{{ $evento->detalle }}</span>@endif
                <span class="svc-timeline__meta">{{ $evento->created_at->locale('es')->isoFormat('D MMM YYYY, HH:mm') }} · {{ $evento->usuario?->name ?? 'Sistema' }}</span>
            </li>
        @empty
            <li class="text-muted small">Sin movimientos registrados.</li>
        @endforelse
    </ol>
</div>
