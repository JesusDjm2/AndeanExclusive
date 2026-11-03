// Itinerario del cliente (programas/show) y formulario de datos de viajeros (programas/pasajeros-cliente).
// Sin jQuery ni Bootstrap JS.

// Navegación por secciones: resalta la sección visible y marca la barra cuando queda fija arriba.
function initSectionNav() {
    const nav = document.querySelector('[data-itin-nav]');
    if (!nav) return;

    const links = [...nav.querySelectorAll('[data-itin-link]')];
    const sections = links.map((link) => document.querySelector(link.getAttribute('href'))).filter(Boolean);

    const activate = (id) => {
        links.forEach((link) => {
            const active = link.getAttribute('href') === `#${id}`;
            link.classList.toggle('is-active', active);
            if (active) {
                link.setAttribute('aria-current', 'true');
                // En celular la barra se desplaza de lado: mantener visible el enlace activo.
                link.scrollIntoView({ block: 'nearest', inline: 'nearest' });
            } else {
                link.removeAttribute('aria-current');
            }
        });
    };

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            const visible = entries.filter((e) => e.isIntersecting).sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top)[0];
            if (visible) activate(visible.target.id);
        }, { rootMargin: '-30% 0px -60% 0px' });
        sections.forEach((section) => observer.observe(section));

        // Sombra cuando la barra queda fija.
        const sentinel = document.createElement('div');
        nav.before(sentinel);
        new IntersectionObserver(([entry]) => nav.classList.toggle('is-stuck', !entry.isIntersecting)).observe(sentinel);
    }
}

// Formulario de datos de los viajeros (programas/pasajeros-cliente): agregar y quitar viajeros nuevos.
function initPaxForm() {
    const form = document.querySelector('[data-pax-form]');
    if (!form) return;

    const list = form.querySelector('[data-pax-list]');
    const template = form.querySelector('[data-pax-template]');
    const add = form.querySelector('[data-pax-add]');
    const max = parseInt(add?.dataset.max, 10) || 20;
    let next = parseInt(list.dataset.nextIndex, 10) || 0;

    const refresh = () => {
        const items = list.querySelectorAll('[data-pax-item]');
        items.forEach((item, i) => {
            const n = item.querySelector('[data-pax-num]');
            if (n) n.textContent = i + 1;
        });
        if (add) add.hidden = items.length >= max;
    };

    add?.addEventListener('click', () => {
        list.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__INDEX__', next++));
        refresh();
        list.lastElementChild?.querySelector('input')?.focus();
    });

    list.addEventListener('click', (event) => {
        const remove = event.target.closest('[data-pax-remove]');
        if (!remove) return;
        remove.closest('[data-pax-item]').remove();
        refresh();
    });

    refresh();
    form.querySelector('.is-invalid')?.focus();
}

// Acordeones Día a día (show.blade.php): optimizado para celulares, tablets y escritorios.
function initDayAccordions() {
    const section = document.querySelector('#dia-a-dia');
    if (!section) return;

    const days = section.querySelectorAll('[data-itin-day]');
    if (!days.length) return;

    const setDayOpen = (dayEl, open) => {
        const toggle = dayEl.querySelector('[data-itin-day-toggle]');
        const collapse = dayEl.querySelector('.itin-day__collapse');
        if (!collapse) return;

        dayEl.classList.toggle('is-open', open);
        if (toggle) {
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        }

        if (open) {
            collapse.removeAttribute('hidden');
        } else {
            collapse.setAttribute('hidden', '');
        }
    };

    days.forEach((dayEl) => {
        const toggle = dayEl.querySelector('[data-itin-day-toggle]');
        const marker = dayEl.querySelector('[data-itin-marker-toggle]');

        const toggleHandler = (e) => {
            e.preventDefault();
            const isOpen = dayEl.classList.contains('is-open');
            setDayOpen(dayEl, !isOpen);
        };

        if (toggle) toggle.addEventListener('click', toggleHandler);
        if (marker) marker.addEventListener('click', toggleHandler);
    });

    const expandAllBtn = section.querySelector('[data-itin-expand-all]');
    const collapseAllBtn = section.querySelector('[data-itin-collapse-all]');

    if (expandAllBtn) {
        expandAllBtn.addEventListener('click', (e) => {
            e.preventDefault();
            days.forEach((d) => setDayOpen(d, true));
        });
    }

    if (collapseAllBtn) {
        collapseAllBtn.addEventListener('click', (e) => {
            e.preventDefault();
            days.forEach((d) => setDayOpen(d, false));
        });
    }

    // Si la URL apunta a un día específico (#dia-3), abrirlo y enfocarlo suavemente
    const handleHash = () => {
        if (!window.location.hash) return;
        const target = document.querySelector(window.location.hash);
        if (target && target.hasAttribute('data-itin-day')) {
            setDayOpen(target, true);
            setTimeout(() => {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 250);
        }
    };

    window.addEventListener('hashchange', handleHash);
    if (window.location.hash) {
        handleHash();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initSectionNav();
    initPaxForm();
    initDayAccordions();
});
