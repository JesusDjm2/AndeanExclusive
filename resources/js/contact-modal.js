// Global contact modal (layouts/partials/contact-modal.blade.php).
// Opens from any [data-contact-open] trigger (footer button, homepage CTA);
// reopens itself after a successful/failed submission via data-auto-open.
// Mirrors the hotel gallery modal's a11y pattern: focus trap, Esc, backdrop
// click, body scroll lock, focus restore.

export function initContactModals() {
    const modal = document.querySelector('[data-contact-modal]');
    if (!modal) return;

    const dialog = modal.querySelector('.contact-modal__dialog');
    const previouslyFocused = { current: null };

    const focusables = () => [
        ...modal.querySelectorAll('button, [href], input, textarea, [tabindex]:not([tabindex="-1"])'),
    ].filter((el) => !el.disabled && el.offsetParent !== null);

    const open = (tourName = null) => {
        previouslyFocused.current = document.activeElement;
        modal.hidden = false;
        modal.setAttribute('aria-hidden', 'false');
        modal.classList.add('is-open');
        document.body.style.overflow = 'hidden';

        if (tourName) {
            const textarea = modal.querySelector('textarea[name="mensaje"]');
            if (textarea && !textarea.value.trim()) {
                const isEs = (document.documentElement.lang || 'es').startsWith('es');
                textarea.value = isEs
                    ? `¡Hola! Me interesa recibir información y cotizar el tour "${tourName}".\nFechas tentativas y número de personas: `
                    : `Hello! I would like more information and a quote for the tour "${tourName}".\nTentative dates and number of travelers: `;
            }
        }

        const first = dialog.querySelector('[data-aet-gracias], [data-aet-field].is-invalid input, [data-aet-field].is-invalid textarea, input:not([type=hidden]):not([tabindex="-1"])');
        (first ?? dialog).focus({ preventScroll: true });
    };

    const close = () => {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        window.setTimeout(() => { modal.hidden = true; }, 250);
        previouslyFocused.current?.focus({ preventScroll: true });
    };

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-contact-open]');
        if (trigger) {
            event.preventDefault();
            const tourName = trigger.dataset.tourName || null;
            open(tourName);
            return;
        }
        if (event.target.closest('[data-contact-close]')) close();
    });

    document.addEventListener('keydown', (event) => {
        if (!modal.classList.contains('is-open')) return;

        if (event.key === 'Escape') {
            close();
            return;
        }

        if (event.key === 'Tab') {
            const items = focusables();
            if (!items.length) return;
            const first = items[0];
            const last = items[items.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    });

    if (modal.hasAttribute('data-auto-open')) {
        window.setTimeout(open, 400);
    }
}
