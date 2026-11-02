<?php

namespace App\Http\Controllers;

use App\Support\Ayuda;
use Illuminate\Http\Request;

/** Centro de ayuda del panel. El contenido vive en App\Support\Ayuda. */
class AyudaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return view('ayuda.index', [
            'grupos' => Ayuda::porGrupo($user),
            'area' => Ayuda::AREAS[Ayuda::rol($user)] ?? null,
        ]);
    }

    /** Guía completa en PDF: solo con sesión, nunca desde una carpeta pública. */
    public function guia()
    {
        abort_unless(Ayuda::guiaDisponible(), 404);

        return response()->file(base_path(Ayuda::GUIA), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Guia-del-sistema-Andean-Exclusive.pdf"',
        ]);
    }
}
