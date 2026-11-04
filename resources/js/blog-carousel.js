// Home "Latest from the Blog" rail: the track scrolls natively (scroll-snap);
// these buttons just page it one card at a time and hide at either end.

export function initBlogRails() {
    document.querySelectorAll('[data-blog-rail]').forEach((rail) => {
        const track = rail.querySelector('.blog-rail__track');
        const prev = rail.querySelector('[data-rail-prev]');
        const next = rail.querySelector('[data-rail-next]');
        if (!track || !prev || !next) return;

        const step = () => {
            const card = track.firstElementChild;
            const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
            return card ? card.getBoundingClientRect().width + gap : track.clientWidth;
        };

        const update = () => {
            const max = track.scrollWidth - track.clientWidth - 1;
            prev.disabled = track.scrollLeft <= 1;
            next.disabled = track.scrollLeft >= max;
        };

        prev.addEventListener('click', () => track.scrollBy({ left: -step() }));
        next.addEventListener('click', () => track.scrollBy({ left: step() }));
        track.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update);
        update();
    });
}
