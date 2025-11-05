<li class="menu-item"><a href="{{route('index')}}" @if(request()->is('/')) id="active" @endif>Home</a></li>
<li class='menu-item'><a href="{{route('experiences')}}" @if(request()->is('experiences')) id="active" @endif>Exclusive</a></li>
<li class='menu-item'><a href="{{route('around')}}" @if(request()->is('around')) id="active" @endif>Around Perú</a></li>
<li class='menu-item'><a href='{{route('adventures')}}' @if(request()->is('adventures')) id="active" @endif>Adventures</a></li>
<li class='menu-item'><a href='{{route('blog-en')}}' @if(request()->is('blog')) id="active" @endif>blog</a></li>