@extends('layouts.app')
@section('body-class', 'view-imagenes-create')
@section('titulo', 'Subir nueva imagen o imagenes')
@section('contenido')
    <div class="row">
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif
        <div class="col-12">
            <h4 class="float-start">Subir imagen nueva:</h4>
            <a href="{{ route('imagenes.index') }}" class="btn btn-primary float-end">Volver</a>
        </div>
        <div class="col-12">
            <form method="POST" action="{{ route('imagenes.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="img">Seleccionar imágenes:</label>
                    <input type="file" name="img[]" id="img" class="form-control" accept=".jpg,.jpeg,.png,.webp,.gif" multiple>
                </div>
                <div class="image-preview">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Subir imágenes</button>
            </form>
        </div>
    </div>
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
