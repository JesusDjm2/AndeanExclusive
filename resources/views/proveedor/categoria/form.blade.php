<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $categoria->nombre ?? '') }}"
        class="form-control @error('nombre') is-invalid @enderror" placeholder="Ingrese el nombre de la categoría">
    @error('nombre')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex justify-content-end mt-3">
    <button type="submit" class="btn btn-primary">
        {{ isset($categoria) ? 'Actualizar' : 'Guardar' }}
    </button>
</div>
