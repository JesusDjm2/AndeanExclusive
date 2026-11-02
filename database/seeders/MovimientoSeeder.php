<?php

namespace Database\Seeders;

use App\Models\Movimiento;
use App\Models\Programa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MovimientoSeeder extends Seeder
{
    /**
     * Bitácora de auditoría histórica para reflejar actividad real del equipo en las últimas dos semanas.
     */
    public function run(): void
    {
        Movimiento::query()->delete();

        $usuarios = User::all()->keyBy('email');
        $maria = $usuarios->get('operaciones@andeanexclusive.com') ?? User::first();
        $david = $usuarios->get('mirandadjmdjm@gmail.com') ?? User::first();
        $edith = $usuarios->get('contabilidad@andean.com') ?? User::first();
        $gabriela = $usuarios->get('reservas@andeanexclusive.com') ?? User::first();

        $p1 = Programa::withoutGlobalScope('sin_plantillas')->where('codigo', 'AET-2026-0101')->first();
        $p2 = Programa::withoutGlobalScope('sin_plantillas')->where('codigo', 'AET-2026-0102')->first();
        $p3 = Programa::withoutGlobalScope('sin_plantillas')->where('codigo', 'AET-2026-0103')->first();
        $p4 = Programa::withoutGlobalScope('sin_plantillas')->where('codigo', 'AET-2026-0104')->first();

        $hoy = Carbon::now();

        $bitacora = [
            [
                'user_id' => $maria?->id,
                'usuario' => $maria?->name ?? 'María Fernanda',
                'area' => 'Operaciones',
                'verbo' => 'creó',
                'modulo' => 'Programa',
                'titulo' => 'The Ultimate Peru Experience: Lima Gourmet, Andes & Belmond Hiram Bingham 8D/7N',
                'programa_id' => $p1?->id,
                'programa_codigo' => $p1?->codigo,
                'cambios' => null,
                'created_at' => $hoy->copy()->subDays(12),
            ],
            [
                'user_id' => $gabriela?->id,
                'usuario' => $gabriela?->name ?? 'Gabriela Pérez',
                'area' => 'Reservas',
                'verbo' => 'confirmó',
                'modulo' => 'Servicio',
                'titulo' => 'Tren Belmond Hiram Bingham Poroy → Machu Picchu (TK-HB-98124)',
                'programa_id' => $p1?->id,
                'programa_codigo' => $p1?->codigo,
                'cambios' => [['campo' => 'Estado', 'de' => 'solicitado', 'a' => 'confirmado']],
                'created_at' => $hoy->copy()->subDays(10),
            ],
            [
                'user_id' => $edith?->id,
                'usuario' => $edith?->name ?? 'Edith Dueñas',
                'area' => 'Contabilidad',
                'verbo' => 'registró',
                'modulo' => 'Pago',
                'titulo' => 'Depósito de $5,900.00 USD vía Transferencia SWIFT-JP-849102',
                'programa_id' => $p1?->id,
                'programa_codigo' => $p1?->codigo,
                'cambios' => [['campo' => 'Monto', 'a' => '5900.00'], ['campo' => 'Tipo', 'a' => 'deposito']],
                'created_at' => $hoy->copy()->subDays(9),
            ],
            [
                'user_id' => $maria?->id,
                'usuario' => $maria?->name ?? 'María Fernanda',
                'area' => 'Operaciones',
                'verbo' => 'actualizó',
                'modulo' => 'Pasajero',
                'titulo' => 'Beatrice Kensington: Anotación médica de alergia severa a frutos secos',
                'programa_id' => $p1?->id,
                'programa_codigo' => $p1?->codigo,
                'cambios' => [['campo' => 'Alergias', 'a' => 'Alérgica severa a frutos secos / maní (lleva Epipen)']],
                'created_at' => $hoy->copy()->subDays(8),
            ],
            [
                'user_id' => $david?->id,
                'usuario' => $david?->name ?? 'David Miranda',
                'area' => 'Administración',
                'verbo' => 'aprobó',
                'modulo' => 'Programa',
                'titulo' => 'Confirmación de itinerario y margen comercial para AET-2026-0102',
                'programa_id' => $p2?->id,
                'programa_codigo' => $p2?->codigo,
                'cambios' => [['campo' => 'Estado', 'de' => 'cotizacion', 'a' => 'confirmado']],
                'created_at' => $hoy->copy()->subDays(7),
            ],
            [
                'user_id' => $gabriela?->id,
                'usuario' => $gabriela?->name ?? 'Gabriela Pérez',
                'area' => 'Reservas',
                'verbo' => 'reservó',
                'modulo' => 'Hotel',
                'titulo' => 'Bloqueo de Suite Valle Sagrado en Tambo del Inka (3 noches)',
                'programa_id' => $p2?->id,
                'programa_codigo' => $p2?->codigo,
                'cambios' => null,
                'created_at' => $hoy->copy()->subDays(6),
            ],
            [
                'user_id' => $edith?->id,
                'usuario' => $edith?->name ?? 'Edith Dueñas',
                'area' => 'Contabilidad',
                'verbo' => 'registró',
                'modulo' => 'Pago',
                'titulo' => 'Pago de saldo final $6,300.00 USD con tarjeta Visa para AET-2026-0102',
                'programa_id' => $p2?->id,
                'programa_codigo' => $p2?->codigo,
                'cambios' => null,
                'created_at' => $hoy->copy()->subDays(5),
            ],
            [
                'user_id' => $maria?->id,
                'usuario' => $maria?->name ?? 'María Fernanda',
                'area' => 'Operaciones',
                'verbo' => 'asignó',
                'modulo' => 'Operaciones',
                'titulo' => 'Asignación de guía oficial bilingüe AGOTUR para Circuito Machu Picchu',
                'programa_id' => $p2?->id,
                'programa_codigo' => $p2?->codigo,
                'cambios' => null,
                'created_at' => $hoy->copy()->subDays(3),
            ],
            [
                'user_id' => $gabriela?->id,
                'usuario' => $gabriela?->name ?? 'Gabriela Pérez',
                'area' => 'Reservas',
                'verbo' => 'confirmó',
                'modulo' => 'Servicio',
                'titulo' => 'Boletos DDC Llaqta Machu Picchu Circuito 2 para AET-2026-0103',
                'programa_id' => $p3?->id,
                'programa_codigo' => $p3?->codigo,
                'cambios' => [['campo' => 'N.º Confirmación', 'a' => 'DDC-MP-99410']],
                'created_at' => $hoy->copy()->subDays(2),
            ],
            [
                'user_id' => $maria?->id,
                'usuario' => $maria?->name ?? 'María Fernanda',
                'area' => 'Operaciones',
                'verbo' => 'envió',
                'modulo' => 'Correo al cliente',
                'titulo' => 'Briefing final de viaje y contacto de chofer enviado a Oliver Vance',
                'programa_id' => $p4?->id,
                'programa_codigo' => $p4?->codigo,
                'cambios' => null,
                'created_at' => $hoy->copy()->subDay(),
            ],
            [
                'user_id' => $maria?->id,
                'usuario' => $maria?->name ?? 'María Fernanda',
                'area' => 'Operaciones',
                'verbo' => 'completó',
                'modulo' => 'Tarea',
                'titulo' => 'Recepción y bienvenida de Dr. Arthur Kensington en Aeropuerto de Cusco',
                'programa_id' => $p1?->id,
                'programa_codigo' => $p1?->codigo,
                'cambios' => [['campo' => 'Completada', 'a' => 'Sí']],
                'created_at' => $hoy->copy()->subHours(5),
            ],
        ];

        foreach ($bitacora as $b) {
            Movimiento::create($b);
        }
    }
}
