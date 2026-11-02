/**
 * Alternar visibilidad de contraseña (mostrar / ocultar)
 * Funciona con cualquier botón que tenga [data-toggle-password] o clase .btn-toggle-password.
 */
export function initPasswordToggle() {
    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-toggle-password], .btn-toggle-password');
        if (!button) return;

        event.preventDefault();

        // 1. Localizar el input objetivo
        const targetSelector = button.dataset.togglePassword;
        let input = null;

        if (targetSelector) {
            input = document.querySelector(targetSelector);
        }

        if (!input) {
            const container = button.closest('.input-group') || button.parentElement;
            input = container ? container.querySelector('input[type="password"], input[type="text"]') : null;
        }

        if (!input) return;

        // 2. Alternar entre 'password' y 'text'
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';

        // 3. Alternar icono
        const icon = button.querySelector('i');
        if (icon) {
            if (isPassword) {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // 4. Actualizar accesibilidad y tooltip
        const label = isPassword ? 'Ocultar contraseña' : 'Mostrar contraseña';
        button.setAttribute('aria-label', label);
        button.setAttribute('title', label);
        button.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
    });
}
