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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Logo -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('index') }}">
        <img src="{{ asset('img/Logo-blanco-andean.png') }}" width="100%" style="padding: 0.6em">
    </a>

    <!-- Home -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-home"></i>
            <span>Inicio</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Sección: Inglés -->
    <div class="sidebar-heading text-uppercase">Inglés</div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#toursEn" aria-expanded="false" aria-controls="toursEn">
            <i class="fas fa-fw fa-map-marked-alt"></i>
            <span>Tours EN</span>
        </a>
        <div id="toursEn" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('tours.index') }}">Tours</a>
                <a class="collapse-item" href="{{ route('categories.index') }}">Categorías</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#blogsEn" aria-expanded="false" aria-controls="blogsEn">
            <i class="fas fa-fw fa-blog"></i>
            <span>Blogs EN</span>
        </a>
        <div id="blogsEn" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('entags.index') }}">Tags</a>
                <a class="collapse-item" href="{{ route('enblogs.index') }}">Blogs</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <!-- Sección: Español -->
    <div class="sidebar-heading text-uppercase">Español</div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#toursEs" aria-expanded="false" aria-controls="toursEs">
            <i class="fas fa-fw fa-map"></i>
            <span>Tours ES</span>
        </a>
        <div id="toursEs" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('estours.index') }}">Tours</a>
                <a class="collapse-item" href="{{ route('categorias.index') }}">Categorías</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#blogsEs" aria-expanded="false" aria-controls="blogsEs">
            <i class="fas fa-fw fa-pen"></i>
            <span>Blogs ES</span>
        </a>
        <div id="blogsEs" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('estags.index') }}">Tags</a>
                <a class="collapse-item" href="{{ route('esblogs.index') }}">Blogs</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <!-- Sección: Administración -->
    <div class="sidebar-heading text-uppercase">Administración</div>

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

    {{-- ✅ Nuevo módulo: Proveedores --}}
    <li class="nav-item">
        <a class="nav-link" href="{{ route('proveedors.index') }}">
            <i class="fas fa-fw fa-truck"></i>
            <span>Proveedores</span>
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
                    <h5 class="text-primary">Hola {{ $firstName }}!</h5>
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
                                <li class="nav-item dropdown">
                                    <a class="nav-link mr-4" href="#" role="button" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" v-pre>
                                       {{--  {{ Auth::user()->name }} --}}
                                    </a>
                                </li>
                                <li>
                                    <a class="btn btn-danger mt-3 btn-sm" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
                    @yield('contenido')
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Made by <a target="_blank"
                                href="https://www.facebook.com/DjmWebMaster/">DJM2</a></span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <script src="//cdn.ckeditor.com/4.14.1/full/ckeditor.js"></script>

    <script src="{{ asset('admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('admin/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('admin/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('admin/js/demo/chart-pie-demo.js') }}"></script>

</body>

</html>
