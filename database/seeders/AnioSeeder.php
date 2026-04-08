<?php

namespace Database\Seeders;

use App\Models\Anio;
use Illuminate\Database\Seeder;

class AnioSeeder extends Seeder
{
    public function run(): void
    {
        $desde = (int) date('Y') - 2;
        $hasta = (int) date('Y') + 8;

        for ($y = $desde; $y <= $hasta; $y++) {
            Anio::query()->firstOrCreate(['anio' => $y]);
        }
    }
}
