<li class="menu-item"><a href="{{ route('inicio') }}" @if (request()->routeIs('inicio')) id="active" @endif>Inicio</a></li>
<li class="menu-item"><a href="{{ route('experiencias') }}" @if (request()->routeIs('experiencias')) id="active" @endif>Exclusivos</a></li>
<li class="menu-item"><a href="{{ route('alrededor-de-peru') }}" @if (request()->routeIs('alrededor-de-peru')) id="active" @endif>Tours en Perú</a></li>
<li class="menu-item"><a href="{{ route('caminatas') }}" @if (request()->routeIs('caminatas')) id="active" @endif>Aventuras</a></li>
<li class="menu-item"><a href="{{ route('blog-es') }}" @if (request()->routeIs('blog-es')) id="active" @endif>Blog</a></li>
