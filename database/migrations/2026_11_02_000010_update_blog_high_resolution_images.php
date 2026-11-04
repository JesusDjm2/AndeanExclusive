<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Mapeo de imágenes en alta resolución para blogs en español e inglés.
     * Reemplaza miniaturas de baja resolución (540x313, 650x379) por
     * fotografía de lujo (1000px a 2737px) con proporciones y temas exactos.
     */
    public function up(): void
    {
        $esMap = [
            'el-nevado-Ausangate-Cusco' => [
                'imgThumb' => 'img/full/llama-ausangate.webp',
                'imgFull'  => 'img/full/llama-ausangate.webp',
            ],
            'inti-raymi-fiesta-del-sol' => [
                'imgThumb' => 'img/blog/inti-raymi-andean-exclusive.jpg',
                'imgFull'  => 'img/blog/INTI-RAYMI-FESTIVITY-exclusive.jpg',
            ],
            '10-lugares-en-cusco' => [
                'imgThumb' => 'img/full/calles-del-cusco-full.jpg',
                'imgFull'  => 'img/full/calles-del-cusco-full.jpg',
            ],
            'fortaleza-de-ollantaytambo' => [
                'imgThumb' => 'img/galeria/ollantaytambo-1.webp',
                'imgFull'  => 'img/blog/OLLANTAYTAMBO.webp',
            ],
            'recomendaciones-viajar-por-peru' => [
                'imgThumb' => 'img/blog/Como-viajar-a-Peru-Andean-Exclusive.webp',
                'imgFull'  => 'img/Fondos/Recorrido-Cusco-Machu-Picchu.webp',
            ],
            'gastronomia-peruana' => [
                'imgThumb' => 'img/blog/chiriuchu-andean-exclusive.jpg',
                'imgFull'  => 'img/full/gastronomia-peruana-Andean-Exclusive.webp',
            ],
            'semana-santa-en-cusco' => [
                'imgThumb' => 'img/blog/semana-santa-cusco.webp',
                'imgFull'  => 'img/full/semana-santa-full.jpg',
            ],
            'dia-de-la-pachamama' => [
                'imgThumb' => 'img/blog/pachamama-ceremony-andean-exclusive.jpg',
                'imgFull'  => 'img/Fondos/salkantay-valle-nevado.webp',
            ],
            'reserva-pacaya-samiria' => [
                'imgThumb' => 'img/full/Pacaya-Samiria-Peru.webp',
                'imgFull'  => 'img/full/Pacaya-Samiria-Peru.webp',
            ],
            'festividad-virgen-del-carmen' => [
                'imgThumb' => 'img/blog/virgen-del-carmen-andean.jpg',
                'imgFull'  => 'img/blog/virgen-del-carmen-andean-exclusive.jpg',
            ],
            'picnic-en-los-andes' => [
                'imgThumb' => 'img/blog/Luxury-picnic-lunch-in-Cusco-Andean-Exclusive-Tours-Travel-Agency.webp',
                'imgFull'  => 'img/Fondos/Luxury-picnic-lunch-in-Cusco-Andean-Exclusive-Tours-Travel-Agency.jpg',
            ],
            'lineas-de-nasca' => [
                'imgThumb' => 'img/full/nazca-lineas-fondo4_11zon.webp',
                'imgFull'  => 'img/full/nazca-lineas-fondo4_11zon.webp',
            ],
            'lima-ciudad-de-los-reyes' => [
                'imgThumb' => 'img/blog/lima-plaza-de-armas.jpg',
                'imgFull'  => 'img/blog/lima-plaza-de-armas.jpg',
            ],
            'corpus-christi-en-cusco' => [
                'imgThumb' => 'img/full/corpus-cristi.jpg',
                'imgFull'  => 'img/blog/Corpus-Christi-Cusco-ESCLUSIVE.jpg',
            ],
            'apachetas-en-la-cultura-andina' => [
                'imgThumb' => 'img/full/apacheta-full.jpg',
                'imgFull'  => 'img/galeria/apacheta-1_11zon.webp',
            ],
        ];

        foreach ($esMap as $slug => $data) {
            DB::table('esblogs')->where('slug', $slug)->update($data);
        }

        $enMap = [
            'Ausangate-mountain-snowy' => [
                'imgThumb' => 'img/full/llama-ausangate.webp',
                'imgFull'  => 'img/full/llama-ausangate.webp',
            ],
            'Inti-Raymi-Sun-Festival' => [
                'imgThumb' => 'img/blog/inti-raymi-andean-exclusive.jpg',
                'imgFull'  => 'img/blog/INTI-RAYMI-FESTIVITY-exclusive.jpg',
            ],
            'places-to-visit-in-cusco' => [
                'imgThumb' => 'img/full/calles-del-cusco-full.jpg',
                'imgFull'  => 'img/full/calles-del-cusco-full.jpg',
            ],
            'ollantaytambo-fortress' => [
                'imgThumb' => 'img/galeria/ollantaytambo-1.webp',
                'imgFull'  => 'img/blog/OLLANTAYTAMBO.webp',
            ],
            'recomendations-travel-to-peru' => [
                'imgThumb' => 'img/blog/Como-viajar-a-Peru-Andean-Exclusive.webp',
                'imgFull'  => 'img/Fondos/Recorrido-Cusco-Machu-Picchu.webp',
            ],
            'peruvian-gastronomy' => [
                'imgThumb' => 'img/blog/chiriuchu-andean-exclusive.jpg',
                'imgFull'  => 'img/full/gastronomia-peruana-Andean-Exclusive.webp',
            ],
            'holly-week' => [
                'imgThumb' => 'img/blog/semana-santa-cusco.webp',
                'imgFull'  => 'img/full/semana-santa-full.jpg',
            ],
            'mother-earth-day' => [
                'imgThumb' => 'img/blog/pachamama-ceremony-andean-exclusive.jpg',
                'imgFull'  => 'img/Fondos/salkantay-valle-nevado.webp',
            ],
            'pacaya-samiria-reserve' => [
                'imgThumb' => 'img/full/Pacaya-Samiria-Peru.webp',
                'imgFull'  => 'img/full/Pacaya-Samiria-Peru.webp',
            ],
            'virgen-del-carmen-fest' => [
                'imgThumb' => 'img/blog/virgen-del-carmen-andean.jpg',
                'imgFull'  => 'img/blog/virgen-del-carmen-andean-exclusive.jpg',
            ],
            'andean-picnic' => [
                'imgThumb' => 'img/blog/Luxury-picnic-lunch-in-Cusco-Andean-Exclusive-Tours-Travel-Agency.webp',
                'imgFull'  => 'img/Fondos/Luxury-picnic-lunch-in-Cusco-Andean-Exclusive-Tours-Travel-Agency.jpg',
            ],
            'nasca-lines' => [
                'imgThumb' => 'img/full/nazca-lineas-fondo4_11zon.webp',
                'imgFull'  => 'img/full/nazca-lineas-fondo4_11zon.webp',
            ],
            'lima-city-of-kings' => [
                'imgThumb' => 'img/blog/lima-plaza-de-armas.jpg',
                'imgFull'  => 'img/blog/lima-plaza-de-armas.jpg',
            ],
            'corpus-cristi-cusco-festivity' => [
                'imgThumb' => 'img/full/corpus-cristi.jpg',
                'imgFull'  => 'img/blog/Corpus-Christi-Cusco-ESCLUSIVE.jpg',
            ],
            'apachetas-in-andean-culture' => [
                'imgThumb' => 'img/full/apacheta-full.jpg',
                'imgFull'  => 'img/galeria/apacheta-1_11zon.webp',
            ],
        ];

        foreach ($enMap as $slug => $data) {
            DB::table('enblogs')->where('slug', $slug)->update($data);
        }
    }

    /**
     * Revertir cambios (vuelve a las rutas previas si fuera necesario).
     */
    public function down(): void
    {
        // No-op seguro para evitar dejar rutas rotas.
    }
};
