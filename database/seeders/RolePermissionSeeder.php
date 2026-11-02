<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Roles del panel, uno por área: qué gestiona cada uno está en App\Support\Permisos.
     * Los usuarios sin rol reciben administrador para no bloquear instalaciones existentes.
     */
    public function run(): void
    {
        if (! Schema::hasTable('roles')) {
            throw new \RuntimeException(
                'Falta la tabla `roles`. Ejecute las migraciones (p. ej. php artisan migrate) incluyendo create_permission_tables.'
            );
        }

        $guard = 'web';

        foreach (array_keys(\App\Support\Permisos::AREAS) as $name) {
            Role::findOrCreate($name, $guard);
        }

        User::query()->each(function (User $user): void {
            if ($user->roles()->count() === 0) {
                $user->assignRole('administrador');
            }
        });
    }
}
