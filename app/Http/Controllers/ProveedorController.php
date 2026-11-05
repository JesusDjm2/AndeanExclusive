<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $categoriaId = $request->input('categoria_id');
        $q = trim((string) $request->input('q'));

        $proveedors = Proveedor::query()
            ->with('categoria')
            ->when($categoriaId, fn ($query) => $query->where('categoria_id', $categoriaId))
            ->when($q !== '', function ($query) use ($q) {
                $wildcard = '%' . addcslashes($q, '%_\\') . '%';
                $query->where(function ($sub) use ($wildcard) {
                    $sub->where('nombre', 'like', $wildcard)
                        ->orWhere('ruc', 'like', $wildcard)
                        ->orWhere('telefono', 'like', $wildcard)
                        ->orWhere('correo', 'like', $wildcard)
                        ->orWhere('direccion', 'like', $wildcard);
                });
            })
            ->orderBy('nombre')
            ->paginate(25)
            ->withQueryString();

        $categorias = Categoria::withCount('proveedors')->orderBy('nombre')->get();

        return view('proveedor.index', compact('proveedors', 'categorias', 'categoriaId', 'q'))
            ->with('i', ($proveedors->currentPage() - 1) * $proveedors->perPage());
    }

    public function create()
    {
        $categorias = Categoria::pluck('nombre', 'id');
        $proveedor = null; // 👈 Esto es clave
        return view('proveedor.create', compact('categorias', 'proveedor'));
    }

    public function store(Request $request)
    {
        request()->validate(Proveedor::$rules);

        $proveedor = Proveedor::create($request->all());

        return redirect()->route('proveedors.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    public function show($id)
    {
        $proveedor = Proveedor::with(['categoria', 'tarifas'])->findOrFail($id);
        return view('proveedor.show', compact('proveedor'));
    }

    public function edit(Proveedor $proveedor)
    {
        $categorias = Categoria::pluck('nombre', 'id');
        return view('proveedor.edit', compact('proveedor', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        request()->validate(Proveedor::$rules);

        $proveedor = Proveedor::findOrFail($id);
        $proveedor->nombre = $request->get('nombre');
        $proveedor->categoria_id = $request->get('categoria_id');
        $proveedor->direccion = $request->get('direccion');
        $proveedor->ruc = $request->get('ruc');
        $proveedor->telefono = $request->get('telefono');
        $proveedor->correo = $request->get('correo');
        $proveedor->detalles = $request->get('detalles');
        $proveedor->save();

        return redirect()->route('proveedors.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();

        return redirect()->route('proveedors.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }
}
