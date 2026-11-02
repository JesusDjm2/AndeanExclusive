<?php

namespace App\Http\Controllers;

use App\Models\Pax;
use App\Http\Controllers\Controller;
use App\Models\Agente;
use App\Models\Programa;
use Illuminate\Http\Request;
use App\Support\PassportFiles;

class PaxController extends Controller
{
    public function index()
    {
        $paxs = Pax::query()
            ->with([
                'programa.agentes',
                'programa.anio',
                'programa.mes',
            ])
            ->get()
            ->sortBy(function (Pax $pax) {
                $prog = mb_strtolower(optional($pax->programa)->nombre ?? '');

                return [$prog, mb_strtolower($pax->nombre)];
            })
            ->values();

        $paxsPorPrograma = $paxs->groupBy('programa_id');

        return view('paxs.index', compact('paxsPorPrograma'));
    }

    public function create()
    {
        return view('paxs.form', ['pax' => new Pax()] + $this->catalogos());
    }

    /** Campos del pasajero (mismas reglas que el formulario de programas). */
    private function validar(Request $request): array
    {
        $validated = $request->validate(ProgramaController::reglasPax('') + [
            'programa_id' => 'required|exists:programas,id',
            'agentes' => 'array',
            'agentes.*' => 'exists:agentes,id',
        ]);
        unset($validated['pasaporte_archivo'], $validated['agentes']);

        return $validated;
    }

    private function catalogos(): array
    {
        return [
            'programas' => Programa::orderByDesc('inicio')->get(['id', 'codigo', 'nombre']),
            'agentes' => Agente::orderBy('nombre')->get(),
        ];
    }

    public function store(Request $request)
    {
        $validated = $this->validar($request);
        if ($request->hasFile('pasaporte_archivo')) {
            $validated['pasaporte_archivo'] = PassportFiles::store($request->file('pasaporte_archivo'));
        }

        $pax = Pax::create($validated);
        $pax->agentes()->sync($request->input('agentes', []));

        return redirect()->route('paxs.edit', $pax)->with('success', 'Pasajero creado.');
    }

    public function show(Pax $pax)
    {
        $pax->load('agentes', 'programa');
        return view('paxs.show', compact('pax'));
    }

    public function edit(Pax $pax)
    {
        $pax->load('agentes', 'programa');

        return view('paxs.form', ['pax' => $pax] + $this->catalogos());
    }

    public function update(Request $request, Pax $pax)
    {
        $validated = $this->validar($request);
        if ($request->hasFile('pasaporte_archivo')) {
            PassportFiles::delete($pax->pasaporte_archivo);
            $validated['pasaporte_archivo'] = PassportFiles::store($request->file('pasaporte_archivo'));
        }

        $pax->update($validated);
        $pax->agentes()->sync($request->input('agentes', []));

        return redirect()->route('paxs.edit', $pax)->with('success', 'Cambios guardados.');
    }

    public function destroy(Pax $pax)
    {
        PassportFiles::delete($pax->pasaporte_archivo);

        $pax->agentes()->detach();
        $pax->delete();

        return redirect()->route('paxs.index')->with('success', 'Pax eliminado.');
    }

    /**
     * Pasaporte del pasajero, solo para usuarios del panel (los archivos ya no son públicos).
     */
    public function pasaporte(Pax $pax)
    {
        abort_unless(request()->user()?->can('pasaportes.ver'), 403, 'Los pasaportes los consultan Operaciones y Reservas.');

        $path = PassportFiles::absolutePath($pax->pasaporte_archivo);
        abort_if(! $path, 404);
        \App\Support\Bitacora::anotar('consultó el pasaporte de', $pax);

        $response = response()->file($path, ['X-Content-Type-Options' => 'nosniff']);
        // BinaryFileResponse se marca "public" por defecto: un pasaporte nunca debe quedar en cachés compartidas.
        $response->setPrivate();
        $response->headers->addCacheControlDirective('no-store');

        return $response;
    }
}
