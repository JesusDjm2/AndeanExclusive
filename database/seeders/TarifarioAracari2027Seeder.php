<?php

namespace Database\Seeders;

use App\Models\TarifarioServicio;
use Illuminate\Database\Seeder;

class TarifarioAracari2027Seeder extends Seeder
{
    /**
     * Carga el Catálogo Completo del Tarifario Maestro Oficial Aracari 2027.
     * Incluye todas las pestañas oficiales:
     * - Boletos y Entradas Oficiales (BTG, BTP, Iglesias, Templos, Sitios Arqueológicos)
     * - Viáticos y Propinas de Operaciones
     * - Traslados Escalonados (1 a 20 Pax)
     * - Tours Valle Sagrado y Experiencias Cusco (Adultos + Niños + Feriados)
     * - Machu Picchu y Camino Inca (Full Day, 2D/1N Sanctuary / Aguas Calientes, Adultos, Niños y Feriados)
     * - Treks y Caminatas de Aventura (15 circuitos completos con escalas y feriados)
     * - Componentes Logísticos Operativos (Transporte a disposición, porteadores, snacks, bastones, maleteros)
     * - Ceremonias Místicas de Pago a la Tierra (Cusco, Valle Sagrado y Machu Picchu)
     * - Alimentación, Restaurantes Locales, Picnics Campestres y Box Lunches
     */
    public function run(): void
    {
        TarifarioServicio::query()->delete();
        // ---- Ayudantes para las escalas de la hoja (costo por pasajero de 1 a 20 pax) ----
        // $escala([...], 9) → ['9' => …, '10' => …]: valores desde N pasajeros (antes de N la parte no aplica).
        $escala = fn (array $valores, int $desde = 1) => array_combine(
            array_map('strval', range($desde, $desde + count($valores) - 1)),
            $valores
        );
        $suma = function (array ...$escalas): array {
            $total = [];
            foreach ($escalas as $e) {
                foreach ($e as $pax => $valor) {
                    $total[$pax] = round(($total[$pax] ?? 0) + $valor, 2);
                }
            }

            return $total;
        };
        $porGrupo = fn (float $total, int $hasta) => $escala(array_map(fn ($n) => round($total / $n, 2), range(1, $hasta)));
        // Una parte del desglose; en feriado usa su propia escala si la tiene.
        $parte = fn (string $tipo, string $nombre, array $normal, ?array $feriado = null) => array_filter(
            ['tipo' => $tipo, 'nombre' => $nombre, 'normal' => $normal, 'feriado' => $feriado],
            fn ($v) => $v !== null
        );

        $agua = $escala(array_fill(0, 20, 1.30));
        $propinaAeropuerto = ['tipo' => 'otro', 'nombre' => 'Propina maleteros aeropuerto', 'codigo' => 'PROP-MALETEROS', 'adicional' => true];

        // Traslados dentro de Cusco (aeropuerto, Wanchaq): en feriado se paga doble el representante.
        $tptCusco = $escala([20.00, 10.00, 8.33, 6.25, 5.00, 4.17, 3.57, 4.38, 3.89, 3.50, 3.18, 2.92, 3.85, 3.57, 4.00, 4.38, 4.12, 3.89, 3.68, 3.50]);
        $repCusco = $escala([15.00, 7.50, 5.00, 3.75, 3.00, 2.50, 2.14, 1.88, 1.67, 1.50, 1.36, 1.25, 1.15, 1.07, 1.00, 0.94, 0.88, 0.83, 0.79, 0.75]);
        $rep2Cusco = $escala([1.67, 1.50, 1.36, 1.25, 1.15, 1.07, 1.00, 0.94, 0.88, 0.83, 0.79, 0.75], 9);
        $trasladoUrbano = fn (bool $conPropina) => array_values(array_filter([
            $parte('traslado', 'Transporte', $tptCusco),
            $parte('representante', 'Representante', $repCusco, $suma($repCusco, $repCusco)),
            $parte('representante', '2.º representante', $rep2Cusco),
            $parte('otro', 'Agua embotellada', $agua),
            $conPropina ? $propinaAeropuerto : null,
        ]));

        // Traslados Cusco / aeropuerto / Poroy <-> Valle Sagrado.
        $tptValle = $escala([95.00, 47.50, 38.33, 28.75, 23.00, 19.17, 16.43, 16.88, 15.00, 13.50, 12.27, 11.25, 17.69, 16.43, 16.67, 16.88, 15.88, 15.00, 14.21, 13.50]);
        $repValle = $escala([25.00, 12.50, 8.33, 6.25, 5.00, 4.17, 3.57, 3.13, 2.78, 2.50, 2.27, 2.08, 1.92, 1.79, 1.67, 1.56, 1.47, 1.39, 1.32, 1.25]);
        $rep2Valle = $escala([2.78, 2.50, 2.27, 2.08, 1.92, 1.79, 1.67, 1.56, 1.47, 1.39, 1.32, 1.25], 9);
        $aguaRep = $escala([1.30, 0.65, 0.43, 0.33, 0.26, 0.22, 0.19, 0.16, 0.14, 0.13, 0.12, 0.11, 0.11, 0.11, 0.11, 0.11, 0.11, 0.11, 0.11, 0.11]);
        $trasladoValle = fn (bool $aguaRepresentante, bool $conPropina) => array_values(array_filter([
            $parte('traslado', 'Transporte', $tptValle),
            $parte('representante', 'Representante', $repValle, $suma($repValle, $repValle)),
            $parte('representante', '2.º representante', $rep2Valle),
            $parte('otro', 'Agua embotellada', $agua),
            $aguaRepresentante ? $parte('otro', 'Agua del representante', $aguaRep) : null,
            $conPropina ? $propinaAeropuerto : null,
        ]));

        $tptPoroy = $escala([25.00, 12.50, 11.33, 8.50, 6.80, 5.67, 4.86, 5.63, 5.00, 4.50, 4.09, 3.75, 5.23, 4.86, 5.27, 5.63, 5.29, 5.00, 4.74, 4.50]);
        $repPoroy = $escala([20.00, 10.00, 6.67, 5.00, 4.00, 3.33, 2.86, 2.50, 2.22, 2.00, 1.82, 1.67, 1.67, 1.67, 1.67, 1.67, 1.67, 1.67, 1.67, 1.67]);

        // Traslados cortos (round trip de 2 horas) y con guía en la cena.
        $tptCuscoCorto = $escala([30.00, 17.50, 13.33, 10.00, 8.00, 6.67, 5.71, 6.88, 6.11, 5.50, 5.00, 4.58, 6.15, 5.71, 6.33, 6.88, 6.47, 6.11, 5.79, 5.50]);
        $tptValleCorto = $escala([85.00, 42.50, 31.67, 23.75, 19.00, 15.83, 13.57, 12.50, 11.11, 10.00, 9.09, 8.33, 14.62, 13.57, 13.00, 12.50, 11.76, 11.11, 10.53, 10.00]);
        $guiaCena = $escala([65.00, 32.50, 21.67, 16.25, 13.00, 10.83, 9.29, 8.13, 14.44, 13.00, 11.82, 10.83, 10.00, 9.29, 8.67, 8.13, 11.47, 10.83, 10.26, 9.75]);
        $guiaCenaInd = $escala([80.00, 40.00, 26.67, 20.00, 16.00, 13.33, 11.43, 10.00, 17.78, 16.00, 14.55, 13.33, 12.31, 11.43, 10.67, 10.00, 9.41, 8.89, 8.42, 8.00]);
        $hotelGuia = $escala([50.00, 25.00, 16.67, 12.50, 10.00, 8.33, 7.14, 6.25, 11.11, 10.00, 9.09, 8.33, 7.69, 7.14, 6.67, 6.25, 8.82, 8.33, 7.89, 7.50]);
        $hotelConductor = $escala([50.00, 25.00, 16.67, 12.50, 10.00, 8.33, 7.14, 6.25, 5.56, 5.00, 4.55, 4.17, 7.69, 7.14, 6.67, 6.25, 5.88, 5.56, 5.26, 5.00]);


        $servicios = [
            // ==========================================
            // 1. BOLETOS Y ENTRADAS OFICIALES
            // ==========================================
            [
                'codigo' => 'BTG',
                'categoria' => 'boleto',
                'nombre' => 'Boleto Turístico General (BTG)',
                'descripcion' => 'Válido por 10 días para 16 atractivos en Cusco, Valle Sagrado y circuitos arqueológicos (Sacsayhuamán, Qenqo, Puka Pukara, Tambomachay, Pisac, Ollantaytambo, Chinchero, Moray, Tipón, Pikillacta, etc.).',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 39.39, // S/ 130 @ 3.3
                'precio_nino' => 21.21,   // S/ 70 @ 3.3
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Tarifa oficial en Soles convertida a T/C 3.3. Requiere pasaporte original en ingreso.',
            ],
            [
                'codigo' => 'BTP',
                'categoria' => 'boleto',
                'nombre' => 'Boleto Turístico Parcial (BTP)',
                'descripcion' => 'Válido por 1 a 2 días para un circuito específico (Circuito I: Sacsayhuamán, Circuito II: Valle Sur/Museos, o Circuito III: Valle Sagrado).',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 21.21, // S/ 70 @ 3.3
                'precio_nino' => 21.21,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Válido para un solo circuito por 1 o 2 días.',
            ],
            [
                'codigo' => 'ENT-CATEDRAL',
                'categoria' => 'boleto',
                'nombre' => 'Entrada Catedral Basílica del Cusco',
                'descripcion' => 'Acceso a la Catedral, Templo del Triunfo y Sagrada Familia.',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 12.12, // S/ 40 @ 3.3
                'precio_nino' => 6.06,    // S/ 20 @ 3.3
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'ENT-QORICANCHA',
                'categoria' => 'boleto',
                'nombre' => 'Entrada Templo del Sol Qoricancha',
                'descripcion' => 'Templo inca del Sol sobre el cual se edificó el convento de Santo Domingo.',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 6.06, // S/ 20 @ 3.3
                'precio_nino' => 3.03,   // S/ 10 @ 3.3
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'ENT-SAN-BLAS',
                'categoria' => 'boleto',
                'nombre' => 'Entrada Templo de San Blas',
                'descripcion' => 'Famoso púlpito tallado en cedro barroco y vistas del barrio tradicional de artesanos.',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 6.06,
                'precio_nino' => 3.03,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'ENT-CASA-CONCHA',
                'categoria' => 'boleto',
                'nombre' => 'Entrada Museo Machu Picchu - Casa Concha',
                'descripcion' => 'Colección de piezas de Machu Picchu repatriadas por la Universidad de Yale.',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 6.06,
                'precio_nino' => 3.03,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'ENT-MAP',
                'categoria' => 'boleto',
                'nombre' => 'Entrada Museo de Arte Precolombino (MAP)',
                'descripcion' => 'Casona colonial Cabrera con curaduría de piezas preincaicas y patio colonial.',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 6.06,
                'precio_nino' => 3.03,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'ENT-MARAS',
                'categoria' => 'boleto',
                'nombre' => 'Entrada Salineras de Maras',
                'descripcion' => 'Comunidad de Maras / Salineras con más de 3,000 pozas artesanales de sal rosada.',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 6.06, // S/ 20 @ 3.3
                'precio_nino' => 6.06,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Cobrado directamente por la comunidad campesina de Maras.',
            ],
            [
                'codigo' => 'ENT-ANDAHUAYLILLAS',
                'categoria' => 'boleto',
                'nombre' => 'Entrada Iglesia de San Pedro Apóstol de Andahuaylillas',
                'descripcion' => 'Conocida como la Capilla Sixtina de América por sus frescos dorados y techos mudéjares.',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 4.55, // S/ 15 @ 3.3
                'precio_nino' => 3.03,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'ENT-HUARO',
                'categoria' => 'boleto',
                'nombre' => 'Entrada Iglesia San Juan Bautista de Huaro',
                'descripcion' => 'Murales del Juicio Final pintados por Tadeo Escalante.',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 4.55,
                'precio_nino' => 3.03,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'ENT-CANINCUNCA',
                'categoria' => 'boleto',
                'nombre' => 'Entrada Capilla de la Virgen de la Candelaria de Canincunca',
                'descripcion' => 'Capilla al borde de la laguna de Urcos con frescos barrocos indígenas.',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 4.55,
                'precio_nino' => 3.03,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'ENT-RAQCHI',
                'categoria' => 'boleto',
                'nombre' => 'Entrada Templo de Wiracocha en Raqchi',
                'descripcion' => 'Parque Arqueológico de Raqchi en la Ruta del Sol hacia Puno.',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 6.06,
                'precio_nino' => 3.03,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'BUS-CONSETTUR-RT',
                'categoria' => 'boleto',
                'nombre' => 'Ticket de Bus Consettur Aguas Calientes <-> Machu Picchu (Round Trip)',
                'descripcion' => 'Subida y bajada en autobús ecológico Consettur.',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 24.00,
                'precio_nino' => 15.00,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'MP-INC',
                'categoria' => 'boleto',
                'nombre' => 'Entrada Oficial Machu Picchu Llaqta (INC)',
                'descripcion' => 'Boleto oficial de ingreso al Santuario Histórico de Machu Picchu (Circuito clásico).',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 46.06, // S/ 152 @ 3.3
                'precio_nino' => 23.33,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Boleto nominal intransferible emitido por Ministerio de Cultura.',
            ],
            [
                'codigo' => 'MP-HUAYNA',
                'categoria' => 'boleto',
                'nombre' => 'Entrada Machu Picchu con Montaña Huayna Picchu',
                'descripcion' => 'Circuito 4 + ascenso exigente a la cima de Huayna Picchu.',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 60.60, // S/ 200 @ 3.3
                'precio_nino' => 35.75,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'ENT-ARTE-RELIGIOSO',
                'categoria' => 'boleto',
                'nombre' => 'Entrada Museo de Arte Religioso (Palacio Arzobispal)',
                'descripcion' => 'Colección de lienzos de la escuela cusqueña frente a la piedra de los 12 ángulos.',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 3.03,
                'precio_nino' => 3.03,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'ENT-SANTA-CATALINA',
                'categoria' => 'boleto',
                'nombre' => 'Entrada Museo Monasterio de Santa Catalina',
                'descripcion' => 'Claustro colonial y pinacoteca virreinal.',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 2.42,
                'precio_nino' => 2.42,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],

            // ==========================================
            // 2. VIÁTICOS Y PROPINAS
            // ==========================================
            [
                'codigo' => 'VIAT-MAPI',
                'categoria' => 'viatico',
                'nombre' => 'Viáticos de Almuerzo en Machu Picchu (Guía / Especialista)',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 16.00,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'VIAT-VALLE',
                'categoria' => 'viatico',
                'nombre' => 'Viáticos de Almuerzo en Valle Sagrado (Guía)',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 16.00,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'PERN-VALLE-GUIA',
                'categoria' => 'viatico',
                'nombre' => 'Viáticos Pernocte Valle Sagrado - Guía (Hotel $55 + Cena $16)',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 71.00,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Aplica cuando el guía pernocta en el Valle para servicio al día siguiente.',
            ],
            [
                'codigo' => 'PERN-MAPI-GUIA',
                'categoria' => 'viatico',
                'nombre' => 'Viáticos Pernocte Machu Picchu - Guía (Hotel $60 + Cena $16)',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 76.00,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Aplica en tours de 2 días a Machu Picchu con pernocte de guía.',
            ],
            [
                'codigo' => 'PROP-MALETEROS',
                'categoria' => 'propina',
                'nombre' => 'Propina Sugerida Maleteros Aeropuerto / Estación',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 2.00,
                'comision_pct' => 0.00,
                'aplica_igv_comision' => false,
                'moneda' => 'USD',
                'notas' => 'Sin comisión ni impuestos.',
            ],
            [
                'codigo' => 'PROP-SARAPAMPA-PRIV',
                'categoria' => 'propina',
                'nombre' => 'Propina Almuerzo Privado Hacienda Sarapampa',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 9.00,
                'comision_pct' => 0.00,
                'aplica_igv_comision' => false,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'PROP-HUAYOCCARI',
                'categoria' => 'propina',
                'nombre' => 'Propina Hacienda Huayoccari / Albergue Ollantaytambo',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 5.15, // S/ 17 @ 3.3
                'comision_pct' => 0.00,
                'aplica_igv_comision' => false,
                'moneda' => 'USD',
            ],

            // ==========================================
            // 3. TRASLADOS ESCALONADOS (1 a 20 PAX) — hoja TRFS del Tarifario Aracari 2027
            // Precio = subtotal neto de la hoja (la comisión e IGV se calculan aparte). `desglose` guarda cada parte
            // (transporte, representante, agua…) para agregarlas como servicios separados en el itinerario.
            // La propina de maleteros de aeropuerto no comisiona: va como adicional (PROP-MALETEROS).
            // ==========================================
            [
                'codigo' => 'APTHTL',
                'categoria' => 'traslado',
                'nombre' => 'Traslado Aeropuerto Cusco <-> Hotel Cusco (y viceversa)',
                'descripcion' => 'Vehículo privado con chofer profesional, representante bilingüe y agua embotellada.',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => $escala([36.30, 18.80, 14.63, 11.30, 9.30, 7.97, 7.01, 7.55, 8.52, 7.80, 7.21, 6.72, 7.45, 7.01, 7.30, 7.55, 7.18, 6.86, 6.56, 6.30]),
                    'feriado' => $escala([51.30, 26.30, 19.63, 15.05, 12.30, 10.47, 9.16, 9.43, 10.19, 9.30, 8.57, 7.97, 8.61, 8.09, 8.30, 8.49, 8.06, 7.69, 7.35, 7.05]),
                ],
                'desglose' => $trasladoUrbano(true),
                'notas' => 'Códigos APTHTL / HTLAPT. Desde 9 pasajeros se considera 2.º representante. Propina de maleteros (US$ 2 por pasajero) aparte, sin comisión.',
            ],
            [
                'codigo' => 'HTLWAN',
                'categoria' => 'traslado',
                'nombre' => 'Traslado Hotel Cusco <-> Estación Wanchaq (y viceversa)',
                'descripcion' => 'Vehículo privado con chofer profesional, representante bilingüe y agua embotellada.',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => $escala([36.30, 18.80, 14.63, 11.30, 9.30, 7.97, 7.01, 7.55, 8.52, 7.80, 7.21, 6.72, 7.45, 7.01, 7.30, 7.55, 7.18, 6.86, 6.56, 6.30]),
                    'feriado' => $escala([51.30, 26.30, 19.63, 15.05, 12.30, 10.47, 9.16, 9.43, 10.19, 9.30, 8.57, 7.97, 8.61, 8.09, 8.30, 8.49, 8.06, 7.69, 7.35, 7.05]),
                ],
                'desglose' => $trasladoUrbano(false),
                'notas' => 'Códigos HTLWAN / WANHTL. Desde 9 pasajeros se considera 2.º representante.',
            ],
            [
                'codigo' => 'CUSOLL',
                'categoria' => 'traslado',
                'nombre' => 'Traslado Cusco <-> Urubamba o Estación Ollantaytambo',
                'descripcion' => 'Traslado privado interprovincial entre Cusco y Valle Sagrado, con representante y agua embotellada.',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => $escala([122.60, 61.95, 48.40, 36.63, 29.56, 24.85, 21.49, 21.46, 22.00, 19.93, 18.24, 16.83, 22.95, 21.41, 21.41, 21.41, 20.23, 19.19, 18.25, 17.41]),
                    'feriado' => $escala([147.60, 74.45, 56.73, 42.88, 34.56, 29.02, 25.06, 24.59, 24.78, 22.43, 20.51, 18.91, 24.87, 23.19, 23.08, 22.97, 21.70, 20.58, 19.57, 18.66]),
                ],
                'desglose' => $trasladoValle(true, false),
                'notas' => 'Códigos CUSOLL / OLLCUS · CUSURU / URUCUS. Desde 9 pasajeros se considera 2.º representante.',
            ],
            [
                'codigo' => 'APTOLL',
                'categoria' => 'traslado',
                'nombre' => 'Traslado Aeropuerto Cusco <-> Urubamba o Estación Ollantaytambo',
                'descripcion' => 'Traslado privado del aeropuerto al Valle Sagrado (o viceversa), con representante y agua embotellada.',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => $escala([122.60, 61.95, 48.40, 36.63, 29.56, 24.85, 21.49, 21.46, 22.00, 19.93, 18.24, 16.83, 22.95, 21.41, 21.41, 21.41, 20.23, 19.19, 18.25, 17.41]),
                    'feriado' => $escala([147.60, 74.45, 56.73, 42.88, 34.56, 29.02, 25.06, 24.59, 24.78, 22.43, 20.51, 18.91, 24.87, 23.19, 23.08, 22.97, 21.70, 20.58, 19.57, 18.66]),
                ],
                'desglose' => $trasladoValle(true, true),
                'notas' => 'Códigos APTOLL / OLLAPT · APTURU / URUAPT. Desde 9 pasajeros se considera 2.º representante. Propina de maleteros (US$ 2 por pasajero) aparte, sin comisión.',
            ],
            [
                'codigo' => 'URUPOR',
                'categoria' => 'traslado',
                'nombre' => 'Traslado Urubamba <-> Estación de Tren Poroy',
                'descripcion' => 'Traslado privado entre el Valle Sagrado y la estación de Poroy, con representante y agua embotellada.',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => $escala([121.30, 61.30, 47.97, 36.30, 29.30, 24.63, 21.30, 21.30, 21.86, 19.80, 18.12, 16.72, 22.84, 21.30, 21.30, 21.30, 20.12, 19.08, 18.14, 17.30]),
                    'feriado' => $escala([146.30, 73.80, 56.30, 42.55, 34.30, 28.80, 24.87, 24.43, 24.63, 22.30, 20.39, 18.80, 24.76, 23.09, 22.97, 22.86, 21.59, 20.47, 19.46, 18.55]),
                ],
                'desglose' => $trasladoValle(false, false),
                'notas' => 'Código URUPOR. Desde 9 pasajeros se considera 2.º representante.',
            ],
            [
                'codigo' => 'URUOLL',
                'categoria' => 'traslado',
                'nombre' => 'Traslado Urubamba <-> Ollantaytambo (y viceversa)',
                'descripcion' => 'Traslado privado dentro del Valle Sagrado, con representante y agua embotellada.',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => $escala([121.30, 61.30, 47.97, 36.30, 29.30, 24.63, 21.30, 21.30, 21.86, 19.80, 18.12, 16.72, 22.84, 21.30, 21.30, 21.30, 20.12, 19.08, 18.14, 17.30]),
                    'feriado' => $escala([146.30, 73.80, 56.30, 42.55, 34.30, 28.80, 24.87, 24.43, 24.63, 22.30, 20.39, 18.80, 24.76, 23.09, 22.97, 22.86, 21.59, 20.47, 19.46, 18.55]),
                ],
                'desglose' => $trasladoValle(false, false),
                'notas' => 'Códigos URUOLL / OLLURU. Desde 9 pasajeros se considera 2.º representante.',
            ],
            [
                'codigo' => 'CUSPOR',
                'categoria' => 'traslado',
                'nombre' => 'Traslado Cusco <-> Estación de Tren Poroy',
                'descripcion' => 'Vehículo privado con chofer profesional, representante y agua embotellada.',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => $escala([46.30, 23.80, 19.30, 14.80, 12.10, 10.30, 9.01, 9.43, 10.74, 9.80, 9.03, 8.38, 9.86, 9.49, 9.90, 10.26, 9.93, 9.63, 9.37, 9.13]),
                    'feriado' => $escala([66.30, 33.80, 25.97, 19.80, 16.10, 13.63, 11.87, 11.93, 12.97, 11.80, 10.85, 10.05, 11.53, 11.16, 11.57, 11.93, 11.59, 11.30, 11.04, 10.80]),
                ],
                'desglose' => [
                    $parte('traslado', 'Transporte', $tptPoroy),
                    $parte('representante', 'Representante', $repPoroy, $suma($repPoroy, $repPoroy)),
                    $parte('representante', '2.º representante', $escala([2.22, 2.00, 1.82, 1.67, 1.67, 1.67, 1.67, 1.67, 1.67, 1.67, 1.67, 1.67], 9)),
                    $parte('otro', 'Agua embotellada', $agua),
                ],
                'notas' => 'Códigos CUSPOR / PORCUS. Desde 9 pasajeros se considera 2.º representante.',
            ],
            [
                'codigo' => 'RESTRT',
                'categoria' => 'traslado',
                'nombre' => 'Traslado Hotel <-> Restaurante Cusco (Round Trip - 2 hrs)',
                'tipo_calculo' => 'escala_pax',
                'duracion_horas' => 2.0,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => ['normal' => $tptCuscoCorto],
                'desglose' => [$parte('traslado', 'Transporte', $tptCuscoCorto)],
            ],
            [
                'codigo' => 'SABART',
                'categoria' => 'traslado',
                'nombre' => 'Traslado Hotel <-> Plaza de Armas o San Blas (Round Trip - 2 hrs)',
                'descripcion' => 'Para la experiencia de 2 horas con fotografía de Totemiq.',
                'tipo_calculo' => 'escala_pax',
                'duracion_horas' => 2.0,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => ['normal' => $tptCuscoCorto],
                'desglose' => [$parte('traslado', 'Transporte', $tptCuscoCorto)],
                'notas' => 'Código SABART.',
            ],
            [
                'codigo' => 'RESVRT',
                'categoria' => 'traslado',
                'nombre' => 'Traslado Hotel <-> Restaurante en Valle Sagrado (Round Trip)',
                'tipo_calculo' => 'escala_pax',
                'duracion_horas' => 2.0,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => ['normal' => $tptValleCorto],
                'desglose' => [$parte('traslado', 'Transporte', $tptValleCorto)],
            ],
            [
                'codigo' => 'RESGUI',
                'categoria' => 'traslado',
                'nombre' => 'Traslado Hotel <-> Restaurante con guía acompañando la cena (Round Trip)',
                'descripcion' => 'Traslado ida y vuelta al restaurante con guía que acompaña a los pasajeros durante la cena.',
                'tipo_calculo' => 'escala_pax',
                'duracion_horas' => 2.0,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => $escala([150.00, 75.00, 53.33, 40.00, 32.00, 26.67, 22.86, 20.63, 25.56, 23.00, 20.91, 19.17, 24.62, 22.86, 21.67, 20.63, 23.24, 21.94, 20.79, 19.75]),
                ],
                'desglose' => [
                    $parte('traslado', 'Transporte', $tptValleCorto),
                    $parte('guia', 'Guía durante la cena', $guiaCena),
                ],
                'notas' => 'Código provisional RESGUI (la hoja no trae código). Sin tarifa de feriado en la hoja.',
            ],
            [
                'codigo' => 'RESGUI-VALLE',
                'categoria' => 'traslado',
                'nombre' => 'Traslado con guía durante la cena en Valle Sagrado (complemento de un servicio en el Valle)',
                'descripcion' => 'Traslado con guía que cena con los pasajeros; incluye hotel del guía y del conductor. Tarifa para cuando complementa otro servicio del día en el Valle Sagrado.',
                'tipo_calculo' => 'escala_pax',
                'duracion_horas' => 3.0,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => $escala([250.00, 125.00, 86.68, 65.00, 52.00, 43.32, 37.14, 33.13, 42.22, 38.00, 34.55, 31.66, 40.00, 37.14, 35.01, 33.13, 37.93, 35.83, 33.94, 32.25]),
                    'feriado' => $escala([315.00, 157.50, 108.35, 81.25, 65.00, 54.15, 46.43, 41.26, 56.66, 51.00, 46.37, 42.49, 50.00, 46.43, 43.68, 41.26, 49.40, 46.66, 44.20, 42.00]),
                ],
                'desglose' => [
                    $parte('traslado', 'Transporte', $tptValleCorto),
                    $parte('guia', 'Guía durante la cena', $guiaCena, $suma($guiaCena, $guiaCena)),
                    $parte('alojamiento', 'Hotel del guía', $hotelGuia),
                    $parte('alojamiento', 'Hotel del conductor', $hotelConductor),
                ],
                'notas' => 'Código provisional RESGUI-VALLE (la hoja no trae código). En feriado se duplica el servicio de guía.',
            ],
            [
                'codigo' => 'RESGUI-VALLE-IND',
                'categoria' => 'traslado',
                'nombre' => 'Traslado con guía durante la cena en Valle Sagrado (servicio independiente)',
                'descripcion' => 'Traslado con guía que cena con los pasajeros; incluye hotel del guía y del conductor. Tarifa para cuando es el único servicio del día en el Valle Sagrado.',
                'tipo_calculo' => 'escala_pax',
                'duracion_horas' => 3.0,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => $escala([275.00, 137.50, 98.34, 73.75, 59.00, 49.16, 42.14, 39.38, 49.45, 44.50, 40.46, 37.08, 45.38, 42.14, 40.67, 39.38, 39.99, 37.78, 35.78, 34.00]),
                    'feriado' => $escala([355.00, 177.50, 125.01, 93.75, 75.00, 62.49, 53.57, 49.38, 67.23, 60.50, 55.00, 50.42, 57.69, 53.57, 51.34, 49.38, 49.41, 46.67, 44.20, 42.00]),
                ],
                'desglose' => [
                    $parte('traslado', 'Transporte', $tptValle),
                    $parte('guia', 'Guía durante la cena', $guiaCenaInd, $suma($guiaCenaInd, $guiaCenaInd)),
                    $parte('alojamiento', 'Hotel del guía', $hotelGuia),
                    $parte('alojamiento', 'Hotel del conductor', $hotelConductor),
                ],
                'notas' => 'Código provisional RESGUI-VALLE-IND (la hoja no trae código). En feriado se duplica el servicio de guía.',
            ],
            [
                'codigo' => 'FDAWA',
                'categoria' => 'tour',
                'nombre' => 'Awanacancha, Pisac Market & Ruins & Ollantaytambo (FD Valle)',
                'descripcion' => 'Día completo en Valle Sagrado: tejedoras y camélidos en Awanacancha, mercado artesanal o ruinas de Pisac, y fortaleza inca de Ollantaytambo.',
                'lugares_visita' => 'Awanacancha, Pisac Market / Ruins, Ollantaytambo',
                'duracion_horas' => 8.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 234.90, '2' => 122.75, '3' => 93.70, '4' => 75.43, '5' => 62.46,
                        '6' => 53.82,  '7' => 47.64,  '8' => 44.89, '9' => 51.63, '10' => 47.53,
                        '11' => 44.17, '12' => 41.38, '13' => 50.49, '14' => 47.64, '15' => 46.17,
                        '16' => 44.89, '17' => 48.46, '18' => 46.36, '19' => 44.47, '20' => 42.78,
                    ],
                    'feriado' => [
                        '1' => 329.90, '2' => 170.25, '3' => 125.37, '4' => 99.18, '5' => 81.46,
                        '6' => 69.65,  '7' => 61.21,  '8' => 56.76,  '9' => 72.74, '10' => 66.53,
                        '11' => 61.45, '12' => 57.21, '13' => 65.11, '14' => 61.21, '15' => 58.84,
                        '16' => 56.76, '17' => 65.22, '18' => 62.19, '19' => 59.47, '20' => 57.03,
                    ],
                ],
            ],
            [
                'codigo' => 'FDCHIM',
                'categoria' => 'tour',
                'nombre' => 'Chinchero, Maras & Moray (Full Day Valle Sagrado)',
                'descripcion' => 'Terrazas concéntricas de Moray, salineras de Maras y taller textil tradicional en Chinchero.',
                'lugares_visita' => 'Chinchero, Salineras de Maras, Terrazas de Moray',
                'duracion_horas' => 7.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 239.96, '2' => 130.31, '3' => 102.09, '4' => 81.74, '5' => 69.52,
                        '6' => 61.38,  '7' => 55.56,  '8' => 54.32,  '9' => 61.14, '10' => 57.09,
                        '11' => 53.78, '12' => 51.02, '13' => 59.01, '14' => 56.27, '15' => 55.23,
                        '16' => 54.32, '17' => 57.93, '18' => 55.86, '19' => 54.01, '20' => 52.34,
                    ],
                    'feriado' => [
                        '1' => 334.96, '2' => 177.81, '3' => 133.76, '4' => 105.49, '5' => 88.52,
                        '6' => 77.21,  '7' => 69.13,  '8' => 66.20,  '9' => 82.25,  '10' => 76.09,
                        '11' => 71.05, '12' => 66.85, '13' => 73.63, '14' => 69.85, '15' => 67.90,
                        '16' => 66.20, '17' => 74.70, '18' => 71.69, '19' => 69.01, '20' => 66.59,
                    ],
                ],
            ],

            // ==========================================
            // 4. TOURS Y EXPERIENCIAS CUSCO & VALLE (ADULTOS + NIÑOS + FERIADOS)
            // ==========================================
            [
                'codigo' => 'HINARC',
                'categoria' => 'tour',
                'nombre' => 'In and Around Cusco',
                'duracion_horas' => 4.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 152.78, '2' => 88.13, '3' => 71.58, '4' => 59.56, '5' => 52.34,
                        '6' => 47.53, '7' => 44.10, '8' => 42.14, '9' => 47.29, '10' => 44.91,
                        '11' => 42.96, '12' => 41.34, '13' => 45.68, '14' => 44.10, '15' => 43.06,
                        '16' => 42.14, '17' => 44.87, '18' => 43.68, '19' => 42.62, '20' => 41.66,
                    ],
                    'feriado' => [
                        '1' => 217.78, '2' => 120.63, '3' => 93.25, '4' => 75.81, '5' => 65.34,
                        '6' => 58.37, '7' => 53.38, '8' => 50.27, '9' => 61.74, '10' => 57.91,
                        '11' => 54.78, '12' => 52.17, '13' => 55.68, '14' => 53.38, '15' => 51.72,
                        '16' => 50.27, '17' => 56.34, '18' => 54.52, '19' => 52.88, '20' => 51.41,
                    ],
                    'nino' => [
                        '1' => 143.69, '2' => 79.04, '3' => 62.49, '4' => 50.47, '5' => 43.25,
                        '6' => 38.44, '7' => 35.01, '8' => 33.05, '9' => 38.20, '10' => 35.82,
                        '11' => 33.87, '12' => 32.25, '13' => 36.59, '14' => 35.01, '15' => 33.96,
                        '16' => 33.05, '17' => 35.78, '18' => 34.59, '19' => 33.53, '20' => 32.57,
                    ],
                    'feriado_nino' => [
                        '1' => 208.69, '2' => 111.54, '3' => 84.16, '4' => 66.72, '5' => 56.25,
                        '6' => 49.27, '7' => 44.29, '8' => 41.18, '9' => 52.65, '10' => 48.82,
                        '11' => 45.69, '12' => 43.08, '13' => 46.59, '14' => 44.29, '15' => 42.63,
                        '16' => 41.18, '17' => 47.25, '18' => 45.42, '19' => 43.79, '20' => 42.32,
                    ],
                ],
            ],
            [
                'codigo' => '4CUZCH',
                'categoria' => 'tour',
                'nombre' => 'CUSCO CHALLENGE',
                'duracion_horas' => 4.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 13.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 161.14, '2' => 95.84, '3' => 79.08, '4' => 66.94, '5' => 59.66,
                        '6' => 54.81, '7' => 51.34, '8' => 49.37, '9' => 54.50, '10' => 52.10,
                        '11' => 50.14, '12' => 48.51, '13' => 52.87, '14' => 51.28, '15' => 50.24,
                        '16' => 49.33, '17' => 48.23, '18' => 47.25, '19' => 46.37, '20' => 45.59,
                    ],
                    'feriado' => [
                        '1' => 226.14, '2' => 128.34, '3' => 100.74, '4' => 83.19, '5' => 72.66,
                        '6' => 65.64, '7' => 60.63, '8' => 57.49, '9' => 68.94, '10' => 65.10,
                        '11' => 61.96, '12' => 59.34, '13' => 62.87, '14' => 60.57, '15' => 58.91,
                        '16' => 57.45, '17' => 55.87, '18' => 54.47, '19' => 53.22, '20' => 52.09,
                    ],
                ],
            ],
            [
                'codigo' => 'HOUTSK',
                'categoria' => 'tour',
                'nombre' => 'Around the Outskirts',
                'duracion_horas' => 4.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 139.90, '2' => 75.10, '3' => 58.50, '4' => 46.45, '5' => 39.22,
                        '6' => 34.40, '7' => 30.96, '8' => 29.00, '9' => 34.14, '10' => 31.76,
                        '11' => 29.81, '12' => 28.18, '13' => 32.55, '14' => 30.96, '15' => 29.91,
                        '16' => 29.00, '17' => 31.72, '18' => 30.53, '19' => 29.47, '20' => 28.51,
                    ],
                    'feriado' => [
                        '1' => 204.90, '2' => 107.60, '3' => 80.17, '4' => 62.70, '5' => 52.22,
                        '6' => 45.23, '7' => 40.24, '8' => 37.12, '9' => 48.59, '10' => 44.76,
                        '11' => 41.63, '12' => 39.02, '13' => 42.55, '14' => 40.24, '15' => 38.58,
                        '16' => 37.12, '17' => 43.19, '18' => 41.37, '19' => 39.73, '20' => 38.26,
                    ],
                    'nino' => [
                        '1' => 134.60, '2' => 69.95, '3' => 53.40, '4' => 41.38, '5' => 34.16,
                        '6' => 29.35, '7' => 25.91, '8' => 23.96, '9' => 29.11, '10' => 26.73,
                        '11' => 24.78, '12' => 23.16, '13' => 27.50, '14' => 25.91, '15' => 24.87,
                        '16' => 23.96, '17' => 26.69, '18' => 25.50, '19' => 24.44, '20' => 23.48,
                    ],
                    'feriado_nino' => [
                        '1' => 199.60, '2' => 102.45, '3' => 75.07, '4' => 57.62, '5' => 47.16,
                        '6' => 40.18, '7' => 35.20, '8' => 32.09, '9' => 43.56, '10' => 39.73,
                        '11' => 36.60, '12' => 33.99, '13' => 37.50, '14' => 35.20, '15' => 33.54,
                        '16' => 32.09, '17' => 38.16, '18' => 36.33, '19' => 34.70, '20' => 33.23,
                    ],
                ],
            ],
            [
                'codigo' => 'HFOOT',
                'categoria' => 'tour',
                'nombre' => 'Cusco on foot',
                'duracion_horas' => 4.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 110.96, '2' => 76.31, '3' => 64.76, '4' => 58.99, '5' => 55.52,
                        '6' => 53.21, '7' => 51.56, '8' => 50.33, '9' => 56.59, '10' => 55.09,
                        '11' => 53.87, '12' => 52.86, '13' => 52.33, '14' => 51.56, '15' => 50.90,
                        '16' => 50.33, '17' => 53.64, '18' => 52.97, '19' => 52.38, '20' => 51.84,
                    ],
                    'feriado' => [
                        '1' => 174.45, '2' => 107.30, '3' => 84.92, '4' => 73.72, '5' => 67.01,
                        '6' => 62.53, '7' => 59.33, '8' => 56.94, '9' => 69.52, '10' => 66.58,
                        '11' => 64.18, '12' => 62.17, '13' => 60.81, '14' => 59.33, '15' => 58.06,
                        '16' => 56.94, '17' => 63.60, '18' => 62.29, '19' => 61.13, '20' => 60.08,
                    ],
                    'nino' => [
                        '1' => 94.30, '2' => 59.65, '3' => 48.10, '4' => 42.32, '5' => 38.86,
                        '6' => 36.55, '7' => 34.90, '8' => 33.66, '9' => 39.92, '10' => 38.43,
                        '11' => 37.21, '12' => 36.19, '13' => 35.66, '14' => 34.90, '15' => 34.24,
                        '16' => 33.66, '17' => 36.97, '18' => 36.31, '19' => 35.71, '20' => 35.18,
                    ],
                    'feriado_nino' => [
                        '1' => 159.30, '2' => 92.15, '3' => 69.76, '4' => 58.57, '5' => 51.86,
                        '6' => 47.38, '7' => 44.18, '8' => 41.78, '9' => 54.36, '10' => 51.43,
                        '11' => 49.02, '12' => 47.02, '13' => 45.66, '14' => 44.18, '15' => 42.90,
                        '16' => 41.78, '17' => 48.44, '18' => 47.14, '19' => 45.98, '20' => 44.93,
                    ],
                ],
            ],
            [
                'codigo' => 'XP-TIPON-ANDAHUAY',
                'categoria' => 'tour',
                'nombre' => 'TIPON + ANDAHUAYLILLAS',
                'duracion_horas' => 5.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 220.65, '2' => 118.35, '3' => 94.25, '4' => 74.70, '5' => 62.97,
                        '6' => 55.15, '7' => 49.57, '8' => 47.88, '9' => 54.90, '10' => 51.01,
                        '11' => 47.84, '12' => 45.19, '13' => 52.15, '14' => 49.57, '15' => 48.67,
                        '16' => 47.88, '17' => 51.60, '18' => 49.62, '19' => 47.85, '20' => 46.26,
                    ],
                    'feriado' => [
                        '1' => 315.65, '2' => 165.85, '3' => 125.92, '4' => 98.45, '5' => 81.97,
                        '6' => 70.99, '7' => 63.14, '8' => 71.63, '9' => 76.01, '10' => 70.01,
                        '11' => 65.11, '12' => 61.02, '13' => 66.76, '14' => 63.14, '15' => 61.33,
                        '16' => 59.75, '17' => 68.36, '18' => 65.45, '19' => 62.85, '20' => 60.51,
                    ],
                    'nino' => [
                        '1' => 214.45, '2' => 112.30, '3' => 88.25, '4' => 68.72, '5' => 57.01,
                        '6' => 49.20, '7' => 43.62, '8' => 41.93, '9' => 48.96, '10' => 45.08,
                        '11' => 41.90, '12' => 39.25, '13' => 46.19, '14' => 43.62, '15' => 42.72,
                        '16' => 41.93, '17' => 45.65, '18' => 43.68, '19' => 41.91, '20' => 40.33,
                    ],
                    'feriado_nino' => [
                        '1' => 309.45, '2' => 159.80, '3' => 119.91, '4' => 92.47, '5' => 76.01,
                        '6' => 65.03, '7' => 57.19, '8' => 53.81, '9' => 70.07, '10' => 64.08,
                        '11' => 59.17, '12' => 55.09, '13' => 60.81, '14' => 57.19, '15' => 55.39,
                        '16' => 53.81, '17' => 62.42, '18' => 59.51, '19' => 56.91, '20' => 54.58,
                    ],
                ],
            ],
            [
                'codigo' => 'XP-SOUTHEAST-CUSCO',
                'categoria' => 'tour',
                'nombre' => 'Southeast Cusco',
                'duracion_horas' => 6.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 315.81, '2' => 168.51, '3' => 136.07, '4' => 107.36, '5' => 90.13,
                        '6' => 78.64, '7' => 73.29, '8' => 74.91, '9' => 79.49, '10' => 73.67,
                        '11' => 68.90, '12' => 64.92, '13' => 77.30, '14' => 73.29, '15' => 74.15,
                        '16' => 74.91, '17' => 77.34, '18' => 74.22, '19' => 71.43, '20' => 68.92,
                    ],
                    'feriado' => [
                        '1' => 410.81, '2' => 216.01, '3' => 167.74, '4' => 131.11, '5' => 109.13,
                        '6' => 94.47, '7' => 86.86, '8' => 86.78, '9' => 100.61, '10' => 92.67,
                        '11' => 86.17, '12' => 80.76, '13' => 91.91, '14' => 86.86, '15' => 86.82,
                        '16' => 86.78, '17' => 94.10, '18' => 90.05, '19' => 86.43, '20' => 83.17,
                    ],
                    'nino' => [
                        '1' => 308.99, '2' => 161.84, '3' => 129.46, '4' => 100.77, '5' => 83.55,
                        '6' => 72.07, '7' => 66.73, '8' => 68.35, '9' => 72.95, '10' => 67.12,
                        '11' => 62.35, '12' => 58.38, '13' => 70.74, '14' => 66.73, '15' => 67.60,
                        '16' => 68.35, '17' => 70.79, '18' => 67.67, '19' => 64.88, '20' => 62.37,
                    ],
                    'feriado_nino' => [
                        '1' => 403.99, '2' => 209.34, '3' => 161.12, '4' => 124.52, '5' => 102.55,
                        '6' => 87.91, '7' => 80.31, '8' => 80.23, '9' => 94.06, '10' => 86.12,
                        '11' => 79.63, '12' => 74.22, '13' => 85.35, '14' => 80.31, '15' => 80.26,
                        '16' => 80.23, '17' => 87.55, '18' => 83.50, '19' => 79.88, '20' => 76.62,
                    ],
                ],
            ],
            [
                'codigo' => 'CUSPUC',
                'categoria' => 'tour',
                'nombre' => 'The Scenic Route - Conventional',
                'duracion_horas' => 10.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 576.75, '2' => 298.45, '3' => 242.35, '4' => 186.80, '5' => 153.47,
                        '6' => 131.25, '7' => 115.37, '8' => 112.22, '9' => 123.32, '10' => 113.01,
                        '11' => 104.56, '12' => 97.53, '13' => 122.70, '14' => 115.37, '15' => 113.69,
                        '16' => 112.22, '17' => 118.10, '18' => 112.66, '19' => 107.79, '20' => 103.41,
                    ],
                    'feriado' => [
                        '1' => 773.75, '2' => 399.45, '3' => 311.35, '4' => 239.80, '5' => 196.87,
                        '6' => 168.25, '7' => 147.80, '8' => 141.22, '9' => 170.99, '10' => 156.41,
                        '11' => 144.47, '12' => 89.53, '13' => 84.93, '14' => 80.66, '15' => 76.96,
                        '16' => 73.72, '17' => 93.45, '18' => 89.66, '19' => 86.26, '20' => 83.21,
                    ],
                    'nino' => [
                        '1' => 574.02, '2' => 295.87, '3' => 239.82, '4' => 184.30, '5' => 150.98,
                        '6' => 128.77, '7' => 112.91, '8' => 109.76, '9' => 120.87, '10' => 110.55,
                        '11' => 102.11, '12' => 95.08, '13' => 120.23, '14' => 112.91, '15' => 111.23,
                        '16' => 109.76, '17' => 115.64, '18' => 110.20, '19' => 105.33, '20' => 100.95,
                    ],
                    'feriado_nino' => [
                        '1' => 776.02, '2' => 396.87, '3' => 307.15, '4' => 234.80, '5' => 191.38,
                        '6' => 162.44, '7' => 141.76, '8' => 135.01, '9' => 164.64, '10' => 149.95,
                        '11' => 87.93, '12' => 82.08, '13' => 77.46, '14' => 73.19, '15' => 69.49,
                        '16' => 66.26, '17' => 85.99, '18' => 82.20, '19' => 78.81, '20' => 75.75,
                    ],
                ],
            ],
            [
                'codigo' => 'XP-SCENIC-HIGHLAND',
                'categoria' => 'tour',
                'nombre' => 'The Scenic Route - Highland',
                'duracion_horas' => 10.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 617.05, '2' => 318.75, '3' => 259.32, '4' => 199.60, '5' => 163.77,
                        '6' => 139.88, '7' => 122.82, '8' => 115.02, '9' => 125.85, '10' => 115.31,
                        '11' => 106.68, '12' => 99.50, '13' => 130.69, '14' => 122.82, '15' => 118.66,
                        '16' => 115.02, '17' => 120.75, '18' => 115.18, '19' => 110.20, '20' => 105.71,
                    ],
                    'feriado' => [
                        '1' => 809.05, '2' => 414.75, '3' => 323.32, '4' => 247.60, '5' => 202.17,
                        '6' => 171.88, '7' => 150.25, '8' => 139.02, '9' => 168.52, '10' => 153.71,
                        '11' => 141.59, '12' => 131.50, '13' => 160.23, '14' => 150.25, '15' => 144.26,
                        '16' => 139.02, '17' => 154.64, '18' => 147.18, '19' => 140.51, '20' => 134.51,
                    ],
                    'nino' => [
                        '1' => 616.75, '2' => 318.60, '3' => 259.22, '4' => 199.52, '5' => 163.71,
                        '6' => 139.83, '7' => 122.78, '8' => 114.99, '9' => 125.82, '10' => 115.28,
                        '11' => 106.66, '12' => 99.47, '13' => 130.65, '14' => 122.78, '15' => 118.62,
                        '16' => 114.99, '17' => 120.72, '18' => 115.15, '19' => 110.16, '20' => 105.68,
                    ],
                    'feriado_nino' => [
                        '1' => 808.75, '2' => 414.60, '3' => 323.22, '4' => 247.52, '5' => 202.11,
                        '6' => 171.83, '7' => 150.21, '8' => 138.99, '9' => 168.48, '10' => 153.68,
                        '11' => 141.57, '12' => 131.47, '13' => 160.19, '14' => 150.21, '15' => 144.22,
                        '16' => 138.99, '17' => 154.60, '18' => 147.15, '19' => 140.48, '20' => 134.48,
                    ],
                ],
            ],
            [
                'codigo' => 'XP-SCENIC-TITILAKA',
                'categoria' => 'tour',
                'nombre' => 'The Scenic Route - Conventional ending in Titilaka',
                'duracion_horas' => 10.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 666.75, '2' => 343.45, '3' => 275.68, '4' => 211.80, '5' => 173.47,
                        '6' => 147.91, '7' => 129.66, '8' => 127.22, '9' => 136.66, '10' => 125.01,
                        '11' => 115.47, '12' => 107.53, '13' => 138.08, '14' => 129.66, '15' => 128.36,
                        '16' => 127.22, '17' => 132.22, '18' => 125.99, '19' => 120.42, '20' => 115.41,
                    ],
                    'nino' => [
                        '1' => 664.02, '2' => 340.87, '3' => 273.15, '4' => 209.30, '5' => 170.98,
                        '6' => 145.44, '7' => 127.19, '8' => 124.76, '9' => 134.20, '10' => 122.55,
                        '11' => 113.02, '12' => 105.08, '13' => 135.61, '14' => 127.19, '15' => 125.89,
                        '16' => 124.76, '17' => 129.76, '18' => 123.53, '19' => 117.96, '20' => 112.95,
                    ],
                ],
            ],
            [
                'codigo' => 'XP-CUSCO-UNSEEN',
                'categoria' => 'tour',
                'nombre' => 'Cusco Unseen',
                'duracion_horas' => 4.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 56.87, '2' => 42.90, '3' => 38.25, '4' => 35.92, '5' => 34.52,
                        '6' => 33.59, '7' => 32.93, '8' => 32.43, '9' => 32.04, '10' => 31.73,
                        '11' => 31.48, '12' => 31.26,
                    ],
                    'nino' => [
                        '1' => 56.87, '2' => 42.90, '3' => 38.25, '4' => 35.92, '5' => 34.52,
                        '6' => 33.59, '7' => 32.93, '8' => 32.43, '9' => 32.04, '10' => 31.73,
                        '11' => 31.48, '12' => 31.26,
                    ],
                ],
            ],
            [
                'codigo' => 'XP-BIRDWATCHING',
                'categoria' => 'tour',
                'nombre' => 'BIRD WATCHING AT HUACARPAY  SOLO TRANSPORTE NO INCLUYE GUIA',
                'duracion_horas' => 6.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 115.20, '2' => 60.40, '3' => 52.13, '4' => 40.50, '5' => 33.52,
                        '6' => 28.87, '7' => 25.54, '8' => 25.55, '9' => 23.33, '10' => 21.56,
                        '11' => 20.11, '12' => 18.90, '13' => 27.08, '14' => 25.54, '15' => 25.55,
                        '16' => 25.55, '17' => 24.38, '18' => 23.33, '19' => 22.40, '20' => 21.56,
                    ],
                ],
            ],
            // ==========================================
            // 5. MACHU PICCHU & CAMINO INCA (FULL ESCALAS ADULTO / NIÑO / FERIADO)
            // ==========================================
            [
                'codigo' => 'MP-2D-SANC-NOGUIA',
                'categoria' => 'tour',
                'nombre' => 'Two Days at the Ruins (Paxs Sanctuary Lodge, Guía no pernocta)',
                'duracion_horas' => 24.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 363.41, '2' => 276.09, '3' => 246.99, '4' => 232.43, '5' => 223.70,
                        '6' => 217.88, '7' => 213.72, '8' => 219.31, '9' => 227.58, '10' => 223.70,
                        '11' => 220.53, '12' => 217.88, '13' => 215.64, '14' => 213.72, '15' => 212.06,
                        '16' => 210.61, '17' => 219.59, '18' => 217.88, '19' => 216.35, '20' => 214.97,
                    ],
                    'feriado' => [
                        '1' => 540.27, '2' => 655.82, '3' => 325.24, '4' => 298.37, '5' => 282.24,
                        '6' => 271.49, '7' => 263.81, '8' => 268.09, '9' => 289.41, '10' => 282.24,
                        '11' => 276.38, '12' => 271.49, '13' => 267.35, '14' => 263.81, '15' => 260.74,
                        '16' => 258.05, '17' => 273.84, '18' => 271.49, '19' => 268.66, '20' => 266.11,
                    ],
                    'nino' => [
                        '1' => 277.99, '2' => 430.68, '3' => 161.57, '4' => 147.02, '5' => 138.28,
                        '6' => 132.46, '7' => 128.31, '8' => 133.89, '9' => 142.17, '10' => 138.28,
                        '11' => 135.11, '12' => 132.46, '13' => 130.22, '14' => 128.31, '15' => 126.64,
                        '16' => 125.19, '17' => 134.18, '18' => 132.46, '19' => 130.93, '20' => 129.55,
                    ],
                    'feriado_nino' => [
                        '1' => 432.71, '2' => 545.89, '3' => 222.08, '4' => 195.76, '5' => 179.96,
                        '6' => 169.43, '7' => 161.91, '8' => 166.10, '9' => 186.98, '10' => 179.96,
                        '11' => 174.22, '12' => 169.43, '13' => 165.38, '14' => 161.91, '15' => 158.90,
                        '16' => 156.27, '17' => 171.73, '18' => 169.43, '19' => 166.66, '20' => 164.20,
                    ],
                ],
            ],
            [
                'codigo' => 'MP-2D-AC-NOGUIA',
                'categoria' => 'tour',
                'nombre' => 'Two Days at the Ruins (Paxs Aguas Calientes, Guía no pernocta)',
                'duracion_horas' => 24.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 387.41, '2' => 300.09, '3' => 270.99, '4' => 256.43, '5' => 247.70,
                        '6' => 241.88, '7' => 237.72, '8' => 243.31, '9' => 251.58, '10' => 247.70,
                        '11' => 244.53, '12' => 241.88, '13' => 239.64, '14' => 237.72, '15' => 236.06,
                        '16' => 234.61, '17' => 243.59, '18' => 241.88, '19' => 240.35, '20' => 238.97,
                    ],
                    'feriado' => [
                        '1' => 567.95, '2' => 683.50, '3' => 352.93, '4' => 326.05, '5' => 309.92,
                        '6' => 299.17, '7' => 291.49, '8' => 295.77, '9' => 317.09, '10' => 309.92,
                        '11' => 304.06, '12' => 299.17, '13' => 295.04, '14' => 291.49, '15' => 288.42,
                        '16' => 285.73, '17' => 301.52, '18' => 299.17, '19' => 296.34, '20' => 293.80,
                    ],
                    'nino' => [
                        '1' => 292.99, '2' => 445.68, '3' => 176.57, '4' => 162.02, '5' => 153.28,
                        '6' => 147.46, '7' => 143.31, '8' => 148.89, '9' => 157.17, '10' => 153.28,
                        '11' => 150.11, '12' => 147.46, '13' => 145.22, '14' => 143.31, '15' => 141.64,
                        '16' => 140.19, '17' => 149.18, '18' => 147.46, '19' => 145.93, '20' => 144.55,
                    ],
                    'feriado_nino' => [
                        '1' => 449.65, '2' => 562.84, '3' => 239.03, '4' => 212.70, '5' => 196.91,
                        '6' => 186.38, '7' => 178.85, '8' => 183.05, '9' => 203.93, '10' => 196.91,
                        '11' => 191.16, '12' => 186.38, '13' => 182.33, '14' => 178.85, '15' => 175.84,
                        '16' => 173.21, '17' => 188.68, '18' => 186.38, '19' => 183.60, '20' => 181.11,
                    ],
                ],
            ],
            [
                'codigo' => 'MP-2D-SANC-GUIA',
                'categoria' => 'tour',
                'nombre' => 'Two Days at the Ruins (Paxs Sanctuary Lodge, Guía pernocta)',
                'duracion_horas' => 24.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 514.05, '2' => 350.20, '3' => 297.20, '4' => 270.09, '5' => 253.83,
                        '6' => 242.99, '7' => 235.24, '8' => 243.84, '9' => 261.06, '10' => 253.83,
                        '11' => 247.92, '12' => 242.99, '13' => 238.82, '14' => 235.24, '15' => 232.15,
                        '16' => 229.43, '17' => 246.18, '18' => 242.99, '19' => 240.13, '20' => 237.57,
                    ],
                    'feriado' => [
                        '1' => 714.01, '2' => 465.87, '3' => 383.16, '4' => 341.80, '5' => 316.99,
                        '6' => 300.45, '7' => 288.63, '8' => 296.39, '9' => 328.02, '10' => 316.99,
                        '11' => 307.97, '12' => 300.45, '13' => 294.08, '14' => 288.63, '15' => 283.90,
                        '16' => 279.77, '17' => 304.91, '18' => 300.45, '19' => 296.09, '20' => 292.18,
                    ],
                    'nino' => [
                        '1' => 428.63, '2' => 265.99, '3' => 211.78, '4' => 184.68, '5' => 168.41,
                        '6' => 157.57, '7' => 149.83, '8' => 158.43, '9' => 175.64, '10' => 168.41,
                        '11' => 162.50, '12' => 157.57, '13' => 153.40, '14' => 149.83, '15' => 146.73,
                        '16' => 144.02, '17' => 160.76, '18' => 157.57, '19' => 154.72, '20' => 152.15,
                    ],
                    'feriado_nino' => [
                        '1' => 602.90, '2' => 359.83, '3' => 278.81, '4' => 238.30, '5' => 214.00,
                        '6' => 197.79, '7' => 186.22, '8' => 193.82, '9' => 224.80, '10' => 214.00,
                        '11' => 205.16, '12' => 197.79, '13' => 191.56, '14' => 186.22, '15' => 181.59,
                        '16' => 177.54, '17' => 202.16, '18' => 197.79, '19' => 193.53, '20' => 189.69,
                    ],
                ],
            ],
            [
                'codigo' => 'MP-2D-AC-GUIA',
                'categoria' => 'tour',
                'nombre' => 'Two Days at the Ruins (Paxs Aguas Calientes, Guía pernocta)',
                'duracion_horas' => 24.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 538.05, '2' => 375.41, '3' => 321.20, '4' => 294.09, '5' => 277.83,
                        '6' => 266.99, '7' => 259.24, '8' => 267.84, '9' => 285.06, '10' => 277.83,
                        '11' => 271.92, '12' => 266.99, '13' => 262.82, '14' => 259.24, '15' => 256.15,
                        '16' => 253.43, '17' => 270.18, '18' => 266.99, '19' => 264.13, '20' => 261.57,
                    ],
                    'feriado' => [
                        '1' => 741.69, '2' => 493.55, '3' => 410.84, '4' => 369.48, '5' => 344.67,
                        '6' => 328.13, '7' => 316.31, '8' => 324.07, '9' => 355.70, '10' => 344.67,
                        '11' => 335.65, '12' => 328.13, '13' => 321.77, '14' => 316.31, '15' => 311.59,
                        '16' => 307.45, '17' => 332.59, '18' => 328.13, '19' => 323.78, '20' => 319.86,
                    ],
                    'nino' => [
                        '1' => 443.63, '2' => 280.99, '3' => 226.78, '4' => 199.68, '5' => 183.41,
                        '6' => 172.57, '7' => 164.83, '8' => 173.43, '9' => 190.64, '10' => 183.41,
                        '11' => 177.50, '12' => 172.57, '13' => 168.40, '14' => 164.83, '15' => 161.73,
                        '16' => 159.02, '17' => 175.76, '18' => 172.57, '19' => 169.72, '20' => 167.15,
                    ],
                    'feriado_nino' => [
                        '1' => 619.84, '2' => 376.78, '3' => 295.76, '4' => 255.25, '5' => 230.94,
                        '6' => 214.74, '7' => 203.17, '8' => 210.77, '9' => 241.75, '10' => 230.94,
                        '11' => 222.11, '12' => 214.74, '13' => 208.51, '14' => 203.17, '15' => 198.54,
                        '16' => 194.49, '17' => 219.11, '18' => 214.74, '19' => 210.48, '20' => 206.64,
                    ],
                ],
            ],
            [
                'codigo' => 'FMAPI',
                'categoria' => 'tour',
                'nombre' => 'A Day at the Ruins (Full Day Machu Picchu)',
                'duracion_horas' => 5.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 204.00, '2' => 140.85, '3' => 119.80, '4' => 109.28, '5' => 102.96,
                        '6' => 98.75, '7' => 95.75, '8' => 95.99, '9' => 105.63, '10' => 102.83,
                        '11' => 100.55, '12' => 98.64, '13' => 97.13, '14' => 95.75, '15' => 94.54,
                        '16' => 93.49, '17' => 99.91, '18' => 98.68, '19' => 97.58, '20' => 96.58,
                    ],
                    'feriado' => [
                        '1' => 356.40, '2' => 223.01, '3' => 178.55, '4' => 156.32, '5' => 142.98,
                        '6' => 134.09, '7' => 127.73, '8' => 125.85, '9' => 148.74, '10' => 142.83,
                        '11' => 137.99, '12' => 133.96, '13' => 130.67, '14' => 127.73, '15' => 125.19,
                        '16' => 122.97, '17' => 135.80, '18' => 134.00, '19' => 131.67, '20' => 129.56,
                    ],
                    'nino' => [
                        '1' => 168.66, '2' => 105.51, '3' => 84.46, '4' => 62.69, '5' => 67.62,
                        '6' => 63.41, '7' => 60.40, '8' => 60.65, '9' => 70.28, '10' => 67.49,
                        '11' => 65.21, '12' => 63.30, '13' => 61.79, '14' => 60.40, '15' => 59.20,
                        '16' => 58.15, '17' => 64.57, '18' => 63.34, '19' => 62.23, '20' => 61.24,
                    ],
                    'feriado_nino' => [
                        '1' => 309.18, '2' => 178.52, '3' => 134.97, '4' => 113.19, '5' => 100.12,
                        '6' => 91.41, '7' => 85.19, '8' => 83.35, '9' => 105.77, '10' => 99.98,
                        '11' => 95.24, '12' => 91.29, '13' => 88.06, '14' => 85.19, '15' => 82.70,
                        '16' => 80.52, '17' => 93.09, '18' => 91.33, '19' => 89.04, '20' => 86.98,
                    ],
                ],
            ],
            [
                'codigo' => 'KM104-MP2SA',
                'categoria' => 'tour',
                'nombre' => 'Camino Inca Km 104 2D/1N (Pernocte Sanctuary Lodge)',
                'duracion_horas' => 24.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 728.04, '2' => 510.97, '3' => 438.62, '4' => 402.44, '5' => 380.74,
                        '6' => 366.27, '7' => 373.77, '8' => 402.44, '9' => 390.38, '10' => 380.74,
                        '11' => 372.84, '12' => 366.27, '13' => 370.30, '14' => 364.85, '15' => 360.12,
                        '16' => 355.98, '17' => 370.52, '18' => 366.27, '19' => 362.46, '20' => 359.03,
                    ],
                    'feriado' => [
                        '1' => 960.82, '2' => 649.91, '3' => 546.27, '4' => 481.48, '5' => 463.36,
                        '6' => 442.64, '7' => 448.40, '8' => 494.45, '9' => 477.18, '10' => 463.36,
                        '11' => 452.06, '12' => 442.64, '13' => 445.74, '14' => 438.12, '15' => 431.51,
                        '16' => 425.73, '17' => 448.33, '18' => 442.64, '19' => 437.18, '20' => 432.27,
                    ],
                    'nino' => [
                        '1' => 601.58, '2' => 385.43, '3' => 313.38, '4' => 277.35, '5' => 255.74,
                        '6' => 241.33, '7' => 248.87, '8' => 277.35, '9' => 265.34, '10' => 255.74,
                        '11' => 247.88, '12' => 241.33, '13' => 245.39, '14' => 239.95, '15' => 235.24,
                        '16' => 231.12, '17' => 245.57, '18' => 241.33, '19' => 237.54, '20' => 234.12,
                    ],
                    'feriado_nino' => [
                        '1' => 798.30, '2' => 494.77, '3' => 393.60, '4' => 343.01, '5' => 312.66,
                        '6' => 292.42, '7' => 298.12, '8' => 343.01, '9' => 326.15, '10' => 312.66,
                        '11' => 301.62, '12' => 292.42, '13' => 295.49, '14' => 288.05, '15' => 281.59,
                        '16' => 275.95, '17' => 297.98, '18' => 292.42, '19' => 287.10, '20' => 282.31,
                    ],
                ],
            ],
            [
                'codigo' => 'KM104-MP2AG',
                'categoria' => 'tour',
                'nombre' => 'Camino Inca Km 104 2D/1N (Pernocte Aguas Calientes)',
                'duracion_horas' => 24.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 750.22, '2' => 534.07, '3' => 462.01, '4' => 425.99, '5' => 404.37,
                        '6' => 389.96, '7' => 397.51, '8' => 425.99, '9' => 413.98, '10' => 404.37,
                        '11' => 396.51, '12' => 389.96, '13' => 394.02, '14' => 388.59, '15' => 383.88,
                        '16' => 379.75, '17' => 394.20, '18' => 389.96, '19' => 386.17, '20' => 382.76,
                    ],
                    'feriado' => [
                        '1' => 842.41, '2' => 604.54, '3' => 525.26, '4' => 472.64, '5' => 461.83,
                        '6' => 445.97, '7' => 434.64, '8' => 485.61, '9' => 472.40, '10' => 461.83,
                        '11' => 453.18, '12' => 445.97, '13' => 439.87, '14' => 434.64, '15' => 430.11,
                        '16' => 426.15, '17' => 450.22, '18' => 445.97, '19' => 441.80, '20' => 438.04,
                    ],
                    'nino' => [
                        '1' => 616.58, '2' => 400.43, '3' => 328.38, '4' => 292.35, '5' => 270.74,
                        '6' => 256.33, '7' => 263.87, '8' => 292.35, '9' => 280.34, '10' => 270.86,
                        '11' => 262.88, '12' => 256.33, '13' => 260.39, '14' => 254.95, '15' => 250.24,
                        '16' => 246.12, '17' => 260.57, '18' => 256.33, '19' => 252.54, '20' => 249.12,
                    ],
                    'feriado_nino' => [
                        '1' => 815.24, '2' => 511.72, '3' => 410.55, '4' => 359.96, '5' => 329.61,
                        '6' => 309.37, '7' => 315.07, '8' => 359.96, '9' => 343.10, '10' => 329.61,
                        '11' => 318.57, '12' => 309.37, '13' => 312.44, '14' => 304.99, '15' => 298.54,
                        '16' => 292.89, '17' => 314.92, '18' => 309.37, '19' => 304.05, '20' => 299.25,
                    ],
                ],
            ],
            [
                'codigo' => 'GUIA-LOCAL-MAPI-1D',
                'categoria' => 'tour',
                'nombre' => 'Servicio de Guía Local en Machu Picchu (01 Día)',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 121.00,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Costo neto $121.00 | Venta sugerida $136.71 (inc. fee e impuestos).',
            ],
            [
                'codigo' => 'GUIA-LOCAL-MAPI-2D',
                'categoria' => 'tour',
                'nombre' => 'Servicio de Guía Local en Machu Picchu (02 Días)',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 242.00,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Costo neto $242.00 | Venta sugerida $273.41 (inc. fee e impuestos).',
            ],
            // ==========================================
            // 6. TREKS & CAMINATAS DE AVENTURA (MATRICES OFICIALES)
            // ==========================================
            [
                'codigo' => 'TREK-HUCHUY',
                'categoria' => 'trek',
                'nombre' => 'Patabamba to Huchuy Qosqo Trek',
                'duracion_horas' => 10.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 384.96, '2' => 202.81, '3' => 175.43, '4' => 139.24, '5' => 121.52,
                        '6' => 115.54, '7' => 101.99, '8' => 97.45, '9' => 102.81, '10' => 94.59,
                        '11' => 93.78, '12' => 87.69,
                    ],
                    'feriado' => [
                        '1' => 479.96, '2' => 250.31, '3' => 207.09, '4' => 162.99, '5' => 140.52,
                        '6' => 131.38, '7' => 115.56, '8' => 109.32, '9' => 123.92, '10' => 113.59,
                        '11' => 111.05, '12' => 103.52,
                    ],
                ],
            ],
            [
                'codigo' => 'TREK-WAYLLABAMBA',
                'categoria' => 'trek',
                'nombre' => 'Chinchero - Huayllabamba Trek',
                'duracion_horas' => 8.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 308.60, '2' => 161.45, '3' => 144.07, '4' => 111.62, '5' => 92.16,
                        '6' => 90.02, '7' => 79.20, '8' => 74.21, '9' => 78.11, '10' => 71.73,
                        '11' => 72.42, '12' => 67.58,
                    ],
                    'feriado' => [
                        '1' => 403.60, '2' => 208.95, '3' => 175.73, '4' => 135.38, '5' => 111.16,
                        '6' => 105.85, '7' => 92.77, '8' => 86.09, '9' => 99.22, '10' => 90.73,
                        '11' => 89.69, '12' => 83.41,
                    ],
                ],
            ],
            [
                'codigo' => 'TREK-MARAS-MORAY',
                'categoria' => 'trek',
                'nombre' => 'Maras & Moray Trek',
                'duracion_horas' => 7.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 289.66, '2' => 155.01, '3' => 140.13, '4' => 110.19, '5' => 92.22,
                        '6' => 91.08, '7' => 80.97, '8' => 75.90, '9' => 80.28, '10' => 74.29,
                        '11' => 75.30, '12' => 70.72,
                    ],
                    'feriado' => [
                        '1' => 384.66, '2' => 202.51, '3' => 171.79, '4' => 133.94, '5' => 111.22,
                        '6' => 106.91, '7' => 94.55, '8' => 87.77, '9' => 101.39, '10' => 93.29,
                        '11' => 92.57, '12' => 86.55,
                    ],
                ],
            ],
            [
                'codigo' => 'TREK-04',
                'categoria' => 'trek',
                'nombre' => 'Tipón Trek',
                'duracion_horas' => 8.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [],
                    'feriado' => [
                        '1' => 363.60, '2' => 188.95, '3' => 162.40, '4' => 125.38, '5' => 103.16,
                        '6' => 99.18, '7' => 87.06, '8' => 80.46, '9' => 94.22, '10' => 86.23,
                        '11' => 85.60, '12' => 79.66,
                    ],
                ],
            ],
            [
                'codigo' => 'TREK-PUMAMARCA',
                'categoria' => 'trek',
                'nombre' => 'Pumamarca Hike',
                'duracion_horas' => 6.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 291.63, '2' => 154.48, '3' => 138.76, '4' => 108.41, '5' => 90.19,
                        '6' => 88.88, '7' => 78.66, '8' => 74.12, '9' => 78.36, '10' => 72.26,
                    ],
                    'feriado' => [
                        '1' => 386.63, '2' => 201.98, '3' => 170.43, '4' => 132.16, '5' => 109.19,
                        '6' => 104.71, '7' => 92.23, '8' => 85.99, '9' => 99.47, '10' => 91.26,
                    ],
                ],
            ],
            [
                'codigo' => 'TREK-JOURNEY-ANDES',
                'categoria' => 'trek',
                'nombre' => 'Journey to the Heart of the Andes',
                'duracion_horas' => 8.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 291.63, '2' => 154.48, '3' => 138.76, '4' => 108.41, '5' => 90.19,
                        '6' => 88.88, '7' => 78.66, '8' => 74.12, '9' => 78.36, '10' => 72.26,
                    ],
                    'feriado' => [
                        '1' => 386.63, '2' => 201.98, '3' => 170.43, '4' => 132.16, '5' => 109.19,
                        '6' => 104.71, '7' => 92.23, '8' => 85.99, '9' => 99.47, '10' => 91.26,
                    ],
                ],
            ],
            [
                'codigo' => 'TREK-PATACANCHA-WILLOQ',
                'categoria' => 'trek',
                'nombre' => 'Patacancha o Willoq Trek',
                'duracion_horas' => 8.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 288.60, '2' => 151.45, '3' => 135.73, '4' => 105.38, '5' => 87.16,
                        '6' => 85.85, '7' => 75.63, '8' => 71.09, '9' => 75.33, '10' => 69.23,
                    ],
                    'feriado' => [
                        '1' => 383.60, '2' => 198.95, '3' => 167.40, '4' => 129.12, '5' => 106.16,
                        '6' => 101.68, '7' => 89.20, '8' => 82.96, '9' => 96.44, '10' => 88.23,
                    ],
                ],
            ],
            [
                'codigo' => 'PALCOYO',
                'categoria' => 'trek',
                'nombre' => 'FD Palcoyo (Montaña de Colores)',
                'duracion_horas' => 10.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 416.26, '2' => 228.46, '3' => 180.86, '4' => 165.81, '5' => 136.78,
                        '6' => 128.26, '7' => 112.89, '8' => 107.61, '9' => 108.51, '10' => 99.72,
                    ],
                    'feriado' => [
                        '1' => 511.26, '2' => 275.96, '3' => 212.53, '4' => 189.56, '5' => 155.78,
                        '6' => 144.09, '7' => 126.46, '8' => 119.49, '9' => 129.62, '10' => 118.72,
                    ],
                ],
            ],
            [
                'codigo' => 'HUMANTAY',
                'categoria' => 'trek',
                'nombre' => 'Hike to Humantay - Full Day',
                'duracion_horas' => 12.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 425.23, '2' => 221.43, '3' => 190.16, '4' => 147.03, '5' => 121.15,
                        '6' => 114.73, '7' => 100.86, '8' => 97.33, '9' => 99.03, '10' => 90.89,
                    ],
                    'feriado' => [
                        '1' => 560.23, '2' => 299.93, '3' => 249.83, '4' => 197.28, '5' => 165.75,
                        '6' => 155.56, '7' => 139.00, '8' => 133.46, '9' => 144.14, '10' => 133.69,
                    ],
                ],
            ],
            [
                'codigo' => 'TREK-REAL-SACRED-VALLEY',
                'categoria' => 'trek',
                'nombre' => 'The Real Sacred Valley',
                'duracion_horas' => 9.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 457.20, '2' => 261.40, '3' => 210.13, '4' => 169.75, '5' => 154.52,
                        '6' => 136.37, '7' => 127.54, '8' => 118.68, '9' => 125.55, '10' => 118.56,
                        '11' => 115.29, '12' => 112.57,
                    ],
                    'feriado' => [
                        '1' => 552.20, '2' => 308.90, '3' => 241.80, '4' => 193.50, '5' => 173.52,
                        '6' => 152.20, '7' => 141.11, '8' => 130.56, '9' => 146.66, '10' => 137.56,
                        '11' => 132.56, '12' => 128.40,
                    ],
                ],
            ],
            [
                'codigo' => 'TREK-ANCASMARCA',
                'categoria' => 'trek',
                'nombre' => 'Ancasmarca Trek',
                'duracion_horas' => 8.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 311.93, '2' => 164.78, '3' => 144.06, '4' => 112.46, '5' => 93.49,
                        '6' => 91.68, '7' => 81.10, '8' => 77.54, '9' => 81.44, '10' => 75.06,
                        '11' => 75.75, '12' => 70.91, '13' => 75.98, '14' => 71.82, '15' => 70.54,
                        '16' => 73.48, '17' => 75.78, '18' => 72.55, '19' => 69.66, '20' => 67.06,
                    ],
                ],
            ],
            [
                'codigo' => 'TREK-MAUKALLACTA',
                'categoria' => 'trek',
                'nombre' => 'FD Maukallacta',
                'duracion_horas' => 10.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 362.54, '2' => 200.39, '3' => 171.34, '4' => 133.06, '5' => 110.10,
                        '6' => 105.62, '7' => 93.14, '8' => 86.27, '9' => 89.27, '10' => 82.17,
                        '11' => 82.26, '12' => 76.93, '13' => 88.90, '14' => 83.85, '15' => 80.81,
                        '16' => 82.21, '17' => 84.04, '18' => 80.38, '19' => 77.11, '20' => 74.17,
                    ],
                    'feriado' => [
                        '1' => 457.54, '2' => 247.89, '3' => 203.00, '4' => 156.81, '5' => 129.10,
                        '6' => 121.45, '7' => 106.71, '8' => 98.15, '9' => 110.38, '10' => 101.17,
                        '11' => 99.54, '12' => 92.76, '13' => 103.51, '14' => 97.42, '15' => 93.48,
                        '16' => 94.09, '17' => 100.80, '18' => 96.21, '19' => 92.11, '20' => 88.42,
                    ],
                ],
            ],
            [
                'codigo' => 'TREK-13',
                'categoria' => 'trek',
                'nombre' => 'Journey to the Heart of the Andes (Ruta Alternativa)',
                'duracion_horas' => 8.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 210.00, '2' => 107.50, '3' => 81.67, '4' => 62.50, '5' => 51.00,
                        '6' => 43.33, '7' => 37.86, '8' => 36.88, '9' => 43.89, '10' => 40.00,
                    ],
                    'feriado' => [
                        '1' => 305.00, '2' => 155.00, '3' => 113.33, '4' => 86.25, '5' => 70.00,
                        '6' => 59.17, '7' => 51.43, '8' => 48.75, '9' => 65.00, '10' => 59.00,
                    ],
                ],
            ],
            [
                'codigo' => 'TREK-MIRADOR-CHONTA',
                'categoria' => 'trek',
                'nombre' => 'Full Day Mirador de Chonta',
                'duracion_horas' => 12.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 422.32, '2' => 234.52, '3' => 186.92, '4' => 171.87, '5' => 142.84,
                        '6' => 134.32, '7' => 118.95, '8' => 113.67, '9' => 114.57, '10' => 105.78,
                    ],
                    'feriado' => [
                        '1' => 517.32, '2' => 282.02, '3' => 218.59, '4' => 195.62, '5' => 161.84,
                        '6' => 150.15, '7' => 132.52, '8' => 125.55, '9' => 135.68, '10' => 124.78,
                    ],
                ],
            ],
            [
                'codigo' => 'TREK-PATAKANCHA-XP',
                'categoria' => 'trek',
                'nombre' => 'Patakancha Experience FD',
                'duracion_horas' => 9.0,
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 432.20, '2' => 223.40, '3' => 169.80, '4' => 138.00, '5' => 116.52,
                        '6' => 108.20, '7' => 102.26, '8' => 102.18, '9' => 108.78, '10' => 104.56,
                        '11' => 101.11, '12' => 98.23,
                    ],
                    'feriado' => [
                        '1' => 527.20, '2' => 270.90, '3' => 201.47, '4' => 161.75, '5' => 135.52,
                        '6' => 124.03, '7' => 115.83, '8' => 114.05, '9' => 129.89, '10' => 123.56,
                        '11' => 118.38, '12' => 114.07,
                    ],
                ],
            ],
            // ==========================================
            // 7. COMPONENTES Y LOGÍSTICA OPERATIVA
            // ==========================================
            [
                'codigo' => 'COMP-LAMAY-BASE',
                'categoria' => 'componente',
                'nombre' => 'TPT & Guía para Experiencias con Base Lamay (Chichería, Cuyes & Abejas / Community Day)',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 229.60, '2' => 119.95, '3' => 93.40, '4' => 72.62, '5' => 60.16,
                        '6' => 51.85, '7' => 45.91, '8' => 44.59, '9' => 51.33, '10' => 47.23,
                        '11' => 43.87, '12' => 41.08,
                    ],
                ],
            ],
            [
                'codigo' => 'COMP-LAMAY-PISAC-HIKE',
                'categoria' => 'componente',
                'nombre' => 'TPT & Guía Lamay To Pisac Mountain Hike con llamas, staff y picnic ParuParu',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 324.60, '2' => 167.45, '3' => 125.07, '4' => 96.38, '5' => 79.16,
                        '6' => 67.68, '7' => 59.49, '8' => 56.46, '9' => 72.44, '10' => 66.23,
                        '11' => 61.15, '12' => 56.91,
                    ],
                ],
            ],
            [
                'codigo' => 'COMP-LLAMA-PATH',
                'categoria' => 'componente',
                'nombre' => 'TPT & Guía para Llama Path Experience',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 244.60, '2' => 127.45, '3' => 100.07, '4' => 77.62, '5' => 64.16,
                        '6' => 55.18, '7' => 48.77, '8' => 46.46, '9' => 53.00, '10' => 48.73,
                        '11' => 45.24, '12' => 42.33,
                    ],
                    'feriado' => [
                        '1' => 200.20, '2' => 102.90, '3' => 77.13, '4' => 59.25, '5' => 48.52,
                        '6' => 41.37, '7' => 36.26, '8' => 33.05, '9' => 44.44, '10' => 40.56,
                        '11' => 37.38, '12' => 34.73,
                    ],
                ],
            ],
            [
                'codigo' => 'COMP-CUATRIMOTOS',
                'categoria' => 'componente',
                'nombre' => 'TPT a Disposición para Cuatrimotos Sol y Luna (Half Day & Full Day)',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 116.30, '2' => 58.80, '3' => 46.30, '4' => 35.05, '5' => 28.30,
                        '6' => 23.80, '7' => 20.59, '8' => 21.93, '9' => 19.63, '10' => 17.80,
                        '11' => 16.30, '12' => 15.05,
                    ],
                ],
            ],
            [
                'codigo' => 'COMP-CABALGATA-HUAYOCCARI',
                'categoria' => 'componente',
                'nombre' => 'TPT & Guía para Cabalgata con Huayoccari (Half Day & Full Day)',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 201.30, '2' => 101.30, '3' => 74.63, '4' => 56.30, '5' => 45.30,
                        '6' => 37.97, '7' => 32.73, '8' => 32.55, '9' => 39.63, '10' => 35.80,
                        '11' => 32.66, '12' => 30.05,
                    ],
                    'feriado' => [
                        '1' => 119.29, '2' => 64.72, '3' => 53.19, '4' => 42.43, '5' => 35.97,
                        '6' => 31.67, '7' => 28.59, '8' => 30.04, '9' => 27.83, '10' => 26.06,
                        '11' => 24.61, '12' => 23.41,
                    ],
                ],
            ],
            [
                'codigo' => 'COMP-INTIRAYMI-SUPERVIP',
                'categoria' => 'componente',
                'nombre' => 'Inti Raymi FD - Koricancha General y Sacsayhuamán Zona Inti Super VIP',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 991.90, '2' => 701.25, '3' => 612.70, '4' => 562.18, '5' => 531.86,
                        '6' => 511.65, '7' => 497.21, '8' => 489.51, '9' => 524.30, '10' => 512.93,
                        '11' => 503.63, '12' => 495.88, '13' => 504.65, '14' => 497.93, '15' => 493.44,
                        '16' => 489.51, '17' => 507.93, '18' => 502.52, '19' => 497.68, '20' => 493.33,
                    ],
                    'feriado' => [
                        '1' => 923.90, '2' => 644.75, '3' => 560.03, '4' => 511.43, '5' => 482.26,
                        '6' => 462.82, '7' => 448.93, '8' => 441.64, '9' => 474.19, '10' => 463.33,
                        '11' => 454.45, '12' => 447.04, '13' => 456.11, '14' => 449.64, '15' => 445.37,
                        '16' => 441.64, '17' => 458.87, '18' => 453.69, '19' => 449.05, '20' => 444.88,
                    ],
                ],
            ],
            [
                'codigo' => 'COMP-CARRO-MALETERO-VALLE',
                'categoria' => 'componente',
                'nombre' => 'Carro Maletero Privado Aeropuerto <-> Valle Sagrado (1 a 12 pax)',
                'descripcion' => 'Vehículo exclusivo para el equipaje con representante. Tarifa por grupo (US$ 140) prorrateada por pasajero.',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => ['normal' => $porGrupo(140.00, 12)],
                'desglose' => [
                    $parte('traslado', 'Transporte', $porGrupo(120.00, 12)),
                    $parte('representante', 'Representante', $porGrupo(20.00, 12)),
                ],
            ],
            [
                'codigo' => 'COMP-CARRO-MALETERO-CUSCO',
                'categoria' => 'componente',
                'nombre' => 'Carro Maletero Privado Aeropuerto <-> Hotel Cusco (1 a 12 pax)',
                'descripcion' => 'Vehículo exclusivo para el equipaje con representante. Tarifa por grupo (US$ 65) prorrateada por pasajero.',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => ['normal' => $porGrupo(65.00, 12)],
                'desglose' => [
                    $parte('traslado', 'Transporte', $porGrupo(50.00, 12)),
                    $parte('representante', 'Representante', $porGrupo(15.00, 12)),
                ],
            ],
            [
                'codigo' => 'COMP-AUTO-EXTRA-MINUTO',
                'categoria' => 'componente',
                'nombre' => 'Auto Privado para Servicios Adicionales de Último Minuto',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 69.36,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'COMP-FOTOGRAFIA-MEDIODIA',
                'categoria' => 'componente',
                'nombre' => 'Servicio de Fotografía Profesional 1/2 Día en Cusco',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 128.32,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'COMP-ASISTENCIA-CUSCO-REG',
                'categoria' => 'componente',
                'nombre' => 'Servicio de Asistencia en Cusco (Hotel - Inicio Experiencia - Hotel)',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 28.00,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'COMP-ASISTENCIA-CUSCO-FER',
                'categoria' => 'componente',
                'nombre' => 'Servicio de Asistencia en Cusco Feriado (Hotel - Inicio Experiencia - Hotel)',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 42.00,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'COMP-BASTONES',
                'categoria' => 'componente',
                'nombre' => 'Palos de Caminata / Bastones de Trekking (Par)',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 5.00,
                'precio_nino' => 5.00,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'COMP-SNACK-PAX',
                'categoria' => 'componente',
                'nombre' => 'Snack Gourmet Pasajero + Aguas',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 5.30,
                'precio_nino' => 5.30,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'COMP-SNACK-GUIA',
                'categoria' => 'componente',
                'nombre' => 'Snack Guía de Ruta',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 4.30,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            // ==========================================
            // 8. CEREMONIAS MÍSTICAS DE PAGO A LA TIERRA
            // ==========================================
            [
                'codigo' => 'CEREM-CUSCO',
                'categoria' => 'tour',
                'nombre' => 'Ceremonia de Pago a la Tierra en Cusco (Chamán + Ofrenda + Guía Traductor)',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 398.23, // Venta final $450
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Venta final oficial $450.00 USD por grupo.',
            ],
            [
                'codigo' => 'CEREM-CUSCO-MUSICA',
                'categoria' => 'tour',
                'nombre' => 'Ceremonia de Pago a la Tierra en Cusco + Acompañamiento de Música Andina',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 703.54, // Venta final $795
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Venta final oficial $795.00 USD por grupo.',
            ],
            [
                'codigo' => 'CEREM-VALLE',
                'categoria' => 'tour',
                'nombre' => 'Ceremonia de Pago a la Tierra en Valle Sagrado (Chamán + Ofrenda + Guía Traductor)',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 504.42, // Venta final $570
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Venta final oficial $570.00 USD por grupo.',
            ],
            [
                'codigo' => 'CEREM-VALLE-MUSICA',
                'categoria' => 'tour',
                'nombre' => 'Ceremonia de Pago a la Tierra en Valle Sagrado + Acompañamiento de Música Andina',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 792.03, // Venta final $895
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Venta final oficial $895.00 USD por grupo.',
            ],
            [
                'codigo' => 'CEREM-MAPI-SANCTUARY',
                'categoria' => 'tour',
                'nombre' => 'Pago a la Tierra en Terraza Belmond Sanctuary Lodge (Machu Picchu con Visita)',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 1747.78, // Venta final $1,975 inc IGV
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Incluye chamán con viáticos, tickets tren/bus/ingresos chamán y guía traductor. Venta $1,975 USD.',
            ],
            [
                'codigo' => 'CEREM-MAPI-HOTEL',
                'categoria' => 'tour',
                'nombre' => 'Pago a la Tierra en Terraza Hotel Aguas Calientes / Mariposario (Machu Picchu con Visita)',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 1723.89, // Venta final $1,948 inc IGV
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Venta final oficial $1,948.00 USD.',
            ],
            [
                'codigo' => 'CEREM-MAPI-SINVISITA-SANC',
                'categoria' => 'tour',
                'nombre' => 'Pago a la Tierra en Terraza Sanctuary Lodge (Machu Picchu Sin Visita)',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 1506.19, // Venta final $1,702
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Venta final oficial $1,702.00 USD.',
            ],
            [
                'codigo' => 'CEREM-MAPI-SINVISITA-HOTEL',
                'categoria' => 'tour',
                'nombre' => 'Pago a la Tierra en Terraza Hotel Aguas Calientes / Mariposario (Machu Picchu Sin Visita)',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 1482.30, // Venta final $1,675
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Venta final oficial $1,675.00 USD.',
            ],
            // ==========================================
            // 9. ALIMENTACIÓN, RESTAURANTES, PICNICS Y BOX LUNCH
            // ==========================================
            [
                'codigo' => 'ALM-GUIA-CUCHARA-PALO',
                'categoria' => 'alimentacion',
                'nombre' => 'Almuerzo Guía en Cuchara de Palo (cuando paxs tienen almuerzo)',
                'tipo_calculo' => 'por_grupo',
                'costo_grupo' => 30.00,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Costo neto $30.00 | Total con fee $33.89 USD.',
            ],
            [
                'codigo' => 'ALM-CUCHARA-PALO',
                'categoria' => 'alimentacion',
                'nombre' => 'Almuerzo Gourmet en Cuchara de Palo (Pisac)',
                'descripcion' => 'Menú a la carta gourmet con insumos orgánicos del Valle Sagrado. Incluye bebida (cerveza artesanal o gaseosa).',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 35.00,
                'precio_nino' => 25.00,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Costo neto $35.00 | Total con fee $39.54 USD.',
            ],
            [
                'codigo' => 'ALM-URUBAMBA',
                'categoria' => 'alimentacion',
                'nombre' => 'Almuerzo Restaurante Local en Urubamba',
                'descripcion' => 'Almuerzo en restaurante campestre en Urubamba, incluye una cerveza artesanal o gaseosa.',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 35.00,
                'precio_nino' => 25.00,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Costo neto $35.00 | Total con fee $39.54 USD.',
            ],
            [
                'codigo' => 'ALM-CHUNCHO',
                'categoria' => 'alimentacion',
                'nombre' => 'Almuerzo Tradicional en Chuncho (Ollantaytambo)',
                'descripcion' => 'Gastronomía autóctona y destilados andinos en la plaza de Ollantaytambo.',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 35.00,
                'precio_nino' => 25.00,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'notas' => 'Costo neto $35.00 | Total con fee $39.54 USD.',
            ],
            [
                'codigo' => 'PICNIC-NORMAL',
                'categoria' => 'alimentacion',
                'nombre' => 'Picnic Lunch Standard al aire libre (Valle Sagrado / Tipón)',
                'descripcion' => 'Mesa rústica campestre con mantas andinas, ensaladas frescas, quiches, quesos y postre.',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 168.00, '2' => 90.00, '3' => 77.33, '4' => 71.00, '5' => 67.20,
                        '6' => 64.67,  '7' => 62.86, '8' => 61.50, '9' => 60.44, '10' => 59.60,
                        '11' => 58.91, '12' => 58.33,
                    ],
                ],
                'notas' => 'Costo unitario pax $52.00 + alimentación guía y chofer prorrateada.',
            ],
            [
                'codigo' => 'PICNIC-LUJO',
                'categoria' => 'alimentacion',
                'nombre' => 'Picnic Lunch de Lujo con Montaje Privado & Vino',
                'descripcion' => 'Montaje exclusivo con carpas ligeras o parasoles, vajilla fina, maridaje de vino Trapiche Malbec y servicio personalizado.',
                'tipo_calculo' => 'escala_pax',
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
                'precios_escalonados' => [
                    'normal' => [
                        '1' => 228.00, '2' => 119.00, '3' => 101.00, '4' => 92.00, '5' => 86.60,
                        '6' => 83.00,  '7' => 80.43,  '8' => 78.50,  '9' => 77.00, '10' => 75.80,
                        '11' => 74.82, '12' => 74.00,
                    ],
                ],
                'notas' => 'Costo unitario pax $65.00 + montaje de lujo + alimentación guía y chofer.',
            ],
            [
                'codigo' => 'BOX-LUNCH-SIMPLE',
                'categoria' => 'alimentacion',
                'nombre' => 'Box Lunch Simple para Caminatas / Rutas',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 25.00,
                'precio_nino' => 25.00,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
            [
                'codigo' => 'BOX-LUNCH-SUPER',
                'categoria' => 'alimentacion',
                'nombre' => 'Box Lunch Super Gourmet (Cicciolina o similar)',
                'tipo_calculo' => 'por_persona',
                'precio_adulto' => 30.00,
                'precio_nino' => 30.00,
                'comision_pct' => 11.00,
                'aplica_igv_comision' => true,
                'moneda' => 'USD',
            ],
        ];

        foreach ($servicios as $s) {
            TarifarioServicio::create($s);
        }
    }
}
