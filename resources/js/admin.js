// Panel de administración (layouts/app.blade.php).
// Bootstrap 5 (una sola versión) + comportamiento propio del shell.
import * as bootstrap from 'bootstrap';
import { initProgramaForm } from './programa-form';
import { initClienteCampos } from './cliente-campos';
import { initTheme } from './theme';
import { initPdfPreview } from './pdf-preview';
import { initImagePreview } from './image-preview';
import { initServicioAcciones } from './servicio-acciones';
import { initAvisos } from './avisos';
import { initAyuda } from './ayuda';
import { initTarifas } from './tarifas';
import { initPasswordToggle } from './password-toggle';
import { initProgramaHover } from './programa-hover';
import { initAuthVideo } from './auth-video';
import { initPreloader } from './preloader';
import { initAdminConstellation } from './admin-constellation';

window.bootstrap = bootstrap;

// Cerrar sesión con confirmación (SweetAlert2 se carga en el layout; si falla, confirm nativo).
function initLogout() {
    document.querySelectorAll('[data-logout]').forEach((button) => {
        button.addEventListener('click', async (event) => {
            event.preventDefault();
            const form = document.getElementById(button.dataset.logout);
            if (!form) return;

            if (!window.Swal) {
                if (window.confirm('¿Deseas cerrar sesión?')) form.submit();
                return;
            }

            const result = await window.Swal.fire({
                title: '¿Cerrar sesión?',
                text: 'Podrás volver a ingresar cuando quieras.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Seguir conectado',
                confirmButtonColor: '#0f172a',
                reverseButtons: true,
            });
            if (result.isConfirmed) form.submit();
        });
    });
}

// Al navegar desde el menú en móvil/tablet, cerrar el panel lateral.
function initSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    if (!sidebar) return;

    sidebar.querySelectorAll('a[href]').forEach((link) => {
        link.addEventListener('click', () => {
            bootstrap.Offcanvas.getInstance(sidebar)?.hide();
        });
    });
}

// Las alertas de éxito se cierran solas tras unos segundos (las de error permanecen).
function initAutoDismissAlerts() {
    document.querySelectorAll('.admin-content .alert-success.alert-dismissible').forEach((alert) => {
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(alert).close(), 6000);
    });
}

// <form data-confirm="Texto" [data-confirm-title] [data-confirm-button] [data-confirm-danger] [data-confirm-motivo]>:
// pide confirmación antes de enviar (eliminar, enviar correo...). Sin SweetAlert, usa confirm nativo.
// Con data-confirm-motivo exige además escribir el motivo, que se envía en el campo "motivo".
function initConfirmForms() {
    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('form[data-confirm]');
        if (!form || form.dataset.confirmed === 'true') return;

        event.preventDefault();
        const { confirm: text, confirmTitle, confirmButton } = form.dataset;
        const danger = form.hasAttribute('data-confirm-danger');
        const pideMotivo = form.hasAttribute('data-confirm-motivo');
        const MINIMO = 5;

        let ok;
        let motivo = '';
        if (window.Swal) {
            const result = await window.Swal.fire({
                title: confirmTitle || '¿Confirmar?',
                text,
                icon: danger ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonText: confirmButton || 'Confirmar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: danger ? '#dc2626' : '#0f172a',
                reverseButtons: true,
                focusCancel: danger && !pideMotivo,
                ...(pideMotivo && {
                    input: 'textarea',
                    inputLabel: 'Motivo de la anulación',
                    inputPlaceholder: 'Ej.: pago duplicado, monto equivocado…',
                    inputAttributes: { maxlength: 255, 'aria-label': 'Motivo de la anulación' },
                    inputValidator: (valor) => (valor.trim().length < MINIMO ? 'Explica el motivo: quedará registrado.' : undefined),
                    // Dentro de un modal de Bootstrap: si no, el modal le quita el foco al campo y no se puede escribir.
                    target: form.closest('.modal') || 'body',
                }),
            });
            ok = result.isConfirmed;
            motivo = result.value ?? '';
        } else if (pideMotivo) {
            motivo = window.prompt(`${text}\n\nMotivo de la anulación:`) ?? '';
            ok = motivo.trim().length >= MINIMO;
        } else {
            ok = window.confirm(text);
        }

        if (ok) {
            if (pideMotivo) {
                const campo = form.querySelector('input[name="motivo"]') ?? form.appendChild(Object.assign(document.createElement('input'), { type: 'hidden', name: 'motivo' }));
                campo.value = motivo.trim();
            }
            form.dataset.confirmed = 'true';
            form.requestSubmit ? form.requestSubmit() : form.submit();
        }
    });
}

// <button data-copy="texto">: copia al portapapeles y avisa con un toast.
function initCopyButtons() {
    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-copy]');
        if (!button) return;

        try {
            await navigator.clipboard.writeText(button.dataset.copy);
            const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            window.Swal?.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: 'Enlace copiado',
                showConfirmButton: false,
                timer: 2000,
                background: isDark ? '#0f172a' : '#ffffff',
                color: isDark ? '#f8fafc' : '#0f172a',
                iconColor: isDark ? '#34d399' : '#10b981',
                customClass: {
                    popup: 'ae-copy-toast',
                    title: 'ae-copy-toast__title'
                }
            });
        } catch {
            window.prompt('Copia el enlace:', button.dataset.copy);
        }
    });
}

// <select data-autosubmit>: aplica el filtro al cambiar.
function initAutoSubmit() {
    document.addEventListener('change', (event) => {
        const select = event.target.closest('[data-autosubmit]');
        if (select) select.form?.submit();
    });
}

// <button data-print>: imprime la página (hoja de operaciones).
function initPrintButtons() {
    document.addEventListener('click', (event) => {
        if (event.target.closest('[data-print]')) window.print();
    });
}

// Modal Rápido de Historial / Bitácora del Grupo en 1 clic
function initHistorialModal(bootstrap) {
    const modalEl = document.getElementById('modal-historial-programa');
    if (!modalEl || !bootstrap?.Modal) return;

    const modalTitle = document.getElementById('modal-historial-title');
    const modalSubtitle = document.getElementById('modal-historial-subtitle');
    const modalBody = document.getElementById('modal-historial-body');
    const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);

    document.addEventListener('click', async (event) => {
        const btn = event.target.closest('[data-historial-modal]');
        if (!btn) return;

        event.preventDefault();

        const nombre = btn.dataset.programaNombre || 'Programa';
        const codigo = btn.dataset.programaCodigo || '';
        const url = btn.dataset.historialUrl;

        if (modalTitle) {
            modalTitle.textContent = codigo ? `[${codigo}] ${nombre}` : nombre;
        }
        if (modalSubtitle) {
            modalSubtitle.textContent = 'Historial y bitácora completa de movimientos del grupo';
        }

        if (modalBody) {
            modalBody.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="text-muted mt-2 small">Cargando bitácora del grupo...</p>
                </div>
            `;
        }

        bsModal.show();

        if (!url) return;

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-Modal-Request': '1'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const html = await response.text();
            if (modalBody) {
                modalBody.innerHTML = html;
            }
        } catch (error) {
            console.error('Error cargando historial de programa:', error);
            if (modalBody) {
                modalBody.innerHTML = `
                    <div class="alert alert-danger d-flex align-items-center mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>No se pudo cargar el historial del grupo. Intenta nuevamente o ábrelo desde edición.</div>
                    </div>
                `;
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initPreloader();
    initTheme();
    initPdfPreview(bootstrap);
    initImagePreview(bootstrap);
    initServicioAcciones(bootstrap);
    initAvisos();
    initAyuda();
    initTarifas();
    initPasswordToggle();
    initProgramaHover(bootstrap);
    initLogout();
    initSidebar();
    initAutoDismissAlerts();
    initConfirmForms();
    initCopyButtons();
    initPrintButtons();
    initAutoSubmit();
    initProgramaForm();
    initClienteCampos();
    initAuthVideo();
    initHistorialModal(bootstrap);
    initAdminConstellation();
});
