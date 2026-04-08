{{--
    Cabecera pública español: mismo patrón que el sitio en inglés.
    @param string $enRoute Nombre de ruta Laravel de la página equivalente en inglés (p. ej. 'experiences', 'around').
--}}
@php
    $enRouteName = $enRoute ?? 'index';
@endphp
<header id="header">
    <div class="container headeren">
        <div class="row">
            <div class="col-sm-12">
                <div class="header-wrapper">
                    <div class="site-branding">
                        <a href="{{ route('inicio') }}" rel="home" class="logo-text-link">
                            <div class="logo2" role="img" aria-label="Andean Exclusive Tours"></div>
                        </a>
                    </div>
                    <nav class="main-nav" aria-label="Principal">
                        <ul class="one-page-menu">
                            @include('layouts.menu-castellano')
                            <li id="display">
                                <a href="{{ route($enRouteName) }}">
                                    <button type="button" class="btn botondjm">
                                        <i class="fa fa-language"></i> English
                                    </button>
                                </a>
                            </li>
                            <li id="wasanum" class="menu-item">
                                <a href="https://bit.ly/3kYXpXr" target="_blank" rel="noopener noreferrer">+51 979 721 194</a>
                            </li>
                            <li id="display2" class="menu-has-children">
                                <a href="{{ route('inicio') }}">ES</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{ route($enRouteName) }}">EN</a>
                            </li>
                        </ul>
                        <a href="javascript:;" id="mobile-menu" aria-label="Abrir menú"><span></span></a>
                        <a href="javascript:;" id="close-menu" aria-label="Cerrar menú"></a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
