// Hotel gallery lightbox: each photo-wall tile opens a fullscreen modal
// slideshow fed by a JSON payload rendered next to the wall
// ([data-gallery-data] -> { key: { name, place, images: [] } }).

export function initHotelGalleries() {
    const modal = document.querySelector('[data-gallery-modal]');
    const dataEl = document.querySelector('[data-gallery-data]');
    if (!modal || !dataEl) return;

    let galleries;
    try {
        galleries = JSON.parse(dataEl.textContent);
    } catch {
        return;
    }

    const img = modal.querySelector('[data-gallery-img]');
    const captionName = modal.querySelector('[data-gallery-name]');
    const captionPlace = modal.querySelector('[data-gallery-place]');
    const counter = modal.querySelector('[data-gallery-counter]');
    const closeBtn = modal.querySelector('[data-gallery-close].gallery-modal__close');
    let hotel = null;
    let index = 0;
    let lastFocus = null;

    const show = (i) => {
        index = (i + hotel.images.length) % hotel.images.length;
        img.src = hotel.images[index];
        img.alt = `${hotel.name} — ${hotel.place} (${index + 1}/${hotel.images.length})`;
        counter.textContent = `${index + 1} / ${hotel.images.length}`;
        const next = new Image();
        next.src = hotel.images[(index + 1) % hotel.images.length];
    };

    const open = (key) => {
        hotel = galleries[key];
        if (!hotel || !hotel.images.length) return;
        lastFocus = document.activeElement;
        captionName.textContent = hotel.name;
        captionPlace.textContent = hotel.place;
        show(0);
        modal.classList.add('is-open');
        modal.removeAttribute('hidden');
        document.body.style.overflow = 'hidden';
        closeBtn.focus();
    };

    const close = () => {
        modal.classList.remove('is-open');
        modal.setAttribute('hidden', '');
        document.body.style.overflow = '';
        img.src = '';
        lastFocus?.focus();
    };

    document.querySelectorAll('[data-gallery-open]').forEach((btn) => {
        btn.addEventListener('click', () => open(btn.dataset.galleryOpen));
    });

    modal.querySelector('[data-gallery-prev]').addEventListener('click', () => show(index - 1));
    modal.querySelector('[data-gallery-next]').addEventListener('click', () => show(index + 1));
    modal.querySelectorAll('[data-gallery-close]').forEach((el) => el.addEventListener('click', close));

    document.addEventListener('keydown', (e) => {
        if (!modal.classList.contains('is-open')) return;
        if (e.key === 'Escape') close();
        if (e.key === 'ArrowLeft') show(index - 1);
        if (e.key === 'ArrowRight') show(index + 1);
    });

    modal.addEventListener('keydown', (e) => {
        if (e.key !== 'Tab') return;
        const focusables = modal.querySelectorAll('button');
        const first = focusables[0];
        const last = focusables[focusables.length - 1];
        if (e.shiftKey && document.activeElement === first) {
            last.focus();
            e.preventDefault();
        } else if (!e.shiftKey && document.activeElement === last) {
            first.focus();
            e.preventDefault();
        }
    });
}
