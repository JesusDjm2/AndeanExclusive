<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Programa;
use App\Support\CopiaDePrograma;
use Illuminate\Http\Request;

/** Duplicar viajes y usar plantillas: para no armar desde cero lo que se vende una y otra vez. */
class CopiaDeProgramaController extends Controller
{
    public function plantillas()
    {
        $plantillas = Programa::plantillas()->withCount(['dias', 'servicios', 'habitacionesFechas'])->orderBy('nombre')->get();

        return view('programas.plantillas', compact('plantillas'));
    }

    public function create(Programa $programa)
    {
        return view('programas.duplicar', ['programa' => $programa, 'clientes' => Cliente::orderBy('nombre')->get(['id', 'nombre', 'empresa', 'email'])]);
    }

    public function store(Request $request, Programa $programa)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'cliente_id' => 'required|exists:clientes,id',
            'inicio' => ($programa->inicio ? 'required' : 'nullable') . '|date',
        ], ['cliente_id.required' => 'Elige el cliente.', 'inicio.required' => 'Indica cuándo empieza el viaje.']);

        $copia = CopiaDePrograma::copiar($programa, $datos);

        return redirect()->route('programas.edit', $copia)->with('success', 'Cotización creada a partir de «' . $programa->nombre . '». Revisa fechas y precios.');
    }

    /** Guarda un viaje como plantilla, con fechas de referencia y sin cliente. */
    public function guardarPlantilla(Programa $programa)
    {
        $plantilla = CopiaDePrograma::copiar($programa, [
            'nombre' => $programa->nombre,
            'codigo' => 'PLT-' . now()->format('YmdHis'),
            'plantilla' => true,
            'inicio' => $programa->inicio ? CopiaDePrograma::BASE_PLANTILLA : null,
        ]);

        return redirect()->route('programas.edit', $plantilla)->with('success', 'Plantilla creada. Ajusta lo que quieras: los viajes que nazcan de ella tomarán estos datos.');
    }

    public function eliminarPlantilla(Programa $programa)
    {
        abort_unless($programa->plantilla, 404);
        $programa->delete();

        return redirect()->route('programas.plantillas')->with('success', 'Plantilla eliminada.');
    }
}
