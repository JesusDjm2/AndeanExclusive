<?php

namespace App\Http\Controllers;

use App\Models\ProveedorPago;
use App\Models\Servicio;
use App\Support\Bitacora;
use App\Support\PrivateFiles;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

/**
 * Gestión de un servicio con su proveedor (área de reservas): estado y confirmación, pagos, historial y orden de servicio.
 * Lo usan la bandeja de Reservas, la hoja de operaciones y las cuentas por pagar.
 */
class ServicioController extends Controller
{
    /** Cambia el estado de la reserva (por reservar → solicitado → confirmado / cancelado) y el n.º de confirmación. */
    public function estado(Request $request, Servicio $servicio)
    {
        $datos = $request->validate([
            'estado' => ['required', Rule::in(Servicio::ESTADOS_MANUALES)],
            'confirmacion' => 'nullable|string|max:80',
            'nota' => 'nullable|string|max:255',
        ], ['estado.required' => 'Elige el estado.']);

        if (in_array($datos['estado'], Servicio::REQUIEREN_PROVEEDOR, true) && ! $servicio->proveedor_id) {
            return back()->with('error', "«{$servicio->titulo}» no tiene proveedor: asígnalo antes de solicitarlo o confirmarlo.");
        }

        if ($datos['estado'] === 'confirmado' && blank($datos['confirmacion'] ?? $servicio->confirmacion)) {
            return back()->with('error', "Para confirmar «{$servicio->titulo}» indica el n.º de confirmación del proveedor.");
        }

        $servicio->update([
            'estado' => $datos['estado'],
            'confirmacion' => ($datos['confirmacion'] ?? null) ?: $servicio->confirmacion,
        ]);
        if (filled($datos['nota'] ?? null)) {
            $servicio->registrarEvento('nota', null, null, $datos['nota']);
        }

        return back()->with('success', "«{$servicio->titulo}»: " . mb_strtolower(Servicio::ESTADOS[$servicio->estado]) . '.');
    }

    /**
     * Asigna (o cambia) el proveedor que dará el servicio y su costo real. Un servicio "sin asignar" pasa a "por reservar".
     * Si cambia el proveedor de un servicio ya solicitado o confirmado, vuelve a "por reservar" (la reserva anterior no vale).
     */
    public function asignar(Request $request, Servicio $servicio)
    {
        $datos = $request->validate([
            'proveedor_id' => 'required|exists:proveedors,id',
            'costo' => 'nullable|numeric|min:0',
            'nota' => 'nullable|string|max:255',
        ], ['proveedor_id.required' => 'Elige el proveedor.']);

        $cambia = $servicio->proveedor_id && (int) $servicio->proveedor_id !== (int) $datos['proveedor_id'];
        $servicio->update([
            'proveedor_id' => $datos['proveedor_id'],
            'costo' => ($datos['costo'] ?? '') !== '' ? $datos['costo'] : ($servicio->costo ?? $servicio->costo_estimado),
            'estado' => $cambia && in_array($servicio->estado, Servicio::REQUIEREN_PROVEEDOR, true) ? 'pendiente' : $servicio->estado,
            'confirmacion' => $cambia ? null : $servicio->confirmacion,
        ]);
        if (filled($datos['nota'] ?? null)) {
            $servicio->registrarEvento('nota', null, null, $datos['nota']);
        }

        $servicio->load('programa.paxs', 'proveedor');
        $mensaje = "«{$servicio->titulo}» asignado a {$servicio->proveedor->nombre}.";
        if (($servicio->desvio_costo ?? 0) > 0.009) {
            $mensaje .= ' Atención: el costo real supera lo cotizado en ' . Servicio::formatoMonto($servicio->desvio_costo, $servicio->moneda) . '.';
        }

        return back()->with('success', $mensaje);
    }

    /** Reservas pide a Contabilidad que pague el servicio (o retira la solicitud); el pago lo registra Contabilidad. */
    public function solicitarPago(Servicio $servicio)
    {
        $servicio->load('programa.paxs', 'pagos');

        if ($servicio->pago_solicitado_at) {
            $servicio->update(['pago_solicitado_at' => null, 'pago_solicitado_por' => null]);
            $servicio->registrarEvento('nota', null, null, 'Solicitud de pago retirada');
            Bitacora::anotar('retiró la solicitud de pago de', $servicio);

            return back()->with('success', "Se retiró la solicitud de pago de «{$servicio->titulo}».");
        }

        if ($servicio->estado === 'cancelado' || ! $servicio->proveedor_id || $servicio->saldo_proveedor <= 0) {
            return back()->with('error', "«{$servicio->titulo}» no tiene un saldo que pagar a un proveedor.");
        }

        $servicio->update(['pago_solicitado_at' => now(), 'pago_solicitado_por' => auth()->id()]);
        $saldo = Servicio::formatoMonto($servicio->saldo_proveedor, $servicio->moneda);
        $servicio->registrarEvento('nota', null, null, "Pago solicitado a Contabilidad: {$saldo}");
        Bitacora::anotar('solicitó el pago de', $servicio, [['campo' => 'Saldo', 'a' => $saldo]]);

        return back()->with('success', "Se solicitó a Contabilidad el pago de «{$servicio->titulo}» ({$saldo}).");
    }

    /** Registra un pago (total o parcial) al proveedor, con comprobante opcional. */
    public function pagar(Request $request, Servicio $servicio)
    {
        $servicio->load('programa.paxs', 'pagos');
        $saldo = $servicio->saldo_proveedor;

        $datos = $request->validate([
            'monto' => 'required|numeric|min:0.01|max:' . max(0.01, $saldo),
            'fecha' => 'required|date|before_or_equal:today',
            'metodo' => ['nullable', Rule::in(array_keys(ProveedorPago::METODOS))],
            'referencia' => 'nullable|string|max:255',
            'comprobante' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:4096',
        ], [
            'monto.max' => 'El monto supera el saldo pendiente (' . Servicio::formatoMonto($saldo, $servicio->moneda) . ').',
            'monto.min' => 'Indica un monto mayor a cero.',
        ]);

        if ($saldo <= 0) {
            return back()->with('error', 'Este servicio ya está pagado por completo.');
        }

        $servicio->pagos()->create([
            'fecha' => $datos['fecha'],
            'monto' => $datos['monto'],
            'moneda' => $servicio->moneda,
            'metodo' => $datos['metodo'] ?? null,
            'referencia' => $datos['referencia'] ?? null,
            'comprobante' => $request->hasFile('comprobante') ? PrivateFiles::store($request->file('comprobante'), 'comprobantes') : null,
            'user_id' => auth()->id(),
        ]);

        $servicio->refresh();

        return back()->with('success', 'Pago registrado: ' . Servicio::formatoMonto((float) $datos['monto'], $servicio->moneda)
            . ($servicio->saldo_proveedor > 0 ? ' · saldo ' . Servicio::formatoMonto($servicio->saldo_proveedor, $servicio->moneda) : ' · servicio pagado') . '.');
    }

    /** Anula un pago con su motivo (queda en el historial, con su comprobante). */
    public function anularPago(Request $request, Servicio $servicio, ProveedorPago $pago)
    {
        ['reglas' => $reglas, 'mensajes' => $mensajes] = ProveedorPago::reglasMotivo();
        $pago->anular($request->validate($reglas, $mensajes)['motivo']);

        return back()->with('success', 'Pago anulado.');
    }

    /** Comprobante de pago (archivo privado, solo con sesión). */
    public function comprobante(Servicio $servicio, ProveedorPago $pago)
    {
        $path = PrivateFiles::absolutePath($pago->comprobante);
        abort_unless($path, 404);

        return response()->file($path);
    }

    /** Historial del servicio (fragmento HTML para el modal). */
    public function historial(Servicio $servicio)
    {
        $servicio->load(['programa.paxs', 'proveedor', 'pagos.usuario', 'eventos.usuario']);

        $anulados = $servicio->pagos()->onlyTrashed()->with('anuladoPor')->get();

        return view('servicios.partials.historial', compact('servicio', 'anulados'));
    }

    /** Orden de servicio en PDF para enviar al proveedor: qué, cuándo, dónde y para quién. */
    public function voucher(Servicio $servicio)
    {
        $servicio->load(['programa.paxs', 'programa.cliente', 'programa.agentes', 'proveedor.categoria']);

        $html = View::make('servicios.voucher', ['servicio' => $servicio, 'programa' => $servicio->programa])->render();

        $options = new Options;
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $nombre = 'orden-servicio-' . preg_replace('/[^A-Za-z0-9_-]+/', '-', $servicio->programa->codigo) . '-' . $servicio->id . '.pdf';

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $nombre . '"',
        ]);
    }
}
