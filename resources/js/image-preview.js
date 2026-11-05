// Vista previa de imagen en modal (admin/partials/image-preview-modal).
// Elementos con [data-ae-preview="URL"] abren el popup centrado sin salir de la vista.
// Si no hay JS o se pulsa Ctrl/Cmd, el enlace funciona como apertura tradicional.

export function initImagePreview(bootstrap) {
    const modalEl = document.getElementById('aeImagePreviewModal');
    if (!modalEl) return;

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    const titleEl = modalEl.querySelector('#aePreviewModalTitle');
    const subtitleEl = modalEl.querySelector('#aePreviewModalSubtitle');
    const imgEl = modalEl.querySelector('#aePreviewModalImg');
    const spinnerEl = modalEl.querySelector('#aePreviewModalSpinner');
    const fullLinkEl = modalEl.querySelector('#aePreviewModalFullLink');

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-ae-preview]');
        if (!trigger || event.ctrlKey || event.metaKey || event.shiftKey) return;
        event.preventDefault();

        const src = trigger.getAttribute('data-ae-preview');
        if (!src) return;

        const title = trigger.getAttribute('data-ae-title') || 'Foto de Portada';
        const meta = trigger.getAttribute('data-ae-meta') || 'Vista previa en alta resolución';

        if (titleEl) titleEl.textContent = title;
        if (subtitleEl) subtitleEl.textContent = meta;
        if (fullLinkEl) fullLinkEl.href = src;

        if (imgEl) {
            imgEl.style.opacity = '0';
            if (spinnerEl) spinnerEl.classList.remove('d-none');

            imgEl.onload = () => {
                imgEl.style.opacity = '1';
                if (spinnerEl) spinnerEl.classList.add('d-none');
            };

            imgEl.onerror = () => {
                imgEl.src = '/img/no-image.jpg';
                imgEl.style.opacity = '1';
                if (spinnerEl) spinnerEl.classList.add('d-none');
            };

            imgEl.src = src;
        }

        modal.show();
    });

    modalEl.addEventListener('hidden.bs.modal', () => {
        if (imgEl) {
            imgEl.src = '';
            imgEl.style.opacity = '0';
        }
        if (spinnerEl) spinnerEl.classList.remove('d-none');
    });
}
