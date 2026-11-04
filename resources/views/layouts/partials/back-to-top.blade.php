{{-- Botón flotante "volver arriba", se apila sobre el botón de WhatsApp.
    La visibilidad y el scroll animado los maneja resources/js/back-to-top.js. --}}
<button type="button" class="to-top" data-to-top aria-label="{{ ($locale ?? 'es') === 'es' ? 'Volver arriba' : 'Back to top' }}">
    <i class="fa fa-chevron-up" aria-hidden="true"></i>
</button>
