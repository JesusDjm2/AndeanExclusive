<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedors = Proveedor::paginate();
        return view('proveedor.index', compact('proveedors'))
            ->with('i', (request()->input('page', 1) - 1) * $proveedors->perPage());
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
            ->with('success', 'Proveedor creado exitosamante.');
    }

    public function show($id)
    {
        $proveedor = Proveedor::find($id);
        return view('proveedor.show', compact('proveedor'));
    }

    public function edit(Proveedor $proveedor)
    {
        $categorias = Categoria::pluck('nombre', 'id');
        return view('proveedor.edit', compact('proveedor', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        /* request()->validate(Proveedor::$rules);

        $proveedor->update($request->all()); */
        $proveedor = Proveedor::find($id);
        $proveedor->nombre = $request->get('nombre');
        $proveedor->categoria_id = $request->get('categoria_id');
        $proveedor->direccion = $request->get('direccion');
        $proveedor->ruc = $request->get('ruc');
        $proveedor->telefono = $request->get('telefono');
        $proveedor->correo = $request->get('correo');
        $proveedor->detalles = $request->get('detalles');
        $proveedor->save();
        return redirect()->route('proveedors.index')
            ->with('success', 'Proveedor actualizado exitosamente');
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::find($id)->delete();
        return redirect()->route('proveedors.index')
            ->with('success', 'Proveedor borrado exitosamente');
    }
}
