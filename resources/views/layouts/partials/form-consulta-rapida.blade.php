{{--
    Consulta rápida de la portada: fechas, viajeros y correo, sobre la foto principal.
    @param string $locale 'es'|'en'
--}}
@php
    $isEs = $locale === 'es';
    $form = 'rapida';
    $comun = ['form' => $form, 'opcional' => $isEs ? '(opcional)' : '(optional)'];
    $hoy = now()->toDateString();
    $pasos = fn (string $quien) => ['restar' => ($isEs ? 'Quitar ' : 'Remove ') . $quien, 'sumar' => ($isEs ? 'Agregar ' : 'Add ') . $quien];
@endphp
<div id="consulta-rapida" class="aet-rapida">
    @if (session('form_enviado') === $form)
        @include('layouts.partials.form-gracias', ['locale' => $locale])
    @else
        <form class="aet-form aet-form--rapida" action="{{ route($isEs ? 'book-castellano' : 'bookindex') }}" method="post" novalidate data-aet-form
            data-msg-requerido="{{ $isEs ? 'Completa este dato.' : 'Please fill this in.' }}" data-msg-correo="{{ $isEs ? 'Revisa tu correo: parece incompleto.' : 'Please check your email: it looks incomplete.' }}" data-msg-fechas="{{ $isEs ? 'La salida no puede ser antes de la llegada.' : 'Departure cannot be before arrival.' }}">
            @include('layouts.partials.form-proteccion')
            @if (session('form_fallo') === $form)
                <p class="aet-alerta" role="alert">{{ $isEs ? 'No pudimos enviar tu consulta. Inténtalo de nuevo o escríbenos por WhatsApp.' : 'We could not send your request. Please try again or message us on WhatsApp.' }}</p>
            @endif
            <div class="aet-rapida__campos">
                @include('layouts.partials.form-campo', $comun + ['name' => 'da', 'label' => $isEs ? 'Llegada' : 'Arrival', 'type' => 'date', 'attrs' => ['min' => $hoy, 'data-aet-llegada' => '1']])
                @include('layouts.partials.form-campo', $comun + ['name' => 'do', 'label' => $isEs ? 'Salida' : 'Departure', 'type' => 'date', 'attrs' => ['min' => $hoy, 'data-aet-salida' => '1']])
                @include('layouts.partials.form-campo', $comun + $pasos($isEs ? 'un adulto' : 'one adult') + ['name' => 'adultos', 'label' => $isEs ? 'Adultos' : 'Adults', 'type' => 'stepper', 'required' => true, 'inicial' => 2, 'attrs' => ['min' => 1, 'max' => 99]])
                @include('layouts.partials.form-campo', $comun + $pasos($isEs ? 'un niño' : 'one child') + ['name' => 'child', 'label' => $isEs ? 'Niños' : 'Children', 'type' => 'stepper', 'inicial' => 0, 'opcional' => '', 'attrs' => ['min' => 0, 'max' => 99]])
                @include('layouts.partials.form-campo', $comun + ['name' => 'email', 'label' => $isEs ? 'Tu correo' : 'Your email', 'type' => 'email', 'required' => true, 'icon' => 'envelope-o', 'autocomplete' => 'email', 'placeholder' => $isEs ? 'tu@correo.com' : 'you@email.com', 'attrs' => ['maxlength' => 255]])
                <button type="submit" class="aet-submit aet-submit--rapida" data-aet-enviar data-aet-enviando="{{ $isEs ? 'Enviando…' : 'Sending…' }}">
                    <span data-aet-enviar-texto>{{ $isEs ? 'Consultar disponibilidad' : 'Check availability' }}</span>
                </button>
            </div>
            <p class="aet-rapida__nota"><i class="fa fa-lock" aria-hidden="true"></i> {{ $isEs ? 'Sin compromiso. Te respondemos en menos de un día hábil.' : 'No commitment. We reply within one business day.' }}</p>
        </form>
    @endif
</div>
