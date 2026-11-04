{{--
    Modal para crear un nuevo hotel y su habitación inicial en caliente desde el formulario de programas.
    Permite registrar alojamientos sin salir de la vista ni perder datos.
--}}
<div class="modal fade" id="modalNuevoHotel" tabindex="-1" aria-labelledby="modalNuevoHotelLabel" aria-hidden="true" data-modal-nuevo-hotel>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <form id="form-nuevo-hotel-rapido" action="{{ route('hotel.rapido') }}" method="POST" data-nuevo-hotel-form>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fs-6 fw-bold" id="modalNuevoHotelLabel">
                        <i class="fas fa-hotel text-primary me-2" aria-hidden="true"></i> Registrar Nuevo Hotel / Hospedaje
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Registra el hotel y su habitación base. Se agregará de inmediato a las opciones de alojamiento sin recargar la página.
                    </p>

                    <div class="mb-3">
                        <label class="form-label prog-label" for="hotel-rapido-nombre">
                            Nombre del Hotel o Propiedad <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="hotel-rapido-nombre" name="nombre" class="form-control form-control-sm"
                            placeholder="Ej. JW Marriott El Convento / Airbnb San Blas" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label prog-label" for="hotel-rapido-direccion">Ciudad / Ubicación</label>
                            <input type="text" id="hotel-rapido-direccion" name="direccion" class="form-control form-control-sm"
                                placeholder="Ej. Cusco, Valle Sagrado, Lima">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label prog-label" for="hotel-rapido-telefono">Teléfono / Contacto</label>
                            <input type="text" id="hotel-rapido-telefono" name="telefono" class="form-control form-control-sm"
                                placeholder="+51 984 000 000">
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-sm-7">
                            <label class="form-label prog-label" for="hotel-rapido-tipo">Tipo de Habitación inicial</label>
                            <input type="text" id="hotel-rapido-tipo" name="habitacion_tipo" class="form-control form-control-sm"
                                value="Estándar / Doble" placeholder="Ej. Doble, Suite, Villa">
                        </div>
                        <div class="col-sm-5">
                            <label class="form-label prog-label" for="hotel-rapido-capacidad">Capacidad (pax)</label>
                            <input type="number" id="hotel-rapido-capacidad" name="habitacion_capacidad" class="form-control form-control-sm"
                                value="2" min="1" max="10">
                        </div>
                    </div>

                    <div class="alert alert-danger py-2 px-3 small mt-3" data-nuevo-hotel-error style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary" data-nuevo-hotel-submit>
                        <i class="fas fa-save me-1" aria-hidden="true"></i> Guardar y seleccionar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
