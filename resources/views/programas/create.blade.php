@extends('layouts.app')
@section('titulo', 'Nuevo Programa')
@section('contenido')
    <div class="container">
        <h3 class="mb-4">Crear nuevo programa:</h3>
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

        <form action="{{ route('programas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- =========================== DATOS BÁSICOS DEL PROGRAMA ============================ --}}
            <h5 class="text-primary mb-3">Programa:</h5>
            <div class="mb-3">
                <label class="form-label font-weight-bold">Nombre:</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}"
                    class="form-control form-control-sm @error('nombre') is-invalid @enderror" required>
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Código:</label>
                        <input type="text" name="codigo" value="{{ old('codigo') }}"
                            class="form-control form-control-sm @error('codigo') is-invalid @enderror" required>
                        @error('codigo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Email de contacto:</label>
                        <input type="email" name="email" value="{{ old('email') }}"
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
                        <select name="lang" class="form-control form-control-sm @error('lang') is-invalid @enderror"
                            required>
                            <option value="">Seleccionar idioma</option>
                            <option value="es" {{ old('lang') == 'es' ? 'selected' : '' }}>Español</option>
                            <option value="en" {{ old('lang') == 'en' ? 'selected' : '' }}>Inglés</option>
                            <option value="pt" {{ old('lang') == 'pt' ? 'selected' : '' }}>Portugués</option>
                            <option value="fr" {{ old('lang') == 'fr' ? 'selected' : '' }}>Francés</option>
                        </select>
                        @error('lang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Fecha Inicio:</label>
                        <input type="date" name="inicio" value="{{ old('inicio') }}"
                            class="form-control form-control-sm @error('inicio') is-invalid @enderror">
                        @error('inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Fecha Fin:</label>
                        <input type="date" name="fin" value="{{ old('fin') }}"
                            class="form-control form-control-sm @error('fin') is-invalid @enderror">
                        @error('fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Agente Responsable:</label>
                        <select name="agente_id"
                            class="form-control form-control-sm @error('agente_id') is-invalid @enderror">
                            <option value="">Seleccionar agente principal</option>
                            @foreach ($agentes as $agente)
                                <option value="{{ $agente->id }}"
                                    {{ old('agente_id') == $agente->id ? 'selected' : '' }}>
                                    {{ $agente->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('agente_id')
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
                        <input type="number" name="precioAdulto" value="{{ old('precioAdulto') }}"
                            class="form-control form-control-sm @error('precioAdulto') is-invalid @enderror">
                        @error('precioAdulto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Precio Niño ($):</label>
                        <input type="number" name="precioChild" value="{{ old('precioChild') }}"
                            class="form-control form-control-sm @error('precioChild') is-invalid @enderror">
                        @error('precioChild')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- =========================== AGENTES ASIGNADOS ============================ --}}
            <h5 class="text-primary mb-3 mt-4">Agentes asignados:</h5>
            <div class="mb-3">
                <label class="form-label font-weight-bold">Asignar agentes adicionales:</label>
                @error('agentes')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
                <div class="row">
                    @foreach ($agentes as $agente)
                        <div class="col-6 col-md-4">
                            <label class="d-flex align-items-center gap-2">
                                <input type="checkbox" name="agentes[]" value="{{ $agente->id }}"
                                    {{ in_array($agente->id, old('agentes', [])) ? 'checked' : '' }}>
                                {{ $agente->nombre }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- =========================== PROVEEDORES ============================ --}}
            <h5 class="text-primary mb-3 mt-4">Proveedores:</h5>
            @error('proveedors')
                <div class="text-danger small mb-2">{{ $message }}</div>
            @enderror
            @error('proveedors.*')
                <div class="text-danger small mb-2">{{ $message }}</div>
            @enderror
            @foreach ($proveedorsPorCategoria as $categoria => $proveedors)
                <fieldset class="mb-4">
                    <label class="form-label font-weight-bold">{{ $categoria }}:</label><br>
                    @foreach ($proveedors as $proveedor)
                        <input type="checkbox" name="proveedores[]" value="{{ $proveedor->id }}"
                            {{ in_array($proveedor->id, old('proveedores', [])) ? 'checked' : '' }}>
                        {{ $proveedor->nombre }}
                        <br>
                    @endforeach
                </fieldset>
            @endforeach

            {{-- =========================== DESCRIPCIÓN DEL TOUR ============================ --}}
            <h5 class="text-primary mb-3 mt-4">Descripción del Tour:</h5>
            <div class="mb-3">
                <label class="form-label font-weight-bold">Detalles del Tour (itinerario día a día):</label>
                <textarea name="presentacion" id="presentacion"
                    class="ckeditor form-control form-control-sm @error('presentacion') is-invalid @enderror" rows="4">{{ old('presentacion') }}</textarea>
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
                        <div class="accordion" id="accordionHoteles{{ $hotel->id }}">
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
                                    data-bs-parent="#accordionHoteles{{ $hotel->id }}">
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
                                                        {{ in_array($habitacion->id, old('habitaciones', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="hab_{{ $habitacion->id }}">
                                                        {{ $habitacion->tipo }}
                                                    </label>
                                                </div>

                                                <!-- CONTENEDOR DE FECHAS -->
                                                <div class="fechas-container ms-4 mb-3" id="fechas_{{ $habitacion->id }}"
                                                    style="{{ in_array($habitacion->id, old('habitaciones', [])) ? '' : 'display: none;' }}">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <small class="form-label">Fecha de Ingreso</small>
                                                            <input type="date"
                                                                class="form-control form-control-sm fecha-ingreso @error('fechas.' . $habitacion->id . '.ingreso') is-invalid @enderror"
                                                                name="fechas[{{ $habitacion->id }}][ingreso]"
                                                                value="{{ old('fechas.' . $habitacion->id . '.ingreso') }}">
                                                            @error('fechas.' . $habitacion->id . '.ingreso')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <small class="form-label">Fecha de Salida</small>
                                                            <input type="date"
                                                                class="form-control form-control-sm fecha-salida @error('fechas.' . $habitacion->id . '.salida') is-invalid @enderror"
                                                                name="fechas[{{ $habitacion->id }}][salida]"
                                                                value="{{ old('fechas.' . $habitacion->id . '.salida') }}">
                                                            @error('fechas.' . $habitacion->id . '.salida')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    @error('fechas.' . $habitacion->id)
                                                        <div class="text-danger small mt-1">
                                                            {{ $message }}
                                                        </div>
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
            {{-- =========================== SECCIÓN PARA CREAR PAXS ============================ --}}
            <div class="d-flex justify-content-between align-items-center mb-2 mt-4">
                <h4 class="text-primary">Pasajeros:</h4>
                <button type="button" id="btnAgregarPax" class="btn btn-primary btn-sm">
                    + Agregar Pax
                </button>
            </div>

            @error('paxs')
                <div class="text-danger small mb-2">{{ $message }}</div>
            @enderror

            <div id="contenedor-paxs">
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
                            <div class="text-danger small pax-error-__INDEX__-nacionalidad" style="display:none;">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label>Alimentación</label>
                            <input type="text" name="paxs[__INDEX__][alimentacion]"
                                class="form-control form-control-sm">
                            <div class="text-danger small pax-error-__INDEX__-alimentacion" style="display:none;">
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label>Pasaporte (PDF o imagen)</label>
                            <input type="file" name="paxs[__INDEX__][pasaporte]" class="form-control form-control-sm">
                            <div class="text-danger small pax-error-__INDEX__-pasaporte" style="display:none;"></div>
                        </div>
                    </div>
                </div>
            </template>

            <hr>
            {{-- =========================== BOTONES DE ACCIÓN ============================ --}}
            {{-- Botones flotantes en lado derecho --}}
            <div class="position-fixed end-0 top-50 translate-middle-y me-4" style="z-index: 1000;">
                {{-- Botón Actualizar --}}
                <button type="submit" class="btn btn-success shadow-lg py-3 px-4 mb-2 d-block"
                    style="border-radius: 30px 0 0 30px; width: auto; min-width: 140px;">
                    <i class="fas fa-save me-2"></i> Crear
                </button>

                {{-- Botón Volver --}}
                <a href="{{ route('programas.index') }}" class="btn btn-danger shadow-lg py-3 px-4 d-block"
                    style="border-radius: 30px 0 0 30px; width: auto; min-width: 140px;">
                    <i class="fas fa-arrow-left me-2"></i> Volver
                </a>
            </div>
        </form>
    </div>

    {{-- ======================================
    JAVASCRIPT PARA AGREGAR / QUITAR PAXS Y MANEJAR ERRORES
    ======================================= --}}
    <script>
        let contadorPaxs = {{ old('paxs') ? count(old('paxs')) : 0 }};

        function agregarPax(index) {
            let template = document.getElementById('template-pax').innerHTML;

            // index para name=""
            template = template.replace(/__INDEX__/g, index);

            // número visual (01, 02, 03...)
            const numeroVisual = String(index + 1).padStart(2, '0');
            template = template.replace(/__NUM__/g, numeroVisual);

            const contenedor = document.getElementById('contenedor-paxs');
            const div = document.createElement('div');
            div.innerHTML = template;

            setTimeout(() => {
                @if (old('paxs'))
                    const data = @json(old('paxs'));
                    if (data[index]) {
                        Object.keys(data[index]).forEach(campo => {
                            let input = div.querySelector(`[name="paxs[${index}][${campo}]"]`);
                            if (input && campo !== 'pasaporte') { // No restaurar archivos por seguridad
                                input.value = data[index][campo];
                            }
                        });
                    }
                @endif

                // Mostrar errores específicos para este pax si existen
                @if ($errors->any())
                    const errors = @json($errors->toArray());
                    Object.keys(errors).forEach(key => {
                        // Buscar errores que coincidan con paxs[index].campo
                        const match = key.match(/paxs\.(\d+)\.(\w+)/);
                        if (match && parseInt(match[1]) === index) {
                            const campo = match[2];
                            const errorDiv = div.querySelector(`.pax-error-${index}-${campo}`);
                            if (errorDiv) {
                                errorDiv.style.display = 'block';
                                errorDiv.textContent = errors[key][0];
                            }
                        }
                    });
                @endif
            }, 100);

            contenedor.appendChild(div);
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

        // Cargar paxs existentes de old() al iniciar
        document.addEventListener("DOMContentLoaded", function() {
            @if (old('paxs'))
                @foreach (old('paxs') as $i => $pax)
                    agregarPax({{ $i }});
                @endforeach
            @endif
        });
    </script>
@endsection
