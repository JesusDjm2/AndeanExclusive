<?php

namespace Database\Seeders;

use App\Models\Aviso;
use App\Models\AvisoLectura;
use App\Models\Programa;
use App\Models\User;
use Illuminate\Database\Seeder;

class AvisosSeeder extends Seeder
{
    /**
     * Avisos internos entre áreas (Operaciones, Reservas, Contabilidad) sobre hechos que requieren atención.
     */
    public function run(): void
    {
        AvisoLectura::query()->delete();
        Aviso::query()->delete();

        $usuarios = User::all()->keyBy('email');
        $usuarioOp = $usuarios->get('operaciones@andeanexclusive.com') ?? User::first();
        $usuarioAdmin = $usuarios->get('mirandadjmdjm@gmail.com') ?? User::first();

        $p1 = Programa::withoutGlobalScope('sin_plantillas')->where('codigo', 'AET-2026-0101')->first();
        $p2 = Programa::withoutGlobalScope('sin_plantillas')->where('codigo', 'AET-2026-0102')->first();
        $p3 = Programa::withoutGlobalScope('sin_plantillas')->where('codigo', 'AET-2026-0103')->first();

        $avisos = [
            [
                'area' => 'operaciones',
                'tipo' => 'alerta_medica',
                'requiere_accion' => true,
                'titulo' => 'Alerta médica: Pasajera Beatrice Kensington reporta alergia severa al maní',
                'detalle' => 'Verificar que cocina de Chicha y Belmond Sanctuary Lodge no usen frutos secos ni aceite de cacahuate en sus preparaciones.',
                'accion' => 'Ver ficha de pasajeros',
                'ruta' => 'programas.edit',
                'ruta_params' => $p1 ? [$p1->id] : [],
                'ancla' => 'pasajeros',
                'clave' => 'alerta_medica_p1',
                'veces' => 1,
                'programa_id' => $p1?->id,
                'programa_codigo' => $p1?->codigo,
                'actor_id' => $usuarioOp?->id,
                'actor_nombre' => $usuarioOp?->name,
                'resuelto_at' => null,
            ],
            [
                'area' => 'reservas',
                'tipo' => 'servicio_por_confirmar',
                'requiere_accion' => true,
                'titulo' => 'Reconfirmar van Sprinter y chofer bilingüe para viaje Cusco ↔ Puno',
                'detalle' => 'El programa AET-2026-0103 sale en 3 días. Confirmar con VIP Executive Transports placa y celular del conductor.',
                'accion' => 'Gestionar servicios',
                'ruta' => 'operaciones.diaria',
                'ruta_params' => [],
                'ancla' => 'servicios',
                'clave' => 'reserva_van_p3',
                'veces' => 1,
                'programa_id' => $p3?->id,
                'programa_codigo' => $p3?->codigo,
                'actor_id' => $usuarioAdmin?->id,
                'actor_nombre' => $usuarioAdmin?->name,
                'resuelto_at' => null,
            ],
            [
                'area' => 'contabilidad',
                'tipo' => 'cobro_pendiente',
                'requiere_accion' => true,
                'titulo' => 'Saldo pendiente de cobro ($6,800.00 USD) para programa AET-2026-0103',
                'detalle' => 'El viaje inicia en 3 días. Enviar recordatorio de pago a Abercrombie & Kent Australia.',
                'accion' => 'Ver cobranza',
                'ruta' => 'programas.edit',
                'ruta_params' => $p3 ? [$p3->id] : [],
                'ancla' => 'pagos',
                'clave' => 'cobro_saldo_p3',
                'veces' => 1,
                'programa_id' => $p3?->id,
                'programa_codigo' => $p3?->codigo,
                'actor_id' => $usuarioOp?->id,
                'actor_nombre' => $usuarioOp?->name,
                'resuelto_at' => null,
            ],
            [
                'area' => 'operaciones',
                'tipo' => 'vuelo_confirmado',
                'requiere_accion' => false,
                'titulo' => 'Vuelo LA2041 arribó a tiempo en Cusco (Dr. Kensington)',
                'detalle' => 'Chofer Edwin Huamán reporta que los pasajeros ya están a bordo de la van rumbo al Hotel Monasterio.',
                'accion' => 'Ver operación',
                'ruta' => 'operaciones.diaria',
                'ruta_params' => [],
                'ancla' => 'diaria',
                'clave' => 'arribo_p1',
                'veces' => 1,
                'programa_id' => $p1?->id,
                'programa_codigo' => $p1?->codigo,
                'actor_id' => $usuarioOp?->id,
                'actor_nombre' => $usuarioOp?->name,
                'resuelto_at' => now()->subDay(),
            ],
        ];

        foreach ($avisos as $av) {
            Aviso::create($av);
        }
    }
}
