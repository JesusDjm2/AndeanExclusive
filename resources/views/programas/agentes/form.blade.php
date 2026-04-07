<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" name="nombre" id="nombre" class="form-control"
        value="{{ old('nombre', $agente->nombre ?? '') }}" required>
    @error('nombre')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label for="telefono" class="form-label">Teléfono</label>
    <input type="text" name="telefono" id="telefono" class="form-control"
        value="{{ old('telefono', $agente->telefono ?? '') }}" required>
    @error('telefono')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" id="email" class="form-control"
        value="{{ old('email', $agente->email ?? '') }}" required>
    @error('email')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div> 

<div class="mb-3">
    <label for="foto" class="form-label">Foto</label>
    <input type="file" name="foto" id="foto" class="form-control">

    @if (!empty($agente->foto))
        <div class="mt-2">
            <img src="{{ asset('storage/' . $agente->foto) }}" alt="Foto actual" class="img-thumbnail" width="150">
        </div>
    @endif

    @error('foto')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('agentes.index') }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-success">
        {{ $modo === 'crear' ? 'Guardar' : 'Actualizar' }}
    </button>
</div>
