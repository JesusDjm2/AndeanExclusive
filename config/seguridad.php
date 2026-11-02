<?php

return [
    // Código por correo al entrar. Activarlo solo cuando el envío de correos del servidor esté comprobado.
    'doble_verificacion' => (bool) env('DOBLE_VERIFICACION', false),

    // Áreas que lo deben usar: las que pueden ver y cambiar todo.
    'doble_verificacion_roles' => ['administrador'],

    // Días que un equipo queda como de confianza después de verificarse.
    'dias_equipo_de_confianza' => 30,

    // Copias de la base de datos que se conservan en el servidor.
    'respaldos_a_conservar' => 14,
];
