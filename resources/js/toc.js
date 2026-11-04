// Sticky table of contents for long documents (resources/views/pages/terms.blade.php):
// highlights the clause currently being read.

export function initToc() {
    const toc = document.querySelector('[data-toc]');
    if (!toc) return;

    const links = new Map(
        [...toc.querySelectorAll('[data-toc-link]')].map((link) => [link.hash.slice(1), link])
    );

    const setActive = (id) => {
        links.forEach((link, key) => {
            const isActive = key === id;
            link.classList.toggle('is-active', isActive);
            if (isActive) link.setAttribute('aria-current', 'location');
            else link.removeAttribute('aria-current');
        });
    };

    const spy = new IntersectionObserver((entries) => {
        entries.filter((entry) => entry.isIntersecting).forEach((entry) => setActive(entry.target.id));
    }, { rootMargin: '-20% 0px -70% 0px' });

    document.querySelectorAll('[data-toc-section]').forEach((section) => {
        if (links.has(section.id)) spy.observe(section);
    });
}
