<?php

namespace App\Http\Controllers;

use App\Models\Agente;
use App\Http\Controllers\Controller;
use App\Support\SubidaImagenes;
use Illuminate\Http\Request;

class AgenteController extends Controller
{
    public function index()
    {
        $agentes = Agente::latest()->paginate(10);
        return view('programas.agentes.index', compact('agentes'));
    }

    public function create()
    {
        return view('programas.agentes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:agentes,email',
            'foto' => 'nullable|' . SubidaImagenes::regla(),
        ], SubidaImagenes::mensajes(['foto']));

        $data = $request->only(['nombre', 'telefono', 'email']);

        if ($request->hasFile('foto')) {
            $data['foto'] = 'img/programas/agentes/' . SubidaImagenes::guardar($request->file('foto'), 'img/programas/agentes', time() . '_');
        }
        Agente::create($data);

        return redirect()->route('agentes.index')->with('success', 'Agente creado correctamente.');
    }

    public function show(Agente $agente)
    {
        return view('programas.agentes.show', compact('agente'));
    }

    public function edit(Agente $agente)
    {
        return view('programas.agentes.edit', compact('agente'));
    }

    public function update(Request $request, Agente $agente)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:agentes,email,' . $agente->id,
            'foto' => 'nullable|' . SubidaImagenes::regla(),
        ], SubidaImagenes::mensajes(['foto']));

        $data = $request->only(['nombre', 'telefono', 'email']);

        if ($request->hasFile('foto')) {
            // Eliminar la imagen anterior si existe
            if ($agente->foto && file_exists(public_path($agente->foto))) {
                unlink(public_path($agente->foto));
            }

            $data['foto'] = 'img/programas/agentes/' . SubidaImagenes::guardar($request->file('foto'), 'img/programas/agentes', time() . '_');
        }


        $agente->update($data);

        return redirect()->route('agentes.index')->with('success', 'Agente actualizado correctamente.');
    }

    public function destroy(Agente $agente)
    {
        $agente->delete();

        return redirect()->route('agentes.index')->with('success', 'Agente eliminado correctamente.');
    }
}
