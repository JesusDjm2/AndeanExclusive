<?php

/**
 * SEO central: URL canónica del sitio (producción). Sobrescribe con SEO_SITE_URL en .env si hace falta.
 */
$defaultSite = rtrim(env('SEO_SITE_URL', env('APP_URL', 'https://www.andeanexclusive.com')), '/');

return [

    'site_url' => $defaultSite,

    'brand' => 'Andean Exclusive Tours',

    'default_og_image' => '/img/machupicchu.png',

    'phone' => '+51-979-721-194',

    'address' => [
        'streetAddress' => 'Balconcillo Alto C-6',
        'addressLocality' => 'Cusco',
        'addressRegion' => 'Cusco',
        'postalCode' => '08000',
        'addressCountry' => 'PE',
    ],

    'geo' => [
        'latitude' => -13.5319,
        'longitude' => -71.9675,
    ],

    'same_as' => [
        'https://www.facebook.com/AndeanExclusiveTours',
        'https://www.instagram.com/andean.exclusive/',
        'https://twitter.com/AndeanExclusive',
        'https://www.pinterest.com/andeanexclusive/',
    ],

    /**
     * Silo EN: home → pillar (experiences, around, adventures, blog) → support (about, faqs…)
     * Silo ES: inicio → tours exclusivos, alrededor, aventuras, blog → nosotros, preguntas…
     * Cada entrada: route name de Laravel para URL canónica absoluta.
     */
    'pages' => [
        'en' => [
            'home' => [
                'title' => 'Luxury Travel Agency Peru & Cusco | Private Tours | Andean Exclusive Tours',
                'description' => 'Award-level private tours in Peru: Machu Picchu, Sacred Valley, Lima & Amazon. Bespoke itineraries, English-speaking guides, 24/7 support from Cusco.',
                'route' => 'index',
                'keywords' => 'luxury travel agency Peru, private tours Cusco, Machu Picchu luxury tour, bespoke Peru itinerary, English speaking guide Peru',
                'hreflang_es_route' => 'inicio',
            ],
            'around' => [
                'title' => 'Peru Multi-Destination Tours | Lima, Cusco, Arequipa & More | Andean Exclusive',
                'description' => 'Curated circuits across Peru: coast, Andes and rainforest. Luxury transport, hand-picked hotels and authentic experiences — designed by a Cusco-based DMC.',
                'route' => 'around',
                'keywords' => 'Peru circuit tours, multi day Peru itinerary, luxury Peru DMC, Cusco travel agency',
            ],
            'experiences' => [
                'title' => 'Exclusive Peru Tours | Luxury Cusco & Machu Picchu | Andean Exclusive Tours',
                'description' => 'Signature luxury experiences in Peru: private Machu Picchu, Sacred Valley, gastronomy and culture — small groups or fully private, white-glove service.',
                'route' => 'experiences',
                'keywords' => 'exclusive tours Peru, luxury Machu Picchu tour, private Sacred Valley, high end Cusco tours',
            ],
            'adventures' => [
                'title' => 'Adventure Tours Peru | Rainbow Mountain, Humantay & Treks | Andean Exclusive',
                'description' => 'Premium adventure days from Cusco: Rainbow Mountain, Humantay Lagoon, ATV & rafting — safety-first logistics, expert guides, comfort after the trail.',
                'route' => 'adventures',
                'keywords' => 'Rainbow Mountain private tour, Humantay Lake tour Peru, Cusco adventure tours luxury',
            ],
            'blog' => [
                'title' => 'Peru Travel Blog | Tips, Culture & Itineraries | Andean Exclusive Tours',
                'description' => 'Expert articles on Peru travel: when to visit, Cusco altitude, culture, food and sample routes — from a local luxury operator.',
                'route' => 'blog-en',
                'keywords' => 'Peru travel blog, Cusco travel tips, Machu Picchu planning, Peru itinerary ideas',
            ],
            'about' => [
                'title' => 'About Us | Local Luxury DMC in Cusco, Peru | Andean Exclusive Tours',
                'description' => 'Meet the team behind Andean Exclusive: Cusco-based travel designers crafting ethical, high-end journeys across Peru since 2019.',
                'route' => 'about',
            ],
            'testimonials' => [
                'title' => 'Traveler Reviews | Luxury Peru Tours | Andean Exclusive Tours',
                'description' => 'Real reviews from guests who traveled Peru with Andean Exclusive — private guides, seamless logistics and memorable experiences.',
                'route' => 'testimonials',
            ],
            'terms' => [
                'title' => 'Terms & Conditions | Andean Exclusive Tours',
                'description' => 'Terms and conditions for booking luxury tours and services with Andean Exclusive Tours, Peru.',
                'route' => 'terms',
            ],
            'faqs' => [
                'title' => 'FAQs | Booking & Traveling in Peru | Andean Exclusive Tours',
                'description' => 'Answers about payments, best season, altitude, passports and customizing your luxury Peru itinerary with Andean Exclusive.',
                'route' => 'faqs',
            ],
        ],
        'es' => [
            'home' => [
                'title' => 'Agencia de Viajes de Lujo en Perú y Cusco | Tours Privados | Andean Exclusive',
                'description' => 'Viajes a medida en Perú: Machu Picchu, Valle Sagrado, Lima y selva. Itinerarios exclusivos, guías en español e inglés y operación 24/7 desde Cusco.',
                'route' => 'inicio',
                'keywords' => 'agencia de viajes lujo Perú, tours privados Cusco, Machu Picchu exclusivo, viajes a medida Perú',
                'hreflang_en_route' => 'index',
            ],
            'experiencias' => [
                'title' => 'Tours Exclusivos en Perú | Cusco y Machu Picchu de Lujo | Andean Exclusive',
                'description' => 'Experiencias exclusivas en Perú: Machu Picchu privado, Valle Sagrado, gastronomía y cultura — grupos reducidos o 100% privados, servicio premium.',
                'route' => 'experiencias',
                'keywords' => 'tours exclusivos Perú, Machu Picchu lujo, Valle Sagrado privado, agencia viajes Cusco',
            ],
            'alrededor' => [
                'title' => 'Tours por Todo el Perú | Lima, Cusco, Arequipa y Más | Andean Exclusive',
                'description' => 'Circuitos por el Perú: costa, sierra y selva. Hoteles seleccionados, transporte de calidad y experiencias auténticas — operador receptivo en Cusco.',
                'route' => 'alrededor-de-peru',
                'keywords' => 'tours por Perú, circuito Perú lujo, agencia receptiva Cusco, viaje multidestino Perú',
            ],
            'caminatas' => [
                'title' => 'Tours de Aventura en Perú | Montaña de Colores, Humantay y Más | Andean Exclusive',
                'description' => 'Excursiones de aventura desde Cusco con estándar premium: Montaña de Colores, Laguna Humantay, cuatrimotos y rafting — guías expertos y logística segura.',
                'route' => 'caminatas',
                'keywords' => 'Montaña de Colores tour, Laguna Humantay Cusco, tours aventura Perú lujo',
            ],
            'blog' => [
                'title' => 'Blog de Turismo en Perú | Consejos e Itinerarios | Andean Exclusive Tours',
                'description' => 'Artículos para viajar a Perú: cuándo ir, aclimatación en Cusco, cultura, gastronomía y rutas sugeridas — por una agencia de lujo local.',
                'route' => 'blog-es',
                'keywords' => 'blog turismo Perú, consejos viaje Cusco, Machu Picchu información, itinerario Perú',
            ],
            'nosotros' => [
                'title' => 'Nosotros | Agencia de Viajes de Lujo en Cusco | Andean Exclusive Tours',
                'description' => 'Conoce a Andean Exclusive: equipo en Cusco diseñando viajes de lujo éticos y personalizados por todo el Perú.',
                'route' => 'nosotros',
            ],
            'testimonios' => [
                'title' => 'Testimonios | Viajeros en Perú con Andean Exclusive Tours',
                'description' => 'Opiniones reales de quienes viajaron al Perú con Andean Exclusive: guías privados, logística impecable y experiencias memorables.',
                'route' => 'testimonios',
            ],
            'preguntas' => [
                'title' => 'Preguntas Frecuentes | Reservas y Viajes en Perú | Andean Exclusive',
                'description' => 'Respuestas sobre pagos, mejor época, altura en Cusco, documentos y cómo personalizar tu viaje de lujo por el Perú.',
                'route' => 'preguntas-frecuentes',
            ],
        ],
    ],
];
