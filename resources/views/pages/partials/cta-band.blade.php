{{--
    Banda final de conversión (fondo oscuro con foto): abre el modal de contacto global o WhatsApp.
    @param string $locale
    @param string $image Ruta relativa a public/ para el fondo.
--}}
@php
    $whatsappUrl = 'https://wa.me/' . preg_replace('/\D/', '', config('seo.phone'))
        . '?text=' . rawurlencode(__('pages.cta.whatsapp_text', [], $locale));
@endphp
<section class="cta-band">
    <img class="cta-band__img" src="{{ asset($image ?? 'img/Fondos/Tours-a-Machu-Picchu-Pacha-Mama.webp') }}" alt="" loading="lazy" decoding="async">
    <div class="cta-band__inner site-container" data-reveal>
        <span class="section-eyebrow">{{ __('pages.cta.eyebrow', [], $locale) }}</span>
        <h2 class="cta-band__title">{{ __('pages.cta.title', [], $locale) }}</h2>
        <p class="cta-band__text">{{ __('pages.cta.text', [], $locale) }}</p>
        <div class="cta-band__actions">
            <button type="button" class="btn-brand btn-brand--primary" data-contact-open>{{ __('pages.cta.contact', [], $locale) }}</button>
            <a href="{{ $whatsappUrl }}" class="btn-brand btn-brand--glass" target="_blank" rel="noopener nofollow">
                <i class="fa fa-whatsapp" aria-hidden="true"></i> {{ __('pages.cta.whatsapp', [], $locale) }}
            </a>
        </div>
    </div>
</section>
