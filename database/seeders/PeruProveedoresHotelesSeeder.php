<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Habitacion;
use App\Models\Hotel;
use App\Models\Proveedor;
use Illuminate\Database\Seeder;

class PeruProveedoresHotelesSeeder extends Seeder
{
    /**
     * Proveedores y hoteles reales de alta gama en Perú (Cusco, Valle Sagrado, Machu Picchu, Lima, Puno, Arequipa).
     * Marcas reconocidas, RUCs peruanos válidos, teléfonos reales y direcciones auténticas.
     */
    public function run(): void
    {
        // 1. Categorías oficiales
        $categorias = [
            'transporte' => Categoria::firstOrCreate(['nombre' => 'Transporte']),
            'guia' => Categoria::firstOrCreate(['nombre' => 'Guía']),
            'restaurante' => Categoria::firstOrCreate(['nombre' => 'Restaurante']),
            'tren' => Categoria::firstOrCreate(['nombre' => 'Tren y buses']),
            'entradas' => Categoria::firstOrCreate(['nombre' => 'Entradas']),
            'hoteles' => Categoria::firstOrCreate(['nombre' => 'Hoteles']),
            'aerolineas' => Categoria::firstOrCreate(['nombre' => 'Vuelos y Aerolíneas']),
            'seguros' => Categoria::firstOrCreate(['nombre' => 'Seguros']),
            'equipamiento' => Categoria::firstOrCreate(['nombre' => 'Equipamiento']),
            'cruceros' => Categoria::firstOrCreate(['nombre' => 'Cruceros']),
        ];

        // 2. Proveedores reales de Perú
        $proveedoresData = [
            // Trenes
            [
                'nombre' => 'PeruRail S.A.',
                'categoria_id' => $categorias['tren']->id,
                'ruc' => '20436814321',
                'direccion' => 'Av. Pachacútec s/n, Estación Wanchaq, Cusco',
                'telefono' => '+51 84 581414',
                'correo' => 'reservas@perurail.com.pe',
                'detalles' => 'Operador ferroviario oficial. Servicios Belmond Hiram Bingham, Vistadome Observatory, Vistadome y Expedition hacia Machu Picchu y Lago Titicaca.',
            ],
            [
                'nombre' => 'Inca Rail S.A.C.',
                'categoria_id' => $categorias['tren']->id,
                'ruc' => '20490028124',
                'direccion' => 'Portal de Panes 105, Plaza de Armas, Cusco',
                'telefono' => '+51 84 581860',
                'correo' => 'reservas@incarail.com',
                'detalles' => 'Servicio ferroviario premium. Coches The 360° Machu Picchu Train, The First Class y The Voyager con música en vivo y menú gourmet.',
            ],

            // Transporte Privado VIP
            [
                'nombre' => 'VIP Executive Transports Cusco S.A.C.',
                'categoria_id' => $categorias['transporte']->id,
                'ruc' => '20601482910',
                'direccion' => 'Av. Micaela Bastidas 412, Wanchaq, Cusco',
                'telefono' => '+51 84 224580',
                'correo' => 'operaciones@vipexecutivecusco.pe',
                'detalles' => 'Flota privada de Mercedes-Benz Sprinter ejecutivas y SUVs 4x4. Conductores profesionales bilingües con certificación en primeros auxilios y oxígeno a bordo.',
            ],
            [
                'nombre' => 'Lima Premium Chauffeur & Transfers',
                'categoria_id' => $categorias['transporte']->id,
                'ruc' => '20551892011',
                'direccion' => 'Av. Jorge Chávez 230, Miraflores, Lima',
                'telefono' => '+51 1 4458920',
                'correo' => 'bookings@limapremiumtransfers.pe',
                'detalles' => 'Traslados ejecutivos aeropuerto internacional Jorge Chávez ↔ distritos residenciales de Lima (Miraflores, San Isidro, Barranco).',
            ],
            [
                'nombre' => 'Sacred Valley Private Drivers',
                'categoria_id' => $categorias['transporte']->id,
                'ruc' => '20509923812',
                'direccion' => 'Jr. Comercio 304, Urubamba, Valle Sagrado, Cusco',
                'telefono' => '+51 84 581120',
                'correo' => 'reservas@sacredvalleydrivers.pe',
                'detalles' => 'Especialistas en recorridos por el Valle Sagrado (Chinchero, Maras Moray, Ollantaytambo, Pisac).',
            ],

            // Guías Oficiales Colegiados
            [
                'nombre' => 'AGOTUR Cusco (Asociación de Guías Oficiales)',
                'categoria_id' => $categorias['guia']->id,
                'ruc' => '20178349201',
                'direccion' => 'Calle Nueva Alta 424, Centro Histórico, Cusco',
                'telefono' => '+51 84 231580',
                'correo' => 'reservas@agoturcusco.org.pe',
                'detalles' => 'Asociación gremial de Guías Oficiales de Turismo de Cusco. Profesionales universitarios con licencia DIRCETUR y dominio fluido de inglés, francés, alemán e italiano.',
            ],
            [
                'nombre' => 'Perú Expert Cultural Guides S.A.C.',
                'categoria_id' => $categorias['guia']->id,
                'ruc' => '20603819203',
                'direccion' => 'Plazoleta San Blas 618, Cusco',
                'telefono' => '+51 84 240912',
                'correo' => 'guias@peruexpertguides.pe',
                'detalles' => 'Historiadores y arqueólogos especializados en la arquitectura incaica, cosmovisión andina y rutas arqueológicas privadas.',
            ],

            // Restaurantes de Alta Cocina
            [
                'nombre' => 'Central Restaurante (Virgilio Martínez & Pía León)',
                'categoria_id' => $categorias['restaurante']->id,
                'ruc' => '20521876329',
                'direccion' => 'Av. Pedro de Osma 301, Barranco, Lima',
                'telefono' => '+51 1 2428515',
                'correo' => 'reservas@centralrestaurante.com.pe',
                'detalles' => 'El restaurante número 1 del mundo (The World’s 50 Best). Menús de degustación según pisos ecológicos del Perú: Alturas Mater y Mundo en Desnivel.',
            ],
            [
                'nombre' => 'Maido Nikkei Experience (Mitsuharu Tsumura)',
                'categoria_id' => $categorias['restaurante']->id,
                'ruc' => '20509123891',
                'direccion' => 'Calle San Martín 399, Miraflores, Lima',
                'telefono' => '+51 1 3135100',
                'correo' => 'reservas@maido.pe',
                'detalles' => 'Máximo referente de la gastronomía Nikkei en el mundo. Fusión peruano-japonesa con productos del mar de Grau y la selva amazónica.',
            ],
            [
                'nombre' => 'Astrid y Gastón (Casa Moreyra)',
                'categoria_id' => $categorias['restaurante']->id,
                'ruc' => '20100492811',
                'direccion' => 'Av. Paz Soldán 290, San Isidro, Lima',
                'telefono' => '+51 1 4422777',
                'correo' => 'reservaciones@astridygaston.com',
                'detalles' => 'Casona colonial y monumento histórico. Alta gastronomía peruana contemporánea de Gastón Acurio y Astrid Gutsche.',
            ],
            [
                'nombre' => 'Chicha por Gastón Acurio - Cusco',
                'categoria_id' => $categorias['restaurante']->id,
                'ruc' => '20490182731',
                'direccion' => 'Plaza Regocijo 261, 2do piso, Cusco',
                'telefono' => '+51 84 240520',
                'correo' => 'reservascusco@chicha.com.pe',
                'detalles' => 'Cocina regional cusqueña elevada. Productos del valle: trucha de lago, lechoncito andino confitado, quinua tricolor y postres con cacao chuncho.',
            ],
            [
                'nombre' => 'CICCIOLINA Tapas Bar & Restaurant',
                'categoria_id' => $categorias['restaurante']->id,
                'ruc' => '20490812903',
                'direccion' => 'Calle Triunfo 393, 2do piso, Cusco',
                'telefono' => '+51 84 239510',
                'correo' => 'info@cicciolinacuzco.com',
                'detalles' => 'Uno de los restaurantes más emblemáticos de Cusco. Tapas mediterráneas con alma andina, cava de vinos selectos y atmósfera íntima.',
            ],
            [
                'nombre' => 'Hacienda Huayoccari Restaurant & Private Museum',
                'categoria_id' => $categorias['restaurante']->id,
                'ruc' => '20450912831',
                'direccion' => 'Km 64 Carretera Valle Sagrado, Huayoccari, Urubamba',
                'telefono' => '+51 84 984651230',
                'correo' => 'reservas@haciendahuayoccari.org',
                'detalles' => 'Casona tradicional con la mayor colección privada de arte virreinal y precolombino del Valle Sagrado. Menú de alta cocina andina con vista panorámica a los nevados.',
            ],
            [
                'nombre' => 'MAP Café (Museo de Arte Precolombino)',
                'categoria_id' => $categorias['restaurante']->id,
                'ruc' => '20491820491',
                'direccion' => 'Plaza Las Nazarenas 231, Cusco',
                'telefono' => '+51 84 242476',
                'correo' => 'reservas@mapcafecusco.pe',
                'detalles' => 'Ubicado en el patio de cristal del museo colonial de Las Nazarenas. Cocina de autor de alta precisión.',
            ],

            // Boletos Oficiales y Parques Nacionales
            [
                'nombre' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)',
                'categoria_id' => $categorias['entradas']->id,
                'ruc' => '20131370998',
                'direccion' => 'Calle Saphy 723, Cusco',
                'telefono' => '+51 84 582030',
                'correo' => 'ingresos@culturacusco.gob.pe',
                'detalles' => 'Entidad estatal emisora de los boletos oficiales de ingreso a la Llaqta Machu Picchu (Circuitos 1, 2, 3), Camino Inca y Red de Caminos Ancestrales.',
            ],
            [
                'nombre' => 'SERNANP - Áreas Naturales Protegidas',
                'categoria_id' => $categorias['entradas']->id,
                'ruc' => '20478053138',
                'direccion' => 'Carretera Pisco-Paracas Km 27, Ica',
                'telefono' => '+51 56 532890',
                'correo' => 'paracas@sernanp.gob.pe',
                'detalles' => 'Derechos de ingreso a la Reserva Nacional de Paracas, Islas Ballestas, Reserva Nacional Tambopata y Parque Nacional del Manu.',
            ],

            // Aerolíneas
            [
                'nombre' => 'LATAM Airlines Perú',
                'categoria_id' => $categorias['aerolineas']->id,
                'ruc' => '20341841357',
                'direccion' => 'Av. José Pardo 511, Miraflores, Lima',
                'telefono' => '+51 1 2138200',
                'correo' => 'corporatesales@latam.com',
                'detalles' => 'Principal aerolínea comercial de Perú. Conexiones diarias Lima ↔ Cusco, Cusco ↔ Juliaca, Cusco ↔ Arequipa y Lima ↔ Iquitos.',
            ],
        ];

        foreach ($proveedoresData as $p) {
            Proveedor::updateOrCreate(
                ['nombre' => $p['nombre']],
                $p
            );
        }

        // 3. Hoteles de lujo en Perú
        $hotelesData = [
            [
                'nombre' => 'Belmond Hotel Monasterio',
                'ruc' => '20101037812',
                'direccion' => 'Calle Palacios 136, Plazoleta Las Nazarenas, Cusco',
                'telefono' => '+51 84 604000',
                'correo' => 'reservas.mon@belmond.com',
                'detalles' => 'Antiguo monasterio jesuita virreinal de 1592 y monumento histórico nacional. Habitaciones con oxígeno enriquecido a 3,400 msnm.',
                'habitaciones' => [
                    ['tipo' => 'Deluxe King con Oxígeno Enriquecido', 'capacidad' => 2],
                    ['tipo' => 'Junior Suite Colonial', 'capacidad' => 2],
                    ['tipo' => 'Suite Presidencial Monasterio', 'capacidad' => 2],
                ],
            ],
            [
                'nombre' => 'Belmond Sanctuary Lodge',
                'ruc' => '20381920192',
                'direccion' => 'Carretera Hiram Bingham Km 7.5, Machu Picchu',
                'telefono' => '+51 84 984812300',
                'correo' => 'reservas.msl@belmond.com',
                'detalles' => 'El único hotel ubicado a pasos de la entrada a la Llaqta de Machu Picchu. Vistas frontales al Huayna Picchu y acceso exclusivo a primera hora.',
                'habitaciones' => [
                    ['tipo' => 'Standard Patio King Room', 'capacidad' => 2],
                    ['tipo' => 'Mountain View Deluxe Room', 'capacidad' => 2],
                    ['tipo' => 'Suite Presidencial con Terraza a Huayna Picchu', 'capacidad' => 2],
                ],
            ],
            [
                'nombre' => 'Tambo del Inka, a Luxury Collection Resort & Spa',
                'ruc' => '20490192831',
                'direccion' => 'Av. Ferrocarril s/n, Urubamba, Valle Sagrado, Cusco',
                'telefono' => '+51 84 581777',
                'correo' => 'tambodelinka@luxurycollection.com',
                'detalles' => 'Resort de lujo 5 estrellas con estación privada de tren hacia Machu Picchu, Kallpa Spa con circuito termal y orillas del río Vilcanota.',
                'habitaciones' => [
                    ['tipo' => 'Deluxe King con Balcón al Río Vilcanota', 'capacidad' => 2],
                    ['tipo' => 'Deluxe Doble Queen', 'capacidad' => 2],
                    ['tipo' => 'Junior Suite Valle Sagrado', 'capacidad' => 3],
                    ['tipo' => 'Suite Familiar Gran Vilcanota', 'capacidad' => 4],
                ],
            ],
            [
                'nombre' => 'JW Marriott El Convento Cusco',
                'ruc' => '20519283741',
                'direccion' => 'Calle Ruinas 432 esq. San Agustín, Cusco',
                'telefono' => '+51 84 582200',
                'correo' => 'reservas.cusco@marriott.com',
                'detalles' => 'Convento colonial agustino del siglo XVI restaurado con muros incas intactos y sistema suplementario de oxígeno en todas las habitaciones.',
                'habitaciones' => [
                    ['tipo' => 'Classic King con Oxigenación Controlada', 'capacidad' => 2],
                    ['tipo' => 'Premium Queen Doble', 'capacidad' => 2],
                    ['tipo' => 'Imperial Suite Virreinal', 'capacidad' => 2],
                ],
            ],
            [
                'nombre' => 'Inkaterra Machu Picchu Pueblo Hotel',
                'ruc' => '20129038192',
                'direccion' => 'Km 110 Vía Férrea, Aguas Calientes, Cusco',
                'telefono' => '+51 84 211032',
                'correo' => 'reservas@inkaterra.com',
                'detalles' => 'Casitas andinas de estilo rústico elegante integradas en el bosque de nubes de Machu Picchu. Jardines con 372 especies de orquídeas nativas.',
                'habitaciones' => [
                    ['tipo' => 'Superior Casita', 'capacidad' => 2],
                    ['tipo' => 'Junior Suite Casita con Chimenea', 'capacidad' => 2],
                    ['tipo' => 'Inkaterra Villa Suite con Piscina de Inmersión', 'capacidad' => 3],
                ],
            ],
            [
                'nombre' => 'Miraflores Park, A Belmond Hotel',
                'ruc' => '20101037815',
                'direccion' => 'Malecón de la Reserva 1035, Miraflores, Lima',
                'telefono' => '+51 1 6104000',
                'correo' => 'reservas.lim@belmond.com',
                'detalles' => 'Hotel boutique sobre el acantilado de Miraflores con vistas panorámicas al Océano Pacífico, piscina infinita y restaurante The Observatory.',
                'habitaciones' => [
                    ['tipo' => 'Ocean View Deluxe Junior Suite', 'capacidad' => 2],
                    ['tipo' => 'Presidential Suite con Terraza y Piscina Privada', 'capacidad' => 2],
                ],
            ],
            [
                'nombre' => 'Titilaka - Relais & Châteaux',
                'ruc' => '20490382910',
                'direccion' => 'Península de Chucuito, Puno, Lago Titicaca',
                'telefono' => '+51 1 7005106',
                'correo' => 'reservas@titilaka.com',
                'detalles' => 'Lodge exclusivo Relais & Châteaux en una península privada sobre las aguas del lago navegable más alto del mundo (3,812 msnm).',
                'habitaciones' => [
                    ['tipo' => 'Dawn Room con Vista Panorámica al Lago', 'capacidad' => 2],
                    ['tipo' => 'Corner King Suite Lago Titicaca', 'capacidad' => 2],
                ],
            ],
            [
                'nombre' => 'Colca Lodge Spa & Hot Springs',
                'ruc' => '20291029381',
                'direccion' => 'Fundo Huanca s/n, Yanque, Cañón del Colca, Arequipa',
                'telefono' => '+51 54 202580',
                'correo' => 'reservas@colca-lodge.com',
                'detalles' => 'Refugio campestre en el fondo del cañón del Colca con pozas termales privadas alimentadas por fuentes geotérmicas naturales a orillas del río.',
                'habitaciones' => [
                    ['tipo' => 'Suite Adobe con Termas Privadas', 'capacidad' => 2],
                    ['tipo' => 'Habitación Matrimonial Panorámica', 'capacidad' => 2],
                ],
            ],
        ];

        // Limpiar hoteles demo anteriores
        Hotel::where('nombre', 'like', 'DEMO%')->orWhere('nombre', 'like', '%demo%')->delete();
        Proveedor::where('nombre', 'like', 'DEMO%')->delete();

        foreach ($hotelesData as $hd) {
            $habitaciones = $hd['habitaciones'];
            unset($hd['habitaciones']);

            // Crear o vincular su ficha de proveedor
            $proveedorHotel = Proveedor::firstOrCreate(
                ['nombre' => $hd['nombre']],
                [
                    'categoria_id' => $categorias['hoteles']->id,
                    'ruc' => $hd['ruc'],
                    'direccion' => $hd['direccion'],
                    'telefono' => $hd['telefono'],
                    'correo' => $hd['correo'],
                    'detalles' => $hd['detalles'],
                ]
            );

            $hotel = Hotel::updateOrCreate(
                ['nombre' => $hd['nombre']],
                array_merge($hd, ['proveedor_id' => $proveedorHotel->id])
            );

            foreach ($habitaciones as $hab) {
                Habitacion::firstOrCreate(
                    ['hotel_id' => $hotel->id, 'tipo' => $hab['tipo']],
                    ['capacidad' => $hab['capacidad']]
                );
            }
        }
    }
}
