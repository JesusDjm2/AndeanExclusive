@extends('layouts.app')
@section('titulo', 'Banco de imágenes')
@section('contenido')
    <div class="container-fluid px-4">
        <!-- Encabezado mejorado -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 fw-bold">
                            <i class="fas fa-fw fa-images text-primary me-2"></i>
                            Banco de Imágenes
                        </h2>
                        <small class="text-muted">
                            Gestión y visualización de imágenes del sistema
                        </small>
                    </div>
                    <a href="{{ route('imagenes.create') }}" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Subir nueva imagen
                    </a>
                </div>
            </div>
        </div>

        <!-- Alertas -->
        @if (session('status'))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Barra de búsqueda -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control form-control-lg" id="buscadorImagenes"
                                placeholder="Buscar imágenes por nombre... (min. 2 caracteres)" autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" id="limpiarBusqueda">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-info-circle me-1"></i>
                            Escribe para buscar en tiempo real. Se mostrarán resultados automáticamente.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contador de resultados -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-primary rounded-pill" id="contadorResultados">
                            {{ $imagenes->count() }} imágenes
                        </span>
                    </div>
                    <div class="text-muted">
                        <i class="fas fa-sort me-1"></i>
                        <small>Haz clic en la URL para copiarla al portapapeles</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de imágenes -->
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="tablaImagenes">
                        <thead class="table-light">
                            <tr>
                                <th width="80" class="text-center">ID</th>
                                <th width="150" class="text-center">Imagen</th>
                                <th>Nombre del Archivo</th>
                                <th>URL</th>
                                <th width="180" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="contenedorImagenes">
                            @foreach ($imagenes as $imagen)
                                <tr class="fila-imagen"
                                    data-nombre="{{ strtolower(pathinfo($imagen->img, PATHINFO_FILENAME)) }}">
                                    <td class="text-center">
                                        <span class="badge bg-secondary rounded-pill">{{ $imagen->id }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="image-preview-container">
                                            <img loading="lazy" src="../img/galeria/{{ $imagen->img }}"
                                                alt="{{ $imagen->img }}" class="img-fluid rounded shadow-sm"
                                                style="max-height: 80px; width: auto;"
                                                data-src="../img/galeria/{{ $imagen->img }}">
                                            <div class="image-overlay">
                                                <i class="fas fa-search-plus"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-image text-info me-2"></i>
                                            <span class="nombre-archivo">{{ $imagen->img }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="url-container position-relative"
                                            onclick="copiarURL('{{ asset("img/galeria/$imagen->img") }}')">
                                            <code class="text-truncate d-block pe-4" style="max-width: 300px;">
                                                {{ asset("img/galeria/$imagen->img") }}
                                            </code>
                                            <span class="badge bg-success copiar-badge position-absolute"
                                                style="top: 0; right: 0; display: none;">
                                                <i class="fas fa-copy me-1"></i>Copiar
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href='{{ asset("img/galeria/$imagen->img") }}' target="_blank"
                                                class="btn btn-outline-success" title="Ver imagen completa">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/imagenes/{{ $imagen->id }}/edit" class="btn btn-outline-info"
                                                title="Editar información">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('imagenes.destroy', $imagen->id) }}" method="POST"
                                                class="d-inline eliminar-form"
                                                onsubmit="return confirm('¿Estás seguro de eliminar esta imagen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mensaje sin resultados -->
        <div class="row d-none" id="sinResultados">
            <div class="col-12 text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No se encontraron imágenes</h4>
                    <p class="text-muted">Intenta con otros términos de búsqueda</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .image-preview-container {
            position: relative;
            display: inline-block;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .image-preview-container:hover {
            transform: scale(1.05);
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            border-radius: 0.375rem;
        }

        .image-preview-container:hover .image-overlay {
            opacity: 1;
        }

        .image-overlay i {
            color: white;
            font-size: 1.5rem;
        }

        .url-container {
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .url-container:hover {
            background-color: #f8f9fa;
        }

        .url-container:hover .copiar-badge {
            display: block !important;
        }

        .empty-state {
            opacity: 0.7;
        }

        .fila-imagen {
            transition: all 0.3s ease;
        }

        .fila-imagen.hidden {
            display: none;
        }

        .highlight {
            background-color: rgba(255, 255, 0, 0.1);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buscador = document.getElementById('buscadorImagenes');
            const contador = document.getElementById('contadorResultados');
            const filas = document.querySelectorAll('.fila-imagen');
            const sinResultados = document.getElementById('sinResultados');
            const limpiarBtn = document.getElementById('limpiarBusqueda');

            // Configurar Intersection Observer para lazy loading
            const observerOptions = {
                root: null,
                rootMargin: '50px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        observer.unobserve(img);
                    }
                });
            }, observerOptions);

            // Observar todas las imágenes
            document.querySelectorAll('img[data-src]').forEach(img => {
                observer.observe(img);
            });

            // Función para buscar en tiempo real
            function buscarImagenes(termino) {
                let resultados = 0;
                const terminoMin = termino.toLowerCase().trim();

                if (terminoMin.length < 2) {
                    // Mostrar todas si hay menos de 2 caracteres
                    filas.forEach(fila => {
                        fila.classList.remove('hidden');
                        fila.classList.remove('highlight');
                        resultados++;
                    });
                    sinResultados.classList.add('d-none');
                } else {
                    // Filtrar por término de búsqueda
                    filas.forEach(fila => {
                        const nombreArchivo = fila.dataset.nombre;
                        const textoCompleto = fila.querySelector('.nombre-archivo').textContent
                        .toLowerCase();

                        if (nombreArchivo.includes(terminoMin) || textoCompleto.includes(terminoMin)) {
                            fila.classList.remove('hidden');
                            fila.classList.add('highlight');
                            resultados++;
                        } else {
                            fila.classList.add('hidden');
                            fila.classList.remove('highlight');
                        }
                    });

                    // Mostrar/ocultar mensaje de sin resultados
                    if (resultados === 0) {
                        sinResultados.classList.remove('d-none');
                    } else {
                        sinResultados.classList.add('d-none');
                    }
                }

                // Actualizar contador
                contador.textContent = `${resultados} ${resultados === 1 ? 'imagen' : 'imágenes'}`;
            }

            // Evento de búsqueda en tiempo real
            buscador.addEventListener('input', function() {
                buscarImagenes(this.value);
            });

            // Limpiar búsqueda
            limpiarBtn.addEventListener('click', function() {
                buscador.value = '';
                buscarImagenes('');
                buscador.focus();
            });

            // Permitir limpiar con Escape
            buscador.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    buscarImagenes('');
                }
            });
        });

        // Función para copiar URL mejorada
        function copiarURL(url) {
            navigator.clipboard.writeText(url)
                .then(() => {
                    // Mostrar notificación temporal
                    const badge = event.currentTarget.querySelector('.copiar-badge');
                    const originalText = badge.innerHTML;

                    badge.innerHTML = '<i class="fas fa-check me-1"></i>Copiado!';
                    badge.classList.remove('bg-success');
                    badge.classList.add('bg-primary');

                    setTimeout(() => {
                        badge.innerHTML = originalText;
                        badge.classList.remove('bg-primary');
                        badge.classList.add('bg-success');
                    }, 2000);

                    // O usar toast de Bootstrap si tienes
                    // mostrarToast('URL copiada al portapapeles', 'success');
                })
                .catch(err => {
                    console.error('Error al copiar: ', err);
                    alert('No se pudo copiar la URL. Error: ' + err);
                });
        }

        // Opcional: Función para ampliar imagen al hacer clic
        document.addEventListener('click', function(e) {
            if (e.target.closest('.image-preview-container')) {
                const imgSrc = e.target.closest('.image-preview-container').querySelector('img').src;
                // Puedes usar un modal de Bootstrap aquí
                // $('#modalImagen').find('img').attr('src', imgSrc).modal('show');
                window.open(imgSrc, '_blank');
            }
        });
    </script>

    <!-- Opcional: Modal para ver imagen (si quieres usar modal en lugar de nueva pestaña) -->
    
    {{-- <div class="modal fade" id="modalImagen" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vista previa de imagen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" class="img-fluid" id="imagenAmpliada">
                </div>
            </div>
        </div>
    </div> --}}
    
@endsection
