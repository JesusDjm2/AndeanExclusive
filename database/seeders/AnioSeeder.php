<?php

namespace Database\Seeders;

use App\Models\Anio;
use App\Models\Programa;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AnioSeeder extends Seeder
{
    /**
     * Solo conserva los años que realmente tienen programas registrados, más el año en curso.
     * Como Programa crea el año automáticamente con Anio::firstOrCreate(),
     * no se pre-generan años vacíos a futuro.
     */
    public function run(): void
    {
        // 1. Asegurar que los años de programas existentes y el año actual existan
        $aniosUsados = Programa::withoutGlobalScope('sin_plantillas')
            ->whereNotNull('inicio')
            ->pluck('inicio')
            ->map(fn ($fecha) => (int) Carbon::parse($fecha)->year)
            ->push((int) date('Y'))
            ->unique();

        foreach ($aniosUsados as $y) {
            Anio::firstOrCreate(['anio' => $y]);
        }

        // 2. Eliminar años vacíos sin ningún programa (evita rangos vacíos como 2028-2034)
        $idsConPrograma = Programa::withoutGlobalScope('sin_plantillas')
            ->whereNotNull('anio_id')
            ->pluck('anio_id')
            ->unique();

        Anio::whereNotIn('id', $idsConPrograma)
            ->where('anio', '!=', (int) date('Y'))
            ->delete();
    }
}
