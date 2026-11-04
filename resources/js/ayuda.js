// Ayuda del panel: botón "?" de cada pantalla, buscador del centro de ayuda y bienvenida en Inicio.

// En la ficha del programa, el botón "?" abre lo que explica la pestaña que estás viendo.
function initPanel() {
    const panel = document.querySelector('[data-ayuda-panel]');
    if (!panel) return;

    function syncPestana(pestana) {
        if (!pestana) return;

        // Actualizar tarjeta destacada de pestaña activa
        const cardNombre = panel.querySelector('[data-ayuda-pestana-nombre]');
        const cardDesc = panel.querySelector('[data-ayuda-pestana-desc]');
        const info = panel.querySelector(`[data-tab-info="${pestana}"]`);
        if (info && cardNombre && cardDesc) {
            cardNombre.textContent = info.dataset.tabName || pestana;
            cardDesc.textContent = info.dataset.tabDesc || '';
        }

        const temas = [...panel.querySelectorAll('details')];
        const deLaPestana = temas.filter((tema) => tema.dataset.ayudaTab === pestana);
        if (!deLaPestana.length) return;

        const propio = (tema) => 'ayudaPropio' in tema.dataset;
        // Si la pestaña es de otra área, abre la explicación general de la ficha en modo consulta.
        const general = temas.find((tema) => propio(tema) && !tema.dataset.ayudaTab);
        const abrir = deLaPestana.find(propio) ?? general ?? deLaPestana[0];
        temas.forEach((tema) => {
            tema.open = tema === abrir;
        });

        // Contenedor donde se ordenan los temas
        const contenedor = panel.querySelector('.ayuda-temas-contenedor') || panel.querySelector('.offcanvas-body');
        if (contenedor) {
            [...deLaPestana].reverse().forEach((tema) => contenedor.prepend(tema));
            if (abrir) contenedor.prepend(abrir);
        }
    }

    panel.addEventListener('show.bs.offcanvas', () => {
        const pestana = document.querySelector('.pf-tab.active')?.dataset.tab;
        syncPestana(pestana);
    });

    // Sincronizar en tiempo real si el usuario cambia de pestaña con el panel de ayuda abierto
    document.addEventListener('click', (e) => {
        const tabBtn = e.target.closest('.pf-tab');
        if (tabBtn && tabBtn.dataset.tab && panel.classList.contains('show')) {
            syncPestana(tabBtn.dataset.tab);
        }
    });
}

const normalizar = (texto) => texto.normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();

function initBuscador() {
    const campo = document.querySelector('[data-ayuda-buscar]');
    if (!campo) return;

    const items = [...document.querySelectorAll('[data-ayuda-item]')].map((elemento) => ({ elemento, texto: normalizar(`${elemento.textContent} ${elemento.dataset.ayudaTexto ?? ''}`) }));
    const grupos = [...document.querySelectorAll('[data-ayuda-grupo]')];
    const vacio = document.querySelector('[data-ayuda-vacio]');
    const guias = document.querySelector('[data-ayuda-guias]');

    campo.addEventListener('input', () => {
        const palabras = normalizar(campo.value).split(/\s+/).filter(Boolean);
        const buscando = palabras.length > 0;
        let encontrados = 0;

        items.forEach(({ elemento, texto }) => {
            const coincide = palabras.every((palabra) => texto.includes(palabra));
            elemento.hidden = !coincide;
            elemento.open = buscando && coincide;
            if (coincide) encontrados += 1;
        });
        grupos.forEach((grupo) => {
            grupo.hidden = !grupo.querySelector('[data-ayuda-item]:not([hidden])');
        });
        guias.hidden = buscando;
        vacio.hidden = encontrados > 0;
    });
}

// Un enlace a /ayuda#tema-... abre ese tema.
function initEnlaceATema() {
    const tema = window.location.hash.startsWith('#tema-') && document.querySelector(window.location.hash);
    if (tema instanceof HTMLDetailsElement) tema.open = true;
}

// Se muestra hasta que la persona la cierra (recordado en este navegador).
function initBienvenida() {
    const aviso = document.querySelector('[data-ayuda-bienvenida]');
    if (!aviso) return;

    const CLAVE = 'ae-ayuda-bienvenida';
    let cerrada = false;
    try {
        cerrada = localStorage.getItem(CLAVE) === '1';
    } catch {
        // Sin almacenamiento disponible: se muestra igual.
    }
    aviso.hidden = cerrada;

    aviso.querySelector('[data-ayuda-bienvenida-cerrar]')?.addEventListener('click', () => {
        aviso.hidden = true;
        try {
            localStorage.setItem(CLAVE, '1');
        } catch {
            // Volverá a mostrarse en la próxima visita.
        }
    });
}

export function initAyuda() {
    initPanel();
    initBuscador();
    initEnlaceATema();
    initBienvenida();
}
