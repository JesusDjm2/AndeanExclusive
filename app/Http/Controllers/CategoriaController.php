<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class CategoriaController
 * @package App\Http\Controllers
 */
class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::paginate();
        $proveedor = Proveedor::all();
        return view('proveedor.categoria.index', compact('categorias'))
            ->with('i', (request()->input('page', 1) - 1) * $categorias->perPage());
    }

    public function create()
    {
        $categoria = new Categoria();
        return view('proveedor.categoria.create', compact('categoria'));
    }

    public function store(Request $request)
    {
        request()->validate(Categoria::$rules);
        $categoria = Categoria::create($request->all());

        return redirect()->route('categoriasproveedor.index')
            ->with('success', 'Categoria creada exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function mostrar()
    {
        return 'Hola mundo';
    }
    public function show($id)
    {
        $categorias = Categoria::find($id)->proveedors;
        return view('proveedor.categoria.show', compact('categorias'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categoria = Categoria::find($id);

        return view('proveedor.categoria.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(Categoria::$rules);

        $categoria = Categoria::findOrFail($id);
        $categoria->update($request->all());

        return redirect()
            ->route('categoriasproveedor.index')
            ->with('success', 'Categoría actualizada exitosamente!');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $categoria = Categoria::find($id)->delete();

        return redirect()->route('categoriasproveedor.index')
            ->with('success', 'Categoria borrada exitosamente!');
    }
}
