@extends('layouts.app')
@section('titulo', 'Editar blog')
@section('contenido')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('enblogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="nombre">Nombre</label>
                                <input type="text" name="nombre" value="{{ old('nombre', $blog->nombre) }}"
                                    class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}"
                                    placeholder="Nombre">
                                @if ($errors->has('nombre'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('nombre') }}
                                    </div>
                                @endif
                            </div>

                            <div class="col-lg-6">
                                <label for="imgThumb">Imagen (thumbnail):</label>
                                <input type="file" id="imgThumb" name="imgThumb" class="form-control form-control-sm"
                                    accept="image/*" onchange="previewImage(event, 'thumbPreview')">
                                <img id="thumbPreview" src="{{ asset('img/thumb/' . $blog->imgThumb) }}"
                                    style="width: 100%; height: 300px; object-fit: cover; margin-top:0.5em">
                            </div>

                            <div class="col-lg-6">
                                <label for="imgFull">Imagen (completa):</label>
                                <input type="file" id="imgFull" name="imgFull" class="form-control form-control-sm"
                                    accept="image/*" onchange="previewImage(event, 'fullPreview')">
                                <img id="fullPreview" src="{{ asset('img/full/' . $blog->imgFull) }}"
                                    style="width: 100%; height: 300px; object-fit: cover; margin-top: 0.5em">
                            </div>
                            <div class="col-lg-12">
                                <label for="descripcionCorta">Descripción Corta:</label>
                                <textarea id="descripcionCorta" name="descripcionCorta" class="form-control" rows="3" required>{{ old('descripcionCorta', $blog->descripcionCorta) }}</textarea>
                                @error('descripcionCorta')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-12">
                                <label for="descripcion">Descripción:</label>
                                <textarea id="descripcion" name="descripcion" class="ckeditor form-control" rows="5" required>{{ old('descripcion', $blog->descripcion) }}</textarea>
                                @error('descripcion')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-12 mt-3">
                                <label for="tags">Tags:</label>
                                <div class="checkbox-inline">
                                    @foreach ($tags as $id => $nombre)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="tag{{ $id }}"
                                                name="tags[]" value="{{ $id }}"
                                                {{ in_array($id, $blog->tags->pluck('id')->toArray()) ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="tag{{ $id }}">{{ $nombre }}</label>
                                        </div>
                                    @endforeach
                                    @error('tags')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 mt-3">
                                <label for="slug" class="form-label">Slug:</label>
                                <input type="text" id="slug" name="slug" class="form-control" required
                                    value="{{ old('slug', $blog->slug) }}">
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
                            <div class="col-lg-12">
                                <label for="keywords">Palabras clave:</label>
                                <input type="text" id="keywords" name="keywords" class="form-control"
                                    value="{{ $blog->keywords }}">
                            </div>
                            <div class="col-lg-12 mt-3">
                                <button type="submit" class="btn btn-primary btn-sm">Actualizar</button>
                                <a href="{{ route('enblogs.index') }}" class="btn btn-danger btn-sm float-right">
                                    Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script>
        function previewImage(event, previewId) {
            const fileInput = event.target;
            const preview = document.getElementById(previewId);

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(fileInput.files[0]);
            }
        }
    </script>
@endsection
