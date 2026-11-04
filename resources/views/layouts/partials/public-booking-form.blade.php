{{--
    Consulta sobre un tour (secciones #book de las páginas de tour).
    @param string $locale 'es'|'en'
    @param \App\Models\Tour|\App\Models\Estour $tour Tour sobre el que se consulta.
    @param bool $showTitle Oculta el título propio cuando la sección ya tiene uno (tours/partials/book-section).
--}}
@php
    $isEs = $locale === 'es';
    $form = 'tour';
    $comun = ['form' => $form, 'opcional' => $isEs ? '(opcional)' : '(optional)'];
    $nombreTour = $tour->nombre . ' - ' . $tour->dias . ' ' . ($isEs ? 'días' : 'days');
    $pasos = fn (string $quien) => ['restar' => ($isEs ? 'Quitar ' : 'Remove ') . $quien, 'sumar' => ($isEs ? 'Agregar ' : 'Add ') . $quien];
@endphp
<div class="booking-form" @if ($showTitle ?? true) data-reveal @endif>
    @if (session('form_enviado') === $form)
        @include('layouts.partials.form-gracias', ['locale' => $locale])
    @else
        @if ($showTitle ?? true)
            <h3 class="booking-form__title font-display">{{ $isEs ? 'Solicitar información' : 'Request information' }}</h3>
        @endif
        <form class="aet-form" action="{{ route($isEs ? 'mensaje02' : 'mensaje01') }}" method="post" novalidate data-aet-form
            data-msg-requerido="{{ $isEs ? 'Completa este dato.' : 'Please fill this in.' }}" data-msg-correo="{{ $isEs ? 'Revisa tu correo: parece incompleto.' : 'Please check your email: it looks incomplete.' }}">
            @include('layouts.partials.form-proteccion')
            <input type="hidden" name="tour" value="{{ $nombreTour }}">
            <p class="aet-tema"><i class="fa fa-map-signs" aria-hidden="true"></i> <span>{{ $isEs ? 'Consulta sobre:' : 'Asking about:' }} <strong>{{ $nombreTour }}</strong></span></p>
            @if (session('form_fallo') === $form)
                <p class="aet-alerta" role="alert">{{ $isEs ? 'No pudimos enviar tu consulta. Inténtalo de nuevo o escríbenos por WhatsApp.' : 'We could not send your request. Please try again or message us on WhatsApp.' }}</p>
            @endif
            <div class="aet-form__grid">
                @include('layouts.partials.form-campo', $comun + ['name' => 'nombre', 'label' => $isEs ? 'Tu nombre' : 'Your name', 'required' => true, 'icon' => 'user-o', 'autocomplete' => 'name', 'attrs' => ['maxlength' => 255]])
                @include('layouts.partials.form-campo', $comun + ['name' => 'email', 'label' => $isEs ? 'Tu correo' : 'Your email', 'type' => 'email', 'required' => true, 'icon' => 'envelope-o', 'autocomplete' => 'email', 'placeholder' => $isEs ? 'tu@correo.com' : 'you@email.com', 'attrs' => ['maxlength' => 255]])
                @include('layouts.partials.form-campo', $comun + ['name' => 'telefono', 'label' => $isEs ? 'Teléfono o WhatsApp' : 'Phone or WhatsApp', 'type' => 'tel', 'icon' => 'phone', 'autocomplete' => 'tel', 'attrs' => ['maxlength' => 40]])
                @include('layouts.partials.form-campo', $comun + ['name' => 'da', 'label' => $isEs ? 'Fecha de llegada' : 'Arrival date', 'type' => 'date', 'hint' => $isEs ? 'Aproximada, si aún no la tienes clara.' : 'An estimate is fine.', 'attrs' => ['min' => now()->toDateString()]])
                @include('layouts.partials.form-campo', $comun + $pasos($isEs ? 'un adulto' : 'one adult') + ['name' => 'adultos', 'label' => $isEs ? 'Adultos' : 'Adults', 'type' => 'stepper', 'required' => true, 'inicial' => 2, 'attrs' => ['min' => 1, 'max' => 99]])
                @include('layouts.partials.form-campo', $comun + $pasos($isEs ? 'un niño' : 'one child') + ['name' => 'child', 'label' => $isEs ? 'Niños' : 'Children', 'type' => 'stepper', 'inicial' => 0, 'opcional' => '', 'attrs' => ['min' => 0, 'max' => 99]])
                @include('layouts.partials.form-campo', $comun + ['name' => 'mensaje', 'label' => $isEs ? '¿Qué te gustaría vivir en este viaje?' : 'What would you like from this trip?', 'type' => 'textarea', 'full' => true, 'placeholder' => $isEs ? 'Hoteles, ocasión especial, ritmo del viaje, dudas…' : 'Hotels, special occasion, pace of the trip, questions…'])
            </div>
            <div class="aet-form__pie">
                <button type="submit" class="aet-submit" data-aet-enviar data-aet-enviando="{{ $isEs ? 'Enviando…' : 'Sending…' }}"><span data-aet-enviar-texto>{{ $isEs ? 'Enviar consulta' : 'Send request' }}</span></button>
                <p class="aet-form__nota"><i class="fa fa-lock" aria-hidden="true"></i> {{ $isEs ? 'Sin compromiso. Usamos tus datos solo para responderte.' : 'No commitment. We only use your details to reply.' }}</p>
            </div>
        </form>
    @endif
</div>
