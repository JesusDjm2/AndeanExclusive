<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Habitacion;
use App\Models\Proveedor;
use Illuminate\Database\Seeder;

class PeruProveedoresHotelesSeeder extends Seeder
{
    /**
     * Proveedores, transportes, guías, restaurantes y hoteles reales de alta gama en Perú.
     * Cubre Cusco, Valle Sagrado, Machu Picchu, Lima, Puno y Arequipa.
     * Guías profesionales oficiales bilingües, empresas ferroviarias oficiales, buses turísticos,
     * boletos y entradas oficiales de DDC Cusco, COSITUC, Qorikancha y SERNANP.
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

        // 2. Proveedores reales de Perú (Trenes, Buses, Transportes, Guías, Restaurantes, Entradas y Aerolíneas)
        $proveedoresData = [
            // --- TRENES ---
            [
                'nombre' => 'PeruRail S.A.',
                'categoria_id' => $categorias['tren']->id,
                'ruc' => '20436814321',
                'direccion' => 'Av. Pachacútec s/n, Estación Wanchaq, Cusco',
                'telefono' => '+51 84 581414',
                'correo' => 'reservas@perurail.com.pe',
                'detalles' => 'Operador ferroviario oficial. Servicios Belmond Hiram Bingham de ultralujo, Vistadome Observatory, Vistadome y Expedition hacia Machu Picchu y Lago Titicaca.',
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

            // --- BUSES Y TRANSPORTE TURÍSTICO PRIVADO ---
            [
                'nombre' => 'Consettur Machu Picchu S.A.C.',
                'categoria_id' => $categorias['transporte']->id,
                'ruc' => '20228391024',
                'direccion' => 'Av. Hermanos Ayar s/n, Aguas Calientes, Machu Picchu, Cusco',
                'telefono' => '+51 84 211134',
                'correo' => 'reservas@consettur.com',
                'detalles' => 'Operador oficial y exclusivo del transporte en buses ecológicos entre el pueblo de Aguas Calientes y la entrada principal de la Llaqta de Machu Picchu.',
            ],
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
                'detalles' => 'Especialistas en recorridos por el Valle Sagrado (Chinchero, Maras Moray, Ollantaytambo, Pisac) en vehículos 4x4 y minivans de lujo.',
            ],
            [
                'nombre' => 'Turismo Mer S.R.L. (Ruta del Sol Cusco - Puno)',
                'categoria_id' => $categorias['transporte']->id,
                'ruc' => '20490182390',
                'direccion' => 'Av. 28 de Julio R-2-1, Ttio, Wanchaq, Cusco',
                'telefono' => '+51 84 245100',
                'correo' => 'reservas@turismomer.com',
                'detalles' => 'Servicio de bus turístico guiado de primera clase en la Ruta del Sol (Cusco ↔ Puno). Incluye paradas en Andahuaylillas, Raqchi, La Raya y Pukará con almuerzo buffet campestre.',
            ],
            [
                'nombre' => 'Cruz del Sur S.A.C. (Servicio Cruzero Suite VIP)',
                'categoria_id' => $categorias['transporte']->id,
                'ruc' => '20100227468',
                'direccion' => 'Av. Javier Prado Este 1109, La Victoria, Lima',
                'telefono' => '+51 1 3115050',
                'correo' => 'corporativo@cruzdelsur.com.pe',
                'detalles' => 'Línea de autobuses interprovinciales premium con asientos reclinables a 180° (cama suite), pantallas individuales y servicio de azafata a bordo (Lima - Paracas - Arequipa - Puno).',
            ],
            [
                'nombre' => 'Andean Luxury Sprinters Cusco',
                'categoria_id' => $categorias['transporte']->id,
                'ruc' => '20605912831',
                'direccion' => 'Calle Huáscar 220, Wanchaq, Cusco',
                'telefono' => '+51 84 251920',
                'correo' => 'logistica@andeanluxurysprinters.pe',
                'detalles' => 'Flota moderna de Mercedes-Benz Sprinter 516 VIP configuradas con 10 a 15 butacas de cuero extra ancha, enchufes USB, wifi satelital y nevera con bebidas frías.',
            ],

            // --- GUÍAS OFICIALES DE TURISMO (Asociaciones y Guías Profesionales Independientes) ---
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
            [
                'nombre' => 'Lic. Juan Carlos Huamán Quispe (Guía Arqueológico)',
                'categoria_id' => $categorias['guia']->id,
                'ruc' => '10428910293',
                'direccion' => 'Calle Saphy 450, Centro Histórico, Cusco',
                'telefono' => '+51 984 123 456',
                'correo' => 'jchuaman.guia@andeanexpert.pe',
                'detalles' => 'Guía Oficial de Turismo colegiado (DIRCETUR 0418). Especialista en Arqueología Inca, Astronomía Sagrada y la Llaqta de Machu Picchu. Bilingüe español/inglés.',
            ],
            [
                'nombre' => 'Lic. Carmen Rosa Ttito Condori (Guía Valle Sagrado)',
                'categoria_id' => $categorias['guia']->id,
                'ruc' => '10459810231',
                'direccion' => 'Av. Ferrocarril 112, Urubamba, Valle Sagrado',
                'telefono' => '+51 984 654 321',
                'correo' => 'carmenttito.guia@andeanexpert.pe',
                'detalles' => 'Guía Oficial de Turismo colegiada. Especialista en flora nativa, orquídeas, etnobotánica y comunidades textiles del Valle Sagrado (Chinchero y Pisac). Bilingüe español/inglés/quechua.',
            ],
            [
                'nombre' => 'Lic. Mateo Yupanqui Escalante (Guía Alta Montaña & Trekking)',
                'categoria_id' => $categorias['guia']->id,
                'ruc' => '10471239812',
                'direccion' => 'Calle Tandapata 320, San Blas, Cusco',
                'telefono' => '+51 984 789 012',
                'correo' => 'mateoyupanqui.trek@andeanexpert.pe',
                'detalles' => 'Guía de Trekking y Aventura con certificación internacional WFR (Wilderness First Responder). Especialista en rutas Salkantay, Camino Inca y Montaña de 7 Colores.',
            ],
            [
                'nombre' => 'Lic. Lucía Morales Benavides (Historiadora Virreinal)',
                'categoria_id' => $categorias['guia']->id,
                'ruc' => '10419082341',
                'direccion' => 'Plazoleta Las Nazarenas 180, Cusco',
                'telefono' => '+51 984 345 678',
                'correo' => 'luciamorales.art@andeanexpert.pe',
                'detalles' => 'Licenciada en Historia del Arte por la UNSAAC. Especialista en pintura de la Escuela Cusqueña virreinal, arquitectura colonial de la Catedral y templos del valle.',
            ],
            [
                'nombre' => 'Lic. Renzo Valdivia Chávez (Sommelier & Guía Gastronómico)',
                'categoria_id' => $categorias['guia']->id,
                'ruc' => '10408192301',
                'direccion' => 'Av. Larco 743, Miraflores, Lima',
                'telefono' => '+51 991 234 567',
                'correo' => 'renzovaldivia.food@andeanexpert.pe',
                'detalles' => 'Sommelier profesional y guía gastronómico oficial en Lima. Experto en cocina de costa, sierra y selva, visitas privadas a mercados tradicionales y catas de pisco.',
            ],
            [
                'nombre' => 'Lic. Sonia Mamani Choque (Guía Lago Titicaca)',
                'categoria_id' => $categorias['guia']->id,
                'ruc' => '10461238910',
                'direccion' => 'Jr. Lima 340, Centro, Puno',
                'telefono' => '+51 951 890 123',
                'correo' => 'soniamamani.titicaca@andeanexpert.pe',
                'detalles' => 'Guía Oficial de Turismo en el Altiplano y Lago Titicaca. Especialista en la cultura milenaria de los Uros, arte textil de la Isla Taquile y yacimiento de Sillustani.',
            ],

            // --- RESTAURANTES DE ALTA COCINA Y EXPERIENCIAS GASTRONÓMICAS ---
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
                'detalles' => 'Ubicado en el patio de cristal del museo colonial de Las Nazarenas. Cocina de autor de alta precisión ejecutada por reconocidos chefs cusqueños.',
            ],
            [
                'nombre' => 'Morena Peruvian Kitchen - Cusco',
                'categoria_id' => $categorias['restaurante']->id,
                'ruc' => '20601928391',
                'direccion' => 'Calle Plateros 348, a pasos de la Plaza de Armas, Cusco',
                'telefono' => '+51 84 437832',
                'correo' => 'reservas@morenaperuviankitchen.com',
                'detalles' => 'Fusión contemporánea de las 3 regiones del Perú en un ambiente moderno y vibrante. Destacada coctelería de autor andina.',
            ],
            [
                'nombre' => 'Tinkuy Restaurant at Belmond Sanctuary Lodge',
                'categoria_id' => $categorias['restaurante']->id,
                'ruc' => '20381920192',
                'direccion' => 'Carretera Hiram Bingham Km 7.5, Llaqta de Machu Picchu',
                'telefono' => '+51 84 984812300',
                'correo' => 'tinkuy@belmond.com',
                'detalles' => 'El único restaurante contiguo al ingreso a Machu Picchu. Almuerzo buffet gourmet internacional y peruano con vista a la selva alta.',
            ],
            [
                'nombre' => 'El Huacatay (Cocina del Valle Sagrado)',
                'categoria_id' => $categorias['restaurante']->id,
                'ruc' => '20490182312',
                'direccion' => 'Jr. Arequipa 130, Urubamba, Valle Sagrado',
                'telefono' => '+51 84 201790',
                'correo' => 'reservas@elhuacatay.com',
                'detalles' => 'Templo gastronómico fundado por el chef Pío Vásquez. Cocina creativa y de autor con insumos orgánicos cosechados en huertos locales del valle.',
            ],
            [
                'nombre' => 'La Rosa Náutica (Miraflores, Lima)',
                'categoria_id' => $categorias['restaurante']->id,
                'ruc' => '20100412891',
                'direccion' => 'Espigón 4 Circuito de Playas, Miraflores, Lima',
                'telefono' => '+51 1 4450149',
                'correo' => 'reservas@larosanautica.com',
                'detalles' => 'Restaurante icónico construido sobre las olas del Océano Pacífico al final del muelle de Miraflores. Especialidad en mariscos y pescados de línea.',
            ],
            [
                'nombre' => 'Zig Zag Restaurante (Arequipa)',
                'categoria_id' => $categorias['restaurante']->id,
                'ruc' => '20459812901',
                'direccion' => 'Calle Zela 210, frente a la Plaza San Francisco, Arequipa',
                'telefono' => '+51 54 206020',
                'correo' => 'reservas@zigzagrestaurant.com',
                'detalles' => 'Alquimia gastronómica andina con cocción sobre piedras volcánicas calientes traídas de las canteras del volcán Misti (carnes de alpaca, trucha y res).',
            ],

            // --- BOLETOS DE ENTRADA Y PARQUES NACIONALES ---
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
                'nombre' => 'COSITUC (Comité del Boleto Turístico del Cusco)',
                'categoria_id' => $categorias['entradas']->id,
                'ruc' => '20178391204',
                'direccion' => 'Av. El Sol 103, Galerías Turísticas, Cusco',
                'telefono' => '+51 84 227037',
                'correo' => 'informes@cosituc.gob.pe',
                'detalles' => 'Emisor del Boleto Turístico del Cusco (BTC Integral) que da acceso a 16 sitios arqueológicos: Sacsayhuamán, Qenqo, Puka Pukara, Tambomachay, Pisac, Ollantaytambo, Moray, Chinchero y museos.',
            ],
            [
                'nombre' => 'Convento de Santo Domingo - Qorikancha',
                'categoria_id' => $categorias['entradas']->id,
                'ruc' => '20119830291',
                'direccion' => 'Calle Ahuacpinta 659 / Plazoleta Santo Domingo, Cusco',
                'telefono' => '+51 84 222071',
                'correo' => 'administracion@qorikancha.pe',
                'detalles' => 'Templo del Sol Incaico, máxima joya de la ingeniería inca y convento virreinal dominico.',
            ],
            [
                'nombre' => 'Arzobispado del Cusco (Circuito Religioso)',
                'categoria_id' => $categorias['entradas']->id,
                'ruc' => '20148920193',
                'direccion' => 'Calle Hatun Rumiyoc s/n, Palacio Arzobispal, Cusco',
                'telefono' => '+51 84 225211',
                'correo' => 'museos@arzobispadocusco.pe',
                'detalles' => 'Boleto religioso oficial para la Catedral Basílica del Cusco, Iglesia del Triunfo, Templo de San Blas, San Cristóbal y Museo de Arte Religioso.',
            ],
            [
                'nombre' => 'Comunidad Campesina de Maras (Salineras de Maras)',
                'categoria_id' => $categorias['entradas']->id,
                'ruc' => '20490112890',
                'direccion' => 'Plaza Principal s/n, Maras, Urubamba, Cusco',
                'telefono' => '+51 84 581290',
                'correo' => 'comunidad@salinerasdemaras.pe',
                'detalles' => 'Derecho comunal de ingreso y conservación a los más de 3,000 pozos ancestrales de extracción artesanal de sal rosada de Maras.',
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
            [
                'nombre' => 'AUTOCOLCA (Boleto Cañón del Colca)',
                'categoria_id' => $categorias['entradas']->id,
                'ruc' => '20391028391',
                'direccion' => 'Calle Puente Grau 116, Cercado, Arequipa',
                'telefono' => '+51 54 203000',
                'correo' => 'ventas@colcaperu.gob.pe',
                'detalles' => 'Autoridad Autónoma del Colca. Emisora del Boleto Turístico del Cañón del Colca para la Cruz del Cóndor, miradores y pueblos tradicionales.',
            ],

            // --- AEROLÍNEAS ---
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

        // 3. Hoteles de lujo en Perú (Cusco, Valle Sagrado, Machu Picchu, Lima, Puno, Arequipa)
        $hotelesData = [
            [
                'nombre' => 'Belmond Hotel Monasterio',
                'ruc' => '20101037812',
                'direccion' => 'Calle Palacios 136, Plazoleta Las Nazarenas, Cusco, Perú',
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
                'direccion' => 'Carretera Hiram Bingham Km 7.5, Machu Picchu Pueblo, Cusco, Perú',
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
                'nombre' => 'Belmond Palacio Nazarenas',
                'ruc' => '20101037819',
                'direccion' => 'Plaza Las Nazarenas 223, Centro Histórico, Cusco, Perú',
                'telefono' => '+51 84 582222',
                'correo' => 'reservas.naz@belmond.com',
                'detalles' => 'Antiguo convento y palacio colonial. Exclusivo hotel suites-only con piscina al aire libre temperada, oxígeno suplementario en suite y mayordomo privado dedicado.',
                'habitaciones' => [
                    ['tipo' => 'Junior Suite Nazarenas con Oxígeno', 'capacidad' => 2],
                    ['tipo' => 'Studio Suite Virreinal con Balcón', 'capacidad' => 2],
                    ['tipo' => 'Palacio Suite Presidencial', 'capacidad' => 2],
                ],
            ],
            [
                'nombre' => 'Tambo del Inka, a Luxury Collection Resort & Spa',
                'ruc' => '20490192831',
                'direccion' => 'Av. Ferrocarril s/n, Urubamba, Valle Sagrado, Cusco, Perú',
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
                'direccion' => 'Calle Ruinas 432 esq. San Agustín, Centro Histórico, Cusco, Perú',
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
                'direccion' => 'Km 110 Vía Férrea, Aguas Calientes, Machu Picchu, Cusco, Perú',
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
                'nombre' => 'Sumaq Machu Picchu Hotel',
                'ruc' => '20490819201',
                'direccion' => 'Av. Hermanos Ayar Mz 1 Lote 3, Aguas Calientes, Machu Picchu, Cusco, Perú',
                'telefono' => '+51 84 211059',
                'correo' => 'reservas@sumaqhotelperu.com',
                'detalles' => 'Hotel 5 estrellas de lujo vivencial frente al río Vilcanota. Clases privadas de cocina peruana, ceremonias de pago a la tierra con chamán y spa andino.',
                'habitaciones' => [
                    ['tipo' => 'Deluxe King con Vista al Río Vilcanota', 'capacidad' => 2],
                    ['tipo' => 'Junior Suite Sumaq con Hidromasaje', 'capacidad' => 2],
                    ['tipo' => 'Imperial Suite con Terraza Privada', 'capacidad' => 2],
                ],
            ],
            [
                'nombre' => 'Aranwa Sacred Valley Hotel & Wellness',
                'ruc' => '20490123891',
                'direccion' => 'Antigua Hacienda Yaravilca s/n, Huayllabamba, Urubamba, Valle Sagrado, Perú',
                'telefono' => '+51 84 581900',
                'correo' => 'reservas@aranwahotels.com',
                'detalles' => 'Hacienda colonial del siglo XVII restaurada a orillas del río Vilcanota. Alberga el centro de bienestar y spa de 2,500 m² más completo de los Andes peruanos.',
                'habitaciones' => [
                    ['tipo' => 'Habitación Colonial Deluxe', 'capacidad' => 2],
                    ['tipo' => 'Junior Suite Colonial con Jacuzzi', 'capacidad' => 2],
                    ['tipo' => 'River Suite con Chimenea a Leña', 'capacidad' => 3],
                ],
            ],
            [
                'nombre' => 'Casa Andina Premium Cusco',
                'ruc' => '20436814329',
                'direccion' => 'Plazoleta Limacpampa Chico 473, Centro Histórico, Cusco, Perú',
                'telefono' => '+51 84 233661',
                'correo' => 'reservascusco@casa-andina.com',
                'detalles' => 'Casona colonial del siglo XVII con tres claustros de piedra y teja andina. Tradición cusqueña y servicio de alta calidad con restaurante Alma Bar.',
                'habitaciones' => [
                    ['tipo' => 'Superior King Tradición', 'capacidad' => 2],
                    ['tipo' => 'Suite Colonial con Sala Independiente', 'capacidad' => 2],
                ],
            ],
            [
                'nombre' => 'Miraflores Park, A Belmond Hotel',
                'ruc' => '20101037815',
                'direccion' => 'Malecón de la Reserva 1035, Miraflores, Lima, Perú',
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
                'direccion' => 'Península de Chucuito, Puno, Lago Titicaca, Perú',
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
                'direccion' => 'Fundo Huanca s/n, Yanque, Cañón del Colca, Arequipa, Perú',
                'telefono' => '+51 54 202580',
                'correo' => 'reservas@colca-lodge.com',
                'detalles' => 'Refugio campestre en el fondo del cañón del Colca con pozas termales privadas alimentadas por fuentes geotérmicas naturales a orillas del río.',
                'habitaciones' => [
                    ['tipo' => 'Suite Adobe con Termas Privadas', 'capacidad' => 2],
                    ['tipo' => 'Habitación Matrimonial Panorámica', 'capacidad' => 2],
                ],
            ],
        ];

        // Limpiar proveedores demo anteriores
        Proveedor::where('nombre', 'like', 'DEMO%')->delete();

        foreach ($hotelesData as $hd) {
            $habitaciones = $hd['habitaciones'];
            unset($hd['habitaciones']);

            // Crear o vincular su ficha de proveedor (que es el hotel centralizado)
            $proveedorHotel = Proveedor::updateOrCreate(
                ['nombre' => $hd['nombre']],
                [
                    'categoria_id' => $categorias['hoteles']->id,
                    'ruc' => $hd['ruc'],
                    'direccion' => $hd['direccion'],
                    'telefono' => $hd['telefono'],
                    'correo' => $hd['correo'],
                    'detalles' => $hd['detalles'],
                    'img' => $hd['img'] ?? null,
                ]
            );

            foreach ($habitaciones as $hab) {
                Habitacion::firstOrCreate(
                    ['proveedor_id' => $proveedorHotel->id, 'tipo' => $hab['tipo']],
                    ['capacidad' => $hab['capacidad']]
                );
            }
        }
    }
}
