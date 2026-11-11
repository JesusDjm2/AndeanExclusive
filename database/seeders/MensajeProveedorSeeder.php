<?php

namespace Database\Seeders;

use App\Models\MensajeProveedor;
use App\Models\Programa;
use App\Models\Proveedor;
use App\Models\Servicio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MensajeProveedorSeeder extends Seeder
{
    /**
     * Sembrar mensajes realistas de Reservas con los proveedores de lujo de Perú.
     * Cubre hilos esperando respuesta, confirmaciones de reserva, consultas y cancelaciones.
     */
    public function run(): void
    {
        MensajeProveedor::query()->delete();

        $usuarioReservas = User::whereHas('roles', fn ($q) => $q->where('name', 'reservas'))->first()
            ?? User::first();
        $usuarioId = $usuarioReservas?->id;

        $ahora = Carbon::now();

        // 1. SERVICIOS SOLICITADOS ESPERANDO RESPUESTA
        // Aquellos servicios con estado 'solicitado' cuyo último mensaje fue 'enviado' por Andean Exclusive.
        $serviciosSolicitados = Servicio::where('estado', 'solicitado')
            ->whereNotNull('proveedor_id')
            ->with(['programa', 'proveedor'])
            ->get();

        foreach ($serviciosSolicitados as $index => $servicio) {
            $prov = $servicio->proveedor;
            $prog = $servicio->programa;
            $fechaServ = $servicio->fecha ? Carbon::parse($servicio->fecha)->format('d/m/Y') : 'próximamente';

            MensajeProveedor::create([
                'proveedor_id' => $prov->id,
                'servicio_id' => $servicio->id,
                'programa_id' => $prog?->id,
                'user_id' => $usuarioId,
                'direccion' => 'enviado',
                'tipo' => 'solicitud',
                'para' => $prov->correo ?? 'reservas@proveedor.com',
                'asunto' => "Solicitud de Reserva: {$servicio->titulo} · Programa {$prog?->codigo}",
                'cuerpo' => "Estimado equipo de {$prov->nombre},\n\nPor medio del presente solicitamos formalmente la reserva para el siguiente servicio:\n- Servicio: {$servicio->titulo}\n- Fecha de prestación: {$fechaServ} a las {$servicio->hora}\n- Programa: {$prog?->codigo} ({$prog?->nombre})\n- Pasajeros: Acreditados en voucher VIP de Andean Exclusive.\n\nAgradeceremos confirmar disponibilidad y enviarnos su n.º de confirmación u orden a la brevedad.\n\nAtentamente,\nGabriela Pérez\nÁrea de Reservas · Andean Exclusive Tours\nreservas@andeanexclusive.com",
                'con_orden' => true,
                'resultado' => null,
                'error' => null,
                'created_at' => $ahora->copy()->subHours(6 + ($index * 4)),
                'updated_at' => $ahora->copy()->subHours(6 + ($index * 4)),
            ]);
        }

        // 2. HILOS COMPLETADOS (SOLICITUD ENVIADA + RESPUESTA CONFIRMADA DEL PROVEEDOR)
        $serviciosConfirmados = Servicio::where('estado', 'confirmado')
            ->whereNotNull('proveedor_id')
            ->whereNotNull('confirmacion')
            ->with(['programa', 'proveedor'])
            ->take(8)
            ->get();

        foreach ($serviciosConfirmados as $index => $servicio) {
            $prov = $servicio->proveedor;
            $prog = $servicio->programa;
            $fechaServ = $servicio->fecha ? Carbon::parse($servicio->fecha)->format('d/m/Y') : 'próximamente';
            $creadoHace = $ahora->copy()->subDays(2 + $index);

            // Mensaje 1: Solicitud enviada
            MensajeProveedor::create([
                'proveedor_id' => $prov->id,
                'servicio_id' => $servicio->id,
                'programa_id' => $prog?->id,
                'user_id' => $usuarioId,
                'direccion' => 'enviado',
                'tipo' => 'solicitud',
                'para' => $prov->correo ?? 'reservas@proveedor.com',
                'asunto' => "Solicitud de Servicio: {$servicio->titulo} · Ref. {$prog?->codigo}",
                'cuerpo' => "Estimados señores de {$prov->nombre},\n\nSolicitamos la confirmación del servicio de {$servicio->titulo} para la fecha {$fechaServ} correspondiente a nuestro programa {$prog?->codigo}.\n\nQuedamos a la espera de su código oficial.\n\nSaludos cordiales,\nReservas Andean Exclusive",
                'con_orden' => true,
                'resultado' => null,
                'error' => null,
                'created_at' => $creadoHace,
                'updated_at' => $creadoHace,
            ]);

            // Mensaje 2: Respuesta recibida confirmada
            MensajeProveedor::create([
                'proveedor_id' => $prov->id,
                'servicio_id' => $servicio->id,
                'programa_id' => $prog?->id,
                'user_id' => $usuarioId,
                'direccion' => 'recibido',
                'tipo' => 'respuesta',
                'para' => config('mail.reservas.address', 'reservas@andeanexclusive.com'),
                'asunto' => "RE: Solicitud de Servicio: {$servicio->titulo} · Confirmado: {$servicio->confirmacion}",
                'cuerpo' => "Estimada Gabriela / Equipo de Andean Exclusive,\n\nConfirmamos con éxito el servicio {$servicio->titulo} para la fecha solicitada.\n\n- N.º de confirmación oficial: {$servicio->confirmacion}\n- Tarifa pactada según tarifario maestro oficial.\n\nTodo se encuentra debidamente registrado en nuestro sistema operativo.\n\nCordialmente,\nÁrea de Operaciones & Reservas · {$prov->nombre}",
                'con_orden' => false,
                'resultado' => 'confirmado',
                'error' => null,
                'created_at' => $creadoHace->copy()->addHours(3),
                'updated_at' => $creadoHace->copy()->addHours(3),
            ]);
        }

        // 3. MENSAJES DE CONSULTA Y MODIFICACIÓN
        $provTren = Proveedor::where('nombre', 'PeruRail S.A.')->first() ?? Proveedor::first();
        if ($provTren) {
            MensajeProveedor::create([
                'proveedor_id' => $provTren->id,
                'servicio_id' => null,
                'programa_id' => null,
                'user_id' => $usuarioId,
                'direccion' => 'enviado',
                'tipo' => 'consulta',
                'para' => $provTren->correo ?? 'reservas@perurail.com.pe',
                'asunto' => 'Consulta de frecuencias y capacidad: Coche Observatorio Hiram Bingham Temporada 2027',
                'cuerpo' => "Estimado equipo de ventas corporativas PeruRail,\n\nQuisiéramos consultar la disponibilidad de bloqueo de hasta 8 espacios en el coche observatorio del tren Hiram Bingham para grupos privados de mayo y junio 2027.\n\nFavor indicarnos si aplican las mismas políticas tarifarias Aracari 2027.\n\nAtentamente,\nGabriela Pérez\nAndean Exclusive Tours",
                'con_orden' => false,
                'resultado' => null,
                'error' => null,
                'created_at' => $ahora->copy()->subDays(1),
                'updated_at' => $ahora->copy()->subDays(1),
            ]);
        }

        // 4. MENSAJE DE CANCELACIÓN
        $provTransporte = Proveedor::where('nombre', 'VIP Executive Transports Cusco S.A.C.')->first() ?? Proveedor::first();
        if ($provTransporte) {
            MensajeProveedor::create([
                'proveedor_id' => $provTransporte->id,
                'servicio_id' => null,
                'programa_id' => null,
                'user_id' => $usuarioId,
                'direccion' => 'enviado',
                'tipo' => 'cancelacion',
                'para' => $provTransporte->correo ?? 'operaciones@viptransports.pe',
                'asunto' => 'Anulación de Sprinter por modificación de itinerario de vuelo internacional',
                'cuerpo' => "Estimados señores de VIP Transports,\n\nPor motivos de reprogramación de vuelos de los pasajeros, solicitamos la cancelación del servicio de traslado sin penalidad conforme a las 48 horas de anticipación contractuales.\n\nAgradeceremos confirmar recepción.\n\nSaludos cordiales,\nGabriela Pérez",
                'con_orden' => false,
                'resultado' => null,
                'error' => null,
                'created_at' => $ahora->copy()->subDays(3),
                'updated_at' => $ahora->copy()->subDays(3),
            ]);
        }
    }
}
