{{--
    Modal Global para Visualización Rápida del Historial de Movimientos de un Grupo.
    Permite consultar la auditoría en 1 clic sin abrir la vista de edición.
--}}
<div class="modal fade" id="modal-historial-programa" tabindex="-1" aria-labelledby="modal-historial-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle" style="width: 38px; height: 38px;">
                        <i class="fas fa-history"></i>
                    </span>
                    <div>
                        <h2 class="modal-title h5 mb-0 fw-bold" id="modal-historial-title">Historial de Movimientos</h2>
                        <small class="text-muted" id="modal-historial-subtitle">Bitácora de auditoría del grupo</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4" id="modal-historial-body">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="text-muted mt-2 small">Cargando bitácora del grupo...</p>
                </div>
            </div>
        </div>
    </div>
</div>
