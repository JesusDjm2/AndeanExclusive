@php
    $locale = 'es';
    $adventure = [
        'route' => 'tour-islas-palomino',
        'title' => 'Viaje en Yate a las Islas Palomino',
        'crumb' => 'Islas Palomino',
        'eyebrow' => 'Nada con lobos marinos',
        'lead' => 'El mágico viaje a las Islas Palomino es un recorrido fantástico por el lado natural de la costa peruana: nada con lobos marinos en su hábitat natural.',
        'image' => 'img/tours/palomino-islands-swim.jpg',
        'image_alt' => 'Islas Palomino',
        'days' => 1,
        'facts' => [
            ['icon' => 'fa-map-marker', 'label' => 'Recorrido', 'value' => 'Callao - Islas Palomino'],
        ],
        'includes' => '<ul><li>Briefing.</li><li>Guía tripulante de primer nivel.</li><li>Transporte.</li><li>Tickets de admisión.</li><li>Snacks.</li><li>Equipo.</li></ul>',
        'excludes' => '<ul><li>Propinas.</li></ul>',
        'bring' => '<ul><li>Mochila pequeña, toalla personal, ropa de baño.</li><li>Cantimplora con agua, bloqueador solar, lentes de sol.</li></ul>',
    ];
@endphp
@extends('tours.adventure')

@section('metas')
    @include('layouts.seo-head', [
        'locale' => 'es',
        'title' => 'Viaje en Yate a las Islas Palomino | ' . config('seo.brand'),
        'description' => 'El mágico viaje a las Islas Palomino es un recorrido fantástico por el lado natural de la costa peruana: nada con lobos marinos en su hábitat natural.',
        'canonical' => route('tour-islas-palomino', [], true),
        'og_image' => 'img/tours/palomino-islands-swim.jpg',
        'og_type' => 'article',
    ])
@endsection

@section('overview')
    <p>
        El mágico viaje de medio día a las Islas Palomino es un recorrido fantástico por el lado
        natural de la costa peruana. Visitar un hábitat natural lleno de fauna marina es un
        privilegio que no muchos lugares ofrecen hoy en día, además de conocer una isla de la
        Marina de Guerra.
    </p>
    <p>
        Esta experiencia está destinada a todas las personas que cuenten con un estado de salud
        adecuado. Puede realizarse de manera individual para conocer gente nueva, en pareja o con
        amigos con quienes compartir emociones, y también en familia para vivir una aventura
        inolvidable.
    </p>
@endsection
