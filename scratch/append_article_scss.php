<?php
$file = 'c:/xampp/htdocs/AndeanExclusive/resources/sass/components/_article.scss';
$extra = <<<'SCSS'

// Editorial Reading Meta in Hero
.article-reading-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: var(--space-md);
    margin-top: var(--space-xs);
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.9);

    span {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .article-reading-meta__badge {
        color: var(--color-primary);
        font-weight: 700;
        background: rgba(24, 34, 41, 0.6);
        padding: 0.2rem 0.7rem;
        border-radius: var(--radius-pill);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
}

// Editorial Article Highlights box (top of article body)
.article-highlights {
    background: linear-gradient(135deg, rgba(9, 204, 189, 0.08) 0%, rgba(212, 163, 89, 0.08) 100%);
    border-left: 4px solid var(--color-primary);
    border-radius: 0 var(--radius-md) var(--radius-md) 0;
    padding: var(--space-md) var(--space-lg);
    margin-bottom: var(--space-xl);

    .article-highlights__eyebrow {
        display: block;
        font-family: var(--font-display);
        font-size: 0.95rem;
        color: var(--color-primary-dark);
        font-weight: 700;
        margin-bottom: var(--space-2xs);
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .article-highlights__lead {
        font-size: 1.15rem;
        font-style: italic;
        color: var(--color-ink);
        line-height: 1.6;
        margin-bottom: 0;
    }
}

// In-article bespoke conversion box (Bottom of article body)
.article-cta-box {
    background: linear-gradient(135deg, #182229 0%, #0f172a 100%);
    border-radius: var(--radius-lg);
    color: var(--color-white);
    padding: var(--space-xl);
    margin-block: var(--space-2xl);
    position: relative;
    overflow: hidden;
    box-shadow: 0 16px 40px -10px rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.1);

    &::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(9, 204, 189, 0.2) 0%, transparent 70%);
        pointer-events: none;
    }

    .article-cta-box__eyebrow {
        color: var(--color-primary);
        font-family: var(--font-display);
        font-size: 1.1rem;
        display: block;
        margin-bottom: var(--space-2xs);
    }

    .article-cta-box__title {
        font-size: clamp(1.35rem, 1.15rem + 0.8vw, 1.75rem);
        color: var(--color-white);
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: var(--space-sm);
    }

    .article-cta-box__text {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.98rem;
        line-height: 1.6;
        margin-bottom: var(--space-lg);
        max-width: 640px;
    }

    .article-cta-box__actions {
        display: flex;
        flex-wrap: wrap;
        gap: var(--space-sm);
        align-items: center;
    }
}

// Author / Curated by card
.article-author-card {
    display: flex;
    align-items: center;
    gap: var(--space-md);
    background: var(--color-sand);
    padding: var(--space-md) var(--space-lg);
    border-radius: var(--radius-md);
    margin-top: var(--space-xl);
    border: 1px solid var(--color-border);

    .article-author-card__avatar {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: var(--color-ink);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color-primary);
        font-size: 1.4rem;
        flex: none;
    }

    .article-author-card__name {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--color-ink);
        margin-bottom: 0.2rem;
    }

    .article-author-card__bio {
        font-size: 0.84rem;
        color: var(--color-ink-soft);
        line-height: 1.45;
        margin-bottom: 0;
    }
}

// Sticky Sidebar Concierge Widget
.sidebar-concierge-card {
    background: linear-gradient(180deg, var(--color-white) 0%, var(--color-sand) 100%);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
    padding: var(--space-lg);
    margin-bottom: var(--space-xl);
    box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.06);
    position: relative;
    overflow: hidden;

    &::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--color-primary) 0%, var(--color-accent) 100%);
    }

    .sidebar-concierge-card__eyebrow {
        color: var(--color-primary-dark);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: block;
        margin-bottom: 0.25rem;
    }

    .sidebar-concierge-card__title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--color-ink);
        line-height: 1.3;
        margin-bottom: var(--space-xs);
    }

    .sidebar-concierge-card__desc {
        font-size: 0.85rem;
        color: var(--color-ink-soft);
        line-height: 1.5;
        margin-bottom: var(--space-md);
    }

    .sidebar-concierge-card__features {
        list-style: none;
        padding: 0;
        margin: 0 0 var(--space-md) 0;
        font-size: 0.82rem;
        color: var(--color-ink);

        li {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            margin-bottom: 0.4rem;

            i {
                color: var(--color-primary);
                font-size: 0.85rem;
            }
        }
    }

    .sidebar-concierge-card__btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .sidebar-concierge-card__wa {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
}

// Embedded article images
.legal-prose img {
    max-width: 100%;
    height: auto;
    border-radius: var(--radius-md);
    margin-block: var(--space-md);
    box-shadow: 0 8px 24px -6px rgba(0, 0, 0, 0.08);
}
SCSS;

file_put_contents($file, $extra, FILE_APPEND);
echo "Appended to _article.scss successfully.\n";
