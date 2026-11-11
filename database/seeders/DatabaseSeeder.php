<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with authentic Peruvian luxury travel agency data.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            MesSeeder::class,
            AnioSeeder::class,
            PeruProveedoresHotelesSeeder::class,
            TarifarioAracari2027Seeder::class,
            AgenteSeeder::class,
            ProgramaSeeder::class,
            ConsultaSeeder::class,
            AvisosSeeder::class,
            MovimientoSeeder::class,
        ]);
    }
}
