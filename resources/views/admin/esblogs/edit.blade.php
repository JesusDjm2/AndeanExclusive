@extends('layouts.app')
@section('titulo', 'Edicion de blog en español')
@section('contenido')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card-header">Editar Crear Blog</div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('esblogs.update', $item->id)  }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                            @method('PUT')
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="nombre">Nombre:</label>
                                <input type="text" id="nombre" name="nombre" class="form-control form-control-sm"
                                    value="{{ isset($item) ? $item->nombre : old('nombre') }}" required>
                            </div>

                            <div class="col-lg-12 mt-3">
                                <label for="relacionado_id">Relación de blog en inglés:</label>
                                <select name="relacionado_id" id="relacionado_id" class="form-control">
                                    <option value="">Seleccionar un blog en inglés</option>
                                    @foreach ($enblogs as $enblog)
                                        @if (!$enblog->esblog)
                                            <option value="{{ $enblog->id }}"
                                                {{ (isset($item) && $item->relacionado_id == $enblog->id) || old('relacionado_id') == $enblog->id ? 'selected' : '' }}>
                                                {{ $enblog->nombre }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('relacionado_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>                            

                            <div class="col-lg-6 mt-3">
                                <label for="imgThumb">Imagen (thumbnail):</label>
                                <input type="file" id="imgThumb" name="imgThumb" class="form-control form-control-sm"
                                    accept="image/*" {{ isset($item) ? '' : 'required' }}>
                                @if (isset($item) && $item->imgThumb)
                                    <img src="{{ asset( $item->imgThumb) }}"
                                        style="max-width: 100%; height: auto; margin-top: 0.5em;" id="imgThumbPreview">
                                @endif
                            </div>

                            <div class="col-lg-6 mt-3">
                                <label for="imgFull">Imagen (completa):</label>
                                <input type="file" id="imgFull" name="imgFull" class="form-control form-control-sm"
                                    accept="image/*" {{ isset($item) ? '' : 'required' }}>
                                @if (isset($item) && $item->imgFull)
                                    <img src="{{ asset( $item->imgFull) }}"
                                        style="max-width: 100%; height: auto; margin-top: 0.5em;" id="imgFullPreview">
                                @endif
                            </div>

                            <div class="col-lg-12 mt-3">
                                <label for="descripcionCorta">Descripción Corta:</label>
                                <textarea id="descripcionCorta" name="descripcionCorta" class="form-control form-control-sm" rows="2" required>{{ isset($item) ? $item->descripcionCorta : old('descripcionCorta') }}</textarea>
                            </div>

                            <div class="col-lg-12 mt-3">
                                <label for="descripcion">Descripción:</label>
                                <textarea id="descripcion" name="descripcion" class="ckeditor form-control" rows="5" required>{{ isset($item) ? $item->descripcion : old('descripcion') }}</textarea>
                            </div>

                            <div class="col-lg-12 mt-2">
                                <label for="tags">Tags:</label>
                                <div class="checkbox-inline">
                                    @foreach ($tags as $id => $nombre)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="tag{{ $id }}"
                                                   name="tags[]" value="{{ $id }}"
                                                   @if (isset($item) && $item->tags->contains($id)) checked @endif>
                                            <label class="form-check-label" for="tag{{ $id }}">{{ $nombre }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('tags')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-12 mt-2">
                                <label for="keywords">Palabras clave:</label>
                                <input type="text" id="keywords" name="keywords" class="form-control form-control-sm"
                                       value="{{ isset($item) ? $item->keywords : old('keywords') }}">
                            </div>
                            
                            <div class="col-lg-12 mt-2">
                                <label for="slug" class="form-label">Slug:</label>
                                <input type="text" id="slug" name="slug" class="form-control form-control-sm"
                                       required value="{{ isset($item) ? $item->slug : old('slug') }}">
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

                            <div class="col-lg-12 mt-3">
                                <button type="submit"
                                    class="btn btn-primary btn-sm">{{ isset($item) ? 'Actualizar Blog' : 'Crear Blog' }}</button>
                                <a href="{{ route('esblogs.index') }}"
                                    class="btn btn-danger btn-sm float-right">Cancelar</a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
    </script>

@endsection
