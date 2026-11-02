<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * Contenido de la ayuda del panel: botón "?" de cada pantalla y centro de ayuda (/ayuda).
 * Cada tema indica en qué rutas aparece ('rutas') y quién lo gestiona ('permiso'): quien no tiene ese permiso ve
 * el resumen y a quién acudir, sin los pasos. Con 'solo' => true, ni siquiera lo ve (p. ej. el margen para Reservas).
 * 'sin_permiso' hace lo contrario: el tema es solo para quien no tiene ese permiso.
 * 'tab' abre el tema al usar el botón "?" en esa pestaña de la ficha del programa.
 */
class Ayuda
{
    public const GUIA = 'docs/Guia-del-sistema-Andean-Exclusive.pdf';

    /** Grupo => etiqueta, rol al que pertenece y a quién acudir si no te corresponde. */
    public const GRUPOS = [
        'general' => ['etiqueta' => 'Para todas las áreas', 'rol' => null, 'consulta' => null],
        'programas' => ['etiqueta' => 'Programas y clientes', 'rol' => 'operaciones', 'consulta' => 'Lo gestiona Operaciones. Puedes consultarlo; si hay que cambiar algo, pídeselo a esa área.'],
        'reservas' => ['etiqueta' => 'Reservas y proveedores', 'rol' => 'reservas', 'consulta' => 'Lo gestiona Reservas. Puedes consultarlo; si hay que cambiar algo, pídeselo a esa área.'],
        'contabilidad' => ['etiqueta' => 'Cobros y pagos', 'rol' => 'contabilidad', 'consulta' => 'Lo gestiona Contabilidad. Puedes consultarlo; si hay que cambiar algo, pídeselo a esa área.'],
        'administracion' => ['etiqueta' => 'Administración', 'rol' => Permisos::ADMIN, 'consulta' => 'Lo gestiona Administración.'],
    ];

    /** Lo que hace cada área y por dónde empezar el día. */
    public const AREAS = [
        'operaciones' => [
            'trabajo' => 'Atiendes al cliente, cotizas, armas el programa, lo confirmas y le envías los correos.',
            'rutina' => ['Atiende la campana: datos enviados por clientes y servicios cancelados.', 'En Inicio, mira «Avisar al cliente» y las tareas por vencer.', 'Revisa la hoja de operaciones de hoy y de mañana.'],
        ],
        'reservas' => [
            'trabajo' => 'Eliges proveedores, consigues las confirmaciones, emites órdenes de servicio y pides los pagos.',
            'rutina' => ['Atiende la campana: empieza por lo que requiere acción.', 'En Reservas, revisa Sin asignar, Por reservar y Solicitados. La franja roja avisa de lo urgente.', 'Mira la hoja de operaciones de mañana y la lista «Por confirmar».'],
        ],
        'contabilidad' => [
            'trabajo' => 'Registras lo que paga el cliente, pagas a los proveedores y vigilas el margen de cada viaje.',
            'rutina' => ['Atiende la campana: pagos solicitados, costos altos y programas recién confirmados.', 'En Inicio, revisa «Cobros por gestionar».', 'En Cuentas por pagar, revisa Pago solicitado y Vencidas.'],
        ],
        Permisos::ADMIN => [
            'trabajo' => 'Puedes hacer lo de todas las áreas. Además, manejas los usuarios, los agentes y el sitio web.',
            'rutina' => ['En Notificaciones, filtra por área para ver lo que lleva tiempo sin atenderse.', 'En Movimientos, aclara qué pasó con algo y quién lo hizo.', 'En Inicio, revisa el estado del contenido del sitio web.'],
        ],
    ];

    /** El recorrido de un viaje: [área, qué pasa]. */
    public const VIAJE = [
        ['Operaciones', 'Atiende la consulta y crea la cotización con el cliente, los viajeros, los hoteles, los días y el precio. Se la envía al cliente.'],
        ['Operaciones', 'El cliente acepta y el programa pasa a Confirmado. Reservas y Contabilidad reciben el aviso.'],
        ['Contabilidad', 'Registra el depósito del 50 %, que vence a los 7 días de la confirmación.'],
        ['Reservas', 'Asigna proveedores, consigue las confirmaciones y envía las órdenes de servicio.'],
        ['Reservas y Contabilidad', 'Reservas solicita los pagos y Contabilidad los registra.'],
        ['Cliente', 'Completa los datos de sus viajeros desde su enlace privado. Operaciones los revisa.'],
        ['Contabilidad', 'Registra el saldo, que vence 60 días antes del viaje.'],
        ['Operaciones y Reservas', 'Durante el viaje siguen cada día la hoja de operaciones.'],
        ['Operaciones', 'Al terminar, envía la encuesta. Contabilidad paga lo pendiente y revisa el margen.'],
    ];

    public const PROBLEMAS = [
        ['No veo un botón que otra persona sí ve', 'Esa acción pertenece a otra área. Pídesela a quien corresponda.'],
        ['Una pantalla dice que no tengo permiso (error 403)', 'Tu área no puede hacer eso. Si crees que deberías, habla con Administración.'],
        ['«Un programa confirmado necesita fecha de inicio»', 'Completa inicio y fin en la pestaña Datos antes de confirmar.'],
        ['Un punto rojo en una pestaña del programa', 'Hay un dato por corregir en esa pestaña. Ábrela y busca el campo marcado.'],
        ['«Para confirmar… indica el n.º de confirmación»', 'Escribe el número o código que te dio el proveedor.'],
        ['«…no tiene proveedor: asígnalo antes»', 'Pulsa primero «Asignar proveedor».'],
        ['No encuentro un servicio en Reservas', 'Por defecto se ven los próximos 30 días. Cambia el periodo a «Todos los futuros» y mira todas las pestañas.'],
        ['Un programa no sale en la hoja de operaciones', 'Solo aparecen los programas confirmados. Revisa también la fecha elegida.'],
        ['Un servicio no le aparece al cliente', 'Revisa en Detalles «Mostrar en el itinerario del cliente» y que su fecha esté dentro del viaje.'],
        ['«…no se quitaron: primero Contabilidad debe anular esos pagos»', 'El servicio tiene pagos. Pide a Contabilidad que los anule o cancélalo con Reservas.'],
        ['«No se puede eliminar…» un cliente, hotel o habitación', 'Está en uso en algún programa. Primero cambia o quita ese uso.'],
        ['Los botones de correo aparecen apagados', 'El cliente no tiene correo. Agrégalo en su ficha.'],
        ['No puedo entrar', 'Usa «¿La olvidaste?» o espera un minuto si fallaste varias veces. Si sigue igual, pide a Administración que revise tu usuario.'],
    ];

    public const GLOSARIO = [
        ['Programa', 'Un viaje completo de un cliente, con su código propio (AET-2027-0001).'],
        ['Cotización, Confirmado, Cancelado', 'Los estados de un programa: aún se vende, ya se vendió o ya no va.'],
        ['Cliente', 'Quien contrata: una persona o una agencia. Es quien recibe los correos.'],
        ['Pasajero o «pax»', 'Cada persona que viaja.'],
        ['Servicio', 'Cada cosa que se contrata para el viaje: traslado, guía, tren, entrada, comida.'],
        ['Orden de servicio', 'Un PDF para el proveedor con lo que debe hacer, cuándo y para quiénes.'],
        ['Cobro y pago', 'Cobro: dinero que entra, lo paga el cliente. Pago: dinero que sale, se lo pagamos a un proveedor.'],
        ['Depósito y saldo', 'El depósito es el 50 % que el cliente paga al confirmar. El saldo es lo que falta.'],
        ['Costo estimado y costo real', 'Estimado: lo que se calculó al cotizar. Real: lo que cobra el proveedor elegido.'],
        ['Margen', 'Lo que le queda a la agencia: venta menos costos. Solo lo ven Contabilidad y Administración.'],
        ['Notificación', 'Un aviso interno para tu área. El cliente nunca lo ve.'],
        ['Movimiento', 'Una línea del registro: quién hizo qué y cuándo. Nadie puede modificarlo.'],
    ];

    public const TEMAS = [
        // ---- Para todas las áreas ----
        'ayuda' => [
            'grupo' => 'general', 'rutas' => ['ayuda.*'], 'titulo' => 'Cómo usar esta ayuda',
            'resumen' => 'Aquí están todos los temas del sistema. Lo que le toca a tu área aparece con sus pasos; lo de otras áreas, como «Solo consulta».',
            'pasos' => ['Escribe en el buscador lo que necesitas hacer, como «anular» o «pasaporte».', 'Pulsa un tema para abrirlo.', 'En cualquier otra pantalla, el botón ? de arriba muestra solo lo que explica esa pantalla.'],
        ],
        'ficha-consulta' => [
            'grupo' => 'general', 'rutas' => ['programas.edit'], 'sin_permiso' => 'programas.gestionar', 'titulo' => 'La ficha de un programa en modo consulta',
            'resumen' => 'Puedes ver toda la información del viaje. Los campos aparecen bloqueados a propósito, porque esos datos los modifica Operaciones.',
            'pasos' => ['Recorre las pestañas para consultar pasajeros, hoteles, itinerario y precios.', 'Si estás en Reservas, gestiona los servicios desde la pantalla Reservas.', 'Si estás en Contabilidad, registra los cobros en la pestaña Pagos.', 'Las tareas sí puedes agregarlas y marcarlas en su pestaña.', 'Si hay que cambiar un dato, pídeselo a Operaciones.'],
        ],
        'inicio' => [
            'grupo' => 'general', 'rutas' => ['home'], 'titulo' => 'Inicio: tu resumen del día',
            'resumen' => 'Muestra los viajes en curso y próximos, los servicios de hoy y mañana, las tareas por vencer, los cobros por gestionar y los últimos movimientos.',
            'pasos' => ['Empieza el día aquí para ver lo más urgente.', 'Pulsa un número o un recuadro para abrir el detalle.', 'En «Avisar al cliente» aparecen los correos que conviene enviar hoy.'],
        ],
        'notificaciones' => [
            'grupo' => 'general', 'rutas' => ['avisos.*'], 'titulo' => 'Notificaciones: lo que te toca hacer',
            'resumen' => 'Avisos internos para tu área. Cada uno lleva a la pantalla donde se resuelve.',
            'pasos' => ['La campana muestra cuántas tienes sin ver y se actualiza sola cada minuto.', 'Pulsa el botón de la notificación: abre la pantalla exacta y la marca como vista.', 'Con el icono del ojo la marcas como vista o no vista sin abrirla.'],
            'notas' => ['«Requiere acción» indica que debes hacer algo. «Resuelto» significa que el asunto ya se atendió.', 'Lo visto se guarda por persona: que tú la veas no la marca como vista para tus compañeros.'],
        ],
        'movimientos' => [
            'grupo' => 'general', 'rutas' => ['movimientos.*'], 'titulo' => 'Movimientos: quién hizo qué',
            'resumen' => 'El registro de todo lo que pasa en la agencia. Nadie puede modificarlo.',
            'pasos' => ['Filtra por persona, programa, área, tipo o fechas.', 'En cada programa, la pestaña Historial muestra solo los suyos.'],
            'notas' => ['Los datos personales, como pasaportes o alergias, aparecen como «modificado», sin el valor.'],
        ],
        'hoja' => [
            'grupo' => 'general', 'rutas' => ['operaciones.*'], 'titulo' => 'Hoja de operaciones: el día a día',
            'resumen' => 'Los servicios y los movimientos de hotel de un día, con todos los grupos ordenados por hora. Solo incluye programas confirmados.',
            'pasos' => ['Con las flechas o el calendario cambia de día.', 'Cada servicio trae el punto de encuentro, el proveedor con su teléfono, los viajeros y sus dietas o alergias.', 'A la derecha está lo que falta confirmar en los próximos 14 días.', 'Pulsa «Imprimir» para sacar una hoja limpia para el equipo de campo.'],
        ],

        // ---- Programas y clientes ----
        'consultas' => [
            'grupo' => 'programas', 'rutas' => ['consultas.*'], 'permiso' => 'consultas.gestionar', 'titulo' => 'Atender consultas',
            'resumen' => 'Los formularios de la web entran solos aquí y avisan a Operaciones. Cada consulta avanza de Nueva a Cotizada, y termina Ganada o Perdida.',
            'pasos' => ['Abre la consulta y responde al interesado por correo o WhatsApp.', 'Cambia el estado a En gestión, elige quién la atiende y deja notas.', 'Pulsa «Crear cotización»: se crea el cliente y se abre un programa con sus datos.', 'Si no prospera, márcala como Perdida e indica el motivo.', 'Para lo que llega por otro canal, usa «Nueva consulta».'],
            'notas' => ['Con programa, el estado cambia solo: se gana al confirmarlo y se pierde al cancelarlo.', 'El mismo correo no crea un cliente repetido.'],
        ],
        'prog-lista' => [
            'grupo' => 'programas', 'rutas' => ['programas.index', 'programas.por-periodo'], 'titulo' => 'Buscar y abrir programas',
            'resumen' => 'La lista de todos los viajes, separados en próximos, finalizados y sin fechas.',
            'pasos' => ['Busca por nombre, código, correo o pasajero, y filtra por año, mes y estado.', 'Pulsa «Detalle» para ver pasajeros, hoteles y presupuesto sin abrir el programa.', 'El botón de tres puntos abre las acciones: ver itinerario, copiar enlace, ver PDF y más.', '«Por año y mes» muestra los viajes como un calendario.'],
        ],
        'prog-datos' => [
            'grupo' => 'programas', 'rutas' => ['programas.create', 'programas.edit'], 'tab' => 'datos', 'permiso' => 'programas.gestionar', 'titulo' => 'Crear un programa',
            'resumen' => 'Los datos básicos del viaje. Basta con el nombre y el cliente; el resto puedes completarlo después.',
            'pasos' => ['Escribe el nombre del programa. El código déjalo vacío: el sistema pone el siguiente.', 'Deja el estado en Cotización y elige el idioma del itinerario.', 'Indica inicio y fin si ya los conoces. Si pones una fecha, debes poner la otra.', 'Elige un cliente existente o marca «Nuevo cliente».', 'Marca los agentes a cargo: el cliente los verá como su contacto.', 'Elige la imagen de portada y escribe la descripción del viaje.', 'Pulsa «Crear programa». Las pestañas Itinerario, Pagos, Tareas e Historial aparecen después.'],
        ],
        'prog-plantillas' => [
            'grupo' => 'programas', 'rutas' => ['programas.index', 'programas.plantillas', 'programas.duplicar'], 'permiso' => 'programas.gestionar', 'titulo' => 'Duplicar viajes y usar plantillas',
            'resumen' => 'Para no armar desde cero lo que se vende seguido. Se copian días, hoteles, servicios y precios, con las fechas corridas. No se copian pasajeros, pagos ni confirmaciones.',
            'pasos' => ['En el menú ⋮ de un programa, elige «Duplicar» o «Guardar como plantilla».', 'En «Plantillas», pulsa «Usar» sobre la que necesites.', 'Escribe el nombre, el cliente y la fecha de inicio, y pulsa «Crear cotización».', 'Revisa precios y proveedores: la nueva cotización ya se puede editar.'],
            'notas' => ['Una plantilla no aparece en listados, reservas ni cuentas.'],
        ],
        'prog-pasajeros' => [
            'grupo' => 'programas', 'rutas' => ['programas.create', 'programas.edit'], 'tab' => 'pasajeros', 'permiso' => 'programas.gestionar', 'titulo' => 'Cargar a los pasajeros',
            'resumen' => 'Las personas que viajan. Su edad al iniciar el viaje define la tarifa: adulto desde 12 años, niño de 5 a 11 y sin costo los menores de 5.',
            'pasos' => ['Pulsa «Agregar pasajero».', 'Escribe el nombre como en el pasaporte, la fecha de nacimiento y el país: son obligatorios.', 'Agrega si los tienes el pasaporte, la dieta y las alergias.', 'Guarda con «Guardar cambios».'],
            'notas' => ['El cliente puede completar los datos: usa «Copiar enlace» o «Pedírselo por correo».', 'La alerta amarilla indica pasaportes que faltan o vencen antes de 6 meses desde el viaje.'],
        ],
        'prog-alojamiento' => [
            'grupo' => 'programas', 'rutas' => ['programas.create', 'programas.edit'], 'tab' => 'alojamiento', 'permiso' => 'programas.gestionar', 'titulo' => 'Asignar los hoteles',
            'resumen' => 'Dónde duerme el grupo cada noche. Por ejemplo, 3 habitaciones dobles de un hotel son una fila con cantidad 3.',
            'pasos' => ['Pulsa «Agregar habitación».', 'Elige el hotel y la habitación, y escribe la cantidad, las fechas y el costo por noche de cada habitación.', 'Agrega una fila por cada tipo de habitación y por cada estadía. El mismo hotel puede repetirse.', 'Guarda los cambios. Cada fila con fechas crea su servicio en Itinerario, con el hotel como proveedor.'],
            'notas' => ['El sistema avisa si una noche no hay plazas para todos, pero te deja guardar.', 'Reservas confirma el hotel y Contabilidad lo paga como cualquier otro servicio.', 'Si un programa antiguo ya tiene costos de hotel cargados a mano, no se crean otros para no contarlos dos veces.'],
        ],
        'prog-itinerario' => [
            'grupo' => 'programas', 'rutas' => ['programas.edit'], 'tab' => 'itinerario', 'permiso' => 'programas.gestionar', 'titulo' => 'Armar el itinerario',
            'resumen' => 'El viaje día a día: lo que lee el cliente y los servicios que se contratan. Los días se crean solos a partir de las fechas.',
            'pasos' => ['Escribe el título y la descripción de cada día.', 'Pulsa «Agregar servicio» y completa la hora, el tipo y el nombre.', 'Abre «Detalles» para indicar el punto de encuentro, el costo estimado, la moneda y si se cobra por grupo o por persona.', 'Decide también el texto para el cliente, las notas internas y si el servicio se muestra en su itinerario.', 'Pulsa «Guardar cambios».'],
            'notas' => ['Proveedor, estado y número de confirmación los maneja Reservas.', 'Las notas internas salen en la orden de servicio que recibe el proveedor.', 'Si un servicio queda «Fuera de las fechas», el cliente deja de verlo: cambia su fecha o quítalo.'],
        ],
        'prog-precios' => [
            'grupo' => 'programas', 'rutas' => ['programas.create', 'programas.edit'], 'tab' => 'precios', 'permiso' => 'programas.gestionar', 'titulo' => 'Poner el precio',
            'resumen' => 'El precio de venta es el costo por persona más el porcentaje de ganancia. El cliente solo ve el precio de venta.',
            'pasos' => ['Escribe el costo por persona de adulto y niño, y la ganancia.', 'Si hay habitaciones individuales, escribe el suplemento y cuántas son.', 'Revisa el tipo de cambio: si lo dejas vacío, se usa 3.75.', 'Comprueba el total y el depósito en el recuadro, y guarda.'],
            'notas' => ['El suplemento no se relaciona con la pestaña Alojamiento: comprueba que coincida con lo reservado.'],
        ],
        'prog-enviar' => [
            'grupo' => 'programas', 'rutas' => ['programas.index', 'programas.edit'], 'tab' => 'datos', 'permiso' => 'programas.gestionar', 'titulo' => 'Enviar la propuesta al cliente',
            'resumen' => 'El cliente recibe un enlace privado, sin contraseña, y un PDF con portada. No ve costos, ganancia ni proveedores.',
            'pasos' => ['Pulsa «Ver itinerario» y revisa lo que verá el cliente.', 'Usa «Copiar enlace» para enviarlo por WhatsApp o correo.', '«PDF» abre una vista previa con opción de descargar.', 'En la lista de Programas, «Enviar por correo» manda el PDF al cliente, previa confirmación.'],
            'notas' => ['Quien tenga el enlace puede ver el itinerario: envíalo solo al cliente.'],
        ],
        'prog-confirmar' => [
            'grupo' => 'programas', 'rutas' => ['programas.edit'], 'tab' => 'datos', 'permiso' => 'programas.gestionar', 'titulo' => 'Confirmar o cancelar un viaje',
            'resumen' => 'Al confirmar, Reservas y Contabilidad reciben el aviso, y el viaje entra en la hoja de operaciones.',
            'pasos' => ['En la pestaña Datos, cambia el estado a Confirmado o Cancelado.', 'Para confirmar, comprueba que tenga inicio y fin.', 'Pulsa «Guardar cambios».'],
            'notas' => ['Cancelar no borra el programa y avisa a las áreas que deben actuar.'],
        ],
        'prog-correos' => [
            'grupo' => 'programas', 'rutas' => ['programas.edit'], 'tab' => 'pagos', 'permiso' => 'programas.gestionar', 'titulo' => 'Enviar correos al cliente',
            'resumen' => 'Recordatorios de depósito y saldo, pedido de datos y encuesta. El sistema nunca escribe solo al cliente: los envía una persona.',
            'consulta' => 'Los envía Operaciones. Si hace falta un recordatorio, pídeselo a esa área.',
            'pasos' => ['En la pestaña Pagos, ve a «Correos al cliente».', 'El botón resaltado con «Sugerido» es el que conviene enviar hoy.', 'Púlsalo y confirma. Debajo queda registrado cuándo se envió y quién lo hizo.'],
            'notas' => ['Si el cliente no tiene correo, los botones aparecen apagados: agrégalo en su ficha.'],
        ],
        'prog-tareas' => [
            'grupo' => 'programas', 'rutas' => ['programas.edit'], 'tab' => 'tareas', 'permiso' => 'tareas.gestionar', 'titulo' => 'Controlar los pendientes con Tareas',
            'resumen' => 'Los pendientes de cada viaje. Las que vencen pronto también aparecen en Inicio.',
            'pasos' => ['Marca lo que necesites de la «Lista de control Perú» y pulsa «Agregar seleccionadas». La fecha límite se calcula desde el inicio del viaje.', 'Para algo especial, usa «Nueva tarea».', 'Marca la casilla cuando esté hecha: queda registrado quién lo hizo y cuándo.'],
        ],
        'prog-historial' => [
            'grupo' => 'programas', 'rutas' => ['programas.edit'], 'tab' => 'historial', 'titulo' => 'Historial del programa',
            'resumen' => 'Quién cambió qué en este viaje, en todas las áreas. Sirve para aclarar dudas y nadie puede modificarlo.',
            'pasos' => ['Lee de arriba abajo: lo más reciente aparece primero.'],
        ],
        'clientes' => [
            'grupo' => 'programas', 'rutas' => ['clientes.*'], 'permiso' => 'programas.gestionar', 'titulo' => 'Clientes',
            'resumen' => 'Las fichas de quienes contratan: personas o agencias. Un cliente sirve para varios viajes.',
            'pasos' => ['Busca por nombre, agencia, correo o teléfono.', 'Pulsa «Nuevo cliente» o abre una ficha para editarla.', 'Si es una agencia, escribe también el nombre de la empresa.'],
            'notas' => ['Un cliente con programas no se puede eliminar.'],
        ],
        'pasajeros' => [
            'grupo' => 'programas', 'rutas' => ['paxs.*'], 'permiso' => 'programas.gestionar', 'titulo' => 'Pasajeros',
            'resumen' => 'Todas las personas que viajan, de todos los programas. Normalmente se cargan desde la ficha de cada programa.',
            'pasos' => ['Busca a una persona para ver a qué programa pertenece.', 'Para cargar a un grupo, usa la pestaña Pasajeros del programa.'],
            'notas' => ['Los pasaportes los ven Operaciones, Reservas y Administración. Cuando alguien abre un archivo, queda anotado en Movimientos.'],
        ],

        // ---- Reservas y proveedores ----
        'res-bandejas' => [
            'grupo' => 'reservas', 'rutas' => ['reservas.*'], 'permiso' => 'servicios.gestionar', 'titulo' => 'Las pestañas de Reservas',
            'resumen' => 'Cada servicio avanza por estas pestañas: Sin asignar, Por reservar, Solicitados, Confirmados o Cancelados.',
            'pasos' => ['Sin asignar: elige el proveedor.', 'Por reservar: pídelo al proveedor.', 'Solicitados: haz seguimiento y confírmalo.', 'Confirmados: envía la orden y pide el pago.'],
            'notas' => ['La pantalla muestra por defecto los próximos 30 días. Para un viaje lejano, elige «Todos los futuros».', 'La franja roja avisa de servicios sin confirmar en los próximos 3 días.'],
        ],
        'res-asignar' => [
            'grupo' => 'reservas', 'rutas' => ['reservas.*', 'operaciones.*'], 'permiso' => 'servicios.gestionar', 'titulo' => 'Asignar un proveedor',
            'resumen' => 'Indica quién presta el servicio y cuánto cobra de verdad. Con ese monto se le paga y se calcula el margen.',
            'pasos' => ['En Sin asignar, pulsa «Asignar proveedor».', 'Elige el proveedor y escribe el costo real.', 'Pulsa «Asignar». El servicio pasa a Por reservar.'],
            'notas' => ['Si el costo supera lo cotizado, Contabilidad recibe un aviso.', 'Cambiar de proveedor en un servicio ya solicitado o confirmado lo devuelve a Por reservar y borra el número de confirmación.'],
        ],
        'res-confirmar' => [
            'grupo' => 'reservas', 'rutas' => ['reservas.*', 'operaciones.*'], 'permiso' => 'servicios.gestionar', 'titulo' => 'Solicitar, confirmar o cancelar',
            'resumen' => 'Deja constancia de cómo va cada reserva. Para confirmar hace falta el número del proveedor.',
            'pasos' => ['Contacta al proveedor: su teléfono en la lista abre WhatsApp.', 'Pulsa «Solicitar / confirmar» y elige Solicitado.', 'Cuando responda, elige Confirmado y escribe el número de confirmación.', 'Para cancelar, elige Cancelado en la misma ventana.'],
            'notas' => ['Para varios a la vez, marca sus casillas, elige la acción arriba y pulsa «Aplicar».'],
        ],
        'res-orden' => [
            'grupo' => 'reservas', 'rutas' => ['reservas.*', 'operaciones.*'], 'titulo' => 'Orden de servicio e historial',
            'resumen' => 'La orden es un PDF para el proveedor con el servicio, la fecha, los viajeros, sus dietas y las notas internas.',
            'pasos' => ['Pulsa el icono de documento para abrir la orden.', 'Descárgala y envíasela al proveedor: el sistema no se la envía solo.', 'El icono del reloj muestra los cambios de estado, las notas y los pagos del servicio.'],
        ],
        'res-pago' => [
            'grupo' => 'reservas', 'rutas' => ['reservas.*'], 'permiso' => 'pagos.solicitar', 'titulo' => 'Pedir un pago a Contabilidad',
            'resumen' => 'Avisa a Contabilidad de que hay que pagar un servicio. Se puede pedir antes de confirmar si el proveedor quiere un adelanto.',
            'consulta' => 'Lo solicita Reservas y lo registra Contabilidad.',
            'pasos' => ['En el servicio, pulsa «Solicitar pago».', 'Contabilidad recibe el aviso con el proveedor y el monto.', 'Si te equivocaste, pulsa «Retirar solicitud».', 'Cuando se pague, recibirás un aviso.'],
        ],
        'proveedores' => [
            'grupo' => 'reservas', 'rutas' => ['proveedors.*', 'categoriasproveedor.*'], 'permiso' => 'proveedores.gestionar', 'titulo' => 'Proveedores y categorías',
            'resumen' => 'Las empresas y personas que prestan los servicios, agrupadas por categoría.',
            'pasos' => ['Pulsa «Nuevo proveedor» y escribe nombre, categoría, teléfono y correo. El RUC es opcional.', 'Las categorías, como Transporte o Guías, se manejan desde el menú Categorías.'],
            'notas' => ['El teléfono es el que aparece en la hoja del día y abre WhatsApp.'],
        ],
        'hoteles' => [
            'grupo' => 'reservas', 'rutas' => ['hotel.*', 'habitacion.*'], 'permiso' => 'proveedores.gestionar', 'titulo' => 'Hoteles y habitaciones',
            'resumen' => 'Los hoteles que Operaciones puede elegir en los programas, con sus tipos de habitación.',
            'pasos' => ['Pulsa «Nuevo Hotel» y completa sus datos.', 'Pulsa «Agregar habitación» para cada tipo, con su capacidad.', 'Guarda.'],
            'notas' => ['Un hotel o una habitación en uso no se puede eliminar.'],
        ],

        // ---- Cobros y pagos ----
        'cobros' => [
            'grupo' => 'contabilidad', 'rutas' => ['programas.edit'], 'tab' => 'pagos', 'permiso' => 'cobros.gestionar', 'titulo' => 'Registrar lo que paga el cliente',
            'resumen' => 'La pestaña Pagos muestra el total, lo pagado, el saldo y su vencimiento. El depósito es el 50 % y el saldo vence 60 días antes del viaje.',
            'pasos' => ['Comprueba que el dinero llegó a la cuenta.', 'En «Registrar pago», revisa la fecha y el monto: el sistema propone lo que falta.', 'Elige el tipo y el método, y escribe la referencia.', 'Pulsa «Registrar pago». Operaciones recibe el aviso.'],
        ],
        'cuentas' => [
            'grupo' => 'contabilidad', 'rutas' => ['cuentas.*'], 'permiso' => 'pagos.registrar', 'titulo' => 'Pagar a los proveedores',
            'resumen' => 'Lo que se debe a cada proveedor. Cada servicio vence, a más tardar, el día en que se presta.',
            'pasos' => ['Revisa «Pago solicitado» y «Vencidas».', 'Para un pago total o parcial, pulsa «Pagar», completa los datos y adjunta el comprobante.', 'Para varios servicios de un proveedor, marca las casillas y pulsa «Marcar como pagados». Se paga el saldo completo de cada uno, sin comprobante.'],
            'notas' => ['Los montos en soles y en dólares se suman por separado.', 'La fecha de un pago no puede ser futura.'],
        ],
        'anular' => [
            'grupo' => 'contabilidad', 'rutas' => ['cuentas.*', 'programas.edit'], 'tab' => 'pagos', 'permiso' => 'pagos.registrar', 'titulo' => 'Anular un cobro o un pago',
            'resumen' => 'Nada se borra del todo: el registro se conserva con el motivo, la persona y la fecha. Siempre hay que escribir el motivo.',
            'pasos' => ['Cobro al cliente: en la pestaña Pagos, pulsa la flecha roja de esa línea.', 'Un pago a proveedor: pulsa el icono del reloj del servicio y anúlalo desde su historial.', 'Todos los pagos de un servicio pagado: en la pestaña Pagadas, pulsa «Anular».'],
        ],
        'margen' => [
            'grupo' => 'contabilidad', 'rutas' => ['programas.edit'], 'tab' => 'precios', 'permiso' => 'margen.ver', 'solo' => true, 'titulo' => 'Vigilar el margen',
            'resumen' => 'En la pestaña Precios, «Costo y margen» compara lo cotizado con el costo actual de cada proveedor.',
            'pasos' => ['Compara el costo cotizado con el costo actual.', 'Revisa los servicios que costaron más de lo cotizado.', 'El margen se pone en naranja si bajó y en rojo si es negativo.'],
            'notas' => ['Si falta el costo por noche de un hotel, el sistema lo avisa al guardar: el margen saldría más alto de lo real.'],
        ],

        // ---- Administración ----
        'usuarios' => [
            'grupo' => 'administracion', 'rutas' => ['users.*'], 'permiso' => 'usuarios.gestionar', 'solo' => true, 'titulo' => 'Crear y administrar usuarios',
            'resumen' => 'Cada persona entra con su propio usuario, y su área define lo que puede modificar.',
            'pasos' => ['Pulsa «Nuevo Usuario».', 'Completa el nombre, el correo y la contraseña (al menos 8 caracteres), y repítela para confirmarla.', 'En «Rol en el panel», elige su área.', 'Pulsa «Crear usuario» y entrégale sus datos de forma privada.'],
            'notas' => ['Para cambiar de área, pulsa «Editar». El cambio queda en Movimientos.', 'Al eliminar a alguien, lo que hizo sigue en Movimientos con su nombre.', 'No compartan cuentas: el registro de quién hizo qué perdería su valor.'],
        ],
        'respaldos' => [
            'grupo' => 'administracion', 'rutas' => ['respaldos.*'], 'permiso' => 'usuarios.gestionar', 'solo' => true, 'titulo' => 'Copias de seguridad',
            'resumen' => 'Cada día se guarda una copia de la base de datos en el servidor, y se conservan las últimas 14.',
            'pasos' => ['Pulsa «Crear copia ahora» antes de un cambio importante.', 'Pulsa «Descargar» y guarda la copia fuera del servidor de vez en cuando.'],
            'notas' => ['Las copias tienen todos los datos de la agencia: guárdalas en un lugar seguro.', 'Los archivos de pasaportes y comprobantes no van en esta copia: se respaldan aparte, desde el hosting.', 'Guarda también el archivo .env: sin su clave no se pueden leer los pasaportes cifrados.'],
        ],
        'eliminar-programa' => [
            'grupo' => 'administracion', 'rutas' => ['programas.index'], 'permiso' => 'programas.eliminar', 'solo' => true, 'titulo' => 'Eliminar un programa',
            'resumen' => 'Borra el programa con sus pasajeros y no se puede deshacer.',
            'pasos' => ['En la lista, abre el menú de tres puntos y elige «Eliminar».'],
            'notas' => ['Si un cliente desiste, usa el estado Cancelado: avisa a las áreas y conserva la historia.'],
        ],
        'agentes' => [
            'grupo' => 'administracion', 'rutas' => ['agentes.*'], 'permiso' => 'agentes.gestionar', 'titulo' => 'Agentes',
            'resumen' => 'Las personas que atienden a los clientes. Operaciones los elige en cada programa, y el cliente los ve como su contacto.',
            'pasos' => ['Crea cada agente con su teléfono y correo.', 'Mantén sus datos al día: son los que ve el cliente.'],
        ],
        'sitio-web' => [
            'grupo' => 'administracion', 'rutas' => ['tours.*', 'estours.*', 'categories.*', 'categorias.*', 'enblogs.*', 'esblogs.*', 'entags.*', 'estags.*'], 'permiso' => 'contenido.gestionar', 'solo' => true, 'titulo' => 'Sitio web: tours y blog',
            'resumen' => 'El contenido público de la agencia. Cada elemento tiene una versión en inglés (EN) y otra en español (ES).',
            'pasos' => ['Elige EN o ES en el menú según el idioma que vas a editar.', 'Crea o edita el tour o el artículo y guarda.', 'En Inicio, «Contenido del sitio web» avisa de lo que falta traducir.'],
            'notas' => ['Cada idioma se edita por separado: cambiar uno no cambia el otro.'],
        ],
        'imagenes' => [
            'grupo' => 'administracion', 'rutas' => ['imagenes.*'], 'permiso' => 'contenido.gestionar', 'titulo' => 'Imágenes',
            'resumen' => 'La galería de fotos. Las que tienen suficiente resolución se pueden usar como portada de los programas.',
            'pasos' => ['Sube la imagen desde esta pantalla.', 'Si tiene suficiente resolución, aparecerá entre las portadas de los programas.'],
        ],
    ];

    /** Rol del usuario, para adaptar la ayuda a su área. */
    public static function rol(User $user): ?string
    {
        return $user->getRoleNames()->first();
    }

    public static function puede(array $tema, User $user): bool
    {
        return empty($tema['permiso']) || $user->can($tema['permiso']);
    }

    public static function visible(array $tema, User $user): bool
    {
        if (! empty($tema['sin_permiso']) && $user->can($tema['sin_permiso'])) {
            return false;
        }

        return empty($tema['solo']) || self::puede($tema, $user);
    }

    /** Temas de la pantalla actual: primero los que le tocan a esta persona. */
    public static function dePantalla(Request $request, User $user): array
    {
        $temas = array_filter(
            self::TEMAS,
            fn (array $tema) => self::visible($tema, $user) && $request->routeIs(...$tema['rutas'])
        );
        uasort($temas, fn (array $a, array $b) => self::puede($b, $user) <=> self::puede($a, $user));

        return $temas;
    }

    /** Todos los temas por grupo: primero lo general y lo de su área; el resto, después. */
    public static function porGrupo(User $user): array
    {
        $rol = self::rol($user);
        $grupos = [];
        foreach (self::GRUPOS as $clave => $grupo) {
            $temas = array_filter(self::TEMAS, fn (array $tema) => $tema['grupo'] === $clave && self::visible($tema, $user));
            if ($temas) {
                $grupos[$clave] = $grupo + ['temas' => $temas, 'propio' => $grupo['rol'] === $rol];
            }
        }
        uasort($grupos, fn (array $a, array $b) => ($b['rol'] === null) <=> ($a['rol'] === null) ?: $b['propio'] <=> $a['propio']);

        return $grupos;
    }

    public static function guiaDisponible(): bool
    {
        return is_file(base_path(self::GUIA));
    }
}
