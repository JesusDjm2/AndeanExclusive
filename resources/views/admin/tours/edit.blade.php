@extends('layouts.app')
@section('titulo', 'Editar Tour en inglés')
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
                    <h3>Editar Tour en Inglés</h3>
                </div>
                <div class="col-lg-6">
                    <a href="{{ route('tours.index') }}" class="btn btn-primary float-right">Volver</a>
                </div>
            </div>
            <form action="{{ route('tours.update', $tour->id) }}" method="post" enctype="multipart/form-data"
                class="bg-light">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-5 mt-3">
                        <label for="nombre" class="form-label">Nombre del Tour:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required
                        value="{{ old('nombre', $tour->nombre) }}">
                        @error('nombre')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-4 mt-3">
                        <label for="recorrido" class="form-label">Recorrido:</label>
                        <input type="text" id="recorrido" name="recorrido" class="form-control" required
                            value="{{old('recorrido', $tour->recorrido) }}">

                        @error('recorrido')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-2 mt-3">
                        <label for="precio" class="form-label">Precio:</label>
                        <input type="text" id="precio" name="precio" class="form-control" required
                            value="{{old('precio', $tour->precio) }}">

                        @error('precio')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-1 mt-3">
                        <label for="dias" class="form-label">Días:</label>
                        <input type="number" id="dias" name="dias" class="form-control" required
                            value="{{old('dias', $tour->dias)}}">

                        @error('dias')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-12 mt-3">
                        <label for="descripcionCorta" class="form-label">Descripción corta: <small
                                class="text-success">(Max. 25
                                palabras)</small></label>
                        <input type="text" id="descripcionCorta" name="descripcionCorta" class="form-control" required
                            maxlength="255" value="{{old('descripcionCorta', $tour->descripcionCorta)}}">
                        @error('descripcionCorta')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-lg-4 mt-3">
                        <label for="imgThumb" class="form-label">Imagen Thumb: <small
                                class="text-success">(420x280)</small></label>
                        <input type="file" id="imgThumb" name="imgThumb" class="form-control" accept="image/*">
                        @error('imgThumb')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror

                        <img id="imgThumbPreview" src="{{ asset($tour->imgThumb) }}" alt="Vista previa"
                            style="width: 100%; height: 220px; object-fit: cover">
                    </div>

                    <div class="col-lg-4 mt-3">
                        <label for="imgFull" class="form-label">Imagen Full: <small
                                class="text-success">(1920x1080)</small></label>
                        <input type="file" id="imgFull" name="imgFull" class="form-control" accept="image/*">
                        @error('imgFull')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror

                        <img id="imgFullPreview" src="{{ asset($tour->imgFull) }}" alt="Vista previa"
                            style="width: 100%; height: 220px; object-fit: cover">
                    </div>

                    <div class="col-lg-4 mt-3">
                        <label for="mapa" class="form-label">Mapa del tour:</label>
                        <textarea class="ckeditor form-control" name="mapa" id="mapa"><{{ $tour->mapa }}</textarea>
                        
                        {{-- <input type="file" id="mapa" name="mapa" class="form-control" accept="image/*">
                        @error('mapa')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror

                        <img id="mapaPreview" src="{{ asset($tour->mapa) }}" alt="Vista previa"
                            style="width: 100%; height: 220px; object-fit: cover"> --}}
                    </div>

                    <div class="col-lg-12 mt-3">
                        <label for="presentacion" class="form-label">Contenido Inicial:</label>
                        <textarea class="ckeditor form-control" name="presentacion" id="presentacion" required>{{ $tour->presentacion }}</textarea> {{-- Mostrar valor actual --}}
                        @error('presentacion')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-12 mt-3">
                        <label for="itinerario" class="form-label">Itinerario:</label>
                        <textarea class="ckeditor form-control" name="itinerario" id="itinerario" required>{{ $tour->itinerario }}</textarea> {{-- Mostrar valor actual --}}
                        @error('itinerario')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="incluye" class="form-label">Incluye:</label>
                        <textarea class="ckeditor form-control" name="incluye" id="incluye" required>{{ $tour->incluye }}</textarea> {{-- Mostrar valor actual --}}
                        @error('incluye')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="importante" class="form-label">Importante: <small>Solo listas</small></label>
                        <textarea class="ckeditor form-control" name="importante" id="importante" required>{{ $tour->importante }}</textarea> {{-- Mostrar valor actual --}}
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
                                        {{ in_array($id, $tour->categorias->pluck('id')->toArray()) ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="categoria{{ $id }}">{{ $nombre }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-12 mt-3">
                        <label for="keywords" class="form-label">Keywords: <small class="text-success">(Separar cada
                                palabra/frase por una coma)</small></label>
                        <input type="text" id="keywords" name="keywords" class="form-control" required
                            value="{{old('keywords', $tour->keywords) }}"> 
                        @error('keywords')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-12 mt-3">
                        <label for="slug" class="form-label">Slug:</label>
                        <input type="text" id="slug" name="slug" class="form-control" required
                               value="{{ old('slug', $tour->slug) }}">
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

    <script type="text/javascript">
        function showPreview(input, previewElement) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    previewElement.src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        var imgThumbUrl = "{{ asset($tour->imgThumb) }}";
        var imgFullUrl = "{{ asset($tour->imgFull) }}";
        var mapaUrl = "{{ asset($tour->mapa) }}";

        document.getElementById('imgThumbPreview').src = imgThumbUrl;
        document.getElementById('imgFullPreview').src = imgFullUrl;
        document.getElementById('mapaPreview').src = mapaUrl;

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
