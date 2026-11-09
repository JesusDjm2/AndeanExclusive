{{--
    Mensaje de Reservas al proveedor de un servicio (MensajeProveedorController@crear): plantillas editables,
    orden de servicio adjunta, registro de la respuesta y el hilo completo con el proveedor.
    Envío: App\Support\CorreoReservas · Estilos: resources/sass/admin/_mensajes.scss
--}}
@extends('layouts.app')
@section('titulo', 'Mensaje al proveedor')

@php
    $programa = $servicio->programa;
    $proveedor = $servicio->proveedor;
    $gestiona = auth()->user()->can('servicios.gestionar');
    $estados = \App\Models\Servicio::ESTADOS;
    $efectos = [
        'solicitado' => 'Al enviarlo, el servicio pasa a «Solicitado».',
        'cancelado' => 'Al enviarlo, el servicio pasa a «Cancelado».',
    ];
    $actual = old('tipo', $tipo);
@endphp

@section('contenido')
    <div class="ae-admin-page-header">
        <h1 class="ae-admin-page-title"><i class="fas fa-fw fa-envelope-open-text"></i> Mensaje al proveedor</h1>
        <small class="ae-admin-page-desc">Se envía desde {{ config('mail.reservas.address') }}; las respuestas del proveedor llegan a esa casilla.</small>
    </div>

    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
        @if (session($key))
            <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
    @endforeach
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="msg-layout">
        <div class="msg-layout__main">
            @if (! $proveedor)
                <div class="card dash-card"><div class="admin-empty"><i class="fas fa-user-plus" aria-hidden="true"></i> Este servicio no tiene proveedor: asígnalo en la Bandeja de Reservas para poder escribirle.</div></div>
            @elseif ($gestiona)
                <form method="POST" action="{{ route('servicios.mensaje.enviar', $servicio) }}" class="card dash-card msg-compose" data-msg-compose>
                    @csrf
                    <div class="msg-compose__types" role="radiogroup" aria-label="Tipo de mensaje">
                        @foreach (\App\Support\CorreoReservas::PLANTILLAS as $clave => [$etiqueta, $conOrden, $efecto])
                            <label class="msg-type {{ $clave === 'cancelacion' ? 'msg-type--danger' : '' }}">
                                <input type="radio" name="tipo" value="{{ $clave }}" class="visually-hidden" @checked($actual === $clave)
                                    data-asunto="{{ $plantillas[$clave]['asunto'] }}" data-cuerpo="{{ $plantillas[$clave]['cuerpo'] }}"
                                    data-orden="{{ $conOrden ? '1' : '0' }}" data-efecto="{{ $efectos[$efecto] ?? '' }}">
                                <span>{{ $etiqueta }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="msg-compose__body">
                        <div class="row g-2">
                            <div class="col-md-7">
                                <label for="msg-para" class="prog-label">Para</label>
                                <input id="msg-para" type="email" name="para" value="{{ old('para', $proveedor->correo) }}" required maxlength="255"
                                    class="form-control @error('para') is-invalid @enderror" placeholder="correo@proveedor.com">
                                @unless ($correoValido)
                                    <small class="text-warning-emphasis"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> El proveedor no tiene un correo válido en su ficha.
                                        @can('proveedores.gestionar')<a href="{{ route('proveedors.edit', $proveedor) }}">Agregarlo</a>@endcan</small>
                                @endunless
                            </div>
                            <div class="col-md-5">
                                <span class="prog-label d-block">De</span>
                                <div class="msg-compose__from">{{ config('mail.reservas.name') }} &lt;{{ config('mail.reservas.address') }}&gt;</div>
                            </div>
                            <div class="col-12">
                                <label for="msg-asunto" class="prog-label">Asunto</label>
                                <input id="msg-asunto" type="text" name="asunto" value="{{ old('asunto', $plantillas[$actual]['asunto']) }}" required maxlength="255"
                                    class="form-control @error('asunto') is-invalid @enderror" data-msg-asunto>
                            </div>
                            <div class="col-12">
                                <label for="msg-cuerpo" class="prog-label">Mensaje</label>
                                <textarea id="msg-cuerpo" name="cuerpo" rows="14" required maxlength="10000"
                                    class="form-control msg-compose__text @error('cuerpo') is-invalid @enderror" data-msg-cuerpo>{{ old('cuerpo', $plantillas[$actual]['cuerpo']) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="msg-compose__footer">
                        <div class="msg-compose__options">
                            <label class="form-check mb-0">
                                <input type="hidden" name="con_orden" value="0">
                                <input type="checkbox" class="form-check-input" name="con_orden" value="1" data-msg-orden
                                    @checked(old('con_orden', \App\Support\CorreoReservas::PLANTILLAS[$actual][1] ? '1' : '0') === '1')>
                                <span class="form-check-label"><i class="fas fa-file-pdf text-danger" aria-hidden="true"></i> Adjuntar la orden de servicio (PDF con pasajeros)</span>
                            </label>
                            <small class="msg-compose__efecto" data-msg-efecto>{{ $efectos[\App\Support\CorreoReservas::PLANTILLAS[$actual][2]] ?? '' }}</small>
                        </div>
                        <button type="submit" class="btn btn-primary" data-msg-enviar>
                            <i class="fas fa-paper-plane me-1" aria-hidden="true"></i> Enviar al proveedor
                        </button>
                    </div>
                </form>

                <form method="POST" action="{{ route('servicios.mensaje.respuesta', $servicio) }}" class="card dash-card msg-reply">
                    @csrf
                    <h2 class="msg-card__title"><i class="fas fa-reply" aria-hidden="true"></i> Registrar la respuesta del proveedor</h2>
                    <p class="msg-card__hint">Cuando el proveedor responda a {{ config('mail.reservas.address') }} o por teléfono, déjalo aquí: el servicio se actualiza solo.</p>
                    <div class="msg-reply__options" role="radiogroup" aria-label="Qué respondió el proveedor">
                        @foreach (\App\Models\MensajeProveedor::RESULTADOS as $clave => $etiqueta)
                            <label class="msg-choice">
                                <input type="radio" name="resultado" value="{{ $clave }}" @checked(old('resultado') === $clave) required>
                                <span>{{ $etiqueta }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label for="msg-conf" class="prog-label">N.º de confirmación</label>
                            <input id="msg-conf" type="text" name="confirmacion" value="{{ old('confirmacion', $servicio->confirmacion) }}" maxlength="80"
                                class="form-control @error('confirmacion') is-invalid @enderror" placeholder="Si confirmó">
                        </div>
                        <div class="col-md-8">
                            <label for="msg-detalle" class="prog-label">Detalle (opcional)</label>
                            <input id="msg-detalle" type="text" name="detalle" value="{{ old('detalle') }}" maxlength="2000" class="form-control"
                                placeholder="Ej. Confirmó van H1 con chofer Juan, recojo 07:30">
                        </div>
                    </div>
                    <div class="text-end mt-2">
                        <button type="submit" class="btn btn-outline-primary btn-sm"><i class="fas fa-check me-1" aria-hidden="true"></i> Registrar respuesta</button>
                    </div>
                </form>
            @else
                <div class="card dash-card"><div class="admin-empty"><i class="fas fa-lock" aria-hidden="true"></i> Los mensajes a proveedores los envía Reservas. Aquí puedes consultar el hilo.</div></div>
            @endif
        </div>

        <aside class="msg-layout__side">
            <div class="card dash-card msg-summary">
                <span class="msg-summary__code">{{ $programa->codigo }}</span>
                <h2 class="msg-summary__title">{{ $servicio->titulo ?: $servicio->tipo_etiqueta }}</h2>
                <p class="msg-summary__meta">{{ $servicio->fecha->locale('es')->isoFormat('dddd D [de] MMMM YYYY') }}@if ($servicio->hora) · {{ substr((string) $servicio->hora, 0, 5) }} h @endif</p>
                <dl class="msg-summary__list">
                    <dt>Estado</dt>
                    <dd><span class="svc-badge svc-badge--{{ $servicio->estado }}">{{ $estados[$servicio->estado] ?? $servicio->estado }}</span></dd>
                    @if ($servicio->confirmacion)
                        <dt>Confirmación</dt><dd>{{ $servicio->confirmacion }}</dd>
                    @endif
                    <dt>Proveedor</dt>
                    <dd>
                        @if ($proveedor)
                            <a href="{{ route('proveedors.show', $proveedor) }}">{{ $proveedor->nombre }}</a>
                            @if ($proveedor->telefono)<small class="d-block">{{ $proveedor->telefono }}</small>@endif
                        @else
                            Sin asignar
                        @endif
                    </dd>
                    <dt>Programa</dt>
                    <dd><a href="{{ route('programas.ficha', $programa) }}">{{ $programa->nombre }}</a></dd>
                </dl>
                <a href="{{ route('reservas.index', ['q' => $programa->codigo, 'periodo' => 'todos']) }}" class="msg-summary__back"><i class="fas fa-arrow-left me-1" aria-hidden="true"></i> Volver a Reservas</a>
            </div>

            <div class="card dash-card msg-thread">
                <h2 class="msg-card__title"><i class="fas fa-comments" aria-hidden="true"></i> Hilo con el proveedor <span class="msg-thread__count">{{ $servicio->mensajes->count() }}</span></h2>
                @forelse ($servicio->mensajes as $mensaje)
                    <article class="msg-bubble msg-bubble--{{ $mensaje->direccion }} {{ $mensaje->fallido ? 'is-failed' : '' }}">
                        <header class="msg-bubble__head">
                            <strong>{{ \App\Models\MensajeProveedor::TIPOS[$mensaje->tipo] ?? $mensaje->tipo }}</strong>
                            <time datetime="{{ $mensaje->created_at->toIso8601String() }}" title="{{ $mensaje->created_at->format('d/m/Y H:i') }}">{{ $mensaje->created_at->locale('es')->diffForHumans() }}</time>
                        </header>
                        @if ($mensaje->direccion === 'recibido')
                            <p class="msg-bubble__result">{{ \App\Models\MensajeProveedor::RESULTADOS[$mensaje->resultado] ?? '' }}</p>
                            @if ($mensaje->cuerpo !== (\App\Models\MensajeProveedor::RESULTADOS[$mensaje->resultado] ?? null))
                                <p class="msg-bubble__text">{{ $mensaje->cuerpo }}</p>
                            @endif
                        @else
                            <details class="msg-bubble__details">
                                <summary>{{ $mensaje->asunto }}</summary>
                                <div class="msg-bubble__text">{!! nl2br(e($mensaje->cuerpo)) !!}</div>
                            </details>
                        @endif
                        <footer class="msg-bubble__foot">
                            {{ $mensaje->user?->name ?? 'Sistema' }}
                            @if ($mensaje->para) · a {{ $mensaje->para }} @endif
                            @if ($mensaje->con_orden) · <i class="fas fa-paperclip" aria-hidden="true"></i> orden adjunta @endif
                            @if ($mensaje->fallido)<span class="msg-bubble__error" title="{{ $mensaje->error }}"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> No se envió</span>@endif
                        </footer>
                    </article>
                @empty
                    <p class="msg-thread__empty">Todavía no hay mensajes con el proveedor sobre este servicio.</p>
                @endforelse
            </div>
        </aside>
    </div>
@endsection

@push('scripts')
<script>
// Cambiar de plantilla rellena asunto, mensaje, adjunto y el aviso de lo que pasará con el servicio.
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-msg-compose]');
    if (!form) return;
    const asunto = form.querySelector('[data-msg-asunto]');
    const cuerpo = form.querySelector('[data-msg-cuerpo]');
    const orden = form.querySelector('[data-msg-orden]');
    const efecto = form.querySelector('[data-msg-efecto]');
    const enviar = form.querySelector('[data-msg-enviar]');

    const aplicar = (radio, rellenar) => {
        if (rellenar) {
            asunto.value = radio.dataset.asunto;
            cuerpo.value = radio.dataset.cuerpo;
            orden.checked = radio.dataset.orden === '1';
        }
        efecto.textContent = radio.dataset.efecto;
        const cancela = radio.value === 'cancelacion';
        enviar.classList.toggle('btn-danger', cancela);
        enviar.classList.toggle('btn-primary', !cancela);
        if (cancela) {
            form.dataset.confirm = 'Se enviará la cancelación al proveedor y el servicio quedará cancelado.';
            form.dataset.confirmTitle = '¿Cancelar la reserva?';
            form.dataset.confirmButton = 'Sí, cancelar';
            form.dataset.confirmDanger = '';
        } else {
            delete form.dataset.confirm;
            delete form.dataset.confirmTitle;
            delete form.dataset.confirmButton;
            delete form.dataset.confirmDanger;
        }
    };

    form.querySelectorAll('input[name="tipo"]').forEach((radio) => {
        radio.addEventListener('change', () => aplicar(radio, true));
        if (radio.checked) aplicar(radio, false);
    });
});
</script>
@endpush
