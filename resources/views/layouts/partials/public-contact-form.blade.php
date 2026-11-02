{{--
    Formulario general de contacto con captcha (sin tour asociado). Se renderiza
    dentro del modal de contacto global (contact-modal.blade.php), abierto desde
    el footer y los CTA. Comparte el mismo JS de captcha que public-booking-form.

    @param string $locale 'es'|'en'
    @param string $action Nombre de ruta a la que se envia el formulario.
--}}
@php
    $isEs = $locale === 'es';
@endphp
<div class="booking-form">
    <h3 class="booking-form__title font-display">{{ $isEs ? 'Solicitar Reserva' : 'Request Booking' }}</h3>
    <form action="{{ route($action) }}" method="post" class="booking-form__grid">
        @csrf
        <div class="booking-form__field booking-form__field--full">
            <span class="form-label">{{ $isEs ? 'Nombre:' : 'Name:' }}</span>
            <input type="text" name="nombre" required class="form-control" value="{{ old('nombre') }}">
        </div>
        <div class="booking-form__field">
            <span class="form-label">E-Mail:</span>
            <input type="email" class="form-control" required name="email" value="{{ old('email') }}">
        </div>
        <div class="booking-form__field">
            <span class="form-label">{{ $isEs ? 'Teléfono:' : 'Phone:' }}</span>
            <input type="text" class="form-control" required name="telefono" value="{{ old('telefono') }}">
        </div>
        <div class="booking-form__field booking-form__field--full">
            <span class="form-label">{{ $isEs ? 'Mensaje:' : 'Send us a message:' }}</span>
            <textarea class="form-control" name="mensaje" rows="3">{{ old('mensaje') }}</textarea>
        </div>
        <div class="booking-form__field booking-form__field--full">
            <p class="captcha__checkmark" data-captcha-checkmark>{{ $isEs ? 'Respuesta correcta!' : 'Correct answer!' }} &#10004;</p>
            <div class="captcha" data-captcha>
                <span class="captcha__label">{{ $isEs ? 'Resuelve el captcha antes de enviar:' : 'Solve the Captcha before submitting:' }}</span>
                <span data-captcha-num1></span> + <span data-captcha-num2></span> =
                <input type="number" class="captcha__input" data-captcha-answer required>
                <button type="button" class="captcha__verify" data-captcha-verify>{{ $isEs ? 'Verificar' : 'verify' }}</button>
                <input type="hidden" data-captcha-correct>
            </div>
        </div>
        <div class="booking-form__field--full text-center">
            <button type="submit" class="booking-form__submit" data-captcha-submit disabled>{{ $isEs ? 'Enviar' : 'Send' }}</button>
        </div>
    </form>
</div>
