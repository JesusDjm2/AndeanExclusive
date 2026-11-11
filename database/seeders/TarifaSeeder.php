<?php

namespace Database\Seeders;

use App\Models\Habitacion;
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
            ['hotel' => 'Belmond Sanctuary Lodge', 'hab' => 'Suite Presidencial con Terraza a Huayna Picchu', 'precio' => 2200.00, 'notas' => 'Terraza privada con vistas incomparables a Huayna Picchu.'],

            ['hotel' => 'Belmond Palacio Nazarenas', 'hab' => 'Junior Suite Nazarenas con Oxígeno', 'precio' => 850.00, 'notas' => 'Oxígeno suplementario controlado, bar privado con hierbas andinas y mayordomo.'],
            ['hotel' => 'Belmond Palacio Nazarenas', 'hab' => 'Studio Suite Virreinal con Balcón', 'precio' => 1150.00, 'notas' => 'Pinturas de la escuela cusqueña restauradas y vista a la piscina temperada.'],
            ['hotel' => 'Belmond Palacio Nazarenas', 'hab' => 'Palacio Suite Presidencial', 'precio' => 1850.00, 'notas' => 'La más exclusiva del centro histórico de Cusco.'],

            ['hotel' => 'Tambo del Inka, a Luxury Collection Resort & Spa', 'hab' => 'Deluxe King con Balcón al Río Vilcanota', 'precio' => 480.00, 'notas' => 'Acceso a circuito de hidroterapia Kallpa Spa y andén privado de tren.'],
            ['hotel' => 'Tambo del Inka, a Luxury Collection Resort & Spa', 'hab' => 'Junior Suite Valle Sagrado', 'precio' => 720.00, 'notas' => 'Sala de estar independiente con chimenea a leña.'],
            ['hotel' => 'Tambo del Inka, a Luxury Collection Resort & Spa', 'hab' => 'Suite Familiar Gran Vilcanota', 'precio' => 950.00, 'notas' => 'Dos dormitorios independientes y balcón panorámico al río.'],

            ['hotel' => 'JW Marriott El Convento Cusco', 'hab' => 'Classic King con Oxigenación Controlada', 'precio' => 420.00, 'notas' => 'Incluye tour arqueológico privado por el convento y desayuno en Qespi.'],
            ['hotel' => 'JW Marriott El Convento Cusco', 'hab' => 'Premium Queen Doble', 'precio' => 450.00, 'notas' => 'Dos camas queen con oxigenación suplementaria.'],
            ['hotel' => 'JW Marriott El Convento Cusco', 'hab' => 'Imperial Suite Virreinal', 'precio' => 880.00, 'notas' => 'Muros de piedra inca pulida y sala de estar virreinal.'],

            ['hotel' => 'Inkaterra Machu Picchu Pueblo Hotel', 'hab' => 'Superior Casita', 'precio' => 590.00, 'notas' => 'Incluye caminatas guiadas de orquídeas y avistamiento de aves en los jardines.'],
            ['hotel' => 'Inkaterra Machu Picchu Pueblo Hotel', 'hab' => 'Junior Suite Casita con Chimenea', 'precio' => 790.00, 'notas' => 'Chimenea andina encendida cada noche y mayordomo privado.'],
            ['hotel' => 'Inkaterra Machu Picchu Pueblo Hotel', 'hab' => 'Inkaterra Villa Suite con Piscina de Inmersión', 'precio' => 1350.00, 'notas' => 'Villa privada con jardín propio y piscina de inmersión temperada.'],

            ['hotel' => 'Sumaq Machu Picchu Hotel', 'hab' => 'Deluxe King con Vista al Río Vilcanota', 'precio' => 520.00, 'notas' => 'Desayuno buffet y cena a la carta gourmet en restaurante Qunuq.'],
            ['hotel' => 'Sumaq Machu Picchu Hotel', 'hab' => 'Junior Suite Sumaq con Hidromasaje', 'precio' => 720.00, 'notas' => 'Jacuzzi con vista a la montaña y ceremonia de bienvenida andina.'],
            ['hotel' => 'Sumaq Machu Picchu Hotel', 'hab' => 'Imperial Suite con Terraza Privada', 'precio' => 1100.00, 'notas' => 'Terraza con vista panorámica y clase privada de ceviche y pisco sour.'],

            ['hotel' => 'Aranwa Sacred Valley Hotel & Wellness', 'hab' => 'Habitación Colonial Deluxe', 'precio' => 340.00, 'notas' => 'Hacienda del siglo XVII, acceso libre al museo de la casona y capilla colonial.'],
            ['hotel' => 'Aranwa Sacred Valley Hotel & Wellness', 'hab' => 'Junior Suite Colonial con Jacuzzi', 'precio' => 490.00, 'notas' => 'Circuito en Unno Spa de 2,500 m² incluido.'],
            ['hotel' => 'Aranwa Sacred Valley Hotel & Wellness', 'hab' => 'River Suite con Chimenea a Leña', 'precio' => 650.00, 'notas' => 'Frente al río Vilcanota con chimenea a leña.'],

            ['hotel' => 'Casa Andina Premium Cusco', 'hab' => 'Superior King Tradición', 'precio' => 220.00, 'notas' => 'Casona colonial tradicional en Plazoleta Limacpampa con desayuno buffet.'],
            ['hotel' => 'Casa Andina Premium Cusco', 'hab' => 'Suite Colonial con Sala Independiente', 'precio' => 380.00, 'notas' => 'Balcón colonial hacia el claustro de piedra.'],

            ['hotel' => 'Miraflores Park, A Belmond Hotel', 'hab' => 'Ocean View Deluxe Junior Suite', 'precio' => 520.00, 'notas' => 'Vista directa al Pacífico desde el acantilado y desayuno en piso 11.'],
            ['hotel' => 'Miraflores Park, A Belmond Hotel', 'hab' => 'Presidential Suite con Terraza y Piscina Privada', 'precio' => 1850.00, 'notas' => 'Piscina privada en azotea con vista al océano.'],

            ['hotel' => 'Titilaka - Relais & Châteaux', 'hab' => 'Dawn Room con Vista Panorámica al Lago', 'precio' => 820.00, 'notas' => 'Pensión completa Relais & Châteaux y vista al amanecer en el lago.'],
            ['hotel' => 'Titilaka - Relais & Châteaux', 'hab' => 'Corner King Suite Lago Titicaca', 'precio' => 980.00, 'notas' => 'Ventanas esquineras panorámicas de piso a techo y actividades privadas náuticas.'],

            ['hotel' => 'Colca Lodge Spa & Hot Springs', 'hab' => 'Suite Adobe con Termas Privadas', 'precio' => 450.00, 'notas' => 'Poza termal privada de aguas sulfurosas a 38 °C frente al río.'],
            ['hotel' => 'Colca Lodge Spa & Hot Springs', 'hab' => 'Habitación Matrimonial Panorámica', 'precio' => 320.00, 'notas' => 'Vista al cañón y acceso libre a las 4 pozas termales comunes.'],
        ];

        foreach ($tarifasHabitaciones as $th) {
            $hotel = Proveedor::where('nombre', $th['hotel'])->first();
            if (! $hotel) {
                continue;
            }
            $hab = Habitacion::where('proveedor_id', $hotel->id)->where('tipo', $th['hab'])->first();
            if (! $hab) {
                continue;
            }

            Tarifa::create([
                'habitacion_id' => $hab->id,
                'proveedor_id' => $hotel->id,
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
            // --- TRENES ---
            ['proveedor' => 'PeruRail S.A.', 'concepto' => 'Tren Belmond Hiram Bingham (Cusco ↔ Machu Picchu ida y vuelta)', 'precio' => 580.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Coche observatorio, brunch gourmet de ida, cena gastronómica de vuelta y open bar.'],
            ['proveedor' => 'PeruRail S.A.', 'concepto' => 'Tren Vistadome Observatory (Ollantaytambo ↔ Aguas Calientes)', 'precio' => 185.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Ventanas panorámicas 360°, coche bar con show de danza típica andina.'],
            ['proveedor' => 'PeruRail S.A.', 'concepto' => 'Tren Expedition (Ollantaytambo ↔ Aguas Calientes)', 'precio' => 95.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Servicio clásico con asientos ergonómicos y audio explicativo.'],
            ['proveedor' => 'Inca Rail S.A.C.', 'concepto' => 'The 360° Machu Picchu Train (Ollantaytambo ↔ Aguas Calientes)', 'precio' => 145.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Techo acristalado, audio guía interactiva y catering de productos locales.'],
            ['proveedor' => 'Inca Rail S.A.C.', 'concepto' => 'The First Class Machu Picchu Train con Menú Gourmet', 'precio' => 220.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Coche observatorio al aire libre, música en vivo y menú de 3 tiempos maridado.'],

            // --- BUSES Y TRANSPORTES PRIVADOS ---
            ['proveedor' => 'Consettur Machu Picchu S.A.C.', 'concepto' => 'Bus Oficial Subida y Bajada Aguas Calientes ↔ Llaqta Machu Picchu', 'precio' => 24.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Boleto ecológico oficial de ida y vuelta. Tarifa extranjera estándar.'],
            ['proveedor' => 'VIP Executive Transports Cusco S.A.C.', 'concepto' => 'Minivan Mercedes-Benz Sprinter Día Completo en Valle Sagrado', 'precio' => 210.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Capacidad hasta 8 pasajeros de lujo, chofer profesional bilingüe, agua embotellada y botiquín de oxígeno.'],
            ['proveedor' => 'VIP Executive Transports Cusco S.A.C.', 'concepto' => 'Traslado VIP Privado Aeropuerto Cusco ↔ Hotel Centro / San Blas', 'precio' => 45.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Bienvenida en sala con letrero de Andean Exclusive, toallas refrescantes y asistencia con equipaje.'],
            ['proveedor' => 'Lima Premium Chauffeur & Transfers', 'concepto' => 'Traslado Privado Aeropuerto Jorge Chávez ↔ Hotel Miraflores/San Isidro', 'precio' => 55.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Sedán ejecutivo o van privada con aire acondicionado y seguimiento de vuelo en tiempo real.'],
            ['proveedor' => 'Sacred Valley Private Drivers', 'concepto' => 'Traslado Privado SUV 4x4 Urubamba ↔ Estación Ollantaytambo', 'precio' => 40.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Vehículo Toyota Prado para traslados hacia estaciones de tren.'],
            ['proveedor' => 'Turismo Mer S.R.L. (Ruta del Sol Cusco - Puno)', 'concepto' => 'Bus Guiado Primera Clase Ruta del Sol Cusco ↔ Puno con Almuerzo', 'precio' => 65.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Incluye guía a bordo, entradas y almuerzo buffet campestre en Sicuani.'],
            ['proveedor' => 'Cruz del Sur S.A.C. (Servicio Cruzero Suite VIP)', 'concepto' => 'Bus Interprovincial Cruzero Suite Asiento 180° Lima ↔ Paracas', 'precio' => 35.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Asientos cama vip con entretenimiento a bordo y refrigerio gourmet.'],
            ['proveedor' => 'Andean Luxury Sprinters Cusco', 'concepto' => 'Mercedes Sprinter VIP Excursión Salineras de Maras & Moray', 'precio' => 170.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Conductor con amplia experiencia en rutas de montaña y kit de oxígeno.'],

            // --- GUÍAS OFICIALES Y PROFESIONALES INDEPENDIENTES ---
            ['proveedor' => 'Lic. Juan Carlos Huamán Quispe (Guía Arqueológico)', 'concepto' => 'Servicio de Guía Oficial Privado en Machu Picchu Día Completo', 'precio' => 160.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Guía colegiado DIRCETUR 0418. Especialista en astronomía inca y circuitos 1 y 2.'],
            ['proveedor' => 'Lic. Carmen Rosa Ttito Condori (Guía Valle Sagrado)', 'concepto' => 'Guía Privado Día Completo en Pisac, Maras, Moray & Ollantaytambo', 'precio' => 140.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Especialista en etnobotánica andina y textilería tradicional.'],
            ['proveedor' => 'Lic. Mateo Yupanqui Escalante (Guía Alta Montaña & Trekking)', 'concepto' => 'Guía de Aventura & Trekking Día Completo (Salkantay / Montaña 7 Colores)', 'precio' => 150.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Certificación WFR primeros auxilios de montaña y equipo satelital inReach.'],
            ['proveedor' => 'Lic. Lucía Morales Benavides (Historiadora Virreinal)', 'concepto' => 'Visita Histórica Privada Catedral de Cusco, Qorikancha & Museos (Medio Día)', 'precio' => 120.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Enfoque de historiadora sobre la Escuela Cusqueña virreinal y sincretismo andino.'],
            ['proveedor' => 'Lic. Renzo Valdivia Chávez (Sommelier & Guía Gastronómico)', 'concepto' => 'Walking Tour Gastronómico Privado por Lima & Cata de Piscos', 'precio' => 140.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Recorrido por mercados tradicionales de Surquillo y cata en taberna histórica.'],
            ['proveedor' => 'Lic. Sonia Mamani Choque (Guía Lago Titicaca)', 'concepto' => 'Guía Privado Día Completo Islas Uros & Taquile en Lancha Veloz', 'precio' => 120.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Especialista en tradiciones ancestrales de las comunidades del lago.'],
            ['proveedor' => 'AGOTUR Cusco (Asociación de Guías Oficiales)', 'concepto' => 'Servicio Oficial de Guía Bilingüe en Cusco Imperial & Sacsayhuamán', 'precio' => 140.00, 'moneda' => 'USD', 'por' => 'grupo', 'notas' => 'Acreditación profesional DIRCETUR en español e inglés.'],

            // --- BOLETOS DE ENTRADA Y PARQUES NACIONALES ---
            ['proveedor' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'concepto' => 'Boleto Oficial Llaqta Machu Picchu (Circuito 2 Clásico / Panorámico)', 'precio' => 152.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'Tarifa oficial del Ministerio de Cultura de Perú para turista extranjero.'],
            ['proveedor' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'concepto' => 'Boleto Machu Picchu + Montaña Huayna Picchu (Circuito 3)', 'precio' => 200.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'Cupos limitados por turno. Requiere datos completos y pasaporte de cada pasajero.'],
            ['proveedor' => 'COSITUC (Comité del Boleto Turístico del Cusco)', 'concepto' => 'Boleto Turístico del Cusco Integral (BTC - 16 Atractivos 10 Días)', 'precio' => 130.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'Válido para Sacsayhuamán, Qenqo, Puka Pukara, Tambomachay, Pisac, Ollantaytambo, Moray, Chinchero y museos.'],
            ['proveedor' => 'Convento de Santo Domingo - Qorikancha', 'concepto' => 'Boleto Oficial de Ingreso al Templo del Sol Qorikancha', 'precio' => 20.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'Tarifa oficial por persona. Visita a los recintos incas de piedra pulida.'],
            ['proveedor' => 'Arzobispado del Cusco (Circuito Religioso)', 'concepto' => 'Boleto Oficial Catedral Basílica del Cusco & Templo del Triunfo', 'precio' => 40.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'Acceso a la nave principal, coro tallado en cedro y colección de lienzos coloniales.'],
            ['proveedor' => 'Comunidad Campesina de Maras (Salineras de Maras)', 'concepto' => 'Derecho Comunal de Ingreso a las Salineras Ancestrales de Maras', 'precio' => 20.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'Boleto gestionado por la comunidad campesina local.'],
            ['proveedor' => 'SERNANP - Áreas Naturales Protegidas', 'concepto' => 'Derecho de Ingreso Reserva Nacional de Paracas & Islas Ballestas', 'precio' => 30.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'Tasa oficial SERNANP de conservación ambiental.'],
            ['proveedor' => 'AUTOCOLCA (Boleto Cañón del Colca)', 'concepto' => 'Boleto Turístico del Cañón del Colca (BTC Extranjero)', 'precio' => 70.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'Válido para los miradores del Cañón del Colca y Cruz del Cóndor.'],

            // --- RESTAURANTES Y ALTA GASTRONOMÍA ---
            ['proveedor' => 'Central Restaurante (Virgilio Martínez & Pía León)', 'concepto' => 'Experiencia Alturas Mater (Menú Degustación 14 Pasos)', 'precio' => 1250.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'Recorrido por las diferentes altitudes del territorio peruano. Requiere reserva con 3 meses de anticipación.'],
            ['proveedor' => 'Maido Nikkei Experience (Mitsuharu Tsumura)', 'concepto' => 'Menú Degustación Nikkei Experience (10 Pasos)', 'precio' => 950.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'La más alta expresión del arte culinario peruano-japonés en Sudamérica.'],
            ['proveedor' => 'Astrid y Gastón (Casa Moreyra)', 'concepto' => 'Menú Degustación Tradición & Vanguardia Peruana (7 Tiempos)', 'precio' => 520.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'En el histórico comedor de la Casa Moreyra en San Isidro.'],
            ['proveedor' => 'Chicha por Gastón Acurio - Cusco', 'concepto' => 'Almuerzo Degustación Valle del Vilcanota (4 Tiempos con Maridaje)', 'precio' => 240.00, 'moneda' => 'PEN', 'por' => 'persona', 'notas' => 'Menú especial para viajeros de Andean Exclusive en mesa preferente frente a Plaza Regocijo.'],
            ['proveedor' => 'CICCIOLINA Tapas Bar & Restaurant', 'concepto' => 'Cena Gourmet de Tapas Andinas & Selección de Vinos de Altura', 'precio' => 75.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Tapas de lomo de alpaca, trucha curada y postres de chocolate cusqueño.'],
            ['proveedor' => 'Hacienda Huayoccari Restaurant & Private Museum', 'concepto' => 'Almuerzo Campestre Gourmet con Visita a Colección Privada', 'precio' => 85.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Almuerzo maridado en los salones de la casona con visita guiada por los curadores de la familia Lambarri.'],
            ['proveedor' => 'MAP Café (Museo de Arte Precolombino)', 'concepto' => 'Cena de Autor Contemporánea en Patio de Cristal Colonial', 'precio' => 65.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Platos emblemáticos como canelón de trucha y postre Lucuma Crunch.'],
            ['proveedor' => 'Morena Peruvian Kitchen - Cusco', 'concepto' => 'Almuerzo Fusión 3 Regiones del Perú con Cóctel de Bienvenida', 'precio' => 50.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Mesa reservada en el salón principal junto a Plaza de Armas.'],
            ['proveedor' => 'Tinkuy Restaurant at Belmond Sanctuary Lodge', 'concepto' => 'Almuerzo Buffet Gourmet Internacional y Andino en Machu Picchu', 'precio' => 55.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'El buffet más exclusivo contiguo a la salida de la Llaqta inca.'],
            ['proveedor' => 'El Huacatay (Cocina del Valle Sagrado)', 'concepto' => 'Almuerzo de Autor con Vegetales Orgánicos del Valle Sagrado', 'precio' => 55.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Insumos frescos recolectados en huerto propio en Urubamba.'],
            ['proveedor' => 'La Rosa Náutica (Miraflores, Lima)', 'concepto' => 'Almuerzo Marinero de Pescados & Mariscos sobre el Océano', 'precio' => 90.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Ceviche clásico de corvina, arroz con mariscos a la chiclayana y suspiro limeño.'],
            ['proveedor' => 'Zig Zag Restaurante (Arequipa)', 'concepto' => 'Cena Trilogía de Carnes Andinas a la Piedra Volcánica', 'precio' => 60.00, 'moneda' => 'USD', 'por' => 'persona', 'notas' => 'Lomo de alpaca, cordero y res sobre piedra volcánica del volcán Misti.'],
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
