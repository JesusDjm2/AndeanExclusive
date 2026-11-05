<div class="row g-4">
    <div class="col-lg-8">
        <div class="row g-3">
            <div class="col-12">
                <label for="nombre" class="form-label fw-semibold small">
                    Nombre del Agente o Agencia <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user-tie text-muted"></i></span>
                    <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror"
                        value="{{ old('nombre', $agente->nombre ?? '') }}" required placeholder="Ej. Michael Harrison (Luxury Travel NY)" autofocus>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <small class="text-muted" style="font-size: 0.74rem;">Puede ser el nombre de la persona o la agencia de viajes externa que representa.</small>
            </div>

            <div class="col-md-6">
                <label for="telefono" class="form-label fw-semibold small">
                    Teléfono / WhatsApp <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fab fa-whatsapp text-success"></i></span>
                    <input type="text" name="telefono" id="telefono" class="form-control @error('telefono') is-invalid @enderror"
                        value="{{ old('telefono', $agente->telefono ?? '') }}" required placeholder="Ej. +1 555 234 5678">
                    @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <small class="text-muted" style="font-size: 0.74rem;">Incluye el código de país (ej. +1, +34, +51) para habilitar enlace directo a WhatsApp.</small>
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label fw-semibold small">
                    Correo Electrónico <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $agente->email ?? '') }}" required placeholder="agente@luxurytravel.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <small class="text-muted" style="font-size: 0.74rem;">Canal directo para envío de itinerarios, vouchers y cotizaciones.</small>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <label class="form-label fw-semibold small d-block">Foto o Logo del Agente</label>
        <div class="p-3 border rounded text-center bg-light-subtle">
            <div class="mb-3 d-flex justify-content-center">
                @if (!empty($agente->foto) && file_exists(public_path($agente->foto)))
                    <img id="agente-foto-preview" src="{{ asset($agente->foto) }}" alt="Foto actual"
                        class="rounded-circle shadow-sm" width="100" height="100" style="object-fit: cover; border: 2px solid var(--ae-gold-border);">
                @else
                    <div id="agente-foto-placeholder" class="ae-avatar-circle ae-avatar-circle--gold" style="width: 100px; height: 100px; font-size: 2rem;">
                        <i class="fas fa-camera"></i>
                    </div>
                    <img id="agente-foto-preview" src="#" alt="Vista previa"
                        class="rounded-circle shadow-sm d-none" width="100" height="100" style="object-fit: cover; border: 2px solid var(--ae-gold-border);">
                @endif
            </div>

            <input type="file" accept=".jpg,.jpeg,.png,.webp" name="foto" id="foto" class="form-control form-control-sm @error('foto') is-invalid @enderror">
            <small class="text-muted d-block mt-2" style="font-size: 0.72rem;">JPG, PNG o WebP. Máximo 4 MB.</small>
            @error('foto')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center pt-4 mt-4 border-top">
    <a href="{{ route('agentes.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-times me-1"></i> Cancelar
    </a>
    <button type="submit" class="btn btn-primary px-4 shadow-sm">
        <i class="fas fa-check me-1"></i> {{ $modo === 'crear' ? 'Guardar Agente' : 'Actualizar Agente' }}
    </button>
</div>

@push('scripts')
<script>
    document.getElementById('foto')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                const preview = document.getElementById('agente-foto-preview');
                const placeholder = document.getElementById('agente-foto-placeholder');
                if (preview) {
                    preview.src = evt.target.result;
                    preview.classList.remove('d-none');
                }
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
