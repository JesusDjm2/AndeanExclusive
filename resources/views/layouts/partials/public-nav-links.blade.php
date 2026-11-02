{{--
    Enlaces de navegación principal, por idioma. Reemplaza menu.blade.php,
    menu-castellano.blade.php, menu-blog.blade.php y menu-blog-castellano.blade.php
    (estos últimos usaban hrefs relativos hardcodeados en vez de route()).

    @param string $locale 'es'|'en'
    @param string $variant 'desktop'|'mobile' — solo afecta la clase aplicada, misma lista de enlaces.
--}}
@php
    $links = $locale === 'es'
        ? [
            ['route' => 'inicio', 'label' => 'Inicio'],
            ['route' => 'experiencias', 'label' => 'Exclusivos'],
            ['route' => 'alrededor-de-peru', 'label' => 'Tours en Perú'],
            ['route' => 'caminatas', 'label' => 'Aventuras'],
            ['route' => 'blog-es', 'label' => 'Blog'],
            ['route' => 'nosotros', 'label' => 'Nosotros'],
        ]
        : [
            ['route' => 'index', 'label' => 'Home'],
            ['route' => 'experiences', 'label' => 'Exclusive'],
            ['route' => 'around', 'label' => 'Around Perú'],
            ['route' => 'adventures', 'label' => 'Adventures'],
            ['route' => 'blog-en', 'label' => 'Blog'],
            ['route' => 'about', 'label' => 'About'],
        ];
@endphp
@foreach ($links as $link)
    <a href="{{ route($link['route']) }}" class="nav-link{{ request()->routeIs($link['route']) ? ' is-active' : '' }}">
        {{ $link['label'] }}
    </a>
@endforeach
