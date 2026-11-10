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
        ['No puedo entrar o veo «No tienes permiso para esta acción»', 'Entra a /logout para cerrar cualquier sesión residual e inicia sesión nuevamente con tu correo y contraseña.'],
        ['Al pulsar la foto de un tour o blog, ¿se sale de la página?', 'No, ahora se abre en un popup modal centrado en alta resolución para inspeccionarla sin salir de la lista ni perder los filtros activos.'],
        ['¿Cómo edito un tour o blog que estoy viendo en la web pública?', 'Si iniciaste sesión como Administrador en el panel, verás un botón flotante abajo a la izquierda para editarlo en 1 clic.'],
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
        ['Slug', 'La parte amigable y única de la dirección web (ej. /category/adventure-tours o /en/blog/machu-picchu).'],
        ['Lightbox / Popup modal', 'Ventana flotante sobre la misma página que muestra la foto ampliada sin recargar la vista.'],
        ['Taxonomía (Categorías / Tags)', 'Estructura para organizar y clasificar tours por tipo de experiencia y blogs por temática.'],
        ['Acceso de Edición Pública', 'Botón flotante visible solo para administradores autenticados que permite editar un tour o post directamente desde la web pública.'],
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
            'grupo' => 'programas', 'rutas' => ['programas.index', 'programas.por-periodo', 'programas.ficha'], 'titulo' => 'Buscar y abrir programas',
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
        'prog-aceptacion' => [
            'grupo' => 'programas', 'rutas' => ['programas.edit'], 'tab' => 'datos', 'permiso' => 'programas.gestionar', 'titulo' => 'La aceptación del cliente',
            'resumen' => 'Desde su enlace, el cliente acepta la propuesta con su nombre y los términos y condiciones. Queda constancia de lo aceptado.',
            'pasos' => ['Cuando acepte, recibirás un aviso y lo verás arriba en la ficha.', 'Revisa que todo esté bien y confirma el programa: la confirmación sigue siendo tuya.', 'Si cambias el precio, las fechas o los servicios, la ficha te avisa y el cliente puede volver a aceptar.'],
            'notas' => ['En la ficha, «Visto» indica la última vez que el cliente abrió su enlace.'],
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
        'res-enviar-orden' => [
            'grupo' => 'reservas', 'rutas' => ['reservas.*', 'operaciones.*'], 'permiso' => 'servicios.gestionar', 'titulo' => 'Escribir al proveedor y enviar órdenes',
            'resumen' => 'Cada servicio tiene su sobre: desde ahí se escribe al proveedor desde reservas@andeanexclusivetours.com y queda el hilo.',
            'pasos' => ['Pulsa el icono del sobre del servicio; el número indica cuántos mensajes hay.', 'Elige qué enviar (solicitar, confirmar con orden, modificar, cancelar o consulta) y revisa el texto.', 'La orden de servicio en PDF se adjunta marcando la casilla.', 'Para un hotel, abre el programa, ve a Alojamiento y pulsa el nombre del hotel para sacar su lista.'],
        ],
        'res-mensaje' => [
            'grupo' => 'reservas', 'rutas' => ['servicios.mensaje'], 'permiso' => 'servicios.gestionar', 'titulo' => 'Mensaje al proveedor',
            'resumen' => 'El correo sale desde reservas@andeanexclusivetours.com y el proveedor responde a esa casilla. Todo queda en el hilo del servicio.',
            'pasos' => ['Elige el tipo: el asunto y el texto se rellenan y puedes editarlos.', 'Solicitar pasa el servicio a «Solicitado»; cancelar lo pasa a «Cancelado».', 'Cuando el proveedor conteste, registra su respuesta: si confirmó, indica su n.º y el servicio queda confirmado; si no tiene disponibilidad, vuelve a «Por reservar».'],
        ],
        'res-mensajes' => [
            'grupo' => 'reservas', 'rutas' => ['mensajes.*'], 'titulo' => 'Mensajes a proveedores',
            'resumen' => 'Todo lo enviado a proveedores y sus respuestas, con lo que sigue esperando respuesta arriba.',
            'pasos' => ['«Esperando respuesta» lista las solicitudes sin contestar; en rojo, las de hace dos días o más.', 'Filtra por proveedor, tipo o programa.', 'La flecha abre el hilo del servicio para responder o registrar la respuesta.'],
        ],
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
        'tarifario' => [
            'grupo' => 'reservas', 'rutas' => ['tarifas.*'], 'permiso' => 'proveedores.gestionar', 'titulo' => 'Tarifario',
            'resumen' => 'Lo que cobra cada hotel y proveedor por temporada. El sistema lo propone al cotizar un hotel y al asignar un proveedor.',
            'pasos' => ['Pulsa «Nueva tarifa» y elige si es una habitación de hotel o un servicio de un proveedor.', 'Indica la temporada con sus fechas y el precio.', 'Crea una tarifa por cada temporada.'],
            'notas' => ['Al cotizar, el costo por noche se llena solo si hay tarifa vigente. Al asignar un proveedor, sus tarifas aparecen como botones.', 'Siempre puedes escribir otro precio: la tarifa es solo una sugerencia.'],
        ],
        'tarifario-maestro' => [
            'grupo' => 'programas', 'rutas' => ['tarifario-maestro.*', 'tarifario-categorias.*'], 'permiso' => 'proveedores.gestionar', 'titulo' => 'Tarifario Maestro Oficial 2027',
            'resumen' => 'Catálogo maestro de tours, traslados, boletos oficiales y experiencias escalonadas de 1 a 20 pasajeros con fórmula financiera de cotización.',
            'pasos' => [
                'Consulta la lista de servicios por categoría o busca por código (ej. FDAWA, BTG, APTHTL).',
                'Pulsa «Matriz» para ver la escala de 1 a 20 pasajeros, tarifas de adulto y niño, y el simulador en tiempo real.',
                'Al armar un programa, usa el botón de calculadora en cualquier fila para aplicar el costo oficial según el número de adultos y niños.',
            ],
            'notas' => [
                'La fórmula calcula costo neto + comisión de agencia + IGV sobre la comisión.',
                'Las tarifas de feriados aplican un suplemento oficial en servicios guiados.',
            ],
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
        'cerrar-programa' => [
            'grupo' => 'contabilidad', 'rutas' => ['programas.edit'], 'tab' => 'pagos', 'permiso' => 'programas.cerrar', 'titulo' => 'Cerrar un programa',
            'resumen' => 'Cuando el viaje terminó y está todo cobrado y pagado, se cierra para que nada cambie. Recibes un aviso cuando está listo.',
            'pasos' => ['Abre el programa: arriba verás lo que falta o el botón «Cerrar programa».', 'Pulsa el botón y confirma.', 'Si hay que corregir algo, pide a Administración que lo reabra.'],
        ],
        'margen' => [
            'grupo' => 'contabilidad', 'rutas' => ['programas.edit'], 'tab' => 'precios', 'permiso' => 'margen.ver', 'solo' => true, 'titulo' => 'Vigilar el margen',
            'resumen' => 'En la pestaña Precios, «Costo y margen» compara lo cotizado con el costo actual de cada proveedor.',
            'pasos' => ['Compara el costo cotizado con el costo actual.', 'Revisa los servicios que costaron más de lo cotizado.', 'El margen se pone en naranja si bajó y en rojo si es negativo.'],
            'notas' => ['Si falta el costo por noche de un hotel, el sistema lo avisa al guardar: el margen saldría más alto de lo real.'],
        ],

        // ---- Reportes ----
        'reportes' => [
            'grupo' => 'general', 'rutas' => ['reportes.*'], 'titulo' => 'Reportes',
            'resumen' => 'Ventas y cobros de los programas confirmados, por mes, agente y origen. Contabilidad y Administración ven además el costo, el margen y los proveedores.',
            'pasos' => ['Elige el periodo y pulsa «Ver». Cuenta los programas que inician en esas fechas.', 'Pulsa «Excel» en una tabla para descargarla.', 'Abajo verás cómo van las consultas y por qué se perdieron.'],
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
        'tours-catalogo' => [
            'grupo' => 'administracion', 'rutas' => ['tours.*', 'estours.*'], 'permiso' => 'contenido.gestionar', 'solo' => true, 'titulo' => 'Tours y Experiencias (EN / ES)',
            'resumen' => 'Catálogo comercial y público de experiencias de lujo. Cuenta con editor senior en 2 columnas, panel sticky lateral y gestión bilingüe independiente.',
            'pasos' => [
                'Crea o edita el tour completando título, duración, precio base por persona, recorrido y descripción corta.',
                'En el panel lateral, sube la portada Full HD y la miniatura con previsualización en vivo (dropzone).',
                'Usa CKEditor para redactar el itinerario día por día, inclusiones, exclusiones y notas importantes.',
                'Vincula el tour con su contraparte en el otro idioma (EN <-> ES) para navegación sincronizada en la web.',
                'En el listado, haz clic en la foto de portada para abrir el popup en alta resolución sin salir de la vista.',
            ],
            'notas' => [
                'Cada idioma se edita por separado: cambiar el tour en inglés no modifica el tour en español hasta que tú lo edites.',
                'Si estás logueado como administrador, puedes pulsar el botón flotante en la web pública para editar cualquier tour al instante.',
            ],
        ],
        'blogs-catalogo' => [
            'grupo' => 'administracion', 'rutas' => ['enblogs.*', 'esblogs.*'], 'permiso' => 'contenido.gestionar', 'solo' => true, 'titulo' => 'Blog y Artículos Editoriales (EN / ES)',
            'resumen' => 'Artículos de viaje, guías culturales y gastronómicas para inspirar a los viajeros y optimizar el posicionamiento SEO en Google.',
            'pasos' => [
                'Redacta el artículo usando el editor enriquecido con encabezados, listas y párrafos destacados.',
                'Asigna etiquetas temáticas (tags) usando el selector visual de chips para relacionar lecturas afines.',
                'Completa los metadatos SEO: palabras clave (keywords) y descripción corta para el resumen de búsqueda.',
                'Sube la imagen de portada y vincula el artículo con su versión en el idioma opuesto.',
            ],
            'notas' => [
                'El tiempo estimado de lectura se calcula automáticamente a razón de 200 palabras por minuto.',
                'Las fotos de portada en la tabla cuentan con visor lightbox interactivo al hacer clic.',
            ],
        ],
        'categorias-tours' => [
            'grupo' => 'administracion', 'rutas' => ['categories.*', 'categorias.*'], 'permiso' => 'contenido.gestionar', 'solo' => true, 'titulo' => 'Categorías y Colecciones de Tours',
            'resumen' => 'Taxonomía que agrupa las experiencias (ej. Aventura, Lujo, Machu Picchu) y genera páginas de aterrizaje públicas automáticas.',
            'pasos' => [
                'Escribe el nombre de la categoría: el slug para la URL canónica se genera automáticamente.',
                'Usa el candado para desbloquear la edición manual del slug si requieres una URL personalizada.',
                'Verifica en el recuadro de previsualización en vivo cómo se verá el chip antes de guardar.',
                'En la tabla, consulta el contador en tiempo real de cuántos tours activos tiene asignados cada categoría.',
            ],
            'notas' => [
                'Al eliminar una categoría, los tours vinculados se desasocian limpiamente pero nunca se borran.',
            ],
        ],
        'tags-blogs' => [
            'grupo' => 'administracion', 'rutas' => ['entags.*', 'estags.*'], 'permiso' => 'contenido.gestionar', 'solo' => true, 'titulo' => 'Etiquetas Temáticas de Blogs (Tags)',
            'resumen' => 'Etiquetas temáticas (#Traditions, #History, etc.) que permiten a los lectores descubrir artículos de intereses afines.',
            'pasos' => [
                'Crea el tag ingresando su nombre y slug URL.',
                'Asigna los tags desde el editor de artículos de blog mediante los chips de selección múltiple.',
                'Supervisa en el listado qué tags tienen artículos activos y cuáles están sin usar.',
            ],
            'notas' => [
                'Cada tag genera una página pública propia (/en/tag/slug) con el listado filtrado de artículos asociados.',
            ],
        ],
        'edicion-publica-admin' => [
            'grupo' => 'administracion', 'rutas' => ['tours.*', 'estours.*', 'categories.*', 'categorias.*', 'enblogs.*', 'esblogs.*', 'entags.*', 'estags.*', 'home'], 'permiso' => 'contenido.gestionar', 'solo' => true, 'titulo' => 'Acceso Rápido de Edición desde la Web Pública',
            'resumen' => 'Acceso directo exclusivo para administradores autenticados que navegan por el sitio web oficial andeanexclusivetours.com.',
            'pasos' => [
                'Inicia sesión normalmente en el panel de gestión con tu usuario administrador.',
                'Abre o visita cualquier tour, post de blog, categoría o tag en el sitio web público.',
                'Verás un botón flotante esmeralda con icono de lápiz en la esquina inferior izquierda.',
                'Púlsalo para abrir de inmediato la pantalla de edición en el panel en una pestaña nueva.',
            ],
            'notas' => [
                'Este botón está protegido con directivas de seguridad (@auth) y es 100% invisible para visitantes anónimos o clientes.',
            ],
        ],
        'lightbox-imagenes' => [
            'grupo' => 'administracion', 'rutas' => ['tours.*', 'estours.*', 'enblogs.*', 'esblogs.*'], 'permiso' => 'contenido.gestionar', 'solo' => true, 'titulo' => 'Visor de Portadas en Popup (Lightbox)',
            'resumen' => 'Inspección de fotografías en alta resolución directamente sobre las tablas de tours y blogs sin recargar la página.',
            'pasos' => [
                'Ubica la miniatura de portada en cualquier fila de la tabla de tours o blogs.',
                'Pasa el cursor para ver el icono de zoom y haz clic sobre la miniatura.',
                'Se abrirá un popup centrado con la foto ampliada, título del paquete y enlace al archivo original.',
                'Cierra el popup pulsando la tecla Esc o haciendo clic fuera del modal.',
            ],
            'notas' => [
                'Si mantienes presionada la tecla Ctrl (o Cmd) al hacer clic, la foto se abrirá en una nueva pestaña como enlace tradicional.',
            ],
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

    /**
     * Contexto detallado de la pantalla actual para vistas de alta gestión (Programas, Operaciones, Consultas, etc.).
     * Para vistas simples (como usuarios, agentes o categorías), retorna null para mantener la ayuda simple y directa.
     */
    public static function contextoPantalla(Request $request, User $user): ?array
    {
        if ($request->routeIs('programas.index', 'programas.por-periodo')) {
            return [
                'es_compleja' => true,
                'modulo' => 'Programas',
                'badge' => 'Gestión de Viajes',
                'icono' => 'fa-map-marked-alt',
                'titulo' => 'Catálogo General de Programas e Itinerarios',
                'resumen' => 'Centro de mando comercial y operativo de todos los viajes de Andean Exclusive. Supervisa cotizaciones activas, viajes confirmados, históricos y plantillas maestras.',
                'acciones_clave' => [
                    ['icono' => 'fa-plus', 'nombre' => '+ Nuevo Programa', 'desc' => 'Inicia una nueva cotización asignando cliente, fechas e idioma.'],
                    ['icono' => 'fa-search', 'nombre' => 'Búsqueda Global', 'desc' => 'Filtra al instante por nombre del viaje, titular, código AET, correo o nombre de pasajero.'],
                    ['icono' => 'fa-calendar-alt', 'nombre' => 'Año y Mes', 'desc' => 'Segmenta las salidas por temporada para coordinar reservas y anticipos con proveedores.'],
                    ['icono' => 'fa-ellipsis-v', 'nombre' => 'Menú de Acciones (⋮)', 'desc' => 'Abre el itinerario web del cliente, copia su enlace privado, genera el PDF de lujo, duplica el viaje o envíalo por correo.'],
                    ['icono' => 'fa-clone', 'nombre' => 'Plantillas y Duplicación', 'desc' => 'Reutiliza rutas exitosas para cotizar viajes recurrentes en menos de 1 minuto.'],
                ],
                'flujo_recomendado' => [
                    'Revisa las pestañas de estado: «Cotizaciones» para seguimiento de ventas y «Confirmados» para logística y reservas activas.',
                    'Usa el botón «Detalle» en cualquier fila para inspeccionar pasajeros, hoteles reservados y presupuesto sin salir de la lista.',
                    'Desde el menú ⋮ comparte el «Enlace privado» con el cliente: el viajero disfruta de una web interactiva sin ver costos internos ni márgenes.',
                ],
                'tips_pro' => [
                    'Al duplicar un viaje, las fechas de los servicios y hoteles se recalculan automáticamente; los pasajeros y pagos inician limpios para el nuevo cliente.',
                    'Los programas en estado «Cancelado» no se eliminan: conservan su bitácora histórica y liberan la fecha en la hoja de operaciones.',
                ],
            ];
        }

        if ($request->routeIs('programas.edit', 'programas.create')) {
            return [
                'es_compleja' => true,
                'modulo' => 'Ficha del Programa',
                'badge' => 'Diseño y Ficha Maestra',
                'icono' => 'fa-route',
                'titulo' => 'Ficha de Edición y Logística del Viaje',
                'resumen' => 'Espacio integral para diseñar, cotizar y coordinar cada aspecto del viaje. Cada pestaña está especializada para garantizar que ninguna reserva o detalle quede suelto.',
                'acciones_clave' => [
                    ['icono' => 'fa-save', 'nombre' => 'Guardar Cambios', 'desc' => 'Guarda las modificaciones de la pestaña actual. Los puntos rojos en las pestañas señalan datos requeridos por completar.'],
                    ['icono' => 'fa-link', 'nombre' => 'Copiar Enlace', 'desc' => 'Obtén el enlace web privado del cliente para enviárselo por WhatsApp o correo.'],
                    ['icono' => 'fa-file-pdf', 'nombre' => 'Exportar PDF', 'desc' => 'Descarga la propuesta imprimible de lujo con portada y narrativa día por día.'],
                    ['icono' => 'fa-envelope', 'nombre' => 'Enviar por Correo', 'desc' => 'Envía la cotización o recordatorios de pago oficiales al correo del cliente.'],
                    ['icono' => 'fa-check-double', 'nombre' => 'Aceptación Digital', 'desc' => 'Monitorea si el cliente ya aceptó los términos y el presupuesto desde su enlace web.'],
                ],
                'pestañas_guia' => [
                    'datos' => [
                        'nombre' => 'Datos Generales',
                        'icono' => 'fa-info-circle',
                        'desc' => 'Nombre comercial del tour, código, idioma, counter asignado y portada. Las fechas de inicio y fin son obligatorias para poder pasar el viaje a «Confirmado».',
                    ],
                    'pasajeros' => [
                        'nombre' => 'Pasajeros y Salud',
                        'icono' => 'fa-users',
                        'desc' => 'Nombres como en el pasaporte, fechas de nacimiento (calculan tarifa adulto/niño automáticamente), pasaportes cifrados, dietas y alergias críticas (ej. celiaquía o maní con Epipen).',
                    ],
                    'alojamiento' => [
                        'nombre' => 'Hoteles y Estadías',
                        'icono' => 'fa-hotel',
                        'desc' => 'Hoteles boutique y habitaciones reservadas. Cada estadía con fechas sincroniza automáticamente un servicio de alojamiento en el itinerario con su hotel como proveedor.',
                    ],
                    'itinerario' => [
                        'nombre' => 'Itinerario y Servicios',
                        'icono' => 'fa-list-ol',
                        'desc' => 'Narrativa día por día y servicios contratados: traslados VIP, trenes Hiram Bingham/Vistadome, entradas DDC Machu Picchu, guías oficiales y almuerzos de autor.',
                    ],
                    'precios' => [
                        'nombre' => 'Precios y Rentabilidad',
                        'icono' => 'fa-dollar-sign',
                        'desc' => 'Costos base por persona, porcentaje de ganancia comercial de la agencia, tipo de cambio y suplemento individual. El cliente solo verá el precio de venta final.',
                    ],
                    'pagos' => [
                        'nombre' => 'Cobros y Comunicaciones',
                        'icono' => 'fa-credit-card',
                        'desc' => 'Registro de transferencias y pagos con tarjeta. Control del depósito del 50% (7 días) y del saldo (60 días antes del viaje). Incluye los botones de correos sugeridos para hoy.',
                    ],
                    'tareas' => [
                        'nombre' => 'Tareas y Plazos',
                        'icono' => 'fa-tasks',
                        'desc' => 'Pendientes operativos con fechas límite automatizadas: compra de boletos de tren a 30 días, entradas a Machu Picchu a 30 días, confirmación de Sprinter a 7 días.',
                    ],
                    'historial' => [
                        'nombre' => 'Historial y Auditoría',
                        'icono' => 'fa-history',
                        'desc' => 'Registro cronológico inmutable de cambios: quién modificó precios, servicios, notas de pasajeros o estados del programa.',
                    ],
                ],
                'tips_pro' => [
                    'Regla de oro: Los boletos de ingreso a la Llaqta Machu Picchu y trenes panorámicos de alta demanda deben asegurarse con al menos 30 días de anticipación.',
                    'Si el pasaporte de algún pasajero vence en menos de 6 meses respecto a la fecha del viaje, el sistema mostrará una alerta amarilla para prevenir incidentes migratorios.',
                    'Al cambiar precios o itinerario de un programa aceptado, el sistema avisa para que el cliente pueda volver a reconfirmar la nueva versión desde su enlace privado.',
                ],
            ];
        }

        if ($request->routeIs('operaciones.diaria', 'operaciones.index')) {
            return [
                'es_compleja' => true,
                'modulo' => 'Operaciones',
                'badge' => 'Logística de Campo',
                'icono' => 'fa-calendar-day',
                'titulo' => 'Hoja de Operaciones Diarias',
                'resumen' => 'Supervisión en tiempo real de los servicios, traslados y hoteles de cada día. Conecta a los coordinadores de campo con los choferes, guías y requerimientos de los viajeros.',
                'acciones_clave' => [
                    ['icono' => 'fa-calendar', 'nombre' => 'Selector de Fecha', 'desc' => 'Navega día a día o salta a cualquier fecha futura para anticipar la operación.'],
                    ['icono' => 'fa-print', 'nombre' => 'Imprimir Hoja de Ruta', 'desc' => 'Exporta la planilla diaria limpia con horarios, puntos de encuentro y teléfonos de contacto para el equipo en calle.'],
                    ['icono' => 'fa-clock', 'nombre' => 'Por Confirmar (14 días)', 'desc' => 'Semáforo preventivo de servicios próximos que aún no cuentan con proveedor o número de confirmación.'],
                    ['icono' => 'fa-bed', 'nombre' => 'Rooming List', 'desc' => 'Genera el reporte de huéspedes agrupado por hotel para coordinar check-ins sin demoras.'],
                ],
                'flujo_recomendado' => [
                    '1. Empieza la mañana revisando los servicios de hoy y reconfirmando con los choferes y guías asignados.',
                    '2. Presta atención especial a los badges de dieta y alergia de los pasajeros de hoy para alertar a restaurantes y personal de servicio.',
                    '3. Revisa la columna lateral de servicios por confirmar en los próximos 14 días para evitar imprevistos de última hora.',
                ],
                'tips_pro' => [
                    'Solo los programas en estado «Confirmado» aparecen en la hoja de operaciones.',
                    'Los números de teléfono de proveedores y conductores en la hoja son interactivos: púlsalos desde el móvil para llamar o escribir directamente.',
                ],
            ];
        }

        if ($request->routeIs('consultas.*')) {
            return [
                'es_compleja' => true,
                'modulo' => 'CRM & Ventas',
                'badge' => 'Embudo de Clientes',
                'icono' => 'fa-inbox',
                'titulo' => 'Bandeja de Consultas Web y Cotizaciones',
                'resumen' => 'Gestión comercial de prospectos y leads internacionales. Transforma el interés inicial de un viajero en una propuesta formal de viaje en Andean Exclusive.',
                'acciones_clave' => [
                    ['icono' => 'fa-bolt', 'nombre' => 'Cotizar con 1 Clic', 'desc' => 'Convierte la consulta en un programa nuevo pre-cargado con el nombre del cliente, fechas solicitadas y tour de interés.'],
                    ['icono' => 'fa-whatsapp', 'nombre' => 'Contacto Directo', 'desc' => 'Inicia comunicación inmediata por WhatsApp o correo electrónico con el interesado.'],
                    ['icono' => 'fa-exchange-alt', 'nombre' => 'Bandejas del Embudo', 'desc' => 'Nuevas (sin atender) -> En gestión -> Cotizadas -> Ganadas o Perdidas.'],
                ],
                'flujo_recomendado' => [
                    '1. Atiende de inmediato las consultas en «Nuevas»: la rapidez de respuesta es el factor decisivo en turismo de lujo.',
                    '2. Tras el primer contacto, asigna un responsable, pasa el estado a «En gestión» y anota los gustos del cliente.',
                    '3. Al enviar la cotización, usa «Cotizar»: el sistema creará al cliente y el programa enlazados automáticamente.',
                ],
                'tips_pro' => [
                    'Si el cliente desiste, márcalo como «Perdida» e indica el motivo: esta información permite optimizar precios y rutas en los reportes comerciales.',
                ],
            ];
        }

        if ($request->routeIs('tarifas.*')) {
            return [
                'es_compleja' => true,
                'modulo' => 'Tarifario',
                'badge' => 'Costos Oficiales',
                'icono' => 'fa-tags',
                'titulo' => 'Tarifario Oficial por Temporadas',
                'resumen' => 'Catálogo centralizado de tarifas negociadas con hoteles y proveedores (trenes, guías, entradas y alta gastronomía). Alimenta las sugerencias automáticas al cotizar.',
                'acciones_clave' => [
                    ['icono' => 'fa-calendar-check', 'nombre' => 'Filtro de Vigencia', 'desc' => 'Consulta tarifas vigentes hoy, vencidas o el histórico general.'],
                    ['icono' => 'fa-search', 'nombre' => 'Búsqueda por Concepto', 'desc' => 'Localiza tarifas por hotel, tipo de habitación, tren o proveedor oficial.'],
                    ['icono' => 'fa-plus', 'nombre' => 'Nueva Tarifa', 'desc' => 'Registra precios por noche, por grupo o por persona en dólares (USD) o soles (PEN).'],
                ],
                'flujo_recomendado' => [
                    '1. Carga las tarifas de la nueva temporada con anticipación indicando fecha desde y hasta.',
                    '2. Al armar un itinerario o una estadía de hotel, el sistema tomará estas tarifas como costo sugerido.',
                ],
                'tips_pro' => [
                    'Las tarifas de habitaciones de hotel vinculan directamente con su ficha de hotel para cálculo automático en la pestaña Alojamiento.',
                ],
            ];
        }

        if ($request->routeIs('cuentas.*')) {
            return [
                'es_compleja' => true,
                'modulo' => 'Finanzas',
                'badge' => 'Cobros y Pagos',
                'icono' => 'fa-file-invoice-dollar',
                'titulo' => 'Control de Cobranzas y Cuentas por Pagar',
                'resumen' => 'Supervisión del flujo de caja de la agencia: cobranzas a clientes (depósitos y saldos) y obligaciones de pago con proveedores y hoteles.',
                'acciones_clave' => [
                    ['icono' => 'fa-hand-holding-usd', 'nombre' => 'Cuentas por Cobrar', 'desc' => 'Monitorea los depósitos del 50% y los saldos pendientes con fecha de vencimiento a 60 días antes del viaje.'],
                    ['icono' => 'fa-money-bill-wave', 'nombre' => 'Cuentas por Pagar', 'desc' => 'Gestiona las solicitudes de pago de servicios y proveedores aprobados por Reservas.'],
                ],
                'flujo_recomendado' => [
                    '1. Revisa semanalmente los cobros con saldo por vencer en los próximos 30 días para enviar recordatorios a tiempo.',
                    '2. En Cuentas por pagar, atiende primero los servicios con estado «Pago solicitado» aprobados por el área de Reservas.',
                ],
                'tips_pro' => [
                    'Los pagos nunca se eliminan directamente: se anulan con motivo justificado para preservar la integridad contable.',
                ],
            ];
        }

        if ($request->routeIs('tours.*', 'estours.*')) {
            $isEs = $request->routeIs('estours.*');
            return [
                'es_compleja' => true,
                'modulo' => 'Catálogo Web',
                'badge' => $isEs ? 'Tours en Español' : 'Tours en Inglés',
                'icono' => 'fa-map-marked-alt',
                'titulo' => 'Gestión de Tours y Paquetes Turísticos',
                'resumen' => 'Administración integral de itinerarios, tarifas de venta, portadas multimedia, categorías y vinculación multilingüe para el sitio web.',
                'acciones_clave' => [
                    ['icono' => 'fa-plus', 'nombre' => '+ Nuevo Tour', 'desc' => 'Crea un paquete turístico con editor en 2 columnas, panel sticky lateral y selector visual de categorías.'],
                    ['icono' => 'fa-search-plus', 'nombre' => 'Popup de Portada (Lightbox)', 'desc' => 'Haz clic en la foto de cualquier tour en la lista para verla en pantalla completa sin salir de la vista.'],
                    ['icono' => 'fa-language', 'nombre' => 'Vinculación EN / ES', 'desc' => 'Enlaza el tour con su traducción al otro idioma para sincronizar la experiencia del viajero en la web.'],
                    ['icono' => 'fa-external-link-alt', 'nombre' => 'Edición Rápida en la Web', 'desc' => 'Como administrador logueado, verás un botón flotante en la web pública para editar cualquier tour al instante.'],
                    ['icono' => 'fa-tags', 'nombre' => 'Categorías', 'desc' => 'Gestiona las colecciones temáticas (Aventura, Lujo, Machu Picchu) desde el acceso superior.'],
                ],
                'flujo_recomendado' => [
                    '1. Completa los datos generales (nombre, duración, precio por persona, recorrido y descripción corta).',
                    '2. Redacta el itinerario día por día, inclusiones y exclusiones usando el editor de texto enriquecido.',
                    '3. En el panel lateral, sube la foto de banner (Full HD) y la miniatura optimizada mediante la zona de carga.',
                    '4. Asigna las categorías correspondientes y selecciona el tour equivalente en el otro idioma antes de guardar.',
                ],
                'tips_pro' => [
                    'Sube siempre imágenes en formato horizontal y alta resolución (mínimo 1200x800 px) para que el banner luzca impecable en móviles y pantallas retina.',
                    'Al hacer clic en la miniatura de la tabla, se abrirá un popup centrado para verificar la calidad de la foto sin recargar la página.',
                ],
            ];
        }

        if ($request->routeIs('enblogs.*', 'esblogs.*')) {
            $isEs = $request->routeIs('esblogs.*');
            return [
                'es_compleja' => true,
                'modulo' => 'Blog Editorial',
                'badge' => $isEs ? 'Blog en Español' : 'Blog en Inglés',
                'icono' => 'fa-newspaper',
                'titulo' => 'Gestión Editorial y Artículos de Blog',
                'resumen' => 'Creación y curación de artículos de viaje, cultura y gastronomía peruana para captar prospectos internacionales y fortalecer el SEO.',
                'acciones_clave' => [
                    ['icono' => 'fa-plus', 'nombre' => '+ Nuevo Artículo', 'desc' => 'Redacta un nuevo post con editor enriquecido, metadatos SEO y selección de tags.'],
                    ['icono' => 'fa-search-plus', 'nombre' => 'Popup de Portada (Lightbox)', 'desc' => 'Inspecciona la foto de portada de cualquier artículo en un popup centrado desde el listado.'],
                    ['icono' => 'fa-hashtag', 'nombre' => 'Etiquetas (Tags)', 'desc' => 'Clasifica los artículos por temática (#Traditions, #Gastronomy, #History) para facilitar la navegación.'],
                    ['icono' => 'fa-language', 'nombre' => 'Conexión Bilingüe', 'desc' => 'Vincula cada artículo con su versión en el idioma opuesto.'],
                ],
                'flujo_recomendado' => [
                    '1. Redacta el contenido principal estructurándolo con subtítulos (H2, H3) para una lectura ágil.',
                    '2. Completa los campos SEO: palabras clave y descripción corta atractiva para el buscador de Google.',
                    '3. Selecciona los tags relevantes y sube la imagen de portada.',
                    '4. Guarda y verifica cómo luce usando el acceso de previsualización pública.',
                ],
                'tips_pro' => [
                    'La vista pública calcula automáticamente el tiempo de lectura basándose en la cantidad de palabras del artículo.',
                    'Los administradores pueden editar cualquier post directamente desde la web pública con el botón flotante inferior.',
                ],
            ];
        }

        if ($request->routeIs('categories.*', 'categorias.*', 'entags.*', 'estags.*')) {
            $isTags = $request->routeIs('entags.*', 'estags.*');
            $isEs = $request->routeIs('categorias.*', 'estags.*');
            return [
                'es_compleja' => true,
                'modulo' => 'Taxonomías',
                'badge' => $isTags ? ($isEs ? 'Tags ES' : 'Tags EN') : ($isEs ? 'Categorías ES' : 'Categorías EN'),
                'icono' => $isTags ? 'fa-hashtag' : 'fa-tags',
                'titulo' => $isTags ? 'Gestión de Etiquetas Temáticas (Tags)' : 'Gestión de Categorías de Tours',
                'resumen' => 'Organización y clasificación del catálogo público. Define las colecciones temáticas y páginas de aterrizaje automáticas.',
                'acciones_clave' => [
                    ['icono' => 'fa-plus', 'nombre' => '+ Crear Nuevo', 'desc' => 'Registra una nueva taxonomía con generador automático de slug y previsualizador de chip.'],
                    ['icono' => 'fa-chart-pie', 'nombre' => 'KPIs y Métricas', 'desc' => 'Supervisa en tiempo real cuántas categorías o tags tienen contenido asignado en la web.'],
                    ['icono' => 'fa-link', 'nombre' => 'Slug Inteligente', 'desc' => 'Autogenera la URL canónica amigable y permite desbloquearla con candado si deseas personalizarla.'],
                    ['icono' => 'fa-search', 'nombre' => 'Buscador Reactivo', 'desc' => 'Filtra al instante en la tabla por nombre o slug sin recargar la página.'],
                ],
                'flujo_recomendado' => [
                    '1. Escribe el nombre deseado: el slug y la URL canónica se crearán en tiempo real.',
                    '2. Observa la caja de previsualización en vivo para confirmar la apariencia del chip.',
                    '3. Guarda y asigna la categoría o tag desde el editor de tours o blogs correspondiente.',
                ],
                'tips_pro' => [
                    'Cada categoría y tag genera automáticamente una página pública con su colección filtrada de experiencias.',
                    'Eliminar una categoría o tag no borra los tours o blogs vinculados; solo retira la asociación.',
                ],
            ];
        }

        return null;
    }
}
