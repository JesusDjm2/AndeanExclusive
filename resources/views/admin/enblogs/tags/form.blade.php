<div class="mb-3">
    <label class="form-label" for="nombre">Nombre</label>
    <input type="text" class="form-control form-control-sm" id="nombre" name="nombre"
        value="{{ isset($tag) ? $tag->nombre : old('nombre') }}">
    @error('nombre')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label class="form-label" for="slug">Slug</label>
    <input type="text" class="form-control form-control-sm bg-light" id="slug" name="slug" readonly
        value="{{ isset($tag) ? $tag->slug : old('slug') }}">
    @error('slug')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
<button type="submit" class="btn btn-primary btn-sm">
    <i class="fas fa-save me-1"></i> Guardar
</button>
