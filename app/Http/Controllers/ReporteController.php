<?php

namespace App\Http\Controllers;

use App\Support\Reportes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Reportes de gestión. El costo y el margen solo los ven Contabilidad y Administración. */
class ReporteController extends Controller
{
    public function index(Request $request)
    {
        [$desde, $hasta] = $this->periodo($request);
        $reportes = new Reportes($desde, $hasta);

        return view('reportes.index', [
            'desde' => $desde, 'hasta' => $hasta,
            'totales' => $reportes->totales(), 'porMes' => $reportes->porMes(), 'porAgente' => $reportes->porAgente(),
            'porOrigen' => $reportes->porOrigen(), 'consultas' => $reportes->consultas(),
            'porProveedor' => $request->user()->can('margen.ver') ? $reportes->porProveedor() : collect(),
        ]);
    }

    /** CSV con BOM: Excel lo abre con tildes y columnas correctas. */
    public function exportar(Request $request, string $tabla): StreamedResponse
    {
        [$desde, $hasta] = $this->periodo($request);
        $reportes = new Reportes($desde, $hasta);
        $verMargen = $request->user()->can('margen.ver');
        abort_if($tabla === 'proveedor' && ! $verMargen, 403);

        $filas = match ($tabla) {
            'mes' => $reportes->porMes(), 'agente' => $reportes->porAgente(), 'origen' => $reportes->porOrigen(), 'proveedor' => $reportes->porProveedor(),
            default => abort(404),
        };

        $columnas = $tabla === 'proveedor'
            ? ['etiqueta' => 'Proveedor', 'servicios' => 'Servicios', 'costo' => 'Costo (USD)', 'pagado' => 'Pagado (USD)', 'por_pagar' => 'Por pagar (USD)']
            : ['etiqueta' => ucfirst($tabla), 'programas' => 'Programas', 'pasajeros' => 'Pasajeros', 'venta' => 'Venta (USD)', 'cobrado' => 'Cobrado (USD)', 'por_cobrar' => 'Por cobrar (USD)']
                + ($verMargen ? ['costo' => 'Costo (USD)', 'margen' => 'Margen (USD)', 'margen_pct' => 'Margen (%)'] : []);

        return response()->streamDownload(function () use ($filas, $columnas) {
            $salida = fopen('php://output', 'w');
            fwrite($salida, "\xEF\xBB\xBF");
            fputcsv($salida, array_values($columnas), ';');
            foreach ($filas as $fila) {
                fputcsv($salida, array_map(fn ($c) => $this->celda($fila[$c]), array_keys($columnas)), ';');
            }
            fclose($salida);
        }, "reporte-{$tabla}-{$desde->toDateString()}-a-{$hasta->toDateString()}.csv", ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Un texto no debe poder convertirse en fórmula al abrirlo en Excel. */
    private function celda(mixed $valor): string
    {
        if (is_float($valor)) {
            return number_format($valor, 2, ',', '');
        }

        return preg_replace('/^([=+\-@\t\r])/', "'$1", (string) $valor);
    }

    private function periodo(Request $request): array
    {
        $datos = $request->validate(['desde' => 'nullable|date', 'hasta' => 'nullable|date|after_or_equal:desde']);

        return [
            isset($datos['desde']) ? Carbon::parse($datos['desde'])->startOfDay() : Carbon::today()->startOfYear(),
            isset($datos['hasta']) ? Carbon::parse($datos['hasta'])->startOfDay() : Carbon::today()->endOfYear()->startOfDay(),
        ];
    }
}
