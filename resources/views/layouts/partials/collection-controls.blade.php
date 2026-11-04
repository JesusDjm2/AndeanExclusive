{{--
    Barra interactiva de filtros y búsqueda en vivo para listados de tours y colecciones.
    Permite filtrar al instante por duración y buscar por destino/palabras clave sin recargar la página.
    @param string $locale 'es'|'en'
    @param int $totalTours Cantidad total de tours en la colección.
--}}
@php
    $isEs = ($locale ?? 'es') === 'es';
    $total = $totalTours ?? 0;
@endphp

<div class="collection-controls" aria-label="{{ $isEs ? 'Filtros y búsqueda de tours' : 'Tour filters and search' }}">
    <div class="collection-controls__bar">
        <div class="collection-controls__filters" role="group" aria-label="{{ $isEs ? 'Filtrar por duración' : 'Filter by duration' }}">
            <button type="button" class="collection-pill is-active" data-collection-filter="all" aria-pressed="true">
                <span>{{ $isEs ? 'Todos los viajes' : 'All journeys' }}</span>
                <span class="collection-pill__badge" data-collection-count>{{ $total }}</span>
            </button>
            <button type="button" class="collection-pill" data-collection-filter="short" aria-pressed="false">
                <span>{{ $isEs ? '1 a 3 días' : '1 to 3 days' }}</span>
                <small class="collection-pill__sub">{{ $isEs ? 'Escapadas' : 'Day tours' }}</small>
            </button>
            <button type="button" class="collection-pill" data-collection-filter="medium" aria-pressed="false">
                <span>{{ $isEs ? '4 a 7 días' : '4 to 7 days' }}</span>
                <small class="collection-pill__sub">{{ $isEs ? 'Clásicos' : 'Signature' }}</small>
            </button>
            <button type="button" class="collection-pill" data-collection-filter="long" aria-pressed="false">
                <span>{{ $isEs ? '8+ días' : '8+ days' }}</span>
                <small class="collection-pill__sub">{{ $isEs ? 'Grandes viajes' : 'Grand circuits' }}</small>
            </button>
        </div>

        <div class="collection-controls__search-wrap">
            <div class="collection-search">
                <i class="fa fa-search collection-search__icon" aria-hidden="true"></i>
                <input type="search"
                    class="collection-search__input"
                    data-collection-search
                    placeholder="{{ $isEs ? 'Buscar por destino (Cusco, Machu Picchu, Lago Titicaca…)' : 'Search by destination (Cusco, Machu Picchu, Lake Titicaca…)' }}"
                    aria-label="{{ $isEs ? 'Buscar experiencias' : 'Search experiences' }}"
                    autocomplete="off">
                <button type="button" class="collection-search__clear" data-collection-clear aria-label="{{ $isEs ? 'Limpiar búsqueda' : 'Clear search' }}">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
            <button type="button" class="btn-brand btn-brand--accent btn-brand--sm collection-controls__custom-btn" data-contact-open>
                <i class="fa fa-sliders" aria-hidden="true"></i>
                <span>{{ $isEs ? 'Diseñar a medida' : 'Tailor-made' }}</span>
            </button>
        </div>
    </div>

    {{-- Estado cuando el filtro no devuelve coincidencias --}}
    <div class="collection-empty" data-collection-empty hidden>
        <div class="collection-empty__icon" aria-hidden="true">
            <i class="fa fa-compass"></i>
        </div>
        <h3 class="collection-empty__title">
            {{ $isEs ? 'No encontramos itinerarios con esos criterios' : 'No itineraries matched your search' }}
        </h3>
        <p class="collection-empty__desc">
            {{ $isEs ? '¡No te preocupes! Ningún viaje es idéntico a otro. Cuéntanos tus fechas y lugares soñados y nuestros Travel Designers en Cusco diseñarán tu recorrido privado desde cero.' : 'No worries! No two trips are ever identical. Share your dates and wishlist, and our Cusco-based Travel Designers will craft your private journey from scratch.' }}
        </p>
        <div class="collection-empty__actions">
            <button type="button" class="btn-brand btn-brand--primary" data-contact-open>
                <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                <span>{{ $isEs ? 'Diseñar mi viaje personalizado' : 'Request bespoke itinerary' }}</span>
            </button>
            <button type="button" class="btn-brand btn-brand--ghost" data-collection-clear>
                <i class="fa fa-refresh" aria-hidden="true"></i>
                <span>{{ $isEs ? 'Ver todos los tours' : 'View all tours' }}</span>
            </button>
        </div>
    </div>
</div>
