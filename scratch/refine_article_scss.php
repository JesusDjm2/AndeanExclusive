<?php
$path = 'c:/xampp/htdocs/AndeanExclusive/resources/sass/components/_article.scss';
$content = file_get_contents($path);

// 1. Replace the .article-sidebar sticky block
$oldSticky = <<<'SCSS'
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
SCSS;

$newSticky = <<<'SCSS'
// Senior Sticky Sidebar Rail — natural height, zero clipping
.article-sidebar {
    display: flex;
    flex-direction: column;
    gap: var(--space-md);

    @media (min-width: 992px) {
        position: sticky;
        top: calc(var(--header-h-scrolled, 4.5rem) + 1.25rem);
        align-self: start;
    }
}
SCSS;

// 2. Add .article-sidebar__cta-card styles right after .article-sidebar__stories
$oldStories = <<<'SCSS'
.article-sidebar__stories {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
    margin-top: var(--space-xs);
}
SCSS;

$newStories = <<<'SCSS'
.article-sidebar__stories {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
    margin-top: var(--space-xs);
}

// Compact Bespoke CTA Card at bottom of sticky sidebar
.article-sidebar__cta-card {
    background: linear-gradient(135deg, var(--color-white) 0%, var(--color-sand) 100%);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
    padding: 1.25rem;
    box-shadow: 0 8px 24px -8px rgba(0, 0, 0, 0.06);
    position: relative;
    overflow: hidden;

    &::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--color-primary) 0%, var(--color-accent) 100%);
    }
}

.article-sidebar__cta-eyebrow {
    display: block;
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--color-primary-dark);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 0.2rem;
}

.article-sidebar__cta-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--color-ink);
    line-height: 1.3;
    margin-bottom: 0.35rem;
}

.article-sidebar__cta-text {
    font-size: 0.82rem;
    line-height: 1.5;
    color: var(--color-ink-soft);
    margin-bottom: 0.85rem;
}

.article-sidebar__cta-actions {
    display: flex;
    flex-direction: column;
    gap: 0.45rem;
}
SCSS;

// Normalize line endings
$contentNorm = str_replace("\r\n", "\n", $content);
$oldStickyNorm = str_replace("\r\n", "\n", $oldSticky);
$oldStoriesNorm = str_replace("\r\n", "\n", $oldStories);

if (strpos($contentNorm, $oldStickyNorm) !== false) {
    $contentNorm = str_replace($oldStickyNorm, $newSticky, $contentNorm);
    echo "Replaced old sticky rules\n";
} else {
    echo "Could not match old sticky rules\n";
}

if (strpos($contentNorm, $oldStoriesNorm) !== false) {
    $contentNorm = str_replace($oldStoriesNorm, $newStories, $contentNorm);
    echo "Added cta-card rules\n";
} else {
    echo "Could not match old stories rules\n";
}

file_put_contents($path, $contentNorm);
echo "Done updating _article.scss\n";
