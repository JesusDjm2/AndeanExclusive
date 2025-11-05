@extends('layouts.app')
@section('titulo', 'Subir nueva imagen o imagenes')
@section('contenido')
    <div class="row">
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif
        <div class="col-12">
            <h4 class="float-left">Subir imagen nueva:</h4>
            <a href="{{ route('imagenes.index') }}" class="btn btn-primary float-right">Volver</a>
        </div>
        <div class="col-12">
            <form method="POST" action="{{ route('imagenes.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="img">Seleccionar imágenes:</label>
                    <input type="file" name="img[]" id="img" class="form-control" multiple>
                </div>
                <div class="image-preview">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Subir imágenes</button>
            </form>
        </div>
    </div>
    <style>
        .image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .image-preview img {
            max-width: 100%;
            object-fit: cover;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imgInput = document.getElementById('img');
            const imagePreview = document.querySelector('.image-preview');

            imgInput.addEventListener('change', function() {
                // Limpiar la vista previa actual
                imagePreview.innerHTML = '';

                const files = imgInput.files;
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];

                    // Validar si el archivo es una imagen
                    if (file.type.startsWith('image/')) {
                        const imgElement = document.createElement('img');
                        imgElement.src = URL.createObjectURL(file);
                        imagePreview.appendChild(imgElement);
                    }
                }
            });
        });
    </script>
@endsection
