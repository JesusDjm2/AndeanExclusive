// Tour / adventure detail pages (resources/views/tours/*):
// - section nav: highlights the section in view and marks itself as stuck;
// - itinerary: "expand / collapse all" for the day-by-day <details>;
// - mobile bar: hidden while the hero or the booking form is on screen.

function initSectionNav() {
    const nav = document.querySelector('[data-tour-nav]');
    if (!nav) return;

    const links = new Map(
        [...nav.querySelectorAll('[data-tour-nav-link]')].map((link) => [link.hash.slice(1), link])
    );
    const sections = [...document.querySelectorAll('[data-tour-section]')].filter((section) => links.has(section.id));

    const setActive = (id) => {
        links.forEach((link, key) => {
            const isActive = key === id;
            link.classList.toggle('is-active', isActive);
            if (isActive) {
                link.setAttribute('aria-current', 'location');
                // Keep the active link visible in the horizontally scrolling nav (mobile).
                const list = link.closest('ul');
                list?.scrollTo({ left: link.offsetLeft - list.clientWidth / 3, behavior: 'smooth' });
            } else {
                link.removeAttribute('aria-current');
            }
        });
    };

    // A section counts as current once it crosses the upper third of the viewport.
    const spy = new IntersectionObserver((entries) => {
        entries.filter((entry) => entry.isIntersecting).forEach((entry) => setActive(entry.target.id));
    }, { rootMargin: '-30% 0px -65% 0px' });
    sections.forEach((section) => spy.observe(section));

    const hero = document.querySelector('.tour-hero');
    if (hero) {
        new IntersectionObserver(([entry]) => {
            nav.classList.toggle('is-stuck', !entry.isIntersecting);
        }).observe(hero);
    }
}

function initItineraryToggle() {
    const toggle = document.querySelector('[data-itinerary-toggle]');
    const days = [...document.querySelectorAll('[data-itinerary] details')];
    if (!toggle || !days.length) return;

    const sync = () => {
        const allOpen = days.every((day) => day.open);
        toggle.textContent = allOpen ? toggle.dataset.labelCollapse : toggle.dataset.labelExpand;
        toggle.setAttribute('aria-expanded', String(allOpen));
    };

    toggle.addEventListener('click', () => {
        const open = !days.every((day) => day.open);
        days.forEach((day) => { day.open = open; });
        sync();
    });
    days.forEach((day) => day.addEventListener('toggle', sync));
    sync();
}

function initMobileBar() {
    const bar = document.querySelector('[data-tour-mobile-bar]');
    if (!bar) return;

    const targets = [document.querySelector('.tour-hero'), document.getElementById('book')].filter(Boolean);
    const visible = new Set();

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) visible.add(entry.target);
            else visible.delete(entry.target);
        });
        bar.classList.toggle('is-hidden', visible.size > 0);
    });
    targets.forEach((target) => observer.observe(target));
}

export function initTourDetail() {
    if (!document.querySelector('[data-tour-nav]')) return;

    initSectionNav();
    initItineraryToggle();
    initMobileBar();
}
