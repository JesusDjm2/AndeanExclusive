<?php

namespace App\Support;

use App\Models\TarifarioServicio;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Propone las partes de un servicio del Tarifario Maestro (guía, transporte, representante, entradas, almuerzo…)
 * para agregarlas como filas separadas del itinerario y asignar después un proveedor a cada una.
 *
 * El catálogo solo trae el precio total. Reparto:
 *  - las partes que existen sueltas en el catálogo (entradas, bus, almuerzos, guía local MP) toman ese precio;
 *  - lo que sobra del total se reparte en partes iguales entre las partes "de reparto" (transporte y guía);
 *  - las partes sin precio conocido (representante) quedan sin costo para que se completen a mano.
 *
 * Nada de esto se guarda en el catálogo: el usuario lo revisa y edita en el modal y solo afecta al programa.
 * Si un servicio tiene `desglose` definido en la BD (lista de {tipo, nombre, codigo?}), se usa en lugar de deducirlo.
 */
class DesgloseTarifario
{
    /** Entradas que se reconocen por los lugares que visita el servicio. */
    private const ENTRADAS = [
        'BTG' => ['pisac', 'ollantaytambo', 'chinchero', 'moray', 'sacsayhuam', 'qenqo', 'q\'enqo', 'puka pukara', 'puca pucara', 'tambomachay', 'tipon', 'tipón', 'pikillacta'],
        'ENT-MARAS' => ['maras', 'salineras'],
        'ENT-CATEDRAL' => ['catedral'],
        'ENT-QORICANCHA' => ['qoricancha', 'koricancha', 'coricancha'],
        'ENT-SAN-BLAS' => ['san blas'],
        'ENT-ANDAHUAYLILLAS' => ['andahuaylillas'],
        'ENT-HUARO' => ['huaro'],
        'ENT-CANINCUNCA' => ['canincunca'],
        'ENT-RAQCHI' => ['raqchi'],
        'ENT-CASA-CONCHA' => ['casa concha'],
        'ENT-MAP' => ['precolombino'],
        'ENT-ARTE-RELIGIOSO' => ['arte religioso'],
        'ENT-SANTA-CATALINA' => ['santa catalina'],
    ];

    private const VALLE = ['valle', 'urubamba', 'pisac', 'ollantaytambo', 'chinchero', 'moray', 'maras', 'lamay', 'awanacancha'];

    /** Tipo de servicio del itinerario para un servicio que se agrega entero. */
    public const TIPO_POR_CATEGORIA = [
        'traslado' => 'traslado',
        'tour' => 'tour',
        'trek' => 'tour',
        'boleto' => 'entrada',
        'alimentacion' => 'comida',
        'viatico' => 'otro',
        'propina' => 'otro',
        'componente' => 'otro',
        'otro' => 'otro',
    ];

    private Collection $catalogo;

    public function __construct(
        private TarifarioServicio $servicio,
        private int $adultos,
        private int $ninos = 0,
        private bool $feriado = false,
    ) {
        $this->adultos = max(1, $adultos);
        $this->ninos = max(0, $ninos);
    }

    /**
     * @return array{total: float, moneda: string, partes: list<array>, sobrante_negativo: bool}
     */
    public function calcular(): array
    {
        $total = $this->servicio->costoPorPax($this->adultos, $this->ninos, $this->feriado);
        $partes = $this->partes();
        $deReparto = collect($partes)->keys()->filter(fn ($i) => $partes[$i]['origen'] === 'reparto')->values();

        // Las partes con precio propio están dentro de la tarifa solo si caben en ella incluso con 20 pax (la escala
        // más baja). Si no caben (p. ej. BTG + almuerzo en los Full Day Valle), van como adicionales sobre la tarifa.
        $incluidas = $deReparto->isEmpty() || $this->conocidasCabenEnLaTarifa();
        foreach ($partes as $i => $parte) {
            // Las partes definidas como adicionales (propinas) ya vienen marcadas.
            $partes[$i]['incluido'] ??= $parte['origen'] !== 'catalogo' || $incluidas;
        }

        // El resto de la tarifa se reparte en partes iguales entre transporte y guía.
        $conocido = collect($partes)->where('origen', 'catalogo')->where('incluido', true)->sum('costo');
        $sobrante = round($total - $conocido, 2);

        if ($deReparto->isNotEmpty() && $sobrante > 0) {
            $n = $deReparto->count();
            $cuota = floor(($sobrante / $n) * 100) / 100;
            foreach ($deReparto as $k => $i) {
                // La última absorbe el redondeo para que la suma dé exactamente la tarifa.
                $partes[$i]['costo'] = $k === $n - 1 ? round($sobrante - $cuota * ($n - 1), 2) : $cuota;
            }
        } elseif ($deReparto->isEmpty() && $sobrante != 0.0 && abs($sobrante) <= 0.05) {
            // Partes con precio propio (hoja del tarifario): los centavos de redondeo de la hoja van a la primera.
            foreach ($partes as $i => $parte) {
                if ($parte['incluido'] && $parte['costo'] !== null) {
                    $partes[$i]['costo'] = round($parte['costo'] + $sobrante, 2);
                    break;
                }
            }
        }

        return [
            'total' => $total,
            'moneda' => $this->servicio->moneda ?: 'USD',
            'partes' => array_values($partes),
            'adicionales' => ! $incluidas,
        ];
    }

    private function conocidasCabenEnLaTarifa(): bool
    {
        $con20 = new self($this->servicio, 20, 0, $this->feriado);
        $con20->catalogo = $this->catalogo();
        $conocido = collect($con20->partes())->where('origen', 'catalogo')->sum('costo');

        return $conocido <= $this->servicio->costoPorPax(20, 0, $this->feriado) + 0.009;
    }

    private function partes(): array
    {
        $s = $this->servicio;

        if (is_array($s->desglose) && $s->desglose !== []) {
            return $this->desdeDefinicion($s->desglose);
        }

        $texto = Str::lower(Str::ascii(implode(' ', [$s->nombre, $s->descripcion, $s->lugares_visita])));
        $codigo = Str::upper($s->codigo);

        if ($s->categoria === 'traslado') {
            return $this->traslado($texto);
        }

        if (str_starts_with($codigo, 'CEREM-') || str_starts_with($codigo, 'GUIA-LOCAL-')) {
            return [$this->entero()];
        }

        if ($this->esMachuPicchu($codigo, $texto)) {
            return $this->machuPicchu($codigo, $texto);
        }

        $operado = in_array($s->categoria, ['tour', 'trek'], true)
            || ($s->categoria === 'componente' && Str::contains($texto, ['tpt', 'transporte']) && Str::contains($texto, 'guia'));

        return $operado ? $this->tour($texto) : [$this->entero()];
    }

    private function traslado(string $texto): array
    {
        $partes = [$this->parte('traslado', $this->servicio->nombre, null, 'reparto', true, $this->servicio->descripcion)];

        if (Str::contains($texto, ['representante', 'asistencia']) || Str::upper($this->servicio->codigo) === 'APTHTL') {
            $partes[] = $this->parte('representante', 'Representante de traslado · ' . $this->nombreCorto(), null, 'sin_precio', false,
                null, 'Incluido en el precio del traslado; ajústalo si lo cubre otro proveedor.');
        }

        return $partes;
    }

    private function machuPicchu(string $codigo, string $texto): array
    {
        $dias = Str::contains($texto, ['two days', '2d/1n', '2 dias']) ? 2 : 1;
        $esCamino = str_starts_with($codigo, 'KM104');

        $partes = [$this->parte('guia', $this->servicio->nombre, null, 'reparto', true, $this->servicio->descripcion)];

        $entrada = Str::contains($texto, 'huayna') ? 'MP-HUAYNA' : 'MP-INC';
        $partes[] = $this->delCatalogo($entrada, 'entrada', $dias);
        $partes[] = $this->delCatalogo('BUS-CONSETTUR-RT', 'traslado', $esCamino ? 1 : $dias);

        if ($dias === 2 && Str::contains($texto, 'guia pernocta') && ! Str::contains($texto, 'no pernocta')) {
            $partes[] = $this->delCatalogo('PERN-MAPI-GUIA', 'otro');
        }

        return array_values(array_filter($partes));
    }

    private function tour(string $texto): array
    {
        $sinGuia = Str::contains($texto, ['no incluye guia', 'sin guia', 'solo transporte']);
        $aPie = Str::contains($texto, ['on foot', 'a pie', 'walking']);
        $partes = [];

        if (! $sinGuia) {
            $partes[] = $this->parte('guia', $this->servicio->nombre, null, 'reparto', true, $this->servicio->descripcion);
        }
        if (! $aPie) {
            $nombre = $sinGuia ? $this->servicio->nombre : 'Transporte privado · ' . $this->nombreCorto();
            $partes[] = $this->parte('traslado', $nombre, null, 'reparto', $sinGuia, $sinGuia ? $this->servicio->descripcion : null);
        }

        foreach (self::ENTRADAS as $codigo => $claves) {
            if (Str::contains($texto, $claves)) {
                $partes[] = $this->delCatalogo($codigo, 'entrada');
            }
        }

        if ((float) $this->servicio->duracion_horas >= 7 && ! Str::contains($texto, 'sin almuerzo')) {
            $almuerzo = $this->servicio->categoria === 'trek' ? 'BOX-LUNCH-SIMPLE'
                : (Str::contains($texto, self::VALLE) ? 'ALM-URUBAMBA' : null);
            $partes[] = $almuerzo
                ? $this->delCatalogo($almuerzo, 'comida')
                : $this->parte('comida', 'Almuerzo · ' . $this->nombreCorto(), null, 'sin_precio', false);
        }

        return array_values(array_filter($partes));
    }

    /**
     * Partes definidas en la columna `desglose` del catálogo (p. ej. los traslados de la hoja TRFS). Cada una:
     *  - tipo, nombre;
     *  - normal / feriado: costo por pasajero según cantidad de pasajeros ('1'..'20'); sin clave para N pax = no aplica
     *    (p. ej. el 2.º representante desde 9 pax); por encima de la última clave se usa la última;
     *  - codigo: toma el precio de otro servicio del catálogo (p. ej. PROP-MALETEROS);
     *  - adicional: se suma aparte de la tarifa (propinas, que no comisionan).
     * La primera parte lleva el nombre del servicio y es la única visible al cliente.
     */
    private function desdeDefinicion(array $definicion): array
    {
        $pax = $this->adultos + $this->ninos;
        $partes = [];

        foreach ($definicion as $d) {
            $tipo = $d['tipo'] ?? 'otro';
            $nombre = $partes === [] ? $this->servicio->nombre : ($d['nombre'] ?? 'Parte') . ' · ' . $this->nombreCorto();

            if (isset($d['normal'])) {
                $escala = ($this->feriado && isset($d['feriado'])) ? $d['feriado'] : $d['normal'];
                $costo = self::valorEscala($escala, $pax);
                if ($costo === null) {
                    continue;
                }
                $parte = $this->parte($tipo, $nombre, null, 'catalogo', $partes === [], $partes === [] ? $this->servicio->descripcion : null);
                $parte['costo'] = $costo;
            } elseif (! empty($d['codigo'])) {
                $parte = $this->delCatalogo($d['codigo'], $tipo, (int) ($d['cantidad'] ?? 1));
                if (! $parte) {
                    continue;
                }
                $parte['nombre'] = Str::limit($nombre, 250, '');
            } else {
                $parte = $this->parte($tipo, $nombre, null, 'sin_precio', $partes === []);
            }

            if (! empty($d['adicional'])) {
                $parte['incluido'] = false;
            }
            $partes[] = $parte;
        }

        return $partes ?: [$this->entero()];
    }

    private static function valorEscala(array $escala, int $pax): ?float
    {
        $claves = array_map('intval', array_keys($escala));
        if ($claves === [] || $pax < min($claves)) {
            return null;
        }
        $clave = isset($escala[(string) $pax]) ? (string) $pax : (string) max($claves);

        return round((float) $escala[$clave], 2);
    }

    /** El servicio completo en una sola fila (entradas, almuerzos, ceremonias, componentes simples…). */
    private function entero(): array
    {
        $parte = $this->parte(self::TIPO_POR_CATEGORIA[$this->servicio->categoria] ?? 'tour', $this->servicio->nombre,
            $this->servicio->codigo, 'catalogo', true, $this->servicio->descripcion);
        $parte['costo'] = $this->servicio->costoPorPax($this->adultos, $this->ninos, $this->feriado);

        return $parte;
    }

    private function delCatalogo(string $codigo, string $tipo, int $cantidad = 1): ?array
    {
        $item = $this->catalogo()->get($codigo);
        if (! $item) {
            return null;
        }

        $nombre = $item->nombre . ($cantidad > 1 ? " (×{$cantidad})" : '');
        $parte = $this->parte($tipo, $nombre, $item->codigo, 'catalogo', false);
        $parte['costo'] = round($item->costoPorPax($this->adultos, $this->ninos, $this->feriado) * $cantidad, 2);
        $parte['comision_pct'] = (float) ($item->comision_pct ?? 0);
        $parte['igv_comision'] = (bool) $item->aplica_igv_comision;

        return $parte;
    }

    private function parte(string $tipo, string $nombre, ?string $codigo, string $origen, bool $visible, ?string $descripcion = null, ?string $nota = null): array
    {
        return [
            'tipo' => $tipo,
            'nombre' => Str::limit($nombre, 250, ''),
            'codigo' => $codigo,
            'costo' => null,
            'origen' => $origen,          // catalogo | reparto | sin_precio
            'visible_cliente' => $visible,
            'descripcion' => $descripcion,
            'nota' => $nota,
            // Comisión de la hoja (neto + comisión + IGV de la comisión); las partes de otro código usan la suya.
            'comision_pct' => (float) ($this->servicio->comision_pct ?? 0),
            'igv_comision' => (bool) $this->servicio->aplica_igv_comision,
        ];
    }

    private function esMachuPicchu(string $codigo, string $texto): bool
    {
        return str_starts_with($codigo, 'MP-') || $codigo === 'FMAPI' || str_starts_with($codigo, 'KM104')
            || Str::contains($texto, ['day at the ruins', 'days at the ruins']);
    }

    private function nombreCorto(): string
    {
        return Str::limit(trim(preg_replace('/\s*\(.*?\)\s*/', ' ', $this->servicio->nombre)), 70);
    }

    private function catalogo(): Collection
    {
        return $this->catalogo ??= TarifarioServicio::query()->activo()->get()->keyBy('codigo');
    }
}
