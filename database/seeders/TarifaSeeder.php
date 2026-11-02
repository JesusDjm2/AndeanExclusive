<?php

namespace Database\Seeders;

use App\Models\Habitacion;
use App\Models\Hotel;
use App\Models\Proveedor;
use App\Models\Tarifa;
use Illuminate\Database\Seeder;

class TarifaSeeder extends Seeder
{
    /**
     * Tarifario oficial de hoteles y proveedores para temporada 2026/2027.
     * Precios reales de mercado de lujo en USD y PEN.
     */
    public function run(): void
    {
        Tarifa::query()->delete();

        $anioActual = now()->year;
        $desde = sprintf('%d-01-01', $anioActual);
        $hasta = sprintf('%d-12-31', $anioActual + 1);

        // 1. Tarifas por habitación de hotel
        $tarifasHabitaciones = [
            ['hotel' => 'Belmond Hotel Monasterio', 'hab' => 'Deluxe King con Oxígeno Enriquecido', 'precio' => 650.00, 'notas' => 'Incluye desayuno buffet colonial y sistema de oxígeno 24h.'],
            ['hotel' => 'Belmond Hotel Monasterio', 'hab' => 'Junior Suite Colonial', 'precio' => 950.00, 'notas' => 'Suite de época con balcón privado hacia el patio de los laureles.'],
            ['hotel' => 'Belmond Hotel Monasterio', 'hab' => 'Suite Presidencial Monasterio', 'precio' => 1450.00, 'notas' => 'Máximo nivel de lujo virreinal y servicio de mayordomo privado.'],

            ['hotel' => 'Belmond Sanctuary Lodge', 'hab' => 'Mountain View Deluxe Room', 'precio' => 1650.00, 'notas' => 'Ubicación contigua a la Llaqta. Incluye todas las comidas y bebidas premium.'],
            ['hotel' => 'Belmond Sanctuary Lodge', 'hab' => 'Standard Patio King Room', 'precio' => 1350.00, 'notas' => 'Desayuno a la carta y acceso anticipado al santuario inca.'],

            ['hotel' => 'Tambo del Inka, a Luxury Collection Resort & Spa', 'hab' => 'Deluxe King con Balcón al Río Vilcanota', 'precio' => 480.00, 'notas' => 'Acceso a circuito de hidroterapia Kallpa Spa y andén privado de tren.'],
            ['hotel' => 'Tambo del Inka, a Luxury Collection Resort & Spa', 'hab' => 'Junior Suite Valle Sagrado', 'precio' => 720.00, 'notas' => 'Sala de estar independiente con chimenea a leña.'],

            ['hotel' => 'JW Marriott El Convento Cusco', 'hab' => 'Classic King con Oxigenación Controlada', 'precio' => 420.00, 'notas' => 'Incluye tour arqueológico privado por el convento y desayuno en Qespi.'],
            ['hotel' => 'JW Marriott El Convento Cusco', 'hab' => 'Imperial Suite Virreinal', 'precio' => 880.00, 'notas' => 'Muros de piedra inca pulida y sala de estar virreinal.'],

            ['hotel' => 'Inkaterra Machu Picchu Pueblo Hotel', 'hab' => 'Superior Casita', 'precio' => 590.00, 'notas' => 'Incluye caminatas guiadas de orquídeas y avistamiento de aves en los jardines.'],
            ['hotel' => 'Inkaterra Machu Picchu Pueblo Hotel', 'hab' => 'Junior Suite Casita con Chimenea', 'precio' => 790.00, 'notas' => 'Chimenea andina encendida cada noche y mayordomo privado.'],

            ['hotel' => 'Miraflores Park, A Belmond Hotel', 'hab' => 'Ocean View Deluxe Junior Suite', 'precio' => 520.00, 'notas' => 'Vista directa al Pacífico desde el acantilado y desayuno en piso 11.'],
            ['hotel' => 'Titilaka - Relais & Châteaux', 'hab' => 'Corner King Suite Lago Titicaca', 'precio' => 980.00, 'notas' => 'Pensión completa Relais & Châteaux y actividades náuticas privadas.'],
            ['hotel' => 'Colca Lodge Spa & Hot Springs', 'hab' => 'Suite Adobe con Termas Privadas', 'precio' => 450.00, 'notas' => 'Poza termal privada de aguas sulfurosas a 38 °C frente al río.'],
        ];

        foreach ($tarifasHabitaciones as $th) {
            $hotel = Hotel::where('nombre', $th['hotel'])->first();
            if (! $hotel) {
                continue;
            }
            $hab = Habitacion::where('hotel_id', $hotel->id)->where('tipo', $th['hab'])->first();
            if (! $hab) {
                continue;
            }

            Tarifa::create([
                'habitacion_id' => $hab->id,
                'proveedor_id' => $hotel->proveedor_id,
                'concepto' => "Alojamiento: {$hotel->nombre} · {$hab->tipo}",
                'desde' => $desde,
                'hasta' => $hasta,
                'precio' => $th['precio'],
                'moneda' => 'USD',
                'por' => 'noche',
                'notas' => $th['notas'],
            ]);
        }

        // 2. Tarifas por servicios de proveedores
        $tarifasProveedores = [
            // PeruRail
            ['proveedor' => 'PeruRail S.A.', 'concepto' => 'Tren Belmond Hiram Bingham (Cusco ↔ Machu Picchu ida y vuelta)', 'precio' => 580.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Coche observatorio, brunch gourmet de ida, cena gastronómica de vuelta y open bar.'],
            ['proveedor' => 'PeruRail S.A.', 'concepto' => 'Tren Vistadome Observatory (Ollantaytambo ↔ Aguas Calientes)', 'precio' => 185.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Ventanas panorámicas 360°, coche bar con show de danza típica andina.'],
            ['proveedor' => 'PeruRail S.A.', 'concepto' => 'Tren Expedition (Ollantaytambo ↔ Aguas Calientes)', 'precio' => 95.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Servicio clásico con asientos ergonómicos y audio explicativo.'],

            // Inca Rail
            ['proveedor' => 'Inca Rail S.A.C.', 'concepto' => 'The 360° Machu Picchu Train (Ollantaytambo ↔ Aguas Calientes)', 'precio' => 145.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Techo acristalado, audio guía interactiva y catering de productos locales.'],

            // Guías
            ['proveedor' => 'AGOTUR Cusco (Asociación de Guías Oficiales)', 'concepto' => 'Servicio Privado de Guía Oficial en Llaqta Machu Picchu (Día Completo)', 'precio' => 160.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Guía bilingüe acreditado DIRCETUR. Incluye circuitos 1 y 2 con pausas fotográficas.'],
            ['proveedor' => 'AGOTUR Cusco (Asociación de Guías Oficiales)', 'concepto' => 'Guía Privado Día Completo en Valle Sagrado (Pisac, Maras, Moray & Ollantaytambo)', 'precio' => 140.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Experto en agronomía incaica y textilería tradicional.'],
            ['proveedor' => 'Perú Expert Cultural Guides S.A.C.', 'concepto' => 'Arqueólogo Privado en Sacsayhuamán & Qenqo (Medio Día)', 'precio' => 130.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Enfoque arqueológico e histórico sobre la cantería y astronomía inca.'],

            // Transporte
            ['proveedor' => 'VIP Executive Transports Cusco S.A.C.', 'concepto' => 'Minivan Mercedes-Benz Sprinter Día Completo en Valle Sagrado', 'precio' => 210.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Capacidad hasta 8 pasajeros de lujo, chofer profesional bilingüe, agua embotellada y botiquín de oxígeno.'],
            ['proveedor' => 'VIP Executive Transports Cusco S.A.C.', 'concepto' => 'Traslado VIP Privado Aeropuerto Cusco ↔ Hotel Centro / San Blas', 'precio' => 45.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Bienvenida en sala con letrero de Andean Exclusive, toallas refrescantes y asistencia con equipaje.'],
            ['proveedor' => 'Lima Premium Chauffeur & Transfers', 'concepto' => 'Traslado Privado Aeropuerto Jorge Chávez ↔ Hotel Miraflores/San Isidro', 'precio' => 55.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Sedán ejecutivo o van privada con aire acondicionado y seguimiento de vuelo en tiempo real.'],

            // Entradas DDC Cusco
            ['proveedor' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'concepto' => 'Boleto Oficial Llaqta Machu Picchu (Circuito 2 Clásico / Panorámico)', 'precio' => 152.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'Tarifa oficial del Ministerio de Cultura de Perú para turista extranjero.'],
            ['proveedor' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'concepto' => 'Boleto Machu Picchu + Montaña Huayna Picchu (Circuito 3)', 'precio' => 200.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'Cupos limitados por turno. Requiere datos completos y pasaporte de cada pasajero.'],
            ['proveedor' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'concepto' => 'Boleto Turístico del Cusco Integral (BTG - 16 Atractivos 10 Días)', 'precio' => 130.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'Válido para Sacsayhuamán, Qenqo, Puka Pukara, Tambomachay, Pisac, Ollantaytambo, Moray, Chinchero y museos.'],

            // Restaurantes
            ['proveedor' => 'Central Restaurante (Virgilio Martínez & Pía León)', 'concepto' => 'Experiencia Alturas Mater (Menú Degustación 14 Pasos)', 'precio' => 1250.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'Recorrido por las diferentes altitudes del territorio peruano. Requiere reserva con 3 meses de anticipación.'],
            ['proveedor' => 'Maido Nikkei Experience (Mitsuharu Tsumura)', 'concepto' => 'Menú Degustación Nikkei Experience (10 Pasos)', 'precio' => 950.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'La más alta expresión del arte culinario peruano-japonés en Sudamérica.'],
            ['proveedor' => 'Chicha por Gastón Acurio - Cusco', 'concepto' => 'Almuerzo Degustación Valle del Vilcanota (4 Tiempos con Maridaje)', 'precio' => 240.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'Menú especial para viajeros de Andean Exclusive en mesa preferente frente a Plaza Regocijo.'],
            ['proveedor' => 'Hacienda Huayoccari Restaurant & Private Museum', 'concepto' => 'Almuerzo Campestre Gourmet con Visita a Colección Privada', 'precio' => 85.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Almuerzo maridado en los salones de la casona con visita guiada por los curadores de la familia Lambarri.'],
        ];

        foreach ($tarifasProveedores as $tp) {
            $proveedor = Proveedor::where('nombre', $tp['proveedor'])->first();
            if (! $proveedor) {
                continue;
            }

            Tarifa::create([
                'proveedor_id' => $proveedor->id,
                'concepto' => $tp['concepto'],
                'desde' => $desde,
                'hasta' => $hasta,
                'precio' => $tp['precio'],
                'moneda' => $tp['moneda'],
                'por' => $tp['por'],
                'notas' => $tp['notas'],
            ]);
        }
    }
}
