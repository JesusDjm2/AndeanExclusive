// Tema del panel: exclusivamente 'light' o 'dark'. Se guarda en localStorage.
// El script en el <head> (layouts/app.blade.php) aplica data-bs-theme antes de pintar para evitar destellos.

const STORAGE_KEY = 'ae-theme';
const ICONS = { light: 'fa-sun', dark: 'fa-moon' };

const stored = () => {
    try {
        const val = localStorage.getItem(STORAGE_KEY);
        return val === 'light' ? 'light' : 'dark';
    } catch {
        return 'dark';
    }
};

function apply(theme, animate = false) {
    const root = document.documentElement;
    const finalTheme = theme === 'light' ? 'light' : 'dark';
    if (animate) {
        root.classList.add('ae-theme-switching');
        window.setTimeout(() => root.classList.remove('ae-theme-switching'), 300);
    }
    root.setAttribute('data-bs-theme', finalTheme);
    syncControls(finalTheme);
}

function syncControls(theme) {
    document.querySelectorAll('[data-theme-value]').forEach((item) => {
        const active = item.dataset.themeValue === theme;
        item.classList.toggle('active', active);
        item.setAttribute('aria-pressed', String(active));
    });
    // El botón muestra el tema al que se pasa con un clic.
    const next = theme === 'dark' ? 'light' : 'dark';
    document.querySelectorAll('[data-theme-icon]').forEach((icon) => {
        icon.className = `fas ${ICONS[next]}`;
    });
    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
        const label = `Cambiar a tema ${next === 'light' ? 'claro' : 'oscuro'}`;
        btn.setAttribute('aria-label', label);
        btn.title = label;
    });
}

export function initTheme() {
    const currentTheme = stored();
    apply(currentTheme, false);

    document.addEventListener('click', (event) => {
        const item = event.target.closest('[data-theme-value]');
        if (item) {
            const theme = item.dataset.themeValue === 'light' ? 'light' : 'dark';
            try {
                localStorage.setItem(STORAGE_KEY, theme);
            } catch {
                // Modo privado
            }
            apply(theme, true);
            return;
        }

        // Toggle directo si se hace clic en un botón con data-theme-toggle
        const toggleBtn = event.target.closest('[data-theme-toggle]');
        if (toggleBtn) {
            const nextTheme = stored() === 'dark' ? 'light' : 'dark';
            try {
                localStorage.setItem(STORAGE_KEY, nextTheme);
            } catch {}
            apply(nextTheme, true);
        }
    });
}
