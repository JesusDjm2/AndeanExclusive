<div class="card-body">
    <form method="POST"
        action="{{ isset($proveedor) ? route('proveedors.update', $proveedor->id) : route('proveedors.store') }}"
        enctype="multipart/form-data">
        @csrf
        @isset($proveedor)
            @method('PUT')
        @endisset
        <div class="row">
            <div class="form-group col-lg-6 mb-3">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $proveedor->nombre ?? '') }}"
                    class="form-control form-control-sm @error('nombre') is-invalid @enderror" placeholder="Nombre">
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-lg-6 mb-3">
                <label for="categoria_id">Categoría</label>
                <select name="categoria_id" id="categoria_id"
                    class="form-control form-control-sm @error('categoria_id') is-invalid @enderror">
                    <option selected disabled>Seleccionar categoría</option>
                    @foreach ($categorias as $id => $nombre)
                        <option value="{{ $id }}"
                            {{ old('categoria_id', $proveedor->categoria_id ?? '') == $id ? 'selected' : '' }}>
                            {{ $nombre }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-lg-6 mb-3">
                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion"
                    value="{{ old('direccion', $proveedor->direccion ?? '') }}"
                    class="form-control form-control-sm @error('direccion') is-invalid @enderror"
                    placeholder="Dirección">
                @error('direccion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-lg-6 mb-3">
                <label for="ruc">RUC</label>
                <input type="text" name="ruc" id="ruc" value="{{ old('ruc', $proveedor->ruc ?? '') }}"
                    class="form-control form-control-sm @error('ruc') is-invalid @enderror" placeholder="RUC">
                @error('ruc')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-lg-6 mb-3">
                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" id="telefono"
                    value="{{ old('telefono', $proveedor->telefono ?? '') }}"
                    class="form-control form-control-sm @error('telefono') is-invalid @enderror" placeholder="Teléfono">
                @error('telefono')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-lg-6 mb-3">
                <label for="correo">Correo</label>
                <input type="email" name="correo" id="correo"
                    value="{{ old('correo', $proveedor->correo ?? '') }}"
                    class="form-control form-control-sm @error('correo') is-invalid @enderror" placeholder="Correo">
                @error('correo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="detalles">Detalles</label>
            <textarea name="detalles"  rows="4"
                class="form-control ckeditor form-control-sm @error('detalles') is-invalid @enderror"
                placeholder="Puede dejar vacío este espacio">{{ old('detalles', $proveedor->detalles ?? '') }}</textarea>
            @error('detalles')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('proveedors.index') }}" class="btn btn-secondary me-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                {{ isset($proveedor) ? 'Actualizar' : 'Guardar' }}
            </button>
        </div>
    </form>
</div>

<script>
    CKEDITOR.replace('detalles');
</script>
