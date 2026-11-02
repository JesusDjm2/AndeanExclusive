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

document.addEventListener('DOMContentLoaded', () => {
    initSectionNav();
    initPaxForm();
});
