<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * Menú del panel de administración: única fuente para el sidebar y el breadcrumb del topbar.
 *
 * Grupos > ítems. Un ítem bilingüe lleva 'langs' => [[código, ruta, patrones activos], ...].
 * 'active' son patrones de nombre de ruta; 'roles' limita la visibilidad (ausente = todos).
 */
class AdminNavigation
{
    private const GROUPS = [
        [
            'label' => null,
            'items' => [
                ['label' => 'Inicio', 'icon' => 'fa-tachometer-alt', 'route' => 'home', 'active' => ['home']],
                ['label' => 'Notificaciones', 'icon' => 'fa-bell', 'route' => 'avisos.index', 'active' => ['avisos.*']],
                ['label' => 'Movimientos', 'icon' => 'fa-stream', 'route' => 'movimientos.index', 'active' => ['movimientos.*']],
                ['label' => 'Ayuda', 'icon' => 'fa-question-circle', 'route' => 'ayuda.index', 'active' => ['ayuda.*']],
            ],
        ],
        [
            'label' => 'Operaciones',
            'items' => [
                ['label' => 'Consultas', 'icon' => 'fa-inbox', 'route' => 'consultas.index', 'active' => ['consultas.*']],
                ['label' => 'Programas', 'icon' => 'fa-suitcase-rolling', 'route' => 'programas.index', 'active' => ['programas.index', 'programas.create', 'programas.edit', 'programas.pdf']],
                ['label' => 'Reservas', 'icon' => 'fa-concierge-bell', 'route' => 'reservas.index', 'active' => ['reservas.*']],
                ['label' => 'Hoja de operaciones', 'icon' => 'fa-clipboard-check', 'route' => 'operaciones.index', 'active' => ['operaciones.*']],
                ['label' => 'Por año y mes', 'icon' => 'fa-calendar-alt', 'route' => 'programas.por-periodo', 'active' => ['programas.por-periodo']],
                ['label' => 'Clientes', 'icon' => 'fa-address-book', 'route' => 'clientes.index', 'active' => ['clientes.*']],
                ['label' => 'Pasajeros', 'icon' => 'fa-users', 'route' => 'paxs.index', 'active' => ['paxs.*']],
            ],
        ],
        [
            'label' => 'Proveedores',
            'items' => [
                ['label' => 'Cuentas por pagar', 'icon' => 'fa-file-invoice-dollar', 'route' => 'cuentas.index', 'active' => ['cuentas.*']],
                ['label' => 'Tarifario', 'icon' => 'fa-tags', 'route' => 'tarifas.index', 'active' => ['tarifas.*']],
                ['label' => 'Proveedores', 'icon' => 'fa-handshake', 'route' => 'proveedors.index', 'active' => ['proveedors.*']],
                ['label' => 'Categorías', 'icon' => 'fa-tags', 'route' => 'categoriasproveedor.index', 'active' => ['categoriasproveedor.*']],
                ['label' => 'Hoteles', 'icon' => 'fa-hotel', 'route' => 'hotel.index', 'active' => ['hotel.*', 'habitacion.*']],
                ['label' => 'Agentes', 'icon' => 'fa-user-tie', 'route' => 'agentes.index', 'active' => ['agentes.*']],
            ],
        ],
        [
            'label' => 'Sitio web',
            'roles' => ['administrador'],
            'items' => [
                ['label' => 'Tours', 'icon' => 'fa-map-marked-alt', 'langs' => [['EN', 'tours.index', ['tours.*']], ['ES', 'estours.index', ['estours.*']]]],
                ['label' => 'Categorías de tours', 'icon' => 'fa-layer-group', 'langs' => [['EN', 'categories.index', ['categories.*']], ['ES', 'categorias.index', ['categorias.*']]]],
                ['label' => 'Blog', 'icon' => 'fa-pen-nib', 'langs' => [['EN', 'enblogs.index', ['enblogs.*']], ['ES', 'esblogs.index', ['esblogs.*']]]],
                ['label' => 'Etiquetas', 'icon' => 'fa-hashtag', 'langs' => [['EN', 'entags.index', ['entags.*']], ['ES', 'estags.index', ['estags.*']]]],
            ],
        ],
        [
            'label' => 'Medios',
            'items' => [
                ['label' => 'Imágenes', 'icon' => 'fa-images', 'route' => 'imagenes.index', 'active' => ['imagenes.*']],
            ],
        ],
        [
            'label' => 'Sistema',
            'roles' => ['administrador'],
            'items' => [
                ['label' => 'Usuarios', 'icon' => 'fa-user-shield', 'route' => 'users.index', 'active' => ['users.*']],
                ['label' => 'Copias de seguridad', 'icon' => 'fa-database', 'route' => 'respaldos.index', 'active' => ['respaldos.*']],
            ],
        ],
    ];

    /**
     * @return array{groups: array, crumb: array<int, string>}
     */
    public static function build(Request $request, ?User $user): array
    {
        $groups = [];
        $crumb = [];

        foreach (self::GROUPS as $group) {
            if (isset($group['roles']) && ! $user?->hasAnyRole($group['roles'])) {
                continue;
            }

            foreach ($group['items'] as $i => $item) {
                if (isset($item['langs'])) {
                    foreach ($item['langs'] as $l => [$code, $route, $patterns]) {
                        $group['items'][$i]['langs'][$l][3] = $request->routeIs(...$patterns);
                    }
                    $activeLang = collect($group['items'][$i]['langs'])->first(fn ($lang) => $lang[3]);
                    $isActive = $activeLang !== null;
                    $label = $item['label'] . ($activeLang ? ' ' . $activeLang[0] : '');
                } else {
                    $isActive = $request->routeIs(...$item['active']);
                    $label = $item['label'];
                }

                $group['items'][$i]['isActive'] = $isActive;
                if ($isActive) {
                    $crumb = array_values(array_filter([$group['label'], $label]));
                }
            }

            $groups[] = $group;
        }

        return ['groups' => $groups, 'crumb' => $crumb];
    }
}
