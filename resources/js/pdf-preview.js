// Vista previa de PDF en un modal (admin/partials/pdf-preview-modal).
// <a href="URL de descarga" data-pdf-preview="URL en línea" data-pdf-title="Nombre"> abre el modal en lugar de descargar;
// sin JS, el enlace descarga como antes.

export function initPdfPreview(bootstrap) {
    const modalEl = document.querySelector('[data-pdf-modal]');
    if (!modalEl) return;

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    const frame = modalEl.querySelector('[data-pdf-frame]');
    const loading = modalEl.querySelector('[data-pdf-loading]');
    const fallback = modalEl.querySelector('[data-pdf-fallback]');
    const title = modalEl.querySelector('[data-pdf-title]');
    const openLink = modalEl.querySelector('[data-pdf-open]');
    const downloadLink = modalEl.querySelector('[data-pdf-download]');

    // Navegadores móviles (Android, algunos iOS) no muestran PDF en un iframe: se ofrecen solo los botones.
    const canEmbed = navigator.pdfViewerEnabled !== false && !/Android/i.test(navigator.userAgent);

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-pdf-preview]');
        if (!trigger || event.ctrlKey || event.metaKey || event.shiftKey) return;
        event.preventDefault();

        const inlineUrl = trigger.dataset.pdfPreview;
        title.textContent = trigger.dataset.pdfTitle || 'Vista previa';
        openLink.href = inlineUrl;
        downloadLink.href = trigger.getAttribute('href');

        fallback.hidden = canEmbed;
        loading.hidden = !canEmbed;
        frame.hidden = !canEmbed;
        frame.classList.remove('is-ready');
        if (canEmbed) frame.src = inlineUrl;

        modal.show();
    });

    frame.addEventListener('load', () => {
        if (!frame.src || frame.src === 'about:blank') return;
        loading.hidden = true;
        frame.classList.add('is-ready');
    });

    // Al cerrar se libera el PDF (no queda cargado ni sigue consumiendo memoria).
    modalEl.addEventListener('hidden.bs.modal', () => {
        frame.removeAttribute('src');
        frame.classList.remove('is-ready');
    });
}
