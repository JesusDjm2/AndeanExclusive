<?php
$path = 'c:/xampp/htdocs/AndeanExclusive/resources/sass/components/_article.scss';
$content = file_get_contents($path);

$tagStyles = <<<'SCSS'

// ==========================================================================
// TOPIC / TAG COLLECTION VIEW (Senior Editorial Archive)
// ==========================================================================

.tag-hero-meta {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.9);
    margin-top: var(--space-xs);

    span {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .tag-hero-badge {
        color: var(--color-primary);
        font-weight: 700;
        background: rgba(24, 34, 41, 0.65);
        padding: 0.25rem 0.85rem;
        border-radius: var(--radius-pill);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
}

.tag-stories-grid {
    display: grid;
    gap: var(--space-xl);
    grid-template-columns: 1fr;

    @media (min-width: 680px) {
        grid-template-columns: repeat(2, 1fr);
    }
}

.tag-story-card {
    display: flex;
    flex-direction: column;
    background: var(--color-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    border: 1px solid var(--color-border);
    box-shadow: 0 4px 20px -6px rgba(0, 0, 0, 0.05);
    transition: transform var(--duration-fast) var(--ease-premium),
                box-shadow var(--duration-fast) var(--ease-premium),
                border-color var(--duration-fast) var(--ease-premium);

    &:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 36px -12px rgba(0, 0, 0, 0.12);
        border-color: rgba(9, 204, 189, 0.35);

        .tag-story-card__img {
            transform: scale(1.05);
        }

        .tag-story-card__title a {
            color: var(--color-primary);
        }

        .tag-story-card__cta {
            color: var(--color-primary-dark);

            i {
                transform: translateX(4px);
            }
        }
    }
}

.tag-story-card__media {
    position: relative;
    display: block;
    aspect-ratio: 16 / 10;
    overflow: hidden;
    background: var(--color-ink);
    text-decoration: none;
}

.tag-story-card__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform var(--duration-slow) var(--ease-premium);
}

.tag-story-card__reading {
    position: absolute;
    bottom: var(--space-xs);
    right: var(--space-xs);
    background: rgba(24, 34, 41, 0.78);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    color: var(--color-white);
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.25rem 0.65rem;
    border-radius: var(--radius-pill);
    border: 1px solid rgba(255, 255, 255, 0.25);
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.tag-story-card__body {
    padding: var(--space-md) var(--space-lg);
    display: flex;
    flex-direction: column;
    flex-grow: 1;

    @media (max-width: 575px) {
        padding: 1.15rem 1rem;
    }
}

.tag-story-card__meta {
    font-size: 0.74rem;
    font-weight: 700;
    color: var(--color-primary-dark);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: var(--space-2xs);
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.tag-story-card__title {
    font-size: 1.15rem;
    line-height: 1.35;
    font-weight: 700;
    color: var(--color-ink);
    margin-bottom: var(--space-xs);

    a {
        color: inherit;
        text-decoration: none;
        transition: color var(--duration-fast) var(--ease-premium);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
}

.tag-story-card__excerpt {
    font-size: 0.88rem;
    line-height: 1.55;
    color: var(--color-ink-soft);
    margin-bottom: var(--space-md);
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex-grow: 1;

    @media (max-width: 575px) {
        -webkit-line-clamp: 2;
        font-size: 0.84rem;
    }
}

.tag-story-card__footer {
    padding-top: var(--space-xs);
    border-top: 1px solid var(--color-sand-dark);
    margin-top: auto;
}

.tag-story-card__cta {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.84rem;
    font-weight: 700;
    color: var(--color-primary);
    text-decoration: none;
    transition: color var(--duration-fast) var(--ease-premium);

    i {
        transition: transform var(--duration-fast) var(--ease-premium);
    }
}

// Tag cloud card in sidebar
.tag-cloud-card {
    background: var(--color-sand);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
    padding: var(--space-lg);
    margin-top: var(--space-xl);
}

.tag-cloud {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: var(--space-sm);
}

.tag-pill--active {
    background: var(--color-primary) !important;
    color: var(--color-white) !important;
    border-color: var(--color-primary) !important;
    box-shadow: 0 4px 12px -2px rgba(9, 204, 189, 0.4);

    &:hover {
        background: var(--color-primary-dark) !important;
        border-color: var(--color-primary-dark) !important;
        color: var(--color-white) !important;
    }
}

SCSS;

file_put_contents($path, $content . $tagStyles);
echo "Appended tag styles to _article.scss\n";
