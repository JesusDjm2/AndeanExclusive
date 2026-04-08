<li class="menu-item"><a href="{{ route('index') }}" @if (request()->routeIs('index')) id="active" @endif>Home</a></li>
<li class="menu-item"><a href="{{ route('experiences') }}" @if (request()->routeIs('experiences')) id="active" @endif>Exclusive</a></li>
<li class="menu-item"><a href="{{ route('around') }}" @if (request()->routeIs('around')) id="active" @endif>Around Perú</a></li>
<li class="menu-item"><a href="{{ route('adventures') }}" @if (request()->routeIs('adventures')) id="active" @endif>Adventures</a></li>
<li class="menu-item"><a href="{{ route('blog-en') }}" @if (request()->routeIs('blog-en')) id="active" @endif>Blog</a></li>