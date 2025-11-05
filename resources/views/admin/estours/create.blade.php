@extends('layouts.app')
@section('titulo', 'Crear nuevo tour en español')
@section('contenido')
    <div class="row">
        <div class="col-lg-12">
            @if (session('status'))
                <div class="text-success">
                    <div class="alert alert-danger" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            @endif
        </div>
        <div class="col-12 mt-2">
            <div class="row" style="padding: 1em; border-radius: 10px;">
                <div class="col-lg-6 float-left">
                    <h3>Crear Nuevo Tour en español</h3>
                </div>
                <div class="col-lg-6">
                    <a href="{{ route('estours.index') }}" class="btn btn-primary float-right">Volver</a>
                </div>
            </div>
            <form action="{{ route('estours.store') }}" method="post" enctype="multipart/form-data" class="bg-light">
                @csrf
                <div class="row">
                    <div class="col-lg-5 mt-3">
                        <label for="nombre" class="form-label">Nombre del Tour:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required
                            value="{{ old('nombre') }}">
                        @error('nombre')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-4 mt-3">
                        <label for="recorrido" class="form-label">Recorrido:</label>
                        <input type="text" id="recorrido" name="recorrido" class="form-control" required
                            value="{{ old('recorrido') }}">
                        @error('recorrido')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-2 mt-3">
                        <label for="precio" class="form-label">Precio:</label>
                        <input type="text" id="precio" name="precio" class="form-control" required
                            value="{{ old('precio') }}">
                        @error('precio')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-1 mt-3">
                        <label for="dias" class="form-label">Días:</label>
                        <input type="number" id="dias" name="dias" class="form-control" required
                            value="{{ old('dias') }}">
                        @error('dias')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-12 mt-3">
                        <label for="relacionado_id">Relación de Tour en inglés:</label>
                        <select name="relacionado_id" id="relacionado_id" class="form-control" required>
                            <option value="">Seleccione un Tour en inglés</option>
                            @foreach ($entours as $entour)
                                @if (!$entour->estour)
                                    <option value="{{ $entour->id }}">{{ $entour->nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('relacionado_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>                 

                    <div class="col-lg-12 mt-3">
                        <label for="descripcionCorta" class="form-label">Descripción corta: <small
                                class="text-success">(Max. 25
                                palabras)</small></label>
                        <input type="text" id="descripcionCorta" name="descripcionCorta" class="form-control" required
                            maxlength="255" value="{{ old('descripcionCorta') }}">
                        @error('descripcionCorta')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-4 mt-3">
                        <label for="imgThumb" class="form-label">Imagen Thumb: <small
                                class="text-success">(420x280)</small></label>
                        <input type="file" id="imgThumb" name="imgThumb" class="form-control" accept="image/*" required>
                        @error('imgThumb')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                        <img id="imgThumbPreview" src="#" alt="Preview"
                            style="display: none; max-width: 100%; margin-top: 10px; width: 100%; height: 220px; object-fit: cover">
                    </div>
                    <div class="col-lg-4 mt-3">
                        <label for="imgFull" class="form-label">Imagen Full: <small
                                class="text-success">(1920x1080)</small></label>
                        <input type="file" id="imgFull" name="imgFull" class="form-control" accept="image/*" required>
                        @error('imgFull')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                        <img id="imgFullPreview" src="#" alt="Preview"
                            style="display: none; max-width: 100%; margin-top: 10px; width: 100%; height: 220px; object-fit: cover">
                    </div>
                    <div class="col-lg-4 mt-3">
                        <label for="mapa" class="form-label">Mapa del tour:</label>
                        <textarea name="mapa" id="mapa" class="ckefitor form-control"></textarea>
                    </div>

                    <div class="col-lg-12 mt-3">
                        <label for="presentacion" class="form-label">Contenido Inicial:</label>
                        <textarea class="ckeditor form-control" name="presentacion" id="presentacion" value="{{ old('presentacion') }}">

                        </textarea>
                        @error('presentacion')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-12 mt-3">
                        <label for="itinerario" class="form-label">Itinerario:</label>
                        <textarea class="ckeditor form-control" name="itinerario" id="itinerario"></textarea>
                        @error('itinerario')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <script type="text/javascript">
                        document.addEventListener("DOMContentLoaded", function() {
                            const itinerarioValue = {!! json_encode(old('itinerario')) !!};
                            CKEDITOR.instances['itinerario'].setData(itinerarioValue);
                        });
                    </script>

                    <div class="col-lg-6 mt-3">
                        <label for="incluye" class="form-label">Incluye:</label>
                        <textarea class="ckeditor form-control" name="incluye" id="incluye" value="{{ old('incluye') }}"></textarea>
                        @error('incluye')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="importante" class="form-label">Importante: <small>Solo listas</small></label>
                        <textarea class="ckeditor form-control" name="importante" id="importante" value="{{ old('importante') }}"></textarea>
                        @error('importante')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-12 mt-3">
                        <label for="categorias">Categorías:</label><br>
                        <div class="checkbox-inline">
                            @foreach ($categorias as $id => $nombre)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="categoria{{ $id }}"
                                        name="categorias[]" value="{{ $id }}"
                                        @if (is_array(old('categorias')) && in_array($id, old('categorias'))) checked @endif>
                                    <label class="form-check-label"
                                        for="categoria{{ $id }}">{{ $nombre }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('categorias')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-12 mt-3">
                        <label for="keywords" class="form-label">Keywords: <small class="text-success">(Separar cada
                                palabra/frase por una coma)</small></label>
                        <input type="text" id="keywords" name="keywords" class="form-control" required
                            value="{{ old('keywords') }}">
                        @error('keywords')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-12 mt-3">
                        <label for="slug" class="form-label">Slug:</label>
                        <input type="text" id="slug" name="slug" class="form-control" required
                            value="{{ old('slug') }}">
                        @error('slug')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <script>
                        const slugInput = document.getElementById('slug');
                        slugInput.addEventListener('input', function() {
                            const originalValue = this.value;
                            const slugValue = originalValue.replace(/\s/g, '-');
                            this.value = slugValue;
                        });
                    </script>                    
                </div>
                <button class="btn btn-primary mt-4" type="submit">Guardar</button>
                <a href="{{ route('tours.index') }}" class="btn btn-secondary mt-4 float-right">Cancelar</a>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        CKEDITOR.replace('.ckeditor', {
            extraPlugins: 'youtube',
            toolbar: [
                ['Youtube']
            ]
        });
    </script>
    <script>
        function showPreview(input, previewElement) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewElement.src = e.target.result;
                    previewElement.style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('imgThumb').addEventListener('change', function() {
            showPreview(this, document.getElementById('imgThumbPreview'));
        });

        document.getElementById('imgFull').addEventListener('change', function() {
            showPreview(this, document.getElementById('imgFullPreview'));
        });

        document.getElementById('mapa').addEventListener('change', function() {
            showPreview(this, document.getElementById('mapaPreview'));
        });
    </script>

@endsection
