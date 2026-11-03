// Vista previa interactiva de programas en hover (desktop) y tap (móvil).
// Estilos: resources/sass/admin/_components.scss (.ae-prog-popover)

export function initProgramaHover(bootstrap) {
    if (!bootstrap || !bootstrap.Popover) return;

    const wrappers = document.querySelectorAll('.ae-prog-code-wrapper');
    if (!wrappers.length) return;

    wrappers.forEach((wrapper) => {
        const link = wrapper.querySelector('.ae-prog-code-link');
        const button = wrapper.querySelector('.ae-prog-preview-btn');
        const tpl = wrapper.querySelector('.ae-prog-popover-tpl');
        if (!tpl) return;

        const getContent = () => tpl.innerHTML;

        // Configuración común
        const popoverConfig = {
            html: true,
            sanitize: false,
            boundary: 'viewport',
            container: 'body',
            placement: 'auto',
            fallbackPlacements: ['bottom', 'top', 'right', 'left'],
            customClass: 'ae-prog-popover',
            content: getContent,
        };

        // Enlace del código: hover / focus en desktop con soporte para interactuar con la tarjeta
        if (link) {
            let hideTimeout = null;
            const popoverInstance = new bootstrap.Popover(link, {
                ...popoverConfig,
                trigger: 'manual',
            });

            const showPopover = () => {
                clearTimeout(hideTimeout);
                // Cerrar otros popovers abiertos
                document.querySelectorAll('.popover.ae-prog-popover').forEach((p) => {
                    const tipId = p.getAttribute('id');
                    if (tipId && tipId !== link.getAttribute('aria-describedby')) {
                        p.remove();
                    }
                });
                popoverInstance.show();

                const popoverEl = document.getElementById(link.getAttribute('aria-describedby'));
                if (popoverEl) {
                    popoverEl.addEventListener('mouseenter', () => clearTimeout(hideTimeout));
                    popoverEl.addEventListener('mouseleave', () => {
                        hideTimeout = setTimeout(() => popoverInstance.hide(), 200);
                    });
                }
            };

            const scheduleHide = () => {
                hideTimeout = setTimeout(() => {
                    const popoverEl = document.getElementById(link.getAttribute('aria-describedby'));
                    if (!popoverEl || !popoverEl.matches(':hover')) {
                        popoverInstance.hide();
                    }
                }, 200);
            };

            link.addEventListener('mouseenter', showPopover);
            link.addEventListener('mouseleave', scheduleHide);
            link.addEventListener('focus', showPopover);
            link.addEventListener('blur', scheduleHide);
        }

        // Botón (i): click directo para móvil o interacción manual
        if (button) {
            new bootstrap.Popover(button, {
                ...popoverConfig,
                trigger: 'click',
            });
        }
    });

    // Cerrar popovers al hacer click o tap fuera
    document.addEventListener('click', (event) => {
        if (!event.target.closest('.ae-prog-code-wrapper') && !event.target.closest('.popover.ae-prog-popover')) {
            document.querySelectorAll('.ae-prog-code-wrapper [data-prog-popover]').forEach((el) => {
                const instance = bootstrap.Popover.getInstance(el);
                if (instance) {
                    instance.hide();
                }
            });
        }
    });
}
