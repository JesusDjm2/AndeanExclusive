<?php

namespace Database\Seeders;

use App\Models\Mes;
use Illuminate\Database\Seeder;

class MesSeeder extends Seeder
{
    public function run(): void
    {
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        foreach ($meses as $numero => $nombre) {
            Mes::query()->updateOrCreate(
                ['numero' => $numero],
                ['nombre' => $nombre]
            );
        }
    }
}
