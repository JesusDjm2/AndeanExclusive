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
                ['label' => 'Notificaciones', 'icon' => 'fa-bell', 'route' => 'avisos.index', 'active' => ['avisos.*'], 'roles' => ['administrador', 'operaciones', 'reservas', 'contabilidad']],
            ],
        ],
        [
            'label' => 'Comercial y Operaciones',
            'roles' => ['administrador', 'operaciones', 'reservas', 'contabilidad'],
            'items' => [
                ['label' => 'Consultas', 'icon' => 'fa-inbox', 'route' => 'consultas.index', 'active' => ['consultas.*']],
                ['label' => 'Programas', 'icon' => 'fa-suitcase-rolling', 'route' => 'programas.index', 'active' => ['programas.*']],
                ['label' => 'Tarifario Maestro', 'icon' => 'fa-calculator', 'route' => 'tarifario-maestro.index', 'active' => ['tarifario-maestro.*']],
                ['label' => 'Hoja de operaciones', 'icon' => 'fa-clipboard-check', 'route' => 'operaciones.index', 'active' => ['operaciones.*']],
            ],
        ],
        [
            'label' => 'Reservas y Proveedores',
            'roles' => ['administrador', 'operaciones', 'reservas', 'contabilidad'],
            'items' => [
                ['label' => 'Reservas', 'icon' => 'fa-concierge-bell', 'route' => 'reservas.index', 'active' => ['reservas.*', 'mensajes.*', 'servicios.mensaje']],
                ['label' => 'Proveedores', 'icon' => 'fa-handshake', 'route' => 'proveedors.index', 'active' => ['proveedors.*', 'categoriasproveedor.*']],
            ],
        ],
        [
            'label' => 'Finanzas y Contabilidad',
            'roles' => ['administrador', 'operaciones', 'reservas', 'contabilidad'],
            'items' => [
                ['label' => 'Cuentas por pagar', 'icon' => 'fa-file-invoice-dollar', 'route' => 'cuentas.index', 'active' => ['cuentas.*']],
                ['label' => 'Reportes', 'icon' => 'fa-chart-bar', 'route' => 'reportes.index', 'active' => ['reportes.*']],
            ],
        ],
        [
            'label' => 'Sitio web',
            'roles' => ['administrador', 'branding'],
            'items' => [
                ['label' => 'Tours', 'icon' => 'fa-map-marked-alt', 'langs' => [['EN', 'tours.index', ['tours.*']], ['ES', 'estours.index', ['estours.*']]]],
                ['label' => 'Categorías de tours', 'icon' => 'fa-layer-group', 'langs' => [['EN', 'categories.index', ['categories.*']], ['ES', 'categorias.index', ['categorias.*']]]],
                ['label' => 'Blog', 'icon' => 'fa-pen-nib', 'langs' => [['EN', 'enblogs.index', ['enblogs.*']], ['ES', 'esblogs.index', ['esblogs.*']]]],
                ['label' => 'Etiquetas', 'icon' => 'fa-hashtag', 'langs' => [['EN', 'entags.index', ['entags.*']], ['ES', 'estags.index', ['estags.*']]]],
                ['label' => 'Imágenes', 'icon' => 'fa-images', 'route' => 'imagenes.index', 'active' => ['imagenes.*']],
            ],
        ],
        [
            'label' => 'Sistema',
            'roles' => ['administrador'],
            'items' => [
                ['label' => 'Usuarios', 'icon' => 'fa-user-shield', 'route' => 'users.index', 'active' => ['users.*']],
                ['label' => 'Agentes / Counters', 'icon' => 'fa-user-tie', 'route' => 'agentes.index', 'active' => ['agentes.*']],
                ['label' => 'Movimientos', 'icon' => 'fa-stream', 'route' => 'movimientos.index', 'active' => ['movimientos.*']],
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

            $filteredItems = [];
            foreach ($group['items'] as $item) {
                if (isset($item['roles']) && ! $user?->hasAnyRole($item['roles'])) {
                    continue;
                }

                if (isset($item['langs'])) {
                    foreach ($item['langs'] as $l => [$code, $route, $patterns]) {
                        $item['langs'][$l][3] = $request->routeIs(...$patterns);
                    }
                    $activeLang = collect($item['langs'])->first(fn ($lang) => $lang[3]);
                    $isActive = $activeLang !== null;
                    $label = $item['label'] . ($activeLang ? ' ' . $activeLang[0] : '');
                } else {
                    $isActive = $request->routeIs(...$item['active']);
                    $label = $item['label'];
                }

                $item['isActive'] = $isActive;
                if ($isActive) {
                    $crumb = array_values(array_filter([$group['label'], $label]));
                }

                $filteredItems[] = $item;
            }

            $group['items'] = $filteredItems;
            $groups[] = $group;
        }

        return ['groups' => $groups, 'crumb' => $crumb];
    }
}
