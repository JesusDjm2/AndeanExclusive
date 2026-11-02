import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const EASE = 'power3.out';

// Elements within the last two viewport-heights of the document can only be
// revealed during the final stretch of scrolling, which leaves them invisible
// (opacity 0) while partially on screen. Reveal those on load instead of
// wiring a scroll trigger.
function isNearPageEnd(el) {
    const top = el.getBoundingClientRect().top + window.scrollY;
    return document.documentElement.scrollHeight - top <= window.innerHeight * 2.5;
}

function isRevealed(el) {
    return Number(getComputedStyle(el).opacity) > 0.95;
}

function revealImmediately(targets, distance, stagger = 0) {
    gsap.fromTo(
        targets,
        { opacity: 0, y: distance },
        { opacity: 1, y: 0, duration: 0.5, ease: EASE, stagger, delay: 0.1 }
    );
}

function initReveals() {
    document.querySelectorAll('[data-reveal]').forEach((el) => {
        const distance = Number(el.dataset.revealDistance ?? 24);
        if (isNearPageEnd(el)) {
            revealImmediately(el, distance);
            return;
        }
        const tween = gsap.fromTo(
            el,
            { opacity: 0, y: distance },
            {
                opacity: 1,
                y: 0,
                duration: 0.55,
                ease: EASE,
                scrollTrigger: {
                    trigger: el,
                    start: 'top 92%',
                    once: true,
                },
            }
        );
        el._revealTrigger = tween.scrollTrigger;
    });
}

function initRevealStagger() {
    document.querySelectorAll('[data-reveal-stagger]').forEach((group) => {
        const items = group.children;
        if (!items.length) return;

        if (isNearPageEnd(group)) {
            revealImmediately(items, 20, 0.06);
            return;
        }

        const triggerTarget = group.closest('section') ?? group;
        const tween = gsap.fromTo(
            items,
            { opacity: 0, y: 20 },
            {
                opacity: 1,
                y: 0,
                duration: 0.45,
                ease: EASE,
                stagger: 0.06,
                scrollTrigger: {
                    trigger: triggerTarget,
                    start: 'top 85%',
                    once: true,
                },
            }
        );
        group._revealTrigger = tween.scrollTrigger;
    });
}

function reconcileEndOfPage() {
    // Fonts and images settle after init, so the document can be taller or
    // shorter than when reveals were wired. Re-classify against the final
    // layout: pending triggers near the page end fire now, and elements that
    // were revealed on load but sit mid-page go back to scroll triggers.
    const vh = window.innerHeight;
    const targets = [
        ...document.querySelectorAll('[data-reveal]'),
        ...document.querySelectorAll('[data-reveal-stagger]'),
    ];

    targets.forEach((el) => {
        const isGroup = el.hasAttribute('data-reveal-stagger');
        if (isGroup && !el.children.length) return;

        const subject = isGroup ? el.children : el;
        const gauge = isGroup ? el.children[0] : el;
        const nearEnd = isNearPageEnd(el);
        const revealed = isRevealed(gauge);

        if (nearEnd && !revealed && el._revealTrigger) {
            el._revealTrigger.kill();
            gsap.to(subject, {
                opacity: 1,
                y: 0,
                duration: 0.45,
                ease: EASE,
                ...(isGroup ? { stagger: 0.06 } : {}),
            });
            return;
        }

        const inView = el.getBoundingClientRect().top < vh;
        if (!nearEnd && revealed && !el._revealTrigger && !inView) {
            const distance = isGroup ? 20 : Number(el.dataset.revealDistance ?? 24);
            const triggerTarget = isGroup ? (el.closest('section') ?? el) : el;
            el._revealTrigger = gsap.fromTo(
                subject,
                { opacity: 0, y: distance },
                {
                    opacity: 1,
                    y: 0,
                    duration: isGroup ? 0.45 : 0.55,
                    ease: EASE,
                    ...(isGroup ? { stagger: 0.06 } : {}),
                    scrollTrigger: {
                        trigger: triggerTarget,
                        start: isGroup ? 'top 85%' : 'top 92%',
                        once: true,
                    },
                }
            ).scrollTrigger;
        }
    });
}

function initParallax() {
    document.querySelectorAll('[data-parallax]').forEach((el) => {
        const strength = Number(el.dataset.parallax) || 60;
        gsap.to(el, {
            yPercent: strength,
            ease: 'none',
            scrollTrigger: {
                trigger: el.closest('[data-parallax-container]') ?? el.parentElement ?? el,
                start: 'top top',
                end: 'bottom top',
                scrub: true,
            },
        });
    });
}

// Slow zoom in/out loop ("breathing") for hero media. Safe to combine with
// the parallax scrub: GSAP tracks y and scale as independent transform
// channels on the same element.
function initBreathing() {
    document.querySelectorAll('[data-breathe]').forEach((el) => {
        gsap.to(el, {
            scale: 1.07,
            duration: 5,
            ease: 'sine.inOut',
            repeat: -1,
            yoyo: true,
        });
    });
}

// Gentle vertical drift loop for decorative elements (e.g. the reviews
// watermark, floating quote marks).
function initFloat() {
    document.querySelectorAll('[data-float]').forEach((el) => {
        gsap.to(el, {
            y: -14,
            duration: 3,
            ease: 'sine.inOut',
            repeat: -1,
            yoyo: true,
        });
    });
}

function initHero() {
    const hero = document.querySelector('.hero');
    if (!hero) return;

    const tl = gsap.timeline({ defaults: { ease: EASE, duration: 0.9 } });

    tl.fromTo(hero.querySelector('.hero__eyebrow'), { opacity: 0, y: 16 }, { opacity: 1, y: 0 })
        .fromTo(hero.querySelector('.hero__title'), { opacity: 0, y: 24 }, { opacity: 1, y: 0 }, '-=0.6')
        .fromTo(hero.querySelector('.hero__subtitle'), { opacity: 0, y: 18 }, { opacity: 1, y: 0 }, '-=0.55')
        .fromTo(hero.querySelectorAll('.hero__actions > *'), { opacity: 0, y: 14 }, { opacity: 1, y: 0, stagger: 0.1 }, '-=0.45');
}

function initPageBanner() {
    document.querySelectorAll('.page-banner__content').forEach((content) => {
        const targets = content.querySelectorAll(':scope > *');
        if (!targets.length) return;

        gsap.fromTo(
            targets,
            { opacity: 0, y: 22 },
            { opacity: 1, y: 0, duration: 0.9, ease: EASE, stagger: 0.12, delay: 0.15 }
        );
    });
}

export function initAnimations() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.querySelectorAll('[data-reveal], [data-reveal-stagger] > *').forEach((el) => {
            el.style.opacity = '1';
        });
        return;
    }

    initHero();
    initPageBanner();
    initReveals();
    initRevealStagger();
    initParallax();
    initBreathing();
    initFloat();

    window.addEventListener('load', () => {
        ScrollTrigger.refresh();
        reconcileEndOfPage();
    });
}
