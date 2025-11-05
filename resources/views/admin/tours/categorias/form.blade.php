<div class="form-group">
    <label for="nombre">Nombre:</label>
    <input type="text" class="form-control form-control-sm" id="nombre" name="nombre"
        value="{{ isset($categoria) ? $categoria->nombre : old('nombre') }}">
    @error('nombre')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="slug">Slug:</label>
    <input type="text" class="form-control form-control-sm" id="slug" name="slug" readonly
        value="{{ isset($categoria) ? $categoria->slug : old('slug') }}">
    @error('slug')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</div>
<button type="submit" class="btn btn-primary">Guardar</button>
