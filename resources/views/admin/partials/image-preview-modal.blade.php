{{--
    Vista previa de imagen (un solo modal para todo el panel).
    Se abre al hacer clic en cualquier elemento que tenga data-ae-preview="URL_IMAGEN".
    Atributos opcionales: data-ae-title="TITULO" y data-ae-meta="SUBTITULO / META".
    Comportamiento en resources/js/image-preview.js.
--}}
<div class="modal fade ae-image-preview-modal" id="aeImagePreviewModal" tabindex="-1" aria-labelledby="aePreviewModalTitle" aria-hidden="true" data-ae-img-modal>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 0.85rem; overflow: hidden;">
            <div class="modal-header d-flex align-items-center justify-content-between py-2.5 px-3 bg-light border-bottom">
                <div class="d-flex align-items-center gap-2 text-truncate pe-2">
                    <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                        <i class="fas fa-image fa-sm"></i>
                    </div>
                    <div class="text-truncate">
                        <h5 class="modal-title fs-6 fw-bold mb-0 text-dark text-truncate" id="aePreviewModalTitle">Foto de Portada</h5>
                        <small class="text-muted d-block text-truncate" id="aePreviewModalSubtitle" style="font-size: 0.76rem;">Vista previa de imagen</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-0 text-center position-relative d-flex align-items-center justify-content-center" style="background: #0b0f12; min-height: 280px; max-height: 78vh; overflow: hidden;">
                <div class="spinner-border text-light position-absolute" role="status" id="aePreviewModalSpinner">
                    <span class="visually-hidden">Cargando imagen...</span>
                </div>
                <img src="" id="aePreviewModalImg" alt="Foto ampliada" class="img-fluid"
                    style="max-height: 76vh; width: auto; max-width: 100%; object-fit: contain; opacity: 0; transition: opacity 0.25s ease;">
            </div>

            <div class="modal-footer py-2 px-3 d-flex justify-content-between align-items-center bg-light border-top">
                <small class="text-muted" style="font-size: 0.78rem;">
                    <i class="fas fa-info-circle me-1"></i> Presiona <kbd class="px-1.5 py-0.5 bg-secondary text-white rounded">Esc</kbd> o clic fuera para cerrar
                </small>
                <div class="d-flex gap-2">
                    <a href="#" id="aePreviewModalFullLink" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary" title="Abrir imagen original en nueva pestaña">
                        <i class="fas fa-external-link-alt me-1"></i> Abrir original
                    </a>
                    <button type="button" class="btn btn-sm btn-primary px-3 shadow-sm" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
