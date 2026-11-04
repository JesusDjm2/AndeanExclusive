{{--
    Tarjeta lateral de reserva (sticky): precio, datos, CTAs y garantías.
    @param float|null $price    Precio "desde" por persona; null/0 muestra "a consultar".
    @param array      $facts    [['icon' => 'fa-clock-o', 'value' => '4 días'], ...]
    @param string     $locale
    @param string     $tourName Nombre para el mensaje prellenado de WhatsApp.
--}}
@php
    $whatsappUrl = 'https://wa.me/' . preg_replace('/\D/', '', config('seo.phone'))
        . '?text=' . rawurlencode(__('tour.whatsapp_text', ['tour' => $tourName], $locale));
@endphp
<div class="booking-card" data-reveal>
    <div class="booking-card__price">
        @if (! empty($price))
            <span class="booking-card__from">{{ __('tour.from', [], $locale) }}</span>
            <span class="booking-card__amount">US${{ number_format($price, 0) }}</span>
            <span class="booking-card__unit">{{ __('tour.per_person', [], $locale) }}</span>
        @else
            <span class="booking-card__amount booking-card__amount--muted">{{ __('tour.on_request', [], $locale) }}</span>
        @endif
    </div>

    @if (! empty($facts))
        <ul class="booking-card__facts">
            @foreach ($facts as $fact)
                <li><i class="fa {{ $fact['icon'] }}" aria-hidden="true"></i> {{ $fact['value'] }}</li>
            @endforeach
        </ul>
    @endif

    <div class="booking-card__actions">
        <a href="#book" class="btn-brand btn-brand--primary btn-brand--block">{{ __('tour.request_quote', [], $locale) }}</a>
        <a href="{{ $whatsappUrl }}" class="btn-brand btn-brand--whatsapp btn-brand--block" target="_blank" rel="noopener nofollow">
            <i class="fa fa-whatsapp" aria-hidden="true"></i> {{ __('tour.whatsapp', [], $locale) }}
        </a>
    </div>

    @if (! empty($price))
        <p class="booking-card__note">{{ __('tour.price_note', [], $locale) }}</p>
    @endif

    <ul class="booking-card__trust">
        @foreach (__('tour.trust', [], $locale) as $item)
            <li><i class="fa fa-check" aria-hidden="true"></i> {{ $item }}</li>
        @endforeach
    </ul>
</div>
