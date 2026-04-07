<?php

namespace App\Http\Controllers;

use App\Models\Agente;
use App\Http\Controllers\Controller;
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
            'foto' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['nombre', 'telefono', 'email']);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $rutaDestino = public_path('img/programas/agentes');

            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0755, true);
            }

            $file->move($rutaDestino, $nombreArchivo);
            $data['foto'] = 'img/programas/agentes/' . $nombreArchivo;
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
            'foto' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['nombre', 'telefono', 'email']);

        if ($request->hasFile('foto')) {
            // Eliminar la imagen anterior si existe
            if ($agente->foto && file_exists(public_path($agente->foto))) {
                unlink(public_path($agente->foto));
            }

            $file = $request->file('foto');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $rutaDestino = public_path('img/programas/agentes');

            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0755, true);
            }

            $file->move($rutaDestino, $nombreArchivo);
            $data['foto'] = 'img/programas/agentes/' . $nombreArchivo;
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
