<!DOCTYPE html>
<html lang="Es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="nofollow">
    <title>@yield('titulo')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logoico.png') }}">
    <link href="{{ asset('admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/thumb/favicon-admin.png') }}" />
    <link rel="stylesheet" href="{{ asset('admin/css/estilos.css') }}">
    @stack('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('index') }}">
                <img src="{{ asset('img/Logo-blanco-andean.png') }}" width="100%" style="padding: 0.6em">
            </a>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Inicio</span>
                </a>
            </li>
            @php
                $navToursEn = request()->routeIs('tours.*', 'categories.*');
                $navBlogsEn = request()->routeIs('entags.*', 'enblogs.*');
                $navToursEs = request()->routeIs('estours.*', 'categorias.*');
                $navBlogsEs = request()->routeIs('estags.*', 'esblogs.*');
            @endphp
            <hr class="sidebar-divider">
            <div class="sidebar-heading text-uppercase">Inglés</div>
            <li class="nav-item">
                <a class="nav-link {{ $navToursEn ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
                    data-target="#collapseToursEn" aria-expanded="{{ $navToursEn ? 'true' : 'false' }}"
                    aria-controls="collapseToursEn">
                    <i class="fas fa-fw fa-map-marked-alt"></i>
                    <span>Tours EN</span>
                </a>
                <div id="collapseToursEn" class="collapse {{ $navToursEn ? 'show' : '' }}"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ request()->routeIs('tours.*') ? 'active' : '' }}"
                            href="{{ route('tours.index') }}">Tours</a>
                        <a class="collapse-item {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                            href="{{ route('categories.index') }}">Categorías</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $navBlogsEn ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
                    data-target="#collapseBlogsEn" aria-expanded="{{ $navBlogsEn ? 'true' : 'false' }}"
                    aria-controls="collapseBlogsEn">
                    <i class="fas fa-fw fa-blog"></i>
                    <span>Blogs EN</span>
                </a>
                <div id="collapseBlogsEn" class="collapse {{ $navBlogsEn ? 'show' : '' }}"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ request()->routeIs('entags.*') ? 'active' : '' }}"
                            href="{{ route('entags.index') }}">Tags</a>
                        <a class="collapse-item {{ request()->routeIs('enblogs.*') ? 'active' : '' }}"
                            href="{{ route('enblogs.index') }}">Blogs</a>
                    </div>
                </div>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading text-uppercase">Español</div>
            <li class="nav-item">
                <a class="nav-link {{ $navToursEs ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
                    data-target="#collapseToursEs" aria-expanded="{{ $navToursEs ? 'true' : 'false' }}"
                    aria-controls="collapseToursEs">
                    <i class="fas fa-fw fa-map"></i>
                    <span>Tours ES</span>
                </a>
                <div id="collapseToursEs" class="collapse {{ $navToursEs ? 'show' : '' }}"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ request()->routeIs('estours.*') ? 'active' : '' }}"
                            href="{{ route('estours.index') }}">Tours</a>
                        <a class="collapse-item {{ request()->routeIs('categorias.*') ? 'active' : '' }}"
                            href="{{ route('categorias.index') }}">Categorías</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $navBlogsEs ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
                    data-target="#collapseBlogsEs" aria-expanded="{{ $navBlogsEs ? 'true' : 'false' }}"
                    aria-controls="collapseBlogsEs">
                    <i class="fas fa-fw fa-pen"></i>
                    <span>Blogs ES</span>
                </a>
                <div id="collapseBlogsEs" class="collapse {{ $navBlogsEs ? 'show' : '' }}"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ request()->routeIs('estags.*') ? 'active' : '' }}"
                            href="{{ route('estags.index') }}">Tags</a>
                        <a class="collapse-item {{ request()->routeIs('esblogs.*') ? 'active' : '' }}"
                            href="{{ route('esblogs.index') }}">Blogs</a>
                    </div>
                </div>
            </li>
            <hr class="sidebar-divider">
            <!-- Sección: Administración -->
            <div class="sidebar-heading text-uppercase">Administración</div>

            @php
                $programasNavOpen = request()->routeIs('programas.*', 'paxs.*');
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $programasNavOpen ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
                    data-target="#collapseProgramas" aria-expanded="{{ $programasNavOpen ? 'true' : 'false' }}"
                    aria-controls="collapseProgramas">
                    <i class="fas fa-fw fa-layer-group"></i>
                    <span>Programas</span>
                </a>
                <div id="collapseProgramas" class="collapse {{ $programasNavOpen ? 'show' : '' }}"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ request()->routeIs('programas.index', 'programas.create', 'programas.edit', 'programas.pdf') ? 'active' : '' }}"
                            href="{{ route('programas.index') }}">Listado completo</a>
                        <a class="collapse-item {{ request()->routeIs('programas.por-periodo') ? 'active' : '' }}"
                            href="{{ route('programas.por-periodo') }}">Por año y mes</a>
                        <a class="collapse-item {{ request()->routeIs('paxs.*') ? 'active' : '' }}"
                            href="{{ route('paxs.index') }}">Pasajeros</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#proveedor"
                    aria-expanded="false" aria-controls="proveedor">
                    <i class="fas fa-fw fa-pen"></i>
                    <span>Proveedores</span>
                </a>
                <div id="proveedor" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('proveedors.index') }}">Proveedores</a>
                        <a class="collapse-item" href="{{ route('categoriasproveedor.index') }}">Categoria de
                            Proveedores</a>
                        <a class="collapse-item" href="{{ route('hotel.index') }}">Hoteles</a>
                        <a class="collapse-item" href="{{ route('agentes.index') }}">Agentes</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('imagenes.index') }}">
                    <i class="fas fa-fw fa-images"></i>
                    <span>Imágenes</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.index') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Usuarios</span>
                </a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <h6 class="text-muted mb-0 d-flex align-items-center gap-2">
                        Qué gusto verte, <span class="fw-semibold text-dark">{{ $firstName }}</span>
                    </h6>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <li class="nav-item no-arrow mx-1">
                            <ul class="navbar-nav ms-auto">
                                @auth
                                    <li>
                                        <button type="button" id="logoutButton" class="btn-logout">
                                            <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                                        </button>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>

                                        <script>
                                            document.getElementById('logoutButton').addEventListener('click', function(e) {
                                                e.preventDefault();

                                                Swal.fire({
                                                    title: '¿Deseas salir del sistema?',
                                                    text: 'Podrás volver a iniciar sesión cuando quieras.',
                                                    icon: 'question',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#3085d6',
                                                    cancelButtonColor: '#6c757d',
                                                    cancelButtonText: 'Seguir conectado',
                                                    confirmButtonText: 'Sí, cerrar sesión',
                                                    background: '#fff',
                                                    customClass: {
                                                        popup: 'rounded-3 shadow-lg',
                                                        title: 'fw-bold',
                                                        confirmButton: 'px-4',
                                                        cancelButton: 'px-4'
                                                    }
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        document.getElementById('logout-form').submit();
                                                    }
                                                });
                                            });
                                        </script>
                                    </li>
                                @endauth
                            </ul>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
                    @yield('contenido')
                </div>
            </div>
            {{--   <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Made by <a target="_blank"
                                href="https://www.facebook.com/DjmWebMaster/">DJM2</a></span>
                    </div>
                </div>
            </footer> --}}
            <footer class="sticky-footer animated-footer">
                <div class="container my-auto text-center py-3">
                    <div class="copyright my-auto">
                        <span class="text-white">
                            © {{ date('Y') }} Made by
                            <a target="_blank" href="https://www.facebook.com/DjmWebMaster/" class="footer-link">
                                DJM2
                            </a>
                        </span>
                    </div>
                </div>
            </footer>

            <style>
                /* 🎨 Estilos base */
                .animated-footer {
                    background-color: #313131;
                    color: #ffffff;
                    opacity: 0;
                    transform: translateY(100%);
                    visibility: hidden;
                    position: relative;
                    transition: opacity 0.3s ease, transform 0.3s ease;
                }

                /* 💫 Cuando aparece */
                .animated-footer.show {
                    animation: slideUpBounce 0.5s ease-in-out forwards;
                    /* ⏱️ más rápido */
                    opacity: 1;
                    transform: translateY(0);
                    visibility: visible;
                    box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.3);
                }

                /* 🌟 Enlaces */
                .footer-link {
                    color: #f9d342;
                    text-decoration: none;
                    transition: color 0.1s ease;
                }

                .footer-link:hover {
                    color: #ffffff;
                }

                /* 🧩 Animación */
                @keyframes slideUpBounce {
                    0% {
                        transform: translateY(100%);
                        opacity: 0;
                    }

                    55% {
                        transform: translateY(0.2em);
                        opacity: 1;
                    }

                    75% {
                        transform: translateY(0.1em);
                    }

                    100% {
                        transform: translateY(0);
                        opacity: 1;
                        visibility: visible;
                    }
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const footer = document.querySelector('.animated-footer');
                    let visible = false;

                    window.addEventListener('scroll', function() {
                        const scrollBottom = window.scrollY + window.innerHeight;
                        const docHeight = document.documentElement.scrollHeight;

                        if (scrollBottom >= docHeight - 10 && !visible) {
                            footer.classList.add('show');
                            visible = true;
                        } else if (scrollBottom < docHeight - 100 && visible) {
                            footer.classList.remove('show');
                            visible = false;
                        }
                    });
                });
            </script>

        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <style>
        /* Eliminar scrollbars duplicadas */
        html,
        body {
            overflow-x: hidden;
            overflow-y: auto;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #wrapper {
            overflow-x: hidden;
            height: 100vh;
        }

        #content-wrapper {
            overflow-y: auto;
            height: 100vh;
        }

        .container-fluid {
            overflow-y: visible;
            max-height: none;
        }

        /* Arreglar acordeones */
        .accordion .card {
            overflow: visible;
        }

        .collapse {
            overflow: visible;
        }

        /* Botón flotante */
        .fixed-bottom {
            position: fixed;
            bottom: 80px;
            z-index: 9999;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.9.2/ckeditor.js"></script>
    <script src="{{ asset('admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('admin/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('admin/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('admin/js/demo/chart-pie-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>

</html>
