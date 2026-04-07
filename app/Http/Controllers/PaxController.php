<?php

namespace App\Http\Controllers;

use App\Models\Pax;
use App\Http\Controllers\Controller;
use App\Models\Agente;
use App\Models\Programa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaxController extends Controller
{
    public function index()
    {
        $paxs = Pax::with('programa')->latest()->get();
        return view('paxs.index', compact('paxs'));
    }

    public function create()
    {
        $programas = Programa::all();
        $agentes   = Agente::all();

        return view('paxs.create', compact('programas', 'agentes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'       => 'required|string|max:255',
            'edad'         => 'required|integer',
            'pasaporte'    => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'nacionalidad' => 'required|string|max:255',
            'alimentacion' => 'required|string|max:255',
            'programa_id'  => 'required|exists:programas,id',
            'agentes'      => 'array',
        ]);

        if ($request->hasFile('pasaporte')) {
            $validated['pasaporte'] = $request->file('pasaporte')
                ->store('pasaportes', 'public');
        }

        $pax = Pax::create($validated);
        $pax->agentes()->sync($request->agentes);

        return redirect()->route('paxs.index')->with('success', 'Pax creado correctamente.');
    }

    public function show(Pax $pax)
    {
        $pax->load('agentes', 'programa');
        return view('paxs.show', compact('pax'));
    }

    public function edit(Pax $pax)
    {
        $programas = Programa::all();
        $agentes   = Agente::all();

        return view('paxs.edit', compact('pax', 'programas', 'agentes'));
    }

    public function update(Request $request, Pax $pax)
    {
        $validated = $request->validate([
            'nombre'       => 'required|string|max:255',
            'edad'         => 'required|integer',
            'pasaporte'    => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'nacionalidad' => 'required|string|max:255',
            'alimentacion' => 'required|string|max:255',
            'programa_id'  => 'required|exists:programas,id',
            'agentes'      => 'array',
        ]);

        if ($request->hasFile('pasaporte')) {
            if ($pax->pasaporte) {
                Storage::disk('public')->delete($pax->pasaporte);
            }

            $validated['pasaporte'] = $request->file('pasaporte')
                ->store('pasaportes', 'public');
        }

        $pax->update($validated);
        $pax->agentes()->sync($request->agentes);

        return redirect()->route('paxs.index')->with('success', 'Pax actualizado correctamente.');
    }

    public function destroy(Pax $pax)
    {
        if ($pax->pasaporte) {
            Storage::disk('public')->delete($pax->pasaporte);
        }

        $pax->agentes()->detach();
        $pax->delete();

        return redirect()->route('paxs.index')->with('success', 'Pax eliminado.');
    }
}
