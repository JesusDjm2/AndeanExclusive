{{--
    Mensaje de Reservas al proveedor de un servicio (MensajeProveedorController@crear):
    Redactor de correos profesional y compacto, con plantillas, orden PDF, adjuntos desde PC,
    registro rápido de respuestas (WhatsApp/teléfono) y timeline de actividad.
    Envío: App\Support\CorreoReservas · Estilos: resources/sass/admin/_mensajes.scss
--}}
@extends('layouts.app')
@section('titulo', 'Mensajería con Proveedor')

@php
    $programa = $servicio->programa;
    $proveedor = $servicio->proveedor;
    $gestiona = auth()->user()->can('servicios.gestionar');
    $estados = \App\Models\Servicio::ESTADOS;
    $efectos = [
        'solicitado' => 'Al enviarlo, el servicio pasará a «Solicitado».',
        'cancelado' => 'Al enviarlo, el servicio pasará a «Cancelado».',
    ];
    $actual = old('tipo', $tipo);
    $activeTab = (old('resultado') || (isset($errors) && ($errors->has('resultado') || $errors->has('confirmacion')))) ? 'reply' : 'compose';
    $telefonoLimpio = $proveedor?->telefono ? preg_replace('/[^0-9]/', '', $proveedor->telefono) : null;
@endphp

@section('contenido')
    {{-- Barra de navegación y contexto superior --}}
    <div class="msg-nav-bar d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('reservas.index', ['q' => $programa->codigo, 'periodo' => 'todos']) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="fas fa-arrow-left"></i> <span>Volver a Reservas</span>
            </a>
            <div class="msg-nav-breadcrumbs text-muted small d-none d-md-flex align-items-center gap-2 ms-2">
                <span class="crumb-code">{{ $programa->codigo }}</span>
                <i class="fas fa-chevron-right text-muted" style="font-size: 0.65rem;"></i>
                <span class="crumb-service text-truncate" style="max-width: 280px;" title="{{ $servicio->titulo ?: $servicio->tipo_etiqueta }}">
                    {{ $servicio->titulo ?: $servicio->tipo_etiqueta }}
                </span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="svc-badge svc-badge--{{ $servicio->estado }}">{{ $estados[$servicio->estado] ?? $servicio->estado }}</span>
            @if ($servicio->confirmacion)
                <span class="badge bg-light text-dark border d-inline-flex align-items-center gap-1" title="Código de confirmación del proveedor">
                    <i class="fas fa-check text-success"></i> Conf: <strong>{{ $servicio->confirmacion }}</strong>
                </span>
            @endif
        </div>
    </div>

    {{-- Alertas del Sistema --}}
    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
        @if (session($key))
            <div class="alert alert-{{ $type }} alert-dismissible fade show py-2 px-3 small shadow-sm mb-3" role="alert">
                <i class="fas fa-{{ $type === 'success' ? 'check-circle' : 'exclamation-triangle' }} me-1"></i>
                {{ session($key) }}
                <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
    @endforeach

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger py-2 px-3 small shadow-sm mb-3" role="alert">
            <div class="fw-bold mb-1"><i class="fas fa-exclamation-circle me-1"></i> Revisa los siguientes campos:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Layout Principal en Dos Columnas --}}
    <div class="msg-layout">
        {{-- ================================================================= --}}
        {{-- COLUMNA PRINCIPAL: REDACCIÓN Y RESPUESTA                          --}}
        {{-- ================================================================= --}}
        <div class="msg-layout__main">
            @if (! $proveedor)
                <div class="card dash-card">
                    <div class="admin-empty py-4 text-center">
                        <i class="fas fa-user-plus fa-2x text-muted mb-2" aria-hidden="true"></i>
                        <p class="mb-1 fw-bold">Este servicio no tiene proveedor asignado</p>
                        <small class="text-muted">Asígnale un proveedor en el área de Reservas para habilitar el envío de correos y la orden de servicio.</small>
                    </div>
                </div>
            @elseif ($gestiona)
                <div class="card dash-card msg-unified-card">
                    {{-- Selector de Pestañas --}}
                    <div class="msg-unified-header">
                        <div class="msg-tabs-nav" role="tablist">
                            <button type="button" class="msg-tab-btn {{ $activeTab === 'compose' ? 'is-active' : '' }}" data-msg-tab="compose" role="tab">
                                <i class="fas fa-paper-plane"></i> Redactar correo
                            </button>
                            <button type="button" class="msg-tab-btn {{ $activeTab === 'reply' ? 'is-active' : '' }}" data-msg-tab="reply" role="tab">
                                <i class="fas fa-reply"></i> Registrar respuesta
                                <span class="msg-tab-pill">WhatsApp / Teléfono</span>
                            </button>
                        </div>
                        <div class="d-none d-lg-flex align-items-center text-muted small">
                            <i class="fas fa-shield-alt text-primary me-1"></i> Envío: <strong class="ms-1">{{ config('mail.reservas.address') }}</strong>
                        </div>
                    </div>

                    {{-- ------------------------------------------------------------- --}}
                    {{-- PANEL 1: REDACTAR CORREO AL PROVEEDOR                         --}}
                    {{-- ------------------------------------------------------------- --}}
                    <div class="msg-tab-panel {{ $activeTab === 'compose' ? 'is-active' : '' }}" id="pane-compose">
                        <form method="POST" action="{{ route('servicios.mensaje.enviar', $servicio) }}" enctype="multipart/form-data" data-msg-compose>
                            @csrf

                            {{-- Barra de Plantillas Rápidas --}}
                            <div class="msg-template-bar">
                                <span class="msg-template-bar__label"><i class="fas fa-bolt text-warning me-1"></i> Plantilla:</span>
                                <div class="msg-template-chips" role="radiogroup" aria-label="Plantillas predefinidas">
                                    @foreach (\App\Support\CorreoReservas::PLANTILLAS as $clave => [$etiqueta, $conOrden, $efecto])
                                        <label class="msg-chip {{ $clave === 'cancelacion' ? 'msg-chip--danger' : '' }}" title="{{ $efectos[$efecto] ?? '' }}">
                                            <input type="radio" name="tipo" value="{{ $clave }}" class="visually-hidden" @checked($actual === $clave)
                                                data-asunto="{{ $plantillas[$clave]['asunto'] }}" data-cuerpo="{{ $plantillas[$clave]['cuerpo'] }}"
                                                data-orden="{{ $conOrden ? '1' : '0' }}" data-efecto="{{ $efectos[$efecto] ?? '' }}">
                                            <span>
                                                @if ($clave === 'solicitud') <i class="fas fa-calendar-plus"></i>
                                                @elseif ($clave === 'reiteracion') <i class="fas fa-redo-alt"></i>
                                                @elseif ($clave === 'modificacion') <i class="fas fa-pencil-alt"></i>
                                                @elseif ($clave === 'confirmacion') <i class="fas fa-check-circle"></i>
                                                @elseif ($clave === 'cancelacion') <i class="fas fa-ban"></i>
                                                @else <i class="fas fa-envelope"></i>
                                                @endif
                                                {{ $etiqueta }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Bloque de Campos Integrados (Para, Cc, Asunto) --}}
                            <div class="msg-fields-group">
                                {{-- Fila Para --}}
                                <div class="msg-row">
                                    <label for="msg-para" class="msg-row__label">Para</label>
                                    <div class="msg-row__input-wrap">
                                        <input id="msg-para" type="text" name="para" value="{{ old('para', $proveedor->correo) }}" required maxlength="500"
                                            class="form-control form-control-sm msg-inline-input @error('para') is-invalid @enderror"
                                            placeholder="correo@proveedor.com, reservas@proveedor.com">
                                        @unless ($correoValido)
                                            <div class="msg-no-email-alert">
                                                <i class="fas fa-exclamation-triangle"></i> Sin correo en ficha.
                                                @can('proveedores.gestionar')
                                                    <a href="{{ route('proveedors.edit', $proveedor) }}" target="_blank">Completar ficha</a>
                                                @endcan
                                            </div>
                                        @endunless
                                    </div>
                                    <div class="msg-row__actions">
                                        <button type="button" class="msg-btn-toggle-cc {{ old('cc') || (isset($errors) && $errors->has('cc')) ? 'is-active' : '' }}" id="btn-toggle-cc" title="Agregar destinatarios en copia">
                                            <i class="fas fa-plus me-1" style="font-size: 0.62rem;"></i>Cc
                                        </button>
                                        <span class="msg-from-chip d-none d-sm-inline-flex" title="Se envía desde {{ config('mail.reservas.address') }}">
                                            De: {{ config('mail.reservas.name') }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Fila Cc (oculta por defecto, se expande con botón o si hay valor previo) --}}
                                <div class="msg-row msg-row--cc {{ old('cc') || (isset($errors) && $errors->has('cc')) ? 'is-visible' : '' }}" id="row-cc">
                                    <label for="msg-cc" class="msg-row__label">Cc</label>
                                    <div class="msg-row__input-wrap">
                                        <input id="msg-cc" type="text" name="cc" value="{{ old('cc') }}" maxlength="500"
                                            class="form-control form-control-sm msg-inline-input @error('cc') is-invalid @enderror"
                                            placeholder="operaciones@andeanexclusivetours.com, gerencia@...">
                                    </div>
                                    <div class="msg-row__actions">
                                        <button type="button" class="btn-close msg-btn-close-cc" id="btn-close-cc" aria-label="Ocultar CC" title="Ocultar CC"></button>
                                    </div>
                                </div>

                                {{-- Fila Asunto --}}
                                <div class="msg-row">
                                    <label for="msg-asunto" class="msg-row__label">Asunto</label>
                                    <div class="msg-row__input-wrap">
                                        <input id="msg-asunto" type="text" name="asunto" value="{{ old('asunto', $plantillas[$actual]['asunto'] ?? '') }}" required maxlength="255"
                                            class="form-control form-control-sm msg-inline-input fw-semibold @error('asunto') is-invalid @enderror" data-msg-asunto>
                                    </div>
                                </div>
                            </div>

                            {{-- Editor de Contenido --}}
                            <div class="msg-editor-wrap">
                                <textarea id="msg-cuerpo" name="cuerpo" rows="8" required maxlength="10000"
                                    class="form-control msg-textarea @error('cuerpo') is-invalid @enderror" data-msg-cuerpo
                                    placeholder="Redacta las indicaciones o consulta para el proveedor...">{{ old('cuerpo', $plantillas[$actual]['cuerpo'] ?? '') }}</textarea>
                            </div>

                            {{-- Barra Inferior de Acciones / Adjuntos / Envío --}}
                            <div class="msg-action-bar">
                                <div class="msg-action-bar__left">
                                    {{-- Checkbox PDF Orden Oficial --}}
                                    <label class="msg-addon-checkbox" title="Adjuntar la orden de servicio oficial en PDF con la lista de pasajeros">
                                        <input type="hidden" name="con_orden" value="0">
                                        <input type="checkbox" name="con_orden" value="1" data-msg-orden
                                            @checked(old('con_orden', (\App\Support\CorreoReservas::PLANTILLAS[$actual][1] ?? false) ? '1' : '0') === '1')>
                                        <span class="msg-addon-checkbox__box">
                                            <i class="fas fa-file-pdf text-danger"></i>
                                            <span>Orden oficial (PDF)</span>
                                        </span>
                                    </label>

                                    {{-- Adjunto de PC --}}
                                    <label class="msg-addon-file" title="Subir archivo desde tu computadora (PDF, Word, Excel, imagen)">
                                        <input type="file" name="archivo_adjunto" id="msg-archivo"
                                            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp,.zip" class="visually-hidden">
                                        <span class="msg-addon-file__btn">
                                            <i class="fas fa-paperclip text-primary"></i>
                                            <span>Adjuntar de PC</span>
                                        </span>
                                    </label>

                                    {{-- Chip de Archivo Cargado --}}
                                    <div class="msg-file-badge d-none" id="msg-file-badge">
                                        <i class="fas fa-file-alt text-primary"></i>
                                        <span class="msg-file-name" id="msg-file-name">archivo</span>
                                        <span class="msg-file-size text-muted" id="msg-file-size"></span>
                                        <button type="button" class="msg-file-remove" id="btn-remove-file" title="Quitar archivo">&times;</button>
                                    </div>
                                </div>

                                <div class="msg-action-bar__right">
                                    <span class="msg-effect-badge" data-msg-efecto>{{ $efectos[\App\Support\CorreoReservas::PLANTILLAS[$actual][2] ?? ''] ?? '' }}</span>
                                    <button type="submit" class="btn btn-primary msg-btn-submit" data-msg-enviar>
                                        <i class="fas fa-paper-plane me-1" aria-hidden="true"></i> Enviar correo
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- ------------------------------------------------------------- --}}
                    {{-- PANEL 2: REGISTRAR RESPUESTA MANUAL DEL PROVEEDOR             --}}
                    {{-- ------------------------------------------------------------- --}}
                    <div class="msg-tab-panel {{ $activeTab === 'reply' ? 'is-active' : '' }}" id="pane-reply">
                        <form method="POST" action="{{ route('servicios.mensaje.respuesta', $servicio) }}" class="msg-reply-form">
                            @csrf
                            <div class="msg-reply-intro">
                                <div class="msg-reply-intro__icon"><i class="fas fa-headset"></i></div>
                                <div class="msg-reply-intro__text">
                                    <h4 class="msg-reply-intro__title">Registrar respuesta del proveedor</h4>
                                    <p class="msg-reply-intro__desc">
                                        Si el proveedor respondió por WhatsApp, teléfono o correo externo, registra aquí su respuesta. El estado del servicio se actualizará automáticamente y quedará guardado en el historial.
                                    </p>
                                </div>
                            </div>

                            {{-- Rejilla Interactiva de Resultados --}}
                            <div class="msg-result-grid" role="radiogroup" aria-label="Resultado de la respuesta">
                                @foreach (\App\Models\MensajeProveedor::RESULTADOS as $clave => $etiqueta)
                                    @php
                                        $icon = match($clave) {
                                            'confirmado' => 'fa-check-circle text-success',
                                            'reconfirmado' => 'fa-check-double text-info',
                                            'no_disponible' => 'fa-times-circle text-danger',
                                            'anulado' => 'fa-ban text-secondary',
                                            default => 'fa-comment-dots text-primary',
                                        };
                                    @endphp
                                    <label class="msg-result-card msg-result-card--{{ $clave }}">
                                        <input type="radio" name="resultado" value="{{ $clave }}" @checked(old('resultado') === $clave) required class="visually-hidden">
                                        <div class="msg-result-card__content">
                                            <i class="fas {{ $icon }} msg-result-card__icon"></i>
                                            <span class="msg-result-card__label">{{ $etiqueta }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="row g-2 mt-2">
                                <div class="col-md-4">
                                    <label for="msg-conf" class="form-label form-label-sm fw-semibold mb-1">
                                        N.º Confirmación / Voucher
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="fas fa-hashtag text-muted"></i></span>
                                        <input id="msg-conf" type="text" name="confirmacion" value="{{ old('confirmacion', $servicio->confirmacion) }}" maxlength="80"
                                            class="form-control form-control-sm @error('confirmacion') is-invalid @enderror" placeholder="Si confirmó">
                                    </div>
                                    <small class="text-muted" style="font-size: 0.72rem;">Requerido si el servicio fue confirmado.</small>
                                </div>
                                <div class="col-md-8">
                                    <label for="msg-detalle" class="form-label form-label-sm fw-semibold mb-1">
                                        Detalle o notas de la respuesta
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="fas fa-info-circle text-muted"></i></span>
                                        <input id="msg-detalle" type="text" name="detalle" value="{{ old('detalle') }}" maxlength="2000" class="form-control form-control-sm"
                                            placeholder="Ej. Chofer Juan Pérez +51 984..., van H1, recojo 07:30 am">
                                    </div>
                                    <small class="text-muted" style="font-size: 0.72rem;">Notas operativas, horarios o condiciones indicadas por el proveedor.</small>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                                <span class="text-muted small"><i class="fas fa-magic text-primary me-1"></i> Actualiza el estado en Reservas en tiempo real.</span>
                                <button type="submit" class="btn btn-sm btn-primary px-3 shadow-sm">
                                    <i class="fas fa-check me-1"></i> Guardar respuesta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="card dash-card">
                    <div class="admin-empty py-4 text-center">
                        <i class="fas fa-lock fa-2x text-muted mb-2" aria-hidden="true"></i>
                        <p class="mb-1 fw-bold">Acceso de solo lectura</p>
                        <small class="text-muted">Los mensajes a proveedores son enviados por el área de Reservas. Aquí puedes consultar el historial completo.</small>
                    </div>
                </div>
            @endif
        </div>

        {{-- ================================================================= --}}
        {{-- COLUMNA LATERAL: RESUMEN DEL SERVICIO E HISTORIAL DE ACTIVIDAD    --}}
        {{-- ================================================================= --}}
        <aside class="msg-layout__side">
            {{-- Tarjeta Resumen del Servicio --}}
            <div class="card dash-card msg-summary-card">
                <div class="msg-summary-card__header">
                    <span class="badge bg-dark-subtle text-dark border fw-bold">{{ $programa->codigo }}</span>
                    <span class="svc-badge svc-badge--{{ $servicio->estado }}">{{ $estados[$servicio->estado] ?? $servicio->estado }}</span>
                </div>
                <div class="msg-summary-card__body">
                    <h3 class="msg-summary-card__title">{{ $servicio->titulo ?: $servicio->tipo_etiqueta }}</h3>

                    <div class="msg-summary-grid">
                        <div class="summary-row">
                            <i class="fas fa-calendar-alt summary-row__icon"></i>
                            <div class="summary-row__content">
                                <span class="summary-row__label">Fecha del servicio</span>
                                <div>{{ $servicio->fecha->locale('es')->isoFormat('D [de] MMMM YYYY') }}@if ($servicio->hora) · <strong>{{ substr((string) $servicio->hora, 0, 5) }} h</strong>@endif</div>
                            </div>
                        </div>

                        <div class="summary-row">
                            <i class="fas fa-users summary-row__icon"></i>
                            <div class="summary-row__content">
                                <span class="summary-row__label">Pasajeros</span>
                                <div>{{ $programa->paxs->count() }} {{ $programa->paxs->count() === 1 ? 'pasajero' : 'pasajeros' }} @if($programa->cliente) · {{ $programa->cliente->nombre }} @endif</div>
                            </div>
                        </div>

                        <div class="summary-row">
                            <i class="fas fa-building summary-row__icon"></i>
                            <div class="summary-row__content">
                                <span class="summary-row__label">Proveedor asignado</span>
                                @if ($proveedor)
                                    <div class="fw-semibold">{{ $proveedor->nombre }}</div>
                                    <div class="msg-contact-actions">
                                        @if ($telefonoLimpio && strlen($telefonoLimpio) >= 8)
                                            <a href="https://wa.me/{{ $telefonoLimpio }}?text={{ urlencode('Hola ' . $proveedor->nombre . ', te escribo de Andean Exclusive Tours sobre el servicio ' . ($servicio->titulo ?: $servicio->tipo_etiqueta) . ' del programa ' . $programa->codigo) }}"
                                                target="_blank" class="msg-btn-whatsapp" title="Conversar por WhatsApp">
                                                <i class="fab fa-whatsapp"></i> <span>WhatsApp</span>
                                            </a>
                                        @endif
                                        @if ($proveedor->telefono)
                                            <a href="tel:{{ $proveedor->telefono }}" class="msg-btn-call" title="Llamar al proveedor">
                                                <i class="fas fa-phone-alt"></i> <span>{{ $proveedor->telefono }}</span>
                                            </a>
                                        @endif
                                    </div>
                                    @if ($proveedor->correo)
                                        <div class="text-muted small mt-1 text-truncate" title="{{ $proveedor->correo }}">
                                            <i class="fas fa-envelope me-1"></i> {{ $proveedor->correo }}
                                        </div>
                                    @endif
                                @else
                                    <span class="text-danger small fw-semibold">Sin proveedor asignado</span>
                                @endif
                            </div>
                        </div>

                        <div class="summary-row">
                            <i class="fas fa-folder summary-row__icon"></i>
                            <div class="summary-row__content">
                                <span class="summary-row__label">Programa</span>
                                <div>
                                    <a href="{{ route('programas.ficha', $programa) }}" target="_blank" class="text-decoration-none fw-semibold">
                                        {{ $programa->nombre }} <i class="fas fa-external-link-alt ms-1 text-muted" style="font-size: 0.72rem;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tarjeta Hilo / Historial de Mensajes --}}
            <div class="card dash-card msg-thread-card">
                <div class="msg-thread-card__header">
                    <h3><i class="fas fa-history text-primary"></i> Historial de actividad</h3>
                    <span class="msg-thread-card__count">{{ $servicio->mensajes->count() }}</span>
                </div>

                <div class="msg-thread-scroll">
                    @forelse ($servicio->mensajes as $mensaje)
                        <article class="msg-bubble msg-bubble--{{ $mensaje->direccion }} {{ $mensaje->fallido ? 'is-failed' : '' }}">
                            <header class="msg-bubble__header">
                                <span class="msg-bubble__tag msg-bubble__tag--{{ $mensaje->direccion }}">
                                    @if ($mensaje->direccion === 'enviado')
                                        <i class="fas fa-arrow-up"></i> {{ \App\Models\MensajeProveedor::TIPOS[$mensaje->tipo] ?? 'Enviado' }}
                                    @else
                                        <i class="fas fa-arrow-down"></i> Respuesta
                                    @endif
                                </span>
                                <time class="msg-bubble__time" datetime="{{ $mensaje->created_at->toIso8601String() }}" title="{{ $mensaje->created_at->format('d/m/Y H:i') }}">
                                    {{ $mensaje->created_at->locale('es')->diffForHumans() }}
                                </time>
                            </header>

                            @if ($mensaje->direccion === 'recibido')
                                <div class="msg-bubble__title text-success">
                                    <i class="fas fa-check-circle me-1"></i> {{ \App\Models\MensajeProveedor::RESULTADOS[$mensaje->resultado] ?? $mensaje->resultado }}
                                </div>
                                @if ($mensaje->cuerpo && $mensaje->cuerpo !== (\App\Models\MensajeProveedor::RESULTADOS[$mensaje->resultado] ?? null))
                                    <div class="msg-bubble__body">{!! nl2br(e($mensaje->cuerpo)) !!}</div>
                                @endif
                            @else
                                <div class="msg-bubble__title">{{ $mensaje->asunto }}</div>
                                <div class="msg-bubble__body">{!! nl2br(e($mensaje->cuerpo)) !!}</div>
                            @endif

                            <footer class="msg-bubble__footer">
                                <span><i class="fas fa-user-circle me-1"></i> {{ $mensaje->user?->name ?? 'Sistema' }}</span>

                                @if ($mensaje->para)
                                    <span class="msg-meta-pill" title="Destinatarios: {{ $mensaje->para }}">
                                        <i class="fas fa-envelope me-1"></i> {{ Str::limit($mensaje->para, 25) }}
                                    </span>
                                @endif

                                @if ($mensaje->cc)
                                    <span class="msg-meta-pill" title="En copia: {{ $mensaje->cc }}">
                                        <i class="fas fa-copy me-1"></i> Cc: {{ Str::limit($mensaje->cc, 20) }}
                                    </span>
                                @endif

                                @if ($mensaje->con_orden)
                                    <span class="msg-meta-pill text-danger" title="Orden oficial adjunta en el correo">
                                        <i class="fas fa-file-pdf"></i> Orden PDF
                                    </span>
                                @endif

                                @if ($mensaje->adjunto_nombre)
                                    <a href="{{ route('mensajes.adjunto', $mensaje) }}" class="msg-meta-pill msg-meta-pill--link" title="Descargar archivo adjunto">
                                        <i class="fas fa-paperclip me-1"></i> {{ Str::limit($mensaje->adjunto_nombre, 18) }} <i class="fas fa-download ms-1"></i>
                                    </a>
                                @endif

                                @if ($mensaje->fallido)
                                    <span class="badge bg-danger text-white ms-auto" title="{{ $mensaje->error }}">
                                        <i class="fas fa-exclamation-triangle me-1"></i> No enviado
                                    </span>
                                @endif
                            </footer>
                        </article>
                    @empty
                        <div class="msg-thread-empty">
                            <i class="far fa-comments"></i>
                            <div>Aún no hay mensajes registrados con el proveedor sobre este servicio.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // -------------------------------------------------------------------------
    // 1. Cambio de Pestañas (Redactar Correo vs Registrar Respuesta)
    // -------------------------------------------------------------------------
    const tabButtons = document.querySelectorAll('[data-msg-tab]');
    const paneCompose = document.getElementById('pane-compose');
    const paneReply = document.getElementById('pane-reply');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.msgTab;
            tabButtons.forEach(b => b.classList.remove('is-active'));
            btn.classList.add('is-active');

            if (target === 'compose') {
                paneCompose?.classList.add('is-active');
                paneReply?.classList.remove('is-active');
            } else {
                paneReply?.classList.add('is-active');
                paneCompose?.classList.remove('is-active');
            }
        });
    });

    // -------------------------------------------------------------------------
    // 2. Control del Campo C.C. (Expandir / Ocultar)
    // -------------------------------------------------------------------------
    const btnToggleCc = document.getElementById('btn-toggle-cc');
    const btnCloseCc = document.getElementById('btn-close-cc');
    const rowCc = document.getElementById('row-cc');
    const inputCc = document.getElementById('msg-cc');

    if (btnToggleCc && rowCc && inputCc) {
        btnToggleCc.addEventListener('click', () => {
            rowCc.classList.add('is-visible');
            btnToggleCc.classList.add('is-active');
            inputCc.focus();
        });

        btnCloseCc?.addEventListener('click', () => {
            rowCc.classList.remove('is-visible');
            btnToggleCc.classList.remove('is-active');
            inputCc.value = '';
        });
    }

    // -------------------------------------------------------------------------
    // 3. Adjuntos desde PC: Detección y Previsualización con Chip
    // -------------------------------------------------------------------------
    const inputArchivo = document.getElementById('msg-archivo');
    const badgeArchivo = document.getElementById('msg-file-badge');
    const nameArchivo = document.getElementById('msg-file-name');
    const sizeArchivo = document.getElementById('msg-file-size');
    const btnRemoveArchivo = document.getElementById('btn-remove-file');

    if (inputArchivo && badgeArchivo) {
        inputArchivo.addEventListener('change', () => {
            if (inputArchivo.files && inputArchivo.files[0]) {
                const file = inputArchivo.files[0];
                const sizeKb = file.size / 1024;
                const sizeStr = sizeKb > 1024 ? (sizeKb / 1024).toFixed(1) + ' MB' : Math.round(sizeKb) + ' KB';

                nameArchivo.textContent = file.name;
                sizeArchivo.textContent = '(' + sizeStr + ')';
                badgeArchivo.classList.remove('d-none');
            } else {
                badgeArchivo.classList.add('d-none');
            }
        });

        btnRemoveArchivo?.addEventListener('click', () => {
            inputArchivo.value = '';
            badgeArchivo.classList.add('d-none');
            nameArchivo.textContent = '';
            sizeArchivo.textContent = '';
        });
    }

    // -------------------------------------------------------------------------
    // 4. Plantillas Rápidas y Confirmación de Cancelación
    // -------------------------------------------------------------------------
    const form = document.querySelector('[data-msg-compose]');
    if (!form) return;

    const asunto = form.querySelector('[data-msg-asunto]');
    const cuerpo = form.querySelector('[data-msg-cuerpo]');
    const orden = form.querySelector('[data-msg-orden]');
    const efecto = form.querySelector('[data-msg-efecto]');
    const enviar = form.querySelector('[data-msg-enviar]');

    const aplicarPlantilla = (radio, rellenar) => {
        if (rellenar) {
            if (asunto) asunto.value = radio.dataset.asunto;
            if (cuerpo) cuerpo.value = radio.dataset.cuerpo;
            if (orden) orden.checked = radio.dataset.orden === '1';
        }
        if (efecto) efecto.textContent = radio.dataset.efecto;

        const cancela = radio.value === 'cancelacion';
        if (enviar) {
            enviar.classList.toggle('btn-danger', cancela);
            enviar.classList.toggle('btn-primary', !cancela);
        }

        if (cancela) {
            form.dataset.confirm = 'Se enviará la cancelación al proveedor y el servicio quedará cancelado en el sistema.';
            form.dataset.confirmTitle = '¿Enviar cancelación?';
            form.dataset.confirmButton = 'Sí, enviar cancelación';
            form.dataset.confirmDanger = '';
        } else {
            delete form.dataset.confirm;
            delete form.dataset.confirmTitle;
            delete form.dataset.confirmButton;
            delete form.dataset.confirmDanger;
        }
    };

    form.querySelectorAll('input[name="tipo"]').forEach((radio) => {
        radio.addEventListener('change', () => aplicarPlantilla(radio, true));
        if (radio.checked) aplicarPlantilla(radio, false);
    });
});
</script>
@endpush
