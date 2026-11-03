{{--
    Tarjeta de tour senior para listados por categoría y colecciones públicas.
    Diseño de alta gama, micro-interacciones aceleradas por GPU, credenciales de lujo,
    indicador de precio y doble llamada a la acción (itinerario + cotización directa).

    @param \App\Models\Tour|\App\Models\Estour $tour
    @param string $tourRoute Nombre de ruta para el detalle del tour ('estour.show' o 'tour.show').
    @param string|null $categoryRoute Nombre de ruta para el listado por categoría (opcional).
    @param string|null $daysLabel Etiqueta de días opcional (fallback inteligente).
    @param string|null $locale 'es'|'en'
--}}
@php
    $isEs = ($locale ?? (app()->getLocale() === 'es' || ($daysLabel ?? '') === 'días' || ($daysLabel ?? '') === 'día'));
    $diasNum = (int) filter_var($tour->dias, FILTER_SANITIZE_NUMBER_INT);
    if ($diasNum <= 0) {
        $diasNum = 1;
    }
    $diasTexto = $isEs
        ? ($diasNum === 1 ? '1 día' : "{$diasNum} días")
        : ($diasNum === 1 ? '1 day' : "{$diasNum} days");

    $bucket = $diasNum <= 3 ? 'short' : ($diasNum <= 7 ? 'medium' : 'long');
    $locationText = $tour->recorrido ?: ($isEs ? 'Cusco & Perú' : 'Cusco & Peru');
    $searchBlob = Str::lower($tour->nombre . ' ' . $locationText . ' ' . ($tour->descripcionCorta ?? ''));

    $waMessage = $isEs
        ? "¡Hola! Quisiera recibir información y cotizar el tour: {$tour->nombre}"
        : "Hello! I would like more information and a quote for: {$tour->nombre}";
    $waUrl = 'https://wa.me/51979721194?text=' . rawurlencode($waMessage);
@endphp

<article class="card-tour"
    data-tour-card
    data-days="{{ $diasNum }}"
    data-duration="{{ $bucket }}"
    data-search="{{ $searchBlob }}">

    <div class="card-tour__media">
        <a href="{{ route($tourRoute, $tour->slug) }}" class="card-tour__media-link" tabindex="-1" aria-hidden="true">
            <img src="{{ asset($tour->imgThumb) }}" alt="{{ $tour->nombre }}" loading="lazy">
        </a>
        <div class="card-tour__badges">
            <span class="card-tour__badge card-tour__badge--duration">
                <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $diasTexto }}
            </span>
            <span class="card-tour__badge card-tour__badge--style">
                <i class="fa fa-diamond" aria-hidden="true"></i> {{ $isEs ? '100% Privado' : '100% Private' }}
            </span>
        </div>
    </div>

    <div class="card-tour__body">
        <div class="card-tour__meta-top">
            <span class="card-tour__location">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>{{ $locationText }}</span>
            </span>
            <div class="card-tour__rating" aria-label="{{ $isEs ? 'Calificación 5 de 5 estrellas' : 'Rated 5 out of 5' }}">
                <span class="card-tour__stars" aria-hidden="true">
                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                </span>
                <span class="card-tour__score">5.0</span>
            </div>
        </div>

        <h3 class="card-tour__title">
            <a href="{{ route($tourRoute, $tour->slug) }}" class="card-tour__title-link">
                {{ $tour->nombre }}
            </a>
        </h3>

        @if (!empty($tour->descripcionCorta))
            <p class="card-tour__excerpt">{{ $tour->descripcionCorta }}</p>
        @endif

        <div class="card-tour__chips" aria-label="{{ $isEs ? 'Características clave' : 'Key features' }}">
            <span class="card-tour__chip"><i class="fa fa-check" aria-hidden="true"></i> {{ $isEs ? 'Guía exclusivo' : 'Private guide' }}</span>
            <span class="card-tour__chip"><i class="fa fa-check" aria-hidden="true"></i> {{ $isEs ? 'A tu medida' : 'Bespoke pace' }}</span>
            <span class="card-tour__chip"><i class="fa fa-check" aria-hidden="true"></i> {{ $isEs ? 'Hoteles 5★' : 'Luxury stays' }}</span>
        </div>

        <div class="card-tour__footer">
            <div class="card-tour__pricing">
                @if ($tour->precio > 0)
                    <span class="card-tour__price-label">{{ $isEs ? 'Desde' : 'From' }}</span>
                    <div class="card-tour__price-group">
                        <strong class="card-tour__price-val">US$ {{ number_format($tour->precio, 0) }}</strong>
                        <span class="card-tour__price-per">{{ $isEs ? '/ persona' : '/ person' }}</span>
                    </div>
                @else
                    <span class="card-tour__price-custom">
                        <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                        {{ $isEs ? 'Itinerario a medida' : 'Tailor-made journey' }}
                    </span>
                @endif
            </div>

            <div class="card-tour__actions">
                <a href="{{ route($tourRoute, $tour->slug) }}" class="btn-brand btn-brand--primary btn-brand--sm card-tour__btn-primary">
                    <span>{{ $isEs ? 'Ver itinerario' : 'View itinerary' }}</span>
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                </a>
                <a href="{{ $waUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="card-tour__icon-btn card-tour__icon-btn--wa"
                    title="{{ $isEs ? 'Consultar disponibilidad por WhatsApp' : 'Check availability on WhatsApp' }}"
                    aria-label="{{ $isEs ? 'Consultar disponibilidad por WhatsApp' : 'Check availability on WhatsApp' }}">
                    <i class="fa fa-whatsapp" aria-hidden="true"></i>
                </a>
                <button type="button"
                    class="card-tour__icon-btn card-tour__icon-btn--quote"
                    data-contact-open
                    data-tour-name="{{ $tour->nombre }}"
                    title="{{ $isEs ? 'Cotizar este viaje personalizado' : 'Request bespoke quote' }}"
                    aria-label="{{ $isEs ? 'Cotizar este viaje personalizado' : 'Request bespoke quote' }}">
                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>
</article>
