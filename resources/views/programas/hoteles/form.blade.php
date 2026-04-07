<div class="card mb-3">
    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-6">
                <label>Nombre:</label>
                <input type="text" name="nombre" value="{{ old('nombre', $hotel->nombre ?? '') }}"
                    class="form-control form-control-sm @error('nombre') is-invalid @enderror">
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label>RUC:</label>
                <input type="text" name="ruc" value="{{ old('ruc', $hotel->ruc ?? '') }}"
                    class="form-control form-control-sm">
            </div>

            <div class="col-md-6">
                <label>Teléfono:</label>
                <input type="text" name="telefono" value="{{ old('telefono', $hotel->telefono ?? '') }}"
                    class="form-control form-control-sm">
            </div>

            <div class="col-md-6">
                <label>Correo:</label>
                <input type="email" name="correo" value="{{ old('correo', $hotel->correo ?? '') }}"
                    class="form-control form-control-sm">
            </div>

            <div class="col-md-12">
                <label>Dirección:</label>
                <input type="text" name="direccion" value="{{ old('direccion', $hotel->direccion ?? '') }}"
                    class="form-control form-control-sm">
            </div>

            <div class="col-md-12">
                <label>Imagen:</label>
                <input type="file" name="img" class="form-control form-control-sm">

                @isset($hotel)
                    <div class="mt-2">
                        @if ($hotel->img)
                            <img src="{{ asset($hotel->img) }}" alt="Imagen del hotel" class="img-thumbnail"
                                style="max-height: 150px;">
                        @else
                            <em class="text-muted">Sin asignar</em>
                        @endif
                    </div>
                @endisset
            </div>

            <div class="col-md-12">
                <label>Detalles:</label>
                <textarea name="detalles" rows="3" class="form-control form-control-sm">{{ old('detalles', $hotel->detalles ?? '') }}</textarea>
            </div>

            <div class="col-md-12">
                <hr>
                <label>Habitaciones:</label>
                <div id="habitaciones-wrapper">
                    @foreach (old('habitaciones', $hotel->habitaciones ?? []) as $i => $habitacion)
                        <div class="row g-2 mb-2 habitacion-item">

                            @if (isset($habitacion['id']) || isset($habitacion->id))
                                <input type="hidden" name="habitaciones[{{ $i }}][id]"
                                    value="{{ $habitacion['id'] ?? $habitacion->id }}">
                            @endif

                            <div class="col-md-7">
                                <input type="text" name="habitaciones[{{ $i }}][tipo]"
                                    value="{{ $habitacion['tipo'] ?? $habitacion->tipo }}" placeholder="Tipo"
                                    class="form-control form-control-sm
                       @error("habitaciones.$i.tipo") is-invalid @enderror">

                                @error("habitaciones.$i.tipo")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <input type="number" name="habitaciones[{{ $i }}][capacidad]"
                                    value="{{ $habitacion['capacidad'] ?? $habitacion->capacidad }}"
                                    placeholder="Capacidad"
                                    class="form-control form-control-sm
                       @error("habitaciones.$i.capacidad") is-invalid @enderror">

                                @error("habitaciones.$i.capacidad")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-1">
                                <button type="button" class="btn-remove"
                                    style="border:none;border-radius:4px;background:red;color:white;
                        font-weight:700;width:30px;height:30px;">
                                    X
                                </button>
                            </div>

                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-sm btn-outline-primary" id="add-habitacion">
                    + Agregar habitación
                </button>
            </div>


        </div>
    </div>
</div>

<script>
    let habitacionIndex = {{ count(old('habitaciones', $hotel->habitaciones ?? [])) }};

    document.getElementById('add-habitacion').addEventListener('click', () => {
        const wrapper = document.getElementById('habitaciones-wrapper');

        const html = `
            <div class="row g-2 mb-2 habitacion-item">
                <div class="col-md-7">
                    <input type="text"
                           name="habitaciones[${habitacionIndex}][tipo]"
                           placeholder="Tipo"
                           class="form-control form-control-sm">
                </div>

                <div class="col-md-4">
                    <input type="number"
                           name="habitaciones[${habitacionIndex}][capacidad]"
                           placeholder="Capacidad"
                           class="form-control form-control-sm">
                </div>

                <div class="col-md-1">
                    <button type="button"
                            class="btn-remove"
                            style="border:none;border-radius:4px;background:red;color:white;
                            font-weight:700;width:30px;height:30px;">
                        X
                    </button>
                </div>

            </div>
        `;

        wrapper.insertAdjacentHTML('beforeend', html);
        habitacionIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove')) {
            e.target.closest('.habitacion-item').remove();
        }
    });
</script>
