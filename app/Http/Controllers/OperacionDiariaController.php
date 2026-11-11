<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use App\Models\Proveedor;
use App\Models\Servicio;
use App\Support\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Throwable;

/** Lo que el día a día pide fuera del formulario: lista de habitaciones, órdenes por correo y cierre de programas. */
class OperacionDiariaController extends Controller
{
    /** Lista de huéspedes para el hotel. Con ?hotel=ID, solo la de ese hotel. */
    public function rooming(Request $request, Programa $programa)
    {
        $programa->load(['paxs', 'cliente', 'habitacionesFechas.habitacion.proveedor']);
        $estadias = $programa->habitacionesFechas->whereNotNull('fecha_ingreso')
            ->when($request->integer('hotel'), fn ($c, $hotel) => $c->filter(fn ($e) => ($e->habitacion?->proveedor_id) === $hotel))
            ->sortBy('fecha_ingreso')->groupBy(fn ($e) => $e->habitacion?->proveedor_id);
        abort_if($estadias->isEmpty(), 404);

        $html = View::make('programas.rooming', [
            'programa' => $programa, 'hoteles' => Proveedor::findMany($estadias->keys())->keyBy('id'), 'estadias' => $estadias,
            'pasaportes' => $request->user()->can('pasaportes.ver'),
        ])->render();

        return response(Pdf::desdeHtml($html), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="lista-de-huespedes-' . preg_replace('/[^A-Za-z0-9_-]+/', '-', $programa->codigo) . '.pdf"',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    /** Se envía solo a pedido de Reservas, a la dirección que figura en la ficha del proveedor. */
    public function enviarOrden(Servicio $servicio)
    {
        $servicio->load(['programa.paxs', 'programa.cliente', 'programa.agentes', 'proveedor.categoria']);
        $correo = trim((string) $servicio->proveedor?->correo);
        if (! filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return back()->with('error', 'El proveedor no tiene un correo válido: agrégalo en su ficha.');
        }

        $pdf = Pdf::desdeHtml(View::make('servicios.voucher', ['servicio' => $servicio, 'programa' => $servicio->programa])->render());
        $codigo = $servicio->programa->codigo;
        $texto = "Estimado proveedor:\n\nAdjuntamos la orden de servicio {$codigo}-{$servicio->id}: {$servicio->titulo} ({$servicio->fecha->format('d/m/Y')}).\n\nPor favor, confirme su recepción.\n\nAndean Exclusive Tours";

        try {
            Mail::raw($texto, fn ($m) => $m->to($correo, $servicio->proveedor->nombre)
                ->subject("Orden de servicio {$codigo} · {$servicio->titulo}")
                ->attachData($pdf, "orden-de-servicio-{$codigo}-{$servicio->id}.pdf", ['mime' => 'application/pdf']));
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'No se pudo enviar la orden. Inténtalo de nuevo o descárgala y envíala a mano.');
        }

        $servicio->update(['orden_enviada_at' => now()]);
        $servicio->registrarEvento('nota', null, null, "Orden de servicio enviada a {$correo}");

        return back()->with('success', "Orden enviada a {$correo}.");
    }

    public function cerrar(Request $request, Programa $programa)
    {
        $pendientes = $programa->pendientesParaCerrar();
        if ($pendientes) {
            return back()->with('error', 'Todavía no se puede cerrar: ' . implode(' ', $pendientes));
        }

        $programa->update(['cerrado_at' => now(), 'cerrado_por' => $request->user()->id]);

        return back()->with('success', 'Programa cerrado. Ya no se puede modificar.');
    }

    public function reabrir(Programa $programa)
    {
        $programa->update(['cerrado_at' => null, 'cerrado_por' => null]);

        return back()->with('success', 'Programa reabierto.');
    }
}
