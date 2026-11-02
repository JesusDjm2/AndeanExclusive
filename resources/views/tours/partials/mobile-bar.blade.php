{{--
    Barra inferior fija en móvil con precio + CTA. Se oculta cuando el formulario #book está a la vista
    (resources/js/tour-detail.js).
    @param float|null $price
    @param string     $locale
--}}
<div class="tour-mobile-bar" data-tour-mobile-bar>
    <div class="tour-mobile-bar__price">
        @if (! empty($price))
            <small>{{ __('tour.from', [], $locale) }}</small>
            <strong>US${{ number_format($price, 0) }}</strong>
        @else
            <strong>{{ __('tour.on_request', [], $locale) }}</strong>
        @endif
    </div>
    <a href="#book" class="btn-brand btn-brand--primary btn-brand--sm">{{ __('tour.mobile_cta', [], $locale) }}</a>
</div>
