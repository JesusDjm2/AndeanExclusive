{{--
    Vista previa de PDF (un solo modal para todo el panel). Se abre con cualquier enlace que tenga
    data-pdf-preview="URL en línea" (su href es la descarga) y data-pdf-title. Comportamiento: resources/js/pdf-preview.js.
--}}
<div class="modal fade pdf-preview" id="pdfPreviewModal" tabindex="-1" aria-labelledby="pdfPreviewTitle" aria-hidden="true" data-pdf-modal>
    <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header">
                <div class="pdf-preview__heading">
                    <span class="pdf-preview__icon" aria-hidden="true"><i class="fas fa-file-pdf"></i></span>
                    <div>
                        <h2 class="modal-title" id="pdfPreviewTitle" data-pdf-title>Vista previa</h2>
                        <small class="text-muted">Vista previa del PDF que recibe el cliente</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body pdf-preview__body">
                <div class="pdf-preview__loading" data-pdf-loading>
                    <span class="spinner-border text-primary" role="status" aria-hidden="true"></span>
                    <span>Generando el PDF…</span>
                </div>
                <div class="pdf-preview__fallback" data-pdf-fallback hidden>
                    <i class="fas fa-mobile-alt" aria-hidden="true"></i>
                    <p>Este navegador no muestra PDF dentro de la página.<br>Ábrelo en una nueva ventana o descárgalo.</p>
                </div>
                <iframe class="pdf-preview__frame" title="Vista previa del PDF" data-pdf-frame></iframe>
            </div>

            <div class="modal-footer pdf-preview__footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" class="btn btn-outline-primary" target="_blank" rel="noopener" data-pdf-open>
                    <i class="fas fa-external-link-alt" aria-hidden="true"></i> Abrir en nueva ventana
                </a>
                <a href="#" class="btn btn-primary" data-pdf-download>
                    <i class="fas fa-download" aria-hidden="true"></i> Descargar
                </a>
            </div>
        </div>
    </div>
</div>
