{{--
    Sección #book: formulario completo de consulta (destino de todos los CTA "Reservar").
    @param object $tour   Tour/Estour u objeto con nombre y dias.
    @param string $locale
--}}
<section id="book" class="tour-section tour-book" data-tour-section>
    <div class="tour-book__intro">
        <h2 class="tour-section__title">{{ __('tour.book_title', [], $locale) }}</h2>
        <p>{{ __('tour.book_lead', [], $locale) }}</p>
    </div>
    @include('layouts.partials.public-booking-form', ['locale' => $locale, 'tour' => $tour, 'showTitle' => false])
</section>
