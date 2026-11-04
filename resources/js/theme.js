// Tema del panel: 'light' | 'dark' | 'auto' (sigue al sistema operativo). Se guarda por navegador en localStorage.
// El tema inicial lo aplica un script mínimo en el <head> (layouts/app.blade.php) antes de pintar, para evitar el destello.

const STORAGE_KEY = 'ae-theme';
const media = window.matchMedia('(prefers-color-scheme: dark)');
const ICONS = { light: 'fa-sun', dark: 'fa-moon', auto: 'fa-adjust' };

const stored = () => {
    try {
        return localStorage.getItem(STORAGE_KEY) || 'auto';
    } catch {
        return 'auto';
    }
};

const resolve = (theme) => (theme === 'auto' ? (media.matches ? 'dark' : 'light') : theme);

function apply(theme, animate = false) {
    const root = document.documentElement;
    if (animate) {
        root.classList.add('ae-theme-switching');
        window.setTimeout(() => root.classList.remove('ae-theme-switching'), 300);
    }
    root.setAttribute('data-bs-theme', resolve(theme));
    syncControls(theme);
}

function syncControls(theme) {
    document.querySelectorAll('[data-theme-value]').forEach((item) => {
        const active = item.dataset.themeValue === theme;
        item.classList.toggle('active', active);
        item.setAttribute('aria-pressed', String(active));
    });
    document.querySelectorAll('[data-theme-icon]').forEach((icon) => {
        icon.className = `fas ${ICONS[theme] ?? ICONS.auto}`;
    });
}

export function initTheme() {
    syncControls(stored());

    document.addEventListener('click', (event) => {
        const item = event.target.closest('[data-theme-value]');
        if (!item) return;
        const theme = item.dataset.themeValue;
        try {
            localStorage.setItem(STORAGE_KEY, theme);
        } catch {
            // Sin almacenamiento (modo privado): el cambio vale para esta página.
        }
        apply(theme, true);
    });

    // En modo "Sistema", seguir los cambios del sistema operativo en vivo.
    media.addEventListener('change', () => {
        if (stored() === 'auto') apply('auto', true);
    });
}
