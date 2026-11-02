<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Str;

/**
 * Quién gestiona qué en el panel. Todas las áreas consultan todo; cada una modifica solo lo suyo y el administrador, todo.
 * Regla por defecto: una ruta de escritura que no esté aquí queda solo para el administrador (lo nuevo nace cerrado).
 */
class Permisos
{
    public const ADMIN = 'administrador';

    /** Rol => nombre del área. */
    public const AREAS = [
        self::ADMIN => 'Administración',
        'operaciones' => 'Operaciones',
        'reservas' => 'Reservas',
        'contabilidad' => 'Contabilidad',
    ];

    /** Permiso => roles que lo tienen (además del administrador, que los tiene todos). */
    public const MATRIZ = [
        'programas.gestionar' => ['operaciones'],
        'programas.eliminar' => [],
        'consultas.gestionar' => ['operaciones'],
        'pasaportes.ver' => ['operaciones', 'reservas'],     // n.º y archivo: lo necesita quien reserva trenes, entradas y hoteles
        'cotizacion.ver' => ['operaciones', 'contabilidad'], // % de ganancia y costo base con los que se cotiza
        'margen.ver' => ['contabilidad'],                    // margen real del programa
        'servicios.gestionar' => ['reservas'],
        'proveedores.gestionar' => ['reservas'],
        'pagos.solicitar' => ['reservas'],
        'pagos.registrar' => ['contabilidad'],
        'cobros.gestionar' => ['contabilidad'],
        'tareas.gestionar' => ['operaciones', 'reservas', 'contabilidad'],
        'avisos.usar' => ['operaciones', 'reservas', 'contabilidad'],
        'agentes.gestionar' => [],
        'contenido.gestionar' => [],
        'usuarios.gestionar' => [],
    ];

    /** Permiso => rutas que exige. Las consultas (GET) no se listan: están abiertas a todas las áreas. */
    private const RUTAS = [
        'programas.gestionar' => [
            'programas.create', 'programas.store', 'programas.update', 'programas.enviar-correo', 'programas.notificaciones.store',
            'programas.duplicar', 'programas.duplicar.store', 'programas.plantilla.store', 'programas.plantilla.destroy',
            'clientes.create', 'clientes.store', 'clientes.update', 'clientes.destroy',
            'paxs.create', 'paxs.store', 'paxs.edit', 'paxs.update', 'paxs.destroy',
        ],
        'programas.eliminar' => ['programas.destroy'],
        'consultas.gestionar' => ['consultas.create', 'consultas.store', 'consultas.update', 'consultas.cotizar'],
        'servicios.gestionar' => ['servicios.estado', 'servicios.asignar', 'reservas.lote'],
        'proveedores.gestionar' => [
            'proveedors.create', 'proveedors.store', 'proveedors.edit', 'proveedors.update', 'proveedors.destroy',
            'categoriasproveedor.create', 'categoriasproveedor.store', 'categoriasproveedor.edit', 'categoriasproveedor.update', 'categoriasproveedor.destroy',
            'hotel.create', 'hotel.store', 'hotel.edit', 'hotel.update', 'hotel.destroy',
            'tarifas.create', 'tarifas.store', 'tarifas.edit', 'tarifas.update', 'tarifas.destroy',
            'habitacion.create', 'habitacion.store', 'habitacion.edit', 'habitacion.update', 'habitacion.destroy',
        ],
        'pagos.solicitar' => ['servicios.solicitar-pago'],
        'pagos.registrar' => ['servicios.pagar', 'servicios.pagos.anular', 'cuentas.pagar', 'cuentas.anular'],
        'cobros.gestionar' => ['programas.pagos.store', 'programas.pagos.destroy'],
        'tareas.gestionar' => ['programas.tareas.*'],
        'avisos.usar' => ['avisos.marcar', 'avisos.marcar-todas'],
    ];

    /** Formularios de edición que también sirven de ficha de consulta: la vista los muestra bloqueados a quien no gestiona. */
    private const FICHAS_DE_CONSULTA = ['programas.edit', 'clientes.edit'];

    public static function permiteRuta(User $user, string $metodo, ?string $ruta): bool
    {
        if ($user->hasRole(self::ADMIN)) {
            return true;
        }
        if (! $ruta) {
            return false;
        }

        $esConsulta = in_array($metodo, ['GET', 'HEAD'], true)
            && (! Str::endsWith($ruta, ['.create', '.edit', '.duplicar']) || in_array($ruta, self::FICHAS_DE_CONSULTA, true));
        if ($esConsulta) {
            return true;
        }

        foreach (self::RUTAS as $permiso => $patrones) {
            if (Str::is($patrones, $ruta)) {
                return $user->can($permiso);
            }
        }

        return false;
    }

    public static function area(?User $user): ?string
    {
        $rol = $user?->roles->first()?->name;

        return $rol ? (self::AREAS[$rol] ?? ucfirst($rol)) : null;
    }
}
