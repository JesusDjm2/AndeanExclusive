{{-- Menú lateral del panel. Estructura y permisos: App\Support\AdminNavigation. --}}
<nav class="admin-nav" aria-label="Menú del panel">
    @foreach ($groups as $group)
        <div class="admin-nav__group">
            @if ($group['label'])
                <p class="admin-nav__heading">{{ $group['label'] }}</p>
            @endif
            <ul class="admin-nav__list">
                @foreach ($group['items'] as $item)
                    <li class="admin-nav__item {{ $item['isActive'] ? 'is-active' : '' }}">
                        @isset($item['langs'])
                            <a href="{{ route($item['langs'][0][1]) }}" class="admin-nav__link" @if ($item['isActive']) aria-current="page" @endif>
                                <i class="fas fa-fw {{ $item['icon'] }}" aria-hidden="true"></i>{{ $item['label'] }}
                            </a>
                            <span class="admin-nav__langs">
                                @foreach ($item['langs'] as [$code, $route, $patterns, $langActive])
                                    <a href="{{ route($route) }}" class="admin-nav__lang {{ $langActive ? 'is-active' : '' }}"
                                        title="{{ $item['label'] }} en {{ $code === 'EN' ? 'inglés' : 'español' }}">{{ $code }}</a>
                                @endforeach
                            </span>
                        @else
                            <a href="{{ route($item['route']) }}" class="admin-nav__link" @if ($item['isActive']) aria-current="page" @endif>
                                <i class="fas fa-fw {{ $item['icon'] }}" aria-hidden="true"></i>{{ $item['label'] }}
                            </a>
                        @endisset
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</nav>
