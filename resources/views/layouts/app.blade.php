{{--
    Layout del panel de administración.
    Estilos: resources/sass/admin.scss · JS: resources/js/admin.js (Vite).
    Navegación y breadcrumb: App\Support\AdminNavigation.
    Secciones: titulo, contenido, body-class (clase para estilos propios de una vista).
    Stacks: styles, head (librerías necesarias durante la carga, p.ej. CKEditor), vendor, scripts.
--}}
@php
    $authUser = auth()->user();
    $nav = \App\Support\AdminNavigation::build(request(), $authUser);
    $roleName = $authUser?->getRoleNames()->first();
    $initials = collect(explode(' ', trim($authUser?->name ?? '')))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
    $canEdit = $authUser?->can('programas.gestionar');
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Tema claro/oscuro antes de pintar (evita el destello); el selector está en resources/js/theme.js --}}
    <script>(function(){try{var t=localStorage.getItem('ae-theme');var d=t==='light'?'light':'dark';document.documentElement.setAttribute('data-bs-theme',d);}catch(e){}})();</script>
    <title>@hasSection('titulo')@yield('titulo') · @endif Panel Andean Exclusive</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logoico.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/vendor/fontawesome-free/css/all.min.css') }}">
    @vite(['resources/sass/admin.scss', 'resources/js/admin.js'])
    @stack('styles')
    {{-- Librerías que el contenido usa durante la carga (p.ej. CKEditor en formularios de tours) --}}
    @stack('head')
</head>

<body class="admin @yield('body-class')">
    {{-- Pantalla de Carga y Transición Cinemática --}}
    @include('layouts.partials.preloader')

    <a href="#admin-content" class="visually-hidden-focusable btn btn-primary admin-skip">Saltar al contenido</a>

    <div class="admin-shell">
        {{-- Sidebar: panel deslizable en móvil/tablet, columna fija desde 992px --}}
        <aside class="offcanvas-lg offcanvas-start admin-sidebar" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel">
            <div class="admin-sidebar__brand">
                <a href="{{ route('home') }}" id="adminSidebarLabel">
                    <img src="{{ asset('img/Logo-blanco-andean.png') }}" alt="Andean Exclusive · Panel" width="150" height="40">
                </a>
                <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar" aria-label="Cerrar menú"></button>
            </div>
            <div class="offcanvas-body">
                @include('layouts.partials.admin-nav', ['groups' => $nav['groups']])
                <div class="admin-sidebar__footer">
                    <a href="{{ route('index') }}" target="_blank" rel="noopener">
                        <i class="fas fa-external-link-alt" aria-hidden="true"></i> Ver sitio web
                    </a>
                    <small>© {{ date('Y') }} Andean Exclusive Tours</small>
                </div>
            </div>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <button class="admin-topbar__toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar" aria-label="Abrir menú">
                    <i class="fas fa-bars" aria-hidden="true"></i>
                </button>

                <nav aria-label="Ubicación" class="admin-topbar__crumb">
                    <ol class="breadcrumb mb-0 flex-nowrap">
                        @foreach ($nav['crumb'] as $crumb)
                            <li class="breadcrumb-item {{ $loop->last ? 'active fw-semibold text-truncate' : 'd-none d-sm-block' }}" @if ($loop->last) aria-current="page" @endif>{{ $crumb }}</li>
                        @endforeach
                        @if (! $nav['crumb'])
                            <li class="breadcrumb-item active fw-semibold text-truncate" aria-current="page">@yield('titulo', 'Panel')</li>
                        @endif
                    </ol>
                </nav>

                <div class="admin-topbar__actions">
                    @if ($canEdit && ! request()->routeIs('home', 'programas.*'))
                        <a href="{{ route('programas.create') }}" class="btn btn-primary btn-sm d-none d-md-inline-flex">
                            <i class="fas fa-plus" aria-hidden="true"></i> Nuevo programa
                        </a>
                    @endif

                    <button class="theme-toggle__btn" type="button" data-theme-toggle aria-label="Cambiar a tema claro" title="Cambiar a tema claro">
                        <i class="fas fa-sun" data-theme-icon aria-hidden="true"></i>
                    </button>

                    @if ($authUser)
                        <button class="ayuda-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#ayudaPanel" aria-controls="ayudaPanel" aria-label="Ayuda de esta pantalla" title="Ayuda de esta pantalla">
                            <i class="fas fa-question" aria-hidden="true"></i>
                        </button>

                        @include('avisos.partials.campana')

                        <div class="dropdown">
                            <button class="admin-user" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menú de usuario">
                                <span class="admin-user__avatar" aria-hidden="true">{{ $initials ?: '?' }}</span>
                                <span class="admin-user__meta d-none d-sm-block">
                                    <strong>{{ $firstName ?? $authUser->name }}</strong>
                                    @if ($roleName)
                                        <small>{{ $roleName }}</small>
                                    @endif
                                </span>
                                <i class="fas fa-chevron-down small text-muted d-none d-sm-inline" aria-hidden="true"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li class="px-3 py-2 small text-muted">{{ $authUser->email }}</li>
                                <li><hr class="dropdown-divider"></li>
                                @if ($authUser->hasRole('administrador'))
                                    <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-fw fa-user-shield me-2" aria-hidden="true"></i>Usuarios</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('index') }}" target="_blank" rel="noopener"><i class="fas fa-fw fa-external-link-alt me-2" aria-hidden="true"></i>Ver sitio web</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button type="button" class="dropdown-item text-danger" data-logout="logout-form">
                                        <i class="fas fa-fw fa-sign-out-alt me-2" aria-hidden="true"></i>Cerrar sesión
                                    </button>
                                </li>
                            </ul>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </div>
                    @endif
                </div>
            </header>

            <main id="admin-content" class="admin-content" tabindex="-1">
                @yield('contenido')
            </main>

            <footer class="admin-footer">
                Andean Exclusive Tours · Panel de gestión · Diseño
                <a href="https://www.facebook.com/DjmWebMaster/" target="_blank" rel="noopener">DJM2</a>
            </footer>
        </div>
    </div>

    @if ($authUser)
        @include('ayuda.partials.panel')
    @endif

    {{-- jQuery: lo usan DataTables y scripts de algunas vistas --}}
    <script src="{{ asset('admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>
    @include('admin.partials.pdf-preview-modal')
    @include('admin.partials.image-preview-modal')
    @stack('vendor')
    @stack('scripts')
</body>

</html>
