<?php

namespace Database\Seeders;

use App\Models\Aceptacion;
use App\Models\Agente;
use App\Models\Anio;
use App\Models\Cliente;
use App\Models\Habitacion;
use App\Models\HabitacionFecha;
use App\Models\Hotel;
use App\Models\Mes;
use App\Models\Notificacion;
use App\Models\Pago;
use App\Models\Pax;
use App\Models\Programa;
use App\Models\ProgramaDia;
use App\Models\Proveedor;
use App\Models\ProveedorPago;
use App\Models\Servicio;
use App\Models\ServicioEvento;
use App\Models\Tarea;
use App\Support\AlojamientoEnServicios;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramaSeeder extends Seeder
{
    /**
     * Llena el sistema con itinerarios, cotizaciones y reservas de lujo 100% realistas
     * con marcas peruanas reconocidas, clientes de alta gama, servicios y operaciones completas.
     * Cubre todas las etapas del ciclo de vida del programa:
     * - Cotización (Borrador comercial)
     * - Confirmado con reservas por solicitar / solicitadas (Bandeja de Reservas)
     * - Solicitud de pago a contabilidad (Cuentas por pagar)
     * - En curso hoy (Hoja de Operaciones activa)
     * - Finalizado con facturas pendientes (Liquidación de proveedores)
     * - CERRADO Y AUDITADO (Fin del ciclo en Contabilidad con margen real verificado)
     * - Plantillas reutilizables
     */
    public function run(): void
    {
        // Limpiar registros previos de programas y tablas hijas
        $idsPrograma = Programa::withoutGlobalScope('sin_plantillas')->pluck('id');
        if ($idsPrograma->isNotEmpty()) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Aceptacion::whereIn('programa_id', $idsPrograma)->delete();
            Notificacion::whereIn('programa_id', $idsPrograma)->delete();
            Pago::whereIn('programa_id', $idsPrograma)->delete();
            Tarea::whereIn('programa_id', $idsPrograma)->delete();
            ProgramaDia::whereIn('programa_id', $idsPrograma)->delete();
            HabitacionFecha::whereIn('programa_id', $idsPrograma)->delete();
            Pax::whereIn('programa_id', $idsPrograma)->delete();

            $serviciosIds = Servicio::whereIn('programa_id', $idsPrograma)->pluck('id');
            if ($serviciosIds->isNotEmpty()) {
                ProveedorPago::whereIn('servicio_id', $serviciosIds)->delete();
                ServicioEvento::whereIn('servicio_id', $serviciosIds)->delete();
                Servicio::whereIn('id', $serviciosIds)->delete();
            }

            DB::table('hotel_programa')->whereIn('programa_id', $idsPrograma)->delete();
            DB::table('habitacion_programa')->whereIn('programa_id', $idsPrograma)->delete();
            DB::table('agente_programa')->whereIn('programa_id', $idsPrograma)->delete();
            Programa::withoutGlobalScope('sin_plantillas')->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // Limpiar clientes demo anteriores
        Cliente::where('email', 'like', '%@demo%')
            ->orWhere('nombre', 'like', 'Cliente demo%')
            ->orWhere('nombre', 'like', 'Agente demo%')
            ->delete();

        $meses = Mes::query()->orderBy('numero')->get()->keyBy('numero');
        $anios = Anio::query()->orderBy('anio')->get()->keyBy('anio');
        $agentes = Agente::all();
        $agenteDefault = $agentes->first();
        $usuarioAdmin = \App\Models\User::first();

        $proveedores = Proveedor::all()->keyBy('nombre');
        $hoteles = Hotel::with('habitaciones')->get()->keyBy('nombre');

        $hoy = Carbon::today();

        // -------------------------------------------------------------
        // Definición de programas de lujo auténticos
        // -------------------------------------------------------------
        $programasDef = [
            // 1. EN CURSO HOY (Día 3 de viaje - Experiencia Hiram Bingham & Machu Picchu)
            [
                'codigo' => 'AET-2026-0101',
                'nombre' => 'The Ultimate Peru Experience: Lima Gourmet, Andes & Belmond Hiram Bingham 8D/7N',
                'estado' => 'confirmado',
                'plantilla' => false,
                'inicio' => $hoy->copy()->subDays(2)->toDateString(),
                'fin' => $hoy->copy()->addDays(5)->toDateString(),
                'lang' => 'en',
                'precioAdulto' => '5900.00',
                'precioChild' => '0.00',
                'porcentaje_ganancia' => '20.00',
                'tipo_cambio' => '3.750',
                'suplemento_individual' => '0.00',
                'habitaciones_individuales' => 0,
                'llegada_vuelo' => 'LA2041 (LATAM)',
                'llegada_at' => $hoy->copy()->subDays(2)->setTime(9, 35),
                'salida_vuelo' => 'LA2022 (LATAM)',
                'salida_at' => $hoy->copy()->addDays(5)->setTime(16, 20),
                'cliente' => [
                    'tipo' => 'directo',
                    'nombre' => 'Dr. Arthur Kensington',
                    'empresa' => null,
                    'email' => 'arthur.kensington@post.harvard.edu',
                    'telefono' => '+1 617 555 0184',
                    'pais' => 'US',
                    'idioma' => 'en',
                    'origen' => 'web',
                    'notas' => 'Catedrático de Harvard. Viaje de aniversario con su esposa. Requieren habitaciones con oxígeno en Cusco y extrema precaución con alergia a frutos secos.',
                ],
                'hoteles' => ['Belmond Hotel Monasterio', 'Belmond Sanctuary Lodge'],
                'habitaciones' => [
                    ['hotel' => 'Belmond Hotel Monasterio', 'tipo' => 'Deluxe King con Oxígeno Enriquecido', 'noches' => 4, 'costo_noche' => 650.00],
                    ['hotel' => 'Belmond Sanctuary Lodge', 'tipo' => 'Mountain View Deluxe Room', 'noches' => 2, 'costo_noche' => 1650.00],
                ],
                'paxes' => [
                    [
                        'nombre' => 'Dr. Arthur Kensington',
                        'fecha_nacimiento' => '1964-05-14',
                        'sexo' => 'M',
                        'pais' => 'US',
                        'pasaporte' => 'US784910283',
                        'pasaporte_vencimiento' => '2030-08-20',
                        'dieta' => 'ninguna',
                        'alergias' => null,
                        'condiciones_medicas' => null,
                        'emergencia_nombre' => 'Sarah Kensington (Hija)',
                        'emergencia_telefono' => '+1 617 555 9931',
                    ],
                    [
                        'nombre' => 'Beatrice Kensington',
                        'fecha_nacimiento' => '1967-11-22',
                        'sexo' => 'F',
                        'pais' => 'US',
                        'pasaporte' => 'US884910492',
                        'pasaporte_vencimiento' => '2029-04-12',
                        'dieta' => 'vegetariana',
                        'alergias' => 'Alérgica severa a frutos secos / maní (lleva Epipen)',
                        'condiciones_medicas' => 'Leve hipertensión controlada.',
                        'emergencia_nombre' => 'Sarah Kensington (Hija)',
                        'emergencia_telefono' => '+1 617 555 9931',
                    ],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco, Traslado Privado & Aclimatación', 'desc' => 'Recepción VIP en sala de llegadas del aeropuerto Alejandro Velasco Astete con letrero de Andean Exclusive. Traslado privado en van Mercedes-Benz al Belmond Hotel Monasterio. Té de muña y mate de coca de bienvenida en el claustro colonial.'],
                    ['dia' => 1, 'titulo' => 'Cusco Imperial & Muros Megalíticos de Sacsayhuamán', 'desc' => 'Visita privada con arqueólogo oficial: Templo del Sol Qorikancha, Plaza de Armas y fortaleza ceremonial de Sacsayhuamán. Almuerzo degustación en Chicha por Gastón Acurio.'],
                    ['dia' => 2, 'titulo' => 'Tesoros del Valle Sagrado: Moray, Maras & Hacienda Huayoccari', 'desc' => 'Descenso al Valle Sagrado. Visita a las terrazas circulares de Moray y salineras ancestrales de Maras. Almuerzo campestre privado maridado con visita a la colección virreinal de la familia Lambarri en Hacienda Huayoccari.'],
                    ['dia' => 3, 'titulo' => 'Tren Belmond Hiram Bingham hacia Machu Picchu & Atardecer', 'desc' => 'Abordaje del exclusivo tren Belmond Hiram Bingham. Brunch gourmet y cocteles a base de pisco mientras se desciende por el cañón del Vilcanota. Check-in en Belmond Sanctuary Lodge junto a la ciudadela inca.'],
                    ['dia' => 4, 'titulo' => 'Amanecer en la Llaqta de Machu Picchu & Retorno', 'desc' => 'Ingreso preferente a primera hora a la Llaqta (Circuito 2 Panorámico) guiado por arqueólogo privado. Almuerzo en Tinkuy Restaurant y retorno vespertino en tren Hiram Bingham con cena gastronómica a bordo.'],
                    ['dia' => 5, 'titulo' => 'Arte Virreinal en San Blas & Tarde de Descanso', 'desc' => 'Recorrido por los talleres de maestros artesanos de San Blas (Mendívil y Mérida). Almuerzo íntimo en MAP Café del Museo de Arte Precolombino.'],
                    ['dia' => 6, 'titulo' => 'Día Libre de Spa & Cena de Gala en CICCIOLINA', 'desc' => 'Día para disfrutar de los servicios del hotel, compras de textilería fina de alpaca y cena de despedida en el salón privado de CICCIOLINA Tapas Bar.'],
                    ['dia' => 7, 'titulo' => 'Despedida de los Andes & Vuelo Internacional', 'desc' => 'Desayuno buffet colonial en el patio de los laureles. Traslado privado al aeropuerto de Cusco para su vuelo de conexión con Lima.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '10:00', 'tipo' => 'traslado', 'titulo' => 'Traslado VIP Aeropuerto Cusco → Belmond Hotel Monasterio', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'costo' => 45.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-TRANS-1001', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '09:00', 'tipo' => 'guia', 'titulo' => 'Visita Histórica Privada Qorikancha & Catedral de Cusco', 'prov' => 'Lic. Lucía Morales Benavides (Historiadora Virreinal)', 'costo' => 120.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-GUIA-2001', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '13:00', 'tipo' => 'comida', 'titulo' => 'Almuerzo Degustación en Chicha por Gastón Acurio', 'prov' => 'Chicha por Gastón Acurio - Cusco', 'costo' => 240.00, 'moneda' => 'PEN', 'por' => 'persona', 'confirmacion' => 'CF-REST-3001', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '08:30', 'tipo' => 'tour', 'titulo' => 'Excursión Privada Valle Sagrado en Mercedes Sprinter VIP', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'costo' => 210.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-TRANS-1002', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '09:30', 'tipo' => 'guia', 'titulo' => 'Guía Especialista Valle Sagrado & Comunidades Textiles', 'prov' => 'Lic. Carmen Rosa Ttito Condori (Guía Valle Sagrado)', 'costo' => 140.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-GUIA-2003', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '13:00', 'tipo' => 'comida', 'titulo' => 'Almuerzo Privado Maridado en Hacienda Huayoccari', 'prov' => 'Hacienda Huayoccari Restaurant & Private Museum', 'costo' => 85.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => 'CF-REST-3002', 'estado' => 'confirmado'],
                    ['dia' => 3, 'hora' => '09:05', 'tipo' => 'tren', 'titulo' => 'Tren Belmond Hiram Bingham Poroy → Machu Picchu', 'prov' => 'PeruRail S.A.', 'costo' => 580.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => 'TK-HB-98124', 'estado' => 'confirmado'],
                    ['dia' => 3, 'hora' => '13:30', 'tipo' => 'comida', 'titulo' => 'Almuerzo Buffet Gourmet en Tinkuy Sanctuary Lodge', 'prov' => 'Tinkuy Restaurant at Belmond Sanctuary Lodge', 'costo' => 55.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => 'TK-REST-501', 'estado' => 'confirmado'],
                    ['dia' => 4, 'hora' => '06:30', 'tipo' => 'entrada', 'titulo' => 'Boleto Oficial Llaqta Machu Picchu Circuito 2 Clásico', 'prov' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'costo' => 152.00, 'moneda' => 'PEN', 'por' => 'persona', 'confirmacion' => 'DDC-MP-849102', 'estado' => 'confirmado'],
                    ['dia' => 4, 'hora' => '07:00', 'tipo' => 'guia', 'titulo' => 'Guía Oficial Privado Arqueológico en Machu Picchu', 'prov' => 'Lic. Juan Carlos Huamán Quispe (Guía Arqueológico)', 'costo' => 160.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-GUIA-2002', 'estado' => 'confirmado'],
                    ['dia' => 4, 'hora' => '17:50', 'tipo' => 'tren', 'titulo' => 'Tren Belmond Hiram Bingham Machu Picchu → Poroy (Cena)', 'prov' => 'PeruRail S.A.', 'costo' => 580.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => 'TK-HB-98125', 'estado' => 'confirmado'],
                    ['dia' => 7, 'hora' => '13:00', 'tipo' => 'traslado', 'titulo' => 'Traslado Belmond Monasterio → Aeropuerto Cusco', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'costo' => 45.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-TRANS-1003', 'estado' => 'confirmado'],
                ],
                'pagos' => [
                    ['tipo' => 'deposito', 'monto' => 5900.00, 'dias_antes' => 90, 'metodo' => 'transferencia', 'ref' => 'SWIFT-JP-849102'],
                    ['tipo' => 'saldo', 'monto' => 5900.00, 'dias_antes' => 60, 'metodo' => 'transferencia', 'ref' => 'SWIFT-JP-910283'],
                ],
            ],

            // 2. EN CURSO HOY (Día 2 de viaje - Familia Suiza en Tambo del Inka)
            [
                'codigo' => 'AET-2026-0102',
                'nombre' => 'Imperio Incaico de Lujo: Cusco, Valle Sagrado & Machu Picchu 6D/5N',
                'estado' => 'confirmado',
                'plantilla' => false,
                'inicio' => $hoy->copy()->subDay()->toDateString(),
                'fin' => $hoy->copy()->addDays(4)->toDateString(),
                'lang' => 'en',
                'precioAdulto' => '4200.00',
                'precioChild' => '0.00',
                'porcentaje_ganancia' => '18.00',
                'tipo_cambio' => '3.750',
                'suplemento_individual' => '0.00',
                'habitaciones_individuales' => 0,
                'llegada_vuelo' => 'LA2015',
                'llegada_at' => $hoy->copy()->subDay()->setTime(11, 20),
                'salida_vuelo' => 'LA2038',
                'salida_at' => $hoy->copy()->addDays(4)->setTime(15, 45),
                'cliente' => [
                    'tipo' => 'directo',
                    'nombre' => 'Charles Dupont',
                    'empresa' => 'Geneva Private Wealth',
                    'email' => 'c.dupont@genevatrust.ch',
                    'telefono' => '+41 22 555 4910',
                    'pais' => 'CH',
                    'idioma' => 'en',
                    'origen' => 'web',
                    'notas' => 'Familia suiza de 3 pasajeros. Esposa e hija con celíaca severa (estricto gluten-free).',
                ],
                'hoteles' => ['Tambo del Inka, a Luxury Collection Resort & Spa', 'JW Marriott El Convento Cusco'],
                'habitaciones' => [
                    ['hotel' => 'Tambo del Inka, a Luxury Collection Resort & Spa', 'tipo' => 'Junior Suite Valle Sagrado', 'noches' => 3, 'costo_noche' => 720.00],
                    ['hotel' => 'JW Marriott El Convento Cusco', 'tipo' => 'Imperial Suite Virreinal', 'noches' => 2, 'costo_noche' => 880.00],
                ],
                'paxes' => [
                    ['nombre' => 'Charles Dupont', 'fecha_nacimiento' => '1974-03-10', 'sexo' => 'M', 'pais' => 'CH', 'pasaporte' => 'CH49201948', 'pasaporte_vencimiento' => '2031-06-15', 'dieta' => 'ninguna', 'alergias' => null, 'condiciones_medicas' => null, 'emergencia_nombre' => 'Henri Dupont', 'emergencia_telefono' => '+41 22 555 1100'],
                    ['nombre' => 'Sophia Dupont', 'fecha_nacimiento' => '1977-09-04', 'sexo' => 'F', 'pais' => 'CH', 'pasaporte' => 'CH49201949', 'pasaporte_vencimiento' => '2031-06-15', 'dieta' => 'sin_gluten', 'alergias' => 'Celiaquía severa (sin gluten / sin contaminación cruzada)', 'condiciones_medicas' => null, 'emergencia_nombre' => 'Henri Dupont', 'emergencia_telefono' => '+41 22 555 1100'],
                    ['nombre' => 'Laurent Dupont', 'fecha_nacimiento' => '2007-01-18', 'sexo' => 'F', 'pais' => 'CH', 'pasaporte' => 'CH49201950', 'pasaporte_vencimiento' => '2028-12-10', 'dieta' => 'sin_gluten', 'alergias' => 'Dieta sin gluten', 'condiciones_medicas' => null, 'emergencia_nombre' => 'Henri Dupont', 'emergencia_telefono' => '+41 22 555 1100'],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco & Descenso Directo a Valle Sagrado', 'desc' => 'Recepción en aeropuerto de Cusco y traslado en Sprinter privada directamente al Valle Sagrado (2,800 msnm) para mitigar el mal de altura. Registro en Tambo del Inka.'],
                    ['dia' => 1, 'titulo' => 'Mercado de Pisac, Complejo Arqueológico & Almuerzo en Huayoccari', 'desc' => 'Visita a la fortaleza inca de Pisac y terrazas de cultivo. Almuerzo gourmet adaptado 100% gluten-free en Hacienda Huayoccari.'],
                    ['dia' => 2, 'titulo' => 'Tren Panorámico Vistadome Observatory a Machu Picchu', 'desc' => 'Embarque en andén privado de Tambo del Inka en tren Vistadome Observatory. Recorrido con guía arqueólogo privado por el Circuito 2 de la Llaqta.'],
                    ['dia' => 3, 'titulo' => 'Salineras de Maras, Terrazas de Moray & Retorno a Cusco', 'desc' => 'Mañana de visita a las pozas de sal de Maras y andenería circular de Moray. Traslado al JW Marriott El Convento Cusco.'],
                    ['dia' => 4, 'titulo' => 'Cusco Histórico & Cena de Despedida en Qespi', 'desc' => 'Paseo privado por la Piedra de los Doce Ángulos, Qorikancha y cena de autor en Qespi Restaurant.'],
                    ['dia' => 5, 'titulo' => 'Traslado al Aeropuerto & Conexión a Lima', 'desc' => 'Desayuno con panadería artesanal sin gluten y traslado privado al aeropuerto.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '11:45', 'tipo' => 'traslado', 'titulo' => 'Traslado Aeropuerto Cusco → Tambo del Inka Urubamba', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'costo' => 65.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-TR-201', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '09:00', 'tipo' => 'guia', 'titulo' => 'Guía Oficial Bilingüe Día Completo Pisac & Valle Sagrado', 'prov' => 'Lic. Carmen Rosa Ttito Condori (Guía Valle Sagrado)', 'costo' => 140.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-GU-202', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '06:50', 'tipo' => 'tren', 'titulo' => 'Tren Vistadome Observatory Urubamba → Machu Picchu', 'prov' => 'PeruRail S.A.', 'costo' => 185.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => 'TK-VO-55102', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '09:30', 'tipo' => 'traslado', 'titulo' => 'Bus Oficial Consettur Aguas Calientes ↔ Llaqta Machu Picchu', 'prov' => 'Consettur Machu Picchu S.A.C.', 'costo' => 24.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => 'BUS-CON-4819', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '10:30', 'tipo' => 'entrada', 'titulo' => 'Entrada Llaqta Machu Picchu Circuito 2 Clásico (3 Pax)', 'prov' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'costo' => 152.00, 'moneda' => 'PEN', 'por' => 'persona', 'confirmacion' => 'DDC-MP-77401', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '10:45', 'tipo' => 'guia', 'titulo' => 'Guía Oficial Privado en Machu Picchu', 'prov' => 'Lic. Juan Carlos Huamán Quispe (Guía Arqueológico)', 'costo' => 160.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-GU-203', 'estado' => 'confirmado'],
                    ['dia' => 5, 'hora' => '13:00', 'tipo' => 'traslado', 'titulo' => 'Traslado Privado JW Marriott → Aeropuerto Cusco', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'costo' => 45.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-TR-204', 'estado' => 'confirmado'],
                ],
                'pagos' => [
                    ['tipo' => 'deposito', 'monto' => 6300.00, 'dias_antes' => 90, 'metodo' => 'tarjeta', 'ref' => 'VISA-CH-99201'],
                    ['tipo' => 'saldo', 'monto' => 6300.00, 'dias_antes' => 60, 'metodo' => 'tarjeta', 'ref' => 'VISA-CH-99342'],
                ],
            ],

            // 3. INICIA EN 3 DÍAS (Operaciones próximas - A&K Australia con saldo por cobrar)
            [
                'codigo' => 'AET-2026-0103',
                'nombre' => 'Sinfonía Andina: Tesoros del Cusco, Lago Titicaca & Titilaka 10D/9N',
                'estado' => 'confirmado',
                'plantilla' => false,
                'inicio' => $hoy->copy()->addDays(3)->toDateString(),
                'fin' => $hoy->copy()->addDays(12)->toDateString(),
                'lang' => 'en',
                'precioAdulto' => '6800.00',
                'precioChild' => '0.00',
                'porcentaje_ganancia' => '17.50',
                'tipo_cambio' => '3.750',
                'suplemento_individual' => '0.00',
                'habitaciones_individuales' => 0,
                'cliente' => [
                    'tipo' => 'agencia',
                    'nombre' => 'Charlotte Sterling',
                    'empresa' => 'Abercrombie & Kent Australia',
                    'email' => 'csterling@abercrombiekent.com.au',
                    'telefono' => '+61 2 9223 8810',
                    'pais' => 'AU',
                    'idioma' => 'en',
                    'origen' => 'agencia',
                    'notas' => 'Reserva B2B para viajeros australianos de alto poder adquisitivo. Requieren traslado terrestre privado Cusco - Puno con paradas en Andahuaylillas y Raqchi.',
                ],
                'hoteles' => ['JW Marriott El Convento Cusco', 'Inkaterra Machu Picchu Pueblo Hotel', 'Titilaka - Relais & Châteaux'],
                'habitaciones' => [
                    ['hotel' => 'JW Marriott El Convento Cusco', 'tipo' => 'Classic King con Oxigenación Controlada', 'noches' => 4, 'costo_noche' => 420.00],
                    ['hotel' => 'Inkaterra Machu Picchu Pueblo Hotel', 'tipo' => 'Superior Casita', 'noches' => 2, 'costo_noche' => 590.00],
                    ['hotel' => 'Titilaka - Relais & Châteaux', 'tipo' => 'Corner King Suite Lago Titicaca', 'noches' => 3, 'costo_noche' => 980.00],
                ],
                'paxes' => [
                    ['nombre' => 'Richard Montgomery', 'fecha_nacimiento' => '1959-07-28', 'sexo' => 'M', 'pais' => 'AU', 'pasaporte' => 'AU91823901', 'pasaporte_vencimiento' => '2028-10-14', 'dieta' => 'ninguna', 'alergias' => null, 'condiciones_medicas' => null, 'emergencia_nombre' => 'Liam Montgomery', 'emergencia_telefono' => '+61 4 1234 5678'],
                    ['nombre' => 'Evelyn Montgomery', 'fecha_nacimiento' => '1961-12-05', 'sexo' => 'F', 'pais' => 'AU', 'pasaporte' => 'AU91823902', 'pasaporte_vencimiento' => '2028-10-14', 'dieta' => 'ninguna', 'alergias' => null, 'condiciones_medicas' => null, 'emergencia_nombre' => 'Liam Montgomery', 'emergencia_telefono' => '+61 4 1234 5678'],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco & Bienvenida Imperial', 'desc' => 'Recepción y check-in en JW Marriott.'],
                    ['dia' => 1, 'titulo' => 'Cusco Esencial & Sacsayhuamán', 'desc' => 'Visita privada guiada con historiador colonial.'],
                    ['dia' => 2, 'titulo' => 'Valle Sagrado & Tren a Aguas Calientes', 'desc' => 'Ollantaytambo e ingreso a Inkaterra Pueblo Hotel.'],
                    ['dia' => 3, 'titulo' => 'Machu Picchu Ciudadela & Jardines de Orquídeas', 'desc' => 'Visita con arqueólogo privado.'],
                    ['dia' => 4, 'titulo' => 'Retorno a Cusco en Tren Panorámico', 'desc' => 'Tarde libre en el centro histórico.'],
                    ['dia' => 5, 'titulo' => 'Ruta del Sol Privada: De Cusco al Lago Titicaca', 'desc' => 'Viaje terrestre privado cruzando la cordillera hacia Puno. Paradas en la Capilla Sixtina de América (Andahuaylillas), Templo de Viracocha (Raqchi) y Paso de La Raya (4,335 msnm). Llegada a Titilaka Relais & Châteaux.'],
                    ['dia' => 6, 'titulo' => 'Islas Flotantes de los Uros & Comunidad de Taquile', 'desc' => 'Embarcación privada rápida de Titilaka por el Lago Titicaca con guía local colegiada.'],
                    ['dia' => 7, 'titulo' => 'Chullpas de Sillustani & Atardecer en la Península', 'desc' => 'Necrópolis preinca frente a la laguna de Umayo.'],
                    ['dia' => 8, 'titulo' => 'Día de Navegación en Kayak & Gastronomía Relais & Châteaux', 'desc' => 'Día de descanso y conexión con la naturaleza.'],
                    ['dia' => 9, 'titulo' => 'Traslado al Aeropuerto de Juliaca & Vuelo a Lima', 'desc' => 'Traslado privado al aeropuerto de Juliaca para retorno a Lima.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '10:30', 'tipo' => 'traslado', 'titulo' => 'Traslado VIP Aeropuerto Cusco → JW Marriott', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'costo' => 45.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-TR-301', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '11:15', 'tipo' => 'tren', 'titulo' => 'Tren Inca Rail The 360° Ollantaytambo → Machu Picchu', 'prov' => 'Inca Rail S.A.C.', 'costo' => 145.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => 'TK-IR-48190', 'estado' => 'confirmado'],
                    ['dia' => 3, 'hora' => '07:00', 'tipo' => 'entrada', 'titulo' => 'Boleto Machu Picchu Circuito 2 Clásico (2 Pax)', 'prov' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'costo' => 152.00, 'moneda' => 'PEN', 'por' => 'persona', 'confirmacion' => 'DDC-MP-99410', 'estado' => 'confirmado'],
                    ['dia' => 5, 'hora' => '08:00', 'tipo' => 'traslado', 'titulo' => 'Bus Guiado Primera Clase Ruta del Sol Cusco ↔ Puno con Almuerzo', 'prov' => 'Turismo Mer S.R.L. (Ruta del Sol Cusco - Puno)', 'costo' => 65.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => 'TK-MER-8819', 'estado' => 'confirmado'],
                    ['dia' => 6, 'hora' => '09:00', 'tipo' => 'guia', 'titulo' => 'Guía Privado Día Completo Islas Uros & Taquile en Lancha Veloz', 'prov' => 'Lic. Sonia Mamani Choque (Guía Lago Titicaca)', 'costo' => 120.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-TIT-551', 'estado' => 'confirmado'],
                ],
                'pagos' => [
                    ['tipo' => 'deposito', 'monto' => 6800.00, 'dias_antes' => 75, 'metodo' => 'transferencia', 'ref' => 'SWIFT-ANZ-48190'],
                    // Saldo pendiente para reflejar cuentas por cobrar activas
                ],
            ],

            // 4. INICIA EN 5 DÍAS (Luna de Miel - Servicios en POR SOLICITAR, SOLICITADO y PAGO SOLICITADO)
            [
                'codigo' => 'AET-2026-0104',
                'nombre' => 'Luna de Miel en los Andes: Suites de Ensueño & Atardecer en Vilcanota 6D/5N',
                'estado' => 'confirmado',
                'plantilla' => false,
                'inicio' => $hoy->copy()->addDays(5)->toDateString(),
                'fin' => $hoy->copy()->addDays(10)->toDateString(),
                'lang' => 'en',
                'precioAdulto' => '4800.00',
                'precioChild' => '0.00',
                'porcentaje_ganancia' => '22.00',
                'tipo_cambio' => '3.750',
                'suplemento_individual' => '0.00',
                'habitaciones_individuales' => 0,
                'cliente' => [
                    'tipo' => 'directo',
                    'nombre' => 'Oliver Vance',
                    'empresa' => 'Vance Partners UK',
                    'email' => 'oliver.vance@vancepartners.co.uk',
                    'telefono' => '+44 20 7946 0912',
                    'pais' => 'GB',
                    'idioma' => 'en',
                    'origen' => 'web',
                    'notas' => 'Viaje de novios. Solicitaron amenidad de bienvenida romántica (flores andinas, vino espumante y trufas de chocolate con sal de Maras).',
                ],
                'hoteles' => ['Belmond Hotel Monasterio', 'Belmond Sanctuary Lodge'],
                'habitaciones' => [
                    ['hotel' => 'Belmond Hotel Monasterio', 'tipo' => 'Junior Suite Colonial', 'noches' => 3, 'costo_noche' => 950.00],
                    ['hotel' => 'Belmond Sanctuary Lodge', 'tipo' => 'Mountain View Deluxe Room', 'noches' => 2, 'costo_noche' => 1650.00],
                ],
                'paxes' => [
                    ['nombre' => 'Oliver Vance', 'fecha_nacimiento' => '1992-06-18', 'sexo' => 'M', 'pais' => 'GB', 'pasaporte' => 'GB772910482', 'pasaporte_vencimiento' => '2032-11-20', 'dieta' => 'ninguna', 'alergias' => null, 'condiciones_medicas' => null, 'emergencia_nombre' => 'Henry Vance', 'emergencia_telefono' => '+44 20 7946 0001'],
                    ['nombre' => 'Eleanor Vance', 'fecha_nacimiento' => '1994-02-14', 'sexo' => 'F', 'pais' => 'GB', 'pasaporte' => 'GB772910483', 'pasaporte_vencimiento' => '2032-11-20', 'dieta' => 'vegetariana', 'alergias' => 'Vegetariana estricta', 'condiciones_medicas' => null, 'emergencia_nombre' => 'Henry Vance', 'emergencia_telefono' => '+44 20 7946 0001'],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco & Bienvenida Romántica en Monasterio', 'desc' => 'Arribo a Cusco y recepción con flores andinas y copa de champaña en Belmond Monasterio.'],
                    ['dia' => 1, 'titulo' => 'Valle Sagrado & Picnic Gourmet frente a los Nevados', 'desc' => 'Recorrido privado por Maras y Moray con almuerzo campestre privado preparado por chef exclusivo.'],
                    ['dia' => 2, 'titulo' => 'Tren Belmond Hiram Bingham hacia Machu Picchu', 'desc' => 'Viaje de lujo a bordo del Hiram Bingham y registro en Belmond Sanctuary Lodge.'],
                    ['dia' => 3, 'titulo' => 'Machu Picchu al Alba & Sesión Fotográfica Privada', 'desc' => 'Acceso prioritario a la Llaqta con fotógrafo profesional para retratos de recuerdo.'],
                    ['dia' => 4, 'titulo' => 'Retorno a Cusco & Cena Romántica a la Luz de las Velas en CICCIOLINA', 'desc' => 'Cena de degustación maridada en mesa reservada de CICCIOLINA.'],
                    ['dia' => 5, 'titulo' => 'Despedida de los Andes', 'desc' => 'Traslado al aeropuerto.'],
                ],
                'servicios' => [
                    // Confirmado con orden enviada
                    ['dia' => 0, 'hora' => '10:00', 'tipo' => 'traslado', 'titulo' => 'Traslado VIP Aeropuerto → Belmond Monasterio', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'costo' => 45.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-TR-401', 'estado' => 'confirmado'],
                    // Solicitado con PAGO SOLICITADO a Contabilidad (para probar la bandeja de pagos solicitados)
                    ['dia' => 2, 'hora' => '09:05', 'tipo' => 'tren', 'titulo' => 'Tren Belmond Hiram Bingham Poroy → Machu Picchu', 'prov' => 'PeruRail S.A.', 'costo' => 580.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => null, 'estado' => 'solicitado', 'pago_solicitado' => true],
                    // En estado 'solicitado' (esperando confirmación del hotel/restaurante)
                    ['dia' => 2, 'hora' => '13:30', 'tipo' => 'comida', 'titulo' => 'Almuerzo Buffet Gourmet en Tinkuy Sanctuary Lodge', 'prov' => 'Tinkuy Restaurant at Belmond Sanctuary Lodge', 'costo' => 55.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => null, 'estado' => 'solicitado'],
                    // En estado 'por_solicitar' (para que el equipo de Reservas pueda probar emitir órdenes)
                    ['dia' => 3, 'hora' => '06:00', 'tipo' => 'traslado', 'titulo' => 'Bus Oficial Consettur Aguas Calientes ↔ Llaqta Machu Picchu', 'prov' => 'Consettur Machu Picchu S.A.C.', 'costo' => 24.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => null, 'estado' => 'por_solicitar'],
                    ['dia' => 3, 'hora' => '06:30', 'tipo' => 'entrada', 'titulo' => 'Boletos Llaqta Machu Picchu Circuito 2 (2 Pax)', 'prov' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'costo' => 152.00, 'moneda' => 'PEN', 'por' => 'persona', 'confirmacion' => null, 'estado' => 'por_solicitar'],
                    ['dia' => 3, 'hora' => '07:00', 'tipo' => 'guia', 'titulo' => 'Guía Oficial Privado Arqueológico en Machu Picchu', 'prov' => 'Lic. Juan Carlos Huamán Quispe (Guía Arqueológico)', 'costo' => 160.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => null, 'estado' => 'solicitado'],
                    ['dia' => 4, 'hora' => '20:00', 'tipo' => 'comida', 'titulo' => 'Cena Gourmet de Tapas Andinas & Selección de Vinos de Altura', 'prov' => 'CICCIOLINA Tapas Bar & Restaurant', 'costo' => 75.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => 'CF-CIC-901', 'estado' => 'confirmado'],
                ],
                'pagos' => [
                    ['tipo' => 'deposito', 'monto' => 4800.00, 'dias_antes' => 90, 'metodo' => 'tarjeta', 'ref' => 'BARCLAY-UK-7729'],
                    ['tipo' => 'saldo', 'monto' => 4800.00, 'dias_antes' => 60, 'metodo' => 'tarjeta', 'ref' => 'BARCLAY-UK-7730'],
                ],
            ],

            // 5. COTIZACIÓN ABIERTA (Virtuoso USA - En etapa comercial)
            [
                'codigo' => 'AET-2026-0105',
                'nombre' => 'Amazonia Mística & Machu Picchu Sanctuary Lodge 9D/8N',
                'estado' => 'cotizacion',
                'plantilla' => false,
                'inicio' => $hoy->copy()->addMonths(2)->toDateString(),
                'fin' => $hoy->copy()->addMonths(2)->addDays(9)->toDateString(),
                'lang' => 'en',
                'precioAdulto' => '6400.00',
                'precioChild' => '0.00',
                'porcentaje_ganancia' => '20.00',
                'tipo_cambio' => '3.750',
                'suplemento_individual' => '0.00',
                'habitaciones_individuales' => 0,
                'cliente' => [
                    'tipo' => 'agencia',
                    'nombre' => 'Sarah Jenkins',
                    'empresa' => 'Virtuoso Travel Specialists NYC',
                    'email' => 'sjenkins@virtuosotravel.com',
                    'telefono' => '+1 212 555 0199',
                    'pais' => 'US',
                    'idioma' => 'en',
                    'origen' => 'agencia',
                    'notas' => 'Cotización solicitada por agencia Virtuoso. Cliente VIP exige Belmond Sanctuary Lodge y cena en Central.',
                ],
                'hoteles' => ['Miraflores Park, A Belmond Hotel', 'Belmond Sanctuary Lodge', 'Belmond Hotel Monasterio'],
                'habitaciones' => [
                    ['hotel' => 'Miraflores Park, A Belmond Hotel', 'tipo' => 'Ocean View Deluxe Junior Suite', 'noches' => 2, 'costo_noche' => 520.00],
                    ['hotel' => 'Belmond Sanctuary Lodge', 'tipo' => 'Mountain View Deluxe Room', 'noches' => 2, 'costo_noche' => 1650.00],
                    ['hotel' => 'Belmond Hotel Monasterio', 'tipo' => 'Deluxe King con Oxígeno Enriquecido', 'noches' => 4, 'costo_noche' => 650.00],
                ],
                'paxes' => [
                    ['nombre' => 'David Vance', 'fecha_nacimiento' => '1968-05-12', 'sexo' => 'M', 'pais' => 'US', 'pasaporte' => null, 'pasaporte_vencimiento' => null, 'dieta' => 'ninguna', 'alergias' => null, 'condiciones_medicas' => null, 'emergencia_nombre' => null, 'emergencia_telefono' => null],
                    ['nombre' => 'Susan Vance', 'fecha_nacimiento' => '1971-08-20', 'sexo' => 'F', 'pais' => 'US', 'pasaporte' => null, 'pasaporte_vencimiento' => null, 'dieta' => 'ninguna', 'alergias' => null, 'condiciones_medicas' => null, 'emergencia_nombre' => null, 'emergencia_telefono' => null],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Lima & Registro en Miraflores Park', 'desc' => 'Recepción ejecutiva y noche frente al Pacífico.'],
                    ['dia' => 1, 'titulo' => 'Lima Gastronómica & Cena en Central Restaurante', 'desc' => 'Experiencia Alturas Mater de Virgilio Martínez.'],
                    ['dia' => 2, 'titulo' => 'Vuelo a Cusco & Aclimatación en Monasterio', 'desc' => 'Vuelo matutino y relax en patio virreinal.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '18:00', 'tipo' => 'traslado', 'titulo' => 'Traslado VIP Aeropuerto Lima → Miraflores Park', 'prov' => 'Lima Premium Chauffeur & Transfers', 'costo' => 55.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => null, 'estado' => 'por_solicitar'],
                    ['dia' => 1, 'hora' => '19:30', 'tipo' => 'comida', 'titulo' => 'Cena Degustación en Central Restaurante (Mater)', 'prov' => 'Central Restaurante (Virgilio Martínez & Pía León)', 'costo' => 1250.00, 'moneda' => 'PEN', 'por' => 'persona', 'confirmacion' => null, 'estado' => 'por_solicitar'],
                ],
                'pagos' => [],
            ],

            // 6. COTIZACIÓN NUEVA (Lord Alistair & Lady Claire Hamilton - Escocia)
            [
                'codigo' => 'AET-2026-0107',
                'nombre' => 'Aniversario de Plata: Perú Imperial, Belmond Palacio Nazarenas & Hiram Bingham 9D/8N',
                'estado' => 'cotizacion',
                'plantilla' => false,
                'inicio' => $hoy->copy()->addMonths(3)->toDateString(),
                'fin' => $hoy->copy()->addMonths(3)->addDays(9)->toDateString(),
                'lang' => 'en',
                'precioAdulto' => '7200.00',
                'precioChild' => '0.00',
                'porcentaje_ganancia' => '22.00',
                'tipo_cambio' => '3.750',
                'suplemento_individual' => '0.00',
                'habitaciones_individuales' => 0,
                'cliente' => [
                    'tipo' => 'directo',
                    'nombre' => 'Lord Alistair Hamilton',
                    'empresa' => 'Scott Holdings UK',
                    'email' => 'alistair.hamilton@scottholdings.co.uk',
                    'telefono' => '+44 20 7946 0812',
                    'pais' => 'GB',
                    'idioma' => 'en',
                    'origen' => 'web',
                    'notas' => 'Cliente VIP de Reino Unido. Interesado en servicio exclusivo con tren Hiram Bingham y Belmond Palacio Nazarenas.',
                ],
                'hoteles' => ['Belmond Palacio Nazarenas', 'Belmond Sanctuary Lodge'],
                'habitaciones' => [
                    ['hotel' => 'Belmond Palacio Nazarenas', 'tipo' => 'Junior Suite Nazarenas con Oxígeno', 'noches' => 5, 'costo_noche' => 850.00],
                    ['hotel' => 'Belmond Sanctuary Lodge', 'tipo' => 'Mountain View Deluxe Room', 'noches' => 2, 'costo_noche' => 1650.00],
                ],
                'paxes' => [
                    ['nombre' => 'Lord Alistair Hamilton', 'fecha_nacimiento' => '1961-07-19', 'sexo' => 'M', 'pais' => 'GB', 'pasaporte' => null, 'pasaporte_vencimiento' => null, 'dieta' => 'ninguna', 'alergias' => null, 'condiciones_medicas' => null, 'emergencia_nombre' => null, 'emergencia_telefono' => null],
                    ['nombre' => 'Lady Claire Hamilton', 'fecha_nacimiento' => '1964-12-02', 'sexo' => 'F', 'pais' => 'GB', 'pasaporte' => null, 'pasaporte_vencimiento' => null, 'dieta' => 'ninguna', 'alergias' => null, 'condiciones_medicas' => null, 'emergencia_nombre' => null, 'emergencia_telefono' => null],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco & Bienvenida en Palacio Nazarenas', 'desc' => 'Traslado y check-in.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '10:00', 'tipo' => 'traslado', 'titulo' => 'Traslado VIP Aeropuerto → Palacio Nazarenas', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'costo' => 45.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => null, 'estado' => 'por_solicitar'],
                ],
                'pagos' => [],
            ],

            // 7. FINALIZADO CON CUENTAS POR PAGAR PENDIENTES (Semana Santa en Colca - Jean-Paul Bernard)
            [
                'codigo' => 'AET-2026-0040',
                'nombre' => 'Semana Santa en Cusco & Cañón del Colca 8D/7N',
                'estado' => 'confirmado',
                'cerrado' => false,
                'plantilla' => false,
                'inicio' => '2026-04-06',
                'fin' => '2026-04-13',
                'lang' => 'en',
                'precioAdulto' => '5400.00',
                'precioChild' => '0.00',
                'porcentaje_ganancia' => '20.00',
                'tipo_cambio' => '3.750',
                'suplemento_individual' => '0.00',
                'habitaciones_individuales' => 0,
                'cliente' => [
                    'tipo' => 'directo',
                    'nombre' => 'Jean-Paul & Monique Bernard',
                    'empresa' => null,
                    'email' => 'jp.bernard@lyon-avocats.fr',
                    'telefono' => '+33 4 72 00 11 22',
                    'pais' => 'FR',
                    'idioma' => 'en',
                    'origen' => 'web',
                ],
                'hoteles' => ['Colca Lodge Spa & Hot Springs', 'JW Marriott El Convento Cusco'],
                'habitaciones' => [
                    ['hotel' => 'Colca Lodge Spa & Hot Springs', 'tipo' => 'Suite Adobe con Termas Privadas', 'noches' => 3, 'costo_noche' => 450.00],
                    ['hotel' => 'JW Marriott El Convento Cusco', 'tipo' => 'Classic King con Oxigenación Controlada', 'noches' => 4, 'costo_noche' => 420.00],
                ],
                'paxes' => [
                    ['nombre' => 'Jean-Paul Bernard', 'fecha_nacimiento' => '1958-02-17', 'sexo' => 'M', 'pais' => 'FR', 'pasaporte' => 'FRA77391028', 'pasaporte_vencimiento' => '2029-11-12', 'dieta' => 'ninguna', 'alergias' => null, 'condiciones_medicas' => null, 'emergencia_nombre' => 'Luc Bernard', 'emergencia_telefono' => '+33 6 12 34 56 78'],
                    ['nombre' => 'Monique Bernard', 'fecha_nacimiento' => '1961-09-03', 'sexo' => 'F', 'pais' => 'FR', 'pasaporte' => 'FRA77391029', 'pasaporte_vencimiento' => '2029-11-12', 'dieta' => 'ninguna', 'alergias' => null, 'condiciones_medicas' => null, 'emergencia_nombre' => 'Luc Bernard', 'emergencia_telefono' => '+33 6 12 34 56 78'],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Arequipa & Ruta al Cañón del Colca', 'desc' => 'Traslado y registro en Colca Lodge.'],
                    ['dia' => 1, 'titulo' => 'Cruz del Cóndor & Termas Privadas', 'desc' => 'Vuelo de los cóndores.'],
                ],
                'servicios' => [
                    // Servicios confirmados pero con pendiente_pago => true para que aparezcan en Cuentas por pagar VENCIDAS
                    ['dia' => 0, 'hora' => '10:00', 'tipo' => 'traslado', 'titulo' => 'Traslado Privado Aeropuerto Arequipa → Colca Lodge', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'costo' => 180.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-COL-01', 'estado' => 'confirmado', 'pendiente_pago' => true],
                    ['dia' => 1, 'hora' => '07:00', 'tipo' => 'entrada', 'titulo' => 'Boleto Turístico del Cañón del Colca (AUTOCOLCA)', 'prov' => 'AUTOCOLCA (Boleto Cañón del Colca)', 'costo' => 70.00, 'moneda' => 'PEN', 'por' => 'persona', 'confirmacion' => 'AUTO-7819', 'estado' => 'confirmado', 'pendiente_pago' => true],
                ],
                'pagos' => [
                    ['tipo' => 'deposito', 'monto' => 5400.00, 'dias_antes' => 60, 'metodo' => 'tarjeta', 'ref' => 'BNP-FR-0401'],
                    ['tipo' => 'saldo', 'monto' => 5400.00, 'dias_antes' => 30, 'metodo' => 'tarjeta', 'ref' => 'BNP-FR-0402'],
                ],
            ],

            // 8. PROGRAMA 100% CERRADO Y AUDITADO (El ejemplo de cómo termina la gestión en Contabilidad)
            [
                'codigo' => 'AET-2026-0080',
                'nombre' => 'Perú Gastronómico & Arqueológico de Lujo 8D/7N',
                'estado' => 'confirmado',
                'cerrado' => true,
                'plantilla' => false,
                'inicio' => '2026-08-10',
                'fin' => '2026-08-17',
                'lang' => 'en',
                'precioAdulto' => '5900.00',
                'precioChild' => '0.00',
                'porcentaje_ganancia' => '24.15',
                'tipo_cambio' => '3.750',
                'suplemento_individual' => '0.00',
                'habitaciones_individuales' => 0,
                'cliente' => [
                    'tipo' => 'directo',
                    'nombre' => 'Thomas Becker',
                    'empresa' => 'Becker Holding GmbH',
                    'email' => 't.becker@becker-holding.de',
                    'telefono' => '+49 89 3810 4420',
                    'pais' => 'DE',
                    'idioma' => 'en',
                    'origen' => 'web',
                    'notas' => 'Viajero gourmet de Alemania. 100% liquidado y auditado con margen neto cerrado.',
                ],
                'hoteles' => ['Miraflores Park, A Belmond Hotel', 'Tambo del Inka, a Luxury Collection Resort & Spa', 'Belmond Hotel Monasterio'],
                'habitaciones' => [
                    ['hotel' => 'Miraflores Park, A Belmond Hotel', 'tipo' => 'Ocean View Deluxe Junior Suite', 'noches' => 2, 'costo_noche' => 520.00],
                    ['hotel' => 'Tambo del Inka, a Luxury Collection Resort & Spa', 'tipo' => 'Deluxe King con Balcón al Río Vilcanota', 'noches' => 3, 'costo_noche' => 480.00],
                    ['hotel' => 'Belmond Hotel Monasterio', 'tipo' => 'Deluxe King con Oxígeno Enriquecido', 'noches' => 2, 'costo_noche' => 650.00],
                ],
                'paxes' => [
                    ['nombre' => 'Thomas Becker', 'fecha_nacimiento' => '1970-11-05', 'sexo' => 'M', 'pais' => 'DE', 'pasaporte' => 'DEU66391028', 'pasaporte_vencimiento' => '2031-01-20', 'dieta' => 'ninguna', 'alergias' => null, 'condiciones_medicas' => null, 'emergencia_nombre' => 'Brigitte Becker', 'emergencia_telefono' => '+49 89 3810 4000'],
                    ['nombre' => 'Brigitte Becker', 'fecha_nacimiento' => '1973-04-18', 'sexo' => 'F', 'pais' => 'DE', 'pasaporte' => 'DEU66391029', 'pasaporte_vencimiento' => '2031-01-20', 'dieta' => 'ninguna', 'alergias' => null, 'condiciones_medicas' => null, 'emergencia_nombre' => 'Brigitte Becker', 'emergencia_telefono' => '+49 89 3810 4000'],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Lima & Miraflores Park', 'desc' => 'Recepción y traslado.'],
                    ['dia' => 1, 'titulo' => 'Cena en Central Restaurante (Mater)', 'desc' => 'Menú degustación 14 pasos.'],
                    ['dia' => 2, 'titulo' => 'Cusco Imperial & Monasterio', 'desc' => 'Arribo a la capital inca.'],
                    ['dia' => 3, 'titulo' => 'Valle Sagrado & Almuerzo en Huayoccari', 'desc' => 'Maras y Moray.'],
                    ['dia' => 4, 'titulo' => 'Machu Picchu en Hiram Bingham', 'desc' => 'Visita con arqueólogo privado.'],
                    ['dia' => 7, 'titulo' => 'Retorno a Lima & Vuelo Internacional', 'desc' => 'Fin del viaje.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '18:00', 'tipo' => 'traslado', 'titulo' => 'Traslado VIP Aeropuerto Lima → Miraflores Park', 'prov' => 'Lima Premium Chauffeur & Transfers', 'costo' => 55.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-LIM-01', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '19:30', 'tipo' => 'comida', 'titulo' => 'Experiencia Alturas Mater en Central Restaurante', 'prov' => 'Central Restaurante (Virgilio Martínez & Pía León)', 'costo' => 1250.00, 'moneda' => 'PEN', 'por' => 'persona', 'confirmacion' => 'CF-MATER-01', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '10:00', 'tipo' => 'traslado', 'titulo' => 'Traslado VIP Aeropuerto Cusco → Belmond Monasterio', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'costo' => 45.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-CUZ-02', 'estado' => 'confirmado'],
                    ['dia' => 3, 'hora' => '09:00', 'tipo' => 'guia', 'titulo' => 'Guía Especialista Valle Sagrado & Comunidades', 'prov' => 'Lic. Carmen Rosa Ttito Condori (Guía Valle Sagrado)', 'costo' => 140.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-GU-03', 'estado' => 'confirmado'],
                    ['dia' => 3, 'hora' => '13:00', 'tipo' => 'comida', 'titulo' => 'Almuerzo Campestre en Hacienda Huayoccari', 'prov' => 'Hacienda Huayoccari Restaurant & Private Museum', 'costo' => 85.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => 'CF-HUA-03', 'estado' => 'confirmado'],
                    ['dia' => 4, 'hora' => '09:05', 'tipo' => 'tren', 'titulo' => 'Tren Belmond Hiram Bingham Poroy ↔ Machu Picchu', 'prov' => 'PeruRail S.A.', 'costo' => 580.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => 'TK-HB-8810', 'estado' => 'confirmado'],
                    ['dia' => 4, 'hora' => '10:30', 'tipo' => 'entrada', 'titulo' => 'Boleto Oficial Llaqta Machu Picchu Circuito 2', 'prov' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'costo' => 152.00, 'moneda' => 'PEN', 'por' => 'persona', 'confirmacion' => 'DDC-MP-8819', 'estado' => 'confirmado'],
                    ['dia' => 4, 'hora' => '11:00', 'tipo' => 'guia', 'titulo' => 'Guía Arqueológico Privado en Machu Picchu', 'prov' => 'Lic. Juan Carlos Huamán Quispe (Guía Arqueológico)', 'costo' => 160.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-GU-04', 'estado' => 'confirmado'],
                ],
                'pagos' => [
                    ['tipo' => 'deposito', 'monto' => 5900.00, 'dias_antes' => 60, 'metodo' => 'transferencia', 'ref' => 'DB-DE-0801'],
                    ['tipo' => 'saldo', 'monto' => 5900.00, 'dias_antes' => 30, 'metodo' => 'transferencia', 'ref' => 'DB-DE-0802'],
                ],
            ],

            // 9. PROGRAMA 100% CERRADO Y AUDITADO (Inti Raymi VIP en Sacsayhuamán)
            [
                'codigo' => 'AET-2026-0060',
                'nombre' => 'Fiesta del Sol: Inti Raymi VIP en Sacsayhuamán & Belmond 7D/6N',
                'estado' => 'confirmado',
                'cerrado' => true,
                'plantilla' => false,
                'inicio' => '2026-06-21',
                'fin' => '2026-06-27',
                'lang' => 'en',
                'precioAdulto' => '6800.00',
                'precioChild' => '0.00',
                'porcentaje_ganancia' => '24.00',
                'tipo_cambio' => '3.750',
                'suplemento_individual' => '0.00',
                'habitaciones_individuales' => 0,
                'cliente' => [
                    'tipo' => 'directo',
                    'nombre' => 'Marcus & Valerie Sterling',
                    'empresa' => null,
                    'email' => 'marcus.sterling@sterling-pe.com',
                    'telefono' => '+1 415 555 0188',
                    'pais' => 'US',
                    'idioma' => 'en',
                    'origen' => 'recomendacion',
                ],
                'hoteles' => ['Belmond Hotel Monasterio'],
                'habitaciones' => [
                    ['hotel' => 'Belmond Hotel Monasterio', 'tipo' => 'Suite Presidencial Monasterio', 'noches' => 6, 'costo_noche' => 1450.00],
                ],
                'paxes' => [
                    ['nombre' => 'Marcus Sterling', 'fecha_nacimiento' => '1965-03-30', 'sexo' => 'M', 'pais' => 'US', 'pasaporte' => 'US88491028', 'pasaporte_vencimiento' => '2030-07-15', 'dieta' => 'ninguna', 'alergias' => null, 'condiciones_medicas' => null, 'emergencia_nombre' => 'Todd Sterling', 'emergencia_telefono' => '+1 415 555 9000'],
                    ['nombre' => 'Valerie Sterling', 'fecha_nacimiento' => '1967-10-14', 'sexo' => 'F', 'pais' => 'US', 'pasaporte' => 'US88491029', 'pasaporte_vencimiento' => '2030-07-15', 'dieta' => 'ninguna', 'alergias' => null, 'condiciones_medicas' => null, 'emergencia_nombre' => 'Todd Sterling', 'emergencia_telefono' => '+1 415 555 9000'],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco para las Fiestas Jubilares', 'desc' => 'Recepción y suite en Monasterio.'],
                    ['dia' => 3, 'titulo' => 'Inti Raymi: Asientos VIP Tribuna Naranja en Sacsayhuamán', 'desc' => 'Ceremonia ancestral del dios Sol.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '10:00', 'tipo' => 'traslado', 'titulo' => 'Traslado VIP Aeropuerto → Belmond Monasterio', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'costo' => 45.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-INTI-01', 'estado' => 'confirmado'],
                    ['dia' => 3, 'hora' => '08:30', 'tipo' => 'guia', 'titulo' => 'Guía Arqueólogo Privado en Inti Raymi Sacsayhuamán', 'prov' => 'Lic. Juan Carlos Huamán Quispe (Guía Arqueológico)', 'costo' => 180.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => 'CF-INTI-02', 'estado' => 'confirmado'],
                ],
                'pagos' => [
                    ['tipo' => 'deposito', 'monto' => 6800.00, 'dias_antes' => 90, 'metodo' => 'transferencia', 'ref' => 'CHASE-US-0601'],
                    ['tipo' => 'saldo', 'monto' => 6800.00, 'dias_antes' => 45, 'metodo' => 'transferencia', 'ref' => 'CHASE-US-0602'],
                ],
            ],

            // 10. CANCELADO (Ejemplo de control de cancelaciones)
            [
                'codigo' => 'AET-2026-0035',
                'nombre' => 'Expedición Privada Salkantay Trek & Glamping 5D/4N',
                'estado' => 'cancelado',
                'plantilla' => false,
                'inicio' => '2026-03-15',
                'fin' => '2026-03-19',
                'lang' => 'es',
                'precioAdulto' => '2800.00',
                'precioChild' => '0.00',
                'porcentaje_ganancia' => '20.00',
                'tipo_cambio' => '3.750',
                'suplemento_individual' => '0.00',
                'habitaciones_individuales' => 0,
                'cliente' => [
                    'tipo' => 'directo',
                    'nombre' => 'Eduardo Cisneros',
                    'empresa' => null,
                    'email' => 'eduardo.cisneros@techlatam.com',
                    'telefono' => '+1 305 555 4910',
                    'pais' => 'US',
                    'idioma' => 'es',
                    'origen' => 'web',
                    'notas' => 'Cancelado por el cliente por motivos de salud antes del primer pago.',
                ],
                'hoteles' => [],
                'habitaciones' => [],
                'paxes' => [
                    ['nombre' => 'Eduardo Cisneros', 'fecha_nacimiento' => '1985-09-12', 'sexo' => 'M', 'pais' => 'US', 'pasaporte' => 'US49201948', 'pasaporte_vencimiento' => '2030-01-10', 'dieta' => 'ninguna', 'alergias' => null, 'condiciones_medicas' => null, 'emergencia_nombre' => null, 'emergencia_telefono' => null],
                ],
                'itinerario' => [],
                'servicios' => [],
                'pagos' => [],
            ],

            // 11. PLANTILLA 1: Machu Picchu & Valle Sagrado Exclusivo 5D/4N
            [
                'codigo' => 'PLANT-001',
                'nombre' => '[Plantilla] Machu Picchu & Valle Sagrado Exclusivo 5D/4N',
                'estado' => 'cotizacion',
                'plantilla' => true,
                'inicio' => $hoy->copy()->addMonths(1)->toDateString(),
                'fin' => $hoy->copy()->addMonths(1)->addDays(5)->toDateString(),
                'lang' => 'en',
                'precioAdulto' => '3950.00',
                'precioChild' => '0.00',
                'porcentaje_ganancia' => '20.00',
                'tipo_cambio' => '3.750',
                'suplemento_individual' => '450.00',
                'habitaciones_individuales' => 0,
                'cliente' => null,
                'hoteles' => ['Belmond Hotel Monasterio', 'Tambo del Inka, a Luxury Collection Resort & Spa', 'Belmond Sanctuary Lodge'],
                'habitaciones' => [
                    ['hotel' => 'Belmond Hotel Monasterio', 'tipo' => 'Deluxe King con Oxígeno Enriquecido', 'noches' => 2, 'costo_noche' => 650.00],
                    ['hotel' => 'Tambo del Inka, a Luxury Collection Resort & Spa', 'tipo' => 'Deluxe King con Balcón al Río Vilcanota', 'noches' => 1, 'costo_noche' => 480.00],
                    ['hotel' => 'Belmond Sanctuary Lodge', 'tipo' => 'Mountain View Deluxe Room', 'noches' => 1, 'costo_noche' => 1650.00],
                ],
                'paxes' => [],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco, Traslado Privado & Monasterio', 'desc' => 'Recepción y check-in.'],
                    ['dia' => 1, 'titulo' => 'Cusco Imperial & Sacsayhuamán', 'desc' => 'Visita arqueológica privada.'],
                    ['dia' => 2, 'titulo' => 'Valle Sagrado, Maras Moray & Tambo del Inka', 'desc' => 'Pernocte en Urubamba.'],
                    ['dia' => 3, 'titulo' => 'Tren Hiram Bingham a Machu Picchu & Sanctuary Lodge', 'desc' => 'Día de ensueño.'],
                    ['dia' => 4, 'titulo' => 'Amanecer en Machu Picchu & Retorno a Cusco', 'desc' => 'Cierre del paquete.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '10:00', 'tipo' => 'traslado', 'titulo' => 'Traslado VIP Aeropuerto → Monasterio', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'costo' => 45.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => null, 'estado' => 'por_solicitar'],
                    ['dia' => 3, 'hora' => '09:05', 'tipo' => 'tren', 'titulo' => 'Tren Belmond Hiram Bingham', 'prov' => 'PeruRail S.A.', 'costo' => 580.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => null, 'estado' => 'por_solicitar'],
                    ['dia' => 4, 'hora' => '06:30', 'tipo' => 'entrada', 'titulo' => 'Boleto Llaqta Machu Picchu Circuito 2', 'prov' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'costo' => 152.00, 'moneda' => 'PEN', 'por' => 'persona', 'confirmacion' => null, 'estado' => 'por_solicitar'],
                    ['dia' => 4, 'hora' => '07:00', 'tipo' => 'guia', 'titulo' => 'Guía Arqueológico Privado Machu Picchu', 'prov' => 'Lic. Juan Carlos Huamán Quispe (Guía Arqueológico)', 'costo' => 160.00, 'moneda' => 'USD', 'por' => 'grupo', 'confirmacion' => null, 'estado' => 'por_solicitar'],
                ],
                'pagos' => [],
            ],

            // 12. PLANTILLA 2: Gran Travesía Andina (Lima, Cusco & Titilaka 10D/9N)
            [
                'codigo' => 'PLANT-002',
                'nombre' => '[Plantilla] Gran Travesía Andina: Lima Gourmet, Cusco & Titicaca 10D/9N',
                'estado' => 'cotizacion',
                'plantilla' => true,
                'inicio' => $hoy->copy()->addMonths(2)->toDateString(),
                'fin' => $hoy->copy()->addMonths(2)->addDays(10)->toDateString(),
                'lang' => 'en',
                'precioAdulto' => '7800.00',
                'precioChild' => '0.00',
                'porcentaje_ganancia' => '22.00',
                'tipo_cambio' => '3.750',
                'suplemento_individual' => '850.00',
                'habitaciones_individuales' => 0,
                'cliente' => null,
                'hoteles' => ['Miraflores Park, A Belmond Hotel', 'Belmond Hotel Monasterio', 'Titilaka - Relais & Châteaux'],
                'habitaciones' => [
                    ['hotel' => 'Miraflores Park, A Belmond Hotel', 'tipo' => 'Ocean View Deluxe Junior Suite', 'noches' => 2, 'costo_noche' => 520.00],
                    ['hotel' => 'Belmond Hotel Monasterio', 'tipo' => 'Deluxe King con Oxígeno Enriquecido', 'noches' => 4, 'costo_noche' => 650.00],
                    ['hotel' => 'Titilaka - Relais & Châteaux', 'tipo' => 'Corner King Suite Lago Titicaca', 'noches' => 3, 'costo_noche' => 980.00],
                ],
                'paxes' => [],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Lima & Miraflores Park', 'desc' => 'Bienvenida.'],
                    ['dia' => 2, 'titulo' => 'Cusco Imperial & Monasterio', 'desc' => 'Arribo a Cusco.'],
                    ['dia' => 5, 'titulo' => 'Ruta del Sol hacia Lago Titicaca', 'desc' => 'Cruce de cordillera.'],
                ],
                'servicios' => [],
                'pagos' => [],
            ],
        ];

        // -------------------------------------------------------------
        // Procesamiento e inserción en base de datos
        // -------------------------------------------------------------
        foreach ($programasDef as $def) {
            $clienteId = null;

            // Crear cliente si no es plantilla
            if (! empty($def['cliente'])) {
                $cDef = $def['cliente'];
                $cliente = Cliente::firstOrCreate(
                    ['email' => $cDef['email']],
                    [
                        'tipo' => $cDef['tipo'],
                        'nombre' => $cDef['nombre'],
                        'empresa' => $cDef['empresa'],
                        'telefono' => $cDef['telefono'],
                        'pais' => $cDef['pais'],
                        'idioma' => $cDef['idioma'],
                        'origen' => $cDef['origen'],
                        'notas' => $cDef['notas'] ?? null,
                    ]
                );
                $clienteId = $cliente->id;
            }

            $inicio = Carbon::parse($def['inicio']);
            $fin = Carbon::parse($def['fin']);
            $anio = $anios->get($inicio->year) ?? Anio::firstOrCreate(['anio' => $inicio->year]);
            $mes = $meses->get($inicio->month) ?? Mes::firstOrCreate(['numero' => $inicio->month], ['nombre' => $inicio->locale('es')->monthName]);

            $cerrado = ! empty($def['cerrado']);
            $programa = Programa::withoutGlobalScope('sin_plantillas')->create([
                'codigo' => $def['codigo'],
                'nombre' => $def['nombre'],
                'estado' => $def['estado'],
                'plantilla' => $def['plantilla'],
                'cliente_id' => $clienteId,
                'inicio' => $def['inicio'],
                'fin' => $def['fin'],
                'lang' => $def['lang'],
                'precioAdulto' => $def['precioAdulto'],
                'precioChild' => $def['precioChild'],
                'porcentaje_ganancia' => $def['porcentaje_ganancia'],
                'tipo_cambio' => $def['tipo_cambio'],
                'suplemento_individual' => $def['suplemento_individual'],
                'habitaciones_individuales' => $def['habitaciones_individuales'],
                'llegada_vuelo' => $def['llegada_vuelo'] ?? null,
                'llegada_at' => $def['llegada_at'] ?? null,
                'salida_vuelo' => $def['salida_vuelo'] ?? null,
                'salida_at' => $def['salida_at'] ?? null,
                'confirmado_at' => $def['estado'] === 'confirmado' ? $inicio->copy()->subDays(65) : null,
                'itinerario_visto_at' => $def['estado'] === 'confirmado' ? $inicio->copy()->subDays(66) : null,
                'cerrado_at' => $cerrado ? $fin->copy()->addDay() : null,
                'cerrado_por' => $cerrado ? $usuarioAdmin?->id : null,
                'anio_id' => $anio?->id,
                'mes_id' => $mes?->id,
                'presentacion' => "<p>Bienvenido a su itinerario exclusivo diseñado por <strong>Andean Exclusive</strong>. Cada detalle ha sido meticulosamente seleccionado para ofrecerle una experiencia inigualable en Perú, combinando la mística de los Andes con la más alta excelencia en hospitalidad.</p>",
            ]);

            // Asignar agente
            if ($agenteDefault) {
                $programa->agentes()->syncWithoutDetaching([$agenteDefault->id]);
            }

            // Asociar hoteles en tabla pivote
            if (! empty($def['hoteles'])) {
                foreach ($def['hoteles'] as $hNombre) {
                    if (isset($hoteles[$hNombre])) {
                        DB::table('hotel_programa')->insertOrIgnore([
                            'hotel_id' => $hoteles[$hNombre]->id,
                            'programa_id' => $programa->id,
                        ]);
                    }
                }
            }

            // Alojamiento y noches
            if (! empty($def['habitaciones'])) {
                $diasOffset = 0;
                foreach ($def['habitaciones'] as $habData) {
                    $hModel = $hoteles->get($habData['hotel']);
                    if (! $hModel) {
                        continue;
                    }

                    $habModel = $hModel->habitaciones->firstWhere('tipo', $habData['tipo']);
                    if (! $habModel) {
                        continue;
                    }

                    $checkIn = $inicio->copy()->addDays($diasOffset)->toDateString();
                    $checkOut = $inicio->copy()->addDays($diasOffset + $habData['noches'])->toDateString();
                    $diasOffset += $habData['noches'];

                    HabitacionFecha::create([
                        'programa_id' => $programa->id,
                        'habitacion_id' => $habModel->id,
                        'cantidad' => 1,
                        'costo_noche' => $habData['costo_noche'],
                        'moneda' => 'USD',
                        'fecha_ingreso' => $checkIn,
                        'fecha_salida' => $checkOut,
                    ]);
                }

                // Sincronizar servicios de alojamiento automáticamente
                AlojamientoEnServicios::sincronizar($programa);

                if ($cerrado) {
                    $programa->servicios()->whereNull('pagado_at')->each(function (Servicio $s) use ($fin) {
                        $s->update([
                            'estado' => 'confirmado',
                            'pagado_at' => $fin->toDateString(),
                        ]);

                        if ($s->costo > 0) {
                            ProveedorPago::create([
                                'servicio_id' => $s->id,
                                'fecha' => $fin->toDateString(),
                                'monto' => $s->importe,
                                'moneda' => $s->moneda ?? 'USD',
                                'metodo' => 'transferencia',
                                'referencia' => 'OP-HAB-'.str_pad((string) $s->id, 5, '0', STR_PAD_LEFT),
                            ]);
                        }
                    });
                }
            }

            // Pasajeros (Paxs)
            if (! empty($def['paxes'])) {
                foreach ($def['paxes'] as $paxData) {
                    $paxData['programa_id'] = $programa->id;
                    $pax = Pax::create($paxData);
                    if ($agenteDefault) {
                        $pax->agentes()->syncWithoutDetaching([$agenteDefault->id]);
                    }
                }
            }

            // Días de itinerario
            if (! empty($def['itinerario'])) {
                foreach ($def['itinerario'] as $diaData) {
                    $fechaDia = $inicio->copy()->addDays($diaData['dia'])->toDateString();
                    $programa->dias()->create([
                        'fecha' => $fechaDia,
                        'titulo' => $diaData['titulo'],
                        'descripcion' => $diaData['desc'],
                    ]);
                }
            }

            // Servicios diarios
            if (! empty($def['servicios'])) {
                foreach ($def['servicios'] as $servData) {
                    $fechaServ = $inicio->copy()->addDays($servData['dia'])->toDateString();
                    $provId = isset($proveedores[$servData['prov']]) ? $proveedores[$servData['prov']]->id : null;

                    $pagoSolicitado = ! empty($servData['pago_solicitado']);
                    $servicio = $programa->servicios()->create([
                        'fecha' => $fechaServ,
                        'hora' => $servData['hora'],
                        'tipo' => $servData['tipo'],
                        'titulo' => $servData['titulo'],
                        'proveedor_id' => $provId,
                        'punto_encuentro' => $servData['tipo'] === 'traslado' ? 'Sala de llegadas / Lobby' : 'Lobby del Hotel',
                        'estado' => $servData['estado'],
                        'confirmacion' => $servData['confirmacion'] ?? null,
                        'costo' => $servData['costo'] ?? null,
                        'costo_estimado' => $servData['costo'] ?? 0,
                        'moneda' => $servData['moneda'] ?? 'USD',
                        'costo_por' => $servData['por'] ?? 'grupo',
                        'visible_cliente' => true,
                        'pago_solicitado_at' => $pagoSolicitado ? Carbon::now()->subDay() : null,
                        'pago_solicitado_por' => $pagoSolicitado ? $usuarioAdmin?->id : null,
                        'orden_enviada_at' => in_array($servData['estado'], ['solicitado', 'confirmado']) ? Carbon::parse($fechaServ)->subDays(10) : null,
                    ]);

                    // Pago a proveedor: se genera para servicios confirmados que ya pasaron o en programas cerrados
                    $pendientePago = ! empty($servData['pendiente_pago']);
                    $debeEstarPagado = $servData['estado'] === 'confirmado'
                        && ! $pendientePago
                        && (Carbon::parse($fechaServ)->lte($hoy) || $cerrado);

                    if ($debeEstarPagado) {
                        $servicio->update([
                            'pagado_at' => $fechaServ,
                        ]);

                        $paxCount = max(1, count($def['paxes']));
                        $montoReal = ($servData['por'] ?? 'grupo') === 'persona' ? $servData['costo'] * $paxCount : $servData['costo'];

                        ProveedorPago::create([
                            'servicio_id' => $servicio->id,
                            'fecha' => $fechaServ,
                            'monto' => $montoReal,
                            'moneda' => $servData['moneda'] ?? 'USD',
                            'metodo' => 'transferencia',
                            'referencia' => 'OP-PROV-'.str_pad((string) $servicio->id, 5, '0', STR_PAD_LEFT),
                        ]);
                    }
                }
            }

            // Pagos del cliente
            if (! empty($def['pagos'])) {
                if ($cerrado) {
                    $totalVenta = $programa->presupuesto()['total'];
                    $deposito = round($totalVenta * 0.5, 2);
                    $saldo = round($totalVenta - $deposito, 2);

                    $programa->pagos()->create([
                        'fecha' => $inicio->copy()->subDays(60)->toDateString(),
                        'monto' => $deposito,
                        'tipo' => 'deposito',
                        'metodo' => 'transferencia',
                        'referencia' => 'SWIFT-DEPOSITO-100%',
                    ]);
                    $programa->pagos()->create([
                        'fecha' => $inicio->copy()->subDays(30)->toDateString(),
                        'monto' => $saldo,
                        'tipo' => 'saldo',
                        'metodo' => 'transferencia',
                        'referencia' => 'SWIFT-SALDO-100%',
                    ]);
                } else {
                    foreach ($def['pagos'] as $pagoDef) {
                        $fechaPago = $inicio->copy()->subDays($pagoDef['dias_antes'])->toDateString();
                        $programa->pagos()->create([
                            'fecha' => $fechaPago,
                            'monto' => $pagoDef['monto'],
                            'tipo' => $pagoDef['tipo'],
                            'metodo' => $pagoDef['metodo'],
                            'referencia' => $pagoDef['ref'],
                        ]);
                    }
                }
            }

            // Tareas operativas estándar para programas confirmados
            if ($def['estado'] === 'confirmado') {
                $tareasPlantillas = [
                    ['titulo' => 'Emitir boletos de tren Hiram Bingham / Vistadome en PeruRail', 'dias_antes' => 30],
                    ['titulo' => 'Comprar boletos oficiales de ingreso a la Llaqta en DDC Cusco', 'dias_antes' => 30],
                    ['titulo' => 'Reconfirmar habitaciones y oxigenación con Belmond y Marriott', 'dias_antes' => 15],
                    ['titulo' => 'Asignar chofer y placa de Sprinter en VIP Transports', 'dias_antes' => 7],
                    ['titulo' => 'Asignar guía oficial colegiado bilingüe', 'dias_antes' => 7],
                    ['titulo' => 'Reconfirmar restricciones dietéticas y alergias con restaurantes', 'dias_antes' => 3],
                    ['titulo' => 'Enviar briefing final, vouchers y contacto de chofer al cliente', 'dias_antes' => 2],
                ];

                foreach ($tareasPlantillas as $tp) {
                    $limite = $inicio->copy()->subDays($tp['dias_antes']);
                    $completada = $limite->lt($hoy) || $cerrado;

                    $programa->tareas()->create([
                        'titulo' => $tp['titulo'],
                        'fecha_limite' => $limite->toDateString(),
                        'completada_at' => $completada ? $limite->copy()->setTime(11, 0) : null,
                    ]);
                }

                // Aceptación digital firmada
                Aceptacion::create([
                    'programa_id' => $programa->id,
                    'nombre' => $def['cliente']['nombre'] ?? 'Cliente VIP',
                    'ip' => '192.168.1.100',
                    'navegador' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 Safari/537.36',
                    'total' => (float) $def['precioAdulto'] * max(1, count($def['paxes'])),
                    'propuesta' => [
                        'codigo' => $programa->codigo,
                        'nombre' => $programa->nombre,
                        'inicio' => $programa->inicio,
                        'fin' => $programa->fin,
                        'precio_total' => (float) $def['precioAdulto'] * max(1, count($def['paxes'])),
                    ],
                    'huella' => sha1($programa->id . $programa->codigo . 'firmado_digitalmente'),
                ]);
            }
        }
    }
}
