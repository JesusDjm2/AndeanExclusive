<?php
$path = 'c:/xampp/htdocs/AndeanExclusive/resources/sass/components/_article.scss';
$content = file_get_contents($path);

// Replace .article-layout definition (around line 72)
$oldLayout = <<<'SCSS'
.article-layout {
    display: grid;
    gap: var(--space-xl);
    grid-template-columns: 1fr;

    @media (min-width: 992px) {
        grid-template-columns: minmax(0, 1fr) 340px;
    }

    @media (min-width: 1200px) {
        grid-template-columns: minmax(0, 1fr) 380px;
    }
}
SCSS;

$newLayout = <<<'SCSS'
.article-layout {
    display: grid;
    gap: var(--space-xl);
    grid-template-columns: 1fr;
    align-items: start;

    @media (min-width: 992px) {
        grid-template-columns: minmax(0, 1fr) 340px;
    }

    @media (min-width: 1200px) {
        grid-template-columns: minmax(0, 1fr) 380px;
    }
}

// Senior Sticky Sidebar Rail
.article-sidebar {
    display: flex;
    flex-direction: column;
    gap: var(--space-lg);

    @media (min-width: 992px) {
        position: sticky;
        top: calc(var(--header-h, 80px) + 1.5rem);
        align-self: start;
        max-height: calc(100vh - var(--header-h, 80px) - 2.5rem);
        overflow-y: auto;
        overscroll-behavior: contain;
        scrollbar-width: thin;
        scrollbar-color: rgba(24, 34, 41, 0.18) transparent;
        padding-right: 0.35rem;

        &::-webkit-scrollbar {
            width: 4px;
        }

        &::-webkit-scrollbar-thumb {
            background: rgba(24, 34, 41, 0.18);
            border-radius: var(--radius-pill);

            &:hover {
                background: var(--color-primary);
            }
        }
    }
}

.article-sidebar__box {
    background: var(--color-sand);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
    padding: var(--space-md) var(--space-lg);
}

.article-sidebar__stories {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
    margin-top: var(--space-xs);
}

.article-sidebar__quick-cta {
    background: linear-gradient(135deg, rgba(9, 204, 189, 0.08) 0%, rgba(212, 163, 89, 0.1) 100%);
    border-radius: var(--radius-md);
    border: 1px dashed rgba(9, 204, 189, 0.6);
    padding: var(--space-sm) var(--space-md);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.82rem;
    color: var(--color-ink);

    i {
        font-size: 1.25rem;
        color: var(--color-primary-dark);
        flex: none;
    }

    strong {
        display: block;
        color: var(--color-ink);
        font-size: 0.86rem;
        margin-bottom: 0.1rem;
    }
}
SCSS;

// Replace .article-side-card definition (around line 164)
$oldSideCard = <<<'SCSS'
.article-side-card {
    display: flex;
    gap: 0.75rem;
    margin-bottom: var(--space-sm);
    text-decoration: none;
    color: inherit;

    &:hover {
        color: inherit;

        .article-side-card__title {
            color: var(--color-primary);
        }
    }
}

.article-side-card__img {
    flex: none;
    width: 72px;
    height: 72px;
    border-radius: var(--radius-sm);
    object-fit: cover;
}

.article-side-card__title {
    font-size: 0.9rem;
    font-weight: 700;
    line-height: 1.3;
    margin-bottom: 0.25rem;
    transition: color var(--duration-fast) var(--ease-premium);
}

.article-side-card__date {
    font-size: 0.75rem;
    color: var(--color-ink-soft);
}
SCSS;

$newSideCard = <<<'SCSS'
.article-side-card {
    display: flex;
    gap: 0.85rem;
    padding: 0.5rem 0.4rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    color: inherit;
    transition: background-color var(--duration-fast) var(--ease-premium),
                transform var(--duration-fast) var(--ease-premium);

    &:hover {
        background-color: rgba(255, 255, 255, 0.7);
        transform: translateX(3px);
        color: inherit;

        .article-side-card__title {
            color: var(--color-primary);
        }

        .article-side-card__img {
            transform: scale(1.04);
        }
    }
}

.article-side-card__media {
    position: relative;
    width: 68px;
    height: 68px;
    border-radius: var(--radius-sm);
    overflow: hidden;
    flex: none;
    background: var(--color-ink);
}

.article-side-card__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform var(--duration-fast) var(--ease-premium);
}

.article-side-card__info {
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-width: 0;
}

.article-side-card__title {
    font-size: 0.88rem;
    font-weight: 700;
    line-height: 1.35;
    color: var(--color-ink);
    margin-bottom: 0.2rem;
    transition: color var(--duration-fast) var(--ease-premium);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.article-side-card__date {
    font-size: 0.72rem;
    color: var(--color-ink-soft);
    display: flex;
    align-items: center;
    gap: 0.3rem;
}
SCSS;

// Normalize line breaks for search/replace
$contentNorm = str_replace("\r\n", "\n", $content);
$oldLayoutNorm = str_replace("\r\n", "\n", $oldLayout);
$oldSideCardNorm = str_replace("\r\n", "\n", $oldSideCard);

if (strpos($contentNorm, $oldLayoutNorm) !== false) {
    $contentNorm = str_replace($oldLayoutNorm, $newLayout, $contentNorm);
    echo "Replaced article-layout\n";
} else {
    echo "Could not match oldLayout\n";
}

if (strpos($contentNorm, $oldSideCardNorm) !== false) {
    $contentNorm = str_replace($oldSideCardNorm, $newSideCard, $contentNorm);
    echo "Replaced article-side-card\n";
} else {
    echo "Could not match oldSideCard\n";
}

file_put_contents($path, $contentNorm);
echo "Updated _article.scss\n";
