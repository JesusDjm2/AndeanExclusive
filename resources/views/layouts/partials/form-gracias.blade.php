{{--
    Lo que ve el visitante tras enviar un formulario, en lugar del formulario.
    @param string $locale 'es'|'en'
--}}
@php $isEs = $locale === 'es'; @endphp
<div class="aet-gracias" role="status" tabindex="-1" data-aet-gracias>
    <span class="aet-gracias__icono" aria-hidden="true"><i class="fa fa-check"></i></span>
    <h3 class="aet-gracias__titulo">{{ $isEs ? '¡Recibimos tu consulta!' : 'We received your request!' }}</h3>
    <p class="aet-gracias__texto">{{ $isEs ? 'Un asesor de viajes en Cusco te responderá por correo en menos de un día hábil. Revisa también tu carpeta de spam.' : 'A travel advisor in Cusco will reply by email within one business day. Please check your spam folder too.' }}</p>
    @php $whatsapp = 'https://wa.me/' . preg_replace('/\D/', '', (string) config('seo.phone')); @endphp
    <a href="{{ $whatsapp }}" class="aet-gracias__enlace" target="_blank" rel="noopener nofollow"><i class="fa fa-whatsapp" aria-hidden="true"></i> {{ $isEs ? '¿Te urge? Escríbenos por WhatsApp' : 'In a hurry? Message us on WhatsApp' }}</a>
</div>
