<li class='menu-item'><a href="{{route('inicio')}}" @if(request()->is('index-castellano')) id="active" @endif>Inicio</a></li>
<li class='menu-item'><a href="{{route('experiencias')}}" @if(request()->is('experiencias')) id="active" @endif>Exclusivos</a></li>
<li class='menu-item'><a href="{{route('alrededor-de-peru')}}" @if(request()->is('alrededor-de-peru')) id="active" @endif>Tours en Perú</a></li>
<li class='menu-item'><a href='{{route('caminatas')}}' @if(request()->is('caminatas-peru')) id="active" @endif>Aventuras</a></li>
<li class='menu-item'><a href='{{route('blog-es')}}' @if(request()->is('blog-castellano')) id="active" @endif>Blog</a></li>