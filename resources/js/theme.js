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

const REVEAL_MS = 650;

function setTheme(theme) {
    document.documentElement.setAttribute('data-bs-theme', theme);
    syncControls(theme);
}

// origin: elemento desde el que se expande el tema nuevo (el botón pulsado).
function apply(theme, animate = false, origin = null) {
    const root = document.documentElement;
    const finalTheme = theme === 'light' ? 'light' : 'dark';
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!animate || reduceMotion) {
        setTheme(finalTheme);
        return;
    }

    // Sin View Transitions: fundido de colores en toda la página.
    if (!document.startViewTransition) {
        root.classList.add('ae-theme-switching');
        setTheme(finalTheme);
        window.setTimeout(() => root.classList.remove('ae-theme-switching'), 450);
        return;
    }

    // Con View Transitions: el tema nuevo se revela en un círculo que crece desde el botón.
    const rect = origin?.getBoundingClientRect();
    const x = rect ? rect.left + rect.width / 2 : window.innerWidth - 40;
    const y = rect ? rect.top + rect.height / 2 : 40;
    const radius = Math.hypot(Math.max(x, window.innerWidth - x), Math.max(y, window.innerHeight - y));

    root.classList.add('ae-theme-revealing');
    const transition = document.startViewTransition(() => setTheme(finalTheme));
    transition.ready.then(() => {
        root.animate(
            { clipPath: [`circle(0px at ${x}px ${y}px)`, `circle(${radius}px at ${x}px ${y}px)`] },
            { duration: REVEAL_MS, easing: 'cubic-bezier(0.65, 0, 0.35, 1)', pseudoElement: '::view-transition-new(root)' },
        );
    });
    transition.finished.finally(() => root.classList.remove('ae-theme-revealing'));
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
            apply(theme, true, item);
            return;
        }

        // Toggle directo si se hace clic en un botón con data-theme-toggle
        const toggleBtn = event.target.closest('[data-theme-toggle]');
        if (toggleBtn) {
            const nextTheme = stored() === 'dark' ? 'light' : 'dark';
            try {
                localStorage.setItem(STORAGE_KEY, nextTheme);
            } catch {}
            apply(nextTheme, true, toggleBtn);
        }
    });
}
