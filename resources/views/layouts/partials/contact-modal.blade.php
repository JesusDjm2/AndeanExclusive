{{--
    Modal de contacto global (popup senior). Vive en layouts.public para que
    el botón "Contact" del footer funcione en todas las páginas públicas.
    Se abre con [data-contact-open] (resources/js/contact-modal.js) y se
    reabre solo cuando la sesión trae contact_flash / error (envío del form).

    @param string $locale 'es'|'en'
--}}
@php
    $isEs = ($locale ?? 'es') === 'es';
    $contactAction = $isEs ? 'bookindex2castellano' : 'bookindex2';
    $hasFeedback = session('contact_flash') || session('error');
@endphp
<div class="contact-modal"
    data-contact-modal
    role="dialog"
    aria-modal="true"
    aria-labelledby="contact-modal-title"
    aria-hidden="true"
    @if ($hasFeedback) data-auto-open @endif
    hidden>
    <div class="contact-modal__backdrop" data-contact-close></div>
    <div class="contact-modal__dialog">
        <button type="button" class="contact-modal__close" data-contact-close aria-label="{{ $isEs ? 'Cerrar' : 'Close' }}">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
        <div class="contact-modal__header">
            <span class="section-eyebrow font-display">{{ $isEs ? 'Planifiquemos tu viaje' : "Let's plan your trip" }}</span>
            <h3 class="contact-modal__title" id="contact-modal-title">{{ $isEs ? 'Solicitar Reserva' : 'Request Booking' }}</h3>
            <p class="contact-modal__note">{{ $isEs ? 'Respondemos en menos de un día hábil.' : 'We reply within one business day.' }}</p>
        </div>
        <div class="contact-modal__body">
            @if (session('contact_flash'))
                <div class="alert alert-success" role="status">{{ session('contact_flash') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
            @include('layouts.partials.public-contact-form', ['locale' => $isEs ? 'es' : 'en', 'action' => $contactAction])
        </div>
    </div>
</div>
