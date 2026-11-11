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
use App\Models\TarifarioServicio;
use App\Support\AlojamientoEnServicios;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramaSeeder extends Seeder
{
    /**
     * Llena el sistema con itinerarios, cotizaciones y reservas de lujo 100% integrados
     * con el Tarifario Maestro oficial Aracari 2027 y marcas peruanas de alta gama.
     * Cubre todas las áreas del sistema:
     * - Comercial (Cotizaciones abiertas y nuevas con comisiones y márgenes)
     * - Reservas (Bandeja con servicios sin asignar, por reservar y solicitados)
     * - Operaciones (Itinerarios en curso HOY con ficha operativa y choferes)
     * - Contabilidad (Cuentas por pagar, liquidaciones de proveedores y programas cerrados con auditoría)
     * - Plantillas reutilizables oficiales
     */
    public function run(): void
    {
        // 1. Limpiar registros previos de programas y tablas hijas
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
        $tarifarios = TarifarioServicio::all()->keyBy('codigo');

        $hoy = Carbon::today();

        // -------------------------------------------------------------
        // Definición de programas de lujo basados en Tarifario Maestro
        // -------------------------------------------------------------
        $programasDef = [
            // 1. EN CURSO HOY (Día 3 de viaje - Experiencia Hiram Bingham & Machu Picchu) - 2 Pax
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
                        'emergencia_nombre' => 'Sarah Kensington (Hija)',
                        'emergencia_telefono' => '+1 617 555 9931',
                    ],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco, Traslado Privado & Aclimatación', 'desc' => 'Recepción VIP en aeropuerto y traslado al Belmond Hotel Monasterio.'],
                    ['dia' => 1, 'titulo' => 'Cusco Imperial, Qorikancha & Catedral', 'desc' => 'Visita cultural con guía oficial por el centro histórico de Cusco.'],
                    ['dia' => 2, 'titulo' => 'Valle Sagrado: Awanacancha, Pisac & Ollantaytambo', 'desc' => 'Descenso al Valle Sagrado, textilería fina y almuerzo tradicional.'],
                    ['dia' => 3, 'titulo' => 'Tren Hiram Bingham hacia Machu Picchu & Aguas Calientes', 'desc' => 'Experiencia a bordo de Belmond Hiram Bingham y subida a la ciudadela.'],
                    ['dia' => 4, 'titulo' => 'Llaqta de Machu Picchu & Retorno a Cusco', 'desc' => 'Ingreso preferente a la ciudadela inca y retorno en tren de lujo.'],
                    ['dia' => 5, 'titulo' => 'Arte Virreinal en San Blas & Tarde Libre', 'desc' => 'Paseo de compras y descanso en el claustro colonial.'],
                    ['dia' => 6, 'titulo' => 'Día Libre de Spa & Cena de Gala', 'desc' => 'Día de relajo y cena maridada.'],
                    ['dia' => 7, 'titulo' => 'Despedida de los Andes & Traslado al Aeropuerto', 'desc' => 'Traslado privado al aeropuerto Velasco Astete.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '10:00', 'codigo' => 'APTHTL', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'CF-TRANS-1001', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '09:00', 'codigo' => 'HINARC', 'prov' => 'AGOTUR Cusco (Asociación de Guías Oficiales)', 'confirmacion' => 'CF-GUIA-2001', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '10:30', 'codigo' => 'ENT-CATEDRAL', 'prov' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'confirmacion' => 'CF-TKT-3001', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '11:45', 'codigo' => 'ENT-QORICANCHA', 'prov' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'confirmacion' => 'CF-TKT-3002', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '13:00', 'codigo' => 'ALM-CUCHARA-PALO', 'prov' => 'Chicha por Gastón Acurio - Cusco', 'confirmacion' => 'CF-REST-3001', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '08:30', 'codigo' => 'FDAWA', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'CF-TRANS-1002', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '09:30', 'codigo' => 'BTG', 'prov' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'confirmacion' => 'CF-TKT-3003', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '13:00', 'codigo' => 'ALM-CHUNCHO', 'prov' => 'Hacienda Huayoccari Restaurant & Private Museum', 'confirmacion' => 'CF-REST-3002', 'estado' => 'confirmado'],
                    ['dia' => 3, 'hora' => '09:05', 'tipo' => 'tren', 'titulo' => 'Tren Belmond Hiram Bingham Poroy → Machu Picchu', 'prov' => 'PeruRail S.A.', 'costo' => 580.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => 'TK-HB-98124', 'estado' => 'confirmado'],
                    ['dia' => 3, 'hora' => '13:30', 'codigo' => 'VIAT-MAPI', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'TK-REST-501', 'estado' => 'confirmado'],
                    ['dia' => 4, 'hora' => '06:30', 'codigo' => 'BUS-CONSETTUR-RT', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'TK-BUS-849102', 'estado' => 'confirmado'],
                    ['dia' => 4, 'hora' => '17:50', 'tipo' => 'tren', 'titulo' => 'Tren Belmond Hiram Bingham Machu Picchu → Poroy (Cena)', 'prov' => 'PeruRail S.A.', 'costo' => 580.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => 'TK-HB-98125', 'estado' => 'confirmado'],
                    ['dia' => 7, 'hora' => '13:00', 'codigo' => 'APTHTL', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'CF-TRANS-1003', 'estado' => 'confirmado'],
                ],
                'pagos' => [
                    ['tipo' => 'deposito', 'monto' => 5900.00, 'dias_antes' => 90, 'metodo' => 'transferencia', 'ref' => 'SWIFT-JP-849102'],
                    ['tipo' => 'saldo', 'monto' => 5900.00, 'dias_antes' => 60, 'metodo' => 'transferencia', 'ref' => 'SWIFT-JP-910283'],
                ],
            ],

            // 2. EN CURSO HOY (Día 2 de viaje - Familia Suiza en Tambo del Inka) - 3 Pax
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
                    ['hotel' => 'Tambo del Inka, a Luxury Collection Resort & Spa', 'tipo' => 'Deluxe King con Balcón al Río Vilcanota', 'noches' => 3, 'costo_noche' => 480.00],
                    ['hotel' => 'JW Marriott El Convento Cusco', 'tipo' => 'Classic King con Oxigenación Controlada', 'noches' => 2, 'costo_noche' => 420.00],
                ],
                'paxes' => [
                    ['nombre' => 'Charles Dupont', 'fecha_nacimiento' => '1974-03-10', 'sexo' => 'M', 'pais' => 'CH', 'pasaporte' => 'CH49201948', 'pasaporte_vencimiento' => '2031-06-15', 'dieta' => 'ninguna'],
                    ['nombre' => 'Sophia Dupont', 'fecha_nacimiento' => '1977-09-04', 'sexo' => 'F', 'pais' => 'CH', 'pasaporte' => 'CH49201949', 'pasaporte_vencimiento' => '2031-06-15', 'dieta' => 'sin_gluten'],
                    ['nombre' => 'Laurent Dupont', 'fecha_nacimiento' => '2007-01-18', 'sexo' => 'F', 'pais' => 'CH', 'pasaporte' => 'CH49201950', 'pasaporte_vencimiento' => '2028-12-10', 'dieta' => 'sin_gluten'],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco & Descenso Directo a Valle Sagrado', 'desc' => 'Traslado privado directo al Tambo del Inka en Urubamba.'],
                    ['dia' => 1, 'titulo' => 'Chinchero, Salineras de Maras & Terrazas de Moray', 'desc' => 'Excursión privada por los miradores del Valle Sagrado.'],
                    ['dia' => 2, 'titulo' => 'Tren Panorámico hacia Machu Picchu', 'desc' => 'Viaje en tren y visita de la ciudadela.'],
                    ['dia' => 3, 'titulo' => 'Ollantaytambo Arqueológico & Retorno a Cusco', 'desc' => 'Fortaleza inca y check-in en JW Marriott Cusco.'],
                    ['dia' => 4, 'titulo' => 'Sacsayhuamán & Ruinas Aledañas', 'desc' => 'Paseo privado por la fortaleza megalítica.'],
                    ['dia' => 5, 'titulo' => 'Traslado al Aeropuerto & Retorno', 'desc' => 'Traslado privado al aeropuerto.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '11:45', 'codigo' => 'APTOLL', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'CF-TR-201', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '09:00', 'codigo' => 'FDCHIM', 'prov' => 'Sacred Valley Private Drivers', 'confirmacion' => 'CF-TR-202', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '09:30', 'codigo' => 'BTP', 'prov' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'confirmacion' => 'CF-TKT-202', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '13:00', 'codigo' => 'ALM-URUBAMBA', 'prov' => 'Hacienda Huayoccari Restaurant & Private Museum', 'confirmacion' => 'CF-REST-202', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '06:50', 'tipo' => 'tren', 'titulo' => 'Tren Vistadome Observatory Urubamba → Machu Picchu', 'prov' => 'PeruRail S.A.', 'costo' => 185.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => 'TK-VO-55102', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '09:30', 'codigo' => 'BUS-CONSETTUR-RT', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'BUS-CON-4819', 'estado' => 'confirmado'],
                    ['dia' => 3, 'hora' => '09:00', 'codigo' => 'FDOLLM', 'prov' => 'Sacred Valley Private Drivers', 'confirmacion' => 'CF-TR-203', 'estado' => 'confirmado'],
                    ['dia' => 4, 'hora' => '09:00', 'codigo' => 'HINARC', 'prov' => 'AGOTUR Cusco (Asociación de Guías Oficiales)', 'confirmacion' => 'CF-GU-203', 'estado' => 'confirmado'],
                    ['dia' => 5, 'hora' => '13:00', 'codigo' => 'APTHTL', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'CF-TR-204', 'estado' => 'confirmado'],
                ],
                'pagos' => [
                    ['tipo' => 'deposito', 'monto' => 6300.00, 'dias_antes' => 90, 'metodo' => 'tarjeta', 'ref' => 'VISA-CH-99201'],
                    ['tipo' => 'saldo', 'monto' => 6300.00, 'dias_antes' => 60, 'metodo' => 'tarjeta', 'ref' => 'VISA-CH-99342'],
                ],
            ],

            // 3. INICIA EN 3 DÍAS (Operaciones próximas - A&K Australia con saldo por cobrar) - 2 Pax
            [
                'codigo' => 'AET-2026-0103',
                'nombre' => 'Sinfonía Andina: Tesoros del Cusco, Valle Sagrado & Treks 6D/5N',
                'estado' => 'confirmado',
                'plantilla' => false,
                'inicio' => $hoy->copy()->addDays(3)->toDateString(),
                'fin' => $hoy->copy()->addDays(8)->toDateString(),
                'lang' => 'en',
                'precioAdulto' => '4800.00',
                'precioChild' => '0.00',
                'porcentaje_ganancia' => '20.00',
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
                    'notas' => 'Reserva B2B para viajeros australianos de alto poder adquisitivo. Exigen trekking privado y experiencia de pago a la tierra.',
                ],
                'hoteles' => ['JW Marriott El Convento Cusco', 'Tambo del Inka, a Luxury Collection Resort & Spa'],
                'habitaciones' => [
                    ['hotel' => 'JW Marriott El Convento Cusco', 'tipo' => 'Classic King con Oxigenación Controlada', 'noches' => 3, 'costo_noche' => 420.00],
                    ['hotel' => 'Tambo del Inka, a Luxury Collection Resort & Spa', 'tipo' => 'Deluxe King con Balcón al Río Vilcanota', 'noches' => 2, 'costo_noche' => 480.00],
                ],
                'paxes' => [
                    ['nombre' => 'Richard Montgomery', 'fecha_nacimiento' => '1959-07-28', 'sexo' => 'M', 'pais' => 'AU', 'pasaporte' => 'AU91823901', 'pasaporte_vencimiento' => '2028-10-14'],
                    ['nombre' => 'Evelyn Montgomery', 'fecha_nacimiento' => '1961-12-05', 'sexo' => 'F', 'pais' => 'AU', 'pasaporte' => 'AU91823902', 'pasaporte_vencimiento' => '2028-10-14'],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco & Bienvenida Imperial', 'desc' => 'Recepción y check-in en JW Marriott.'],
                    ['dia' => 1, 'titulo' => 'Cusco Challenge & Aventura Arqueológica', 'desc' => 'Recorrido dinámico por templos y miradores.'],
                    ['dia' => 2, 'titulo' => 'Ceremonia Mística de Pago a la Tierra en Valle Sagrado', 'desc' => 'Ofrenda a la Pachamama con chamán andino.'],
                    ['dia' => 3, 'titulo' => 'Trek Maras & Moray con Almuerzo Gourmet', 'desc' => 'Caminata escénica entre andenes incas y salineras.'],
                    ['dia' => 4, 'titulo' => 'Ollantaytambo & Retorno a Cusco', 'desc' => 'Fortaleza inca y retorno.'],
                    ['dia' => 5, 'titulo' => 'Despedida de Cusco', 'desc' => 'Traslado al aeropuerto.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '10:30', 'codigo' => 'APTHTL', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'CF-TR-301', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '09:00', 'codigo' => '4CUZCH', 'prov' => 'Perú Expert Cultural Guides S.A.C.', 'confirmacion' => 'CF-TOUR-302', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '09:30', 'codigo' => 'CEREM-VALLE', 'prov' => 'Perú Expert Cultural Guides S.A.C.', 'confirmacion' => 'CF-CER-303', 'estado' => 'confirmado'],
                    ['dia' => 3, 'hora' => '08:00', 'codigo' => 'TREK-MARAS-MORAY', 'prov' => 'Sacred Valley Private Drivers', 'confirmacion' => 'CF-TRK-304', 'estado' => 'confirmado'],
                    ['dia' => 3, 'hora' => '13:00', 'codigo' => 'ALM-CUCHARA-PALO', 'prov' => 'Hacienda Huayoccari Restaurant & Private Museum', 'confirmacion' => 'CF-REST-305', 'estado' => 'confirmado'],
                    ['dia' => 5, 'hora' => '12:00', 'codigo' => 'APTHTL', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'CF-TR-306', 'estado' => 'solicitado'],
                ],
                'pagos' => [
                    ['tipo' => 'deposito', 'monto' => 4800.00, 'dias_antes' => 75, 'metodo' => 'transferencia', 'ref' => 'SWIFT-ANZ-48190'],
                ],
            ],

            // 4. INICIA EN 5 DÍAS (Luna de Miel - Servicios en POR RESERVAR, SOLICITADO y PAGO SOLICITADO) - 2 Pax
            [
                'codigo' => 'AET-2026-0104',
                'nombre' => 'Luna de Miel en los Andes: Mística Andina & Atardecer en Vilcanota 6D/5N',
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
                    'notas' => 'Viaje de novios. Solicitaron amenidad de bienvenida romántica.',
                ],
                'hoteles' => ['Belmond Hotel Monasterio', 'Belmond Sanctuary Lodge'],
                'habitaciones' => [
                    ['hotel' => 'Belmond Hotel Monasterio', 'tipo' => 'Deluxe King con Oxígeno Enriquecido', 'noches' => 3, 'costo_noche' => 650.00],
                    ['hotel' => 'Belmond Sanctuary Lodge', 'tipo' => 'Mountain View Deluxe Room', 'noches' => 2, 'costo_noche' => 1650.00],
                ],
                'paxes' => [
                    ['nombre' => 'Oliver Vance', 'fecha_nacimiento' => '1992-06-18', 'sexo' => 'M', 'pais' => 'GB', 'pasaporte' => 'GB772910482', 'pasaporte_vencimiento' => '2032-11-20'],
                    ['nombre' => 'Eleanor Vance', 'fecha_nacimiento' => '1994-02-14', 'sexo' => 'F', 'pais' => 'GB', 'pasaporte' => 'GB772910483', 'pasaporte_vencimiento' => '2032-11-20'],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco & Bienvenida Romántica', 'desc' => 'Arribo a Cusco y recepción en Belmond Monasterio.'],
                    ['dia' => 1, 'titulo' => 'Ceremonia de Bendición Andina en Cusco', 'desc' => 'Ritual de unión mística con maestro chamán.'],
                    ['dia' => 2, 'titulo' => 'Around the Outskirts: 4 Ruinas & Mercado San Pedro', 'desc' => 'Exploración íntima de Sacsayhuamán y tradiciones.'],
                    ['dia' => 3, 'titulo' => 'Tren Machu Picchu & Subida a la Ciudadela', 'desc' => 'Viaje de ensueño.'],
                    ['dia' => 4, 'titulo' => 'Machu Picchu al Alba & Retorno', 'desc' => 'Segundo ingreso panorámico y retorno.'],
                    ['dia' => 5, 'titulo' => 'Despedida de los Andes', 'desc' => 'Traslado al aeropuerto.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '10:00', 'codigo' => 'APTHTL', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'CF-TR-401', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '10:00', 'codigo' => 'CEREM-CUSCO', 'prov' => 'Perú Expert Cultural Guides S.A.C.', 'confirmacion' => null, 'estado' => 'solicitado', 'pago_solicitado' => true],
                    ['dia' => 2, 'hora' => '09:00', 'codigo' => 'HOUTSK', 'prov' => 'AGOTUR Cusco (Asociación de Guías Oficiales)', 'confirmacion' => null, 'estado' => 'solicitado'],
                    ['dia' => 2, 'hora' => '13:00', 'codigo' => 'ALM-CUCHARA-PALO', 'prov' => 'Chicha por Gastón Acurio - Cusco', 'confirmacion' => null, 'estado' => 'pendiente'],
                    ['dia' => 3, 'hora' => '09:05', 'tipo' => 'tren', 'titulo' => 'Tren Belmond Hiram Bingham Poroy → Machu Picchu', 'prov' => 'PeruRail S.A.', 'costo' => 580.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => null, 'estado' => 'solicitado', 'pago_solicitado' => true],
                    ['dia' => 4, 'hora' => '06:30', 'codigo' => 'BUS-CONSETTUR-RT', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => null, 'estado' => 'pendiente'],
                    ['dia' => 4, 'hora' => '13:00', 'codigo' => 'VIAT-MAPI', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => null, 'estado' => 'pendiente'],
                ],
                'pagos' => [
                    ['tipo' => 'deposito', 'monto' => 4800.00, 'dias_antes' => 90, 'metodo' => 'tarjeta', 'ref' => 'BARCLAY-UK-7729'],
                    ['tipo' => 'saldo', 'monto' => 4800.00, 'dias_antes' => 60, 'metodo' => 'tarjeta', 'ref' => 'BARCLAY-UK-7730'],
                ],
            ],

            // 5. COTIZACIÓN ABIERTA (Virtuoso USA - En etapa comercial) - 4 Pax
            [
                'codigo' => 'AET-2026-0105',
                'nombre' => 'Experiencias Exclusivas en Cusco & Valle Sagrado 5D/4N',
                'estado' => 'cotizacion',
                'plantilla' => false,
                'inicio' => $hoy->copy()->addMonths(2)->toDateString(),
                'fin' => $hoy->copy()->addMonths(2)->addDays(5)->toDateString(),
                'lang' => 'en',
                'precioAdulto' => '3200.00',
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
                    'notas' => 'Cotización solicitada por agencia Virtuoso. Todos los servicios cotizados directamente con el Tarifario Maestro.',
                ],
                'hoteles' => ['Belmond Hotel Monasterio', 'Tambo del Inka, a Luxury Collection Resort & Spa'],
                'habitaciones' => [
                    ['hotel' => 'Belmond Hotel Monasterio', 'tipo' => 'Deluxe King con Oxígeno Enriquecido', 'noches' => 2, 'costo_noche' => 650.00],
                    ['hotel' => 'Tambo del Inka, a Luxury Collection Resort & Spa', 'tipo' => 'Deluxe King con Balcón al Río Vilcanota', 'noches' => 2, 'costo_noche' => 480.00],
                ],
                'paxes' => [
                    ['nombre' => 'David Vance', 'fecha_nacimiento' => '1968-05-12', 'sexo' => 'M', 'pais' => 'US'],
                    ['nombre' => 'Susan Vance', 'fecha_nacimiento' => '1971-08-20', 'sexo' => 'F', 'pais' => 'US'],
                    ['nombre' => 'Michael Vance', 'fecha_nacimiento' => '1998-03-14', 'sexo' => 'M', 'pais' => 'US'],
                    ['nombre' => 'Emily Vance', 'fecha_nacimiento' => '2001-11-09', 'sexo' => 'F', 'pais' => 'US'],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco & Bienvenida', 'desc' => 'Traslado y registro.'],
                    ['dia' => 1, 'titulo' => 'City Tour Cusco & Templos', 'desc' => 'Recorrido colonial.'],
                    ['dia' => 2, 'titulo' => 'Valle Sagrado: Ollantaytambo + Chinchero', 'desc' => 'Andenería y arqueología.'],
                    ['dia' => 3, 'titulo' => 'Machu Picchu Ciudadela Clásica', 'desc' => 'Visita con tickets oficiales.'],
                    ['dia' => 4, 'titulo' => 'Retorno & Vuelo', 'desc' => 'Traslado al aeropuerto.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '10:00', 'codigo' => 'APTHTL', 'estado' => 'sin_asignar'],
                    ['dia' => 1, 'hora' => '09:00', 'codigo' => 'HINARC', 'estado' => 'sin_asignar'],
                    ['dia' => 1, 'hora' => '10:30', 'codigo' => 'ENT-CATEDRAL', 'estado' => 'sin_asignar'],
                    ['dia' => 1, 'hora' => '11:45', 'codigo' => 'ENT-QORICANCHA', 'estado' => 'sin_asignar'],
                    ['dia' => 2, 'hora' => '08:30', 'codigo' => 'FDCHIO', 'estado' => 'sin_asignar'],
                    ['dia' => 2, 'hora' => '09:30', 'codigo' => 'BTG', 'estado' => 'sin_asignar'],
                    ['dia' => 2, 'hora' => '13:00', 'codigo' => 'ALM-CHUNCHO', 'estado' => 'sin_asignar'],
                    ['dia' => 3, 'hora' => '09:30', 'codigo' => 'BUS-CONSETTUR-RT', 'estado' => 'sin_asignar'],
                    ['dia' => 4, 'hora' => '14:00', 'codigo' => 'APTHTL', 'estado' => 'sin_asignar'],
                ],
                'pagos' => [],
            ],

            // 6. COTIZACIÓN NUEVA (Lord Alistair & Lady Claire Hamilton - Escocia) - 2 Pax
            [
                'codigo' => 'AET-2026-0107',
                'nombre' => 'Aniversario de Plata: Perú Místico & Valle Sagrado 6D/5N',
                'estado' => 'cotizacion',
                'plantilla' => false,
                'inicio' => $hoy->copy()->addMonths(3)->toDateString(),
                'fin' => $hoy->copy()->addMonths(3)->addDays(6)->toDateString(),
                'lang' => 'en',
                'precioAdulto' => '4200.00',
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
                    'notas' => 'Cliente VIP de Reino Unido. Cotización calculada sobre la base oficial Aracari.',
                ],
                'hoteles' => ['Belmond Hotel Monasterio'],
                'habitaciones' => [
                    ['hotel' => 'Belmond Hotel Monasterio', 'tipo' => 'Deluxe King con Oxígeno Enriquecido', 'noches' => 5, 'costo_noche' => 650.00],
                ],
                'paxes' => [
                    ['nombre' => 'Lord Alistair Hamilton', 'fecha_nacimiento' => '1961-07-19', 'sexo' => 'M', 'pais' => 'GB'],
                    ['nombre' => 'Lady Claire Hamilton', 'fecha_nacimiento' => '1964-12-02', 'sexo' => 'F', 'pais' => 'GB'],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco & Bienvenida', 'desc' => 'Traslado y check-in.'],
                    ['dia' => 1, 'titulo' => 'Ceremonia de Pago a la Tierra en Cusco', 'desc' => 'Ritual místico andino.'],
                    ['dia' => 2, 'titulo' => 'Chinchero, Maras & Moray', 'desc' => 'Full Day Valle Sagrado.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '10:00', 'codigo' => 'APTHTL', 'estado' => 'sin_asignar'],
                    ['dia' => 1, 'hora' => '10:00', 'codigo' => 'CEREM-CUSCO', 'estado' => 'sin_asignar'],
                    ['dia' => 2, 'hora' => '08:30', 'codigo' => 'FDCHIM', 'estado' => 'sin_asignar'],
                    ['dia' => 2, 'hora' => '13:00', 'codigo' => 'ALM-CUCHARA-PALO', 'estado' => 'sin_asignar'],
                    ['dia' => 3, 'hora' => '09:00', 'codigo' => 'BUS-CONSETTUR-RT', 'estado' => 'sin_asignar'],
                ],
                'pagos' => [],
            ],

            // 7. FINALIZADO CON CUENTAS POR PAGAR PENDIENTES (Semana Santa en Cusco - Jean-Paul Bernard) - 2 Pax
            [
                'codigo' => 'AET-2026-0040',
                'nombre' => 'Semana Santa en Cusco & Valle Sagrado 6D/5N',
                'estado' => 'confirmado',
                'cerrado' => false,
                'plantilla' => false,
                'inicio' => '2026-04-06',
                'fin' => '2026-04-11',
                'lang' => 'en',
                'precioAdulto' => '3800.00',
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
                'hoteles' => ['JW Marriott El Convento Cusco'],
                'habitaciones' => [
                    ['hotel' => 'JW Marriott El Convento Cusco', 'tipo' => 'Classic King con Oxigenación Controlada', 'noches' => 5, 'costo_noche' => 420.00],
                ],
                'paxes' => [
                    ['nombre' => 'Jean-Paul Bernard', 'fecha_nacimiento' => '1958-02-17', 'sexo' => 'M', 'pais' => 'FR', 'pasaporte' => 'FRA77391028', 'pasaporte_vencimiento' => '2029-11-12'],
                    ['nombre' => 'Monique Bernard', 'fecha_nacimiento' => '1961-09-03', 'sexo' => 'F', 'pais' => 'FR', 'pasaporte' => 'FRA77391029', 'pasaporte_vencimiento' => '2029-11-12'],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco', 'desc' => 'Traslado y registro.'],
                    ['dia' => 1, 'titulo' => 'Trek Maras & Moray', 'desc' => 'Caminata en el Valle.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '10:00', 'codigo' => 'APTHTL', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'CF-COL-01', 'estado' => 'confirmado', 'pendiente_pago' => true],
                    ['dia' => 1, 'hora' => '08:00', 'codigo' => 'TREK-MARAS-MORAY', 'prov' => 'Sacred Valley Private Drivers', 'confirmacion' => 'CF-TRK-7819', 'estado' => 'confirmado', 'pendiente_pago' => true],
                    ['dia' => 1, 'hora' => '13:00', 'codigo' => 'ALM-URUBAMBA', 'prov' => 'Hacienda Huayoccari Restaurant & Private Museum', 'confirmacion' => 'CF-ALM-7820', 'estado' => 'confirmado', 'pendiente_pago' => true],
                ],
                'pagos' => [
                    ['tipo' => 'deposito', 'monto' => 3800.00, 'dias_antes' => 60, 'metodo' => 'tarjeta', 'ref' => 'BNP-FR-0401'],
                    ['tipo' => 'saldo', 'monto' => 3800.00, 'dias_antes' => 30, 'metodo' => 'tarjeta', 'ref' => 'BNP-FR-0402'],
                ],
            ],

            // 8. PROGRAMA 100% CERRADO Y AUDITADO (Finanzas y Contabilidad) - 2 Pax
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
                'hoteles' => ['Belmond Hotel Monasterio', 'Tambo del Inka, a Luxury Collection Resort & Spa'],
                'habitaciones' => [
                    ['hotel' => 'Belmond Hotel Monasterio', 'tipo' => 'Deluxe King con Oxígeno Enriquecido', 'noches' => 4, 'costo_noche' => 650.00],
                    ['hotel' => 'Tambo del Inka, a Luxury Collection Resort & Spa', 'tipo' => 'Deluxe King con Balcón al Río Vilcanota', 'noches' => 3, 'costo_noche' => 480.00],
                ],
                'paxes' => [
                    ['nombre' => 'Thomas Becker', 'fecha_nacimiento' => '1970-11-05', 'sexo' => 'M', 'pais' => 'DE', 'pasaporte' => 'DEU66391028', 'pasaporte_vencimiento' => '2031-01-20'],
                    ['nombre' => 'Brigitte Becker', 'fecha_nacimiento' => '1973-04-18', 'sexo' => 'F', 'pais' => 'DE', 'pasaporte' => 'DEU66391029', 'pasaporte_vencimiento' => '2031-01-20'],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco & Monasterio', 'desc' => 'Arribo a la capital inca.'],
                    ['dia' => 1, 'titulo' => 'City Tour Cusco & Qorikancha', 'desc' => 'Visita cultural.'],
                    ['dia' => 2, 'titulo' => 'Valle Sagrado & Pisac', 'desc' => 'Awanacancha y mercados.'],
                    ['dia' => 3, 'titulo' => 'Machu Picchu Ciudadela Clásica', 'desc' => 'Visita con tickets oficiales.'],
                    ['dia' => 7, 'titulo' => 'Traslado al Aeropuerto', 'desc' => 'Fin del viaje.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '10:00', 'codigo' => 'APTHTL', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'CF-CUZ-02', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '09:00', 'codigo' => 'HINARC', 'prov' => 'AGOTUR Cusco (Asociación de Guías Oficiales)', 'confirmacion' => 'CF-GU-03', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '10:30', 'codigo' => 'ENT-CATEDRAL', 'prov' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'confirmacion' => 'CF-TKT-04', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '11:45', 'codigo' => 'ENT-QORICANCHA', 'prov' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'confirmacion' => 'CF-TKT-05', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '08:30', 'codigo' => 'FDAWA', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'CF-VAL-06', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '09:30', 'codigo' => 'BTG', 'prov' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'confirmacion' => 'CF-BTG-07', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '13:00', 'codigo' => 'ALM-CUCHARA-PALO', 'prov' => 'Hacienda Huayoccari Restaurant & Private Museum', 'confirmacion' => 'CF-ALM-08', 'estado' => 'confirmado'],
                    ['dia' => 3, 'hora' => '09:00', 'codigo' => 'BUS-CONSETTUR-RT', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'BUS-MAPI-09', 'estado' => 'confirmado'],
                    ['dia' => 3, 'hora' => '13:00', 'codigo' => 'VIAT-MAPI', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'CF-VIAT-10', 'estado' => 'confirmado'],
                    ['dia' => 7, 'hora' => '11:00', 'codigo' => 'APTHTL', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'CF-RET-11', 'estado' => 'confirmado'],
                ],
                'pagos' => [
                    ['tipo' => 'deposito', 'monto' => 5900.00, 'dias_antes' => 60, 'metodo' => 'transferencia', 'ref' => 'DB-DE-0801'],
                    ['tipo' => 'saldo', 'monto' => 5900.00, 'dias_antes' => 30, 'metodo' => 'transferencia', 'ref' => 'DB-DE-0802'],
                ],
            ],

            // 9. PROGRAMA 100% CERRADO Y AUDITADO (Inti Raymi VIP en Sacsayhuamán) - 2 Pax
            [
                'codigo' => 'AET-2026-0060',
                'nombre' => 'Fiesta del Sol: Inti Raymi VIP en Sacsayhuamán & Mística Andina 7D/6N',
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
                    ['hotel' => 'Belmond Hotel Monasterio', 'tipo' => 'Deluxe King con Oxígeno Enriquecido', 'noches' => 6, 'costo_noche' => 650.00],
                ],
                'paxes' => [
                    ['nombre' => 'Marcus Sterling', 'fecha_nacimiento' => '1965-03-30', 'sexo' => 'M', 'pais' => 'US', 'pasaporte' => 'US88491028', 'pasaporte_vencimiento' => '2030-07-15'],
                    ['nombre' => 'Valerie Sterling', 'fecha_nacimiento' => '1967-10-14', 'sexo' => 'F', 'pais' => 'US', 'pasaporte' => 'US88491029', 'pasaporte_vencimiento' => '2030-07-15'],
                ],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco para las Fiestas Jubilares', 'desc' => 'Recepción y suite en Monasterio.'],
                    ['dia' => 1, 'titulo' => 'Sacsayhuamán & 3 Ruinas', 'desc' => 'Visita previa al complejo arqueológico.'],
                    ['dia' => 2, 'titulo' => 'Ceremonia de Pago a la Tierra con Música Andina', 'desc' => 'Ritual ancestral previo a la fiesta del Sol.'],
                    ['dia' => 3, 'titulo' => 'Inti Raymi en Sacsayhuamán', 'desc' => 'Ceremonia ancestral del dios Sol.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '10:00', 'codigo' => 'APTHTL', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'CF-INTI-01', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '09:00', 'codigo' => 'HINARC', 'prov' => 'AGOTUR Cusco (Asociación de Guías Oficiales)', 'confirmacion' => 'CF-INTI-02', 'estado' => 'confirmado'],
                    ['dia' => 1, 'hora' => '09:30', 'codigo' => 'BTG', 'prov' => 'Dirección Desconcentrada de Cultura de Cusco (DDC)', 'confirmacion' => 'CF-BTG-03', 'estado' => 'confirmado'],
                    ['dia' => 2, 'hora' => '10:00', 'codigo' => 'CEREM-CUSCO-MUSICA', 'prov' => 'Perú Expert Cultural Guides S.A.C.', 'confirmacion' => 'CF-MUS-04', 'estado' => 'confirmado'],
                    ['dia' => 6, 'hora' => '12:00', 'codigo' => 'APTHTL', 'prov' => 'VIP Executive Transports Cusco S.A.C.', 'confirmacion' => 'CF-INTI-05', 'estado' => 'confirmado'],
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
                    ['nombre' => 'Eduardo Cisneros', 'fecha_nacimiento' => '1985-09-12', 'sexo' => 'M', 'pais' => 'US', 'pasaporte' => 'US49201948', 'pasaporte_vencimiento' => '2030-01-10'],
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
                    ['dia' => 0, 'hora' => '10:00', 'codigo' => 'APTHTL', 'estado' => 'pendiente'],
                    ['dia' => 1, 'hora' => '09:00', 'codigo' => 'HINARC', 'estado' => 'pendiente'],
                    ['dia' => 1, 'hora' => '09:30', 'codigo' => 'ENT-CATEDRAL', 'estado' => 'pendiente'],
                    ['dia' => 2, 'hora' => '08:30', 'codigo' => 'FDAWA', 'estado' => 'pendiente'],
                    ['dia' => 2, 'hora' => '09:30', 'codigo' => 'BTG', 'estado' => 'pendiente'],
                    ['dia' => 2, 'hora' => '13:00', 'codigo' => 'ALM-CHUNCHO', 'estado' => 'pendiente'],
                    ['dia' => 3, 'hora' => '09:05', 'tipo' => 'tren', 'titulo' => 'Tren Belmond Hiram Bingham', 'prov' => 'PeruRail S.A.', 'costo' => 580.00, 'moneda' => 'USD', 'por' => 'persona', 'confirmacion' => null, 'estado' => 'pendiente'],
                    ['dia' => 4, 'hora' => '06:30', 'codigo' => 'BUS-CONSETTUR-RT', 'estado' => 'pendiente'],
                    ['dia' => 4, 'hora' => '17:00', 'codigo' => 'APTHTL', 'estado' => 'pendiente'],
                ],
                'pagos' => [],
            ],

            // 12. PLANTILLA 2: Gran Travesía Andina & Treks 7D/6N
            [
                'codigo' => 'PLANT-002',
                'nombre' => '[Plantilla] Gran Travesía Andina, Mística & Treks 7D/6N',
                'estado' => 'cotizacion',
                'plantilla' => true,
                'inicio' => $hoy->copy()->addMonths(2)->toDateString(),
                'fin' => $hoy->copy()->addMonths(2)->addDays(7)->toDateString(),
                'lang' => 'en',
                'precioAdulto' => '5200.00',
                'precioChild' => '0.00',
                'porcentaje_ganancia' => '22.00',
                'tipo_cambio' => '3.750',
                'suplemento_individual' => '650.00',
                'habitaciones_individuales' => 0,
                'cliente' => null,
                'hoteles' => ['Belmond Hotel Monasterio', 'Tambo del Inka, a Luxury Collection Resort & Spa'],
                'habitaciones' => [
                    ['hotel' => 'Belmond Hotel Monasterio', 'tipo' => 'Deluxe King con Oxígeno Enriquecido', 'noches' => 4, 'costo_noche' => 650.00],
                    ['hotel' => 'Tambo del Inka, a Luxury Collection Resort & Spa', 'tipo' => 'Deluxe King con Balcón al Río Vilcanota', 'noches' => 2, 'costo_noche' => 480.00],
                ],
                'paxes' => [],
                'itinerario' => [
                    ['dia' => 0, 'titulo' => 'Llegada a Cusco & Monasterio', 'desc' => 'Bienvenida.'],
                    ['dia' => 1, 'titulo' => 'Cusco Challenge & Sacsayhuamán', 'desc' => 'Desafío y cultura.'],
                    ['dia' => 2, 'titulo' => 'Trek Patabamba a Huchuy Qosqo', 'desc' => 'Trek escénico andino.'],
                    ['dia' => 3, 'titulo' => 'Ceremonia de Pago a la Tierra en Valle Sagrado', 'desc' => 'Mística y ofrenda.'],
                    ['dia' => 4, 'titulo' => 'Ollantaytambo & Chinchero', 'desc' => 'Valle Sagrado tradicional.'],
                    ['dia' => 5, 'titulo' => 'Machu Picchu Ciudadela Clásica', 'desc' => 'Excursión a la Llaqta.'],
                    ['dia' => 6, 'titulo' => 'Traslado de Salida', 'desc' => 'Fin del tour.'],
                ],
                'servicios' => [
                    ['dia' => 0, 'hora' => '10:00', 'codigo' => 'APTHTL', 'estado' => 'pendiente'],
                    ['dia' => 1, 'hora' => '09:00', 'codigo' => '4CUZCH', 'estado' => 'pendiente'],
                    ['dia' => 2, 'hora' => '07:30', 'codigo' => 'TREK-HUCHUY', 'estado' => 'pendiente'],
                    ['dia' => 3, 'hora' => '10:00', 'codigo' => 'CEREM-VALLE', 'estado' => 'pendiente'],
                    ['dia' => 4, 'hora' => '08:30', 'codigo' => 'FDCHIO', 'estado' => 'pendiente'],
                    ['dia' => 4, 'hora' => '13:00', 'codigo' => 'ALM-CUCHARA-PALO', 'estado' => 'pendiente'],
                    ['dia' => 5, 'hora' => '09:00', 'codigo' => 'BUS-CONSETTUR-RT', 'estado' => 'pendiente'],
                    ['dia' => 6, 'hora' => '12:00', 'codigo' => 'APTHTL', 'estado' => 'pendiente'],
                ],
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

            // Servicios diarios basados en Tarifario Maestro
            if (! empty($def['servicios'])) {
                $paxCount = max(1, count($def['paxes'] ?? []));

                foreach ($def['servicios'] as $servData) {
                    $fechaServ = $inicio->copy()->addDays($servData['dia'])->toDateString();

                    // Resolver servicio en Tarifario Maestro si tiene código
                    $ts = null;
                    if (! empty($servData['codigo'])) {
                        $ts = $tarifarios->get($servData['codigo']);
                    }

                    $tipo = $servData['tipo'] ?? ($ts ? match ($ts->categoria) {
                        'traslado' => 'traslado',
                        'boleto' => 'entrada',
                        'alimentacion' => 'comida',
                        'ceremonia', 'trek' => 'actividad',
                        default => 'tour',
                    } : 'tour');

                    $titulo = $servData['titulo'] ?? ($ts ? $ts->nombre : 'Servicio Operativo');

                    // Costo unitario según escala de pasajeros
                    $costoUnitario = $servData['costo'] ?? ($ts ? $ts->calcularCosto($paxCount, false, 'adulto') : 0.0);
                    $costoPor = $servData['por'] ?? ($ts ? ($ts->tipo_calculo === 'por_grupo' ? 'grupo' : 'persona') : 'persona');
                    $moneda = $servData['moneda'] ?? ($ts ? $ts->moneda : 'USD');
                    $comisionPct = $servData['comision_pct'] ?? ($ts ? ($ts->comision_pct ?? 11.00) : 11.00);
                    $igvComision = $servData['igv_comision'] ?? ($ts ? ($ts->aplica_igv_comision ?? true) : true);
                    $tarifarioCodigo = $ts ? $ts->codigo : ($servData['codigo'] ?? null);

                    // Proveedor asignado
                    $provId = null;
                    if (! empty($servData['prov']) && isset($proveedores[$servData['prov']])) {
                        $provId = $proveedores[$servData['prov']]->id;
                    } elseif (! in_array($servData['estado'] ?? '', ['sin_asignar', 'pendiente'])) {
                        $provId = match ($tipo) {
                            'traslado' => $proveedores->firstWhere('nombre', 'VIP Executive Transports Cusco S.A.C.')?->id,
                            'guia' => $proveedores->firstWhere('nombre', 'AGOTUR Cusco (Asociación de Guías Oficiales)')?->id,
                            'entrada' => $proveedores->firstWhere('nombre', 'Dirección Desconcentrada de Cultura de Cusco (DDC)')?->id,
                            'comida' => $proveedores->firstWhere('nombre', 'Hacienda Huayoccari Restaurant & Private Museum')?->id ?? $proveedores->firstWhere('nombre', 'Chicha por Gastón Acurio - Cusco')?->id,
                            'tren' => $proveedores->firstWhere('nombre', 'PeruRail S.A.')?->id,
                            default => $proveedores->firstWhere('nombre', 'Perú Expert Cultural Guides S.A.C.')?->id,
                        };
                    }

                    $pagoSolicitado = ! empty($servData['pago_solicitado']);
                    $servicio = $programa->servicios()->create([
                        'fecha' => $fechaServ,
                        'hora' => $servData['hora'] ?? '09:00',
                        'tipo' => $tipo,
                        'tarifario_codigo' => $tarifarioCodigo,
                        'comision_pct' => $comisionPct,
                        'igv_comision' => $igvComision,
                        'titulo' => $titulo,
                        'proveedor_id' => $provId,
                        'punto_encuentro' => $tipo === 'traslado' ? 'Sala de llegadas / Lobby' : 'Lobby del Hotel',
                        'estado' => $servData['estado'] ?? 'confirmado',
                        'confirmacion' => $servData['confirmacion'] ?? ($servData['estado'] === 'confirmado' ? 'CF-2027-' . rand(1000, 9999) : null),
                        'costo' => $costoUnitario,
                        'costo_estimado' => $costoUnitario,
                        'moneda' => $moneda,
                        'costo_por' => $costoPor,
                        'visible_cliente' => true,
                        'pago_solicitado_at' => $pagoSolicitado ? Carbon::now()->subDay() : null,
                        'pago_solicitado_por' => $pagoSolicitado ? $usuarioAdmin?->id : null,
                        'orden_enviada_at' => in_array($servData['estado'] ?? '', ['solicitado', 'confirmado']) ? Carbon::parse($fechaServ)->subDays(10) : null,
                    ]);

                    // Pago a proveedor para servicios confirmados que ya pasaron o en programas cerrados
                    $pendientePago = ! empty($servData['pendiente_pago']);
                    $debeEstarPagado = ($servData['estado'] ?? '') === 'confirmado'
                        && ! $pendientePago
                        && (Carbon::parse($fechaServ)->lte($hoy) || $cerrado);

                    if ($debeEstarPagado) {
                        $servicio->update([
                            'pagado_at' => $fechaServ,
                        ]);

                        $montoReal = $costoPor === 'persona' ? $costoUnitario * $paxCount : $costoUnitario;

                        if ($montoReal > 0) {
                            ProveedorPago::create([
                                'servicio_id' => $servicio->id,
                                'fecha' => $fechaServ,
                                'monto' => $montoReal,
                                'moneda' => $moneda,
                                'metodo' => 'transferencia',
                                'referencia' => 'OP-PROV-'.str_pad((string) $servicio->id, 5, '0', STR_PAD_LEFT),
                            ]);
                        }
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
                    foreach ($def['pagos'] as $pagoData) {
                        $programa->pagos()->create([
                            'fecha' => $inicio->copy()->subDays($pagoData['dias_antes'])->toDateString(),
                            'monto' => $pagoData['monto'],
                            'tipo' => $pagoData['tipo'],
                            'metodo' => $pagoData['metodo'] ?? 'transferencia',
                            'referencia' => $pagoData['ref'] ?? null,
                        ]);
                    }
                }
            }

            // Aceptación del cliente si está confirmado
            if ($def['estado'] === 'confirmado' && ! empty($def['cliente'])) {
                try {
                    $propuesta = $programa->fotoDePropuesta();
                    $programa->aceptaciones()->create([
                        'nombre' => $cliente->nombre ?? 'Cliente',
                        'ip' => '190.234.102.18',
                        'navegador' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                        'total' => $propuesta['total'] ?? 0,
                        'propuesta' => $propuesta,
                        'huella' => Programa::huellaDe($propuesta),
                    ]);
                } catch (\Throwable $e) {
                    // Continuar si la foto no puede generarse en seeding
                }
            }

            // Checklist de tareas operativas para programas confirmados
            if ($programa->estado === 'confirmado' && ! $programa->plantilla) {
                $checklistDef = [
                    ['titulo' => 'Reconfirmar restricciones dietéticas y alergias con restaurantes', 'dias_antes' => 6, 'completada' => false],
                    ['titulo' => 'Enviar briefing final, vouchers y contacto de chofer al cliente', 'dias_antes' => 5, 'completada' => false],
                    ['titulo' => 'Emitir boletos de tren Hiram Bingham / Vistadome en PeruRail', 'dias_antes' => 30, 'completada' => true],
                    ['titulo' => 'Comprar boletos oficiales de ingreso a la Llaqta en DDC Cusco', 'dias_antes' => 45, 'completada' => true],
                    ['titulo' => 'Reconfirmar habitaciones y oxigenación con Belmond y Marriott', 'dias_antes' => 20, 'completada' => true],
                    ['titulo' => 'Asignar chofer y placa de Sprinter en VIP Transports', 'dias_antes' => 12, 'completada' => true],
                    ['titulo' => 'Asignar guía oficial colegiado bilingüe', 'dias_antes' => 10, 'completada' => true],
                ];
                foreach ($checklistDef as $tData) {
                    $completada = $tData['completada'];
                    $programa->tareas()->create([
                        'titulo' => $tData['titulo'],
                        'fecha_limite' => $inicio->copy()->subDays($tData['dias_antes'])->toDateString(),
                        'completada_at' => $completada ? $inicio->copy()->subDays($tData['dias_antes'] + 1) : null,
                        'completada_por' => $completada ? $usuarioAdmin?->id : null,
                    ]);
                }
            }
        }
    }
}