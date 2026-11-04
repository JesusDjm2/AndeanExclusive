// Floating "back to top" button (layouts/partials/back-to-top.blade.php).
// Appears after the first scroll stretch; clicking eases back to the hero
// with GSAP ScrollToPlugin (autoKill lets the user interrupt by scrolling).
import gsap from 'gsap';
import { ScrollToPlugin } from 'gsap/ScrollToPlugin';

gsap.registerPlugin(ScrollToPlugin);

export function initBackToTop() {
    const btn = document.querySelector('[data-to-top]');
    if (!btn) return;

    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const toggle = () => btn.classList.toggle('is-visible', window.scrollY > 480);
    toggle();
    window.addEventListener('scroll', toggle, { passive: true });

    btn.addEventListener('click', () => {
        if (reduced) {
            window.scrollTo(0, 0);
            return;
        }
        gsap.to(window, {
            scrollTo: { y: 0, autoKill: false },
            duration: 1.2,
            ease: 'power3.inOut',
        });
    });
}
