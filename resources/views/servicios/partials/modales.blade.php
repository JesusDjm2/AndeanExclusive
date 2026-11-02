{{--
    Modales de gestión de servicios (una vez por página): estado y confirmación, pago al proveedor e historial.
    Los rellena resources/js/servicio-acciones.js con los data-* del botón (servicios/partials/acciones).
--}}
{{-- Estado y confirmación --}}
<div class="modal fade svc-modal" id="svcEstadoModal" tabindex="-1" aria-labelledby="svcEstadoTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content" data-svc-form="estado">
            @csrf
            @method('PATCH')
            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="svcEstadoTitle">Estado de la reserva</h2>
                    <small class="text-muted" data-svc-nombre></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <fieldset>
                    <legend class="prog-label">Estado</legend>
                    <div class="svc-states">
                    @foreach (\App\Models\Servicio::ESTADOS_MANUALES as $clave)
                        @php $etiqueta = \App\Models\Servicio::ESTADOS[$clave]; @endphp
                        <label class="svc-states__option svc-states__option--{{ $clave }}">
                            <input type="radio" name="estado" value="{{ $clave }}" data-svc-estado-opcion>
                            <span>
                                <i class="fas {{ ['pendiente' => 'fa-hourglass-start', 'solicitado' => 'fa-paper-plane', 'confirmado' => 'fa-check-circle', 'cancelado' => 'fa-ban'][$clave] }}" aria-hidden="true"></i>
                                {{ $etiqueta }}
                            </span>
                        </label>
                    @endforeach
                    </div>
                </fieldset>
                <div class="mt-3" data-svc-confirmacion-campo>
                    <label for="svc-confirmacion" class="prog-label">N.º de confirmación del proveedor</label>
                    <input id="svc-confirmacion" type="text" name="confirmacion" maxlength="80" class="form-control" autocomplete="off"
                        placeholder="Código, n.º de reserva o de ticket" data-svc-confirmacion>
                    <small class="prog-input-hint">Obligatorio para marcar como confirmado.</small>
                </div>
                <div class="mt-3">
                    <label for="svc-nota" class="prog-label">Nota para el historial <span class="text-muted fw-normal">(opcional)</span></label>
                    <input id="svc-nota" type="text" name="nota" maxlength="255" class="form-control" placeholder="Ej. Confirmado por correo con Juan">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save" aria-hidden="true"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Asignar proveedor y costo real --}}
@php
    $proveedoresModal = \App\Models\Proveedor::with('categoria:id,nombre')->orderBy('nombre')->get(['id', 'nombre', 'categoria_id', 'telefono'])
        ->groupBy(fn ($p) => $p->categoria?->nombre ?? 'Sin categoría')->sortKeys();
@endphp
<div class="modal fade svc-modal" id="svcAsignarModal" tabindex="-1" aria-labelledby="svcAsignarTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content" data-svc-form="asignar">
            @csrf
            @method('PATCH')
            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="svcAsignarTitle">Asignar proveedor</h2>
                    <small class="text-muted" data-svc-nombre></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="svc-assign-note" data-svc-cambio hidden>
                    <i class="fas fa-info-circle" aria-hidden="true"></i>
                    Si cambias el proveedor de un servicio ya solicitado o confirmado, vuelve a «Por reservar» y se borra el n.º de confirmación.
                </p>
                <label for="svc-proveedor" class="prog-label">Proveedor <span class="required">*</span></label>
                <select id="svc-proveedor" name="proveedor_id" class="form-select" required data-svc-proveedor>
                    <option value="">— Elegir proveedor —</option>
                    @foreach ($proveedoresModal as $categoria => $lista)
                        <optgroup label="{{ $categoria }}">
                            @foreach ($lista as $proveedor)
                                <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}{{ $proveedor->telefono ? ' · ' . $proveedor->telefono : '' }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>

                <dl class="svc-amounts mt-3">
                    <div><dt>Cotizado al cliente</dt><dd data-svc-estimado>—</dd></div>
                    <div data-svc-desvio-box><dt>Diferencia</dt><dd data-svc-desvio>—</dd></div>
                </dl>
                <label for="svc-costo-real" class="prog-label">Costo real del proveedor <span class="text-muted fw-normal" data-svc-por></span></label>
                <input id="svc-costo-real" type="number" name="costo" step="0.01" min="0" class="form-control" data-svc-costo-real>
                <small class="prog-input-hint">Lo que cobra este proveedor. Con este monto se paga y se calcula el margen real.</small>

                <div class="mt-3">
                    <label for="svc-asignar-nota" class="prog-label">Nota para el historial <span class="text-muted fw-normal">(opcional)</span></label>
                    <input id="svc-asignar-nota" type="text" name="nota" maxlength="255" class="form-control" placeholder="Ej. Elegido por disponibilidad en temporada alta">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-user-check" aria-hidden="true"></i> Asignar</button>
            </div>
        </form>
    </div>
</div>

{{-- Pago al proveedor --}}
<div class="modal fade svc-modal" id="svcPagoModal" tabindex="-1" aria-labelledby="svcPagoTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content" enctype="multipart/form-data" data-svc-form="pago">
            @csrf
            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="svcPagoTitle">Registrar pago al proveedor</h2>
                    <small class="text-muted" data-svc-nombre></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <dl class="svc-amounts">
                    <div><dt>Importe</dt><dd data-svc-importe></dd></div>
                    <div><dt>Pagado</dt><dd data-svc-pagado></dd></div>
                    <div class="is-due"><dt>Saldo</dt><dd data-svc-saldo></dd></div>
                </dl>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="svc-monto" class="prog-label">Monto <span class="required">*</span> <span class="text-muted fw-normal" data-svc-moneda></span></label>
                        <input id="svc-monto" type="number" name="monto" step="0.01" min="0.01" required class="form-control" data-svc-monto>
                        <div class="svc-quick">
                            <button type="button" class="btn btn-link btn-sm p-0" data-svc-monto-saldo>Todo el saldo</button>
                            <button type="button" class="btn btn-link btn-sm p-0" data-svc-monto-mitad>50 %</button>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="svc-fecha" class="prog-label">Fecha <span class="required">*</span></label>
                        <input id="svc-fecha" type="date" name="fecha" required class="form-control" max="{{ now()->toDateString() }}" value="{{ now()->toDateString() }}">
                    </div>
                    <div class="col-sm-6">
                        <label for="svc-metodo" class="prog-label">Método</label>
                        <select id="svc-metodo" name="metodo" class="form-select">
                            <option value="">—</option>
                            @foreach (\App\Models\ProveedorPago::METODOS as $clave => $etiqueta)
                                <option value="{{ $clave }}">{{ $etiqueta }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="svc-referencia" class="prog-label">N.º de operación / factura</label>
                        <input id="svc-referencia" type="text" name="referencia" maxlength="255" class="form-control">
                    </div>
                    <div class="col-12">
                        <label for="svc-comprobante" class="prog-label">Comprobante <span class="text-muted fw-normal">(PDF o imagen, privado)</span></label>
                        <input id="svc-comprobante" type="file" name="comprobante" accept=".pdf,.jpg,.jpeg,.png,.webp" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check" aria-hidden="true"></i> Registrar pago</button>
            </div>
        </form>
    </div>
</div>

{{-- Historial y pagos --}}
<div class="modal fade svc-modal" id="svcHistorialModal" tabindex="-1" aria-labelledby="svcHistorialTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="svcHistorialTitle">Historial del servicio</h2>
                    <small class="text-muted" data-svc-nombre></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" data-svc-historial>
                <div class="text-center py-4"><span class="spinner-border text-primary" role="status" aria-hidden="true"></span></div>
            </div>
        </div>
    </div>
</div>
