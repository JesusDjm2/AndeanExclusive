{{--
    Formulario general de contacto (sin tour asociado). Se muestra dentro de la ventana de contacto (contact-modal.blade.php).
    @param string $locale 'es'|'en'
    @param string $action Nombre de ruta a la que se envía.
--}}
@php
    $isEs = $locale === 'es';
    $form = 'contacto';
    $comun = ['form' => $form, 'opcional' => $isEs ? '(opcional)' : '(optional)'];
@endphp
<div class="booking-form">
    @if (session('form_enviado') === $form)
        @include('layouts.partials.form-gracias', ['locale' => $locale])
    @else
        <form class="aet-form" action="{{ route($action) }}" method="post" novalidate data-aet-form
            data-msg-requerido="{{ $isEs ? 'Completa este dato.' : 'Please fill this in.' }}" data-msg-correo="{{ $isEs ? 'Revisa tu correo: parece incompleto.' : 'Please check your email: it looks incomplete.' }}">
            @include('layouts.partials.form-proteccion')
            @if (session('form_fallo') === $form)
                <p class="aet-alerta" role="alert">{{ $isEs ? 'No pudimos enviar tu consulta. Inténtalo de nuevo o escríbenos por WhatsApp.' : 'We could not send your request. Please try again or message us on WhatsApp.' }}</p>
            @endif
            <div class="aet-form__grid">
                @include('layouts.partials.form-campo', $comun + ['name' => 'nombre', 'label' => $isEs ? 'Tu nombre' : 'Your name', 'required' => true, 'full' => true, 'icon' => 'user-o', 'autocomplete' => 'name', 'attrs' => ['maxlength' => 255]])
                @include('layouts.partials.form-campo', $comun + ['name' => 'email', 'label' => $isEs ? 'Tu correo' : 'Your email', 'type' => 'email', 'required' => true, 'icon' => 'envelope-o', 'autocomplete' => 'email', 'placeholder' => $isEs ? 'tu@correo.com' : 'you@email.com', 'attrs' => ['maxlength' => 255]])
                @include('layouts.partials.form-campo', $comun + ['name' => 'telefono', 'label' => $isEs ? 'Teléfono o WhatsApp' : 'Phone or WhatsApp', 'type' => 'tel', 'icon' => 'phone', 'autocomplete' => 'tel', 'attrs' => ['maxlength' => 40]])
                @include('layouts.partials.form-campo', $comun + ['name' => 'mensaje', 'label' => $isEs ? 'Cuéntanos tu viaje ideal' : 'Tell us about your ideal trip', 'type' => 'textarea', 'full' => true, 'placeholder' => $isEs ? 'Fechas, cuántos viajan y qué les gustaría conocer…' : 'Dates, how many are traveling and what you would like to see…'])
            </div>
            <div class="aet-form__pie">
                <button type="submit" class="aet-submit" data-aet-enviar data-aet-enviando="{{ $isEs ? 'Enviando…' : 'Sending…' }}"><span data-aet-enviar-texto>{{ $isEs ? 'Enviar consulta' : 'Send request' }}</span></button>
                <p class="aet-form__nota"><i class="fa fa-lock" aria-hidden="true"></i> {{ $isEs ? 'Sin compromiso. Usamos tus datos solo para responderte.' : 'No commitment. We only use your details to reply.' }}</p>
            </div>
        </form>
    @endif
</div>
