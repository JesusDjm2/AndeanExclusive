// Vanilla replacement for the old jQuery mobile-menu-toggle / sticky-header
// logic that used to live in public/js/scripts.js.

function initStickyHeader() {
    const header = document.querySelector('.site-header');
    if (!header) return;

    const toggleScrolled = () => {
        header.classList.toggle('is-scrolled', window.scrollY > 40);
    };

    toggleScrolled();
    window.addEventListener('scroll', toggleScrolled, { passive: true });
}

function initMobileNav() {
    const toggle = document.querySelector('[data-nav-toggle]');
    const panel = document.querySelector('[data-nav-panel]');
    const close = document.querySelector('[data-nav-close]');
    if (!toggle || !panel) return;

    const open = () => {
        panel.classList.add('is-open');
        panel.removeAttribute('aria-hidden');
        toggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
        close?.focus();
    };

    const dismiss = () => {
        panel.classList.remove('is-open');
        panel.setAttribute('aria-hidden', 'true');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
        toggle.focus();
    };

    toggle.setAttribute('aria-expanded', 'false');
    toggle.addEventListener('click', open);
    close?.addEventListener('click', dismiss);
    panel.querySelectorAll('a').forEach((link) => link.addEventListener('click', dismiss));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && panel.classList.contains('is-open')) dismiss();
    });
}

export function initNav() {
    initStickyHeader();
    initMobileNav();
}
