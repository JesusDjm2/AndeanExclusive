<?php
$path = 'c:/xampp/htdocs/AndeanExclusive/resources/sass/components/_blog-mosaic.scss';
$content = file_get_contents($path);

// Replace from line 22 (.photo-tile) to line 226 (before .journal-section)
$startToken = ".photo-tile {";
$endToken = "// ==========================================================================\n// LUXURY EDITORIAL JOURNAL";

$posStart = strpos($content, $startToken);
$posEnd = strpos($content, $endToken);

if ($posStart === false || $posEnd === false) {
    // Try with \r\n
    $endToken = "// ==========================================================================\r\n// LUXURY EDITORIAL JOURNAL";
    $posEnd = strpos($content, $endToken);
}

if ($posStart === false || $posEnd === false) {
    die("Tokens not found in _blog-mosaic.scss\n");
}

$replacement = <<<'SCSS'
.photo-tile {
    position: relative;
    display: block;
    aspect-ratio: 1 / 1;
    min-height: 330px;
    overflow: hidden;
    color: var(--color-white);
    text-decoration: none;
    background: var(--color-ink);
    transition: transform var(--duration-fast) var(--ease-premium);

    @media (min-width: 576px) {
        aspect-ratio: 4 / 3;
        min-height: 0;
    }

    @media (min-width: 992px) {
        aspect-ratio: 16 / 10;
    }

    &:hover,
    &:focus-within {
        color: var(--color-white);
        text-decoration: none;
    }

    &:active {
        transform: scale(0.995);
    }
}

// Invisible stretched trigger covering the whole tile — hotel tiles use it
// to open the lightbox gallery instead of linking out, while keeping the
// entire photo clickable and the heading structure intact.
.photo-tile__open {
    position: absolute;
    inset: 0;
    z-index: 2;
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;

    &:focus-visible {
        outline: 3px solid var(--color-primary);
        outline-offset: -3px;
    }
}

.photo-tile__img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--duration-slow) var(--ease-premium);
}

.photo-tile:hover .photo-tile__img,
.photo-tile:focus-within .photo-tile__img {
    transform: scale(1.06);
}

// Slim bottom scrim at rest...
.photo-tile::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(24, 34, 41, 0) 45%, rgba(24, 34, 41, 0.88) 100%);
    transition: opacity var(--duration-base) var(--ease-premium);
}

// Mobile and touch devices: calibrated multi-stop scrim ensuring crystal clear
// legibility over bright photography when reveal is permanently expanded.
@media (max-width: 767px), (hover: none) {
    .photo-tile::before {
        background: linear-gradient(
            180deg,
            rgba(15, 23, 42, 0.08) 0%,
            rgba(15, 23, 42, 0.28) 25%,
            rgba(15, 23, 42, 0.8) 60%,
            rgba(15, 23, 42, 0.96) 100%
        );
    }
}

// ...plus a full ink veil that only fades in on hover/focus, so the
// excerpt stays legible over photos of any brightness.
.photo-tile::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(24, 34, 41, 0.55);
    opacity: 0;
    transition: opacity var(--duration-base) var(--ease-premium);
}

.photo-tile:hover::after,
.photo-tile:focus-within::after {
    opacity: 1;
}

.photo-tile__body {
    position: absolute;
    z-index: 1;
    inset-inline: 0;
    bottom: 0;
    padding: var(--space-sm) var(--space-md);

    @media (max-width: 575px) {
        padding: 1.25rem 1.15rem;
    }
}

// Small uppercase accent label — publish date on blog tiles, location on
// hotel tiles.
.photo-tile__meta {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--color-primary-light);
    margin-bottom: 0.35rem;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.7);
}

.photo-tile__title {
    font-size: 1rem;
    line-height: 1.32;
    font-weight: 700;
    color: var(--color-white);
    margin-bottom: 0;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.7);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;

    @media (min-width: 992px) {
        font-size: 1.05rem;
    }
}

.photo-tile__stars {
    display: inline-flex;
    gap: 0.15rem;
    font-size: 0.75rem;
    color: var(--color-primary-light);
    margin-top: 0.3rem;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.6);
}

// Excerpt + CTA expand smoothly on hover/focus; the growing max-height
// nudges the title upward, which doubles as the hover cue.
.photo-tile__reveal {
    max-height: 0;
    opacity: 0;
    overflow: hidden;
    transition: max-height var(--duration-base) var(--ease-premium),
                opacity var(--duration-base) var(--ease-premium);

    .photo-tile:hover &,
    .photo-tile:focus-within & {
        max-height: 10rem;
        opacity: 1;
    }
}

.photo-tile__excerpt {
    font-size: 0.85rem;
    line-height: 1.45;
    color: rgba(255, 255, 255, 0.9);
    margin-top: 0.4rem;
    margin-bottom: 0.6rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;

    @media (min-width: 768px) {
        -webkit-line-clamp: 3;
    }
}

.photo-tile__cta {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.82rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    color: var(--color-white);
    border: 1px solid rgba(255, 255, 255, 0.6);
    border-radius: var(--radius-pill);
    padding: 0.45rem 1.15rem;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    transition: background-color var(--duration-fast) var(--ease-premium),
                border-color var(--duration-fast) var(--ease-premium),
                color var(--duration-fast) var(--ease-premium);

    @media (max-width: 575px) {
        min-height: 42px;
        padding: 0.5rem 1.25rem;
        background: rgba(255, 255, 255, 0.18);
    }

    i {
        transition: transform var(--duration-fast) var(--ease-premium);
    }

    .photo-tile:hover &,
    .photo-tile:focus-within & {
        background: var(--color-primary);
        border-color: var(--color-primary);
    }

    .photo-tile:hover & i {
        transform: translateX(3px);
    }
}

// Touch devices can't hover: keep the excerpt + CTA permanently visible
@media (hover: none) {
    .photo-tile__reveal {
        max-height: none;
        opacity: 1;
    }
}

@media (prefers-reduced-motion: reduce) {
    .photo-tile__img,
    .photo-tile::after,
    .photo-tile__reveal,
    .photo-tile__cta,
    .photo-tile__cta i {
        transition: none;
    }

    .photo-tile:hover .photo-tile__img,
    .photo-tile:focus-within .photo-tile__img {
        transform: none;
    }
}

SCSS;

$newContent = substr($content, 0, $posStart) . $replacement . substr($content, $posEnd);
file_put_contents($path, $newContent);
echo "Updated _blog-mosaic.scss successfully\n";
