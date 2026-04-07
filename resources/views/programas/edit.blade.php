@extends('layouts.app')
@section('titulo', 'Editar Programa')
@section('contenido')
    <div class="container mb-4">
        <h3 class="mb-4"><small>Editar programa: </small> {{ $programa->nombre }}</h3>

        {{-- =========================== MOSTRAR ERRORES GENERALES ============================ --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>¡Por favor corrige los siguientes errores!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('programas.update', $programa) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- =========================== DATOS BÁSICOS DEL PROGRAMA ============================ --}}
            <h5 class="text-primary mb-3">Programa:</h5>
            <div class="mb-3">
                <label class="form-label font-weight-bold">Nombre:</label>
                <input type="text" name="nombre" value="{{ old('nombre', $programa->nombre) }}"
                    class="form-control form-control-sm @error('nombre') is-invalid @enderror" required>
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Código:</label>
                        <input type="text" name="codigo" value="{{ old('codigo', $programa->codigo) }}"
                            class="form-control form-control-sm @error('codigo') is-invalid @enderror" required>
                        @error('codigo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Email de contacto:</label>
                        <input type="email" name="email" value="{{ old('email', $programa->email) }}"
                            class="form-control form-control-sm @error('email') is-invalid @enderror"
                            placeholder="contacto@ejemplo.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Idioma:</label>
                        <select name="lang" class="form-control form-control-sm @error('lang') is-invalid @enderror">
                            <option value="">Seleccionar idioma</option>
                            <option value="es" {{ old('lang', $programa->lang) == 'es' ? 'selected' : '' }}>Español
                            </option>
                            <option value="en" {{ old('lang', $programa->lang) == 'en' ? 'selected' : '' }}>Inglés
                            </option>
                            <option value="pt" {{ old('lang', $programa->lang) == 'pt' ? 'selected' : '' }}>Portugués
                            </option>
                            <option value="fr" {{ old('lang', $programa->lang) == 'fr' ? 'selected' : '' }}>Francés
                            </option>
                        </select>
                        @error('lang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Fecha Inicio:</label>
                        <input type="date" name="inicio" value="{{ old('inicio', $programa->inicio) }}"
                            class="form-control form-control-sm @error('inicio') is-invalid @enderror">
                        @error('inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Fecha Fin:</label>
                        <input type="date" name="fin" value="{{ old('fin', $programa->fin) }}"
                            class="form-control form-control-sm @error('fin') is-invalid @enderror">
                        @error('fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- =========================== SECCIÓN DE PRECIOS ============================ --}}
            <h5 class="text-primary mb-3 mt-4">Precios:</h5>
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Precio Adulto ($):</label>
                        <input type="number" name="precioAdulto"
                            value="{{ old('precioAdulto', $programa->precioAdulto) }}"
                            class="form-control form-control-sm @error('precioAdulto') is-invalid @enderror" step="0.01"
                            min="0" placeholder="0.00">
                        @error('precioAdulto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Precio Niño ($):</label>
                        <input type="number" name="precioChild" value="{{ old('precioChild', $programa->precioChild) }}"
                            class="form-control form-control-sm @error('precioChild') is-invalid @enderror" step="0.01"
                            min="0" placeholder="0.00">
                        @error('precioChild')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- =========================== AGENTES ASIGNADOS ============================ --}}
            <h5 class="text-primary mb-3 mt-4">Agentes asignados:</h5>
            <div class="mb-3">
                <label class="form-label font-weight-bold">Asignar agente(s):</label>
                @error('agentes')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
                <div class="row">
                    @foreach ($agentes as $agente)
                        <div class="col-6 col-md-4">
                            <label class="d-flex align-items-center gap-2">
                                <input type="checkbox" name="agentes[]" value="{{ $agente->id }}"
                                    {{ in_array($agente->id, old('agentes', $agentesAsignados)) ? 'checked' : '' }}>
                                {{ $agente->nombre }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- =========================== PROVEEDORES ============================ --}}
            {{-- =========================== PROVEEDORES ============================ --}}
            <h5 class="text-primary mb-3 mt-4">Proveedores:</h5>

            @error('proveedores')
                <div class="alert alert-danger py-2">{{ $message }}</div>
            @enderror

            <div class="row">
                @foreach ($proveedoresPorCategoria as $categoria => $proveedores)
                    <div class="col-md-12 mb-4">
                        <div class="card border-0 bg-light">
                            <strong class="text-dark">{{ $categoria }}(s)</strong>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($proveedores as $proveedor)
                                        <div class="col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="proveedores[]"
                                                    value="{{ $proveedor->id }}" id="prov_{{ $proveedor->id }}"
                                                    {{ in_array($proveedor->id, old('proveedores', $proveedoresSeleccionados)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="prov_{{ $proveedor->id }}">
                                                    {{ $proveedor->nombre }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- =========================== DESCRIPCIÓN DEL TOUR ============================ --}}
            <h5 class="text-primary mb-3 mt-4">Descripción del Tour:</h5>
            <div class="mb-3">
                <label class="form-label font-weight-bold">Detalles del Tour (itinerario día a día):</label>
                <textarea name="presentacion" id="presentacion"
                    class="ckeditor form-control form-control-sm @error('presentacion') is-invalid @enderror" rows="4">{{ old('presentacion', $programa->presentacion) }}</textarea>
                @error('presentacion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- =========================== HOTELES ============================ --}}
            <h5 class="text-primary mb-3 mt-4">Hoteles:</h5>
            @error('habitaciones')
                <div class="text-danger small mb-2">{{ $message }}</div>
            @enderror
            @error('habitaciones.*')
                <div class="text-danger small mb-2">{{ $message }}</div>
            @enderror

            <div class="row">
                @foreach ($hoteles as $hotel)
                    <div class="col-lg-4 mb-3">
                        <div class="accordion" id="accordionHotel{{ $hotel->id }}">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingHotel{{ $hotel->id }}">
                                    <button class="accordion-button collapsed fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseHotel{{ $hotel->id }}"
                                        aria-expanded="false" aria-controls="collapseHotel{{ $hotel->id }}">
                                        {{ $hotel->nombre }}
                                    </button>
                                </h2>

                                <div id="collapseHotel{{ $hotel->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="headingHotel{{ $hotel->id }}"
                                    data-bs-parent="#accordionHotel{{ $hotel->id }}">
                                    <div class="accordion-body">
                                        @if ($hotel->habitaciones->isEmpty())
                                            <div class="text-muted fst-italic">
                                                Sin habitaciones designadas
                                            </div>
                                        @else
                                            @foreach ($hotel->habitaciones as $habitacion)
                                                <div class="form-check">
                                                    <input class="form-check-input habitacion-check" type="checkbox"
                                                        name="habitaciones[]" value="{{ $habitacion->id }}"
                                                        id="hab_{{ $habitacion->id }}" data-id="{{ $habitacion->id }}"
                                                        {{ in_array($habitacion->id, old('habitaciones', $habitacionesSeleccionadas ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="hab_{{ $habitacion->id }}">
                                                        {{ $habitacion->tipo }}
                                                    </label>
                                                </div>

                                                <!-- CONTENEDOR DE FECHAS -->
                                                <div class="fechas-container ms-4 mb-3" id="fechas_{{ $habitacion->id }}"
                                                    style="{{ in_array($habitacion->id, old('habitaciones', $habitacionesSeleccionadas ?? [])) ? '' : 'display: none;' }}">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <small class="form-label">Fecha de Ingreso</small>
                                                            <input type="date"
                                                                class="form-control form-control-sm fecha-ingreso @error('fechas.' . $habitacion->id . '.ingreso') is-invalid @enderror"
                                                                name="fechas[{{ $habitacion->id }}][ingreso]"
                                                                value="{{ old('fechas.' . $habitacion->id . '.ingreso', isset($fechasPorHabitacion[$habitacion->id]) ? $fechasPorHabitacion[$habitacion->id]->fecha_ingreso : '') }}">
                                                            @error('fechas.' . $habitacion->id . '.ingreso')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <small class="form-label">Fecha de Salida</small>
                                                            <input type="date"
                                                                class="form-control form-control-sm fecha-salida @error('fechas.' . $habitacion->id . '.salida') is-invalid @enderror"
                                                                name="fechas[{{ $habitacion->id }}][salida]"
                                                                value="{{ old('fechas.' . $habitacion->id . '.salida', isset($fechasPorHabitacion[$habitacion->id]) ? $fechasPorHabitacion[$habitacion->id]->fecha_salida : '') }}">
                                                            @error('fechas.' . $habitacion->id . '.salida')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    @error('fechas.' . $habitacion->id)
                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const habitacionCheckboxes = document.querySelectorAll('.habitacion-check');

                    habitacionCheckboxes.forEach(checkbox => {
                        const habitacionId = checkbox.dataset.id;
                        const fechaContainer = document.getElementById('fechas_' + habitacionId);

                        function toggleFechas() {
                            if (fechaContainer) {
                                if (checkbox.checked) {
                                    fechaContainer.style.display = 'block';
                                } else {
                                    fechaContainer.style.display = 'none';
                                    // Limpiar los valores cuando se desmarca
                                    const inputs = fechaContainer.querySelectorAll('input');
                                    inputs.forEach(input => {
                                        input.value = '';
                                    });
                                }
                            }
                        }

                        // Estado inicial
                        toggleFechas();

                        // Event listener
                        checkbox.addEventListener('change', toggleFechas);
                    });
                });
            </script>
            {{-- <h5 class="text-primary mb-3 mt-4">Hoteles:</h5>
            @error('habitaciones')
                <div class="text-danger small mb-2">{{ $message }}</div>
            @enderror
            @error('habitaciones.*')
                <div class="text-danger small mb-2">{{ $message }}</div>
            @enderror
            <div class="row">
                @foreach ($hoteles as $hotel)
                    <div class="col-lg-4 mb-3">
                        <div class="accordion" id="accordionHotel{{ $hotel->id }}">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingHotel{{ $hotel->id }}">
                                    <button class="accordion-button collapsed fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseHotel{{ $hotel->id }}"
                                        aria-expanded="false" aria-controls="collapseHotel{{ $hotel->id }}">
                                        {{ $hotel->nombre }}
                                    </button>
                                </h2>

                                <div id="collapseHotel{{ $hotel->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="headingHotel{{ $hotel->id }}"
                                    data-bs-parent="#accordionHotel{{ $hotel->id }}">
                                    <div class="accordion-body">
                                        @if ($hotel->habitaciones->isEmpty())
                                            <div class="text-muted fst-italic">
                                                Sin habitaciones designadas
                                            </div>
                                        @else
                                            @foreach ($hotel->habitaciones as $habitacion)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="habitaciones[]"
                                                        value="{{ $habitacion->id }}" id="hab_{{ $habitacion->id }}"
                                                        {{ in_array($habitacion->id, old('habitaciones', $habitacionesSeleccionadas)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="hab_{{ $habitacion->id }}">
                                                        {{ $habitacion->tipo }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div> --}}



            @error('paxs')
                <div class="text-danger small mb-2">{{ $message }}</div>
            @enderror
            <h4 class="text-primary mb-2">Pasajeros:</h4>
            <div id="contenedor-paxs">
                @foreach (old('paxs', $paxs) as $i => $pax)
                    <div class="card p-3 mb-3 pax-item">
                        @if (is_object($pax) && $pax->id)
                            <input type="hidden" name="paxs[{{ $i }}][id]" value="{{ $pax->id }}">
                        @endif

                        <div class="d-flex justify-content-between">
                            <h5 class="pax-title">Pax {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</h5>
                            <button type="button" class="btn btn-danger btn-sm btnEliminarPax">Eliminar</button>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-3">
                                <label>Nombre *</label>
                                <input type="text" name="paxs[{{ $i }}][nombre]"
                                    value="{{ old("paxs.$i.nombre", is_object($pax) ? $pax->nombre : $pax['nombre'] ?? '') }}"
                                    class="form-control form-control-sm @error("paxs.$i.nombre") is-invalid @enderror">
                                @error("paxs.$i.nombre")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label>Edad *</label>
                                <input type="number" name="paxs[{{ $i }}][edad]"
                                    value="{{ old("paxs.$i.edad", is_object($pax) ? $pax->edad : $pax['edad'] ?? '') }}"
                                    class="form-control form-control-sm @error("paxs.$i.edad") is-invalid @enderror">
                                @error("paxs.$i.edad")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label>Nacionalidad *</label>
                                <input type="text" name="paxs[{{ $i }}][nacionalidad]"
                                    value="{{ old("paxs.$i.nacionalidad", is_object($pax) ? $pax->nacionalidad : $pax['nacionalidad'] ?? '') }}"
                                    class="form-control form-control-sm @error("paxs.$i.nacionalidad") is-invalid @enderror">
                                @error("paxs.$i.nacionalidad")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Alimentación</label>
                                <input type="text" name="paxs[{{ $i }}][alimentacion]"
                                    value="{{ old("paxs.$i.alimentacion", is_object($pax) ? $pax->alimentacion : $pax['alimentacion'] ?? '') }}"
                                    class="form-control form-control-sm @error("paxs.$i.alimentacion") is-invalid @enderror">
                                @error("paxs.$i.alimentacion")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mt-3">
                                <label>Pasaporte (PDF o imagen)</label>
                                <input type="file" name="paxs[{{ $i }}][pasaporte]"
                                    class="form-control form-control-sm @error("paxs.$i.pasaporte") is-invalid @enderror">
                                @error("paxs.$i.pasaporte")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if (is_object($pax) && $pax->pasaporte)
                                    @php
                                        $ext = pathinfo($pax->pasaporte, PATHINFO_EXTENSION);
                                    @endphp
                                    <div class="mt-2">
                                        @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp', 'gif']))
                                            <img src="{{ asset($pax->pasaporte) }}" class="img-thumbnail"
                                                style="max-height:100px;">
                                        @elseif (strtolower($ext) === 'pdf')
                                            <a href="{{ asset($pax->pasaporte) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                📄 Ver pasaporte (PDF)
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <template id="template-pax">
                <div class="card p-3 mb-3 pax-item">
                    <div class="d-flex justify-content-between">
                        <h5 class="pax-title">Pax __NUM__</h5>
                        <button type="button" class="btn btn-danger btn-sm btnEliminarPax">Eliminar</button>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-3">
                            <label>Nombre *</label>
                            <input type="text" name="paxs[__INDEX__][nombre]" class="form-control form-control-sm">
                            <div class="text-danger small pax-error-__INDEX__-nombre" style="display:none;"></div>
                        </div>

                        <div class="col-md-2">
                            <label>Edad *</label>
                            <input type="number" name="paxs[__INDEX__][edad]" class="form-control form-control-sm">
                            <div class="text-danger small pax-error-__INDEX__-edad" style="display:none;"></div>
                        </div>

                        <div class="col-md-3">
                            <label>Nacionalidad *</label>
                            <input type="text" name="paxs[__INDEX__][nacionalidad]"
                                class="form-control form-control-sm">
                            <div class="text-danger small pax-error-__INDEX__-nacionalidad" style="display:none;"></div>
                        </div>

                        <div class="col-md-4">
                            <label>Alimentación</label>
                            <input type="text" name="paxs[__INDEX__][alimentacion]"
                                class="form-control form-control-sm">
                            <div class="text-danger small pax-error-__INDEX__-alimentacion" style="display:none;"></div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label>Pasaporte (PDF o imagen)</label>
                            <input type="file" name="paxs[__INDEX__][pasaporte]" class="form-control form-control-sm">
                            <div class="text-danger small pax-error-__INDEX__-pasaporte" style="display:none;"></div>
                        </div>
                    </div>
                </div>
            </template>
            {{-- =========================== SECCIÓN PARA CREAR PAXS ============================ --}}
            <div class="d-flex justify-content-between align-items-center mb-2 mt-4">

                <button type="button" id="btnAgregarPax" class="btn btn-primary btn-sm">
                    + Agregar Pax
                </button>
            </div>
            <hr>

            {{-- =========================== BOTONES DE ACCIÓN ============================ --}}
            <div class="d-flex justify-content-between mb-5">
                {{-- Botones flotantes en lado derecho --}}
                <div class="position-fixed end-0 top-50 translate-middle-y me-4" style="z-index: 1000;">
                    {{-- Botón Actualizar --}}
                    <button type="submit" class="btn btn-success shadow-lg py-3 px-4 mb-2 d-block"
                        style="border-radius: 30px 0 0 30px; width: auto; min-width: 140px;">
                        <i class="fas fa-save me-2"></i> Actualizar
                    </button>

                    {{-- Botón Volver --}}
                    <a href="{{ route('programas.index') }}" class="btn btn-danger shadow-lg py-3 px-4 d-block"
                        style="border-radius: 30px 0 0 30px; width: auto; min-width: 140px;">
                        <i class="fas fa-arrow-left me-2"></i> Volver
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- ======================================
    JAVASCRIPT PARA AGREGAR / QUITAR PAXS
    ======================================= --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let contadorPaxs = {{ old('paxs') ? count(old('paxs')) : $paxs->count() }};

            function agregarPax(index) {
                let template = document.getElementById('template-pax').innerHTML;

                template = template.replace(/__INDEX__/g, index);
                const numeroVisual = String(index + 1).padStart(2, '0');
                template = template.replace(/__NUM__/g, numeroVisual);

                const contenedor = document.getElementById('contenedor-paxs');
                contenedor.insertAdjacentHTML('beforeend', template);
            }

            document.getElementById('btnAgregarPax').addEventListener('click', function() {
                agregarPax(contadorPaxs);
                contadorPaxs++;
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('btnEliminarPax')) {
                    e.target.closest('.pax-item').remove();
                }
            });
        });
    </script>
@endsection
