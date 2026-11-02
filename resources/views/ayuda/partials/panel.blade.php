{{-- Panel lateral del botón "?": ayuda de la pantalla actual. Comportamiento: resources/js/ayuda.js · Estilos: resources/sass/admin/_ayuda.scss --}}
@php $temasPantalla = \App\Support\Ayuda::dePantalla(request(), auth()->user()); @endphp
<aside class="offcanvas offcanvas-end ayuda-panel" tabindex="-1" id="ayudaPanel" aria-labelledby="ayudaPanelTitulo" data-ayuda-panel>
    <div class="offcanvas-header">
        <h2 class="offcanvas-title" id="ayudaPanelTitulo">Ayuda de esta pantalla</h2>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar ayuda"></button>
    </div>
    <div class="offcanvas-body">
        @forelse ($temasPantalla as $clave => $tema)
            @include('ayuda.partials.tema', ['clave' => $clave, 'tema' => $tema, 'abierto' => $loop->first])
        @empty
            <p class="ayuda-panel__vacio">En el centro de ayuda puedes buscar lo que necesitas.</p>
        @endforelse
    </div>
    <div class="ayuda-panel__pie">
        <a href="{{ route('ayuda.index') }}" class="btn btn-sm btn-primary"><i class="fas fa-book-open" aria-hidden="true"></i> Ver toda la ayuda</a>
        @if (\App\Support\Ayuda::guiaDisponible())
            <a href="{{ route('ayuda.guia') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file-pdf" aria-hidden="true"></i> Guía en PDF</a>
        @endif
    </div>
</aside>
