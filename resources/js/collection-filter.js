/**
 * Interactive filter and live search for public tour collection views
 * (Exclusivos, Tours en Perú, Aventuras, Categorías).
 *
 * Provides real-time filtering by duration buckets (1-3d, 4-7d, 8+d)
 * and client-side instant search across tour titles, routes, and excerpts.
 */
export function initCollectionFilter() {
    const containers = document.querySelectorAll('[data-collection-container]');
    if (!containers.length) return;

    containers.forEach((container) => {
        const filterBtns = container.querySelectorAll('[data-collection-filter]');
        const searchInput = container.querySelectorAll('[data-collection-search]');
        const clearBtns = container.querySelectorAll('[data-collection-clear]');
        const counterEls = container.querySelectorAll('[data-collection-count]');
        const emptyState = container.querySelector('[data-collection-empty]');
        const cards = container.querySelectorAll('[data-tour-card]');

        if (!cards.length) return;

        let activeBucket = 'all';
        let searchQuery = '';
        let debounceTimer = null;

        const applyFilter = () => {
            let visibleCount = 0;
            const normalizedQuery = searchQuery.trim().toLowerCase();

            cards.forEach((card) => {
                const durationBucket = card.dataset.duration || 'all';
                const searchData = (card.dataset.search || '').toLowerCase();

                const matchesBucket = activeBucket === 'all' || durationBucket === activeBucket;
                const matchesSearch = !normalizedQuery || searchData.includes(normalizedQuery);

                const isVisible = matchesBucket && matchesSearch;

                if (isVisible) {
                    card.hidden = false;
                    card.classList.remove('is-filtered-out');
                    visibleCount++;
                } else {
                    card.hidden = true;
                    card.classList.add('is-filtered-out');
                }
            });

            // Update counter badges
            counterEls.forEach((counter) => {
                counter.textContent = visibleCount;
            });

            // Update empty state
            if (emptyState) {
                emptyState.hidden = visibleCount > 0;
            }

            // Refresh ScrollTrigger positions if available
            if (window.ScrollTrigger) {
                window.ScrollTrigger.refresh();
            }
        };

        // Filter pills click handling
        filterBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                filterBtns.forEach((b) => {
                    b.classList.remove('is-active');
                    b.setAttribute('aria-pressed', 'false');
                });
                btn.classList.add('is-active');
                btn.setAttribute('aria-pressed', 'true');
                activeBucket = btn.dataset.collectionFilter || 'all';
                applyFilter();
            });
        });

        // Search input with fast debounce
        searchInput.forEach((input) => {
            input.addEventListener('input', (e) => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    searchQuery = e.target.value;
                    // Sync other search inputs if any
                    searchInput.forEach((other) => {
                        if (other !== e.target) other.value = e.target.value;
                    });
                    applyFilter();
                }, 120);
            });
        });

        // Clear search / reset filters
        clearBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                searchQuery = '';
                searchInput.forEach((input) => {
                    input.value = '';
                });
                activeBucket = 'all';
                filterBtns.forEach((b) => {
                    const isAll = (b.dataset.collectionFilter || 'all') === 'all';
                    b.classList.toggle('is-active', isAll);
                    b.setAttribute('aria-pressed', isAll ? 'true' : 'false');
                });
                applyFilter();
                searchInput[0]?.focus();
            });
        });
    });
}
