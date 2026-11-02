<?php

namespace App\Support;

use App\Models\Aceptacion;
use App\Models\Agente;
use App\Models\Tarifa;
use App\Models\Cliente;
use App\Models\Consulta;
use App\Models\HabitacionFecha;
use App\Models\Hotel;
use App\Models\Movimiento;
use App\Models\Notificacion;
use App\Models\Pago;
use App\Models\Pax;
use App\Models\Programa;
use App\Models\ProgramaDia;
use App\Models\Proveedor;
use App\Models\ProveedorPago;
use App\Models\Servicio;
use App\Models\Tarea;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Registro de movimientos de la agencia: quién hizo qué, cuándo y qué cambió.
 * Solo se guardan los campos listados (lo no listado, como contraseñas o tokens, nunca entra).
 * 'sinValor': se anota que cambió, sin el dato (datos personales y textos largos). 'reservados': solo los ve quien puede ver la cotización.
 */
class Bitacora
{
    private const MODELOS = [
        Programa::class => [
            'modulo' => 'Programa',
            'campos' => [
                'nombre' => 'Nombre', 'codigo' => 'Código', 'estado' => 'Estado', 'cliente_id' => 'Cliente', 'inicio' => 'Inicio', 'fin' => 'Fin',
                'lang' => 'Idioma', 'precioAdulto' => 'Costo adulto', 'precioChild' => 'Costo niño', 'porcentaje_ganancia' => 'Ganancia (%)',
                'tipo_cambio' => 'Tipo de cambio', 'suplemento_individual' => 'Suplemento individual',
                'habitaciones_individuales' => 'Habitaciones individuales', 'presentacion' => 'Presentación', 'portada' => 'Portada',
            ],
            'sinValor' => ['presentacion'],
            'reservados' => ['precioAdulto', 'precioChild', 'porcentaje_ganancia'],
        ],
        Servicio::class => [
            'modulo' => 'Servicio',
            'campos' => [
                'titulo' => 'Servicio', 'tipo' => 'Tipo', 'fecha' => 'Fecha', 'hora' => 'Hora', 'proveedor_id' => 'Proveedor', 'estado' => 'Estado',
                'confirmacion' => 'N.º de confirmación', 'punto_encuentro' => 'Punto de encuentro', 'costo' => 'Costo real', 'costo_estimado' => 'Costo estimado',
                'moneda' => 'Moneda', 'costo_por' => 'Se cobra', 'visible_cliente' => 'Visible para el cliente', 'descripcion' => 'Descripción', 'notas' => 'Notas',
            ],
            'sinValor' => ['descripcion', 'notas'],
        ],
        HabitacionFecha::class => [
            'modulo' => 'Alojamiento',
            'campos' => ['habitacion_id' => 'Habitación', 'cantidad' => 'Cantidad', 'fecha_ingreso' => 'Ingreso', 'fecha_salida' => 'Salida'],
        ],
        ProgramaDia::class => [
            'modulo' => 'Itinerario',
            'campos' => ['titulo' => 'Título', 'descripcion' => 'Descripción'],
            'sinValor' => ['descripcion'],
        ],
        Pax::class => [
            'modulo' => 'Pasajero',
            'campos' => [
                'nombre' => 'Nombre', 'fecha_nacimiento' => 'Fecha de nacimiento', 'sexo' => 'Sexo', 'pais' => 'País', 'pasaporte' => 'N.º de pasaporte',
                'pasaporte_vencimiento' => 'Vencimiento del pasaporte', 'pasaporte_archivo' => 'Archivo del pasaporte', 'dieta' => 'Dieta', 'alergias' => 'Alergias',
                'condiciones_medicas' => 'Condiciones médicas', 'emergencia_nombre' => 'Contacto de emergencia', 'emergencia_telefono' => 'Teléfono de emergencia',
            ],
            'sinValor' => ['fecha_nacimiento', 'sexo', 'pasaporte', 'pasaporte_vencimiento', 'pasaporte_archivo', 'dieta', 'alergias', 'condiciones_medicas', 'emergencia_nombre', 'emergencia_telefono'],
        ],
        Pago::class => [
            'modulo' => 'Cobro al cliente',
            'campos' => ['monto' => 'Monto', 'fecha' => 'Fecha', 'tipo' => 'Tipo', 'metodo' => 'Método', 'referencia' => 'Referencia'],
            'verbos' => ['creado' => 'registró', 'eliminado' => 'anuló'],
        ],
        ProveedorPago::class => [
            'modulo' => 'Pago a proveedor',
            'campos' => ['monto' => 'Monto', 'fecha' => 'Fecha', 'metodo' => 'Método', 'referencia' => 'Referencia'],
            'verbos' => ['creado' => 'registró', 'eliminado' => 'anuló'],
        ],
        Tarea::class => [
            'modulo' => 'Tarea',
            'campos' => ['titulo' => 'Tarea', 'fecha_limite' => 'Fecha límite', 'completada_at' => 'Completada'],
        ],
        Notificacion::class => [
            'modulo' => 'Correo al cliente',
            'campos' => ['tipo' => 'Tipo'],
            'verbos' => ['creado' => 'envió'],
        ],
        Cliente::class => [
            'modulo' => 'Cliente',
            'campos' => ['nombre' => 'Nombre', 'empresa' => 'Empresa', 'email' => 'Correo', 'telefono' => 'Teléfono', 'pais' => 'País'],
        ],
        Aceptacion::class => [
            'modulo' => 'Aceptación',
            'campos' => ['nombre' => 'Nombre'],
            'verbos' => ['creado' => 'registró'],
        ],
        Tarifa::class => [
            'modulo' => 'Tarifa',
            'campos' => ['concepto' => 'Concepto', 'desde' => 'Desde', 'hasta' => 'Hasta', 'precio' => 'Precio', 'moneda' => 'Moneda'],
        ],
        Consulta::class => [
            'modulo' => 'Consulta',
            'campos' => ['nombre' => 'Nombre', 'estado' => 'Estado', 'motivo_perdida' => 'Motivo', 'notas' => 'Notas'],
            'sinValor' => ['notas'],
        ],
        Proveedor::class => [
            'modulo' => 'Proveedor',
            'campos' => ['nombre' => 'Nombre', 'categoria_id' => 'Categoría', 'telefono' => 'Teléfono', 'ruc' => 'RUC', 'direccion' => 'Dirección'],
        ],
        Hotel::class => [
            'modulo' => 'Hotel',
            'campos' => ['nombre' => 'Nombre', 'ruc' => 'RUC', 'direccion' => 'Dirección', 'telefono' => 'Teléfono', 'correo' => 'Correo'],
        ],
        Agente::class => [
            'modulo' => 'Agente',
            'campos' => ['nombre' => 'Nombre', 'telefono' => 'Teléfono', 'email' => 'Correo'],
        ],
        User::class => [
            'modulo' => 'Usuario',
            'campos' => ['name' => 'Nombre', 'email' => 'Correo'],
        ],
    ];

    private const VERBOS = ['creado' => 'creó', 'actualizado' => 'actualizó', 'eliminado' => 'eliminó'];

    /** Módulos para el filtro de la pantalla de movimientos. */
    public static function modulos(): array
    {
        return array_column(self::MODELOS, 'modulo');
    }

    /** Evento de un modelo (lo llama el trait RegistraMovimientos). */
    public static function registrar(Model $modelo, string $evento): void
    {
        $config = self::MODELOS[$modelo::class] ?? null;
        if (! $config) {
            return;
        }

        $cambios = [];
        if ($evento === 'eliminado' && filled($modelo->motivo_anulacion)) {
            $cambios = [['campo' => 'Motivo', 'a' => $modelo->motivo_anulacion]];
        }
        if ($evento === 'actualizado') {
            $cambios = self::cambios($modelo, $config);
            if (! $cambios) {
                return;
            }
        }

        self::guardar($modelo, $config['verbos'][$evento] ?? self::VERBOS[$evento], $config['modulo'], $cambios);
    }

    /** Hecho puntual que no es un cambio de datos: "consultó el pasaporte de", "solicitó el pago de", "cambió el rol de"... */
    public static function anotar(string $verbo, Model $modelo, array $cambios = []): void
    {
        self::guardar($modelo, $verbo, self::MODELOS[$modelo::class]['modulo'] ?? class_basename($modelo), $cambios);
    }

    /** Un fallo al anotar nunca debe impedir la operación del usuario. */
    private static function guardar(Model $modelo, string $verbo, string $modulo, array $cambios): void
    {
        // Seeders y tareas de consola sin usuario no son movimientos de la agencia.
        if (app()->runningInConsole() && ! Auth::check()) {
            return;
        }

        try {
            $usuario = Auth::user();
            $programa = self::programaDe($modelo);

            Movimiento::create([
                'user_id' => $usuario?->id,
                'usuario' => $usuario?->name ?? (request()->routeIs('itinerario.*') ? 'Cliente (enlace privado)' : 'Sistema'),
                'area' => Permisos::area($usuario),
                'verbo' => $verbo,
                'modulo' => $modulo,
                'titulo' => mb_substr(self::titulo($modelo), 0, 255),
                'programa_id' => $programa?->id,
                'programa_codigo' => $programa?->codigo,
                'cambios' => $cambios ?: null,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private static function cambios(Model $modelo, array $config): array
    {
        $cambios = [];
        foreach (array_intersect_key($config['campos'], $modelo->getChanges()) as $campo => $etiqueta) {
            $antes = $modelo->getOriginal($campo);
            $ahora = $modelo->getAttribute($campo);
            $de = self::valor($modelo, $campo, $antes);
            $a = self::valor($modelo, $campo, $ahora);
            // 150 y 150.00, o 09:00 y 09:00:00, son el mismo dato escrito distinto: no es un cambio.
            if ($de === $a || (is_numeric($de) && is_numeric($a) && (float) $de === (float) $a)) {
                continue;
            }

            $oculto = in_array($campo, $config['sinValor'] ?? [], true);
            $cambios[] = array_filter([
                'campo' => $etiqueta,
                'de' => $oculto ? null : $de,
                'a' => $oculto ? null : $a,
                'reservado' => in_array($campo, $config['reservados'] ?? [], true),
            ], fn ($v) => $v !== null && $v !== false);
        }

        return $cambios;
    }

    private static function valor(Model $modelo, string $campo, mixed $valor): ?string
    {
        if ($valor === null || $valor === '') {
            return null;
        }

        return match (true) {
            $campo === 'hora' => substr((string) $valor, 0, 5),
            $campo === 'estado' => is_string($etiqueta = ($modelo::ESTADOS[$valor] ?? $valor)) ? $etiqueta : (string) $valor,
            $campo === 'proveedor_id' => Proveedor::whereKey($valor)->value('nombre') ?? (string) $valor,
            $campo === 'cliente_id' => Cliente::whereKey($valor)->value('nombre') ?? (string) $valor,
            $campo === 'habitacion_id' => \App\Models\Habitacion::with('hotel')->find($valor)?->tipo ?? (string) $valor,
            $valor instanceof CarbonInterface => $valor->format('d/m/Y'),
            is_bool($valor) => $valor ? 'Sí' : 'No',
            default => mb_substr((string) $valor, 0, 120),
        };
    }

    private static function titulo(Model $m): string
    {
        return match ($m::class) {
            ProgramaDia::class => 'Día ' . $m->fecha?->format('d/m/Y'),
            Aceptacion::class => 'Aceptación de ' . $m->nombre,
            Tarifa::class => trim($m->de_quien . ' · ' . $m->que),
            HabitacionFecha::class => $m->etiqueta . ($m->habitacion?->hotel ? ' · ' . $m->habitacion->hotel->nombre : ''),
            Pago::class => '$' . number_format((float) $m->monto, 2),
            ProveedorPago::class => Servicio::formatoMonto((float) $m->monto, $m->moneda) . ' · ' . ($m->servicio?->titulo ?? 'servicio'),
            Notificacion::class => Notificacion::TIPOS[$m->tipo] ?? (string) $m->tipo,
            Programa::class, Pax::class, Cliente::class, Consulta::class, Proveedor::class, Hotel::class, Agente::class => (string) $m->nombre,
            User::class => (string) $m->name,
            default => (string) ($m->titulo ?? ''),
        };
    }

    private static function programaDe(Model $m): ?Programa
    {
        return match ($m::class) {
            Programa::class => $m,
            Servicio::class, ProgramaDia::class, HabitacionFecha::class, Pax::class, Pago::class, Tarea::class, Notificacion::class, Aceptacion::class => $m->programa,
            ProveedorPago::class => $m->servicio?->programa,
            default => null,
        };
    }
}
