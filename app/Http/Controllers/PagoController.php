<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Programa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Pagos de un programa (pestaña "Pagos" del formulario de edición). */
class PagoController extends Controller
{
    public function store(Request $request, Programa $programa)
    {
        $data = $request->validateWithBag('pago', [
            'fecha' => 'required|date',
            'monto' => 'required|numeric|min:0.01|max:9999999',
            'tipo' => ['required', Rule::in(array_keys(Pago::TIPOS))],
            'metodo' => ['nullable', Rule::in(array_keys(Pago::METODOS))],
            'referencia' => 'nullable|string|max:255',
            'nota' => 'nullable|string|max:255',
        ]);

        $programa->pagos()->create($data);

        return redirect()
            ->route('programas.edit', [$programa, 'tab' => 'pagos'])
            ->with('success', 'Pago registrado.');
    }

    /** Anula el cobro con su motivo: deja de contar, pero queda como constancia. */
    public function destroy(Request $request, Programa $programa, Pago $pago)
    {
        ['reglas' => $reglas, 'mensajes' => $mensajes] = Pago::reglasMotivo();
        $pago->anular($request->validateWithBag('pago', $reglas, $mensajes)['motivo']);

        return redirect()
            ->route('programas.edit', [$programa, 'tab' => 'pagos'])
            ->with('success', 'Pago anulado.');
    }
}
