{{--
    Formulario "Solicitar información" con captcha anti-spam. Unifica
    layouts/booking.blade.php y layouts/booking-castellano.blade.php
    (incluido el JS de captcha, antes duplicado literal en ambos archivos).

    @param string $locale 'es'|'en'
    @param \App\Models\Tour|\App\Models\Estour $tour Tour sobre el que se consulta.
    @param bool $showTitle Oculta el título propio cuando la sección ya tiene uno (tours/partials/book-section).
--}}
@php
    $isEs = $locale === 'es';
    $messageRoute = $isEs ? 'mensaje02' : 'mensaje01';
@endphp
<div class="booking-form" @if ($showTitle ?? true) data-reveal @endif>
    @if ($showTitle ?? true)
        <h3 class="booking-form__title font-display">{{ $isEs ? 'Solicitar Información' : 'Request Info!' }}</h3>
    @endif
    <form action="{{ route($messageRoute) }}" method="post" class="row g-3">
        @csrf
        <div class="col-lg-6">
            <span class="form-label">{{ $isEs ? 'Nombre:' : 'Name:' }}</span>
            <input type="text" name="name" required class="form-control" placeholder="{{ $isEs ? 'Requerido*' : 'Required*' }}">
        </div>
        <div class="col-lg-6">
            <span class="form-label">E-Mail:</span>
            <input type="email" class="form-control" required name="email" placeholder="{{ $isEs ? 'Requerido*' : 'Required*' }}">
        </div>
        <div class="col-lg-6">
            <span class="form-label">{{ $isEs ? 'Adultos:' : 'Adults:' }}</span>
            <input class="form-control" name="adultos" type="number" placeholder="0">
        </div>
        <div class="col-lg-6">
            <span class="form-label">{{ $isEs ? 'Niños:' : 'Children:' }}</span>
            <input class="form-control" name="child" type="number" placeholder="0">
        </div>
        <div class="col-lg-6">
            <span class="form-label">{{ $isEs ? 'Fecha de llegada:' : 'Date Arrive:' }}</span>
            <input class="form-control" name="da" type="date">
        </div>
        <div class="col-lg-6">
            <span class="form-label">Tour:</span>
            <input type="text" name="tour" class="form-control" value="{{ $tour->nombre }} - {{ $tour->dias }} {{ $isEs ? 'días' : 'days' }}" readonly>
        </div>
        <div class="col-lg-12">
            <span class="form-label">{{ $isEs ? 'Mensaje:' : 'Send us a message:' }}</span>
            <textarea class="form-control" name="mensaje" rows="4" placeholder="{{ $isEs ? 'Escriba aquí su mensaje' : 'Message' }}"></textarea>
        </div>
        <div class="col-lg-12">
            <p class="captcha__checkmark" data-captcha-checkmark>{{ $isEs ? 'Respuesta correcta!' : 'Correct answer!' }} &#10004;</p>
            <div class="captcha" data-captcha>
                <span class="captcha__label">{{ $isEs ? 'Resuelve el captcha antes de enviar:' : 'Solve the Captcha before submitting:' }}</span>
                <span data-captcha-num1></span> + <span data-captcha-num2></span> =
                <input type="number" class="captcha__input" data-captcha-answer required>
                <button type="button" class="captcha__verify" data-captcha-verify>{{ $isEs ? 'Verificar' : 'verify' }}</button>
                <input type="hidden" data-captcha-correct>
            </div>
        </div>
        <div class="col-12 text-center">
            <button type="submit" class="booking-form__submit" data-captcha-submit disabled>{{ $isEs ? 'Enviar' : 'Send' }}</button>
        </div>
    </form>
</div>
