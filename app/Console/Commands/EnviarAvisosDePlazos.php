<?php

namespace App\Console\Commands;

use App\Models\Aviso;
use App\Models\Consulta;
use App\Models\Programa;
use App\Models\Servicio;
use App\Models\Tarea;
use App\Support\Avisos;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Revisa una vez al día lo que vence y avisa a quien debe actuar, aunque nadie haya abierto la pantalla.
 * Cada asunto tiene una sola notificación: vuelve a "no vista" cada 3 días si sigue pendiente y se cierra sola al resolverse.
 */
class EnviarAvisosDePlazos extends Command
{
    private const DIAS_AVISO_DEPOSITO = 2;

    private const DIAS_AVISO_SALDO = 7;

    private const DIAS_SERVICIO_SIN_CONFIRMAR = 3;

    private const HORAS_CONSULTA_SIN_ATENDER = 24;

    private const DIAS_PARA_INSISTIR = 3;

    private const DIAS_PARA_BORRAR_RESUELTOS = 180;

    protected $signature = 'avisos:plazos';

    protected $description = 'Avisa a las áreas de lo que vence o sigue sin atenderse';

    /** Claves de los asuntos que siguen pendientes hoy. */
    private array $vigentes = [];

    public function handle(): int
    {
        $this->vigentes = [];

        Avisos::desdeTareaProgramada(function () {
            $this->cobrosAlCliente();
            $this->serviciosSinConfirmar();
            $this->proveedoresSinPagar();
            $this->tareasVencidas();
            $this->consultasSinAtender();
            $this->cerrarLoResuelto();
        });

        $this->info(count($this->vigentes) . ' asuntos pendientes.');

        return self::SUCCESS;
    }

    private function avisar(string $area, string $clave, string $titulo, ?string $detalle, string $accion, string $ruta, array $params, ?Programa $programa = null): void
    {
        $this->vigentes[] = $clave;

        $abierto = Aviso::where('clave', $clave)->where('area', $area)->whereNull('resuelto_at')->first();
        if ($abierto && $abierto->updated_at->gt(now()->subDays(self::DIAS_PARA_INSISTIR))) {
            return;
        }

        Avisos::enviar($area, 'plazo', $titulo, $detalle, $accion, $ruta, $params, null, $clave, $programa);
    }

    private function cobrosAlCliente(): void
    {
        $hoy = Carbon::today();
        Programa::where('estado', 'confirmado')->whereDate('fin', '>=', $hoy)->with(['paxs', 'pagos'])->get()->each(function (Programa $p) use ($hoy) {
            $cobro = $p->cobranza();
            if ($cobro['total'] <= 0) {
                return;
            }
            $params = ['programa' => $p->id, 'tab' => 'pagos'];

            if (! $cobro['depositoCubierto'] && $cobro['vencimientoDeposito']?->copy()->subDays(self::DIAS_AVISO_DEPOSITO)->lte($hoy)) {
                $this->avisar('contabilidad', "plazo:deposito:{$p->id}", ($cobro['depositoVencido'] ? 'Depósito vencido: ' : 'Depósito por vencer: ') . $p->codigo,
                    "{$p->nombre} · vence el {$cobro['vencimientoDeposito']->format('d/m/Y')}", 'Revisar cobro', 'programas.edit', $params, $p);
            } elseif ($cobro['depositoCubierto'] && $cobro['saldo'] > 0.009 && $cobro['vencimientoSaldo']?->copy()->subDays(self::DIAS_AVISO_SALDO)->lte($hoy)) {
                $this->avisar('contabilidad', "plazo:saldo:{$p->id}", ($cobro['saldoVencido'] ? 'Saldo vencido: ' : 'Saldo por vencer: ') . $p->codigo,
                    "{$p->nombre} · $" . number_format($cobro['saldo'], 2) . " · vence el {$cobro['vencimientoSaldo']->format('d/m/Y')}", 'Revisar cobro', 'programas.edit', $params, $p);
            }
        });
    }

    private function serviciosSinConfirmar(): void
    {
        $hoy = Carbon::today();
        Servicio::operables()->abiertos()->whereDate('fecha', '>=', $hoy)->whereDate('fecha', '<=', $hoy->copy()->addDays(self::DIAS_SERVICIO_SIN_CONFIRMAR))
            ->with('programa')->get()->groupBy('programa_id')->each(function ($servicios) {
                $p = $servicios->first()->programa;
                $n = $servicios->count();
                $this->avisar('reservas', "plazo:servicios:{$p->id}", "Sin confirmar en los próximos días: {$p->codigo}",
                    $n === 1 ? $servicios->first()->titulo . ' · ' . $servicios->first()->fecha->format('d/m') : "{$n} servicios · el primero es el " . $servicios->min('fecha')->format('d/m'),
                    'Confirmar servicios', 'reservas.index', ['periodo' => '7', 'q' => $p->codigo], $p);
            });
    }

    private function proveedoresSinPagar(): void
    {
        $n = Servicio::query()->porPagar()->whereDate('fecha', '<', Carbon::today())->count();
        if ($n) {
            $this->avisar('contabilidad', 'plazo:proveedores', $n === 1 ? '1 servicio prestado sin pagar al proveedor' : "{$n} servicios prestados sin pagar al proveedor",
                null, 'Ver cuentas por pagar', 'cuentas.index', ['filtro' => 'vencidas']);
        }
    }

    private function tareasVencidas(): void
    {
        Tarea::query()->pendientes()->whereDate('fecha_limite', '<', Carbon::today())
            ->whereHas('programa', fn ($q) => $q->where('estado', 'confirmado'))
            ->with('programa')->get()->groupBy('programa_id')->each(function ($tareas) {
                $p = $tareas->first()->programa;
                $n = $tareas->count();
                $this->avisar('operaciones', "plazo:tareas:{$p->id}", $n === 1 ? "Tarea vencida en {$p->codigo}" : "{$n} tareas vencidas en {$p->codigo}",
                    $tareas->first()->titulo, 'Ver tareas', 'programas.edit', ['programa' => $p->id, 'tab' => 'tareas'], $p);
            });
    }

    private function consultasSinAtender(): void
    {
        Consulta::where('estado', 'nueva')->where('created_at', '<', now()->subHours(self::HORAS_CONSULTA_SIN_ATENDER))->get()->each(function (Consulta $c) {
            $this->avisar('operaciones', "plazo:consulta:{$c->id}", "Consulta sin atender: {$c->nombre}",
                'Recibida ' . $c->created_at->locale('es')->diffForHumans(), 'Atender consulta', 'consultas.show', ['consulta' => $c->id]);
        });
    }

    /** Lo que ya no está pendiente se cierra solo, y lo resuelto hace meses deja de ocupar espacio. */
    private function cerrarLoResuelto(): void
    {
        Aviso::where('clave', 'like', 'plazo:%')->whereNull('resuelto_at')->whereNotIn('clave', $this->vigentes)->update(['resuelto_at' => now()]);
        Aviso::whereNotNull('resuelto_at')->where('resuelto_at', '<', now()->subDays(self::DIAS_PARA_BORRAR_RESUELTOS))->delete();
    }
}
