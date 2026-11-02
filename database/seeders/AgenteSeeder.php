<?php

namespace Database\Seeders;

use App\Models\Agente;
use Illuminate\Database\Seeder;

class AgenteSeeder extends Seeder
{
    /**
     * Counters y diseñadores de viajes senior de Andean Exclusive.
     */
    public function run(): void
    {
        $agentes = [
            [
                'nombre' => 'David Miranda',
                'telefono' => '+51 921 136 755',
                'email' => 'mirandadjmdjm@gmail.com',
            ],
            [
                'nombre' => 'Valeria Quispe',
                'telefono' => '+51 984 312 870',
                'email' => 'valeria.quispe@andeanexclusive.com',
            ],
            [
                'nombre' => 'Carlos Mendoza',
                'telefono' => '+51 984 551 290',
                'email' => 'operaciones@andeanexclusive.com',
            ],
            [
                'nombre' => 'Raquel Bellota',
                'telefono' => '+51 951 256 358',
                'email' => 'raquel.bellota@andeanexclusive.com',
            ],
            [
                'nombre' => 'Diego Alarcón',
                'telefono' => '+51 984 760 115',
                'email' => 'diego.alarcon@andeanexclusive.com',
            ],
        ];

        foreach ($agentes as $a) {
            Agente::updateOrCreate(
                ['email' => $a['email']],
                $a
            );
        }
    }
}
