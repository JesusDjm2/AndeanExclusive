{{-- Protección de los formularios públicos, invisible para las personas: campo trampa y hora de carga cifrada. Ver ConsultaWebController. --}}
@csrf
<input type="hidden" name="_sello" value="{{ \Illuminate\Support\Facades\Crypt::encryptString((string) now()->timestamp) }}">
<div class="aet-trampa" aria-hidden="true">
    <label>No completar este campo <input type="text" name="sitio_web" tabindex="-1" autocomplete="off"></label>
</div>
