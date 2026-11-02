<?php

namespace App\Support;

use App\Models\Consulta;
use App\Models\HabitacionFecha;
use App\Models\Pago;
use App\Models\Pax;
use App\Models\Programa;
use App\Models\ProveedorPago;
use App\Models\Servicio;

/**
 * Qué hecho avisa a qué área y qué se le pide hacer. Toda la lógica está aquí para poder revisarla de un vistazo.
 * Se apoya en los eventos de los modelos: el aviso sale igual sea cual sea la pantalla desde la que se hizo el cambio.
 * Criterio: avisar solo a quien debe saberlo o actuar; el detalle de todo lo demás está en Movimientos.
 */
class ReglasDeAvisos
{
    private const CON_RESERVA = ['solicitado', 'confirmado'];

    public static function registrar(): void
    {
        // Instanciar antes: así estas reglas corren después de los eventos propios del modelo (p. ej. recalcular el pago).
        foreach ([Servicio::class, Programa::class, Pago::class, ProveedorPago::class, Pax::class, Consulta::class] as $modelo) {
            new $modelo;
        }

        $regla = fn (callable $fn) => fn ($modelo) => rescue(fn () => $fn($modelo));

        Servicio::created($regla(self::servicioCreado(...)));
        Servicio::deleted($regla(self::servicioEliminado(...)));
        Programa::created($regla(self::programaCreado(...)));
        Programa::updated($regla(self::programaActualizado(...)));
        Pago::created($regla(self::cobroRegistrado(...)));
        Pago::deleted($regla(self::cobroAnulado(...)));
        ProveedorPago::created($regla(self::pagoRegistrado(...)));
        ProveedorPago::deleted($regla(self::pagoAnulado(...)));
        HabitacionFecha::created($regla(self::alojamientoCambiado(...)));
        HabitacionFecha::updated($regla(self::alojamientoCambiado(...)));
        HabitacionFecha::deleted($regla(self::alojamientoCambiado(...)));
        Pax::created($regla(self::datosDelCliente(...)));
        Pax::updated($regla(self::datosDelCliente(...)));
        Consulta::created($regla(self::consultaNueva(...)));
    }

    // ---- Destinos: llevan a donde se hace lo que se pide ----

    /** Bandeja de Reservas filtrada por el programa, abierta en la pestaña donde está el servicio. */
    private static function aReservas(Programa $programa, ?Servicio $servicio = null, string $bandeja = 'por_reservar'): array
    {
        $bandeja = $servicio ? match ($servicio->estado) {
            'sin_asignar' => 'sin_asignar',
            'solicitado' => 'solicitados',
            'confirmado' => 'confirmados',
            'cancelado' => 'cancelados',
            default => 'por_reservar',
        } : $bandeja;

        return ['ruta' => 'reservas.index', 'params' => ['bandeja' => $bandeja, 'periodo' => 'todos', 'q' => $programa->codigo], 'ancla' => $servicio ? "servicio-{$servicio->id}" : null];
    }

    private static function aFicha(Programa $programa, string $pestana, ?Servicio $servicio = null): array
    {
        return ['ruta' => 'programas.edit', 'params' => ['programa' => $programa->id, 'tab' => $pestana], 'ancla' => $servicio ? "servicio-{$servicio->id}" : null];
    }

    private static function etiqueta(Servicio $s): string
    {
        return "{$s->titulo} ({$s->fecha->format('d/m')})";
    }

    // ---- Servicios ----

    private static function servicioCreado(Servicio $s): void
    {
        $p = $s->programa;
        // En una cotización todavía no hay nada que reservar.
        if ($p?->estado !== 'confirmado' || ! in_array($s->estado, ['sin_asignar', 'pendiente'], true)) {
            return;
        }

        $d = self::aReservas($p, $s);
        Avisos::enviar('reservas', 'servicio_nuevo', "Hay servicios por reservar en {$p->codigo}", self::etiqueta($s), 'Reservar', $d['ruta'], $d['params'], null, "por_reservar:{$p->id}", $p);
    }

    /** Lo llama el propio modelo, antes de recalcular su pago (ver Servicio::booted). */
    public static function servicioCambiado(Servicio $s): void
    {
        rescue(fn () => self::servicioActualizado($s));
    }

    private static function servicioActualizado(Servicio $s): void
    {
        $p = $s->programa;
        if (! $p) {
            return;
        }

        if ($s->wasChanged('estado')) {
            self::cambioDeEstado($s, $p);
        }

        // Reservas pidió el pago a Contabilidad (o retiró la solicitud).
        if ($s->wasChanged('pago_solicitado_at')) {
            if ($s->pago_solicitado_at) {
                Avisos::enviar(
                    'contabilidad', 'pago_solicitado', "Pago solicitado: {$s->titulo}",
                    trim(($s->proveedor?->nombre ?? 'Sin proveedor') . ' · saldo ' . Servicio::formatoMonto($s->saldo_proveedor, $s->moneda) . " · {$p->codigo}"),
                    'Registrar pago', 'cuentas.index', ['filtro' => 'solicitadas', 'proveedor_id' => $s->proveedor_id], "servicio-{$s->id}", "pago:{$s->id}", $p,
                );
            } else {
                Avisos::resolver("pago:{$s->id}");
            }
        }

        // El costo real afecta lo que se debe al proveedor y el margen.
        if ($s->wasChanged('costo') && $s->costo !== null) {
            $antes = $s->getOriginal('costo');
            $supera = $s->costo_estimado !== null && (float) $s->costo > (float) $s->costo_estimado + 0.009;
            $yaConfirmado = $antes !== null && $s->estado === 'confirmado';
            if ($supera || $yaConfirmado) {
                $f = fn ($monto) => Servicio::formatoMonto((float) $monto, $s->moneda);
                $d = self::aFicha($p, 'precios');
                Avisos::enviar(
                    'contabilidad', 'costo', ($supera ? 'Costo sobre lo cotizado: ' : 'Cambió el costo: ') . $s->titulo,
                    collect([$antes !== null ? "Antes {$f($antes)}" : null, "Ahora {$f($s->costo)}", $s->costo_estimado !== null ? "Cotizado {$f($s->costo_estimado)}" : null, $p->codigo])->filter()->implode(' · '),
                    'Revisar margen', $d['ruta'], $d['params'], null, "costo:{$s->id}", $p,
                );
            }
        }

        // Cambió cuándo se presta un servicio ya pedido o confirmado: el proveedor debe enterarse.
        $horaCambio = substr((string) $s->getOriginal('hora'), 0, 5) !== substr((string) $s->hora, 0, 5);
        if (in_array($s->estado, self::CON_RESERVA, true) && ! $s->wasChanged('estado') && ($s->wasChanged('fecha') || $horaCambio)) {
            $d = self::aReservas($p, $s);
            Avisos::enviar(
                'reservas', 'servicio_fecha', "Cambió la fecha u hora: {$s->titulo}",
                "Ahora {$s->fecha->format('d/m/Y')}" . ($s->hora_corta ? " a las {$s->hora_corta}" : '') . ' · ' . ($s->proveedor?->nombre ?? '') . " · {$p->codigo}",
                'Avisar al proveedor', $d['ruta'], $d['params'], $d['ancla'], "fecha:{$s->id}", $p,
            );
        }
    }

    private static function cambioDeEstado(Servicio $s, Programa $p): void
    {
        $d = self::aFicha($p, 'itinerario', $s);

        if ($s->estado === 'confirmado') {
            $abiertos = $p->servicios()->abiertos()->count();
            if ($abiertos === 0) {
                Avisos::resolver("por_reservar:{$p->id}");
            }
            Avisos::enviar(
                'operaciones', 'servicio_confirmado',
                $abiertos === 0 ? "Todos los servicios de {$p->codigo} están confirmados" : "Servicios confirmados en {$p->codigo}",
                $abiertos === 0 ? 'Último confirmado: ' . self::etiqueta($s) : self::etiqueta($s) . " · faltan {$abiertos}",
                'Ver itinerario', $d['ruta'], $d['params'], null, "confirmados:{$p->id}", $p, false,
            );
        }

        if ($s->estado === 'cancelado') {
            Avisos::resolver("pago:{$s->id}");
            if (! $p->servicios()->abiertos()->exists()) {
                Avisos::resolver("por_reservar:{$p->id}");
            }
            Avisos::enviar(
                'operaciones', 'servicio_cancelado', "Servicio cancelado: {$s->titulo}", self::etiqueta($s) . " · {$p->codigo}",
                'Revisar itinerario', $d['ruta'], $d['params'], $d['ancla'], null, $p,
            );
            if ($s->pagos()->exists()) {
                $h = self::aFicha($p, 'itinerario', $s);
                Avisos::enviar(
                    'contabilidad', 'servicio_cancelado', "Servicio cancelado con pagos: {$s->titulo}", 'Revisa si corresponde devolución del proveedor · ' . $p->codigo,
                    'Revisar pagos', $h['ruta'], $h['params'], $h['ancla'], null, $p,
                );
            }
        }
    }

    private static function servicioEliminado(Servicio $s): void
    {
        Avisos::resolver("pago:{$s->id}");
        $p = $s->programa;
        if (! $p || ! in_array($s->estado, self::CON_RESERVA, true)) {
            return;
        }

        $d = self::aFicha($p, 'itinerario');
        Avisos::enviar(
            'reservas', 'servicio_quitado', "Se quitó un servicio ya reservado: {$s->titulo}",
            self::etiqueta($s) . ' · ' . ($s->proveedor?->nombre ?? 'sin proveedor') . ($s->confirmacion ? " · conf. {$s->confirmacion}" : '') . " · {$p->codigo}",
            'Cancelar con el proveedor', $d['ruta'], $d['params'], null, null, $p,
        );
    }

    // ---- Programas ----

    private static function programaCreado(Programa $p): void
    {
        if ($p->estado === 'confirmado') {
            self::programaConfirmado($p);
        }
    }

    private static function programaActualizado(Programa $p): void
    {
        if ($p->wasChanged('estado')) {
            Consulta::where('programa_id', $p->id)->first()?->seguirA($p);
            match ($p->estado) {
                'confirmado' => self::programaConfirmado($p),
                'cancelado' => self::programaCancelado($p),
                default => null,
            };
        }

        if ($p->estado === 'confirmado' && ! $p->wasChanged('estado') && $p->wasChanged(['inicio', 'fin'])) {
            $d = self::aFicha($p, 'itinerario');
            Avisos::enviar(
                'reservas', 'programa_fechas', "Cambiaron las fechas de {$p->codigo}", "{$p->nombre} · revisa si afecta a los servicios ya reservados",
                'Revisar servicios', $d['ruta'], $d['params'], null, "fechas:{$p->id}", $p,
            );
        }
    }

    private static function programaConfirmado(Programa $p): void
    {
        if ($p->servicios()->abiertos()->exists()) {
            $d = self::aReservas($p);
            Avisos::enviar('reservas', 'programa_confirmado', "Programa confirmado: {$p->codigo}", "{$p->nombre} · hay servicios por reservar", 'Reservar servicios', $d['ruta'], [...$d['params'], 'bandeja' => null], null, "por_reservar:{$p->id}", $p);
        }

        $d = self::aFicha($p, 'pagos');
        Avisos::enviar('contabilidad', 'programa_confirmado', "Programa confirmado: {$p->codigo}", "{$p->nombre} · gestiona el cobro al cliente", 'Gestionar cobro', $d['ruta'], $d['params'], null, "cobrar:{$p->id}", $p);
    }

    private static function programaCancelado(Programa $p): void
    {
        Avisos::resolver("por_reservar:{$p->id}");
        Avisos::resolver("cobrar:{$p->id}");

        if ($p->servicios()->whereIn('estado', self::CON_RESERVA)->exists()) {
            $d = self::aFicha($p, 'itinerario');
            Avisos::enviar('reservas', 'programa_cancelado', "Programa cancelado: {$p->codigo}", "{$p->nombre} · hay servicios reservados con proveedores", 'Cancelar con proveedores', $d['ruta'], $d['params'], null, null, $p);
        }

        $d = self::aFicha($p, 'pagos');
        Avisos::enviar('contabilidad', 'programa_cancelado', "Programa cancelado: {$p->codigo}", "{$p->nombre} · revisa cobros y pagos a proveedores", 'Revisar cobros', $d['ruta'], $d['params'], null, null, $p);
    }

    // ---- Alojamiento ----

    private static function alojamientoCambiado(HabitacionFecha $estadia): void
    {
        $p = $estadia->programa;
        if ($p?->estado !== 'confirmado') {
            return;
        }

        $d = self::aFicha($p, 'alojamiento');
        $hotel = $estadia->habitacion?->hotel?->nombre;
        Avisos::enviar('reservas', 'alojamiento', "Cambió el alojamiento de {$p->codigo}", $estadia->etiqueta . ($hotel ? " · {$hotel}" : ''), 'Revisar alojamiento', $d['ruta'], $d['params'], null, "alojamiento:{$p->id}", $p);
    }

    // ---- Dinero ----

    private static function cobroRegistrado(Pago $pago): void
    {
        $p = $pago->programa;
        if (! $p) {
            return;
        }
        if ($p->cobranza()['saldo'] <= 0) {
            Avisos::resolver("cobrar:{$p->id}");
        }

        $d = self::aFicha($p, 'pagos');
        Avisos::enviar('operaciones', 'cobro', "Cobro registrado en {$p->codigo}", '$' . number_format((float) $pago->monto, 2) . ' · ' . (Pago::TIPOS[$pago->tipo] ?? $pago->tipo), 'Ver pagos', $d['ruta'], $d['params'], null, null, $p, false);
    }

    private static function cobroAnulado(Pago $pago): void
    {
        $p = $pago->programa;
        if (! $p) {
            return;
        }

        $d = self::aFicha($p, 'pagos');
        Avisos::enviar('operaciones', 'cobro_anulado', "Cobro anulado en {$p->codigo}", '$' . number_format((float) $pago->monto, 2) . ' · Motivo: ' . ($pago->motivo_anulacion ?: 'sin indicar'), 'Revisar con el cliente', $d['ruta'], $d['params'], null, null, $p);
    }

    private static function pagoRegistrado(ProveedorPago $pago): void
    {
        $s = $pago->servicio?->fresh();
        if (! $s) {
            return;
        }
        if ($s->pagado_at) {
            Avisos::resolver("pago:{$s->id}");
        }

        $p = $s->programa;
        $d = self::aReservas($p, $s);
        Avisos::enviar('reservas', 'pago_proveedor', ($s->pagado_at ? 'Proveedor pagado: ' : 'Pago parcial al proveedor: ') . $s->titulo, Servicio::formatoMonto((float) $pago->monto, $pago->moneda) . ' · ' . ($s->proveedor?->nombre ?? '') . " · {$p->codigo}", 'Ver servicio', $d['ruta'], $d['params'], $d['ancla'], null, $p, false);
    }

    private static function pagoAnulado(ProveedorPago $pago): void
    {
        $s = $pago->servicio;
        if (! $s) {
            return;
        }

        $p = $s->programa;
        $d = self::aReservas($p, $s);
        Avisos::enviar('reservas', 'pago_anulado', "Pago a proveedor anulado: {$s->titulo}", Servicio::formatoMonto((float) $pago->monto, $pago->moneda) . ' · Motivo: ' . ($pago->motivo_anulacion ?: 'sin indicar') . " · {$p->codigo}", 'Revisar con el proveedor', $d['ruta'], $d['params'], $d['ancla'], null, $p);
    }

    // ---- Consultas ----

    private static function consultaNueva(Consulta $consulta): void
    {
        if ($consulta->estado === 'nueva') {
            Avisos::enviar('operaciones', 'consulta_nueva', "Nueva consulta: {$consulta->nombre}", $consulta->resumen_viaje ?: $consulta->email, 'Atender consulta', 'consultas.show', ['consulta' => $consulta->id], null, "consulta:{$consulta->id}");
        }
    }

    // ---- Cliente ----

    private static function datosDelCliente(Pax $pax): void
    {
        if (! $pax->actualizado_por_cliente_at || ! $pax->wasChanged('actualizado_por_cliente_at') && ! $pax->wasRecentlyCreated) {
            return;
        }

        $p = $pax->programa;
        if ($p) {
            $d = self::aFicha($p, 'pasajeros');
            Avisos::enviar('operaciones', 'datos_cliente', "El cliente envió datos de viajeros: {$p->codigo}", $p->nombre, 'Revisar pasajeros', $d['ruta'], $d['params'], null, "datos_cliente:{$p->id}", $p);
        }
    }
}
